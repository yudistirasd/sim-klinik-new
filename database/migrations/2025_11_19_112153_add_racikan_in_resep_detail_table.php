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
            $table->foreignId('takaran_id')->nullable(true)->change();
            $table->integer('lama_hari')->nullable(true)->change();
            $table->decimal('unit_dosis')->change();
            $table->decimal('qty')->change();

            $table->enum('jenis_resep', ['non_racikan', 'racikan'])->default('non_racikan');

            // Nomor R-ke: 1,2,3 ... untuk grouping racikan
            $table->integer('receipt_number')->nullable()
                ->comment('Nomor R-ke untuk grouping racikan');

            // Jenis racikan: dtd = dari dokter, non_dtd = perhitungan farmasi
            $table->enum('tipe_racikan', ['dtd', 'non_dtd'])->nullable()
                ->comment('dtd = dosis total dari dokter, non_dtd = dosis per racikan');

            // Total bungkus/kapsul/tube yang harus dibuat
            // DTD      → input manual dari dokter (DTD X)
            // non-DTD  → frekuensi × unit_dosis × lama_hari
            $table->integer('jumlah_racikan')->nullable()
                ->comment('Total bungkus/kapsul/tube racikan');

            // Bentuk racikan → bungkus, kapsul, pot, tube, botol
            $table->string('kemasan_racikan')->nullable()
                ->comment('Kemasan racikan seperti bungkus, kapsul, pot, tube');

            // ----------------------------------------------------------------------
            // 🟥 RACIKAN — KHUSUS DTD
            // ----------------------------------------------------------------------
            // Dosis total per bahan yang tertulis di resep
            // Contoh: Paracetamol 3000 mg DTD X → 3000 mg disimpan di sini
            $table->decimal('total_dosis_obat', 10, 2)->nullable()
                ->comment('Total dosis per bahan yang ditulis dokter khusus DTD');

            // ----------------------------------------------------------------------
            // 🟦 RACIKAN — PERHITUNGAN
            // ----------------------------------------------------------------------

            // Dosis per racikan (mg per bungkus)
            // DTD      = total_dosis_obat / jumlah_racikan
            // Non-DTD = diinput petugas (dosis per bungkus)
            $table->decimal('dosis_per_racikan', 10, 2)->nullable()
                ->comment('Dosis mg per 1 racikan (per bungkus/kapsul)');

            // Dosis per satuan obat → mg per tablet, mg per kapsul
            // Digunakan supaya qty stok bisa dihitung
            $table->decimal('dosis_per_satuan', 10, 2)->nullable()
                ->comment('Dosis mg per satuan obat (tablet/kapsul/tetes)');

            // ----------------------------------------------------------------------
            // 📝 CATATAN
            // ----------------------------------------------------------------------
            $table->text('catatan')->nullable();

            // Speed up grouping R1, R2, R3
            $table->index(['resep_id', 'receipt_number']);
        });

        DB::unprepared("
            CREATE OR REPLACE FUNCTION generate_receipt_number()
                RETURNS TRIGGER
                LANGUAGE PLPGSQL
                AS
            \$\$
            BEGIN
                -- Jika receipt_number SUDAH DISET dari backend, biarkan
                IF NEW.receipt_number IS NOT NULL THEN
                    RETURN NEW;
                END IF;

                -- Non racikan atau insert tanpa receipt_number tetap dapat auto-number
                NEW.receipt_number := (
                    SELECT COALESCE(MAX(rd.receipt_number), 0) + 1
                    FROM resep_detail rd
                    WHERE rd.resep_id = NEW.resep_id
                );

                RETURN NEW;
            END;
            \$\$
        ");

        DB::unprepared("
        DROP TRIGGER IF EXISTS set_receipt_number ON resep_detail;
        CREATE TRIGGER set_receipt_number
            BEFORE INSERT
            ON resep_detail
            FOR EACH ROW
            EXECUTE PROCEDURE generate_receipt_number();
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resep_detail', function (Blueprint $table) {
            $table->float('unit_dosis')->change();
            $table->float('qty')->change();

            $table->dropIndex(['resep_id', 'receipt_number']);

            $table->dropColumn([
                'jenis_resep',
                'receipt_number',
                'tipe_racikan',
                'jumlah_racikan',
                'total_dosis_obat',
                'dosis_per_racikan',
                'dosis_per_satuan',
                'kemasan_racikan',
                'catatan',
            ]);
        });
    }
};
