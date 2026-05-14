<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KiaKeluargaBerencana extends Model
{
    protected $table = 'kia_keluarga_berencanas';

    protected $fillable = [
        'data_kia_id',
        'paraf_ibu',
    ];

    public function dataKia()
    {
        return $this->belongsTo(DataKia::class, 'data_kia_id');
    }
}
