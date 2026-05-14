<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KiaPerkembanganBayi extends Model
{
    protected $table = 'kia_perkembangan_bayis';

    protected $fillable = [
        'data_kia_id',
        'angkat_kepala_45',
        'gerak_kepala',
        'tatap_wajah',
        'ngoceh',
        'tertawa_keras',
        'terkejut_suara',
        'tersenyum',
        'mengenal_ibu',
    ];

    protected $casts = [
        'angkat_kepala_45' => 'boolean',
        'gerak_kepala'     => 'boolean',
        'tatap_wajah'      => 'boolean',
        'ngoceh'           => 'boolean',
        'tertawa_keras'    => 'boolean',
        'terkejut_suara'   => 'boolean',
        'tersenyum'        => 'boolean',
        'mengenal_ibu'     => 'boolean',
    ];

    public function dataKia()
    {
        return $this->belongsTo(DataKia::class, 'data_kia_id');
    }
}
