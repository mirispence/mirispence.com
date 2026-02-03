<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Models\Artwork;
use App\Models\Book;
use App\Models\Gallery;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The message of the console command.
     *
     * @var string
     */
    protected $description = 'Generate the sitemap.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $sitemap = Sitemap::create()
            ->add(Url::create('/')->setPriority(1.0)->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY))
            ->add(Url::create('/art')->setPriority(0.9)->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY))
            ->add(Url::create('/books')->setPriority(0.9)->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY))
            ->add(Url::create('/galleries')->setPriority(0.8)->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY))
            ->add(Url::create('/contact')->setPriority(0.5)->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY));

        // Add all published artworks
        Artwork::published()->get()->each(function (Artwork $artwork) use ($sitemap) {
            $sitemap->add(
                Url::create(route('art.show', $artwork))
                    ->setPriority(0.8)
                    ->setLastModificationDate($artwork->updated_at)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
            );
        });

        // Add all published books
        Book::published()->get()->each(function (Book $book) use ($sitemap) {
            $sitemap->add(
                Url::create(route('books.show', $book))
                    ->setPriority(0.8)
                    ->setLastModificationDate($book->updated_at)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
            );
        });

        // Add all published galleries
        Gallery::published()->get()->each(function (Gallery $gallery) use ($sitemap) {
            $sitemap->add(
                Url::create(route('galleries.show', $gallery))
                    ->setPriority(0.7)
                    ->setLastModificationDate($gallery->updated_at)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
            );
        });

        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap generated successfully.');
    }
}
