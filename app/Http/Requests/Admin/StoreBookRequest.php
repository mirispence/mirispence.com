<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('can upload book');
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
            'publish_status' => ['required', 'in:draft,published'],
            'featured_flag' => ['boolean'],
            'release_date' => ['nullable', 'date'],
            'external_links' => ['nullable', 'array'],
            'tags' => ['array'],
            'cover' => ['nullable', 'image', 'max:10240'],
        ];
    }
}
