<?php

namespace App\Http\Controllers;

use App\Enums\FriendRequestStatus;
use App\Http\Requests\StoreFriendShipRequest;
use App\Http\Requests\UpdateFriendShipRequest;
use App\Http\Resources\FriendRequestResource;
use App\Models\FriendRequest;

class FriendRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $friendRequests = FriendRequest::where('user_id', auth()->id())
                                    ->where('status', FriendRequestStatus::PENDING)
                                    ->get();

        return (new FriendRequestResource($friendRequests));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFriendShipRequest $request): FriendRequestResource
    {
        $friendRequest = FriendRequest::create(
            $request->merge(['user_id' => auth()->id()])->toArray()
        );

        $friendRequest->load(['user', 'friend']);

        return (new FriendRequestResource($friendRequest));
        
    }

    /**
     * Display the specified resource.
     */
    public function show(FriendRequest $friendShip)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreFriendShipRequest $request, FriendRequest $friendRequest): FriendRequestResource
    {
        $friendRequest->update(['status' => $request->decision]);
        $friendRequest->load(['user', 'friend']);

        return (new FriendRequestResource($friendRequest));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FriendRequest $friendShip)
    {
        //
    }
}
