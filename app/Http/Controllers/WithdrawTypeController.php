<?php
namespace App\Http\Controllers;

use App\Models\WithdrawType;
use Illuminate\Http\Request;

class WithdrawTypeController extends Controller
{
    public function index()
    {
        return response()->json(WithdrawType::all(), 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'image' => 'required|string',
            'symbol' => 'required|string',
            'currency' => 'required|string',
            'type' => 'required|string',
            'bank_name' => 'nullable|required_if:type,bank|string',
            'routing_number' => 'nullable|string',
            'code' => 'nullable|string',
            'min_limit' => 'required|numeric|min:0',
            'max_limit' => 'required|numeric|min:0|gt:min_limit',
        ]);

        $withdrawType = WithdrawType::create($request->all());

        return response()->json($withdrawType, 201);
    }

    public function show($id)
    {
        $withdrawType = WithdrawType::find($id);
        if (!$withdrawType)
        {
            return response()->json(['message' => 'Withdraw type not found'], 404);
        }
        return response()->json($withdrawType);
    }

    public function update(Request $request, $id)
    {
        $withdrawType = WithdrawType::find($id);
        if (!$withdrawType)
        {
            return response()->json(['message' => 'Withdraw type not found'], 404);
        }

        $request->validate([
            'min_limit' => 'nullable|numeric|min:0',
            'max_limit' => 'nullable|numeric|min:0|gt:min_limit',
        ]);

        $withdrawType->update($request->all());

        return response()->json($withdrawType);
    }

    public function destroy($id)
    {
        $withdrawType = WithdrawType::find($id);
        if (!$withdrawType)
        {
            return response()->json(['message' => 'Withdraw type not found'], 404);
        }

        $withdrawType->delete();

        return response()->json(['message' => 'Withdraw type deleted successfully']);
    }
}
