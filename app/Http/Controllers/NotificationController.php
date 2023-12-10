<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNotificationRequest;
use App\Http\Requests\UpdateNotificationRequest;
use App\Http\Resources\NotificationResource;
use App\Mail\SendMail;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\Mail;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $notifications = $user->notifications()->paginate($request->input('per_page', 10));

        if ($request->wantsJson()) {
            return NotificationResource::collection($notifications);
        }

        return view('notifications.index', ['notifications' => $notifications]);
    }

    public function show(Notification $notification)
    {
        $this->authorize('view', $notification);

        return new NotificationResource($notification);
    }

    public function create($userId)
    {
        return inertia::render('CreateNotify', [
            'user_id' => $userId,
        ]);
    }

    public function store(StoreNotificationRequest $request, Notification $notification)
    {

        $mail = new SendMail($request->input('content'), $request->input('header'), $request->input('footer'));

        $user = User::find($request->input('user_id'));
        if ($user) {
            $user_email = $user->email;
        } else {
            // Handle the case where the user with the given ID doesn't exist
            $user_email = null; // Or you can set a default value or throw an exception
        }
        Mail::to($user_email)->send($mail);

        $notification->create($request->all());

        return response()->json(['message' => 'Notification sent successfully']);
    }

    public function edit(Notification $notification)
    {
        $this->authorize('update', $notification);

        return view('notifications.edit', ['notification' => $notification]);
    }

    public function update(UpdateNotificationRequest $request, Notification $notification)
    {
        $this->authorize('update', $notification);

        $notification->update($request->all());

        return new NotificationResource($notification);
    }

    public function destroy(Notification $notification)
    {
        $this->authorize('delete', $notification);

        $notification->delete();

        return response()->json(['message' => 'Notification deleted successfully']);
    }
}
