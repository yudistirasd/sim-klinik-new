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
        Schema::table('log_perubahan_harga', function (Blueprint $table) {
            $table->renameColumn('harga_jual_lama', 'harga_jual_resep_lama');
            $table->renameColumn('harga_jual_baru', 'harga_jual_resep_baru');
            $table->renameColumn('keuntungan_lama', 'keuntungan_resep_lama');
            $table->renameColumn('keuntungan_baru', 'keuntungan_resep_baru');

            $table->decimal('harga_jual_bebas_lama', 16, 2)->nullable();
            $table->decimal('harga_jual_bebas_baru', 16, 2)->nullable();
            $table->decimal('keuntungan_bebas_lama', 16, 2)->nullable();
            $table->decimal('keuntungan_bebas_baru', 16, 2)->nullable();

            $table->decimal('harga_jual_apotek_lama', 16, 2)->nullable();
            $table->decimal('harga_jual_apotek_baru', 16, 2)->nullable();
            $table->decimal('keuntungan_apotek_lama', 16, 2)->nullable();
            $table->decimal('keuntungan_apotek_baru', 16, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('log_perubahan_harga', function (Blueprint $table) {
            $table->renameColumn('harga_jual_resep_lama', 'harga_jual_lama');
            $table->renameColumn('harga_jual_resep_baru', 'harga_jual_baru');
            $table->renameColumn('keuntungan_resep_lama', 'keuntungan_lama');
            $table->renameColumn('keuntungan_resep_baru', 'keuntungan_baru');

            $table->dropColumn([
                'harga_jual_bebas_lama',
                'harga_jual_bebas_baru',
                'keuntungan_bebas_lama',
                'keuntungan_bebas_baru',
                'harga_jual_apotek_lama',
                'harga_jual_apotek_baru',
                'keuntungan_apotek_lama',
                'keuntungan_apotek_baru',
            ]);
        });
    }
};
