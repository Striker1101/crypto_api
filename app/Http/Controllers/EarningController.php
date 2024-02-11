<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Earning;
use App\Models\User;
use App\Notifications\EarningsUpdated;
use Illuminate\Http\Request;
use App\Http\Requests\StoreEarningRequest;
use Inertia\Inertia;

class EarningController extends Controller
{
    public function index()
    {
        $earnings = Earning::all();

        return response()->json(['earnings' => $earnings], 200);
    }

    public function create($userId)
    {
        return Inertia::render('CreateEarning', [
            'user_id' => $userId,
        ]);
    }

    public function store(StoreEarningRequest $request)
    {
        // Get the user_id from the request
        $userId = $request->user_id;

        // Find the user based on the user_id
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Calculate the new balance
        $newBalance = $user->account->earning + $request->amount;
        // Create the earning record
        $earning = Earning::create([
            'amount' => $request->amount,
            'balance' => $newBalance,
            'user_id' => $user->id,
        ]);

        // Update the user's earning balance
        $user->account->update(['earning' => $newBalance]);

        // Send the earning notification to the user
        $user->notify(new EarningsUpdated($earning->amount, $earning->balance));

        return response()->json(['message' => 'Earning created successfully', 'earning' => $earning], 201);
    }


    public function update(Request $request, Earning $earning)
    {

        $request->validate([
            'amount' => 'required|numeric',
            'user_id' => 'required|exists:users,id',
        ]);

        $earning->update($request->all());

        return response()->json(['message' => 'Earning updated successfully', 'earning' => $earning], 200);
    }

    public function destroy($id)
    {
        $earning = Earning::findOrFail($id); // Find the Earning record by ID
        $amount = $earning->amount; // Get the amount from the earning record

        // Find the associated user
        $user = User::findOrFail($earning->user_id);

        // Subtract the amount from the user's account earnings
        $newBalance = $user->account->earning - $amount;
        $user->account->update(['earning' => $newBalance]);

        // Delete the Earning record
        $earning->delete();

        return response()->json(['message' => 'Earning deleted successfully'], 200);
    }
}
