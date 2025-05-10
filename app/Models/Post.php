<?php

namespace App\Models;

use App\Enums\PostPrivacy;
use Illuminate\Contracts\Database\Eloquent\Builder;
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

    public function scopeUserNewsFeed ($query) : Builder
    {
        $friends = auth()->user()->friends()->pluck('id');

        return $query->whereIn('user_id', $friends->push(auth()->user()->id))
                     ->latest();
    }
}
