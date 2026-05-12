<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KiaPemantauanIbuNifas extends Model
{
    protected $table = 'kia_pemantauan_ibu_nifas';

    protected $fillable = [
        'data_kia_id',
        'hari_ke',
        'pemeriksaan_nifas',
        'konsumsi_vitamin_a',
        'konsumsi_ttd',
        'pemenuhan_gizi',
        'masalah_jiwa',
        'demam',
        'sakit_kepala',
        'pandangan_kabur',
        'nyeri_ulu_hati',
        'jantung_berdebar',
        'keluar_cairan_lahir',
        'napas_pendek',
        'payudara_bengkak',
        'gangguan_bak',
        'kelamin_bengkak',
        'darah_nifas_berbau',
        'pendarahan_hebat',
        'keputihan',
        'paraf_kader_nakes',
    ];

    protected $casts = [
        'pemeriksaan_nifas'    => 'boolean',
        'konsumsi_vitamin_a'   => 'boolean',
        'konsumsi_ttd'         => 'boolean',
        'pemenuhan_gizi'       => 'boolean',
        'masalah_jiwa'         => 'boolean',
        'demam'                => 'boolean',
        'sakit_kepala'         => 'boolean',
        'pandangan_kabur'      => 'boolean',
        'nyeri_ulu_hati'       => 'boolean',
        'jantung_berdebar'     => 'boolean',
        'keluar_cairan_lahir'  => 'boolean',
        'napas_pendek'         => 'boolean',
        'payudara_bengkak'     => 'boolean',
        'gangguan_bak'         => 'boolean',
        'kelamin_bengkak'      => 'boolean',
        'darah_nifas_berbau'   => 'boolean',
        'pendarahan_hebat'     => 'boolean',
        'keputihan'            => 'boolean',
    ];

    public function dataKia()
    {
        return $this->belongsTo(DataKia::class, 'data_kia_id');
    }
}
