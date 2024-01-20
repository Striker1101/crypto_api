<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use App\Models\Account;
use App\Models\KYCInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Traits\HttpResponses;

class AuthController extends Controller
{
    use HttpResponses;

    private function generateRandomPattern()
    {
        $pattern = '';

        for ($i = 1; $i <= 25; $i++) {
            // Generate a random digit
            $digit = mt_rand(0, 9);

            // Add a space after every 4 digits
            if ($i % 5 === 0 && $i !== 25) {
                $pattern .= $digit . ' ';
            } else {
                $pattern .= $digit;
            }
        }

        return $pattern;
    }

    public function register(StoreUserRequest $request)
    {
        $user = User::create($request->validated());


        // Create a new KYCInfo instance with the generated SSN
        $kyc_info = new KYCInfo([
            'ssn' => $this->generateRandomPattern(),
        ]);


        // Save the related record to the user
        $user->kycInfo()->save($kyc_info);
        // Create a related record in the Profile table
        $account = new Account([
            'user_id' => $user->id, // Replace with actual data
            'balance' => '0',
            'earning' => '0',
            'bonus' => '10',
            // Add other fields as needed
        ]);

        // Save the related record to the user
        $user->account()->save($account);

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user
        ], 201);
    }



    public function login(LoginUserRequest $request)
    {
        if (Auth::attempt($request->validated())) {
            // Authentication passed...
            $user = Auth::user()->load(['account', 'kycInfo']); // Load both 'account' and 'kyc_infos' relationships


            return response()->json([
                'message' => 'Login successful',
                'user' => $user,
                'access_token' => $this->createNewToken($user->createToken('API token of ' . $user->name)->plainTextToken)
            ]);
        } else {
            // Authentication failed...
            return response()->json([
                'message' => 'Invalid credentials',
            ], 401); // Unauthorized
        }
    }


    public function logout(Request $request)
    {
        auth::user()->currentAccessToken()->delete();

        return $this->success([
            'message' => 'Logout was successful'
        ]);
    }

    public function userProfile()
    {
        return response()->json(auth()->user());
    }



    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            // 'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }

    //Controller Method for Sending Verification Link:

    public function sendEmailVerificationLink(Request $request)
    {
        $user = Auth::user();

        if (!$user->hasVerifiedEmail()) {
            $user->sendEmailVerificationNotification();
            return response()->json(['message' => 'Verification link sent to your email'], 200);
        }

        return response()->json(['message' => 'Email already verified'], 400);
    }

    // Controller Method for Verifying Email:
    public function verifyEmail(Request $request)
    {
        $request->validate(['id' => 'required', 'hash' => 'required']);

        $user = User::findOrFail($request->id);

        if (!$user->hasVerifiedEmail() && $user->markEmailAsVerified()) {
            return response()->json(['message' => 'Email verified successfully'], 200);
        }

        return response()->json(['message' => 'Unable to verify email'], 400);
    }
}
