<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Consent to see mature artwork: either one artwork (`artwork_id`) or every
 * flagged artwork for the rest of the session (`all`). See ADR-0004.
 */
class MatureConsentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'artwork_id' => ['required_without:all', 'nullable', 'integer'],
            'all' => ['sometimes', 'accepted'],
        ];
    }
}
