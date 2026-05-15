<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KiaCatatanPelayananTrimester2 extends Model
{
    protected $table = 'kia_catatan_pelayanan_trimester2';
    protected $fillable = [
        'data_kia_id',
        'tanggal_periksa',
        'catatan',
        'tanggal_kembali',
    ];

    public function dataKia() { return $this->belongsTo(DataKia::class); }
}
