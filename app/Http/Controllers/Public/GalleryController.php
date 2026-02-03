<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Gallery;
use App\Support\Seo\SeoBuilder;
use Inertia\Inertia;

class GalleryController extends Controller
{
    public function index()
    {
        Inertia::share('seo', SeoBuilder::forArtIndex());

        return Inertia::render('Public/Galleries/Index', [
            'galleries' => Gallery::published()->get(),
        ]);
    }

    public function show(Gallery $gallery)
    {
        if ($gallery->publish_status !== 'published') {
            abort(404);
        }

        $gallery->load(['artworks' => function ($query) {
            $query->where('publish_status', 'published')
                ->orderBy('pivot_sort_order');
        }]);

        Inertia::share('seo', SeoBuilder::forGallery($gallery));

        return Inertia::render('Public/Galleries/Show', [
            'gallery' => $gallery,
        ]);
    }
}
