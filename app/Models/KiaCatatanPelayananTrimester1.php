<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KiaCatatanPelayananTrimester1 extends Model
{
    use HasFactory;

    protected $table = 'kia_catatan_pelayanan_trimester1';

    protected $fillable = [
        'data_kia_id',
        'tanggal_periksa',
        'catatan',
        'tanggal_kembali',
    ];

    public function dataKia()
    {
        return $this->belongsTo(DataKia::class);
    }
}
