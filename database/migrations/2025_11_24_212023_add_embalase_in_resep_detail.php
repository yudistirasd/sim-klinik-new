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
            $table->mediumInteger('embalase')->nullable();
            $table->mediumInteger('jasa_resep')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resep_detail', function (Blueprint $table) {
            $table->dropColumn(['embalase', 'jasa_resep']);
        });
    }
};
