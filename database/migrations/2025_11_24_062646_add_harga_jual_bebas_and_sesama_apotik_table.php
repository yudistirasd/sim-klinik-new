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
        Schema::table('pembelian_detail', function (Blueprint $table) {
            $table->renameColumn('harga_jual_satuan', 'harga_jual_resep')->comment('harga jual untuk resep');
            $table->decimal('harga_jual_bebas', 16, 2)->nullable()->comment('harga jual untuk obat bebas ( tanpa resep )');
            $table->decimal('harga_jual_apotek', 16, 2)->nullable()->comment('harga jual untuk sesama apotek');

            $table->dropColumn('keuntungan_satuan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembelian_detail', function (Blueprint $table) {
            $table->decimal('keuntungan_satuan', 16, 2);
            $table->renameColumn('harga_jual_resep', 'harga_jual_satuan');
            $table->dropColumn(['harga_jual_bebas', 'harga_jual_apotek']);
        });
    }
};
