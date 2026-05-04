<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isClient();
    }

    public function rules(): array
    {
        return [
            'time_slot_id' => 'required|exists:time_slots,id',
            'subject'      => 'required|string|max:255',
            'message'      => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'time_slot_id.required' => 'Veuillez sélectionner un créneau.',
            'time_slot_id.exists'   => 'Ce créneau n\'existe pas.',
            'subject.required'      => 'Le sujet est obligatoire.',
            'subject.max'           => 'Le sujet ne doit pas dépasser 255 caractères.',
            'message.max'           => 'Le message ne doit pas dépasser 1000 caractères.',
        ];
    }
}