<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreGalleryRequest;
use App\Http\Requests\Admin\UpdateGalleryRequest;
use App\Models\Artwork;
use App\Models\Gallery;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $this->authorize('admin');

        return Inertia::render('Admin/Galleries/Index', [
            'galleries' => Gallery::with('coverArtwork.media')
                ->withCount('artworks')
                ->orderBy('sort_order')
                ->paginate(10)
                ->through(fn (Gallery $gallery) => $gallery->append('cover_thumb_url')),
        ]);
    }

    public function create(): Response
    {
        $this->authorize('admin');

        return Inertia::render('Admin/Galleries/Create');
    }

    public function store(StoreGalleryRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        Gallery::create($validated);

        return redirect()->route('admin.galleries.index')
            ->with('success', 'Gallery created successfully.');
    }

    public function show(Gallery $gallery): void
    {
        //
    }

    public function edit(Gallery $gallery): Response
    {
        $this->authorize('admin');

        return Inertia::render('Admin/Galleries/Edit', [
            'gallery' => $gallery->load('coverArtwork.media'),
            // Cover candidates: only this gallery's own member artworks.
            'coverCandidates' => $gallery->artworks()
                ->orderBy('artworks.title')
                ->get()
                ->map(fn (Artwork $artwork) => [
                    'id' => $artwork->id,
                    'title' => $artwork->title,
                    'thumb_url' => $artwork->thumb_url,
                ])
                ->values(),
        ]);
    }

    public function update(UpdateGalleryRequest $request, Gallery $gallery): RedirectResponse
    {
        $validated = $request->validated();

        $gallery->update($validated);

        return redirect()->route('admin.galleries.index')
            ->with('success', 'Gallery updated successfully.');
    }

    public function destroy(Gallery $gallery): RedirectResponse
    {
        $this->authorize('admin');

        $gallery->delete();

        return redirect()->route('admin.galleries.index')
            ->with('success', 'Gallery deleted successfully.');
    }
}
