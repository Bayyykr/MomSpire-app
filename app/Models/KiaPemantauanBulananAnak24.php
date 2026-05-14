<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KiaPemantauanBulananAnak24 extends Model
{
    protected $table = 'kia_pemantauan_bulanan_anak_24s';

    protected $fillable = [
        'data_kia_id',
        'bulan_ke',
        'sesak_napas',
        'batuk',
        'suhu_abnormal',
        'bab_sering',
        'kencing_sedikit',
        'kulit_pucat_biru',
        'aktivitas_lemah',
        'telinga_cairan',
        'tidak_makan',
        'paraf_kader_nakes',
    ];

    public function dataKia()
    {
        return $this->belongsTo(DataKia::class, 'data_kia_id');
    }
}
