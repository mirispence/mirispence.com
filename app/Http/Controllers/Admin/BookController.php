<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Tag;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Admin/Books/Index', [
            'books' => Book::withCount('chapters')
                ->latest()
                ->paginate(10),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Books/Create', [
            'tags' => Tag::whereIn('type', ['book', 'both'])->get(),
        ]);
    }

    public function store(Request $request)
    {
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

        $validated['slug'] = Str::slug($validated['title']);

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
        return Inertia::render('Admin/Books/Edit', [
            'book' => $book->load(['tags', 'media']),
            'tags' => Tag::whereIn('type', ['book', 'both'])->get(),
        ]);
    }

    public function update(Request $request, Book $book)
    {
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

        if ($book->title !== $validated['title']) {
             $validated['slug'] = Str::slug($validated['title']);
        }

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
        $book->delete();

        return redirect()->route('admin.books.index')
            ->with('success', 'Book deleted successfully.');
    }
}
