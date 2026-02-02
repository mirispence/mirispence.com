<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Book extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'publish_status',
        'featured_flag',
        'release_date',
        'external_links',
    ];

    protected $appends = ['image_url', 'thumb_url', 'description_html'];

    public function getThumbUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl('cover', 'thumb');
    }

    public function getDescriptionHtmlAttribute(): string
    {
        return app(\App\Services\MarkdownRenderer::class)->toHtml($this->description);
    }

    protected function casts(): array
    {
        return [
            'featured_flag' => 'boolean',
            'release_date' => 'date',
            'external_links' => 'array',
        ];
    }

    public function getImageUrlAttribute(): string
    {
        return $this->getFirstMediaUrl('cover');
    }

    public function chapters()
    {
        return $this->hasMany(Chapter::class)->orderBy('order');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}
