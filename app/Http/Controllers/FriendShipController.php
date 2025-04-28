<?php

namespace App\Http\Controllers;

use App\Models\FriendShip;
use App\Http\Requests\StoreFriendShipRequest;
use App\Http\Requests\UpdateFriendShipRequest;
use App\Http\Resources\FriendShipResource;

class FriendShipController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFriendShipRequest $request)
    {
        $friendRequest = FriendShip::create(
            $request->merge(['user_id' => auth()->id()])->toArray()
        );
        $friendRequest->load(['user', 'friend']);

        return (new FriendShipResource($friendRequest));
        
    }

    /**
     * Display the specified resource.
     */
    public function show(FriendShip $friendShip)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFriendShipRequest $request, FriendShip $friendShip)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FriendShip $friendShip)
    {
        //
    }
}
