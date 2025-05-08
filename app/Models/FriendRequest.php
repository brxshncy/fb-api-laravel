<?php

namespace App\Models;

use App\Enums\FriendRequestStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FriendRequest extends Model
{
    use HasFactory;

    protected $guarded = [];
    
    protected $casts = [
        'status' => FriendRequestStatus::class
    ];

    protected $attributes = [
        'status' => FriendRequestStatus::PENDING
    ];
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function friendRequestSentTo(): BelongsTo 
    {
        return $this->belongsTo(User::class, 'friend_id');
    }

}
