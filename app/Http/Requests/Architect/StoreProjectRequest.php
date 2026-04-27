<?php

namespace App\Http\Requests\Architect;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isArchitect();
    }

    public function rules(): array
    {
        return [
            'title'             => 'required|string|max:255',
            'description'       => 'required|string',
            'tags'              => 'nullable|array',
            'tags.*'            => 'exists:tags,id',
            'images'            => 'required|array|min:1',
            'images.*'          => 'image|mimes:jpg,jpeg,png,webp|max:4096',
            'is_before'         => 'nullable|array',
            'is_after'          => 'nullable|array',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'    => 'Le titre est obligatoire.',
            'description.required' => 'La description est obligatoire.',
            'images.required'   => 'Ajoutez au moins une photo.',
            'images.*.image'    => 'Chaque fichier doit être une image.',
            'images.*.max'      => 'Chaque image ne doit pas dépasser 4MB.',
        ];
    }
}