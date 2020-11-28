<?php

namespace App\Rules;

use App\Models\Book;
use App\Models\Chapter;
use Illuminate\Contracts\Validation\Rule;

class ChapterNumberExists implements Rule
{
    private $book = null;
    private $chapter = null;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(Book $book, Chapter $chapter=null)
    {
        $this->book = $book;
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
        if (!ctype_digit($value) && !is_int($value)) {
            return false;
        }

        $chapter = Chapter::where([
            'book_id' => $this->book->id,
            'number' => $value
        ])->first();

        if(filled($chapter) && filled($this->chapter) && $chapter->id == $this->chapter->id) {
            return true;
        }

        return blank($chapter);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute of the chapter exists for the current book.';
    }
}
