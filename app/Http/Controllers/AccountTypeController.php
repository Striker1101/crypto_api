<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAccountTypeRequest;
use App\Http\Requests\UpdateAccountTypeRequest;
use App\Models\AccountType;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AccountTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $accountTypes = AccountType::all();

        return request()->wantsJson()
            ? response()->json($accountTypes)
            : view('account_types.index', compact('accountTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('account_types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAccountTypeRequest $request)
    {
        $accountType = AccountType::create($request->validated());

        return $request->wantsJson()
            ? response()->json(['message' => 'Account Type created', 'data' => $accountType])
            : redirect()->back()->with('success', 'Account Type created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(AccountType $accountType)
    {
        return request()->wantsJson()
            ? response()->json($accountType)
            : view('account_types.show', compact('accountType'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        // Fetch all plans
        $accountTypes = AccountType::all();
        // Pass the user to the view
        return Inertia::render('AccountType', [
            'accountTypes' => $accountTypes,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAccountTypeRequest $request, AccountType $accountType)
    {
        $accountType->update($request->validated());

        return $request->wantsJson()
            ? response()->json(['message' => 'Account Type updated', 'data' => $accountType])
            : redirect()->back()->with('success', 'Account Type updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AccountType $accountType)
    {
        $accountType->delete();

        return request()->wantsJson()
            ? response()->json(['message' => 'Account Type deleted'])
            : redirect()->back()->with('success', 'Account Type deleted successfully.');
    }

    /**
     * Bulk upload account types from array or file.
     */
    public function bulkUpload(Request $request)
    {
        // Handle JSON upload or file (CSV/XLSX)
        $data = $request->input('account_types');

        if (!$data && $request->hasFile('file'))
        {
            $file = $request->file('file');
            // You can integrate Excel/CSV parser here (like Laravel Excel or fgetcsv)
            return response()->json(['message' => 'File parsing not implemented'], 501);
        }

        if (!is_array($data))
        {
            return response()->json(['message' => 'Invalid data format'], 422);
        }

        foreach ($data as $entry)
        {
            AccountType::create($entry); // You can validate each $entry if needed
        }

        return $request->wantsJson()
            ? response()->json(['message' => 'Bulk upload completed'])
            : redirect()->back()->with('success', 'Bulk upload completed.');
    }
}
