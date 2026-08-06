<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreArtworkRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('can upload art');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'alt_text' => ['nullable', 'string', 'max:255'],
            'created_on' => ['nullable', 'date'],
            'publish_status' => ['required', 'in:draft,published'],
            'nsfw_flag' => ['boolean'],
            'featured_flag' => ['boolean'],
            'galleries' => ['array'],
            'tags' => ['array'],
            'image' => ['nullable', 'image', 'max:10240'],
        ];
    }
}
