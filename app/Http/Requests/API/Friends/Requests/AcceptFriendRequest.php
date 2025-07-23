<?php

namespace App\Http\Requests\API\Friends\Requests;

use App\Enums\FriendRequestStatus;
use http\Client\Curl\User;
use Illuminate\Foundation\Http\FormRequest;

class AcceptFriendRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $friendRequest = $this->route('friendRequest');
        // Проверяем не только получателя, но и то, что запрос все еще "висящий"
        return $friendRequest
            && $friendRequest->recipient_id === auth()->id()
            && $friendRequest->status === FriendRequestStatus::Pending;
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
