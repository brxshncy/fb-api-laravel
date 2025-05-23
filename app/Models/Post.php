<?php

namespace App\Models;

use App\Enums\PostPrivacy;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

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
        $friends = auth()->user()->friends()->pluck('friend_id');

        return $query->with('user')
                     ->whereIn('user_id', $friends->push(auth()->user()->id))
                     ->latest();
    }
}
