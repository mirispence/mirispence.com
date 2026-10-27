<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\PublicArtworkResource;
use App\Models\Artwork;
use App\Models\Book;
use App\Support\Seo\SeoBuilder;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(): Response
    {
        Inertia::share('seo', SeoBuilder::forHome());

        // The hero is never gated, so it never shows mature artwork (ADR-0004).
        $heroArtwork = Artwork::featured()
            ->published()
            ->notMature()
            ->latest('created_on')
            ->first();

        // The grid shows the remaining featured pieces, so the hero never appears twice.
        $artworks = Artwork::featured()
            ->published()
            ->without('media') // Don't eager load media relation
            ->when($heroArtwork, fn ($query) => $query->whereKeyNot($heroArtwork->getKey()))
            ->latest('created_on')
            ->take(3)
            ->get();

        return Inertia::render('Public/Home', [
            'heroArtwork' => $heroArtwork ? (new PublicArtworkResource($heroArtwork))->resolve() : null,
            'featuredArtworks' => PublicArtworkResource::collection($artworks)->resolve(),
            'featuredBooks' => Book::featured()
                ->published()
                ->withPublicCover()
                ->latest('release_date')
                ->take(3)
                ->get(),
        ]);
    }
}
