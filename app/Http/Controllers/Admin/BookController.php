<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('admin');

        return Inertia::render('Admin/Books/Index', [
            'books' => Book::withCount('chapters')
                ->latest()
                ->paginate(10),
        ]);
    }

    public function create()
    {
        $this->authorize('admin');

        return Inertia::render('Admin/Books/Create', [
            'tags' => Tag::whereIn('type', ['book', 'both'])->get(),
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('can upload book');

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'publish_status' => 'required|in:draft,published',
            'featured_flag' => 'boolean',
            'release_date' => 'nullable|date',
            'external_links' => 'nullable|array',
            'tags' => 'array',
            'cover' => 'nullable|image|max:10240',
        ]);


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

    public function show(string $id)
    {
        //
    }

    public function edit(Book $book)
    {
        $this->authorize('admin');

        return Inertia::render('Admin/Books/Edit', [
            'book' => $book->load(['tags', 'media']),
            'tags' => Tag::whereIn('type', ['book', 'both'])->get(),
        ]);
    }

    public function update(Request $request, Book $book)
    {
        $this->authorize('can edit book');

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'publish_status' => 'required|in:draft,published',
            'featured_flag' => 'boolean',
            'release_date' => 'nullable|date',
            'external_links' => 'nullable|array',
            'tags' => 'array',
            'cover' => 'nullable|image|max:10240',
        ]);


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

    public function destroy(Book $book)
    {
        $this->authorize('admin');

        $book->delete();

        return redirect()->route('admin.books.index')
            ->with('success', 'Book deleted successfully.');
    }
}
