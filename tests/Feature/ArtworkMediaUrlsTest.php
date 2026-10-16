<?php

use App\Http\Resources\PublicArtworkResource;
use App\Models\Artwork;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('media_private');
    Storage::fake('public');
    Storage::fake('r2_private');
    Storage::fake('r2_public');
});

function artworkWithImage(): Artwork
{
    $artwork = Artwork::factory()->create();
    $artwork->addMedia(UploadedFile::fake()->image('test.jpg', 400, 300))
        ->toMediaCollection('artwork');

    return $artwork->fresh();
}

test('artwork serialized media_urls never contain an original url', function () {
    $artwork = artworkWithImage();
    $privateUrl = $artwork->getFirstMedia('artwork')->getUrl();

    $array = $artwork->toArray();

    expect($array['media_urls'])->toHaveKeys(['thumb', 'grid', 'display'])
        ->not->toHaveKey('original');
    expect(json_encode($array))->not->toContain(json_encode($privateUrl));
});

test('artwork thumb_url is the thumb conversion url', function () {
    $artwork = artworkWithImage();

    expect($artwork->thumb_url)->toContain('thumb')
        ->and($artwork->thumb_url)->toBe($artwork->media_urls['thumb']);
});

test('artwork without media has empty media_urls and empty thumb_url', function () {
    $artwork = Artwork::factory()->create();

    expect($artwork->media_urls)->toBe([])
        ->and($artwork->thumb_url)->toBe('');
});

test('public artwork resource exposes the same public media urls', function () {
    $artwork = artworkWithImage();

    $payload = (new PublicArtworkResource($artwork))->toArray(Request::create('/'));

    expect($payload['media_urls'])->toBe($artwork->media_urls)
        ->not->toHaveKey('original')
        ->toHaveKeys(['thumb', 'grid', 'display']);
    expect($payload['media_urls']['grid'])->toHaveKeys(['src', 'srcset']);
});

test('artwork has no image_url accessor exposing the raw original', function () {
    $artwork = artworkWithImage();

    expect($artwork->image_url)->toBeNull();
});
