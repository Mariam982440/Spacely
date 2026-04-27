<?php

namespace App\Http\Requests\Architect;
use Illuminate\Foundation\Http\FormRequest;
 
class StoreBlogPostRequest extends FormRequest
{
    public function authorize(): bool { return auth()->user()->isArchitect(); }
 
    public function rules(): array
    {
        return [
            'title'       => 'required|string|max:255',
            'content'     => 'required|string',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
            'status'      => 'required|in:draft,published',
        ];
    }
 
    public function messages(): array
    {
        return [
            'title.required'   => 'Le titre est obligatoire.',
            'content.required' => 'Le contenu est obligatoire.',
            'status.in'        => 'Statut invalide.',
        ];
    }
}