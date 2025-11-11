<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AsesmenKeperawatanRequest extends FormRequest
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
        return [
            'berat' => 'bail|required',
            'tinggi' => 'bail|required',
            'nadi' => 'bail|required',
            'suhu' => 'bail|required',
            'respirasi' => 'bail|required',
            'tekanan_darah' => 'bail|required',
            'keluhan' => 'bail|required'
        ];
    }
}
