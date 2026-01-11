<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Gallery extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'sort_order',
        'publish_status',
    ];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute(): string
    {
        return $this->getFirstMediaUrl('gallery');
    }

    public function artworks()
    {
        return $this->belongsToMany(Artwork::class)
            ->withPivot('sort_order')
            ->withTimestamps();
    }
}
