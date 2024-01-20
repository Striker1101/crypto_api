<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWithdrawRequest;
use App\Http\Requests\UpdateWithdrawRequest;
use App\Http\Resources\WithdrawResource;
use App\Models\Withdraw;
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
        $user = Auth::user();
        $account = $user->account;

        // Check if KYC is verified
        if (!$user->kycInfo->verified) {
            return response()->json(['message' => 'Please verify KYC.', 'status' => 200]);
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

        return new WithdrawResource($withdrawal);
    }


    public function edit(Withdraw $withdraw)
    {
        $this->authorize('update', $withdraw);

        return view('withdraws.edit', ['withdraw' => $withdraw]);
    }

    public function update(UpdateWithdrawRequest $request, Withdraw $withdraw)
    {
        // Check if status is updated to 1 and added is 0
        if ($request->input('status') == 1 && $request->input('added') == 0) {
            $user = $withdraw->user;
            $account = $user->account;

            // Add the withdrawal amount to the account's balance
            $account->increment('balance', $withdraw->amount);

            \Log::info('Updating withdraw', ['attributes' => $request->all()]);
            // Update 'added' to true (1)
            $withdraw->added = "1";
            $withdraw->update($request->all());
        } elseif ($request->input('status') == 0 && $request->input('added') == 1) {
            // Check if status is updated to 1 and added is 1
            $user = $withdraw->user;
            $account = $user->account;

            // Subtract the withdrawal amount from the account's balance
            $account->decrement('balance', $withdraw->amount);

            \Log::info('Updating withdraw', ['attributes' => $request->all()]);
            // Update 'added' to true (0)
            $withdraw->added = "0";
            $withdraw->update($request->all());
        }



        return new WithdrawResource($withdraw);
    }


    public function destroy(Withdraw $withdraw)
    {
        // $this->authorize('delete', $withdraw);

        $withdraw->delete();

        return response()->json(['message' => 'Withdrawal deleted successfully']);
    }
}
