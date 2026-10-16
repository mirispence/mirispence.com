<?php

namespace App\Http\Requests\Admin;

use App\Models\Gallery;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateGalleryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer'],
            'publish_status' => ['required', 'in:draft,published'],
            'cover_artwork_id' => [
                'nullable',
                'integer',
                // The cover must be one of this gallery's own member artworks.
                Rule::exists('artwork_gallery', 'artwork_id')
                    ->where('gallery_id', $this->gallery()->getKey()),
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'cover_artwork_id.exists' => 'The cover must be one of this gallery\'s artworks.',
        ];
    }

    private function gallery(): Gallery
    {
        /** @var Gallery $gallery */
        $gallery = $this->route('gallery');

        return $gallery;
    }
}
