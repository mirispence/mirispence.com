<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\PublicArtworkResource;
use App\Models\Artwork;
use App\Models\Tag;
use App\Support\Seo\SeoBuilder;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ArtworkController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Artwork::published()
            ->without('media') // Don't eager load media relation
            ->latest('created_on');

        $tag = $request->query('tag');
        $tagSlug = is_string($tag) && $tag !== '' ? $tag : null;

        if ($tagSlug !== null) {
            $query->whereHas('tags', function ($q) use ($tagSlug) {
                $q->where('slug', $tagSlug);
            });
        }

        Inertia::share('seo', SeoBuilder::forArtworkIndex());

        $artworks = $query->paginate(6)->withQueryString();
        $activeTag = $tagSlug !== null ? Tag::where('slug', $tagSlug)->first(['name', 'slug']) : null;

        return Inertia::render('Public/Artwork/Index', [
            'artworks' => PublicArtworkResource::collection($artworks),
            'filters' => array_filter(['tag' => $tagSlug]),
            'activeTag' => $activeTag?->only(['name', 'slug']),
        ]);
    }

    public function show(Artwork $artwork): Response
    {
        if ($artwork->publish_status !== 'published') {
            abort(404);
        }

        $artwork->unsetRelation('media'); // Remove eager-loaded media relation
        $artwork->load(['tags', 'galleries' => fn ($q) => $q->published()]);

        Inertia::share('seo', SeoBuilder::forArtwork($artwork));

        return Inertia::render('Public/Artwork/Show', [
            'artwork' => PublicArtworkResource::make($artwork)->resolve(),
        ]);
    }
}
