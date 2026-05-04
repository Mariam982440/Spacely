<?php

namespace App\Http\Requests\Client;
 
use Illuminate\Foundation\Http\FormRequest;
 
class StoreMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }
 
    public function rules(): array
    {
        return [
            'body' => 'required|string|max:2000',
        ];
    }
 
    public function messages(): array
    {
        return [
            'body.required' => 'Le message ne peut pas être vide.',
            'body.max'      => 'Le message ne doit pas dépasser 2000 caractères.',
        ];
    }
}
