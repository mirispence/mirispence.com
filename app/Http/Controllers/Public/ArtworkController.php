<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Artwork;
use App\Models\Gallery;
use App\Models\Tag;
use Inertia\Inertia;

class ArtworkController extends Controller
{
    public function index(Request $request)
    {
        $query = Artwork::where('publish_status', 'published')
            ->latest('created_on');

        if ($request->filled('tag')) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('slug', $request->tag);
            });
        }

        if ($request->filled('gallery')) {
            $query->whereHas('galleries', function ($q) use ($request) {
                $q->where('slug', $request->gallery);
            });
        }

        return Inertia::render('Public/Art/Index', [
            'artworks' => $query->paginate(12)->withQueryString(),
            'filters' => $request->only(['tag', 'gallery']),
            'galleries' => Gallery::where('publish_status', 'published')->orderBy('sort_order')->get(),
        ]);
    }

    public function show(Artwork $artwork)
    {
        if ($artwork->publish_status !== 'published') {
            abort(404);
        }

        $artwork->load(['tags', 'galleries']);

        return Inertia::render('Public/Art/Show', [
            'artwork' => $artwork,
        ]);
    }
}
