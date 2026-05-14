<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KiaPerkembanganBayi9Bulan extends Model
{
    protected $table = 'kia_perkembangan_bayi_9_bulans';

    protected $fillable = [
        'data_kia_id',
        'duduk_mandiri',
        'tengkurap_dada',
        'merangkak',
        'pindah_benda',
        'pungut_2_benda',
        'pungut_kacang',
        'bersuara_tanpa_arti',
        'cari_mainan',
        'tepuk_tangan',
        'lempar_benda',
        'makan_kue',
    ];

    protected $casts = [
        'duduk_mandiri'       => 'boolean',
        'tengkurap_dada'      => 'boolean',
        'merangkak'           => 'boolean',
        'pindah_benda'        => 'boolean',
        'pungut_2_benda'      => 'boolean',
        'pungut_kacang'       => 'boolean',
        'bersuara_tanpa_arti' => 'boolean',
        'cari_mainan'         => 'boolean',
        'tepuk_tangan'        => 'boolean',
        'lempar_benda'        => 'boolean',
        'makan_kue'           => 'boolean',
    ];

    public function dataKia()
    {
        return $this->belongsTo(DataKia::class, 'data_kia_id');
    }
}
