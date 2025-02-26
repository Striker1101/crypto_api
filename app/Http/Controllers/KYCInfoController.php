<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKYCInfoRequest;
use App\Http\Requests\UpdateKYCInfoRequest;
use App\Http\Resources\KYCInfoResource;
use App\Models\KYCInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class KYCInfoController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $kyc_info = $user->kyc_info()->paginate($request->input('per_page', 10));

        if ($request->wantsJson())
        {
            return KYCInfoResource::collection($kyc_info);
        }

        return view('kyc_info.index', ['kyc_info' => $kyc_info]);
    }

    public function show(KYCInfo $kyc_info)
    {
        $this->authorize('view', $kyc_info); // Check authorization to view the KYCInfo data

        return new KYCInfoResource($kyc_info);
    }

    public function create()
    {
        return view('kyc_info.create');
    }

    public function store(StoreKYCInfoRequest $request)
    {
        $user = Auth::user();

        // Associate the KYCInfo data with the current user
        $kyc_info = $user->kyc_info()->create($request->all());

        return new KYCInfoResource($kyc_info);
    }

    public function edit(KYCInfo $kyc_info)
    {
        $this->authorize('update', $kyc_info); // Check authorization to edit the KYCInfo data

        return view('kyc_info.edit', ['kyc_info' => $kyc_info]);
    }

    public function update(UpdateKYCInfoRequest $request, KYCInfo $kyc_info)
    {
        $this->authorize('update', $kyc_info); // Check authorization to update the KYCInfo data

        $kyc_info->update($request->all());

        return new KYCInfoResource($kyc_info);
    }

    public function destroy(KYCInfo $kyc_info)
    {
        $this->authorize('delete', $kyc_info); // Check authorization to delete the KYCInfo data

        $kyc_info->delete();

        return response()->json(['message' => 'KYCInfo data deleted successfully']);
    }
}
