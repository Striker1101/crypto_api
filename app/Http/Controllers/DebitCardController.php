<?php

namespace App\Http\Controllers;

use App\Http\Resources\DebitCardResource;
use App\Models\DebitCard;
use App\Http\Requests\StoreDebitCardRequest;
use App\Http\Requests\UpdateDebitCardRequest;
use Illuminate\Http\Request;

class DebitCardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // authentication in place
        $user = $request->user();

        // Customize the default per page value
        $perPage = $request->input('per_page', 10);

        // Retrieve only the debit cards associated with the current user
        $debit_card = DebitCard::where('user_id', $user->id)->paginate($perPage);

        if ($request->wantsJson()) {
            return DebitCardResource::collection($debit_card);
        }

        return view('debitCard.index', ['debitCard' => $debit_card]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
         return view('debitCard.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDebitCardRequest $request)
    {
        //
        $debitCard = DebitCard::create($request->all());

        if ($request->wantsJson()) {
            return new DebitCardResource($debitCard);
        }

        return redirect()->route('debitCard.index')->with('success', 'card created successfully!');

    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, DebitCard $debitCard)
    {
        //
        if ($request->wantsJson()) {
            return new DebitCardResource($debitCard);
        }

        return view('debitCard.show', ['debitCard' => $debitCard]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request,DebitCard $debitCard)
    {
        //
        if ($request->wantsJson()) {
            return new DebitCardResource($debitCard);
        }

        return view('debitCard.edit', ['asset' => $debitCard]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDebitCardRequest $request, DebitCard $debitCard)
    {
        //
        $debitCard->update($request->all());

        if ($request->wantsJson()) {
            return new DebitCardResource($debitCard);
        }

        return redirect()->route('debitCard.index')->with('success', 'debitCard updated successfully!');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request,DebitCard $debitCard)
    {
        //
        $debitCard->delete();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'debitCard deleted successfully']);
        }

        return redirect()->route('debitCard.index')->with('success', 'debitCard deleted successfully!');
    }
}
