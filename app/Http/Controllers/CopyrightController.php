<?php

namespace App\Http\Controllers;

use App\Models\Breadcrumb;
use App\Models\Copyright;
use Illuminate\Http\Request;

class CopyrightController extends Controller
{
    public function index()
    {
        return view('copyright.index', [
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
        return view('copyright.create', [
            'breadcrumb' => Breadcrumb::items([
                [
                    'label' => 'Copyrights',
                    'href' => route('copyright.index')
                ], [
                    'label' => 'Create',
                    'active' => true
                ]
            ])
        ]);
    }

    public function store()
    {
        $attributes = request()->validate([
            'name' => 'required',
            'text' => 'required'
        ]);

        $attributes['user_id'] = auth()->user()->id;

        $copyright = Copyright::create($attributes);
        return redirect()
            ->route('copyright.show', ['copyright' => $copyright])
            ->with('message', 'Copyright created.');
    }

    public function show(Copyright $copyright)
    {
        return view('copyright.show', [
            'breadcrumb' => Breadcrumb::items([
                [
                    'label' => 'Copyrights',
                    'href' => route('copyright.index')
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
        //
    }

    public function update(Copyright $copyright)
    {
        //
    }

    public function destroy(Copyright $copyright)
    {
        //
    }
}
