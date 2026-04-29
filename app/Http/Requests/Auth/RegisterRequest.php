<?php
namespace App\Http\Requests\Auth;
use Illuminate\Foundation\Http\FormRequest;
 
class RegisterRequest extends FormRequest
{
    public function authorize(): bool { return true; }
 
    public function rules(): array
    {
        return [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'role'     => 'required|in:client,architect',
        ];
    }
 
    public function messages(): array
    {
        return [
            'email.unique'       => 'Cet email est déjà utilisé.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
            'role.in'            => 'Rôle invalide.',
        ];
    }
}