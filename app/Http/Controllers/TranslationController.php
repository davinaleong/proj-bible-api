<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Breadcrumb;
use App\Models\Chapter;
use App\Models\Copyright;
use App\Models\Translation;
use Illuminate\Http\Request;

class TranslationController extends Controller
{
    public function index()
    {
        return view('translations.index', [
            'breadcrumb' => Breadcrumb::items([
                [
                    'label' => 'Translations',
                    'active' => true
                ]
            ]),
            'translations' => Translation::orderBy('name')->get()
        ]);
    }

    public function create()
    {
        return view('translations.create', [
            'breadcrumb' => Breadcrumb::items([
                [
                    'label' => 'Translations',
                    'href' => route('translations.index')
                ], [
                    'label' => 'Create',
                    'active' => true
                ]
            ]),
            'copyrights' => Copyright::orderBy('name')->get()
        ]);
    }

    public function store()
    {
        $attributes = request()->validate($this->rules());

        $attributes['created_by'] = auth()->user()->id;
        $attributes['updated_by'] = null;
        $translation = Translation::create($attributes);

        return redirect()
            ->route('translations.show', ['translation' => $translation])
            ->with('message', 'Translation created.');
    }

    public function show(Translation $translation)
    {
        return view('translations.show', [
            'breadcrumb' => Breadcrumb::items([
                [
                    'label' => 'Translations',
                    'href' => route('translations.index')
                ], [
                    'label' => $translation->abbr,
                    'active' => true
                ]
            ]),
            'translation' => $translation
        ]);
    }

    public function edit(Translation $translation)
    {
        return view('translations.edit', [
            'breadcrumb' => Breadcrumb::items([
                [
                    'label' => 'Translations',
                    'href' => route('translations.index')
                ], [
                    'label' => $translation->abbr,
                    'href' => route('translations.show', ['translation' => $translation])
                ], [
                    'label' => 'Edit',
                    'active' => true
                ]
            ]),
            'translation' => $translation,
            'copyrights' => Copyright::orderBy('name')->get()
        ]);
    }

    public function update(Translation $translation)
    {
        $attributes = request()->validate($this->rules(true, $translation->abbr));

        $translation->name = $attributes['name'];
        $translation->abbr = $attributes['abbr'];
        $translation->copyright_id = $attributes['copyright_id'];
        $translation->updated_by = auth()->user()->id;
        $translation->save();

        return redirect()
            ->route('translations.show', ['translation' => $translation])
            ->with('message', 'Translation updated.');
    }

    public function destroy(Translation $translation)
    {
        $books = Book::where(['translation_id' => $translation->id])->get();
        foreach($books as $book) {
            //TODO: Delete verses
            Chapter::where(['book_id' => $book->id])->delete();
        }
        Book::where(['translation_id' => $translation->id])->delete();
        $translation->delete();

        return redirect()
            ->route('translations.index')
            ->with('message', 'Translation deleted.');
    }

    private function rules($edit = false, $abbr = '')
    {
        $rules = [
            'name' => 'required',
            'abbr' => 'required|unique:translations,abbr',
            'copyright_id' => 'required|exists:copyrights,id'
        ];

        if ($edit) {
            $rules['abbr'] = "required|unique:translations,abbr,{$abbr}";
        }

        return $rules;
    }
}
