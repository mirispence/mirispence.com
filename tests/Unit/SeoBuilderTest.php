<?php

namespace Tests\Unit;

use App\Models\Artwork;
use App\Models\Book;
use App\Support\Seo\SeoBuilder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoBuilderTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_generates_home_payload()
    {
        $payload = SeoBuilder::forHome();

        $this->assertEquals(config('app.name'), $payload->title);
        $this->assertStringContainsString('Miri Spence', $payload->description);
        $this->assertEquals('website', $payload->og['type']);
    }

    public function test_it_generates_art_index_payload()
    {
        $payload = SeoBuilder::forArtIndex();

        $this->assertEquals("Art - " . config('app.name'), $payload->title);
        $this->assertStringContainsString('art gallery', $payload->description);
    }

    public function test_it_generates_artwork_payload()
    {
        $artwork = Artwork::factory()->create([
            'title' => 'Test Painting',
            'description' => 'This is a test description.',
        ]);

        $payload = SeoBuilder::forArtwork($artwork);

        $this->assertEquals("Test Painting - Art", $payload->title);
        $this->assertEquals("This is a test description.", $payload->description);
        $this->assertEquals('article', $payload->og['type']);
        $this->assertNotNull($payload->jsonld);
    }

    public function test_it_generates_book_payload()
    {
        $book = Book::factory()->create([
            'title' => 'Test Novel',
            'description' => 'This is a test world.',
        ]);

        $payload = SeoBuilder::forBook($book);

        $this->assertEquals("Test Novel - Books", $payload->title);
        $this->assertEquals("This is a test world.", $payload->description);
        $this->assertEquals('book', $payload->og['type']);
    }

    public function test_it_normalizes_description()
    {
        $payload = SeoBuilder::make(
            title: 'Test',
            description: "Line 1\nLine 2 **Bold** [Link](https://example.com) <p>Para</p>"
        );

        $this->assertEquals("Line 1 Line 2 Bold Link Para", $payload->description);
    }
}
