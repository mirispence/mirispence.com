<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Book;
use App\Support\Seo\SeoBuilder;
use Inertia\Inertia;

class BookController extends Controller
{
    public function index()
    {
        Inertia::share('seo', SeoBuilder::forBooksIndex());
        
        return Inertia::render('Public/Books/Index', [
            'books' => Book::published()->latest()->get(),
        ]);
    }

    public function show(Book $book)
    {
        if ($book->publish_status !== 'published') {
            abort(404);
        }

        $book->load(['chapters' => function ($query) {
            $query->orderBy('order');
        }, 'tags']);

        Inertia::share('seo', SeoBuilder::forBook($book));

        return Inertia::render('Public/Books/Show', [
            'book' => $book,
        ]);
    }
}
