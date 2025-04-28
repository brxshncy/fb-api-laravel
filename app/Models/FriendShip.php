<?php

namespace App\Models;

use App\Enums\FriendShipStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FriendShip extends Model
{
    use HasFactory;

    protected $guarded = [];
    
    protected $casts = [
        'status' => FriendShipStatus::class
    ];

    protected $attributes = [
        'status' => FriendShipStatus::PENDING
    ];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function friend(): BelongsTo
    {
        return $this->belongsTo(User::class, 'friend_id');
    }
}
