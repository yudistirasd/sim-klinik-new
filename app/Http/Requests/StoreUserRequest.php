<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => 'required',
            'username' => 'required|alpha_dash|lowercase|unique:users,username',
            'password' => [
                'required',
                'confirmed',
                Password::min(6)
                    ->mixedCase()
            ],
            'role' => 'required',
        ];

        if ($this->nakes) {
            $rules['nik'] = 'required|unique:users,nik';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'nik.required' => 'NIK wajib diisi apabila peran pengguna adalah Tenaga Kesehatan.'
        ];
    }
}
