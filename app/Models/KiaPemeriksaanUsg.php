<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KiaPemeriksaanUsg extends Model
{
    use HasFactory;

    protected $fillable = [
        'data_kia_id',
        'gambar_usg',
        'catatan',
        'jenis_kegiatan',
    ];

    public function dataKia()
    {
        return $this->belongsTo(DataKia::class);
    }
}
