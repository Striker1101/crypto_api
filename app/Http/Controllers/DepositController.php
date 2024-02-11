<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDepositRequest;
use App\Http\Requests\UpdateDepositRequest;
use App\Http\Resources\DebitCardResource;
use App\Http\Resources\DepositResource;
use App\Models\Deposit;
use App\Models\User;
use App\Notifications\DepositPending;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DepositController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $user = Auth::user();

        // Retrieve deposits only for the current user
        $deposits = $user->deposit()->paginate($perPage);

        if ($request->wantsJson()) {
            return DepositResource::collection($deposits);
        }

        return view('deposits.index', ['deposits' => $deposits]);
    }

    public function show(Request $request, Deposit $deposit)
    {

        if ($request->wantsJson()) {
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
        // Get the user_id from the request
        $userId = $request->user_id;

        // Find the user based on the user_id
        $user = User::find($userId);
        //on user.error return no such user

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Associate the deposit with the current user
        $deposit = $user->deposit()->create($request->all());

        // Send the deposit pending notification to the user
        $deposit->user->notify(new DepositPending($deposit->amount, $user->name));

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

        if ($request->wantsJson()) {
            return new DepositResource($deposit);
        }

        return redirect()->route('deposit.index')->with('success', 'deposit updated successfully!');
    }

    public function destroy(Deposit $deposit)
    {

        // Check if the deposit status is 1 (completed)
        if ($deposit->status === "completed") {
            $user = $deposit->user;
            $account = $user->account;

            // Increase the account's balance with the deposital amount
            $account->decrement('balance', $deposit->amount);

            // Save the updated account
            $account->save();
        }
        $deposit->delete();

        return response()->json(['message' => 'Deposit deleted successfully']);
    }
}
