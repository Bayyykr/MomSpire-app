<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KiaPerkembanganBayi24Bulan extends Model
{
    protected $table = 'kia_perkembangan_bayi_24_bulans';

    protected $fillable = [
        'data_kia_id',
        'berdiri_30_detik',
        'jalan_tanpa_huyung',
        'tumpuk_4_kubus',
        'pungut_benda_kecil',
        'gelinding_bola',
        'sebut_3_6_kata',
        'bantu_pekerjaan_rumah',
        'pegang_cangkir_sendiri',
    ];

    protected $casts = [
        'berdiri_30_detik'       => 'boolean',
        'jalan_tanpa_huyung'     => 'boolean',
        'tumpuk_4_kubus'         => 'boolean',
        'pungut_benda_kecil'     => 'boolean',
        'gelinding_bola'         => 'boolean',
        'sebut_3_6_kata'         => 'boolean',
        'bantu_pekerjaan_rumah'  => 'boolean',
        'pegang_cangkir_sendiri' => 'boolean',
    ];

    public function dataKia()
    {
        return $this->belongsTo(DataKia::class, 'data_kia_id');
    }
}
