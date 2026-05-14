<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KiaPerkembanganAnak60Bulan extends Model
{
    use HasFactory;

    protected $table = 'kia_perkembangan_anak_60_bulans';

    protected $fillable = [
        'data_kia_id',
        'berdiri_1_kaki_6_detik',
        'lompat_1_kaki',
        'menari',
        'gambar_tanda_silang',
        'gambar_lingkaran',
        'gambar_orang_3_bagian',
        'kancing_baju_boneka',
        'sebut_nama_lengkap',
        'senang_sebut_kata_baru',
        'senang_bertanya',
        'jawab_pertanyaan_kata_benar',
        'bicara_mudah_dimengerti',
        'banding_ukuran_bentuk',
        'sebut_angka_hitung_jari',
    ];

    protected $casts = [
        'berdiri_1_kaki_6_detik'      => 'boolean',
        'lompat_1_kaki'               => 'boolean',
        'menari'                      => 'boolean',
        'gambar_tanda_silang'         => 'boolean',
        'gambar_lingkaran'            => 'boolean',
        'gambar_orang_3_bagian'       => 'boolean',
        'kancing_baju_boneka'         => 'boolean',
        'sebut_nama_lengkap'          => 'boolean',
        'senang_sebut_kata_baru'      => 'boolean',
        'senang_bertanya'             => 'boolean',
        'jawab_pertanyaan_kata_benar' => 'boolean',
        'bicara_mudah_dimengerti'     => 'boolean',
        'banding_ukuran_bentuk'       => 'boolean',
        'sebut_angka_hitung_jari'     => 'boolean',
    ];

    public function dataKia()
    {
        return $this->belongsTo(DataKia::class);
    }
}
