<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePasienRequest extends FormRequest
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
            'nik' => 'bail|required',
            'nama' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required',
            'jenis_kelamin' => 'required',
            'alamat' => 'required',
            'provinsi_id' => 'required',
            'kabupaten_id' => 'required',
            'kecamatan_id' => 'required',
            'kelurahan_id' => 'required',
            'nohp' => 'required',
            'pekerjaan_id' => 'required',
            'agama_id' => 'required'
        ];
    }

    public function attributes()
    {
        return [
            'provinsi_id' => 'provinsi',
            'kabupaten_id' => 'kabupaten',
            'kecamatan_id' => 'kecamatan',
            'kelurahan_id' => 'kelurahan',
            'pekerjaan_id' => 'pekerjaan',
            'agama_id' => 'agama',
        ];
    }
}
