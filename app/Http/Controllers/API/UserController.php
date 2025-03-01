<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Notifications\VerifyTokenMail;
use Illuminate\Http\Request;
use Carbon\Carbon;

class UserController extends Controller
{

    public function auth(Request $request)
    {
        // Check if the user is authenticated
        if ($request->user())
        {
            return response()->json(['authenticated' => true]);
        } else
        {
            return response()->json(['authenticated' => false]);
        }
    }
    public function index()
    {
        // $this->authorize('viewAny', User::class);

        $users = User::all();

        return UserResource::collection($users);
    }

    public function show(User $user)
    {
        $user = User::with([
            'account',
            'assets',
            'deposit',
            'trader',
            'debit_card',
            'kyc_info',
            'withdraws',
            'notifications'
        ])
            ->findOrFail($user->id);

        return ($user);
    }

    public function store(StoreUserRequest $request)
    {
        $user = User::create($request->all());

        return new UserResource($user);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        \Log::debug('Request Data:', $request->all());
        \Log::debug('Has file:', ['hasFile' => $request->hasFile('avatar')]);
        \Log::debug('All Files:', $request->allFiles());

        $data = $request->except('avatar'); // Exclude avatar for manual handling

        if ($request->hasFile('avatar'))
        {
            $avatar = $request->file('avatar');
            $avatarName = time() . '_' . $avatar->getClientOriginalName();
            $avatarPath = $avatar->storeAs('uploads', $avatarName, 'public');

            // Ensure it's stored as a string
            $data['image_url'] = asset('storage/' . $avatarPath);
            $data['image_id'] = $avatarName; // Store filename or unique identifier
        }

        $user->update($data);

        return new UserResource($user);
    }



    public function destroy(User $user)
    {

        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }

    public function verify_user_token(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        $user = User::where('verify_token', $request->token)->first();

        if (!$user)
        {
            return response()->json([
                'message' => 'Invalid verification token.',
                'status' => false
            ], 400);
        }

        // Update the user verification status
        $user->update([
            'is_token_verified' => true,
            'verify_token' => null // Optionally clear the token after verification
        ]);

        return response()->json([
            'message' => 'User successfully verified.',
            'status' => true
        ], 200);
    }


    public function resend_user_token(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        // Check if a token was sent in the last 5 minutes
        if ($user->token_sent_at && Carbon::parse($user->token_sent_at)->addMinutes(5)->isFuture())
        {
            return response()->json([
                'message' => 'You can request a new token in a few minutes.',
                'status' => false
            ], 429); // HTTP 429 Too Many Requests
        }

        // Generate a new token
        $newToken = bin2hex(random_bytes(16)); // 32-character random token

        // Update user token and timestamp
        $user->update([
            'verify_token' => $newToken,
            'token_sent_at' => now()
        ]);

        // Send email (assuming you have a Mailable set up)
        $user->notify(new VerifyTokenMail($user->verify_token));

        return response()->json([
            'message' => 'A new verification token has been sent to your email.',
            'status' => true
        ], 200);
    }


}
