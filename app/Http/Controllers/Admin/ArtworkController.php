<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreArtworkRequest;
use App\Http\Requests\Admin\UpdateArtworkRequest;
use App\Models\Artwork;
use App\Models\Gallery;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ArtworkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): \Inertia\Response
    {
        $this->authorize('admin');

        return Inertia::render('Admin/Artworks/Index', [
            'artworks' => Artwork::with(['galleries', 'tags'])
                ->latest()
                ->paginate(10),
        ]);
    }

    public function create(): \Inertia\Response
    {
        $this->authorize('admin');

        return Inertia::render('Admin/Artworks/Create', [
            'galleries' => Gallery::all(),
            'tags' => Tag::whereIn('type', ['artwork', 'both'])->get(),
        ]);
    }

    public function store(StoreArtworkRequest $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validated();

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

        $message = 'Artwork created successfully.';
        if ($artwork->slug !== Str::slug($artwork->title)) {
            $message .= " Slug was adjusted to '{$artwork->slug}' due to collision.";
        }

        return redirect()->route('admin.artworks.index')
            ->with('success', $message);
    }

    public function show(Artwork $artwork): void
    {
        //
    }

    public function edit(Artwork $artwork): \Inertia\Response
    {
        $this->authorize('admin');

        return Inertia::render('Admin/Artworks/Edit', [
            'artwork' => $artwork->load(['galleries', 'tags', 'media']),
            'galleries' => Gallery::all(),
            'tags' => Tag::whereIn('type', ['artwork', 'both'])->get(),
        ]);
    }

    public function update(UpdateArtworkRequest $request, Artwork $artwork): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validated();

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

    public function destroy(Artwork $artwork): \Illuminate\Http\RedirectResponse
    {
        $this->authorize('admin');

        $artwork->delete();

        return redirect()->route('admin.artworks.index')
            ->with('success', 'Artwork deleted successfully.');
    }

    public function regenerate(Artwork $artwork): \Illuminate\Http\RedirectResponse
    {
        $this->authorize('can regenerate image thumbnails');

        \App\Jobs\RegenerateArtworkImages::dispatch($artwork);

        return back()->with('success', 'Image regeneration started in the background.');
    }

    public function bulkRegenerate(Request $request): \Illuminate\Http\RedirectResponse
    {
        $this->authorize('can regenerate image thumbnails');

        $ids = $request->validate(['ids' => 'required|array'])['ids'];

        Artwork::whereIn('id', $ids)->get()->each(function ($artwork) {
            \App\Jobs\RegenerateArtworkImages::dispatch($artwork);
        });

        return back()->with('success', count($ids).' artworks queued for regeneration.');
    }
}
