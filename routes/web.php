<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;

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
Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');
Route::get('/books/create', [BookController::class, 'create'])->name('books.create');
Route::get('/books/{book}/edit', [BookController::class, 'edit'])->name('books.edit');
Route::get('/genres', [GenreController::class, 'index'])->name('genres.index');
Route::get('/genres/{genres}', [GenreController::class, 'show'])->name('genres.show');
Route::get('/genres/create', [GenreController::class, 'create'])->name('genres.create');
Route::get('/genres/{genres}/edit', [GenreController::class, 'edit'])->name('genres.edit');
Route::get('/reviews/{review}/edit', [ReviewController::class, 'edit'])->name('reviews.edit');
Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
Route::get('/ranking', [RankingController::class, 'index'])->name('ranking.index');
Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
Route::get('/reading-plans', [ReadingPlanController::class, 'index'])->name('reading-plans.index');
Route::get('/reading-plans/create', [ReadingPlanController::class, 'create'])->name('reading-plans.create');
Route::get('/reading-plans/{plan}/edit', [ReadingPlanController::class, 'edit'])->name('reading-plans.edit');
Route::get('/notifications', [NotificationController::class, 'edit'])->name('notification.index');




