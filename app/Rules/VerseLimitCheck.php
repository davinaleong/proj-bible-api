<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class VerseLimitCheck implements Rule
{
    private $chapter;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($chapter)
    {
        $this->chapter = $chapter;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (!is_numeric($value)) return true;

        return ($this->chapter->verse_limit >= $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute field must be less than or equal to ' . $this->chapter->verse_limit . '.';
    }
}
