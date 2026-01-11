<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function item()
    {
        return $this->morphTo();
    }
}
