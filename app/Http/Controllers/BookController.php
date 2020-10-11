<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Breadcrumb;
use App\Models\Translation;
use App\Rules\BookAbbrExists;
use App\Rules\BookNameExists;
use App\Rules\BookNumberExists;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function create(Translation $translation)
    {
        return view('books.create', [
            'breadcrumb' => Breadcrumb::items([
                [
                    'label' => 'Translations',
                    'href' => route('translations.index')
                ], [
                    'label' => 'ID: ' . $translation->id,
                    'href' => route('translations.show', ['translation' => $translation])
                ], [
                    'label' => 'Create Book',
                    'active' => true
                ]
            ]),
            'translation' => $translation
        ]);
    }

    public function store(Translation $translation)
    {
        $attributes = request()->validate($this->rules());

        if (Book::getBook($translation, $attributes['number'])) {
            return redirect()
                ->back()
                ->withErrors('Number exists for current translation.');
        }

        $attributes['translation_id'] = $translation->id;
        $attributes['created_by'] = auth()->user()->id;

        $book = Book::create($attributes);

        return redirect()
            ->route('books.show', ['translation' => $translation, 'book' => $book])
            ->with('message', 'Book created.');
    }

    public function show(Translation $translation, Book $book)
    {
        return view('books.show', [
            'breadcrumb' => Breadcrumb::items([
                [
                    'label' => 'Translations',
                    'href' => route('translations.index')
                ], [
                    'label' => 'ID: ' . $translation->id,
                    'href' => route('translations.show', ['translation' => $translation])
                ], [
                    'label' => 'Book ID: ' . $book->id,
                    'active' => true
                ]
            ]),
            'translation' => $translation,
            'book' => $book
        ]);
    }

    public function showBook(Book $book)
    {
        return redirect()
            ->route('books.show', ['translation' => $book->translation, 'book' => $book]);
    }

    public function edit(Translation $translation, Book $book)
    {
        return view('books.edit', [
            'breadcrumb' => Breadcrumb::items([
                [
                    'label' => 'Translations',
                    'href' => route('translations.index')
                ], [
                    'label' => 'ID: ' . $translation->id,
                    'href' => route('translations.show', ['translation' => $translation])
                ], [
                    'label' => 'Book ID: ' . $book->id,
                    'href' => route('books.show', ['translation' => $translation, 'book' => $book])
                ], [
                    'label' => 'Edit',
                    'active' => true
                ]
            ]),
            'translation' => $translation,
            'book' => $book
        ]);
    }

    public function update(Translation $translation, Book $book)
    {
        $attributes = request()->validate($this->rules());

        $otherBook = Book::getBook($translation, $attributes['number']);
        if ($otherBook->id != $book->id) {
            return redirect()
                ->back()
                ->withErrors('Number exists for current translation.');
        }
        $attributes['translation_id'] = $translation->id;
        $attributes['updated_by'] = auth()->user()->id;

        $book->update($attributes);

        return redirect()
            ->route('books.show', ['translation' => $translation, 'book' => $book])
            ->with('message', 'Book updated.');
    }

    private function rules()
    {
        return [
            'name' => 'required|string',
            'abbr' => 'required|string',
            'number' => 'required|integer|min:1|max:66',
            'chapter_limit' => 'required|integer|min:1'
        ];
    }
}
