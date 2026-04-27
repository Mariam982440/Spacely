<?php
namespace App\Http\Requests\Architect;
use Illuminate\Foundation\Http\FormRequest;
 
class StoreMessageRequest extends FormRequest
{
    public function authorize(): bool { return true; }
 
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
            'body.max'      => 'Le message ne peut pas dépasser 2000 caractères.',
        ];
    }
}