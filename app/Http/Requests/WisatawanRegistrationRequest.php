<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WisatawanRegistrationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name'             => 'required|string|max:100',
            'email'            => 'required|email|unique:users,email',
            'password'         => 'required|min:8|confirmed',
            'gol_darah'        => 'nullable|in:A,B,AB,O',
            'kontak_darurat'   => 'nullable|string|max:20',
            'riwayat_alergi'   => 'nullable|string|max:500',
        ];
    }
}
