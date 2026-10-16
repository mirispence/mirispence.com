<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBookRequest;
use App\Http\Requests\Admin\UpdateBookRequest;
use App\Models\Book;
use App\Models\Gallery;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Inertia\Inertia;
use Inertia\Response;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $this->authorize('admin');

        return Inertia::render('Admin/Books/Index', [
            'books' => Book::with('gallery.coverArtwork.media')
                ->withCount('chapters')
                ->latest()
                ->paginate(10)
                ->through(fn (Book $book) => [
                    ...$book->toArray(),
                    'cover_thumb_url' => $book->gallery?->coverArtwork->thumb_url ?? '',
                ]),
        ]);
    }

    public function create(): Response
    {
        $this->authorize('admin');

        return Inertia::render('Admin/Books/Create', [
            'tags' => Tag::whereIn('type', ['book', 'both'])->get(),
            'galleries' => $this->galleryOptions(),
        ]);
    }

    public function store(StoreBookRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $book = Book::create($validated);

        if (isset($validated['tags'])) {
            $book->tags()->sync($validated['tags']);
        }

        return redirect()->route('admin.books.index')
            ->with('success', 'Book created successfully.');
    }

    public function show(Book $book): void
    {
        //
    }

    public function edit(Book $book): Response
    {
        $this->authorize('admin');

        return Inertia::render('Admin/Books/Edit', [
            'book' => $book->load('tags'),
            'tags' => Tag::whereIn('type', ['book', 'both'])->get(),
            'galleries' => $this->galleryOptions(),
        ]);
    }

    public function update(UpdateBookRequest $request, Book $book): RedirectResponse
    {
        $validated = $request->validated();

        $book->update($validated);

        if (isset($validated['tags'])) {
            $book->tags()->sync($validated['tags']);
        }

        return redirect()->route('admin.books.index')
            ->with('success', 'Book updated successfully.');
    }

    public function destroy(Book $book): RedirectResponse
    {
        $this->authorize('admin');

        $book->delete();

        return redirect()->route('admin.books.index')
            ->with('success', 'Book deleted successfully.');
    }

    /**
     * Galleries offered by the Book cover picker.
     *
     * @return Collection<int, array{id: int, name: string}>
     */
    private function galleryOptions(): Collection
    {
        return Gallery::orderBy('name')
            ->get(['id', 'name'])
            ->map(fn (Gallery $gallery) => ['id' => $gallery->id, 'name' => $gallery->name]);
    }
}
