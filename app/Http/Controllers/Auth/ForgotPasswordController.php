<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\URL;
use App\Models\User;

class ForgotPasswordController extends Controller
{
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            // Generate the reset link with token and email parameters
            $frontendUrl = 'https://coinpeckko.online/reset-password.html'; // Replace with your frontend URL
            $resetLink = $frontendUrl . '?token=' . $this->getToken($request->email) . '&email=' . urlencode($request->email);

            // You can then use $resetLink to send in an email
            // For example, if you're sending an email using Laravel's built-in mail function:
            // Mail::to($request->email)->send(new ResetPasswordMail($resetLink));

            return response()->json(['message' => __('Reset link sent successfully'), 'reset_link' => $resetLink], 200);
        } else {
            return response()->json(['message' => __('Failed to send reset link, Make sure email is register')], 400);
        }
    }

    protected function getToken($email)
    {
        return Password::broker()->createToken(User::where('email', $email)->first());
    }
}
