<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
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
            'username' => 'bail|required|alpha_dash|lowercase|' . Rule::unique('users', 'username')->ignore($this->pengguna->id),
            'role' => 'required',
        ];

        if ($this->nakes && !empty($this->nik)) {
            $rules['nik'] = 'required|' . Rule::unique('users', 'nik')->ignore($this->pengguna->id);
        }

        if (!empty($this->password)) {
            $rules['password'] =           [
                'required',
                'confirmed',
                Password::min(6)
                    ->mixedCase()
            ];
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
