<?php

namespace App\Http\Requests\Architect;
use Illuminate\Foundation\Http\FormRequest;
 
class UpdateProjectRequest extends FormRequest
{
    public function authorize(): bool { return auth()->user()->isArchitect(); }
 
    public function rules(): array
    {
        return [
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'tags'        => 'nullable|array',
            'tags.*'      => 'exists:tags,id',
            'images'      => 'nullable|array',
            'images.*'    => 'image|mimes:jpg,jpeg,png,webp|max:4096',
        ];
    }
}