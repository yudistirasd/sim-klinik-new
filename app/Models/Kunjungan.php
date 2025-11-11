<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\ICD10;

class Kunjungan extends Model
{
    use HasUuids;

    protected $table = 'kunjungan';
    public $guarded = [];

    /**
     * Get the pasien that owns the Kunjungan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pasien(): BelongsTo
    {
        return $this->belongsTo(Pasien::class);
    }

    /**
     * Get the ruangan that owns the Kunjungan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ruangan(): BelongsTo
    {
        return $this->belongsTo(Ruangan::class);
    }

    /**
     * Get the dokter that owns the Kunjungan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function dokter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dokter_id');
    }

    /**
     * Get the jenisPenyakit that owns the Kunjungan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function jenisPenyakit(): BelongsTo
    {
        return $this->belongsTo(ICD10::class, 'icd10_id');
    }
}
