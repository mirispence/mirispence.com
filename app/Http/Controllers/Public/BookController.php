<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Book;
use Inertia\Inertia;

class BookController extends Controller
{
    public function index()
    {
        return Inertia::render('Public/Books/Index', [
            'books' => Book::where('publish_status', 'published')
                ->latest('release_date')
                ->get(),
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

        return Inertia::render('Public/Books/Show', [
            'book' => $book,
        ]);
    }
}
