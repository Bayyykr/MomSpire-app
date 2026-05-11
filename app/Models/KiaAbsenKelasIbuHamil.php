<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KiaAbsenKelasIbuHamil extends Model
{
    protected $fillable = [
        'data_kia_id',
        'kehadiran_ke',
        'tanggal',
        'kader_info',
    ];

    public function dataKia()
    {
        return $this->belongsTo(DataKia::class, 'data_kia_id');
    }
}
