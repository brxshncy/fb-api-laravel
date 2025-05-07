<?php

namespace App\Http\Requests;

use App\Models\FriendRequest;
use Illuminate\Foundation\Http\FormRequest;
use Closure;

class StoreFriendShipRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'friend_id' => [
                'required',
                'integer',
                'exists:users,id',
                'not_in:' . auth()->id(),
                function (string $attribute, mixed $value, Closure $fail) {
                    if (FriendRequest::where('user_id', auth()->id())
                        ->where('friend_id', $value)->exists()) {
                        $fail('Friend request already sent.');
                    }
                }
            ]
        ];
    }
}
