<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ProsedurePasien extends Model
{
    use HasUuids;

    protected $table = 'prosedure_pasien';
    public $guarded = [];
}
