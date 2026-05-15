<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KiaPemeriksaanTrimester1 extends Model
{
    use HasFactory;
    
    protected $table = 'kia_pemeriksaan_trimester1';

    protected $fillable = [
        'data_kia_id',
        'gambar_usg',
        'tgl_periksa_lab',
        'lab_hemoglobin',
        'lab_gol_darah',
        'lab_gula_darah',
        'lab_tripel_h',
        'lab_tripel_s',
        'lab_tripel_hep_b',
        'tgl_skrining_jiwa',
        'skrining_jiwa',
        'tindak_lanjut_jiwa',
        'rujukan_jiwa',
        'kesimpulan',
        'rekomendasi',
        'konjungtiva', 'sklera', 'kulit', 'leher', 'gigi_mulut', 'tht',
        'dada_jantung', 'dada_paru', 'perut', 'tungkai',
        'keterangan_konjungtiva', 'keterangan_sklera', 'keterangan_kulit', 'keterangan_leher',
        'keterangan_gigi_mulut', 'keterangan_tht', 'keterangan_dada_jantung', 'keterangan_dada_paru',
        'keterangan_perut', 'keterangan_tungkai',
        'hpht', 'keteraturan_haid', 'usia_kehamilan_hpht', 'hpl_hpht',
        'usia_kehamilan_usg', 'hpl_usg', 'jumlah_gs', 'diameter_gs_cm',
        'diameter_gs_minggu', 'diameter_gs_hari', 'jumlah_bayi', 'crl_cm',
        'crl_minggu', 'crl_hari', 'letak_produk_kehamilan', 'pulsasi_jantung',
        'kecurigaan_temuan_abnormal', 'kecurigaan_temuan_abnormal_sebutkan',
    ];

    public function dataKia()
    {
        return $this->belongsTo(DataKia::class);
    }
}
