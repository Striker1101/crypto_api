<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Earning;
use Illuminate\Http\Request;
use App\Http\Requests\StoreEarningRequest;

class EarningController extends Controller
{
    public function index()
    {
        $earnings = Earning::all();

        return response()->json(['earnings' => $earnings], 200);
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

    public function destroy(Earning $earning)
    {
        $earning->delete();

        return response()->json(['message' => 'Earning deleted successfully'], 200);
    }
}
