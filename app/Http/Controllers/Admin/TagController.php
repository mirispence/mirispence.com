<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTagRequest;
use App\Http\Requests\Admin\UpdateTagRequest;
use App\Models\Tag;
use Illuminate\Support\Str;
use Inertia\Inertia;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): \Inertia\Response
    {
        $this->authorize('admin');

        return Inertia::render('Admin/Tags/Index', [
            'tags' => Tag::withCount(['artworks', 'books'])
                ->orderBy('name')
                ->paginate(10),
        ]);
    }

    public function create(): \Inertia\Response
    {
        $this->authorize('admin');

        return Inertia::render('Admin/Tags/Create');
    }

    public function store(StoreTagRequest $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validated();

        $validated['slug'] = Str::slug($validated['name']);

        Tag::create($validated);

        return redirect()->route('admin.tags.index')
            ->with('success', 'Tag created successfully.');
    }

    public function show(Tag $tag): void
    {
        //
    }

    public function edit(Tag $tag): \Inertia\Response
    {
        $this->authorize('admin');

        return Inertia::render('Admin/Tags/Edit', [
            'tag' => $tag,
        ]);
    }

    public function update(UpdateTagRequest $request, Tag $tag): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validated();

        if ($tag->name !== $validated['name']) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $tag->update($validated);

        return redirect()->route('admin.tags.index')
            ->with('success', 'Tag updated successfully.');
    }

    public function destroy(Tag $tag): \Illuminate\Http\RedirectResponse
    {
        $this->authorize('admin');

        $tag->delete();

        return redirect()->route('admin.tags.index')
            ->with('success', 'Tag deleted successfully.');
    }
}
