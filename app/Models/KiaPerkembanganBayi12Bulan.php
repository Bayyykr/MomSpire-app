<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KiaPerkembanganBayi12Bulan extends Model
{
    protected $table = 'kia_perkembangan_bayi_12_bulans';

    protected $fillable = [
        'data_kia_id',
        'angkat_badan_berdiri',
        'belajar_berdiri',
        'jalan_dituntun',
        'ulur_tangan_raih',
        'genggam_pensil',
        'masuk_benda_mulut',
        'tiru_bunyi',
        'sebut_2_suku_kata',
        'eksplorasi_sekitar',
        'reaksi_panggilan',
        'bermain_cilukba',
        'kenal_keluarga',
    ];

    protected $casts = [
        'angkat_badan_berdiri' => 'boolean',
        'belajar_berdiri'      => 'boolean',
        'jalan_dituntun'       => 'boolean',
        'ulur_tangan_raih'     => 'boolean',
        'genggam_pensil'       => 'boolean',
        'masuk_benda_mulut'    => 'boolean',
        'tiru_bunyi'           => 'boolean',
        'sebut_2_suku_kata'    => 'boolean',
        'eksplorasi_sekitar'   => 'boolean',
        'reaksi_panggilan'     => 'boolean',
        'bermain_cilukba'      => 'boolean',
        'kenal_keluarga'       => 'boolean',
    ];

    public function dataKia()
    {
        return $this->belongsTo(DataKia::class, 'data_kia_id');
    }
}
