<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAssetRequest;
use App\Http\Requests\UpdateAssetRequest;
use App\Http\Resources\AssetResource;
use App\Models\Asset;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    public function index(Request $request)
    {
         // Assuming you have authentication in place
         $user = $request->user();

        $perPage = $request->input('per_page', 10); // You can customize the default per page value
        $assets = Asset::where('user_id', $user->id)->paginate($perPage);

        if ($request->wantsJson()) {
            return AssetResource::collection($assets);
        }

        return view('assets.index', ['assets' => $assets]);
    }

    public function show(Request $request, Asset $asset)
    {
        if ($request->wantsJson()) {
            return new AssetResource($asset);
        }

        return view('assets.show', ['asset' => $asset]);
    }

    public function create()
    {
        return view('assets.create');
    }

    public function store(StoreAssetRequest $request)
    {

        $asset = Asset::create($request->all());

        if ($request->wantsJson()) {
            return new AssetResource($asset);
        }

        return redirect()->route('assets.index')->with('success', 'Asset created successfully!');
    }

    public function edit(Request $request, Asset $asset)
    {
        if ($request->wantsJson()) {
            return new AssetResource($asset);
        }

        return view('assets.edit', ['asset' => $asset]);
    }

    public function update(UpdateAssetRequest $request, Asset $asset)
    {

        $asset->update($request->all());

        if ($request->wantsJson()) {
            return new AssetResource($asset);
        }

        return redirect()->route('assets.index')->with('success', 'Asset updated successfully!');
    }

    public function destroy(Request $request, Asset $asset)
    {
        $asset->delete();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Asset deleted successfully']);
        }

        return redirect()->route('assets.index')->with('success', 'Asset deleted successfully!');
    }
}
