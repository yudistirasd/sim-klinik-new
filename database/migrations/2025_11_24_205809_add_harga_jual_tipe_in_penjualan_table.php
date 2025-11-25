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

        DB::statement("ALTER TABLE penjualan DROP CONSTRAINT penjualan_jenis_check");

        DB::statement("
            UPDATE penjualan
            SET jenis = CASE
                WHEN jenis IN ('resep_in', 'resep_ex') THEN 'resep'
                WHEN jenis = 'ecer' THEN 'bebas'
            END
        ");


        DB::statement("
            ALTER TABLE penjualan
            ADD CONSTRAINT penjualan_jenis_check
            CHECK (jenis IN ('resep', 'bebas', 'apotek'))
        ");

        DB::statement("ALTER TABLE penjualan ALTER COLUMN jenis SET DEFAULT 'resep'");

        Schema::table('penjualan_detail', function (Blueprint $table) {
            $table->enum('harga_jual_tipe', ['resep', 'bebas', 'apotek'])->default('resep')->comment('resep | bebas | apotek');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE penjualan ALTER COLUMN jenis DROP DEFAULT");

        // drop constraint baru
        DB::statement("ALTER TABLE penjualan DROP CONSTRAINT penjualan_jenis_check");

        // restore constraint lama
        DB::statement("
            ALTER TABLE penjualan
            ADD CONSTRAINT penjualan_jenis_check
            CHECK (jenis IN ('resep_in', 'resep_ex', 'ecer'))
        ");

        Schema::table('penjualan_detail', function (Blueprint $table) {
            $table->dropColumn('harga_jual_tipe');
        });
    }
};
