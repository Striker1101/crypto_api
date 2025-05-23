<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWithdrawRequest;
use App\Http\Requests\UpdateWithdrawRequest;
use App\Http\Resources\WithdrawResource;
use App\Models\Withdraw;
use App\Models\WithdrawType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class WithdrawController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $withdraws = $user->withdraws()->with('withdrawal_type')->paginate($request->input('per_page', 20));

        if ($request->wantsJson())
        {
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
        $withdraw_type = WithdrawType::all();
        return inertia::render('CreateWithdraw', [
            'user_id' => $userId,
            'withdraw_type' => $withdraw_type
        ]);
    }

    public function store(StoreWithdrawRequest $request)
    {
        $user = Auth::user();
        $account = $user->account;

        // Check if KYC is verified
        // if (!$user->kyc_info->verified)
        // {
        //     return response()->json(['message' => 'Please verify KYC.', 'status' => 200]);
        // }

        // Calculate the total available amount for withdrawal
        $totalAvailableAmount = $account->balance + $account->bonus + $account->earning;

        // Check if the withdrawal amount is greater than the total available amount
        $withdrawalAmount = $request->input('amount');
        if ($withdrawalAmount > $totalAvailableAmount)
        {
            return response()->json(['message' => 'Insufficient funds for withdrawal.'], 400);
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
