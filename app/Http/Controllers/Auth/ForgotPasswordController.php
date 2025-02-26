<?php

namespace App\Http\Controllers\Auth;

use App\Mail\ResetPasswordMail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use App\Models\User;

class ForgotPasswordController extends Controller
{
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $user = User::where('email', $request->email)->first();

        if (!$user)
        {
            return response()->json(['message' => 'Failed to send reset link, make sure email is registered'], 400);
        }

        // Manually generate the token
        $token = Password::broker()->createToken($user);

        // Use your frontend URL
        $frontendUrl = config('app.frontend_url', 'http://localhost:3003');

        // Generate the correct reset link
        $resetLink = $frontendUrl . '/confirm_password?token=' . $token . '&email=' . urlencode($request->email);

        // Send email
        Mail::to($request->email)->send(new ResetPasswordMail($resetLink));

        return response()->json(['message' => 'Reset link sent successfully', 'reset_link' => $resetLink], 200);
    }

    protected function getToken($email)
    {
        return Password::broker()->createToken(User::where('email', $email)->first());
    }
}
