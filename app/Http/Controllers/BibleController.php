<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Translation;
use Illuminate\Http\Request;

class BibleController extends Controller
{
    public function translations()
    {
        return [
            'translations' => Translation::with('copyright')->get()
        ];
    }

    public function translation(string $abbr)
    {
        return [
            'translation' => Translation::with('copyright')->where('abbr', $abbr)->first()
        ];
    }

    public function books(string $abbr)
    {
        $translation = Translation::with('copyright')->where('abbr', $abbr)->first();
        $books = Book::where('translation_id', $translation->id)->orderBy('number')->get();

        return [
            'translation' => $translation,
            'books' => $books
        ];
    }
}
