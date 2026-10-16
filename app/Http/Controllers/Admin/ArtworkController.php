<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreArtworkRequest;
use App\Http\Requests\Admin\UpdateArtworkRequest;
use App\Jobs\RegenerateArtworkImages;
use App\Models\Artwork;
use App\Models\Gallery;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class ArtworkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $this->authorize('admin');

        return Inertia::render('Admin/Artworks/Index', [
            'artworks' => Artwork::with(['galleries', 'tags'])
                ->latest()
                ->paginate(10),
        ]);
    }

    public function create(): Response
    {
        $this->authorize('admin');

        return Inertia::render('Admin/Artworks/Create', [
            'galleries' => Gallery::all(),
            'tags' => Tag::whereIn('type', ['artwork', 'both'])->get(),
        ]);
    }

    public function store(StoreArtworkRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $artwork = Artwork::create($validated);

        if (isset($validated['galleries'])) {
            $artwork->syncGalleries($validated['galleries']);
        }

        if (isset($validated['tags'])) {
            $artwork->tags()->sync($validated['tags']);
        }

        if ($request->hasFile('image')) {
            $artwork->addMediaFromRequest('image')
                ->toMediaCollection('artwork');

            RegenerateArtworkImages::dispatch($artwork);
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

    public function edit(Artwork $artwork): Response
    {
        $this->authorize('admin');

        return Inertia::render('Admin/Artworks/Edit', [
            'artwork' => $artwork->load(['galleries', 'tags', 'media']),
            // Media id for the gated `admin.media.original` link; the media relation itself is hidden.
            'originalMediaId' => $artwork->getFirstMedia('artwork')?->id,
            'galleries' => Gallery::all(),
            'tags' => Tag::whereIn('type', ['artwork', 'both'])->get(),
        ]);
    }

    public function update(UpdateArtworkRequest $request, Artwork $artwork): RedirectResponse
    {
        $validated = $request->validated();

        $artwork->update($validated);

        if (isset($validated['galleries'])) {
            $artwork->syncGalleries($validated['galleries']);
        }

        if (isset($validated['tags'])) {
            $artwork->tags()->sync($validated['tags']);
        }

        if ($request->hasFile('image')) {
            $artwork->clearMediaCollection('artwork');
            $artwork->addMediaFromRequest('image')
                ->toMediaCollection('artwork');

            RegenerateArtworkImages::dispatch($artwork);
        }

        return redirect()->route('admin.artworks.index')
            ->with('success', 'Artwork updated successfully.');
    }

    public function destroy(Artwork $artwork): RedirectResponse
    {
        $this->authorize('admin');

        $artwork->delete();

        return redirect()->route('admin.artworks.index')
            ->with('success', 'Artwork deleted successfully.');
    }

    public function regenerate(Artwork $artwork): RedirectResponse
    {
        $this->authorize('can regenerate image thumbnails');

        RegenerateArtworkImages::dispatch($artwork);

        return back()->with('success', 'Image regeneration started in the background.');
    }

    public function bulkRegenerate(Request $request): RedirectResponse
    {
        $this->authorize('can regenerate image thumbnails');

        $ids = $request->validate(['ids' => 'required|array'])['ids'];

        Artwork::whereIn('id', $ids)->get()->each(function ($artwork) {
            RegenerateArtworkImages::dispatch($artwork);
        });

        return back()->with('success', count($ids).' artworks queued for regeneration.');
    }
}
