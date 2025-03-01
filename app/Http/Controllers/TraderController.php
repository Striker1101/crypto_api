<?php

namespace App\Http\Controllers;

use App\Models\Trader;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class TraderController extends Controller
{
    /**
     * Display a listing of traders.
     */
    public function index()
    {
        return response()->json(Trader::all());
    }

    public function edit()
    {
        // Fetch all plans
        $traders = Trader::all();
        // Pass the user to the view
        return Inertia::render('Trader', [
            'traders' => $traders,
        ]);
    }

    /**
     * Store a newly created trader.
     */
    public function store(Request $request)
    {
        $request->merge([
            'display' => filter_var($request->display, FILTER_VALIDATE_BOOLEAN)
        ]);
        $request->validate([
            'name' => 'required|string|max:255',
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'rating' => 'required|string',
            'ROI' => 'numeric',
            'PnL' => 'numeric',
            'investment' => 'numeric',
            'ranking' => 'integer',
            'position' => 'string',
            'display' => 'boolean',
        ]);

        // Handle image upload
        if ($request->hasFile('profile_picture'))
        {
            $image = $request->file('profile_picture');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = $image->storeAs('profile_pictures', $imageName, 'public');

            // Generate full URL
            $fullImageUrl = asset("storage/$imagePath");
        } else
        {
            $fullImageUrl = null;
        }

        // Create trader record
        $trader = Trader::create([
            'name' => $request->name,
            'profile_picture' => $fullImageUrl,
            'rating' => $request->rating,
            'ROI' => $request->ROI,
            'PnL' => $request->PnL,
            'investment' => $request->investment,
            'ranking' => $request->ranking,
            'position' => $request->position,
            'display' => $request->display,
        ]);

        return response()->json([
            'message' => 'Trader created successfully',
            'trader' => $trader
        ], 201);
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
        // Convert display to boolean
        $request->merge([
            'display' => filter_var($request->display, FILTER_VALIDATE_BOOLEAN)
        ]);

        // Validate request
        $validatedData = $request->validate([
            'name' => 'nullable|string|max:255',
            'profile_picture' => 'nullable', // Allow string URLs or images
            'rating' => 'nullable|string',
            'ROI' => 'nullable|numeric',
            'PnL' => 'nullable|numeric',
            'investment' => 'nullable|numeric',
            'ranking' => 'nullable|integer',
            'position' => 'nullable|string|max:255',
            'display' => 'nullable|boolean',
        ]);

        // Handle image upload only if an actual file is uploaded
        if ($request->hasFile('profile_picture'))
        {
            // Delete old image if it exists and is a stored file (not an external URL)
            if ($trader->profile_picture && filter_var($trader->profile_picture, FILTER_VALIDATE_URL) === false)
            {
                $oldImagePath = str_replace(asset('storage/'), '', $trader->profile_picture);
                Storage::disk('public')->delete($oldImagePath);
            }

            // Upload new image
            $image = $request->file('profile_picture');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = $image->storeAs('profile_pictures', $imageName, 'public');

            // Store full URL
            $validatedData['profile_picture'] = asset("storage/$imagePath");
        } elseif (is_string($request->profile_picture))
        {
            // Keep existing profile_picture if it's a string (URL)
            $validatedData['profile_picture'] = $request->profile_picture;
        }

        // Update trader with validated data
        $trader->update($validatedData);

        return response()->json([
            'message' => 'Trader updated successfully',
            'trader' => $trader
        ]);
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
