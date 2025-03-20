<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PlanController extends Controller
{
    public function index(Request $request)
    {
        $ownerId = $request->query('owner_id');

        $plans = $ownerId ? Plan::where('owner_referral_id', $ownerId)->get() : Plan::all();

        return response()->json($plans);
    }
    public function edit()
    {
        // Fetch all plans
        $plans = Plan::all();
        // Pass the user to the view
        return Inertia::render('UsersPlan', [
            'plans' => $plans,
        ]);
    }
    /**
     * Store a newly created plan in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|unique:plans',
            'percent' => 'required|numeric',
            'duration' => 'required|integer',
        ]);

        $plan = Plan::create($request->all());


        // Return a response
        return response()->json(['message' => 'Plan created successfully', 'plan' => $plan], 201);
    }

    public function update(Request $request, Plan $plan)
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'percent' => 'sometimes|numeric',
            'duration' => 'sometimes|integer',
            'amount' => 'sometimes|numeric',
            'support' => 'sometimes|numeric',
            'agent' => 'sometimes|numeric',
            'type' => 'sometimes|string|max:255',
        ]);

        // Update only the provided fields
        $plan->update($request->only([
            'name',
            'percent',
            'duration',
            'amount',
            'support',
            'agent',
            'type',
        ]));

        // Return a response
        return response()->json(['message' => 'Plan updated successfully', 'plan' => $plan]);
    }

    public function destroy(Plan $plan)
    {
        try
        {
            // Delete the plan
            $plan->delete();

            // Return success response
            return response()->json(['message' => 'Plan deleted successfully'], 200);
        } catch (\Exception $e)
        {
            // Return error response
            return response()->json(['error' => 'Failed to delete plan', 'message' => $e->getMessage()], 500);
        }
    }

}
