<?php

namespace App\Http\Controllers;

use App\Models\Breadcrumb;
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
                    'label' => 'ID: ' . $translation->id,
                    'active' => true
                ]
            ]),
            'translation' => $translation
        ]);
    }

    public function edit(Translation $translation)
    {
        //
    }

    public function update(Translation $translation)
    {
        //
    }

    public function destroy(Translation $translation)
    {
        //
    }

    private function rules($edit = false)
    {
        $rules = [
            'name' => 'required',
            'abbr' => 'required|unique:translations,abbr',
            'copyright_id' => 'required|exists:copyrights,id'
        ];

        return $rules;
    }
}
