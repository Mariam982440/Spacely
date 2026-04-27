<?php
namespace App\Http\Requests\Architect;
use Illuminate\Foundation\Http\FormRequest;
 
class StoreQuoteRequest extends FormRequest
{
    public function authorize(): bool { return auth()->user()->isArchitect(); }
 
    public function rules(): array
    {
        return [
            'items'                   => 'required|array|min:1',
            'items.*.description'     => 'required|string|max:255',
            'items.*.quantity'        => 'required|integer|min:1',
            'items.*.unit_price'      => 'required|numeric|min:0',
            'tva'                     => 'required|numeric|min:0|max:100',
        ];
    }
 
    public function messages(): array
    {
        return [
            'items.required'              => 'Ajoutez au moins une ligne.',
            'items.*.description.required'=> 'La description de chaque ligne est obligatoire.',
            'items.*.quantity.min'        => 'La quantité doit être au moins 1.',
            'items.*.unit_price.numeric'  => 'Le prix doit être un nombre.',
        ];
    }
}