<?php

namespace App\Rules;

use App\Models\Book;
use App\Models\Translation;
use Illuminate\Contracts\Validation\Rule;

class BookAbbrExists implements Rule
{
    public $translation;
    public $book;

    /**
     * BookAbbrExists constructor.
     * @param Translation $translation
     * @param Book|null $book
     */
    public function __construct(Translation $translation, Book $book=null)
    {
        $this->translation = $translation;
        $this->book = $book;
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
        $book = Book::where([
            'translation_id' => $this->translation->id,
            'abbr' => $value
        ])->get();

        if (filled($this->book) && filled($book)) {
            return $this->book->id === $book[0]->id;
        }

        return blank($book);
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
