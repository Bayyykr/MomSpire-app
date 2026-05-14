<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KiaWarnaTinja extends Model
{
    protected $table = 'kia_warna_tinjas';

    protected $fillable = [
        'data_kia_id',
        'tanggal_2_minggu',
        'nomor_2_minggu',
        'tanggal_1_bulan',
        'nomor_1_bulan',
        'tanggal_2_4_bulan',
        'nomor_2_4_bulan',
    ];

    public function dataKia()
    {
        return $this->belongsTo(DataKia::class, 'data_kia_id');
    }
}
