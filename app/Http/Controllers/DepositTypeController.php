<?php

namespace App\Http\Controllers;

use App\Models\DepositType;
use Illuminate\Http\Request;
use App\Http\Requests\StoreDepositTypeRequest;
use App\Http\Requests\UpdateDepositTypeRequest;
use Inertia\Inertia;

class DepositTypeController extends Controller
{
    public function index()
    {
        return response()->json(DepositType::all());
    }
    public function edit()
    {
        // Fetch all plans
        $depositTypes = DepositType::all();
        // Pass the user to the view
        return Inertia::render('DepositType', [
            'depositTypes' => $depositTypes,
        ]);
    }

    public function store(StoreDepositTypeRequest $request)
    {
        $depositType = DepositType::create($request->all());
        return response()->json($depositType, 201);
    }

    public function show(DepositType $depositType)
    {
        return response()->json($depositType);
    }

    public function update(UpdateDepositTypeRequest $request, DepositType $depositType)
    {
        $depositType->update($request->validated());
        return response()->json($depositType);
    }

    public function destroy(DepositType $depositType)
    {
        $depositType->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}
