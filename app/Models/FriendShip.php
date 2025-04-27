<?php

namespace App\Models;

use App\Enums\FriendShipStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FriendShip extends Model
{
    use HasFactory;

    protected $guarded = [];
    
    protected $casts = [
        'status' => FriendShipStatus::class
    ];
}
