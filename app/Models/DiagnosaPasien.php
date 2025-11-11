<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DiagnosaPasien extends Model
{
    use HasUuids;

    protected $table = 'diagnosa_pasien';
    public $guarded = [];

    /**
     * Get the icd10 that owns the DiagnosaPasien
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function icd10(): BelongsTo
    {
        return $this->belongsTo(ICD10::class);
    }
}
