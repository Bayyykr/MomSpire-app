<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KiaPemantauanMingguanBayi extends Model
{
    protected $table = 'kia_pemantauan_mingguan_bayis';

    protected $fillable = [
        'data_kia_id',
        'minggu_ke',
        'sesak_napas',
        'batuk',
        'suhu_abnormal',
        'bab_sering',
        'kencing_sedikit',
        'kulit_biru',
        'aktivitas_lemah',
        'hisapan_lemah',
        'tidak_makan',
        'paraf_kader_nakes',
    ];

    public function dataKia()
    {
        return $this->belongsTo(DataKia::class, 'data_kia_id');
    }
}
