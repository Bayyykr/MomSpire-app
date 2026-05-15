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
    public function pemantauanBulananBayi12s() { return $this->hasMany(KiaPemantauanBulananBayi12::class, 'data_kia_id'); }
    public function perkembanganBayi9Bulan() { return $this->hasOne(KiaPerkembanganBayi9Bulan::class, 'data_kia_id'); }
    public function perkembanganBayi12Bulan() { return $this->hasOne(KiaPerkembanganBayi12Bulan::class, 'data_kia_id'); }
    public function pemantauanBulananAnak24s() { return $this->hasMany(KiaPemantauanBulananAnak24::class, 'data_kia_id'); }
    public function perkembanganBayi18Bulan() { return $this->hasOne(KiaPerkembanganBayi18Bulan::class, 'data_kia_id'); }
    public function perkembanganBayi24Bulan() { return $this->hasOne(KiaPerkembanganBayi24Bulan::class, 'data_kia_id'); }
    public function pemantauanBulananAnak72s() { return $this->hasMany(KiaPemantauanBulananAnak72::class, 'data_kia_id'); }
    public function perkembanganAnak36Bulan() { return $this->hasOne(KiaPerkembanganAnak36Bulan::class, 'data_kia_id'); }
    public function perkembanganAnak48Bulan() { return $this->hasOne(KiaPerkembanganAnak48Bulan::class, 'data_kia_id'); }
    public function perkembanganAnak60Bulan() { return $this->hasOne(KiaPerkembanganAnak60Bulan::class, 'data_kia_id'); }
    public function perkembanganAnak72Bulan() { return $this->hasOne(KiaPerkembanganAnak72Bulan::class, 'data_kia_id'); }
    public function pemeriksaanTrimester1() { return $this->hasOne(KiaPemeriksaanTrimester1::class, 'data_kia_id'); }
    public function catatanPelayananTrimester1() { return $this->hasMany(KiaCatatanPelayananTrimester1::class, 'data_kia_id'); }
    public function pemeriksaanTrimester2() { return $this->hasOne(KiaPemeriksaanTrimester2::class, 'data_kia_id'); }
    public function catatanPelayananTrimester2() { return $this->hasMany(KiaCatatanPelayananTrimester2::class, 'data_kia_id'); }
    public function kesehatanLingkungan() { return $this->hasOne(KiaKesehatanLingkungan::class, 'data_kia_id'); }
    public function pelayananKesehatanIbu() { return $this->hasMany(KiaPelayananKesehatanIbu::class, 'data_kia_id'); }
    public function evaluasiKesehatanIbu() { return $this->hasOne(KiaEvaluasiKesehatanIbu::class, 'data_kia_id'); }
}
