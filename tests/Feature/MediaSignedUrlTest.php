<?php

namespace Tests\Feature;

use App\Models\Artwork;
use App\Models\User;
use App\Services\SignedMediaUrl;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MediaSignedUrlTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('media_private');
        Storage::fake('media_conversions');
    }

    public function test_can_generate_and_validate_signed_url()
    {
        $artwork = Artwork::factory()->create();
        $artwork->addMedia(UploadedFile::fake()->image('test.jpg'))
            ->toMediaCollection('artwork');
        
        $media = $artwork->getFirstMedia('artwork');
        $url = SignedMediaUrl::url($media, 'thumb');

        $uri = parse_url($url);
        parse_str($uri['query'], $query);

        $this->assertTrue(
            SignedMediaUrl::validate(
                (string) $media->id,
                'thumb',
                $query['exp'],
                $query['sig']
            )
        );
    }

    public function test_signed_url_fails_on_tampering()
    {
        $artwork = Artwork::factory()->create();
        $artwork->addMedia(UploadedFile::fake()->image('test.jpg'))
            ->toMediaCollection('artwork');
        
        $media = $artwork->getFirstMedia('artwork');
        $url = SignedMediaUrl::url($media, 'thumb');

        $badUrl = $url . 'tamper';
        $uri = parse_url($badUrl);
        parse_str($uri['query'], $query);

        $this->assertFalse(
            SignedMediaUrl::validate(
                (string) $media->id,
                'thumb',
                $query['exp'],
                $query['sig']
            )
        );
    }

    public function test_signed_url_fails_on_expiration()
    {
        $artwork = Artwork::factory()->create();
        $artwork->addMedia(UploadedFile::fake()->image('test.jpg'))
            ->toMediaCollection('artwork');
        
        $media = $artwork->getFirstMedia('artwork');
        $exp = now()->subMinute()->timestamp;
        
        $dataToSign = "{$media->id}|thumb|{$exp}";
        $sig = hash_hmac('sha256', $dataToSign, config('media.signing_key'));

        $this->assertFalse(
            SignedMediaUrl::validate(
                (string) $media->id,
                'thumb',
                (string) $exp,
                $sig
            )
        );
    }

    public function test_admin_can_access_original_media()
    {
        $user = User::factory()->create();
        $artwork = Artwork::factory()->create();
        $artwork->addMedia(UploadedFile::fake()->image('test.jpg'))
            ->toMediaCollection('artwork');
        
        $media = $artwork->getFirstMedia('artwork');
        $route = route('admin.media.original', $media->id);

        // Guest check
        $this->get($route)->assertRedirect(route('login'));

        // Admin check
        $this->actingAs($user)->get($route)->assertOk();
    }
}
