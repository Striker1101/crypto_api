<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWithdrawRequest;
use App\Http\Requests\UpdateWithdrawRequest;
use App\Http\Resources\WithdrawResource;
use App\Models\Withdraw;
use App\Models\User;
use App\Notifications\WithdrawPending;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class WithdrawController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $withdraws = $user->withdraws()->paginate($request->input('per_page', 10));

        if ($request->wantsJson()) {
            return WithdrawResource::collection($withdraws);
        }

    }

    public function show(Withdraw $withdraw)
    {
        $this->authorize('view', $withdraw);

        return new WithdrawResource($withdraw);
    }

    public function create($userId)
    {
        return inertia::render('CreateWithdraw', [
            'user_id' => $userId,
        ]);
    }

    public function store(StoreWithdrawRequest $request)
    {
        // Get the user_id from the request
        $userId = $request->user_id;

        // Find the user based on the user_id
        $user = User::find($userId);
        //on user.error return no such user

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $account = $user->account;

        // Check if KYC is verified
        if ($user->kycInfo->verified == "0" && $request->check == "1") {
            return response()->json(['message' => 'Please verify KYC.', 'status' => 422], 422);
        }

        // Calculate the total available amount for withdrawal
        $totalAvailableAmount = $account->balance + $account->bonus + $account->earning;

        // Check if the withdrawal amount is greater than the total available amount
        $withdrawalAmount = $request->input('amount');
        if ($withdrawalAmount > $totalAvailableAmount) {
            return response()->json(['error' => 'Insufficient funds for withdrawal.'], 400);
        }

        // Associate the withdrawal with the current user's account
        $withdrawal = $user->withdraws()->create($request->all());


        // Send the withdraw pending notification to the user
        $withdrawal->user->notify(new WithdrawPending($withdrawal->amount, $user->name));

        return new WithdrawResource($withdrawal);
    }


    public function edit(Withdraw $withdraw)
    {
        $this->authorize('update', $withdraw);

        return view('withdraws.edit', ['withdraw' => $withdraw]);
    }

    public function update(UpdateWithdrawRequest $request, Withdraw $withdraw)
    {
        // $this->authorize('update', $withdraw);

        $withdraw->update($request->all());

        return new WithdrawResource($withdraw);
    }

    public function destroy(Withdraw $withdraw)
    {
        // Check if the withdraw status is 1 (completed)
        if ($withdraw->status === 1) {
            $user = $withdraw->user;
            $account = $user->account;

            // Increase the account's balance with the withdrawal amount
            $account->increment('balance', $withdraw->amount);

            // Save the updated account
            $account->save();
        }

        // Delete the withdrawal
        $withdraw->delete();

        return response()->json(['message' => 'Withdrawal deleted successfully']);
    }
}
