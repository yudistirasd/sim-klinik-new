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
        Schema::create('asesmen_keperawatan', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('pasien_id');
            $table->foreignUuid('kunjungan_id');
            $table->foreignUuid('created_by');
            $table->mediumInteger('berat')->nullable();
            $table->mediumInteger('tinggi')->nullable();
            $table->mediumInteger('nadi')->nullable();
            $table->mediumInteger('suhu')->nullable();
            $table->mediumInteger('respirasi')->nullable();
            $table->string('tekanan_darah')->nullable();
            $table->enum('riwayat_ranap', ['Y', 'N'])->default('N');
            $table->string('riwayat_penyakit_keluarga')->nullable();
            $table->string('alergi');
            $table->string('alergi_ket')->nullable();
            $table->text('keluhan');
            $table->enum('sempoyongan', ['Y', 'N'])->default('N');
            $table->enum('pegangan_kursi', ['Y', 'N'])->default('N');
            $table->enum('skrining_nyeri', ['Y', 'N'])->default('N');
            $table->text('penyebab_nyeri')->nullable();
            $table->text('lokasi_nyeri')->nullable();
            $table->text('kualitas_nyeri')->nullable();
            $table->text('skala_nyeri')->nullable();
            $table->text('waktu_nyeri')->nullable();
            $table->string('kehilangan_bb')->default('N');
            $table->string('penurunan_nafsu_makan')->default('N');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asesmen_keperawatan');
    }
};
