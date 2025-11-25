<?php

namespace Database\Seeders;

use App\Models\AturanPakaiObat;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AturanPakaiObatTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ["name" => "Diminum"],
            ["name" => "Dikunyah"],
            ["name" => "Diteteskan"],
            ["name" => "Dioleskan Tipis"],
            ["name" => "Obat Luar"],
            ["name" => "Tetes Mata"],
            ["name" => "Tetes Telinga"],
            ["name" => "Tetes Hidung"],
            ["name" => "Disuntikkan"],
            ["name" => "Dihirup / Hisap"],
            ["name" => "Dimasukkan (Rektal)"],
            ["name" => "Dimasukkan (Vaginal)"]
        ];

        foreach ($data as $value) {
            AturanPakaiObat::updateOrCreate($value);
        }
    }
}
