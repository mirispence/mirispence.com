<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Chapter;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ChapterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('admin');

        $bookId = $request->input('book_id');
        $book = null;
        $chaptersQuery = Chapter::query();

        if ($bookId) {
            $book = Book::findOrFail($bookId);
            $chaptersQuery->where('book_id', $bookId);
        }

        return Inertia::render('Admin/Chapters/Index', [
            'chapters' => $chaptersQuery->with('book')
                ->orderBy('book_id')
                ->orderBy('order')
                ->paginate(10),
            'book' => $book,
            'books' => Book::all(),
        ]);
    }

    public function create(Request $request)
    {
        $this->authorize('admin');

        return Inertia::render('Admin/Chapters/Create', [
            'books' => Book::all(),
            'book_id' => $request->input('book_id'),
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('admin');

        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
            'title' => 'required|string|max:255',
            'summary' => 'nullable|string',
            'body_markdown' => 'required|string',
            'order' => 'required|integer',
            'is_sample' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['title']);

        Chapter::create($validated);

        return redirect()->route('admin.chapters.index', ['book_id' => $validated['book_id']])
            ->with('success', 'Chapter created successfully.');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(Chapter $chapter)
    {
        $this->authorize('admin');

        return Inertia::render('Admin/Chapters/Edit', [
            'chapter' => $chapter,
            'books' => Book::all(),
        ]);
    }

    public function update(Request $request, Chapter $chapter)
    {
        $this->authorize('admin');

        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
            'title' => 'required|string|max:255',
            'summary' => 'nullable|string',
            'body_markdown' => 'required|string',
            'order' => 'required|integer',
            'is_sample' => 'boolean',
        ]);

        if ($chapter->title !== $validated['title']) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        $chapter->update($validated);

        return redirect()->route('admin.chapters.index', ['book_id' => $validated['book_id']])
            ->with('success', 'Chapter updated successfully.');
    }

    public function destroy(Chapter $chapter)
    {
        $this->authorize('admin');

        $bookId = $chapter->book_id;
        $chapter->delete();

        return redirect()->route('admin.chapters.index', ['book_id' => $bookId])
            ->with('success', 'Chapter deleted successfully.');
    }
}
