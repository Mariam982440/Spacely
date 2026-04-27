<?php 
namespace App\Http\Requests\Architect;
use Illuminate\Foundation\Http\FormRequest;
 
class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool { return auth()->user()->isArchitect(); }
 
    public function rules(): array
    {
        return [
            'bio'              => 'nullable|string|max:1000',
            'city'             => 'required|string|max:100',
            'experience_years' => 'required|integer|min:0|max:60',
            'profile_picture'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];
    }
 
    public function messages(): array
    {
        return [
            'city.required'             => 'La ville est obligatoire.',
            'experience_years.required' => 'Les années d\'expérience sont obligatoires.',
            'profile_picture.image'     => 'Le fichier doit être une image.',
            'profile_picture.max'       => 'La photo ne doit pas dépasser 2MB.',
        ];
    }
}