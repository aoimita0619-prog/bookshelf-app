<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReadingPlanController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RankingController;
use App\Http\Controllers\ReviewLikeController;




/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [BookController::class, 'index']);
Route::get('/books', [BookController::class, 'index'])->name('books.index');
Route::get('/ranking', [RankingController::class, 'index'])->name('ranking.index');



Route::middleware('auth')->group(function(){
    Route::get('/books/isbn/{isbn}', [BookController::class, 'isbnSearch'])->name('books.isbn');
    Route::get('/books/create', [BookController::class, 'create'])->name('books.create');
    Route::post('/books/create', [BookController::class, 'store'])->name('books.store');
    Route::get('/books/{book}/edit', [BookController::class, 'edit'])->name('books.edit');
    Route::put('/books/{book}/edit', [BookController::class, 'update'])->name('books.update');
    Route::delete('/books/{book}/delete', [BookController::class, 'delete'])->name('books.destroy');
    Route::get('/genres/create', [GenreController::class, 'create'])->name('genres.create');
    Route::post('/genres/create', [GenreController::class, 'store'])->name('genres.store');
    Route::get('/genres/{genre}/edit', [GenreController::class, 'edit'])->name('genres.edit');
    Route::put('/genres/{genre}/edit', [GenreController::class, 'update'])->name('genres.update');
    Route::get('/genres', [GenreController::class, 'index'])->name('genres.index');
    Route::get('/genres/{genre}', [GenreController::class, 'show'])->name('genres.show');
    Route::delete('/genres/{genre}/delete', [GenreController::class, 'delete'])->name('genres.destroy');
    Route::get('/reviews', [ReviewController::class, 'index']) ->name('reviews.index'); 
    Route::post('/books/{book}/review', [ReviewController::class, 'store'])->name('reviews.store');
    Route::get('/reviews/{review}/edit', [ReviewController::class, 'edit'])->name('reviews.edit');
    Route::put('/books/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/books/{review}/delete', [ReviewController::class, 'delete'])->name('reviews.destroy');
    Route::post('/reviews/{review}/like', [ReviewLikeController::class, 'toggle'])
    ->name('reviews.like');
    Route::get('/favorites', [FavoriteController::class, 'index']) ->name('favorites.index'); 
    Route::post('/favorites/{book}', [FavoriteController::class, 'toggle']) ->name('favorites.toggle');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reading-plans', [ReadingPlanController::class, 'index'])->name('reading-plans.index');
    Route::get('/reading-plans/create', [ReadingPlanController::class, 'create'])->name('reading-plans.create');
    Route::post('/reading-plans', [ReadingPlanController::class, 'store'])->name('reading-plans.store');
    Route::get('/reading-plans/{plan}/edit', [ReadingPlanController::class, 'edit'])->name('reading-plans.edit');
    Route::put('/reading-plans/{plan}', [ReadingPlanController::class, 'update'])->name('reading-plans.update');
    Route::post('/reading-plans/{plan}/complete', [ReadingPlanController::class, 'complete'])->name('reading-plans.complete');
    Route::delete('/reading-plans/{plan}', [ReadingPlanController::class, 'delete']) ->name('reading-plans.destroy');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [NotificationController::class, 'read'])->name('notifications.read');
});

    Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');
    
    






