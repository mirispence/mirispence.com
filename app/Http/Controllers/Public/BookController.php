<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Support\Seo\SeoBuilder;
use Inertia\Inertia;
use Inertia\Response;

class BookController extends Controller
{
    public function index(): Response
    {
        Inertia::share('seo', SeoBuilder::forBooksIndex());

        return Inertia::render('Public/Books/Index', [
            'books' => Book::published()
                ->withPublicCover()
                ->latest()
                ->get(),
        ]);
    }

    public function show(Book $book): Response
    {
        if ($book->publish_status !== 'published') {
            abort(404);
        }

        $book->load(['chapters' => function ($query) {
            $query->orderBy('order');
        }, 'tags'])->loadPublicCover();

        Inertia::share('seo', SeoBuilder::forBook($book));

        return Inertia::render('Public/Books/Show', [
            'book' => $book,
        ]);
    }
}
