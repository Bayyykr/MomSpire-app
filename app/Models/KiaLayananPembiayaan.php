<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KiaLayananPembiayaan extends Model
{
    protected $table = 'kia_layanan_pembiayaan';
    protected $guarded = [];
    public function dataKia() { return $this->belongsTo(DataKia::class, 'data_kia_id'); }
}
