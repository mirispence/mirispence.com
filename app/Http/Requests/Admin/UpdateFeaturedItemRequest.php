<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFeaturedItemRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'item_type' => ['required', 'string', 'in:artwork,book'],
            'item_id' => ['required', 'integer'],
            'display_context' => ['required', 'string', 'in:home,sidebar,footer'],
            'display_order' => ['required', 'integer'],
        ];
    }
}
