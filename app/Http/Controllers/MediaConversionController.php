<?php

namespace App\Http\Controllers;

use App\Services\SignedMediaUrl;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MediaConversionController extends Controller
{
    /**
     * Serve a signed media conversion.
     */
    public function show(Request $request, Media $media, string $conversion, ?string $filename = null): StreamedResponse
    {
        $exp = $request->query('exp');
        $sig = $request->query('sig');

        if (!$exp || !$sig) {
            abort(403, 'Missing signature or expiration.');
        }

        if (!SignedMediaUrl::validate((string) $media->id, $conversion, $exp, $sig)) {
            abort(403, 'Invalid or expired signature.');
        }

        // Ensure the conversion exists
        $path = $media->getPath($conversion);
        
        if (!file_exists($path)) {
            \Log::error("Media conversion not found: Media ID {$media->id}, Conversion {$conversion}");
            abort(404, 'Image conversion not found.');
        }

        $headers = [
            'Content-Type' => \Illuminate\Support\Facades\File::mimeType($path),
            'Cache-Control' => 'public, max-age=1209600, s-maxage=1209600, immutable',
            'Last-Modified' => gmdate('D, d M Y H:i:s', filemtime($path)) . ' GMT',
        ];

        return response()->stream(function () use ($path) {
            $stream = fopen($path, 'r');
            fpassthru($stream);
            fclose($stream);
        }, 200, $headers);
    }
}
