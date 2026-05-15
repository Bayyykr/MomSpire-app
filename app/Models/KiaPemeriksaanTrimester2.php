<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KiaPemeriksaanTrimester2 extends Model
{
    protected $table = 'kia_pemeriksaan_trimester2';
    protected $fillable = [
        'data_kia_id',
        'skrining_preeklampsia',
        'kesimpulan_preeklampsia',
        'lab_gula_darah_puasa',
        'tindak_lanjut_puasa',
        'lab_gula_darah_2jam',
        'tindak_lanjut_2jam',
        'tgl_periksa_diabetes',
        'nama_dokter_diabetes',
    ];

    protected $casts = [
        'skrining_preeklampsia' => 'array',
    ];

    public function dataKia() { return $this->belongsTo(DataKia::class); }
}
