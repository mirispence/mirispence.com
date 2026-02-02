<?php

use App\Http\Controllers\Public\ArtworkController;
use App\Http\Controllers\Public\BookController;
use App\Http\Controllers\Public\ContactController;
use App\Http\Controllers\Public\GalleryController;
use App\Http\Controllers\Public\HomeController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/art', [ArtworkController::class, 'index'])->name('art.index');
Route::get('/art/{artwork:slug}', [ArtworkController::class, 'show'])->name('art.show');
Route::get('/galleries', [GalleryController::class, 'index'])->name('galleries.index');
Route::get('/galleries/{gallery:slug}', [GalleryController::class, 'show'])->name('galleries.show');
Route::get('/books', [BookController::class, 'index'])->name('books.index');
Route::get('/books/{book:slug}', [BookController::class, 'show'])->name('books.show');
Route::get('/contact', [ContactController::class, 'create'])->name('contact.create');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/settings.php';

Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('artworks', App\Http\Controllers\Admin\ArtworkController::class);
    Route::resource('galleries', App\Http\Controllers\Admin\GalleryController::class);
    Route::resource('books', App\Http\Controllers\Admin\BookController::class);
    Route::resource('chapters', App\Http\Controllers\Admin\ChapterController::class);
    Route::resource('tags', App\Http\Controllers\Admin\TagController::class);
    Route::resource('featured-items', App\Http\Controllers\Admin\FeaturedItemController::class);
    Route::resource('messages', App\Http\Controllers\Admin\MessageController::class)->only(['index', 'show', 'update', 'destroy']);
    Route::resource('users', App\Http\Controllers\Admin\UserController::class);

    Route::get('media/{media}/original', [\App\Http\Controllers\Admin\OriginalMediaController::class, 'show'])
        ->middleware(['permission:can view source image'])
        ->name('media.original');

    Route::post('artworks/{artwork}/regenerate', [App\Http\Controllers\Admin\ArtworkController::class, 'regenerate'])
        ->middleware(['permission:can regenerate image thumbnails'])
        ->name('artworks.regenerate');
    Route::post('artworks/bulk-regenerate', [App\Http\Controllers\Admin\ArtworkController::class, 'bulkRegenerate'])
        ->middleware(['permission:can regenerate image thumbnails'])
        ->name('artworks.bulk-regenerate');
});
