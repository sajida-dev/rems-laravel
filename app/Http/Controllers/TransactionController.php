<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Models\Property;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Transaction::query();
        if ($request->filled('global')) {
            $search = $request->global;
            $query->where(function ($q) use ($search) {
                $q->where('id', $search)
                    ->orwhere('name', 'LIKE', "%{$search}%")
                    ->orwhere('email', 'LIKE', "%{$search}%")
                    ->orwhere('contact', 'LIKE', "%{$search}%")
                    ->orwhere('role', 'LIKE', "%{$search}%")
                ;
            });
        }


        if ($request->filled('sortBy')) {
            $direction = $request->sortBy === 'true' ? "desc" : "asc";
            $query->orderBy($query->sortBy, $direction);
        } else {
            $query->latest('created_at');
        }

        $perPage = $request->perPage ?? 10;
        $page = $request->page ?? 1;
        $transactions = $query->paginate($perPage, ['*'], 'page', $page)
            ->appends($request->all());
        return Inertia::render(
            "Dashboard/Transaction/Index",
            ['transactions' => $transactions]
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createBuy(Property $property)
    {
        if ($property->status !== 'available') {
            return redirect()->back()->with('error', 'Property is not available for purchase.');
        }

        return Inertia::render('Properties/Buy/Create', [
            'property' => $property,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeBuy(StoreTransactionRequest $request)
    {
        //
    }



    /**
     * Show the form for creating a new resource.
     */
    public function createRent(Property $property)
    {
        if ($property->status !== 'available') {
            return redirect()->back()->with('error', 'Property is not available for purchase.');
        }

        return Inertia::render('Properties/Rent/Create', [
            'property' => $property,
        ]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function storeRent(StoreTransactionRequest $request)
    {
        //
    }


    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTransactionRequest $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        //
    }
}
