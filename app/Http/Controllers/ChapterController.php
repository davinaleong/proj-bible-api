<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Chapter;
use App\Models\Translation;
use Illuminate\Http\Request;

class ChapterController extends Controller
{
    public function create(Translation $translation, Book $book)
    {
        //
    }

    public function showChapter(Chapter $chapter)
    {
        return redirect()
            ->route('chapters.show',
                ['translation' => $chapter->book->translation, 'book' => $chapter->book, 'chapter' => $chapter]);
    }

    public function show(Translation $translation, Book $book, Chapter $chapter)
    {
        //
    }
}
