<?php

namespace App\Http\Controllers;

use App\Models\Breadcrumb;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function show(User $user)
    {
        if (auth()->user()->id !== $user->id) {
            return redirect()
                ->route('dashboard')
                ->with('message', 'You can only view your own profile.');
        }

        return view('user.show', [
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

        return view('user.edit', [
            'breadcrumb' => Breadcrumb::items([
                [
                    'label' => 'Profile',
                    'href' => route('user.show', ['user' => $user])
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
                ->route('user.show', ['user' => $user])
                ->with('message', 'Profile updated.');
        }

        return redirect()
            ->route('user.show', ['user' => $user])
            ->withErrors('Unable to update profile.');
    }

    public function changePassword(User $user)
    {
        extract(request()->validate([
            'password' => 'required|min:8',
            'new_password' => 'required|min:8',
            'confirm_new_password' => 'required|min:8|same:new_password'
        ]));

        if (!Hash::check($password, $user->password)) {
            return redirect()
                ->route('user.edit', ['user' => $user])
                ->withErrors('Password is incorrect');
        }

        $user->password = Hash::make($new_password);
        if ($user->save()) {
            return redirect()
                ->route('user.show', ['user' => $user])
                ->with('message', 'Password changed.');
        }

        return redirect()
            ->route('user.show', ['user' => $user])
            ->withErrors('Unable to change password.');
    }
}
