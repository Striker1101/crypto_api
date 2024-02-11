<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Earning;
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


        $earning = Earning::create($request->all());

        return response()->json(['message' => 'Earning created successfully', 'earning' => $earning], 201);
    }

    public function update(Request $request, Earning $earning)
    {

        $request->validate([
            'amount' => 'required|numeric',
            'balance' => 'required|numeric',
            'user_id' => 'required|exists:users,id',
        ]);

        $earning->update($request->all());

        return response()->json(['message' => 'Earning updated successfully', 'earning' => $earning], 200);
    }

    public function destroy($id)
    {
        $earning = Earning::findOrFail($id); // Corrected syntax for querying the Earning model by ID
        $earning->delete(); // Delete the found Earning record

        return response()->json(['message' => 'Earning deleted successfully'], 200);
    }
}
