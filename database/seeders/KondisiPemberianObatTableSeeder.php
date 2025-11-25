<?php

namespace Database\Seeders;

use App\Models\KondisiPemberianObat;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KondisiPemberianObatTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ["name" => "Sebelum Makan"],
            ["name" => "Sesudah Makan"],
            ["name" => "Saat Makan"],
            ["name" => "2 Jam Sesudah Makan"],
            ["name" => "Sesudah Makan Obat Harus Habis"],
            ["name" => "Bila Perlu"],
            ["name" => "Bangun Tidur"],
            ["name" => "Sebelum Tidur"],
            ["name" => "Untuk Nyeri"],
            ["name" => "Untuk Demam"],
            ["name" => "Jika Perlu"],
            ["name" => "Saat Batuk"],
            ["name" => "Saat Alergi"],
            ["name" => "Saat Sesak"],
            ["name" => "Jika Timbul Keluhan"],
            ["name" => "Untuk Kejang"],
            ["name" => "Sesuai Petunjuk Dokter"],
            ["name" => "Jangan Dicampur Dengan Susu"],
            ["name" => "Jangan Dicampur Dengan Jus"],
            ["name" => "Minum Dengan Air Putih"]
        ];

        foreach ($data as $value) {
            KondisiPemberianObat::updateOrCreate($value);
        }
    }
}
