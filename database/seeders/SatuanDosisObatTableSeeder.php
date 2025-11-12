<?php

namespace Database\Seeders;

use App\Models\SatuanDosisObat;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SatuanDosisObatTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['name' => '-'],
            ['name' => '%/5ML'],
            ['name' => '%'],
            ['name' => 'MG'],
            ['name' => 'MG/ML'],
            ['name' => 'MG/5 ML'],
            ['name' => 'MG/100 ML'],
            ['name' => 'MG/2ML'],
            ['name' => 'MG/0,6ML'],
            ['name' => 'µG/H'],
            ['name' => 'GR'],
            ['name' => 'ML'],
            ['name' => '%/2ML'],
            ['name' => '%/10ML'],
            ['name' => 'MG/VIAL'],
            ['name' => 'MG/5ML'],
            ['name' => 'IU'],
            ['name' => 'IU / ML'],
            ['name' => 'FDC'],
            ['name' => 'MG/100.000 IU'],
            ['name' => 'MG/10ML'],
            ['name' => 'MCG'],
            ['name' => 'MG IN 2 ML'],
            ['name' => 'MG/DOSE'],
            ['name' => 'MCG/DOSIS'],
            ['name' => '/100 MCG'],
            ['name' => '/250 MCG'],
            ['name' => '/500 MCG'],
            ['name' => 'MG/ 2,5 ML'],
            ['name' => 'MCG/PUFF'],
            ['name' => 'MG/GR'],
            ['name' => 'MCG/ML'],
            ['name' => 'MG/20%ML'],
            ['name' => 'MG/100ML'],
            ['name' => 'GRAM'],
            ['name' => '%/MG'],
            ['name' => 'MG/0,5ML'],
            ['name' => 'MG/TAB'],
            ['name' => 'MG/2 ML'],
            ['name' => 'MG/1ML'],
            ['name' => 'GR'],
            ['name' => '%/25ML'],
            ['name' => '%/500ML'],
            ['name' => '%/100ML'],
            ['name' => '%/1000ML'],
            ['name' => 'IU/ML'],
            ['name' => 'µG'],
            ['name' => '%/5GR'],
            ['name' => 'ML/1 SYRINGE'],
            ['name' => 'MG/4ML'],
            ['name' => 'GR/5ML'],
            ['name' => 'GR/15ML'],
            ['name' => 'MG/SUPP'],
            ['name' => 'IU/10GR'],
            ['name' => 'IU/5GR'],
            ['name' => 'UNIT/ML'],
            ['name' => 'U/ML'],
            ['name' => 'MG / 50ML'],
            ['name' => 'MG / 20ML'],
            ['name' => 'MG / 100ML'],
            ['name' => 'GR / 50ML'],
            ['name' => 'GR / 100ML'],
            ['name' => '50ML'],
            ['name' => 'MG/50ML'],
            ['name' => 'MG/GRAM'],
            ['name' => 'MG/6ML'],
            ['name' => 'MG/15ML'],
            ['name' => 'MG/45ML'],
            ['name' => 'MG/25MG'],
            ['name' => 'MG/25ML'],
            ['name' => '%/50ML'],
            ['name' => '%/50 ML'],
            ['name' => '%/100 ML'],
            ['name' => '%/250 ML'],
            ['name' => 'ML/VIAL'],
            ['name' => 'MG/3ML'],
            ['name' => 'DOS'],
            ['name' => 'PCS'],
            ['name' => 'ROL'],
            ['name' => 'PACK'],
            ['name' => 'ZAK'],
            ['name' => 'LITER'],
            ['name' => 'KG'],
            ['name' => 'KALENG'],
            ['name' => '%/BOTOL'],
            ['name' => 'BOTOL'],
            ['name' => 'SACH'],
            ['name' => 'BOX'],
            ['name' => 'PATCH'],
            ['name' => 'MCG/4.5 MCG'],
            ['name' => 'SET'],
            ['name' => 'OVULA']
        ];

        foreach ($data as $value) {
            SatuanDosisObat::updateOrCreate($value);
        }
    }
}
