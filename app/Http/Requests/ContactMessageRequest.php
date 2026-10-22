<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['nullable', 'string', 'max:255'],
            'message' => ['required_without:body', 'nullable', 'string', 'min:10', 'max:5000'],
            'body' => ['required_without:message', 'nullable', 'string', 'min:10', 'max:5000'],
            'website' => ['nullable', 'string'], // Honeypot trap
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Please provide your full or preferred name.',
            'email.required' => 'Please provide a valid email address so I can reply.',
            'email.email' => 'Please enter a valid email address.',
            'message.required_without' => 'Please include a message describing your inquiry or project.',
            'body.required_without' => 'Please include a message describing your inquiry or project.',
            'message.min' => 'Please write at least 10 characters so I can understand your project needs.',
            'body.min' => 'Please write at least 10 characters so I can understand your project needs.',
        ];
    }
}
