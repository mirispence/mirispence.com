<?php

namespace Tests\Feature;

use App\Models\Artwork;
use App\Models\Book;
use App\Models\Chapter;
use App\Models\FeaturedItem;
use App\Models\Tag;
use App\Models\User;
use App\Support\Seo\SeoBuilder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Inertia\Testing\AssertableInertia as Assert;
use Laravel\Pennant\Feature;

uses(RefreshDatabase::class);

/** phpunit.xml turns the flag on for the rest of the suite; these tests choose. */
function setBooksFlag(bool $active): void
{
    config(['features.books' => $active]);
    Feature::flushCache();
}

function booksAdmin(): User
{
    test()->seed(RolesAndPermissionsSeeder::class);
    $user = User::factory()->create();
    $user->assignRole('admin');

    return $user;
}

function sitemapPaths(): array
{
    $path = public_path('sitemap.xml');
    $backup = File::exists($path) ? File::get($path) : null;

    try {
        test()->artisan('sitemap:generate')->assertSuccessful();
        preg_match_all('#<loc>([^<]+)</loc>#', File::get($path), $matches);

        return collect($matches[1])->map(fn ($loc) => parse_url($loc, PHP_URL_PATH) ?? '/')->all();
    } finally {
        $backup === null ? File::delete($path) : File::put($path, $backup);
    }
}

describe('books flag off', function () {
    beforeEach(fn () => setBooksFlag(false));

    test('public book routes return 404', function () {
        $book = Book::factory()->create(['publish_status' => 'published']);

        $this->get(route('books.index'))->assertNotFound();
        $this->get(route('books.show', $book))->assertNotFound();
    });

    test('admin book and chapter routes return 404', function () {
        $admin = booksAdmin();
        $book = Book::factory()->create();
        $chapter = Chapter::factory()->create(['book_id' => $book->id]);

        $this->actingAs($admin);

        $this->get(route('admin.books.index'))->assertNotFound();
        $this->get(route('admin.books.create'))->assertNotFound();
        $this->get(route('admin.books.edit', $book))->assertNotFound();
        $this->post(route('admin.books.store'), [])->assertNotFound();
        $this->delete(route('admin.books.destroy', $book))->assertNotFound();
        $this->get(route('admin.chapters.index'))->assertNotFound();
        $this->get(route('admin.chapters.edit', $chapter))->assertNotFound();
        $this->delete(route('admin.chapters.destroy', $chapter))->assertNotFound();

        expect(Book::find($book->id))->not->toBeNull()
            ->and(Chapter::find($chapter->id))->not->toBeNull();
    });

    test('home page has no featured books and shares the flag as false', function () {
        Book::factory()->create(['featured_flag' => true, 'publish_status' => 'published']);

        $this->get(route('home'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Public/Home')
                ->has('featuredBooks', 0)
                ->where('features.books', false)
                ->where('seo.description', SeoBuilder::HOME_DESCRIPTION_WITHOUT_BOOKS)
            );
    });

    test('the sitemap has no book urls', function () {
        Book::factory()->create(['publish_status' => 'published']);

        $paths = sitemapPaths();

        expect($paths)->toContain('/')
            ->and(collect($paths)->filter(fn ($p) => str_starts_with($p, '/books')))->toBeEmpty();
    });

    test('featured item create and edit pages get no books', function () {
        $admin = booksAdmin();
        Book::factory()->create();
        $item = FeaturedItem::create([
            'item_type' => 'artwork',
            'item_id' => Artwork::factory()->create()->id,
            'display_context' => 'home',
            'display_order' => 1,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.featured-items.create'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page->has('books', 0)->where('features.books', false));

        $this->actingAs($admin)
            ->get(route('admin.featured-items.edit', $item))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page->has('books', 0));
    });

    test('a book featured item cannot be created', function () {
        $admin = booksAdmin();
        $book = Book::factory()->create();

        $this->actingAs($admin)
            ->post(route('admin.featured-items.store'), [
                'item_type' => 'book',
                'item_id' => $book->id,
                'display_context' => 'home',
                'display_order' => 1,
            ])
            ->assertSessionHasErrors('item_type');

        expect(FeaturedItem::count())->toBe(0);
    });

    test('admin tags omit the book count', function () {
        $admin = booksAdmin();
        Tag::factory()->create();

        $this->actingAs($admin)
            ->get(route('admin.tags.index'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->has('tags.data.0.artworks_count')
                ->missing('tags.data.0.books_count')
            );
    });
});

describe('books flag on', function () {
    beforeEach(fn () => setBooksFlag(true));

    test('books index with zero published books returns an empty list', function () {
        Book::factory()->create(['publish_status' => 'draft']);

        $this->get(route('books.index'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Public/Books/Index')
                ->has('books', 0)
                ->where('features.books', true)
            );
    });

    test('books index lists only the published books, with no placeholder entry', function () {
        $books = Book::factory()->count(2)->create(['publish_status' => 'published']);
        Book::factory()->create(['publish_status' => 'draft']);

        $this->get(route('books.index'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->has('books', 2)
                ->where('books', fn ($list) => collect($list)->pluck('id')->sort()->values()->all()
                    === $books->pluck('id')->sort()->values()->all())
            );
    });

    test('home page shares the flag as true and keeps the books description', function () {
        $this->get(route('home'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->where('features.books', true)
                ->where('seo.description', SeoBuilder::HOME_DESCRIPTION)
            );
    });

    test('the sitemap includes the books index and published books', function () {
        $book = Book::factory()->create(['publish_status' => 'published']);

        expect(sitemapPaths())->toContain('/books', '/books/'.$book->slug);
    });

    test('admin tags include the book count', function () {
        $admin = booksAdmin();
        Tag::factory()->create();

        $this->actingAs($admin)
            ->get(route('admin.tags.index'))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page->has('tags.data.0.books_count'));
    });
});
