<?php

namespace Tests\Unit;

use Laravel\Pennant\Feature;
use Tests\TestCase;

class FeatureFlagsTest extends TestCase
{
    public function test_books_flag_follows_config(): void
    {
        config(['features.books' => true]);
        Feature::flushCache();
        $this->assertTrue(Feature::active('books'));

        config(['features.books' => false]);
        Feature::flushCache();
        $this->assertFalse(Feature::active('books'));
    }

    public function test_flags_are_global_not_per_user(): void
    {
        config(['features.books' => true]);
        Feature::flushCache();

        // The default scope resolves to null, so Feature::active() and the
        // explicit null scope always agree, logged in or not.
        $this->assertTrue(Feature::for(null)->active('books'));
        $this->assertTrue(Feature::active('books'));
    }

    public function test_pennant_uses_the_array_store(): void
    {
        // The database store would remember the first value and ignore later
        // config changes (ADR-0006).
        $this->assertSame('array', config('pennant.default'));
    }
}
