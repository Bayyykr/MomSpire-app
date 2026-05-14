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
    public function persiapanMelahirkan() { return $this->hasOne(KiaPersiapanMelahirkan::class, 'data_kia_id'); }
    public function pemantauanIbuNifas() { return $this->hasMany(KiaPemantauanIbuNifas::class, 'data_kia_id'); }
    public function keluargaBerencana() { return $this->hasOne(KiaKeluargaBerencana::class, 'data_kia_id'); }
    public function bayiBaruLahir() { return $this->hasOne(KiaBayiBaruLahir::class, 'data_kia_id'); }
    public function pemantauanBayis() { return $this->hasMany(KiaPemantauanBayi::class, 'data_kia_id'); }
    public function warnaTinja() { return $this->hasOne(KiaWarnaTinja::class, 'data_kia_id'); }
    public function absenKelasBalitas() { return $this->hasMany(KiaAbsenKelasBalita::class, 'data_kia_id'); }
    public function pemantauanMingguanBayis() { return $this->hasMany(KiaPemantauanMingguanBayi::class, 'data_kia_id'); }
    public function perkembanganBayi() { return $this->hasOne(KiaPerkembanganBayi::class, 'data_kia_id'); }
    public function pemantauanBulananBayis() { return $this->hasMany(KiaPemantauanBulananBayi::class, 'data_kia_id'); }
    public function perkembanganBayi6Bulan() { return $this->hasOne(KiaPerkembanganBayi6Bulan::class, 'data_kia_id'); }
}
