<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNotificationRequest;
use App\Http\Requests\UpdateNotificationRequest;
use App\Http\Resources\NotificationResource;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function create()
    {
        return view('notifications.create');
    }

    public function store(StoreNotificationRequest $request)
    {
        $user = Auth::user();

        // Associate the notification with the current user
        $notification = $user->notifications()->create($request->all());

        return new NotificationResource($notification);
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
