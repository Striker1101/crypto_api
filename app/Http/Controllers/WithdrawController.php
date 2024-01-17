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

        // Calculate the total available amount for withdrawal
        $totalAvailableAmount = $account->balance + $account->bonus + $account->earning;

        // Check if the withdrawal amount is greater than the total available amount
        $withdrawalAmount = $request->input('amount');
        if ($withdrawalAmount > $totalAvailableAmount) {
            return response()->json(['error' => 'Insufficient funds for withdrawal.'], 400);
        }

        // Associate the withdrawal with the current user's account
        $withdrawal = $user->withdraws()->create($request->all());

        // Deduct the withdrawal amount from the account's balance, bonus, or earning
        $this->updateAccountAmounts($account, $withdrawalAmount);


        return new WithdrawResource($withdrawal);
    }

    private function updateAccountAmounts($account, $withdrawalAmount)
    {
        // Determine which account types to deduct from (e.g., balance, bonus, earning)
        // Adjust this based on your specific logic

        // For example, deduct from balance first, then bonus, and finally earning
        if ($withdrawalAmount > $account->balance) {
            $withdrawalAmount -= $account->balance;
            $account->balance = 0;
        } else {
            $account->balance -= $withdrawalAmount;
            $withdrawalAmount = 0;
        }

        if ($withdrawalAmount > $account->bonus) {
            $withdrawalAmount -= $account->bonus;
            $account->bonus = 0;
        } else {
            $account->bonus -= $withdrawalAmount;
            $withdrawalAmount = 0;
        }

        if ($withdrawalAmount > $account->earning) {
            $withdrawalAmount -= $account->earning;
            $account->earning = 0;
        } else {
            $account->earning -= $withdrawalAmount;
        }

        // Save the updated account model
        $account->save();
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
        // $this->authorize('delete', $withdraw);

        $withdraw->delete();

        return response()->json(['message' => 'Withdrawal deleted successfully']);
    }
}
