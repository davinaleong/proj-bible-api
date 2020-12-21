<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Breadcrumb;
use App\Models\Chapter;
use App\Models\Translation;
use App\Models\Verse;
use App\Rules\VerseNumberExists;
use Illuminate\Http\Request;

class VerseController extends Controller
{
    public function create(Translation $translation, Book $book, Chapter $chapter)
    {
        return view('verses.create', [
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
                    'label' => $chapter->number,
                    'href' => route('chapters.show', ['translation' => $translation, 'book' => $book, 'chapter' => $chapter])
                ], [
                    'label' => 'Create Verse',
                    'active' => true
                ]
            ]),
            'translation' => $translation,
            'book' => $book,
            'chapter' => $chapter
        ]);
    }

    public function store(Translation $translation, Book $book, Chapter $chapter)
    {
        $attributes = request()->validate($this->rules($chapter));
        $attributes['chapter_id'] = $chapter->id;
        $attributes['created_by'] = auth()->user()->id;
        $verse = Verse::create($attributes);
        return redirect()
            ->route('verses.show', [
                'translation' => $translation,
                'book' => $book,
                'chapter' => $chapter,
                'verse' => $verse
            ])
            ->with('message', 'Verse created.');
    }

    public function show(Translation $translation, Book $book, Chapter $chapter, Verse $verse)
    {
        return view('verses.show', [
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
                    'label' => $chapter->number,
                    'href' => route('chapters.show', ['translation' => $translation, 'book' => $book, 'chapter' => $chapter])
                ], [
                    'label' => $verse->number,
                    'active' => true
                ]
            ]),
            'translation' => $translation,
            'book' => $book,
            'chapter' => $chapter,
            'verse' => $verse
        ]);
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
        $attributes = request()->validate($this->rules($chapter, $verse));
        $attributes['updated_by'] = auth()->user()->id;
        $verse->update($attributes);
        return redirect()
            ->route('verses.show', [
                'translation' => $translation,
                'book' => $book,
                'chapter' => $chapter,
                'verse' => $verse
            ])
            ->with('message', 'Verse updated.');
    }

    public function destroy(Translation $translation, Book $book, Chapter $chapter, Verse $verse)
    {
        $verse->delete();
        return redirect()
            ->route('chapters.show', [
                'translation' => $translation,
                'book' => $book,
                'chapter' => $chapter
            ])
            ->with('message', 'Verse deleted.');
    }

    private function rules(Chapter $chapter, Verse $verse=null)
    {
        return [
            'number' => ['required', 'string', new VerseNumberExists($chapter, $verse)],
            'passage' => 'required|string'
        ];
    }
}
