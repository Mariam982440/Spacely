<?php
namespace App\Http\Requests\Client;
 
use Illuminate\Foundation\Http\FormRequest;
 
class StoreFavoriteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isClient();
    }
 
    public function rules(): array
    {
        return [
            'favoritable_id'   => 'required|integer',
            'favoritable_type' => 'required|string|in:App\Models\Project,App\Models\BlogPost',
        ];
    }
 
    public function messages(): array
    {
        return [
            'favoritable_id.required'   => 'Élément invalide.',
            'favoritable_type.in'       => 'Type d\'élément invalide.',
        ];
    }
}