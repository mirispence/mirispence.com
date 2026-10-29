<?php

use App\Http\Controllers\Admin\ChapterController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FeaturedItemController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\OriginalMediaController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Public\ArtworkController;
use App\Http\Controllers\Public\BookController;
use App\Http\Controllers\Public\ContactController;
use App\Http\Controllers\Public\GalleryController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\MatureConsentController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
// ADR-0005: /art is for Galleries, /artwork is for Artworks. No redirects from the old URLs.
Route::get('/art', [GalleryController::class, 'index'])->name('art.index');
Route::get('/art/{gallery:slug}', [GalleryController::class, 'show'])->name('art.gallery');
Route::get('/artwork', [ArtworkController::class, 'index'])->name('artwork.index');
Route::get('/artwork/{artwork:slug}', [ArtworkController::class, 'show'])->name('artwork.show');
Route::get('/books', [BookController::class, 'index'])->name('books.index');
Route::get('/books/{book:slug}', [BookController::class, 'show'])->name('books.show');
Route::get('/contact', [ContactController::class, 'create'])->name('contact.create');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::post('/mature-consent', [MatureConsentController::class, 'store'])
    ->middleware('throttle:30,1')
    ->name('mature-consent.store');

require __DIR__.'/settings.php';

Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('artworks', App\Http\Controllers\Admin\ArtworkController::class);
    Route::resource('galleries', App\Http\Controllers\Admin\GalleryController::class);
    Route::resource('books', App\Http\Controllers\Admin\BookController::class);
    Route::resource('chapters', ChapterController::class);
    Route::resource('tags', TagController::class);
    Route::resource('featured-items', FeaturedItemController::class);
    Route::resource('messages', MessageController::class)->only(['index', 'show', 'update', 'destroy']);
    Route::resource('users', UserController::class);

    Route::get('media/{media}/original', [OriginalMediaController::class, 'show'])
        ->middleware(['permission:can view source image'])
        ->name('media.original');

    Route::post('artworks/{artwork}/regenerate', [App\Http\Controllers\Admin\ArtworkController::class, 'regenerate'])
        ->middleware(['permission:can regenerate image thumbnails'])
        ->name('artworks.regenerate');
    Route::post('artworks/bulk-regenerate', [App\Http\Controllers\Admin\ArtworkController::class, 'bulkRegenerate'])
        ->middleware(['permission:can regenerate image thumbnails'])
        ->name('artworks.bulk-regenerate');
});
