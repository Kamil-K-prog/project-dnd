<?php

namespace App\Rules;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NoPendingFriendRequest implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (auth()->user()->hasPendingRequestWith(User::where('friend_code', $value)->first())){
            $fail("У вас уже есть активный запрос в друзья с этим пользователем.");
        }
    }
}
