<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Resources\PublicArtworkResource;
use Illuminate\Http\Request;

use App\Models\Artwork;
use App\Models\Book;
use App\Support\Seo\SeoBuilder;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        Inertia::share('seo', SeoBuilder::forHome());

        $artworks = Artwork::featured()
            ->published()
            ->without('media') // Don't eager load media relation
            ->latest('created_on')
            ->take(3)
            ->get();

        return Inertia::render('Public/Home', [
            'featuredArtworks' => PublicArtworkResource::collection($artworks),
            'featuredBooks' => Book::featured()
                ->published()
                ->latest('release_date')
                ->take(3)
                ->get(),
        ]);
    }
}
