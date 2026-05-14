<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KiaPerkembanganBayi18Bulan extends Model
{
    protected $table = 'kia_perkembangan_bayi_18_bulans';

    protected $fillable = [
        'data_kia_id',
        'berdiri_tanpa_pegangan',
        'bungkuk_pungut_mainan',
        'jalan_mundur_5_langkah',
        'panggil_papa_mama',
        'tumpuk_2_kubus',
        'masuk_kubus_kotak',
        'tunjuk_tanpa_nangis',
        'rasa_cemburu',
    ];

    protected $casts = [
        'berdiri_tanpa_pegangan' => 'boolean',
        'bungkuk_pungut_mainan'  => 'boolean',
        'jalan_mundur_5_langkah' => 'boolean',
        'panggil_papa_mama'      => 'boolean',
        'tumpuk_2_kubus'         => 'boolean',
        'masuk_kubus_kotak'      => 'boolean',
        'tunjuk_tanpa_nangis'    => 'boolean',
        'rasa_cemburu'           => 'boolean',
    ];

    public function dataKia()
    {
        return $this->belongsTo(DataKia::class, 'data_kia_id');
    }
}
