<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CopyrightController;
use App\Http\Controllers\TranslationController;
use App\Http\Controllers\BookController;

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

    // Users
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::patch('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::patch('/users/{user}/change-password', [UserController::class, 'changePassword'])->name('users.change-password');

    // Copyrights
    Route::resource('copyrights', CopyrightController::class);

    // Translations
    Route::resource('translations', TranslationController::class);

    // Books
    Route::get('/books/{book}', [BookController::class, 'showBook'])->name('books.showBook');
    Route::get('/translations/{translation}/books/create', [BookController::class, 'create'])->name('books.create');
    Route::post('/translations/{translation}/books', [BookController::class, 'store'])->name('books.store');
    Route::get('/translations/{translation}/books/{book}', [BookController::class, 'show'])->name('books.show');
    Route::get('/translations/{translation}/books/{book}/edit', [BookController::class, 'edit'])->name('books.edit');
    Route::patch('/translations/{translation}/books/{book}', [BookController::class, 'update'])->name('books.update');
    Route::delete('/translations/{translation}/books/{book}', [BookController::class, 'destroy'])->name('books.destroy');
});
