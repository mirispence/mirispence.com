<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    use HasFactory;
    protected $fillable = [
        'book_id',
        'title',
        'slug',
        'summary',
        'body_markdown',
        'order',
        'is_sample',
    ];

    protected function casts(): array
    {
        return [
            'is_sample' => 'boolean',
            'order' => 'integer',
        ];
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}
