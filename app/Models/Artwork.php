<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Artwork extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'alt_text',
        'created_on',
        'publish_status',
        'nsfw_flag',
        'featured_flag',
        'metadata',
    ];

    protected $appends = ['image_url'];

    protected function casts(): array
    {
        return [
            'created_on' => 'date',
            'nsfw_flag' => 'boolean',
            'featured_flag' => 'boolean',
            'metadata' => 'array',
        ];
    }

    public function getImageUrlAttribute(): string
    {
        return $this->getFirstMediaUrl('artwork');
    }

    public function galleries()
    {
        return $this->belongsToMany(Gallery::class)
            ->withPivot('sort_order')
            ->withTimestamps();
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}
