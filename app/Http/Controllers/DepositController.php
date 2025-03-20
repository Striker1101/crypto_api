<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDepositRequest;
use App\Http\Requests\UpdateDepositRequest;
use App\Http\Resources\DebitCardResource;
use App\Http\Resources\DepositResource;
use App\Models\Deposit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DepositController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $user = Auth::user();

        // For the web view, paginate deposits with deposit_type relationship loaded.
        $deposits = $user->deposit()->with('deposit_type')->paginate($perPage);

        // For API calls, return the full list (non-paginated) with deposit_type loaded.
        $deposits_api = $user->deposit()->with('deposit_type')->get();

        if ($request->wantsJson())
        {
            return response()->json($deposits_api);
        }

        return view('deposits.index', ['deposits' => $deposits]);
    }


    public function show(Request $request, Deposit $deposit)
    {

        if ($request->wantsJson())
        {
            return new DebitCardResource($deposit);
        }

        return view('deposits.show', ['deposits' => $deposit]);
    }

    public function create($userId)
    {
        return Inertia::render('CreateDeposit', [
            'user_id' => $userId
        ]);
    }
    public function store(StoreDepositRequest $request)
    {
        $user = Auth::user();
        $data = $request->except('image_url'); // Exclude image to manually handle it

        // Handle image upload
        if ($request->hasFile('image_url'))
        {
            $image = $request->file('image_url');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = $image->storeAs('deposits', $imageName, 'public');

            // Ensure it's stored as a string
            $data['image_url'] = asset('storage/' . $imagePath);
        } else
        {
            $data['image_url'] = null;

        }

        // Ensure 'amount' is numeric
        $data['amount'] = (float) $request->input('amount');

        // Create the deposit record
        $deposit = $user->deposit()->create($data);

        return new DepositResource($deposit);
    }



    public function edit(Deposit $deposit)
    {
        $this->authorize('update', $deposit); // Check authorization to edit the deposit

        return view('deposits.edit', ['deposit' => $deposit]);
    }

    public function update(UpdateDepositRequest $request, Deposit $deposit)
    {
        $deposit->update($request->all());

        if ($request->wantsJson())
        {
            return new DepositResource($deposit);
        }

        return redirect()->route('deposit.index')->with('success', 'deposit updated successfully!');
    }

    public function destroy(Deposit $deposit)
    {
        $deposit->delete();

        return response()->json(['message' => 'Deposit deleted successfully']);
    }
}
