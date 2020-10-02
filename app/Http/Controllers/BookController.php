<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Translation;
use App\Rules\BookExists;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function create(Translation $translation)
    {
        //
    }

    public function store(Translation $translation)
    {
        $attributes = request()->validate($this->rules($translation));
        $attributes['translation_id'] = $translation->id;
        $attributes['created_by'] = auth()->user()->id;

        $book = Book::create($attributes);

        return redirect()
            ->route('books.show', ['translation' => $translation, 'book' => $book])
            ->with('message', 'Book created.');
    }

    public function show(Translation $translation, Book $book)
    {
        //
    }

    public function showBook(Book $book)
    {
        return redirect()
            ->route('books.show', ['translation' => $book->translation, 'book' => $book]);
    }

    public function edit(Translation $translation, Book $book)
    {
        //
    }

    private function rules(Translation $translation, Book $book=null)
    {
        return [
            'name' => ['required', 'string', new BookExists($translation, $book)],
            'abbr' => 'required',
            'number' => 'required|integer|min:1',
            'chapter_limit' => 'required|integer|min:0'
        ];
    }
}
