<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Laravel\Pennant\Feature;

class StoreFeaturedItemRequest extends FormRequest
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
            // Book items can only be added while the books flag is on (#45).
            'item_type' => ['required', 'string', Rule::in(Feature::active('books') ? ['artwork', 'book'] : ['artwork'])],
            'item_id' => ['required', 'integer'],
            'display_context' => ['required', 'string', 'in:home,sidebar,footer'],
            'display_order' => ['required', 'integer'],
        ];
    }
}
