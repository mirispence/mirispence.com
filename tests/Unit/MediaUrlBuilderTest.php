<?php

namespace Tests\Unit;

use App\Models\Artwork;
use App\Support\Media\MediaUrlBuilder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Tests\TestCase;

class MediaUrlBuilderTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('media_private');
        Storage::fake('public');
        Storage::fake('r2_private');
        Storage::fake('r2_public');
    }

    private function artworkWithImage(): Artwork
    {
        $artwork = Artwork::factory()->create();
        $artwork->addMedia(UploadedFile::fake()->image('test.jpg', 400, 300))
            ->toMediaCollection('artwork');

        return $artwork->fresh();
    }

    public function test_public_urls_expose_registered_conversions_without_original()
    {
        $urls = (new MediaUrlBuilder($this->artworkWithImage(), 'artwork'))->public();

        $this->assertSame(['thumb', 'grid', 'display'], array_keys($urls));
        $this->assertArrayNotHasKey('original', $urls);
        $this->assertStringContainsString('thumb', $urls['thumb']);
        $this->assertSame(['src', 'srcset'], array_keys($urls['grid']));
        $this->assertStringContainsString('grid_640', $urls['grid']['src']);
        $this->assertMatchesRegularExpression('/grid_640\S* 640w, \S*grid_960\S* 960w$/', $urls['grid']['srcset']);
        $this->assertStringContainsString('display_1280', $urls['display']['src']);
        $this->assertMatchesRegularExpression(
            '/display_1280\S* 1280w, \S*display_1600\S* 1600w, \S*display_2048\S* 2048w$/',
            $urls['display']['srcset'],
        );
    }

    public function test_admin_urls_have_same_shape_and_no_original()
    {
        $builder = new MediaUrlBuilder($this->artworkWithImage(), 'artwork');

        $this->assertSame($builder->public(), $builder->admin());
        $this->assertArrayNotHasKey('original', $builder->admin());
    }

    public function test_returns_empty_array_when_collection_has_no_media()
    {
        $builder = new MediaUrlBuilder(Artwork::factory()->create(), 'artwork');

        $this->assertSame([], $builder->public());
        $this->assertSame([], $builder->admin());
    }

    public function test_thumb_returns_the_thumb_conversion_url_or_empty_string()
    {
        $builder = new MediaUrlBuilder($this->artworkWithImage(), 'artwork');

        $this->assertStringContainsString('thumb', $builder->thumb());
        $this->assertSame($builder->admin()['thumb'], $builder->thumb());
        $this->assertSame('', (new MediaUrlBuilder(Artwork::factory()->create(), 'artwork'))->thumb());
    }

    public function test_keys_follow_collection_conversions_and_original_is_never_exposed()
    {
        $model = MediaUrlBuilderFixtureModel::create(Artwork::factory()->raw());
        $model->addMedia(UploadedFile::fake()->image('test.jpg', 400, 300))
            ->toMediaCollection('artwork');
        $model = $model->fresh();

        $builder = new MediaUrlBuilder($model, 'artwork');

        $this->assertSame(['thumb'], array_keys($builder->public()));
        $this->assertSame(['thumb'], array_keys($builder->admin()));
    }
}

/**
 * Registers a different conversion set, including one maliciously named
 * `original`, to prove the builder's shape and invariant hold for any model.
 */
class MediaUrlBuilderFixtureModel extends Artwork
{
    protected $table = 'artworks';

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')->nonQueued()->performOnCollections('artwork')->width(100);
        $this->addMediaConversion('original')->nonQueued()->performOnCollections('artwork')->width(200);
        $this->addMediaConversion('elsewhere')->performOnCollections('other');
    }
}
