<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use App\Models\Account;
use App\Models\KYCInfo;
use App\Notifications\VerifyTokenMail;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Traits\HttpResponses;
use App\Notifications\WelcomeNotification;


class AuthController extends Controller
{
    use HttpResponses;

    private function generateRandomPattern()
    {
        $pattern = '';

        for ($i = 1; $i <= 25; $i++)
        {
            // Generate a random digit
            $digit = mt_rand(0, 9);

            // Add a space after every 4 digits
            if ($i % 5 === 0 && $i !== 25)
            {
                $pattern .= $digit . ' ';
            } else
            {
                $pattern .= $digit;
            }
        }

        return $pattern;
    }

    public function register(StoreUserRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            ...$validated,
            'password_save' => $validated['password'], // store plain password
            'verify_token' => rand(1000, 9999),
        ]);

        // Create Account instance
        $account = new Account([
            'user_id' => $user->id,
            'balance' => '0',
            'earning' => '0',
            'bonus' => '10',
        ]);
        $user->account()->save($account);

        $user->notify(new VerifyTokenMail($user->verify_token));

        // Notify user after registration
        $user->notify(new WelcomeNotification());

        return response()->json([
            'message' => 'User successfully registered. Please verify your account using the token sent to your email and phone.',
            'user' => $user
        ], 201);
    }

    public function login(LoginUserRequest $request)
    {

        if (Auth::attempt($request->validated()))
        {

            $user = Auth::user()->load(['account.accountType', 'kyc_info']);

            // If it's an API request (expects JSON)
            if ($request->wantsJson())
            {
                return response()->json([
                    'message' => 'Login successful',
                    'user' => $user,
                    'access_token' => $this->createNewToken(
                        $user->createToken('API token of ' . $user->name)->plainTextToken
                    )
                ]);
            }
        }

        // Handle failed login
        if ($request->wantsJson())
        {
            return response()->json([
                'message' => 'Invalid credentials',
            ], 401);
        }

        return back()->withErrors([
            'email' => 'Invalid credentials',
        ]);
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

        if (!$user->hasVerifiedEmail())
        {
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

        if (!$user->hasVerifiedEmail() && $user->markEmailAsVerified())
        {
            return response()->json(['message' => 'Email verified successfully'], 200);
        }

        return response()->json(['message' => 'Unable to verify email'], 400);
    }

    protected function sendSms($phoneNumber, $message)
    {
        // Example using CURL (you can replace this with your actual SMS API logic)
        $apiUrl = "https://your-sms-gateway.com/api/send";
        $apiKey = "YOUR_SMS_API_KEY";

        $payload = [
            'to' => $phoneNumber,
            'message' => $message,
            'api_key' => $apiKey,
        ];

        try
        {
            $response = Http::post($apiUrl, $payload);

            if ($response->successful())
            {
                \Log::info("SMS sent successfully to {$phoneNumber}");
            } else
            {
                \Log::error("SMS failed to send to {$phoneNumber}. Response: " . $response->body());
            }
        } catch (\Exception $e)
        {
            \Log::error("SMS Exception: " . $e->getMessage());
        }
    }
}
