<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KiaPersiapanMelahirkan extends Model
{
    protected $fillable = [
        'data_kia_id',
        'tanya_tanggal_perkiraan',
        'hpl_tanggal',
        'hpl_bulan',
        'hpl_tahun',
        'minta_dampingi',
        'siap_tabungan',
        'kartu_jkn',
        'tempat_melahirkan',
        'siap_ktp_kk',
        'siap_pendonor',
        'siap_kendaraan',
        'sepakat_stiker_p4k',
        'rencana_kb',
        'metode_kb',
    ];

    protected $casts = [
        'tanya_tanggal_perkiraan' => 'boolean',
        'minta_dampingi'          => 'boolean',
        'siap_tabungan'           => 'boolean',
        'kartu_jkn'               => 'boolean',
        'tempat_melahirkan'       => 'boolean',
        'siap_ktp_kk'             => 'boolean',
        'siap_pendonor'           => 'boolean',
        'siap_kendaraan'          => 'boolean',
        'sepakat_stiker_p4k'      => 'boolean',
        'rencana_kb'              => 'boolean',
    ];

    public function dataKia()
    {
        return $this->belongsTo(DataKia::class, 'data_kia_id');
    }
}
