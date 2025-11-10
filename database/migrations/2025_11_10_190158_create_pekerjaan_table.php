<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pekerjaan', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        $data = [
            ['name' => 'PELAJAR/MAHASISWA'],
            ['name' => 'PENSIUNAN'],
            ['name' => 'TNI/POLRI'],
            ['name' => 'PETANI'],
            ['name' => 'NELAYAN'],
            ['name' => 'PNS/ASN'],
            ['name' => 'WIRASWASTA'],
            ['name' => 'TENAGA KESEHATAN'],
            ['name' => 'TENAGA PENDIDIK'],
            ['name' => 'BURUH PABRIK'],
            ['name' => 'LAINNYA        '],
        ];

        DB::table('pekerjaan')->insert($data);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pekerjaan');
    }
};
