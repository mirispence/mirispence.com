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

        $artworks = Artwork::featured()
            ->published()
            ->without('media') // Don't eager load media relation
            ->latest('created_on')
            ->take(3)
            ->get();

        // The hero is never gated, so it never shows mature artwork (ADR-0004).
        $heroArtworks = Artwork::featured()
            ->published()
            ->where('nsfw_flag', false)
            ->latest('created_on')
            ->take(2)
            ->get();

        return Inertia::render('Public/Home', [
            'heroArtworks' => PublicArtworkResource::collection($heroArtworks)->resolve(),
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
