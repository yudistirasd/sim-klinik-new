<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePembelianDetailRequest extends FormRequest
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
            'produk_id' => 'required',
            'jumlah_kemasan' => 'required',
            'satuan_kemasan' => 'required',
            'isi_per_kemasan' => 'required',
            'qty' => 'required',
            'harga_beli_kemasan' => 'required',
            'harga_beli_satuan' => 'required',
            'harga_jual_resep' => 'required',
            'harga_jual_bebas' => 'required',
            'harga_jual_apotek' => 'required',
            'margin_resep' => 'required',
            'margin_bebas' => 'required',
            'margin_apotek' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'produk_id' => 'obat',
        ];
    }
}
