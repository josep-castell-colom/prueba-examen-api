<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommunityResource;
use App\Models\Community;
use Illuminate\Http\Request;

class CommunityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return CommunityResource::collection(Community::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required | string',
            'description' => 'required | string',
            'rules' => 'required | string',
        ]);

        $community = Community::factory()->create($validated);

        return CommunityResource::make($community);
    }

    /**
     * Display the specified resource.
     */
    public function show(Community $community)
    {
        return CommunityResource::make($community);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Community $community)
    {
        $validated = $request->validate([
            'name' => 'string',
            'description' => 'string',
            'rules' => 'string',
        ]);

        $community->update($validated);

        return CommunityResource::make($community);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Community $community)
    {
        $community->delete();

        return response()->noContent();
    }
}
