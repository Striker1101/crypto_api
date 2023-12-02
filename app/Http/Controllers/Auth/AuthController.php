<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\V1\LoginUserRequest;
use App\Http\Requests\StoreUserRequest;

class AuthController extends Controller
{
    use HttpResponses;

    public function login(LoginUserRequest $request) {
        $request->validated($request->all());

        $credentials = [
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ];

        if (!Auth::attempt($credentials)) {
            return $this->error('', 'Credentials do not match', 401);
        }

        $user = User::where("email", $request->email)->first();

        return $this->success([
            'user' => $user,
            'token' => $user->createToken('API token of '. $user->name) ->plainTextToken
        ]);
    }


    public function register(StoreUserRequest $request){
        $request->validated($request->all());

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make( $request->password)
        ]);

        return $this->success([
            'user' => $user,
            'token' => $user->createToken('API token of '. $user->name) ->plainTextToken
        ]);
    }

    public function logout(){
        Auth:user()->currentAccessToken()->delete();
        return $this->success([
            'message' => 'logout was successful'
        ]
        );
    }
}
