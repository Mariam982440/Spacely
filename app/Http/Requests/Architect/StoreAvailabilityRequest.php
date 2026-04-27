<?php
namespace App\Http\Requests\Architect;
use Illuminate\Foundation\Http\FormRequest;
 
class StoreAvailabilityRequest extends FormRequest
{
    public function authorize(): bool { return auth()->user()->isArchitect(); }
 
    public function rules(): array
    {
        return [
            'day_of_week'    => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time'     => 'required|date_format:H:i',
            'end_time'       => 'required|date_format:H:i|after:start_time',
            'slot_duration'  => 'required|integer|in:30,60,90,120',
        ];
    }
 
    public function messages(): array
    {
        return [
            'day_of_week.required'   => 'Choisissez un jour.',
            'end_time.after'         => 'L\'heure de fin doit être après l\'heure de début.',
            'slot_duration.in'       => 'Durée invalide.',
        ];
    }
}