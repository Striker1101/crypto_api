<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class WalletController extends Controller
{
    /**
     * Display a listing of the wallets.
     */
    public function index()
    {
        $wallets = Wallet::all();
        return response()->json($wallets, 200);
    }

    public function edit()
    {
        // Fetch all plans
        $wallets = Wallet::all();
        // Pass the user to the view
        // dd($wallets);
        return Inertia::render('Wallet', [
            'wallets' => $wallets,
        ]);
    }

    /**
     * Store a newly created wallet.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|unique:wallets,address|max:255',
            'balance' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|max:10',
            'symbol' => 'nullable|string|max:10',
            'image' => 'nullable|string|max:255',
            'user_id' => 'nullable|exists:users,id',
        ]);

        $wallet = Wallet::create($request->all());

        return response()->json([
            'message' => 'Wallet created successfully!',
            'wallet' => $wallet
        ], 201);
    }

    /**
     * Display the specified wallet.
     */
    public function show($id)
    {
        $wallet = Wallet::find($id);

        if (!$wallet)
        {
            return response()->json(['message' => 'Wallet not found'], 404);
        }

        return response()->json($wallet, 200);
    }

    /**
     * Update the specified wallet.
     */
    public function update(Request $request, $id)
    {
        $wallet = Wallet::find($id);

        if (!$wallet)
        {
            return response()->json(['message' => 'Wallet not found'], 404);
        }

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'address' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('wallets', 'address')->ignore($wallet->id),
            ],
            'balance' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|max:10',
            'symbol' => 'nullable|string|max:10',
            'image' => 'nullable|string|max:255',
            'user_id' => 'nullable|exists:users,id',
        ]);

        $wallet->update($request->all());

        return response()->json([
            'message' => 'Wallet updated successfully!',
            'wallet' => $wallet
        ], 200);
    }

    /**
     * Remove the specified wallet.
     */
    public function destroy($id)
    {
        $wallet = Wallet::find($id);

        if (!$wallet)
        {
            return response()->json(['message' => 'Wallet not found'], 404);
        }

        $wallet->delete();

        return response()->json(['message' => 'Wallet deleted successfully'], 200);
    }
}
