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
        Schema::table('produk_stok', function (Blueprint $table) {
            $table->renameColumn('harga_jual', 'harga_jual_resep');
            $table->decimal('harga_jual_bebas', 16, 2)->nullable();
            $table->decimal('harga_jual_apotek', 16, 2)->nullable();

            $table->dropColumn('keuntungan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produk_stok', function (Blueprint $table) {
            $table->decimal('keuntungan', 16, 2);
            $table->renameColumn('harga_jual_resep', 'harga_jual');
            $table->dropColumn(['harga_jual_bebas', 'harga_jual_apotek']);
        });
    }
};
