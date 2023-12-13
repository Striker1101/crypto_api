<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAccountRequest;
use App\Http\Requests\UpdateAccountRequest;
use App\Http\Resources\AccountResource;
use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    // Apply the 'auth' middleware to all methods in this controller
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10); // You can customize the default per page value
        $accounts = Account::paginate($perPage);

        return AccountResource::collection($accounts);
    }

    public function show(Account $account)
    {
        return new AccountResource($account);
    }

    public function create()
    {
        if (request()->wantsJson()) {
            // Return JSON response for API requests
            return response()->json(['message' => 'API create method']);
        }
        return view('accounts.create');
    }

    public function store(StoreAccountRequest $request)
    {

        $account = Account::create($request->all());

        return new AccountResource($account);
    }

    public function edit(Account $account)
    {
        if (request()->wantsJson()) {
            // Return JSON response for API requests
            return response()->json(['message' => 'API edit method']);
        }

        // Return the web view for editing an existing account
        return view('accounts.edit', ['account' => $account]);
    }

    public function update(UpdateAccountRequest $request, Account $account)
    {
        $account->update($request->validated());

        return new AccountResource($account);
    }

    public function destroy(Account $account)
    {
        $account->delete();

        return response()->json(['message' => 'Account deleted successfully']);
    }
}
