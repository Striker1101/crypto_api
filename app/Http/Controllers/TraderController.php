<?php

namespace App\Http\Controllers;

use App\Models\Trader;
use Illuminate\Http\Request;

class TraderController extends Controller
{
    /**
     * Display a listing of traders.
     */
    public function index()
    {
        return response()->json(Trader::all());
    }

    /**
     * Store a newly created trader.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'profile_picture' => 'nullable|string',
            'rating' => 'required|string',
            'ROI' => 'numeric',
            'PnL' => 'numeric',
            'investment' => 'numeric',
            'ranking' => 'integer',
        ]);

        $trader = Trader::create($request->all());

        return response()->json(['message' => 'Trader created successfully', 'trader' => $trader], 201);
    }

    /**
     * Display the specified trader.
     */
    public function show(Trader $trader)
    {
        return response()->json($trader);
    }

    /**
     * Update the specified trader.
     */
    public function update(Request $request, Trader $trader)
    {
        $request->validate([
            'name' => 'string|max:255',
            'profile_picture' => 'nullable|string',
            'rating' => 'numeric|min:0|max:5',
            'ROI' => 'numeric',
            'PnL' => 'numeric',
            'investment' => 'numeric',
            'ranking' => 'integer',
        ]);

        $trader->update($request->all());

        return response()->json(['message' => 'Trader updated successfully', 'trader' => $trader]);
    }

    /**
     * Remove the specified trader.
     */
    public function destroy(Trader $trader)
    {
        $trader->delete();
        return response()->json(['message' => 'Trader deleted successfully']);
    }
}
