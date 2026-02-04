<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\PublicArtworkResource;
use Illuminate\Http\Request;

use App\Models\Artwork;
use App\Models\Gallery;
use App\Models\Tag;
use App\Support\Seo\SeoBuilder;
use Inertia\Inertia;

class ArtworkController extends Controller
{
    public function index(Request $request)
    {
        $query = Artwork::published()
            ->without('media') // Don't eager load media relation
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

        Inertia::share('seo', SeoBuilder::forArtIndex());

        $artworks = $query->paginate(12)->withQueryString();

        return Inertia::render('Public/Art/Index', [
            'artworks' => PublicArtworkResource::collection($artworks),
            'filters' => $request->only(['tag', 'gallery']),
            'galleries' => Gallery::published()->get(),
        ]);
    }

    public function show(Artwork $artwork)
    {
        if ($artwork->publish_status !== 'published') {
            abort(404);
        }

        $artwork->unsetRelation('media'); // Remove eager-loaded media relation
        $artwork->load(['tags', 'galleries']);

        Inertia::share('seo', SeoBuilder::forArtwork($artwork));

        return Inertia::render('Public/Art/Show', [
            'artwork' => new PublicArtworkResource($artwork),
        ]);
    }
}
