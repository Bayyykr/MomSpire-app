<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KiaIdentitasIbu extends Model
{
    protected $table = 'kia_identitas_ibu';
    protected $guarded = [];
    public function dataKia() { return $this->belongsTo(DataKia::class, 'data_kia_id'); }
}
