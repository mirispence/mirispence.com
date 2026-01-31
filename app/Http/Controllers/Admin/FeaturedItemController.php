<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeaturedItem;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FeaturedItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('admin');

        return Inertia::render('Admin/FeaturedItems/Index', [
            'featuredItems' => FeaturedItem::with('item')
                ->orderBy('display_context')
                ->orderBy('display_order')
                ->paginate(10),
        ]);
    }

    public function create()
    {
        $this->authorize('admin');

        return Inertia::render('Admin/FeaturedItems/Create', [
            'artworks' => \App\Models\Artwork::all(),
            'books' => \App\Models\Book::all(),
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('admin');

        $validated = $request->validate([
            'item_type' => 'required|string|in:artwork,book',
            'item_id' => 'required|integer',
            'display_context' => 'required|string|in:home,sidebar,footer',
            'display_order' => 'required|integer',
        ]);

        FeaturedItem::create($validated);

        return redirect()->route('admin.featured-items.index')
            ->with('success', 'Featured item created successfully.');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(FeaturedItem $featuredItem)
    {
        $this->authorize('admin');

        return Inertia::render('Admin/FeaturedItems/Edit', [
            'featuredItem' => $featuredItem,
            'artworks' => \App\Models\Artwork::all(),
            'books' => \App\Models\Book::all(),
        ]);
    }

    public function update(Request $request, FeaturedItem $featuredItem)
    {
        $this->authorize('admin');

        $validated = $request->validate([
            'item_type' => 'required|string|in:artwork,book',
            'item_id' => 'required|integer',
            'display_context' => 'required|string|in:home,sidebar,footer',
            'display_order' => 'required|integer',
        ]);

        $featuredItem->update($validated);

        return redirect()->route('admin.featured-items.index')
            ->with('success', 'Featured item updated successfully.');
    }

    public function destroy(FeaturedItem $featuredItem)
    {
        $this->authorize('admin');

        $featuredItem->delete();

        return redirect()->route('admin.featured-items.index')
            ->with('success', 'Featured item deleted successfully.');
    }
}
