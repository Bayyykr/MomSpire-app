<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KiaPelayananKesehatanIbu extends Model
{
    use HasFactory;

    protected $table = 'kia_pelayanan_kesehatan_ibus';

    protected $fillable = [
        'data_kia_id',
        'kunjungan_ke',
        'tanggal_periksa',
        'tempat_periksa',
        'berat_badan',
        'tinggi_badan',
        'lingkar_lengan_atas',
        'tekanan_darah',
        'tinggi_rahim',
        'letak_janin',
        'denyut_jantung_bayi',
        'status_imunisasi_tt',
        'konseling',
        'skrining_dokter',
        'tablet_tambah_darah',
        'tes_lab_hb',
        'tes_golongan_darah',
        'tes_lab_protein_urine',
        'tes_lab_gula_darah',
        'usg',
        'tripel_eliminasi',
        'tata_laksana_kasus',
    ];

    public function dataKia()
    {
        return $this->belongsTo(DataKia::class);
    }
}
