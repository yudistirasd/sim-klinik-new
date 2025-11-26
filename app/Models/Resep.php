<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Resep extends Model
{
    use HasUuids;

    protected $table = 'resep';
    public $guarded = [];

    /**
     * Get the dokter that owns the Resep
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function dokter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dokter_id');
    }

    /**
     * Get the penjualan associated with the Resep
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function penjualan(): HasOne
    {
        return $this->hasOne(Penjualan::class, 'resep_id');
    }

    /**
     * Get the pasien that owns the Resep
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pasien(): BelongsTo
    {
        return $this->belongsTo(Pasien::class);
    }

    /**
     * Get the kunjungan that owns the Resep
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function kunjungan(): BelongsTo
    {
        return $this->belongsTo(Kunjungan::class);
    }
}
