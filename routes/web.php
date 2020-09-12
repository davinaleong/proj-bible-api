<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\IsAdmin;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth:sanctum', 'verified'])->group(function() {
    Route::get('/dashboard', function () {
        return view('dashboard', [
            'breadcrumb' => [
                [
                    'label' => 'Dashboard',
                    'active' => true
                ]
            ]
        ]);
    })->name('dashboard');

    Route::get('/profile/{user}', 'App\Http\Controllers\UserController@show')->name('users.show');
});
