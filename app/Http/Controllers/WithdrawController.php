<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWithdrawRequest;
use App\Http\Requests\UpdateWithdrawRequest;
use App\Http\Resources\WithdrawResource;
use App\Models\Withdraw;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WithdrawController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $withdraws = $user->withdraws()->paginate($request->input('per_page', 10));

        if ($request->wantsJson()) {
            return WithdrawResource::collection($withdraws);
        }

        return view('withdraws.index', ['withdraws' => $withdraws]);
    }

    public function show(Withdraw $withdraw)
    {
        $this->authorize('view', $withdraw);

        return new WithdrawResource($withdraw);
    }

    public function create()
    {
        return view('withdraws.create');
    }

    public function store(StoreWithdrawRequest $request)
    {
        $user = Auth::user();

        // Associate the withdrawal with the current user
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
        $this->authorize('update', $withdraw);

        $withdraw->update($request->all());

        return new WithdrawResource($withdraw);
    }

    public function destroy(Withdraw $withdraw)
    {
        $this->authorize('delete', $withdraw);

        $withdraw->delete();

        return response()->json(['message' => 'Withdrawal deleted successfully']);
    }
}
