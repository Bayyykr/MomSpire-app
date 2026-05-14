<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KiaPemantauanBayi extends Model
{
    protected $table = 'kia_pemantauan_bayis';

    protected $fillable = [
        'data_kia_id',
        'hari_ke',
        'sesak_napas',
        'aktivitas_lemah',
        'warna_kulit_biru',
        'hisapan_lemah',
        'kejang',
        'suhu_abnormal',
        'bab_abnormal',
        'kencing_sedikit',
        'tali_pusat_merah',
        'mata_merah',
        'kulit_bintil',
        'belum_imunisasi',
        'paraf_kader_nakes',
    ];

    protected $casts = [
        'sesak_napas'      => 'boolean',
        'aktivitas_lemah'  => 'boolean',
        'warna_kulit_biru' => 'boolean',
        'hisapan_lemah'    => 'boolean',
        'kejang'           => 'boolean',
        'suhu_abnormal'    => 'boolean',
        'bab_abnormal'     => 'boolean',
        'kencing_sedikit'  => 'boolean',
        'tali_pusat_merah' => 'boolean',
        'mata_merah'       => 'boolean',
        'kulit_bintil'     => 'boolean',
        'belum_imunisasi'  => 'boolean',
    ];

    public function dataKia()
    {
        return $this->belongsTo(DataKia::class, 'data_kia_id');
    }
}
