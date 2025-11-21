<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}
