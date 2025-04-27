<?php

namespace App\Models;

use App\Enums\PostPrivacy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    use HasFactory;

    protected $guarded = [];
    
    protected $casts = [
        'privacy' => PostPrivacy::class
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
