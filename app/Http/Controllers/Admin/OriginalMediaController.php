<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class OriginalMediaController extends Controller
{
    /**
     * Redirect admins to a signed, time-limited URL for the original media file.
     *
     * Authorization is handled by route middleware (`permission:can view source image`).
     */
    public function show(Media $media): RedirectResponse
    {
        $lifetime = (int) config('media-library.temporary_url_default_lifetime');

        return redirect()->away(
            $media->getTemporaryUrl(now()->addMinutes($lifetime))
        );
    }
}
