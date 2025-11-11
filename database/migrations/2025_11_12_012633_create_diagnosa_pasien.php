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
        Schema::create('diagnosa_pasien', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('pasien_id');
            $table->foreignUuid('kunjungan_id');
            $table->foreignId('icd10_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diagnosa_pasien');
    }
};
