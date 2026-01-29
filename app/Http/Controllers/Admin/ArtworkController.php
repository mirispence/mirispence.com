<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Artwork;
use App\Models\Gallery;
use App\Models\Tag;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;

class ArtworkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Admin/Artworks/Index', [
            'artworks' => Artwork::with(['galleries', 'tags'])
                ->latest()
                ->paginate(10),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Artworks/Create', [
            'galleries' => Gallery::all(),
            'tags' => Tag::whereIn('type', ['artwork', 'both'])->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'alt_text' => 'nullable|string|max:255',
            'created_on' => 'nullable|date',
            'publish_status' => 'required|in:draft,published',
            'nsfw_flag' => 'boolean',
            'featured_flag' => 'boolean',
            'galleries' => 'array',
            'tags' => 'array',
            'image' => 'nullable|image|max:10240', // 10MB max
        ]);

        $validated['slug'] = Str::slug($validated['title']);

        $artwork = Artwork::create($validated);

        if (isset($validated['galleries'])) {
            $artwork->galleries()->sync($validated['galleries']);
        }

        if (isset($validated['tags'])) {
            $artwork->tags()->sync($validated['tags']);
        }

        if ($request->hasFile('image')) {
            $artwork->addMediaFromRequest('image')
                ->toMediaCollection('artwork');
            
            \App\Jobs\RegenerateArtworkImages::dispatch($artwork);
        }

        return redirect()->route('admin.artworks.index')
            ->with('success', 'Artwork created successfully.');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(Artwork $artwork)
    {
        return Inertia::render('Admin/Artworks/Edit', [
            'artwork' => $artwork->load(['galleries', 'tags', 'media']),
            'galleries' => Gallery::all(),
            'tags' => Tag::whereIn('type', ['artwork', 'both'])->get(),
        ]);
    }

    public function update(Request $request, Artwork $artwork)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'alt_text' => 'nullable|string|max:255',
            'created_on' => 'nullable|date',
            'publish_status' => 'required|in:draft,published',
            'nsfw_flag' => 'boolean',
            'featured_flag' => 'boolean',
            'galleries' => 'array',
            'tags' => 'array',
            'image' => 'nullable|image|max:10240',
        ]);

        if ($artwork->title !== $validated['title']) {
             $validated['slug'] = Str::slug($validated['title']);
        }

        $artwork->update($validated);

        if (isset($validated['galleries'])) {
            $artwork->galleries()->sync($validated['galleries']);
        }

        if (isset($validated['tags'])) {
            $artwork->tags()->sync($validated['tags']);
        }

        if ($request->hasFile('image')) {
            $artwork->clearMediaCollection('artwork');
            $artwork->addMediaFromRequest('image')
                ->toMediaCollection('artwork');
            
            \App\Jobs\RegenerateArtworkImages::dispatch($artwork);
        }

        return redirect()->route('admin.artworks.index')
            ->with('success', 'Artwork updated successfully.');
    }

    public function destroy(Artwork $artwork)
    {
        $artwork->delete();

        return redirect()->route('admin.artworks.index')
            ->with('success', 'Artwork deleted successfully.');
    }

    public function regenerate(Artwork $artwork)
    {
        \App\Jobs\RegenerateArtworkImages::dispatch($artwork);

        return back()->with('success', 'Image regeneration started in the background.');
    }

    public function bulkRegenerate(Request $request)
    {
        $ids = $request->validate(['ids' => 'required|array'])['ids'];
        
        Artwork::whereIn('id', $ids)->get()->each(function ($artwork) {
            \App\Jobs\RegenerateArtworkImages::dispatch($artwork);
        });

        return back()->with('success', count($ids) . ' artworks queued for regeneration.');
    }
}
