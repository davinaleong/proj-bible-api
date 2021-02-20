<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Translation;
use Illuminate\Http\Request;

class BibleController extends Controller
{
    public function translations() : array
    {
        return [
            'translations' => Translation::with('copyright')->get()
        ];
    }

    public function translation(string $abbr) : array
    {
        return [
            'translation' => Translation::with('copyright')
                ->where('abbr', $abbr)
                ->first()
        ];
    }

    public function books(string $abbr) : array
    {
        $translation = Translation::with('copyright')
            ->where('abbr', $abbr)
            ->first();
        $books = Book::where('translation_id', $translation->id)
            ->orderBy('number')
            ->get();

        return [
            'translation' => $translation,
            'books' => $books
        ];
    }

    public function book(string $abbr, string $name) : array
    {
        $translation = Translation::with('copyright')
            ->where('abbr', $abbr)
            ->first();
        $book = Book::where('translation_id', $translation->id)
            ->where('name', $name)
            ->first();

        return [
            'translation' => $translation,
            'book' => $book
        ];
    }

    public function chapters(string $abbr, string $name) : array
    {
        $translation = Translation::with('copyright')
            ->where('abbr', $abbr)
            ->first();
        $book = Book::where('translation_id', $translation->id)
            ->where('name', $name)
            ->first();

        return [
            'translation' => $translation,
            'book' => $book,
            'chapters' => $book->chapters()->orderBy('number')->get()
        ];
    }
}
