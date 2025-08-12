<?php

namespace App\Http\Requests\API\Friends;

use Illuminate\Foundation\Http\FormRequest;

class DestroyFriendRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $friendToDelete = $this->route('user');
        return auth()->user()->isFriendsWith($friendToDelete);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }
}
