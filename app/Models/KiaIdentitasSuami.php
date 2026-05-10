<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KiaIdentitasSuami extends Model
{
    protected $table = 'kia_identitas_suami';
    protected $guarded = [];
    public function dataKia() { return $this->belongsTo(DataKia::class, 'data_kia_id'); }
}
