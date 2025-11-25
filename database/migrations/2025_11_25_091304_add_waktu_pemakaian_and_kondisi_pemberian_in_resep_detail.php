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
        Schema::table('resep_detail', function (Blueprint $table) {
            $table->unsignedBigInteger('kondisi_pemberian_obat_id')->nullable();
            $table->jsonb('waktu_pemberian_obat')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resep_detail', function (Blueprint $table) {
            $table->dropColumn(['kondisi_pemberian_obat_id', 'waktu_pemberian_obat']);
        });
    }
};
