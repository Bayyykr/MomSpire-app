<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KiaPerkembanganAnak36Bulan extends Model
{
    use HasFactory;

    protected $table = 'kia_perkembangan_anak_36_bulans';

    protected $fillable = [
        'data_kia_id',
        'naik_tangga',
        'tendang_bola',
        'coret_kertas',
        'bicara_2_kata',
        'tunjuk_bagian_tubuh',
        'sebut_nama_benda',
        'pungut_mainan',
        'makan_nasi_sendiri',
        'lepas_pakaian',
    ];

    protected $casts = [
        'naik_tangga'         => 'boolean',
        'tendang_bola'        => 'boolean',
        'coret_kertas'        => 'boolean',
        'bicara_2_kata'       => 'boolean',
        'tunjuk_bagian_tubuh' => 'boolean',
        'sebut_nama_benda'    => 'boolean',
        'pungut_mainan'       => 'boolean',
        'makan_nasi_sendiri'  => 'boolean',
        'lepas_pakaian'       => 'boolean',
    ];

    public function dataKia()
    {
        return $this->belongsTo(DataKia::class);
    }
}
