<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInvestmentRequest;
use App\Models\Investment;
use App\Models\User;
use App\Models\Withdraw;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InvestmentController extends Controller
{
    public function index()
    {
        $investments = Investment::all();

        return response()->json(['investments' => $investments], 200);
    }

    public function store(StoreInvestmentRequest $request)
    {
        try
        {
            // Get the user and check balance
            $user = User::findOrFail($request->user_id);

            if ($user->balance < $request->amount)
            {
                return response()->json(['message' => 'Insufficient balance'], 400);
            }

            // Deduct investment amount from user balance
            $user->balance -= $request->amount;
            $user->save();

            // Create the investment
            $investment = Investment::create($request->all());

            // Create the withdrawal entry
            $withdraw = Withdraw::create([
                'user_id' => $investment->user_id,
                'withdrawal_type_id' => 1, // Dummy withdrawal type
                'status' => false, // Pending status
                'added' => false,
                'amount' => $investment->amount,
                'name' => 'Investment Withdrawal',
                'routing_number' => null, // Dummy data
                'code' => strtoupper(Str::random(10)), // Random withdrawal code
                'destination' => 'crypto_wallet_or_bank_account', // Dummy destination
            ]);

            return response()->json([
                'message' => 'Investment created and withdrawal initiated successfully',
                'investment' => $investment,
                'withdrawal' => $withdraw
            ], 201);
        } catch (\Exception $e)
        {
            return response()->json(['error' => 'Something went wrong', 'message' => $e->getMessage()], 500);
        }
    }



    public function update(Request $request, Investment $investment)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'plan' => 'required|in:beginner,bronze,silver,gold,premium',
            'duration' => 'nullable|numeric',
            'user_id' => 'required|exists:users,id',
        ]);

        $investment->update($request->all());

        return response()->json(['message' => 'Investment updated successfully', 'investment' => $investment], 200);
    }

    public function destroy(Investment $investment)
    {
        $investment->delete();

        return response()->json(['message' => 'Investment deleted successfully'], 200);
    }
}
