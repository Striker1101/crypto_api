<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInvestmentRequest;
use App\Models\Account;
use App\Models\Investment;
use App\Models\Plan;
use Illuminate\Http\Request;


class InvestmentController extends Controller
{
    public function index()
    {
        $investments = Investment::all();

        return response()->json(['investments' => $investments], 200);
    }

    public function store(StoreInvestmentRequest $request)
    {
        $user = auth()->user();
        $accountStage = $user->account->account_stage;
        $account = $user->account;

        // Retrieve the plan details from the plans table based on the user's current plan
        $plan = Plan::where('plan', $accountStage)->first();


        // Check if the requested amount exceeds the plan's max_amount
        if ($request->amount > $plan->max_amount && $request->check) {
            return response()->json(['message' => 'Your plan maximum investment is ' . $plan->max_amount . ', please upgrade your plan'], 422);
        }

        // Check if account balance + account earning is greater than or equal to the requested amount
        if (($user->account->balance + $user->account->earning) < $request->amount) {
            return response()->json(['message' => 'Insufficient funds'], 422);
        }

        $investment = new Investment();
        $investment->user_id = $user->id;
        $investment->amount = $request->amount;
        $investment->plan = $plan->plan;
        $investment->duration = $plan->duration;

        $investment->save();

        // Deduct the amount from account earning
        $remainingEarning = $account->earning - $request->amount;
        $updatedEarning = max(0, $remainingEarning); // Ensure earning doesn't go negative

        // Calculate the remaining amount to deduct from balance
        $remainingAmount = max(0, -$remainingEarning); // If remainingEarning is negative, subtract it from the balance
        $updatedBalance = $account->balance - $remainingAmount;

        $account->update([
            'earning' => $updatedEarning,
            'balance' => $updatedBalance,
        ]);

        return response()->json(['message' => 'Investment created successfully', 'investment' => $investment], 201);
    }


    public function update(Request $request, Investment $investment)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'check' => 'sometimes|boolean',

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
