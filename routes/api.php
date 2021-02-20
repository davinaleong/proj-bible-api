<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BibleController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'api'], function() {
    Route::get('/translations', [BibleController::class, 'translations'])->name('translations');
    Route::get('/translations/{abbr}', [BibleController::class, 'translation'])->name('translation');
    Route::get('/translations/{abbr}/books', [BibleController::class, 'books'])->name('books');
    Route::get('/translations/{abbr}/books/{book}', [BibleController::class, 'book'])->name('book');
    Route::get('/translations/{abbr}/books/{book}/chapters', [BibleController::class, 'chapters'])->name('chapters');
    Route::get('/translations/{abbr}/books/{book}/chapters/{number}', [BibleController::class, 'chapter'])->name('chapter');
});
