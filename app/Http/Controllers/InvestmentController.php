<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInvestmentRequest;
use App\Models\Investment;
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
        $investment = Investment::create($request->all());

        return response()->json(['message' => 'Investment created successfully', 'investment' => $investment], 201);
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
