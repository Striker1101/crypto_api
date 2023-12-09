<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Account;
use App\Models\KYCInfo;
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
        // Fetch user details with all associated relationships
        $user = User::with(['account', 'assets', 'deposit', 'debit_card', 'kycInfo', 'withdraws', 'notifications'])
            ->find($userId);
            
        // Pass the user to the view
        return Inertia::render('EditUser', [
            'user' => $user,
        ]);
    }
}


