<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use App\Models\Property;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Illuminate\Support\Facades\Cache;
use App\Services\PaymentNotificationService;

class PaymentController extends Controller
{
    protected $paymentNotificationService;

    public function __construct(PaymentNotificationService $paymentNotificationService)
    {
        $this->paymentNotificationService = $paymentNotificationService;
        $this->middleware(['auth', 'verified']);
        $this->middleware('throttle:6,1')->only(['store', 'create']); // Rate limit payment attempts
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = Payment::with(['transaction.property']);

            if (auth()->user()->role === 'admin') {
                if ($request->filled('global')) {
                    $search = $request->global;
                    $query->where(function ($q) use ($search) {
                        $q->where('id', $search)
                            ->orWhere('transaction_id', $search)
                            ->orWhere('payment_method', 'LIKE', "%{$search}%")
                            ->orWhere('status', 'LIKE', "%{$search}%");
                    });
                }
            } elseif (auth()->user()->role === 'agent') {
                $user = auth()->user();
                if ($user->properties) {
                    $propertyIds = auth()->user()->properties->pluck('id');
                    $query->whereHas('transaction', function ($q) use ($propertyIds) {
                        $q->whereIn('property_id', $propertyIds);
                    });
                }
            } else {
                $query->whereHas('transaction', function ($q) {
                    $q->where('user_id', auth()->id());
                });
            }

            if ($request->filled('sortBy')) {
                $direction = $request->sortBy === 'true' ? 'desc' : 'asc';
                $query->orderBy($request->sortBy, $direction);
            } else {
                $query->latest('created_at');
            }

            $perPage = $request->perPage ?? 10;
            $page = $request->page ?? 1;

            $payments = $query->paginate($perPage, ['*'], 'page', $page)
                ->appends($request->all());

            return Inertia::render('Dashboard/Payment/Index', [
                'payment' => $payments,
            ]);
        } catch (\Exception $e) {
            Log::error('Payment index error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load payments. Please try again later.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        try {
            $request->validate([
                'property_id' => 'required|exists:properties,id',
                'type' => 'required|in:rent,buy',
            ]);

            // Check if user has already made a payment for this property
            $existingPayment = Payment::whereHas('transaction', function ($query) use ($request) {
                $query->where('property_id', $request->property_id)
                    ->where('user_id', auth()->id())
                    ->where('status', 'completed');
            })->exists();

            if ($existingPayment) {
                return redirect()->back()->with('error', 'You have already made a payment for this property.');
            }

            DB::beginTransaction();

            $user = auth()->user();
            $propertyId = $request->input('property_id');
            $type = strtolower($request->input('type'));

            $transaction = Transaction::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'property_id' => $propertyId,
                    'transaction_type' => $type,
                    'status' => 'pending',
                ]
            );

            $transaction->load([
                'property',
                'property.agent',
                'property.agent.user',
                'property.category',
                'user'
            ]);

            $property = $transaction->property;
            $agent = $property->agent->user;

            // Initialize Stripe
            Stripe::setApiKey(config('services.stripe.secret'));

            // Create PaymentIntent
            $intent = \Stripe\PaymentIntent::create([
                'amount' => intval($type === 'buy' ? $property->purchase_price : $property->rent_price) * 100,
                'currency' => 'usd',
                'metadata' => [
                    'transaction_id' => $transaction->id,
                    'property_id' => $property->id,
                    'user_id' => $user->id
                ],
                'receipt_email' => $user->email,
            ]);

            DB::commit();

            return Inertia::render('Dashboard/PropertyPaymentForm', [
                'transaction' => $transaction,
                'property' => $property,
                'agent' => $agent,
                'type' => $transaction->transaction_type,
                'price' => $type === 'buy' ? $property->purchase_price : $property->rent_price,
                'stripeKey' => config('services.stripe.key'),
                'clientSecret' => $intent->client_secret,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Payment create failed: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'property_id' => $request->property_id,
                'type' => $request->type,
            ]);
            return back()->withErrors(['error' => 'Failed to initialize payment. Please try again later.']);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePaymentRequest $request)
    {
        try {
            DB::beginTransaction();

            $transaction = Transaction::with('property')->findOrFail($request->transaction_id);

            // Verify transaction belongs to user
            if ($transaction->user_id !== auth()->id()) {
                throw new \Exception('Unauthorized transaction access');
            }

            // Verify amount matches property price
            $expectedAmount = $transaction->transaction_type === 'buy'
                ? $transaction->property->purchase_price
                : $transaction->property->rent_price;

            if ($request->amount != $expectedAmount) {
                throw new \Exception('Invalid payment amount');
            }

            // Verify Stripe payment
            Stripe::setApiKey(config('services.stripe.secret'));
            $paymentIntent = \Stripe\PaymentIntent::retrieve($request->stripe_payment_id);

            if ($paymentIntent->status !== 'succeeded') {
                throw new \Exception('Payment not completed');
            }

            // Create payment record
            $payment = Payment::create([
                'transaction_id' => $transaction->id,
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'stripe_payment_id' => $request->stripe_payment_id,
                'status' => 'completed',
            ]);

            // Update transaction status
            $transaction->update(['status' => 'completed']);

            // Send notifications
            $this->paymentNotificationService->notifyPaymentReceived($payment);

            DB::commit();

            return redirect()->route('payment.show', $payment->id)
                ->with('success', 'Payment completed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment store failed: ' . $e->getMessage(), [
                'transaction_id' => $request->transaction_id,
                'amount' => $request->amount,
            ]);
            return redirect()->back()->withErrors(['error' => 'Payment processing failed. Please try again later.']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        // Verify user has access to this payment
        if (
            auth()->user()->role !== 'admin' &&
            $payment->transaction->user_id !== auth()->id()
        ) {
            abort(403);
        }

        return Inertia::render('Dashboard/Payment/Show', [
            'payment' => $payment->load(['transaction.property', 'transaction.user'])
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePaymentRequest $request, Payment $payment)
    {
        // Only admin can update payment status
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $payment->update([
            'status' => $request->status,
        ]);

        return response()->json(['message' => 'Payment status updated', 'payment' => $payment]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        // Only admin can delete payments
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $payment->delete();
        return response()->json(['message' => 'Payment deleted successfully']);
    }
}
