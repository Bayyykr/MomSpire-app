<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KiaIdentitasAnak extends Model
{
    protected $table = 'kia_identitas_anak';
    protected $guarded = [];
    public function dataKia() { return $this->belongsTo(DataKia::class, 'data_kia_id'); }
}
