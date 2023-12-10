<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        // $this->authorize('viewAny', User::class);

        $users = User::all();

        return UserResource::collection($users);
    }

    public function show(User $user)
    {
        $user = User::with(['account', 'assets',
            'deposit', 'debit_card', 'kycInfo',
            'withdraws', 'notifications']);
        return new UserResource($user);
    }

    public function store(StoreUserRequest $request)
    {
        $user = User::create($request->all());

        return new UserResource($user);
    }

    public function update(UpdateUserRequest $request, User $user)
    {

        $user->update($request->all());

        return new UserResource($user);
    }

    public function destroy(User $user)
    {

        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
}
