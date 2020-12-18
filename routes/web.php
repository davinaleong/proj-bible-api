<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CopyrightController;
use App\Http\Controllers\TranslationController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\ChapterController;

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

    //#region Users
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::patch('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::patch('/users/{user}/change-password', [UserController::class, 'changePassword'])->name('users.change-password');
    //#endregion

    //#region Copyrights
    Route::resource('copyrights', CopyrightController::class);
    //#endregion

    //#region Translations
    Route::resource('translations', TranslationController::class);
    //#endregion

    //#region Books
    Route::get('/books/{book}', [BookController::class, 'showBook'])->name('books.showBook');
    Route::get('/translations/{translation}/books/create', [BookController::class, 'create'])->name('books.create');
    Route::post('/translations/{translation}/books', [BookController::class, 'store'])->name('books.store');
    Route::get('/translations/{translation}/books/{book}', [BookController::class, 'show'])->name('books.show');
    Route::get('/translations/{translation}/books/{book}/edit', [BookController::class, 'edit'])->name('books.edit');
    Route::patch('/translations/{translation}/books/{book}', [BookController::class, 'update'])->name('books.update');
    Route::delete('/translations/{translation}/books/{book}', [BookController::class, 'destroy'])->name('books.destroy');
    //#endregion

    //#region Chapters
    Route::get('/chapters/{chapter}', [ChapterController::class, 'showChapter'])->name('chapters.showChapter');
    Route::get('/translations/{translation}/books/{book}/chapters/create', [ChapterController::class, 'create'])->name('chapters.create');
    Route::post('/translations/{translation}/books/{book}/chapters', [ChapterController::class, 'store'])->name('chapters.store');
    Route::get('/translations/{translation}/books/{book}/chapters/{chapter}', [ChapterController::class, 'show'])->name('chapters.show');
    Route::get('/translations/{translation}/books/{book}/chapters/{chapter}/edit', [ChapterController::class, 'edit'])->name('chapters.edit');
    Route::patch('/translations/{translation}/books/{book}/chapters/{chapter}', [ChapterController::class, 'update'])->name('chapters.update');
    Route::delete('/translations/{translation}/books/{book}/chapters/{chapter}', [ChapterController::class, 'destroy'])->name('chapters.destroy');
    //#endregion

    //#region Verses
    // TODO: Test manage verses
    // TODO: Verse controller
    // TODO: Verse routes
    // TODO: Verse views
    //#endregion
});
