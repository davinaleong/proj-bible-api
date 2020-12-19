<?php

namespace App\Rules;

use App\Models\Chapter;
use App\Models\Verse;
use Illuminate\Contracts\Validation\Rule;

class VerseNumberExists implements Rule
{
    private $chapter = null;
    private $verse = null;

    /**
     * VerseNumberExists constructor.
     * @param Chapter $chapter
     * @param Verse|null $verse
     */
    public function __construct(Chapter $chapter, Verse $verse=null)
    {
        $this->chapter = $chapter;
        $this->verse = $verse;
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
        $verse = Verse::where([
            'chapter_id' => $this->chapter->id,
            'number' => $value
        ])->first();

        if (filled($verse) && filled($this->verse) && $verse->id == $this->verse->id) {
            return true;
        }

        return blank($verse);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The validation error message.';
    }
}
