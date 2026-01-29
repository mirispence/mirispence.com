<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class SignedMediaUrl
{
    /**
     * Generate a signed URL for a media conversion.
     */
    public static function url(Media $media, string $conversion, ?string $filename = null, ?Carbon $expiresAt = null): string
    {
        $expiresAt = $expiresAt ?? now()->addDays(config('media.ttl_days', 14));
        $exp = $expiresAt->timestamp;

        $dataToSign = "{$media->id}|{$conversion}|{$exp}";
        $sig = hash_hmac('sha256', $dataToSign, config('media.signing_key'));

        $prettyName = $filename ?? $media->name;

        return route('media.conversion', [
            'media' => $media->id,
            'conversion' => $conversion,
            'filename' => $prettyName,
            'exp' => $exp,
            'sig' => $sig,
        ]);
    }

    /**
     * Validate a signed URL.
     */
    public static function validate(string $mediaId, string $conversion, string $exp, string $sig): bool
    {
        if (now()->timestamp > (int) $exp) {
            return false;
        }

        $dataToSign = "{$mediaId}|{$conversion}|{$exp}";
        $expectedSig = hash_hmac('sha256', $dataToSign, config('media.signing_key'));

        return hash_equals($expectedSig, $sig);
    }
}
