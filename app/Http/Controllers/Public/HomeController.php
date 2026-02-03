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
        return Inertia::render('Public/Home', [
            'featuredArtworks' => Artwork::where('featured_flag', true)
                ->where('publish_status', 'published')
                ->latest('created_on')
                ->take(3)
                ->get(),
            'featuredBooks' => Book::where('featured_flag', true)
                ->where('publish_status', 'published')
                ->latest('release_date')
                ->take(3)
                ->get(),
            'seo' => SeoBuilder::forHome(),
        ]);
    }
}
