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
            $table->renameColumn('margin', 'margin_resep');
            $table->integer('margin_bebas')->nullable();
            $table->integer('margin_apotek')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembelian_detail', function (Blueprint $table) {
            $table->renameColumn('margin_resep', 'margin');
            $table->dropColumn(['margin_bebas', 'margin_apotek']);
        });
    }
};
