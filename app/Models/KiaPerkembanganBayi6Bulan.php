<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KiaPerkembanganBayi6Bulan extends Model
{
    protected $table = 'kia_perkembangan_bayi_6_bulans';

    protected $fillable = [
        'data_kia_id',
        'berbalik',
        'kepala_tegak_90',
        'kepala_stabil',
        'genggam_mainan',
        'raih_benda',
        'amati_tangan',
        'luas_pandang',
        'arah_mata',
        'suara_gembira',
        'senyum_mainan',
    ];

    protected $casts = [
        'berbalik'        => 'boolean',
        'kepala_tegak_90' => 'boolean',
        'kepala_stabil'   => 'boolean',
        'genggam_mainan'  => 'boolean',
        'raih_benda'      => 'boolean',
        'amati_tangan'    => 'boolean',
        'luas_pandang'    => 'boolean',
        'arah_mata'       => 'boolean',
        'suara_gembira'   => 'boolean',
        'senyum_mainan'   => 'boolean',
    ];

    public function dataKia()
    {
        return $this->belongsTo(DataKia::class, 'data_kia_id');
    }
}
