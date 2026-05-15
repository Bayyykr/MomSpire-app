<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KiaKesehatanLingkungan extends Model
{
    use HasFactory;

    protected $table = 'kia_kesehatan_lingkungans';

    protected $fillable = [
        'data_kia_id',
        'bab_sembarangan',
        'bab_jamban_sendiri',
        'penampung_tangki_septik',
        'penampung_cubluk',
        'penampung_drainase',
        'kloset_leher_angsa',
        'ctps_sarana',
        'ctps_air_mengalir',
        'ctps_sabun',
        'ctps_waktu_sebelum_makan',
        'ctps_waktu_sebelum_mengolah',
        'ctps_waktu_sebelum_menyusui',
        'ctps_waktu_setelah_bab',
        'sumber_air_pipa',
        'sumber_air_kran',
        'sumber_air_sumur_terlindungi',
        'sumber_air_mata_air_terlindungi',
        'sumber_air_sungai',
        'sumber_air_danau',
        'sumber_air_hujan',
        'sumber_air_waduk',
        'sumber_air_kolam',
        'sumber_air_irigasi',
        'kelola_air_rebus',
        'kelola_air_endap_saring',
        'kelola_air_wadah_tertutup',
        'kelola_makanan_tertutup',
        'kelola_makanan_jauh_bahan_berbahaya',
        'kelola_makanan_baik_benar',
        'sampah_tidak_berserakan',
        'sampah_tempat_tertutup',
        'sampah_dipilah',
        'sampah_tidak_dibakar',
        'sampah_tidak_dibuang_sembarangan',
        'limbah_tidak_menggenang',
        'limbah_saluran_tertutup',
        'limbah_terhubung_resapan',
    ];

    protected $casts = [
        'bab_sembarangan' => 'boolean',
        'bab_jamban_sendiri' => 'boolean',
        'penampung_tangki_septik' => 'boolean',
        'penampung_cubluk' => 'boolean',
        'penampung_drainase' => 'boolean',
        'kloset_leher_angsa' => 'boolean',
        'ctps_sarana' => 'boolean',
        'ctps_air_mengalir' => 'boolean',
        'ctps_sabun' => 'boolean',
        'ctps_waktu_sebelum_makan' => 'boolean',
        'ctps_waktu_sebelum_mengolah' => 'boolean',
        'ctps_waktu_sebelum_menyusui' => 'boolean',
        'ctps_waktu_setelah_bab' => 'boolean',
        'sumber_air_pipa' => 'boolean',
        'sumber_air_kran' => 'boolean',
        'sumber_air_sumur_terlindungi' => 'boolean',
        'sumber_air_mata_air_terlindungi' => 'boolean',
        'sumber_air_sungai' => 'boolean',
        'sumber_air_danau' => 'boolean',
        'sumber_air_hujan' => 'boolean',
        'sumber_air_waduk' => 'boolean',
        'sumber_air_kolam' => 'boolean',
        'sumber_air_irigasi' => 'boolean',
        'kelola_air_rebus' => 'boolean',
        'kelola_air_endap_saring' => 'boolean',
        'kelola_air_wadah_tertutup' => 'boolean',
        'kelola_makanan_tertutup' => 'boolean',
        'kelola_makanan_jauh_bahan_berbahaya' => 'boolean',
        'kelola_makanan_baik_benar' => 'boolean',
        'sampah_tidak_berserakan' => 'boolean',
        'sampah_tempat_tertutup' => 'boolean',
        'sampah_dipilah' => 'boolean',
        'sampah_tidak_dibakar' => 'boolean',
        'sampah_tidak_dibuang_sembarangan' => 'boolean',
        'limbah_tidak_menggenang' => 'boolean',
        'limbah_saluran_tertutup' => 'boolean',
        'limbah_terhubung_resapan' => 'boolean',
    ];

    public function dataKia()
    {
        return $this->belongsTo(DataKia::class);
    }
}
