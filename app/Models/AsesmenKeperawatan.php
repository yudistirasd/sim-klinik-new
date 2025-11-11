<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AsesmenKeperawatan extends Model
{
    use HasUuids;

    protected $table = 'asesmen_keperawatan';
    public $guarded = [];

    /**
     * Get the petugass that owns the AsesmenKeperawatan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function petugas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
