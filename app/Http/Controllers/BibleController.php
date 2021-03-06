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

        return [
            'translation' => $translation,
            'books' => $translation->books()->orderBy('number')->get()
        ];
    }

    public function book(string $abbr, string $name) : array
    {
        $translation = Translation::with('copyright')
            ->where('abbr', $abbr)
            ->first();

        return [
            'translation' => $translation,
            'book' => $translation->books()->where('name', $name)->first()
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

    public function chapter(string $abbr, string $name, int $number) : array
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
            'chapter' => $book->chapters()->where('number', $number)->first()
        ];
    }
}
