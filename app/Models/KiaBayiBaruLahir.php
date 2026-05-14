<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KiaBayiBaruLahir extends Model
{
    protected $table = 'kia_bayi_baru_lahirs';

    protected $fillable = [
        'data_kia_id',
        'jam_0_6',
        'jam_6_48',
        'hari_3_7',
        'hari_8_28',
    ];

    public function dataKia()
    {
        return $this->belongsTo(DataKia::class, 'data_kia_id');
    }
}
