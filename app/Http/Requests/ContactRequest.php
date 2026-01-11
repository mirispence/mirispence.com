<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:150'],
            'message' => ['required', 'string', 'min:20', 'max:5000'],
            'type' => ['nullable', 'in:general,commission'],
            'budget_range' => ['nullable', 'string', 'max:100'],
            'desired_due_date' => ['nullable', 'date'],
            'honeypot' => ['prohibited'],
        ];
    }
}
