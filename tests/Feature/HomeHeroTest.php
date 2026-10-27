<?php

use App\Models\Artwork;
use Illuminate\Support\Facades\Storage;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    Storage::fake('media_private');
    Storage::fake('public');
});

function heroArtwork(array $attributes = []): Artwork
{
    return Artwork::factory()->create(array_merge([
        'publish_status' => 'published',
        'featured_flag' => true,
        'nsfw_flag' => false,
    ], $attributes));
}

function homeProps($test): array
{
    return $test->get(route('home'))->assertOk()->viewData('page')['props'];
}

test('the hero is the newest unflagged featured artwork, even when the newest featured artwork is flagged', function () {
    heroArtwork(['created_on' => now()->subYears(2)]);
    $safe = heroArtwork(['created_on' => now()->subYear()]);
    heroArtwork(['created_on' => now(), 'nsfw_flag' => true]);

    $props = homeProps($this);

    expect($props['heroArtwork']['id'])->toBe($safe->id)
        ->and($props['heroArtwork']['nsfw_flag'])->toBeFalse()
        ->and($props)->not->toHaveKey('heroArtworks');
});

test('the featured grid excludes the hero artwork', function () {
    $older = heroArtwork(['created_on' => now()->subYears(2)]);
    $hero = heroArtwork(['created_on' => now()->subYear()]);
    $flagged = heroArtwork(['created_on' => now(), 'nsfw_flag' => true]);

    $props = homeProps($this);

    $gridIds = collect($props['featuredArtworks'])->pluck('id')->all();

    expect($props['heroArtwork']['id'])->toBe($hero->id)
        ->and($gridIds)->not->toContain($hero->id)
        ->and($gridIds)->toBe([$flagged->id, $older->id]);
});

test('the featured grid still shows three pieces besides the hero', function () {
    foreach (range(1, 5) as $i) {
        heroArtwork(['created_on' => now()->subDays($i)]);
    }

    $props = homeProps($this);

    expect($props['featuredArtworks'])->toHaveCount(3)
        ->and(collect($props['featuredArtworks'])->pluck('id'))->not->toContain($props['heroArtwork']['id']);
});

test('with no eligible artwork the hero is null without an error', function () {
    heroArtwork(['nsfw_flag' => true]);
    heroArtwork(['publish_status' => 'draft']);
    heroArtwork(['featured_flag' => false]);

    $this->get(route('home'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Public/Home')
            ->where('heroArtwork', null)
        );
});

test('with no featured artwork at all the home page renders an empty hero and grid', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->where('heroArtwork', null)
            ->where('featuredArtworks', [])
        );
});
