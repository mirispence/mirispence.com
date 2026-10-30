<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class FeaturedItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_type',
        'item_id',
        'display_context',
        'display_order',
    ];

    protected function casts(): array
    {
        return [
            'display_order' => 'integer',
        ];
    }

    public function item(): MorphTo
    {
        return $this->morphTo();
    }
}
