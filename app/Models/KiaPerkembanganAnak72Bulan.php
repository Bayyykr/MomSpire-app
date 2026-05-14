<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KiaPerkembanganAnak72Bulan extends Model
{
    use HasFactory;

    protected $table = 'kia_perkembangan_anak_72_bulans';

    protected $fillable = [
        'data_kia_id',
        'berjalan_lurus',
        'berdiri_1_kaki_11_detik',
        'gambar_6_bagian_orang_lengkap',
        'tangkap_bola_kecil',
        'gambar_segi_empat',
        'mengerti_lawan_kata',
        'mengerti_pembicaraan_7_kata',
        'jawab_bahan_guna_benda',
        'kenal_angka_hitung_5_10',
        'kenal_warna_warni',
        'ungkapkan_simpati',
        'ikut_aturan_permainan',
        'pakaian_sendiri_tanpa_bantu',
    ];

    protected $casts = [
        'berjalan_lurus'                => 'boolean',
        'berdiri_1_kaki_11_detik'       => 'boolean',
        'gambar_6_bagian_orang_lengkap' => 'boolean',
        'tangkap_bola_kecil'            => 'boolean',
        'gambar_segi_empat'             => 'boolean',
        'mengerti_lawan_kata'           => 'boolean',
        'mengerti_pembicaraan_7_kata'   => 'boolean',
        'jawab_bahan_guna_benda'        => 'boolean',
        'kenal_angka_hitung_5_10'       => 'boolean',
        'kenal_warna_warni'             => 'boolean',
        'ungkapkan_simpati'             => 'boolean',
        'ikut_aturan_permainan'         => 'boolean',
        'pakaian_sendiri_tanpa_bantu'   => 'boolean',
    ];

    public function dataKia()
    {
        return $this->belongsTo(DataKia::class);
    }
}
