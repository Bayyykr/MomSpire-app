<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KiaProsesMelahirkan extends Model
{
    protected $fillable = [
        'data_kia_id',
        'mulas_teratur',
        'durasi_persalinan',
        'hak_pendamping',
        'hak_posisi',
        'ingin_bab',
        'kurangi_sakit',
        'inisiasi_menyusu_dini',
    ];

    protected $casts = [
        'mulas_teratur'         => 'boolean',
        'durasi_persalinan'     => 'boolean',
        'hak_pendamping'        => 'boolean',
        'hak_posisi'            => 'boolean',
        'ingin_bab'             => 'boolean',
        'kurangi_sakit'         => 'boolean',
        'inisiasi_menyusu_dini' => 'boolean',
    ];

    public function dataKia()
    {
        return $this->belongsTo(DataKia::class, 'data_kia_id');
    }
}
