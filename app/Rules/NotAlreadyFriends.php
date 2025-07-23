<?php

namespace App\Rules;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NotAlreadyFriends implements ValidationRule
{
    protected ?User $recipient;

    public function __construct(?User $recipient)
    {
        $this->recipient = $recipient;
    }

    /**
     * Run the validation rule.
     *
     * @param \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (auth()->user()->isFriendsWith($this->recipient)) {
            $fail("Вы уже дружите с этим пользователем.");
        }
    }
}
