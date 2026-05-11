<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KiaPemantauanMingguan extends Model
{
    protected $guarded = [];

    public function dataKia()
    {
        return $this->belongsTo(DataKia::class);
    }
}
