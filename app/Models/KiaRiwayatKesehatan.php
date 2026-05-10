<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KiaRiwayatKesehatan extends Model
{
    protected $table = 'kia_riwayat_kesehatan';
    protected $guarded = [];
    public function dataKia() { return $this->belongsTo(DataKia::class, 'data_kia_id'); }
}
