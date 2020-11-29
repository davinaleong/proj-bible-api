<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Breadcrumb;
use App\Models\Chapter;
use App\Models\Translation;
use App\Rules\ChapterNumberExists;
use Illuminate\Http\Request;

class ChapterController extends Controller
{
    public function create(Translation $translation, Book $book)
    {
        return view('chapters.create', [
            'breadcrumb' => Breadcrumb::items([
                [
                    'label' => 'Translations',
                    'href' => route('translations.index')
                ], [
                    'label' => $translation->abbr,
                    'href' => route('translations.show', ['translation' => $translation])
                ], [
                    'label' => $book->abbr,
                    'href' => route('books.show', ['translation' => $translation, 'book' => $book])
                ], [
                    'label' => 'Create Chapter',
                    'active' => true
                ]
            ]),
            'translation' => $translation,
            'book' => $book
        ]);
    }

    public function store(Translation $translation, Book $book)
    {
        $attributes = request()->validate($this->rules($book));
        $attributes['book_id'] = $book->id;
        $attributes['created_by'] = auth()->user()->id;

        $chapter = Chapter::create($attributes);

        return redirect()
            ->route('chapters.show', ['translation' => $translation, 'book' => $book, 'chapter' => $chapter])
            ->with('message', 'Chapter created.');
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

    public function edit(Translation $translation, Book $book, Chapter $chapter)
    {
        //
    }

    public function update(Translation $translation, Book $book, Chapter $chapter)
    {
        $attributes = request()->validate($this->rules($book, $chapter));
        $attributes['updated_by'] = auth()->user()->id;

        $chapter->update($attributes);

        return redirect()
            ->route('chapters.show', ['translation' => $translation, 'book' => $book, 'chapter' => $chapter])
            ->with('message', 'Chapter updated.');
    }

    public function destroy(Translation $translation, Book $book, Chapter $chapter)
    {
        //TODO: Delete verses
        $chapter->delete();

        return redirect()
            ->route('books.show', ['translation' => $translation, 'book' => $book])
            ->with('message', 'Chapter deleted.');
    }

    private function rules(Book $book, Chapter $chapter=null)
    {
        return [
            'number' => ['required', 'integer', 'min:1', "max:$book->chapter_limit", new ChapterNumberExists($book, $chapter)],
            'verse_limit' => 'required|integer|min:1'
        ];
    }
}
