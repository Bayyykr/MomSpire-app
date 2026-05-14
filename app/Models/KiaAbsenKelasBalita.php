<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KiaAbsenKelasBalita extends Model
{
    protected $table = 'kia_absen_kelas_balitas';

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
