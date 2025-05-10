<?php

namespace App\Observers;

use App\Enums\FriendRequestStatus;
use App\Models\FriendRequest;
use Illuminate\Support\Facades\Log;

class FriendRequesObserver
{
    /**
     * Handle the FriendRequest "created" event.
     */
    public function created(FriendRequest $friendRequest): void
    {
        //
    }

    /**
     * Handle the FriendRequest "updated" event.
     */
    public function updated(FriendRequest $friendRequest): void
    {
        if ($friendRequest->wasChanged('status') 
            && $friendRequest->status = FriendRequestStatus::ACCEPTED) {
                $friendRequest->acceptFriendRequest(friendRequestId: $friendRequest->friend_id);
        }
    }

    /**
     * Handle the FriendRequest "deleted" event.
     */
    public function deleted(FriendRequest $friendRequest): void
    {
        //
    }

    /**
     * Handle the FriendRequest "restored" event.
     */
    public function restored(FriendRequest $friendRequest): void
    {
        //
    }

    /**
     * Handle the FriendRequest "force deleted" event.
     */
    public function forceDeleted(FriendRequest $friendRequest): void
    {
        //
    }
}
