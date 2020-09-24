<?php

namespace App\Http\Controllers;

use App\Models\Breadcrumb;
use App\Models\Copyright;
use App\Models\Translation;
use Illuminate\Http\Request;

class TranslationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Translation $translation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Translation $translation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Translation $translation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Translation $translation)
    {
        //
    }

    private function rules()
    {
        return [
            'name' => 'required',
            'abbr' => 'required',
            'copyright_id' => 'required|exists:copyrights,id'
        ];
    }
}
