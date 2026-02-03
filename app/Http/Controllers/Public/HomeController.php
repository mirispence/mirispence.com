<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
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

        return Inertia::render('Public/Home', [
            'featuredArtworks' => Artwork::featured()
                ->published()
                ->latest('created_on')
                ->take(3)
                ->get(),
            'featuredBooks' => Book::featured()
                ->published()
                ->latest('release_date')
                ->take(3)
                ->get(),
        ]);
    }
}
