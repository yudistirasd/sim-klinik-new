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
        Schema::create('agama', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        $data = [
            ['name' => 'ISLAM'],
            ['name' => 'KATOLIK'],
            ['name' => 'PROTESTAN'],
            ['name' => 'HINDU'],
            ['name' => 'BUDHA'],
            ['name' => 'KONGHUCHU'],
            ['name' => 'KEPERCAYAAN LAIN'],
        ];

        DB::table('agama')
            ->insert($data);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agama');
    }
};
