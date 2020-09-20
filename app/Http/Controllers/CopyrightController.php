<?php

namespace App\Http\Controllers;

use App\Models\Copyright;
use Illuminate\Http\Request;

class CopyrightController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        //
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
        //
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
