<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Get all users except the currently authenticated user with pagination
        $users = User::where('id', '!=', auth()->id())
            ->orderBy('created_at', 'desc') // Order by creation date in descending order
            ->paginate(10); // Adjust the pagination as needed

        // Check if the request is from an API
        if ($request->wantsJson()) {
            return response()->json(['users' => $users]);
        }

        return Inertia::render('Dashboard', [
            'users' => $users,
        ]);
    }

    public function edit($userId)
    {
        // Fetch user data and pass it to the view
        $user = User::find($userId);

        return Inertia::render('EditUser', [
            'user' => $user,
        ]);
    }
}


