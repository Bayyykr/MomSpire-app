<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataKia extends Model
{
    protected $fillable = [
        'user_id',
        'faskes_dikeluarkan',
        'tanggal_dikeluarkan',
        'kab_kota_dikeluarkan',
        'provinsi_dikeluarkan',
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function ibu() { return $this->hasOne(KiaIdentitasIbu::class, 'data_kia_id'); }
    public function suami() { return $this->hasOne(KiaIdentitasSuami::class, 'data_kia_id'); }
    public function anak() { return $this->hasOne(KiaIdentitasAnak::class, 'data_kia_id'); }
    public function layanan() { return $this->hasOne(KiaLayananPembiayaan::class, 'data_kia_id'); }
    public function riwayat() { return $this->hasOne(KiaRiwayatKesehatan::class, 'data_kia_id'); }
    public function ttdTrackings() { return $this->hasMany(KiaTtdTracking::class, 'data_kia_id'); }
    public function pemantauanMingguans() { return $this->hasMany(KiaPemantauanMingguan::class, 'data_kia_id'); }
    public function absenKelasIbuHamils() { return $this->hasMany(KiaAbsenKelasIbuHamil::class, 'data_kia_id'); }
}
