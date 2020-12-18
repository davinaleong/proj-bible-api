<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Chapter;
use App\Models\Translation;
use App\Models\Verse;
use Illuminate\Http\Request;

class VerseController extends Controller
{
    public function create(Translation $translation, Book $book, Chapter $chapter)
    {
        //
    }

    public function store(Translation $translation, Book $book, Chapter $chapter)
    {
        //
    }

    public function show(Translation $translation, Book $book, Chapter $chapter, Verse $verse)
    {
        //
    }

    public function showVerse(Verse $verse)
    {
        return redirect()
            ->route('verses.show', [
                'translation' => $verse->chapter->book->translation,
                'book' => $verse->chapter->book,
                'chapter' => $verse->chapter,
                'verse' => $verse
            ]);
    }

    public function edit(Translation $translation, Book $book, Chapter $chapter, Verse $verse)
    {
        //
    }

    public function update(Translation $translation, Book $book, Chapter $chapter, Verse $verse)
    {
        //
    }

    public function destroy(Translation $translation, Book $book, Chapter $chapter, Verse $verse)
    {
        //
    }
}
