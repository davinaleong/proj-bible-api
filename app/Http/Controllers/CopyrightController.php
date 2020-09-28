<?php

namespace App\Http\Controllers;

use App\Models\Breadcrumb;
use App\Models\Copyright;
use Illuminate\Http\Request;

class CopyrightController extends Controller
{
    public function index()
    {
        return view('copyrights.index', [
            'breadcrumb' => Breadcrumb::items([
                [
                    'label' => 'Copyrights',
                    'active' => true
                ]
            ]),
            'copyrights' => Copyright::orderBy('name')->get()
        ]);
    }

    public function create()
    {
        return view('copyrights.create', [
            'breadcrumb' => Breadcrumb::items([
                [
                    'label' => 'Copyrights',
                    'href' => route('copyrights.index')
                ], [
                    'label' => 'Create',
                    'active' => true
                ]
            ])
        ]);
    }

    public function store()
    {
        $attributes = request()->validate($this->rules());

        $attributes['created_by'] = auth()->user()->id;
        $attributes['updated_by'] = null;
        $copyright = Copyright::create($attributes);

        return redirect()
            ->route('copyrights.show', ['copyright' => $copyright])
            ->with('message', 'Copyright created.');
    }

    public function show(Copyright $copyright)
    {
        return view('copyrights.show', [
            'breadcrumb' => Breadcrumb::items([
                [
                    'label' => 'Copyrights',
                    'href' => route('copyrights.index')
                ], [
                    'label' => 'ID: ' . $copyright->id,
                    'active' => true
                ]
            ]),
            'copyright' => $copyright
        ]);
    }

    public function edit(Copyright $copyright)
    {
        return view('copyrights.edit', [
            'breadcrumb' => Breadcrumb::items([
                [
                    'label' => 'Copyrights',
                    'href' => route('copyrights.index')
                ], [
                    'label' => 'ID: ' . $copyright->id,
                    'href' => route('copyrights.show', ['copyright' => $copyright])
                ], [
                    'label' => 'Edit',
                    'active' => true
                ]
            ]),
            'copyright' => $copyright
        ]);
    }

    public function update(Copyright $copyright)
    {
        $attributes = request()->validate($this->rules());

        $copyright->name = $attributes['name'];
        $copyright->text = $attributes['text'];
        $copyright->updated_by = auth()->user()->id;
        $copyright->save();

        return redirect()
            ->route('copyrights.show', ['copyright' => $copyright])
            ->with('message', 'Copyright updated.');
    }

    public function destroy(Copyright $copyright)
    {
        $copyright->delete();

        return redirect()
            ->route('copyrights.index')
            ->with('message', 'Copyright deleted.');
    }

    private function rules()
    {
        return [
            'name' => 'required',
            'text' => 'required'
        ];
    }
}
