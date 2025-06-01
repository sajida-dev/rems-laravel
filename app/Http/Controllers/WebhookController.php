<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;
use App\Models\Payment;
use App\Models\Transaction;
use App\Services\PaymentNotificationService;

class WebhookController extends Controller
{
    protected $paymentNotificationService;

    public function __construct(PaymentNotificationService $paymentNotificationService)
    {
        $this->paymentNotificationService = $paymentNotificationService;
    }

    public function handleStripeWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $mode = config('services.stripe.mode');

        try {
            $event = Webhook::constructEvent(
                $payload,
                $sig_header,
                config('services.stripe.webhook_secret')
            );

            // Verify the event is from the correct environment
            if ($event->livemode !== ($mode === 'live')) {
                Log::warning('Webhook event environment mismatch', [
                    'event_mode' => $event->livemode ? 'live' : 'test',
                    'app_mode' => $mode
                ]);
                return response()->json(['error' => 'Environment mismatch'], 400);
            }
        } catch (SignatureVerificationException $e) {
            Log::error('Stripe webhook signature verification failed: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // Handle the event
        switch ($event->type) {
            case 'payment_intent.succeeded':
                $this->handlePaymentIntentSucceeded($event->data->object);
                break;
            case 'payment_intent.payment_failed':
                $this->handlePaymentIntentFailed($event->data->object);
                break;
            default:
                Log::info('Unhandled Stripe webhook event: ' . $event->type);
        }

        return response()->json(['status' => 'success']);
    }

    protected function handlePaymentIntentSucceeded($paymentIntent)
    {
        try {
            // Find the transaction using metadata
            $transaction = Transaction::find($paymentIntent->metadata->transaction_id);

            if (!$transaction) {
                Log::error('Transaction not found for payment intent: ' . $paymentIntent->id);
                return;
            }

            // Create or update payment record
            $payment = Payment::updateOrCreate(
                ['stripe_payment_id' => $paymentIntent->id],
                [
                    'transaction_id' => $transaction->id,
                    'amount' => $paymentIntent->amount / 100, // Convert from cents
                    'payment_method' => 'credit_card',
                    'status' => 'completed',
                    'environment' => $paymentIntent->livemode ? 'live' : 'test'
                ]
            );

            // Update transaction status
            $transaction->update(['status' => 'completed']);

            // Send notifications
            $this->paymentNotificationService->notifyPaymentReceived($payment);

            Log::info('Payment processed successfully', [
                'payment_id' => $payment->id,
                'transaction_id' => $transaction->id,
                'amount' => $payment->amount,
                'environment' => $paymentIntent->livemode ? 'live' : 'test'
            ]);
        } catch (\Exception $e) {
            Log::error('Error processing successful payment: ' . $e->getMessage(), [
                'payment_intent_id' => $paymentIntent->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    protected function handlePaymentIntentFailed($paymentIntent)
    {
        try {
            // Find the transaction using metadata
            $transaction = Transaction::find($paymentIntent->metadata->transaction_id);

            if (!$transaction) {
                Log::error('Transaction not found for failed payment intent: ' . $paymentIntent->id);
                return;
            }

            // Create or update payment record
            $payment = Payment::updateOrCreate(
                ['stripe_payment_id' => $paymentIntent->id],
                [
                    'transaction_id' => $transaction->id,
                    'amount' => $paymentIntent->amount / 100,
                    'payment_method' => 'credit_card',
                    'status' => 'failed',
                    'environment' => $paymentIntent->livemode ? 'live' : 'test'
                ]
            );

            // Update transaction status
            $transaction->update(['status' => 'failed']);

            Log::info('Payment failed', [
                'payment_id' => $payment->id,
                'transaction_id' => $transaction->id,
                'amount' => $payment->amount,
                'error' => $paymentIntent->last_payment_error->message ?? 'Unknown error',
                'environment' => $paymentIntent->livemode ? 'live' : 'test'
            ]);
        } catch (\Exception $e) {
            Log::error('Error processing failed payment: ' . $e->getMessage(), [
                'payment_intent_id' => $paymentIntent->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}
