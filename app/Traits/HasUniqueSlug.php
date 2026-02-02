<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasUniqueSlug
{
    /**
     * Boot the trait and register model events.
     */
    protected static function bootHasUniqueSlug(): void
    {
        static::creating(function ($model) {
            $model->slug = $model->generateUniqueSlug($model->getSlugSourceValue());
        });

        static::updating(function ($model) {
            $sourceField = $model->getSlugSourceField();
            if ($model->isDirty($sourceField)) {
                $model->slug = $model->generateUniqueSlug($model->getSlugSourceValue());
            }
        });
    }

    /**
     * Get the source field used to generate the slug.
     */
    abstract public function getSlugSourceField(): string;

    /**
     * Get the value from the source field.
     */
    public function getSlugSourceValue(): string
    {
        return $this->getAttribute($this->getSlugSourceField()) ?? '';
    }

    /**
     * Generate a unique slug for the model.
     */
    public function generateUniqueSlug(string $value): string
    {
        $slug = Str::slug($value);
        $originalSlug = $slug;
        $count = 1;

        while (static::where('slug', $slug)
            ->where($this->getKeyName(), '!=', $this->getKey())
            ->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }

        return $slug;
    }
}
