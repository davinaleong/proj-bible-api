<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CopyrightController;

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

    Route::get('/profile/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('/profile/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::patch('/profile/{user}', [UserController::class, 'update'])->name('users.update');
    Route::patch('/profile/{user}/change-password', [UserController::class, 'changePassword'])->name('users.change-password');

    Route::resource('copyright', CopyrightController::class);
});
