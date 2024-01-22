<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index()
    {
        // Retrieve all plans from the database
        $plans = Plan::all();

        // Return the plans as JSON
        return response()->json($plans);
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
            'plan' => 'required|string|unique:plans',
            'percent' => 'required|numeric',
            'duration' => 'required|integer',
        ]);

        // Create a new plan instance
        $plan = new Plan();
        $plan->plan = $request->input('plan');
        $plan->percent = $request->input('percent');
        $plan->duration = $request->input('duration');

        // Save the plan to the database
        $plan->save();

        // Return a response
        return response()->json(['message' => 'Plan created successfully', 'plan' => $plan], 201);
    }

    public function update(Request $request, Plan $plan)
    {
        // Validate the incoming request data
        $request->validate([
            'percent' => 'required|numeric',
            'duration' => 'required|integer',
        ]);

        // Update the plan instance
        $plan->percent = $request->input('percent');
        $plan->duration = $request->input('duration');

        // Save the updated plan to the database
        $plan->save();

        // Return a response
        return response()->json(['message' => 'Plan updated successfully', 'plan' => $plan]);
    }
}
