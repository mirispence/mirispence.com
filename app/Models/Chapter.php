<?php

namespace App\Models;

use App\Services\MarkdownRenderer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    protected $appends = ['body_html'];

    public function getBodyHtmlAttribute(): string
    {
        return app(MarkdownRenderer::class)->toHtml($this->body_markdown);
    }

    protected function casts(): array
    {
        return [
            'is_sample' => 'boolean',
            'order' => 'integer',
        ];
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }
}
