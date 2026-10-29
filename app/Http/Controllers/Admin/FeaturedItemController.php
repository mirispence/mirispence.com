<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreFeaturedItemRequest;
use App\Http\Requests\Admin\UpdateFeaturedItemRequest;
use App\Models\Artwork;
use App\Models\Book;
use App\Models\FeaturedItem;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class FeaturedItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $this->authorize('admin');

        return Inertia::render('Admin/FeaturedItems/Index', [
            'featuredItems' => FeaturedItem::with('item')
                ->orderBy('display_context')
                ->orderBy('display_order')
                ->paginate(10),
        ]);
    }

    public function create(): Response
    {
        $this->authorize('admin');

        return Inertia::render('Admin/FeaturedItems/Create', [
            'artworks' => Artwork::all(),
            'books' => Book::all(),
        ]);
    }

    public function store(StoreFeaturedItemRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        FeaturedItem::create($validated);

        return redirect()->route('admin.featured-items.index')
            ->with('success', 'Featured item created successfully.');
    }

    public function show(FeaturedItem $featuredItem): void
    {
        //
    }

    public function edit(FeaturedItem $featuredItem): Response
    {
        $this->authorize('admin');

        return Inertia::render('Admin/FeaturedItems/Edit', [
            'featuredItem' => $featuredItem,
            'artworks' => Artwork::all(),
            'books' => Book::all(),
        ]);
    }

    public function update(UpdateFeaturedItemRequest $request, FeaturedItem $featuredItem): RedirectResponse
    {
        $validated = $request->validated();

        $featuredItem->update($validated);

        return redirect()->route('admin.featured-items.index')
            ->with('success', 'Featured item updated successfully.');
    }

    public function destroy(FeaturedItem $featuredItem): RedirectResponse
    {
        $this->authorize('admin');

        $featuredItem->delete();

        return redirect()->route('admin.featured-items.index')
            ->with('success', 'Featured item deleted successfully.');
    }
}
