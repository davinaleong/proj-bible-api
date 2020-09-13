<?php

namespace App\Http\Controllers;

use App\Models\Breadcrumb;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show(User $user)
    {
        if (auth()->user()->id !== $user->id) {
            return redirect()
                ->route('dashboard')
                ->with('message', 'You can only view your own profile.');
        }

        return view('users.show', [
            'breadcrumb' => Breadcrumb::items([
                [
                    'label' => 'Profile',
                    'active' => true
                ]
            ]),
            'user' => $user
        ]);
    }

    public function edit(User $user)
    {
        if (auth()->user()->id !== $user->id) {
            return redirect()
                ->route('dashboard')
                ->with('message', 'You can only edit your own profile.');
        }

        return view('users.edit', [
            'breadcrumb' => Breadcrumb::items([
                [
                    'label' => 'Profile',
                    'href' => route('users.show', ['user' => $user])
                ], [
                    'label' => 'Edit',
                    'active' => true
                ]
            ]),
            'user' => $user
        ]);
    }

    public function update(User $user)
    {
        extract(request()->validate([
            'name' => 'required'
        ]));

        $user->name = $name;
        if ($user->save()) {
            return redirect()
                ->route('users.show', ['user' => $user])
                ->with('message', 'Profile updated.');
        }

        return redirect()
            ->route('users.show', ['user' => $user])
            ->with('message', 'Unable to update profile.');
    }
}
