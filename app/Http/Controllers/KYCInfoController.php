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
        $kycInfo = $user->kycInfo()->paginate($request->input('per_page', 10));

        if ($request->wantsJson()) {
            return KYCInfoResource::collection($kycInfo);
        }

        return view('kyc_info.index', ['kycInfo' => $kycInfo]);
    }

    public function show(KYCInfo $kycInfo)
    {
        $this->authorize('view', $kycInfo); // Check authorization to view the KYCInfo data

        return new KYCInfoResource($kycInfo);
    }

    public function create()
    {
        return view('kyc_info.create');
    }

    public function store(StoreKYCInfoRequest $request)
    {
        $user = Auth::user();

        // Associate the KYCInfo data with the current user
        $kycInfo = $user->kycInfo()->create($request->all());

        return new KYCInfoResource($kycInfo);
    }

    public function edit(KYCInfo $kycInfo)
    {
        $this->authorize('update', $kycInfo); // Check authorization to edit the KYCInfo data

        return view('kyc_info.edit', ['kycInfo' => $kycInfo]);
    }

    public function update(UpdateKYCInfoRequest $request, KYCInfo $kycInfo)
    {
        $this->authorize('update', $kycInfo); // Check authorization to update the KYCInfo data

        $kycInfo->update($request->all());

        return new KYCInfoResource($kycInfo);
    }

    public function destroy(KYCInfo $kycInfo)
    {
        $this->authorize('delete', $kycInfo); // Check authorization to delete the KYCInfo data

        $kycInfo->delete();

        return response()->json(['message' => 'KYCInfo data deleted successfully']);
    }
}
