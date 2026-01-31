<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OriginalMediaController extends Controller
{
    /**
     * Stream the original media file for admins.
     */
    public function show(Request $request, Media $media): StreamedResponse
    {
        // Authorization is handled by middleware

        $path = $media->getPath();

        if (! file_exists($path)) {
            abort(404, 'Original image not found.');
        }

        $headers = [
            'Content-Type' => $media->mime_type,
            'Cache-Control' => 'private, no-store',
            'Content-Disposition' => 'inline; filename="'.$media->file_name.'"',
        ];

        return response()->stream(function () use ($path) {
            $stream = fopen($path, 'r');
            fpassthru($stream);
            fclose($stream);
        }, 200, $headers);
    }
}
