<?php

namespace App\Http\Requests\API\Friends\Requests;

use App\Models\User;
use App\Rules\NoPendingFriendRequest;
use App\Rules\NotAlreadyFriends;
use App\Rules\NotSelf;
use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreFriendRequest extends FormRequest
{
    public ?User $recipient = null;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Маршрут уже защищён sanctum
    }

    public function prepareForValidation(): void
    {
        $this->recipient = User::where('friend_code', $this->friend_code)->first();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $currentUser = $this->user();
        return [
            'friend_code' => [
                'bail',
                'required',
                'string',
                'size:8',
                Rule::exists('users', 'friend_code'),
                new NotSelf(),
                new NotAlreadyFriends($this->recipient),
                new NoPendingFriendRequest($this->recipient),
            ]
        ];
    }
}
