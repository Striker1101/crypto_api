<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Password;


class AuthController extends Controller
{
    use HttpResponses;
    use SendsPasswordResetEmails, ResetsPasswords;

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
            'street'=> $request->street,
            'city'=> $request->city,
            'zip_code'=> $request->zip_code,
            'state'=> $request->state,
            'password' => Hash::make( $request->password)
        ]);

        return $this->success([
            'user' => new UserResource($user),
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

    // Show the form to enter the email address for password reset
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    // Handle the submission of the email address for password reset
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? $this->sendResetLinkResponse($status)
            : $this->sendResetLinkFailedResponse($request, $status);
    }

    // Show the password reset form with the token
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    // Handle the password reset submission
    public function reset(Request $request)
    {
        $request->validate($this->rules(), $this->validationErrorMessages());

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $this->resetPassword($user, $password);
            }
        );

        return $status == Password::PASSWORD_RESET
            ? $this->sendResetResponse($status)
            : $this->sendResetFailedResponse($request, $status);
    }

    // Event listener when the password is successfully reset
    protected function resetPassword($user, $password)
    {
        $user->forceFill([
            'password' => bcrypt($password),
            'remember_token' => Str::random(60),
        ])->save();

        event(new PasswordReset($user));
    }
}
