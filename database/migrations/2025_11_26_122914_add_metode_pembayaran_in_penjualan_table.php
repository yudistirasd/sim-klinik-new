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
        Schema::table('penjualan', function (Blueprint $table) {
            $table->string('metode_pembayaran')->default('Tunai');
            $table->decimal('total_tagihan', 16, 2)->nullable();
            $table->decimal('diskon', 16, 2)->nullable();
            $table->decimal('total_bayar', 16, 2)->nullable();
            $table->decimal('cash', 16, 2)->nullable();
            $table->decimal('kembalian', 16, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penjualan', function (Blueprint $table) {
            $table->dropColumn([
                'metode_pembayaran',
                'total_tagihan',
                'diskon',
                'total_bayar',
                'cash',
                'kembalian',
            ]);
        });
    }
};
