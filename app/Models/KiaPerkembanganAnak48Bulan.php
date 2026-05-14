<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KiaPerkembanganAnak48Bulan extends Model
{
    use HasFactory;

    protected $table = 'kia_perkembangan_anak_48_bulans';

    protected $fillable = [
        'data_kia_id',
        'berdiri_1_kaki_2_detik',
        'lompat_kedua_kaki',
        'kayuh_sepeda_roda_3',
        'gambar_garis_lurus',
        'tumpuk_8_kubus',
        'kenal_2_4_warna',
        'sebut_nama_umur_tempat',
        'mengerti_arti_kata_posisi',
        'dengar_cerita',
        'cuci_tangan_sendiri',
        'bermain_dengan_teman',
        'pakai_sepatu_sendiri',
        'pakai_celana_baju_sendiri',
    ];

    protected $casts = [
        'berdiri_1_kaki_2_detik'    => 'boolean',
        'lompat_kedua_kaki'         => 'boolean',
        'kayuh_sepeda_roda_3'       => 'boolean',
        'gambar_garis_lurus'        => 'boolean',
        'tumpuk_8_kubus'            => 'boolean',
        'kenal_2_4_warna'           => 'boolean',
        'sebut_nama_umur_tempat'    => 'boolean',
        'mengerti_arti_kata_posisi' => 'boolean',
        'dengar_cerita'             => 'boolean',
        'cuci_tangan_sendiri'       => 'boolean',
        'bermain_dengan_teman'      => 'boolean',
        'pakai_sepatu_sendiri'      => 'boolean',
        'pakai_celana_baju_sendiri' => 'boolean',
    ];

    public function dataKia()
    {
        return $this->belongsTo(DataKia::class);
    }
}
