<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBookRequest;
use App\Http\Requests\Admin\UpdateBookRequest;
use App\Models\Book;
use App\Models\Tag;
use Inertia\Inertia;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): \Inertia\Response
    {
        $this->authorize('admin');

        return Inertia::render('Admin/Books/Index', [
            'books' => Book::withCount('chapters')
                ->latest()
                ->paginate(10),
        ]);
    }

    public function create(): \Inertia\Response
    {
        $this->authorize('admin');

        return Inertia::render('Admin/Books/Create', [
            'tags' => Tag::whereIn('type', ['book', 'both'])->get(),
        ]);
    }

    public function store(StoreBookRequest $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validated();

        $book = Book::create($validated);

        if (isset($validated['tags'])) {
            $book->tags()->sync($validated['tags']);
        }

        if ($request->hasFile('cover')) {
            $book->addMediaFromRequest('cover')
                ->toMediaCollection('cover');
        }

        return redirect()->route('admin.books.index')
            ->with('success', 'Book created successfully.');
    }

    public function show(Book $book): void
    {
        //
    }

    public function edit(Book $book): \Inertia\Response
    {
        $this->authorize('admin');

        return Inertia::render('Admin/Books/Edit', [
            'book' => $book->load(['tags', 'media']),
            'tags' => Tag::whereIn('type', ['book', 'both'])->get(),
        ]);
    }

    public function update(UpdateBookRequest $request, Book $book): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validated();

        $book->update($validated);

        if (isset($validated['tags'])) {
            $book->tags()->sync($validated['tags']);
        }

        if ($request->hasFile('cover')) {
            $book->clearMediaCollection('cover');
            $book->addMediaFromRequest('cover')
                ->toMediaCollection('cover');
        }

        return redirect()->route('admin.books.index')
            ->with('success', 'Book updated successfully.');
    }

    public function destroy(Book $book): \Illuminate\Http\RedirectResponse
    {
        $this->authorize('admin');

        $book->delete();

        return redirect()->route('admin.books.index')
            ->with('success', 'Book deleted successfully.');
    }
}
