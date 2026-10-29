<?php

use App\Models\Artwork;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Assert;
use Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class)
    ->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function something()
{
    // ..
}

/**
 * Recursively assert a serialized payload (e.g. Inertia props) never exposes an
 * original image: no `original` / `original_url` key at any depth, and no string
 * value containing any of the given private original URLs.
 *
 * @param  array<int, string>  $privateUrls
 */
function assertNoOriginalMedia(mixed $payload, array $privateUrls = []): void
{
    $data = json_decode((string) json_encode($payload), true);

    $walk = function (mixed $value, string $path) use (&$walk, $privateUrls): void {
        if (is_string($value)) {
            foreach ($privateUrls as $url) {
                Assert::assertStringNotContainsString(
                    $url, $value, "Private original URL leaked at [{$path}]"
                );
            }

            return;
        }

        if (! is_array($value)) {
            return;
        }

        foreach ($value as $key => $child) {
            Assert::assertNotContains(
                $key, ['original', 'original_url'], "Forbidden key [{$key}] found at [{$path}]"
            );
            $walk($child, $path === '' ? (string) $key : "{$path}.{$key}");
        }
    };

    $walk($data, '');
}

/**
 * Recursively assert a serialized payload contains none of the given artwork's
 * public media URLs (any conversion) at any depth. Used for the mature-content
 * gate (ADR-0004): flagged artwork must not leak URLs before consent.
 */
function assertNoMediaUrlsFor(mixed $payload, Artwork $artwork): void
{
    $urls = [];
    $sets = $artwork->mediaUrlBuilder()->public();
    array_walk_recursive($sets, function (string $value) use (&$urls): void {
        foreach (preg_split('/,\s*/', $value) ?: [] as $part) {
            $urls[] = explode(' ', trim($part))[0];
        }
    });
    $urls = array_values(array_filter(array_unique($urls)));

    Assert::assertNotEmpty($urls, 'Artwork has no media URLs to check against');

    $walk = function (mixed $value, string $path) use (&$walk, $urls): void {
        if (is_string($value)) {
            foreach ($urls as $url) {
                Assert::assertStringNotContainsString($url, $value, "Media URL of artwork leaked at [{$path}]");
            }

            return;
        }

        if (is_array($value)) {
            foreach ($value as $key => $child) {
                $walk($child, $path === '' ? (string) $key : "{$path}.{$key}");
            }
        }
    };

    $walk(json_decode((string) json_encode($payload), true), '');
}
