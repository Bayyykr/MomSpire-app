<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KiaEvaluasiKesehatanIbu extends Model
{
    use HasFactory;

    protected $fillable = [
        'data_kia_id',
        'nama_dokter',
        'tanggal_periksa',
        'fasilitas_kesehatan',
        'tb',
        'bb',
        'imt',
        'lila',
        'lila_kurus',
        'lila_normal',
        'lila_gemuk',
        'lila_obesitas',
        'imunisasi_tt_1',
        'imunisasi_tt_2',
        'imunisasi_tt_3',
        'imunisasi_tt_4',
        'imunisasi_tt_5',
        'riwayat_kesehatan_ibu',
        'riwayat_kesehatan_ibu_lainnya',
        'riwayat_perilaku',
        'riwayat_perilaku_lainnya',
        'riwayat_penyakit_keluarga',
        'riwayat_penyakit_keluarga_lainnya',
        'inspeksi_porsio',
        'inspeksi_uretra',
        'inspeksi_vagina',
        'inspeksi_vulva',
        'inspeksi_fluksus',
        'inspeksi_fluor',
        'riwayat_kehamilan',
    ];

    protected $casts = [
        'imunisasi_tt_1' => 'boolean',
        'imunisasi_tt_2' => 'boolean',
        'imunisasi_tt_3' => 'boolean',
        'imunisasi_tt_4' => 'boolean',
        'imunisasi_tt_5' => 'boolean',
        'riwayat_kesehatan_ibu' => 'array',
        'riwayat_perilaku' => 'array',
        'riwayat_penyakit_keluarga' => 'array',
        'riwayat_kehamilan' => 'array',
    ];

    public function dataKia()
    {
        return $this->belongsTo(DataKia::class);
    }
}
