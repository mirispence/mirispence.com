<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('admin');

        return Inertia::render('Admin/Galleries/Index', [
            'galleries' => Gallery::withCount('artworks')
                ->orderBy('sort_order')
                ->paginate(10),
        ]);
    }

    public function create()
    {
        $this->authorize('admin');

        return Inertia::render('Admin/Galleries/Create');
    }

    public function store(Request $request)
    {
        $this->authorize('admin');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer',
            'publish_status' => 'required|in:draft,published',
            'cover' => 'nullable|image|max:10240',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $gallery = Gallery::create($validated);

        if ($request->hasFile('cover')) {
            $gallery->addMediaFromRequest('cover')
                ->toMediaCollection('gallery');
        }

        return redirect()->route('admin.galleries.index')
            ->with('success', 'Gallery created successfully.');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(Gallery $gallery)
    {
        $this->authorize('admin');

        return Inertia::render('Admin/Galleries/Edit', [
            'gallery' => $gallery->load('media'),
        ]);
    }

    public function update(Request $request, Gallery $gallery)
    {
        $this->authorize('admin');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer',
            'publish_status' => 'required|in:draft,published',
            'cover' => 'nullable|image|max:10240',
        ]);

        if ($gallery->name !== $validated['name']) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $gallery->update($validated);

        if ($request->hasFile('cover')) {
            $gallery->clearMediaCollection('gallery');
            $gallery->addMediaFromRequest('cover')
                ->toMediaCollection('gallery');
        }

        return redirect()->route('admin.galleries.index')
            ->with('success', 'Gallery updated successfully.');
    }

    public function destroy(Gallery $gallery)
    {
        $this->authorize('admin');

        $gallery->delete();

        return redirect()->route('admin.galleries.index')
            ->with('success', 'Gallery deleted successfully.');
    }
}
