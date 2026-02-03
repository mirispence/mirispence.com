<?php

namespace App\Support\Seo;

use Illuminate\Contracts\Support\Arrayable;

class SeoPayload implements Arrayable
{
    public function __construct(
        public string $title,
        public string $description,
        public string $canonical,
        public string $robots = 'index,follow',
        public array $og = [],
        public array $twitter = [],
        public array|object|null $jsonld = null,
        public string $locale = 'en_US',
        public ?string $site_name = null,
    ) {
        $this->site_name ??= config('app.name');
        
        $this->og = array_merge([
            'type' => 'website',
            'title' => $this->title,
            'description' => $this->description,
            'url' => $this->canonical,
            'site_name' => $this->site_name,
        ], $this->og);

        $this->twitter = array_merge([
            'card' => 'summary_large_image',
            'title' => $this->title,
            'description' => $this->description,
        ], $this->twitter);
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'canonical' => $this->canonical,
            'robots' => $this->robots,
            'og' => $this->og,
            'twitter' => $this->twitter,
            'jsonld' => $this->jsonld,
            'locale' => $this->locale,
            'site_name' => $this->site_name,
        ];
    }
}
