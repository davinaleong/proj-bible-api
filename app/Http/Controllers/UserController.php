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
}
