<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KiaPemantauanBulananAnak72 extends Model
{
    protected $table = 'kia_pemantauan_bulanan_anak_72s';

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

    protected $casts = [
        'bulan_ke'         => 'integer',
        'sesak_napas'      => 'boolean',
        'batuk'            => 'boolean',
        'suhu_abnormal'    => 'boolean',
        'bab_sering'       => 'boolean',
        'kencing_sedikit'  => 'boolean',
        'kulit_pucat_biru' => 'boolean',
        'aktivitas_lemah'  => 'boolean',
        'telinga_cairan'   => 'boolean',
        'tidak_makan'      => 'boolean',
    ];

    public function dataKia()
    {
        return $this->belongsTo(DataKia::class, 'data_kia_id');
    }
}
