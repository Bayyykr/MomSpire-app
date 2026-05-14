<?php

namespace App\Services;

use App\Models\DataKia;
use Illuminate\Support\Facades\Log;

if (!class_exists('App\Services\MyFpdi')) {
    class MyFpdi extends \setasign\Fpdi\Fpdi
    {
        protected $angle = 0;

        function Rotate($angle, $x = -1, $y = -1)
        {
            if ($x == -1)
                $x = $this->x;
            if ($y == -1)
                $y = $this->y;
            if ($this->angle != 0)
                $this->_out('Q');
            $this->angle = $angle;
            if ($angle != 0) {
                $angle *= M_PI / 180;
                $c = cos($angle);
                $s = sin($angle);
                $cx = $x * $this->k;
                $cy = ($this->h - $y) * $this->k;
                $this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm', $c, $s, -$s, $c, $cx, $cy, -$cx, -$cy));
            }
        }

        function RotatedText($x, $y, $txt, $angle)
        {
            $this->Rotate($angle, $x, $y);
            $this->Text($x, $y, $txt);
            $this->Rotate(0);
        }

        function _endpage()
        {
            if ($this->angle != 0) {
                $this->angle = 0;
                $this->_out('Q');
            }
            parent::_endpage();
        }
    }
}

class KiaPdfService
{
    /**
     * Generate the Buku KIA PDF file content.
     *
     * @param  \App\Models\DataKia  $dataKia
     * @return string  Binary PDF string
     */
    public function generate(DataKia $dataKia)
    {
        $originalPath = resource_path('views/buku/Buku KIA (Permenkes).pdf');
        $convertedPath = storage_path('app/buku_kia_converted.pdf');
        $scriptPath = base_path('scripts/convert_pdf_fpdi.py');

        if (!file_exists($originalPath)) {
            abort(404, 'File PDF template tidak ditemukan!');
        }

        // Konversi PDF menggunakan Python/pikepdf agar kompatibel dengan FPDI jika belum ada
        if (!file_exists($convertedPath)) {
            $output = shell_exec("python \"$scriptPath\" \"$originalPath\" \"$convertedPath\" 2>&1");
            if (!file_exists($convertedPath)) {
                abort(500, 'Gagal mengkonversi PDF: ' . $output);
            }
        }

        $pdf = new MyFpdi();
        $pageCount = $pdf->setSourceFile($convertedPath);

        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $templateId = $pdf->importPage($pageNo);
            $size = $pdf->getTemplateSize($templateId);
            $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
            $pdf->useTemplate($templateId);

            $pdf->SetFont('Arial', '', 9);
            $pdf->SetTextColor(0, 0, 0);

            // 1. COVER MAPPING (Halaman 1)
            if ($pageNo === 1) {
                $ibu = $dataKia->ibu;
                $pdf->SetXY(51, 222);
                $pdf->Write(0, $ibu->nama ?? '');
                $pdf->SetXY(32, 240);
                $pdf->Write(0, $dataKia->faskes_dikeluarkan ?? '');
                $pdf->SetXY(106, 240);
                $pdf->Write(0, $dataKia->kab_kota_dikeluarkan ?? '');
                $pdf->SetXY(32, 253);
                $pdf->Write(0, $dataKia->tanggal_dikeluarkan ? date('d-m-Y', strtotime($dataKia->tanggal_dikeluarkan)) : '');
                $pdf->SetXY(106, 253);
                $pdf->Write(0, $dataKia->provinsi_dikeluarkan ?? '');
            }

            // 2. IDENTITAS TABLE MAPPING (Halaman 2)
            if ($pageNo === 2) {
                $ibu = $dataKia->ibu;
                $suami = $dataKia->suami;
                $anak = $dataKia->anak;
                $layanan = $dataKia->layanan;

                // Baris 1: Nama
                $pdf->SetXY(240, 46);
                $pdf->Write(0, $ibu->nama ?? '-');
                $pdf->SetXY(276, 46);
                $pdf->Write(0, $suami->nama ?? '-');
                $pdf->SetXY(312, 46);
                $pdf->Write(0, $anak->nama ?? '-');

                // Baris 2: NIK
                $pdf->SetXY(240, 52);
                $pdf->Write(0, $ibu->nik ?? '-');
                $pdf->SetXY(276, 52);
                $pdf->Write(0, $suami->nik ?? '-');
                $pdf->SetXY(312, 52);
                $pdf->Write(0, $anak->nik ?? '-');

                // Baris 3: No JKN
                $pdf->SetXY(240, 58);
                $pdf->Write(0, $ibu->no_jkn ?? '-');
                $pdf->SetXY(276, 58);
                $pdf->Write(0, $suami->no_jkn ?? '-');
                $pdf->SetXY(312, 58);
                $pdf->Write(0, $anak->no_jkn ?? '-');

                // Baris 4: Faskes TK 1
                $pdf->SetXY(240, 64);
                $pdf->Write(0, $ibu->faskes_tk1 ?? '-');
                $pdf->SetXY(276, 64);
                $pdf->Write(0, $suami->faskes_tk1 ?? '-');
                $pdf->SetXY(312, 64);
                $pdf->Write(0, $anak->faskes_tk1 ?? '-');

                // Baris 5: Faskes Rujukan
                $pdf->SetXY(240, 70);
                $pdf->Write(0, $ibu->faskes_rujukan ?? '-');
                $pdf->SetXY(276, 70);
                $pdf->Write(0, $suami->faskes_rujukan ?? '-');
                $pdf->SetXY(312, 70);
                $pdf->Write(0, $anak->faskes_rujukan ?? '-');

                // Baris 6: Tempat/Tgl Lahir
                $pdf->SetXY(240, 78);
                $pdf->Write(0, (($ibu->tempat_lahir ?? '') . ', ' . ($ibu->tanggal_lahir ?? '')) ?: '-');
                $pdf->SetXY(276, 78);
                $pdf->Write(0, (($suami->tempat_lahir ?? '') . ', ' . ($suami->tanggal_lahir ?? '')) ?: '-');
                $pdf->SetXY(312, 78);
                $pdf->Write(0, (($anak->tempat_lahir ?? '') . ', ' . ($anak->tanggal_lahir ?? '')) ?: '-');

                // Baris 7: Pendidikan
                $pdf->SetXY(240, 84);
                $pdf->Write(0, $ibu->pendidikan ?? '-');
                $pdf->SetXY(276, 84);
                $pdf->Write(0, $suami->pendidikan ?? '-');

                // Baris 8: Pekerjaan
                $pdf->SetXY(240, 90);
                $pdf->Write(0, $ibu->pekerjaan ?? '-');
                $pdf->SetXY(276, 90);
                $pdf->Write(0, $suami->pekerjaan ?? '-');

                // Baris 9: Alamat
                $pdf->SetXY(240, 93);
                $pdf->MultiCell(35, 4, $ibu->alamat ?? '-', 0, 'L');
                $pdf->SetXY(276, 93);
                $pdf->MultiCell(35, 4, $suami->alamat ?? '-', 0, 'L');
                $pdf->SetXY(312, 93);
                $pdf->MultiCell(35, 4, $anak->alamat ?? '-', 0, 'L');

                // Baris 10: Telepon
                $pdf->SetXY(240, 101);
                $pdf->Write(0, $ibu->telepon ?? '-');
                $pdf->SetXY(276, 101);
                $pdf->Write(0, $suami->telepon ?? '-');
                $pdf->SetXY(312, 101);
                $pdf->Write(0, $anak->telepon ?? '-');

                // Baris 11: Anak ke-
                $pdf->SetXY(312, 105);
                $pdf->Write(0, $anak->anak_ke ?? '-');

                // Baris 12: No Akta
                $pdf->SetXY(312, 112);
                $pdf->Write(0, $anak->no_akta_kelahiran ?? '-');

                // Baris 13: Gol Darah
                $pdf->SetXY(240, 118);
                $pdf->Write(0, $ibu->golongan_darah ?? '-');
                $pdf->SetXY(276, 118);
                $pdf->Write(0, $suami->golongan_darah ?? '-');
                $pdf->SetXY(312, 118);
                $pdf->Write(0, $anak->golongan_darah ?? '-');

                // --- SEKSI PEMBIAYAAN LAIN ---
                // Baris 15: Asuransi Lain
                $pdf->SetXY(240, 129);
                $pdf->Write(0, $layanan->asuransi_lain ?? '-');
                $pdf->SetXY(276, 129);
                $pdf->Write(0, $layanan->asuransi_suami ?? '-');
                $pdf->SetXY(312, 129);
                $pdf->Write(0, $layanan->asuransi_anak ?? '-');

                // Baris 16: Nomor
                $pdf->SetXY(240, 135);
                $pdf->Write(0, $layanan->no_asuransi_lain ?? '-');
                $pdf->SetXY(276, 135);
                $pdf->Write(0, $layanan->no_asuransi_suami ?? '-');
                $pdf->SetXY(312, 135);
                $pdf->Write(0, $layanan->no_asuransi_anak ?? '-');

                // Baris 17: Tanggal Berlaku
                $pdf->SetXY(240, 141);
                $pdf->Write(0, $layanan->tanggal_berlaku_asuransi_lain ?? '-');
                $pdf->SetXY(276, 141);
                $pdf->Write(0, $layanan->tanggal_berlaku_asuransi_suami ?? '-');
                $pdf->SetXY(312, 141);
                $pdf->Write(0, $layanan->tanggal_berlaku_asuransi_anak ?? '-');

                // --- SEKSI FASILITAS PELAYANAN KESEHATAN ---
                // Baris 20: Puskesmas Domisili
                $pdf->SetXY(240, 158);
                $pdf->Write(0, $layanan->puskesmas_domisili ?? '-');
                $pdf->SetXY(276, 158);
                $pdf->Write(0, $layanan->puskesmas_domisili_suami ?? '-');
                $pdf->SetXY(312, 158);
                $pdf->Write(0, $layanan->puskesmas_domisili_anak ?? '-');

                // Baris 21: No. Reg. Kohort Ibu
                $pdf->SetXY(240, 166);
                $pdf->Write(0, $layanan->no_reg_kohort_ibu ?? '-');
                $pdf->SetXY(276, 166); $pdf->Write(0, '-');
                $pdf->SetXY(312, 166); $pdf->Write(0, '-');

                // Baris 22: No. Reg. Kohort Bayi
                $pdf->SetXY(240, 176); $pdf->Write(0, '-');
                $pdf->SetXY(276, 176); $pdf->Write(0, '-');
                $pdf->SetXY(312, 176);
                $pdf->Write(0, $layanan->no_reg_kohort_bayi ?? '-');

                // Baris 23: No. Reg. Kohort Balita
                $pdf->SetXY(240, 186); $pdf->Write(0, '-');
                $pdf->SetXY(276, 186); $pdf->Write(0, '-');
                $pdf->SetXY(312, 186);
                $pdf->Write(0, $layanan->no_reg_kohort_balita ?? '-');

                // Baris 25: No. Catatan Medik RS
                $pdf->SetXY(240, 199);
                $pdf->Write(0, $layanan->no_catatan_medik_rs ?? '-');
                $pdf->SetXY(276, 199);
                $pdf->Write(0, $layanan->no_catatan_medik_rs_suami ?? '-');
                $pdf->SetXY(312, 199);
                $pdf->Write(0, $layanan->no_catatan_medik_rs_anak ?? '-');

                // --- SEKSI RIWAYAT KESEHATAN IBU (Halaman 2 Bawah) ---
                $riwayat = $dataKia->riwayat;
                if ($riwayat) {
                    $pdf->SetXY(240, 220); $pdf->Write(0, ($riwayat->usia_ibu ?? '-') . ' Tahun');
                    $pdf->SetXY(240, 225); $pdf->Write(0, $riwayat->kehamilan_ke ?? '-');
                    $pdf->SetXY(240, 231); $pdf->Write(0, $riwayat->jumlah_anak_hidup ?? '-');
                    $pdf->SetXY(240, 237); $pdf->Write(0, $riwayat->riwayat_keguguran ?? '-');
                    $pdf->SetXY(240, 241); $pdf->MultiCell(100, 4, $riwayat->riwayat_penyakit_ibu ?? '-', 0, 'L');
                } else {
                    $pdf->SetXY(240, 220); $pdf->Write(0, '-');
                    $pdf->SetXY(240, 225); $pdf->Write(0, '-');
                    $pdf->SetXY(240, 227); $pdf->Write(0, '-');
                    $pdf->SetXY(240, 229); $pdf->Write(0, '-');
                    $pdf->SetXY(240, 246); $pdf->Write(0, '-');
                }
            }

            // 3. TTD TRACKING MAPPING (Halaman 5)
            if ($pageNo === 5) {
                $trackings = $dataKia->ttdTrackings->keyBy('bulan_ke');
                
                $xMap = [
                    1  => 257.5,  // Bulan 1
                    2  => 266.35, // Bulan 2
                    3  => 276.2,  // Bulan 3
                    4  => 286.05, // Bulan 4
                    5  => 295.9,  // Bulan 5
                    6  => 305.75, // Bulan 6
                    7  => 315.6,  // Bulan 7
                    8  => 325.45, // Bulan 8
                    9  => 335.3,  // Bulan 9
                    10 => 345.15, // Bulan 10
                ];

                $yMap = [
                    1  => 211, // Hari 1
                    2  => 206, // Hari 2
                    3  => 200, // Hari 3
                    4  => 195, // Hari 4
                    5  => 189.5, // Hari 5
                    6  => 184, // Hari 6
                    7  => 178.5, // Hari 7
                    8  => 173.5, // Hari 8
                    9  => 168, // Hari 9
                    10 => 163, // Hari 10
                    11 => 157, // Hari 11
                    12 => 152, // Hari 12
                    13 => 146, // Hari 13
                    14 => 141, // Hari 14
                    15 => 136, // Hari 15
                    16 => 130, // Hari 16
                    17 => 125, // Hari 17
                    18 => 119.5, // Hari 18
                    19 => 114, // Hari 19
                    20 => 109, // Hari 20
                    21 => 103.5, // Hari 21
                    22 => 98,  // Hari 22
                    23 => 92.5,  // Hari 23
                    24 => 87,  // Hari 24
                    25 => 81.5,  // Hari 25
                    26 => 76,  // Hari 26
                    27 => 70.5,  // Hari 27
                    28 => 65.5,  // Hari 28
                    29 => 60,  // Hari 29
                    30 => 54.5,  // Hari 30
                    31 => 49,  // Hari 31
                ];

                $pdf->SetTextColor(0, 0, 0);

                foreach (range(1, 10) as $bulan) {
                    $tracking = $trackings->get($bulan);
                    if ($tracking) {
                        $x = $xMap[$bulan] ?? null;
                        if ($x) {
                            // 1. Plot Checkmarks (Hari 1-31)
                            $pdf->SetFont('ZapfDingbats', '', 9);
                            foreach (range(1, 31) as $hari) {
                                $y = $yMap[$hari] ?? null;
                                if ($y) {
                                    $colName = 'h' . $hari;
                                    if ($tracking->$colName) {
                                        // Cetak tepat di tengah kotak dengan mengimbangi efek rotasi (-4.5)
                                        $pdf->RotatedText($x, $y - 4.5, chr(51), 90);
                                    }
                                }
                            }

                            // 2. Usia Kehamilan (Sesuai koordinat pas Anda - JANGAN DIUBAH)
                            $pdf->SetFont('Arial', '', 9);
                            $pdf->RotatedText($x, 218, $tracking->usia_kehamilan ?? '', 90);

                            // 3. Bulan / Tahun (Sesuai koordinat pas Anda - JANGAN DIUBAH)
                            $pdf->SetFont('Arial', '', 9);
                            $pdf->RotatedText($x, 236.5, $tracking->bulan_tahun ?? '', 90);
                        }
                    }
                }
            }

            // 4. LEMBAR PEMANTAUAN TRIMESTER I (Halaman 7)
            if ($pageNo === 7) {
                $pemantauans = $dataKia->pemantauanMingguans->keyBy('minggu_ke');

                $xMap = [
                    'pemeriksaan_kehamilan' => 55,  // Kolom 1
                    'kelas_ibu_hamil'       => 82,  // Kolom 2
                    'demam_lebih_2_hari'    => 108,  // Kolom 3
                    'pusing_sakit_kepala'   => 133,  // Kolom 4
                    'sulit_tidur_cemas'     => 158, // Kolom 5
                    'risiko_tb'             => 215, // Kolom 6 (Halaman Kanan)
                    'gerakan_bayi'          => 240, // Kolom 7
                    'nyeri_perut_hebat'     => 263, // Kolom 8
                    'keluar_cairan_lahir'   => 286, // Kolom 9
                    'sakit_saat_kencing'    => 310, // Kolom 10
                    'diare_berulang'        => 335, // Kolom 11
                ];

                $yMap = [
                    4  => 123,
                    5  => 129,
                    6  => 136,
                    7  => 142,
                    8  => 148,
                    9  => 154,
                    10 => 160,
                    11 => 167,
                    12 => 173,
                    13 => 180,
                    14 => 186,
                    15 => 192,
                    16 => 199,
                    17 => 205,
                    18 => 211,
                    19 => 217,
                    20 => 224,
                    21 => 230,
                    22 => 237,
                    23 => 243,
                    24 => 249,
                ];

                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetFont('ZapfDingbats', '', 10);

                foreach (range(4, 24) as $w) {
                    $p = $pemantauans->get($w);
                    if ($p) {
                        $visualY = $yMap[$w] ?? null;
                        if ($visualY) {
                            foreach ($xMap as $field => $visualX) {
                                if ($field === 'gerakan_bayi' && $w <= 24) {
                                    continue;
                                }

                                if ($p->{$field}) {
                                    $pdf->Text($visualX, $visualY, chr(51));
                                }
                            }
                        }
                    }
                }
            }

            // 5. LEMBAR PEMANTAUAN TRIMESTER II & III (Halaman 8)
            if ($pageNo === 8) {
                $pemantauans = $dataKia->pemantauanMingguans->keyBy('minggu_ke');

                $xMap = [
                    'pemeriksaan_kehamilan' => 55,  // Kolom 1
                    'kelas_ibu_hamil'       => 82,  // Kolom 2
                    'demam_lebih_2_hari'    => 108,  // Kolom 3
                    'pusing_sakit_kepala'   => 133,  // Kolom 4
                    'sulit_tidur_cemas'     => 158, // Kolom 5
                    'risiko_tb'             => 215, // Kolom 6 (Halaman Kanan)
                    'gerakan_bayi'          => 239, // Kolom 7
                    'nyeri_perut_hebat'     => 263, // Kolom 8
                    'keluar_cairan_lahir'   => 286, // Kolom 9
                    'sakit_saat_kencing'    => 310, // Kolom 10
                    'diare_berulang'        => 335, // Kolom 11
                ];

                $yMap = [
                    25  => 123,
                    26  => 131,
                    27  => 139,
                    28  => 146,
                    29  => 153,
                    30  => 161,
                    31  => 168,
                    32  => 176,
                    33  => 183,
                    34  => 190,
                    35  => 198,
                    36  => 205,
                    37  => 212,
                    38  => 220,
                    39  => 227,
                    40  => 234,
                    41  => 242,
                    42  => 249,
                ];

                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetFont('ZapfDingbats', '', 10);

                foreach (range(25, 42) as $w) {
                    $p = $pemantauans->get($w);
                    if ($p) {
                        $visualY = $yMap[$w] ?? null;
                        if ($visualY) {
                            foreach ($xMap as $field => $visualX) {
                                if ($p->{$field}) {
                                    $pdf->Text($visualX, $visualY, chr(51));
                                }
                            }
                        }
                    }
                }
            }

            // 6. ABSEN KELAS IBU HAMIL (Halaman 9)
            if ($pageNo === 9) {
                $absensi = $dataKia->absenKelasIbuHamils->keyBy('kehadiran_ke');

                $xMap = [
                    'tanggal'    => 222,
                    'kader_info' => 303,
                ];

                $yMap = [
                    1 => 178,
                    2 => 186.5,
                    3 => 195.5,
                    4 => 204.5,
                    5 => 213.5,
                    6 => 222.5,
                    7 => 231,
                    8 => 240,
                    9 => 248,
                ];

                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetFont('Arial', '', 9);

                foreach (range(1, 9) as $k) {
                    $item = $absensi->get($k);
                    if ($item) {
                        $y = $yMap[$k] ?? null;
                        if ($y) {
                            if (!empty($item->tanggal)) {
                                $pdf->Text($xMap['tanggal'], $y, $item->tanggal);
                            }
                            if (!empty($item->kader_info)) {
                                $pdf->Text($xMap['kader_info'], $y, $item->kader_info);
                            }
                        }
                    }
                }
            }

            // 7. PERSIAPAN MELAHIRKAN (Halaman 11)
            if ($pageNo === 11) {
                $p = $dataKia->persiapanMelahirkan;
                if ($p) {
                    $checkboxX = [
                        'col1' => 32,
                        'col2' => 102,
                    ];

                    $rowY = [
                        1 => 155,
                        2 => 173.5,
                        3 => 192,
                        4 => 210,
                        5 => 232.7,
                    ];

                    $pdf->SetTextColor(0, 0, 0);

                    // Centang Kolom Kiri
                    $pdf->SetFont('ZapfDingbats', '', 10);
                    if ($p->tanya_tanggal_perkiraan) {
                        $pdf->Text($checkboxX['col1'], $rowY[1], chr(51));
                    }
                    if ($p->minta_dampingi) {
                        $pdf->Text($checkboxX['col1'], $rowY[2], chr(51));
                    }
                    if ($p->siap_tabungan) {
                        $pdf->Text($checkboxX['col1'], $rowY[3], chr(51));
                    }
                    if ($p->kartu_jkn) {
                        $pdf->Text($checkboxX['col1'], $rowY[4], chr(51));
                    }
                    if ($p->tempat_melahirkan) {
                        $pdf->Text($checkboxX['col1'], $rowY[5], chr(51));
                    }

                    // Centang Kolom Kanan
                    if ($p->siap_ktp_kk) {
                        $pdf->Text($checkboxX['col2'], $rowY[1], chr(51));
                    }
                    if ($p->siap_pendonor) {
                        $pdf->Text($checkboxX['col2'], $rowY[2], chr(51));
                    }
                    if ($p->siap_kendaraan) {
                        $pdf->Text($checkboxX['col2'], $rowY[3], chr(51));
                    }
                    if ($p->sepakat_stiker_p4k) {
                        $pdf->Text($checkboxX['col2'], $rowY[4], chr(51));
                    }
                    if ($p->rencana_kb) {
                        $pdf->Text($checkboxX['col2'], $rowY[5], chr(51));
                    }

                    // Teks Isian
                    $pdf->SetFont('Arial', '', 9);
                    if (!empty($p->hpl_tanggal)) {
                        $pdf->Text(49, $rowY[1] + 9, $p->hpl_tanggal);
                    }
                    if (!empty($p->hpl_bulan)) {
                        $pdf->Text(68.3, $rowY[1] + 9, $p->hpl_bulan);
                    }
                    if (!empty($p->hpl_tahun)) {
                        $pdf->Text(90, $rowY[1] + 9, $p->hpl_tahun);
                    }
                    if (!empty($p->metode_kb)) {
                        $pdf->Text(143, $rowY[5] + 9, $p->metode_kb);
                    }
                }
            }

            // 8. PROSES MELAHIRKAN (Halaman 14) - Fitur Dihapus
            if ($pageNo === 14) {
                // Tidak ada isian yang perlu dicetak
            }

            // 9. PEMANTAUAN IBU NIFAS SECTION A (Halaman 16)
            if ($pageNo === 16) {
                $records = $dataKia->pemantauanIbuNifas;
                if ($records && count($records) > 0) {
                    $yMap = [
                        'pemeriksaan_nifas'  => 227,
                        'konsumsi_vitamin_a' => 207,
                        'konsumsi_ttd'       => 187,
                        'pemenuhan_gizi'     => 167,
                        'masalah_jiwa'       => 147,
                        'demam'              => 127,
                        'sakit_kepala'       => 107,
                        'pandangan_kabur'    => 87,
                        'nyeri_ulu_hati'     => 67,
                        'paraf'              => 56.5,
                    ];

                    $xMap = [
                        1  => 96.5,  2  => 102, 3  => 108, 4  => 113.5, 5  => 119.5,
                        6  => 125, 7  => 130.5, 8  => 136, 9  => 142, 10 => 148,
                        11 => 153, 12 => 159, 13 => 164.5, 14 => 170, 15 => 207.5,
                        16 => 212.5, 17 => 218, 18 => 223, 19 => 228, 20 => 233,
                        21 => 238, 22 => 243, 23 => 248.5, 24 => 254, 25 => 259,
                        26 => 264.5, 27 => 269.5, 28 => 274.5, 29 => 279.5, 30 => 285,
                        31 => 290, 32 => 295, 33 => 300, 34 => 305, 35 => 310,
                        36 => 315, 37 => 320.5, 38 => 325.5, 39 => 331, 40 => 336,
                        41 => 341, 42 => 346,
                    ];

                    $pdf->SetTextColor(0, 0, 0);

                    foreach ($records as $r) {
                        $day = $r->hari_ke;
                        $x = $xMap[$day] ?? null;

                        if (!$x) {
                            continue;
                        }

                        $pdf->SetFont('ZapfDingbats', '', 10);
                        if ($r->pemeriksaan_nifas) {
                            $pdf->RotatedText($x, $yMap['pemeriksaan_nifas'], chr(51), 90);
                        }
                        if ($r->konsumsi_vitamin_a) {
                            $pdf->RotatedText($x, $yMap['konsumsi_vitamin_a'], chr(51), 90);
                        }
                        if ($r->konsumsi_ttd) {
                            $pdf->RotatedText($x, $yMap['konsumsi_ttd'], chr(51), 90);
                        }
                        if ($r->pemenuhan_gizi) {
                            $pdf->RotatedText($x, $yMap['pemenuhan_gizi'], chr(51), 90);
                        }
                        if ($r->masalah_jiwa) {
                            $pdf->RotatedText($x, $yMap['masalah_jiwa'], chr(51), 90);
                        }
                        if ($r->demam) {
                            $pdf->RotatedText($x, $yMap['demam'], chr(51), 90);
                        }
                        if ($r->sakit_kepala) {
                            $pdf->RotatedText($x, $yMap['sakit_kepala'], chr(51), 90);
                        }
                        if ($r->pandangan_kabur) {
                            $pdf->RotatedText($x, $yMap['pandangan_kabur'], chr(51), 90);
                        }
                        if ($r->nyeri_ulu_hati) {
                            $pdf->RotatedText($x, $yMap['nyeri_ulu_hati'], chr(51), 90);
                        }

                        if (!empty($r->paraf_kader_nakes)) {
                            $pdf->SetFont('Arial', '', 4);
                            $pdf->RotatedText($x, $yMap['paraf'], $r->paraf_kader_nakes, 90);
                        }
                    }
                }
            }

            // 10. PEMANTAUAN IBU NIFAS SECTION B (Halaman 17)
            if ($pageNo === 17) {
                $records = $dataKia->pemantauanIbuNifas;
                if ($records && count($records) > 0) {
                    $yMap = [
                        'jantung_berdebar'     => 227,
                        'keluar_cairan_lahir'  => 207,
                        'napas_pendek'         => 187,
                        'payudara_bengkak'     => 167,
                        'gangguan_bak'         => 147,
                        'kelamin_bengkak'      => 127,
                        'darah_nifas_berbau'   => 107,
                        'pendarahan_hebat'     => 87,
                        'keputihan'            => 67,
                        'paraf'                => 56.5,
                    ];

                    $xMap = [
                        1  => 96.5,  2  => 102, 3  => 108, 4  => 113.5, 5  => 119.5,
                        6  => 125, 7  => 130.5, 8  => 136, 9  => 142, 10 => 148,
                        11 => 153, 12 => 159, 13 => 164.5, 14 => 170, 15 => 207.5,
                        16 => 212.5, 17 => 218, 18 => 223, 19 => 228, 20 => 233,
                        21 => 238, 22 => 243, 23 => 248.5, 24 => 254, 25 => 259,
                        26 => 264.5, 27 => 269.5, 28 => 274.5, 29 => 279.5, 30 => 285,
                        31 => 290, 32 => 295, 33 => 300, 34 => 305, 35 => 310,
                        36 => 315, 37 => 320.5, 38 => 325.5, 39 => 331, 40 => 336,
                        41 => 341, 42 => 346,
                    ];

                    $pdf->SetTextColor(0, 0, 0);

                    foreach ($records as $r) {
                        $day = $r->hari_ke;
                        $x = $xMap[$day] ?? null;

                        if (!$x) {
                            continue;
                        }

                        $pdf->SetFont('ZapfDingbats', '', 10);
                        if ($r->jantung_berdebar) {
                            $pdf->RotatedText($x, $yMap['jantung_berdebar'], chr(51), 90);
                        }
                        if ($r->keluar_cairan_lahir) {
                            $pdf->RotatedText($x, $yMap['keluar_cairan_lahir'], chr(51), 90);
                        }
                        if ($r->napas_pendek) {
                            $pdf->RotatedText($x, $yMap['napas_pendek'], chr(51), 90);
                        }
                        if ($r->payudara_bengkak) {
                            $pdf->RotatedText($x, $yMap['payudara_bengkak'], chr(51), 90);
                        }
                        if ($r->gangguan_bak) {
                            $pdf->RotatedText($x, $yMap['gangguan_bak'], chr(51), 90);
                        }
                        if ($r->kelamin_bengkak) {
                            $pdf->RotatedText($x, $yMap['kelamin_bengkak'], chr(51), 90);
                        }
                        if ($r->darah_nifas_berbau) {
                            $pdf->RotatedText($x, $yMap['darah_nifas_berbau'], chr(51), 90);
                        }
                        if ($r->pendarahan_hebat) {
                            $pdf->RotatedText($x, $yMap['pendarahan_hebat'], chr(51), 90);
                        }
                        if ($r->keputihan) {
                            $pdf->RotatedText($x, $yMap['keputihan'], chr(51), 90);
                        }

                        if (!empty($r->paraf_kader_nakes)) {
                            $pdf->SetFont('Arial', '', 4);
                            $pdf->RotatedText($x, $yMap['paraf'], $r->paraf_kader_nakes, 90);
                        }
                    }
                }
            }

            // 11. KELUARGA BERENCANA (Halaman 18)
            if ($pageNo === 18) {
                $p = $dataKia->keluargaBerencana;
                if ($p) {
                    $pdf->SetTextColor(0, 0, 0);

                    // Paraf Ibu di kolom tabel (Arial)
                    if (!empty($p->paraf_ibu)) {
                        $pdf->SetFont('Arial', '', 9);
                        $pdf->Text(333, 245.5, $p->paraf_ibu);
                    }
                }
            }

            // 12. BAYI BARU LAHIR (Halaman 22)
            if ($pageNo === 22) {
                $p = $dataKia->bayiBaruLahir;
                if ($p) {
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetFont('ZapfDingbats', '', 10);

                    if ($p->jam_0_6) {
                        $pdf->Text(32, 233.5, chr(51));
                    }
                    if ($p->jam_6_48) {
                        $pdf->Text(67, 233.5, chr(51));
                    }
                    if ($p->hari_3_7) {
                        $pdf->Text(102, 233.5, chr(51));
                    }
                    if ($p->hari_8_28) {
                        $pdf->Text(137, 233.5, chr(51));
                    }
                }
            }
        }

        return $pdf->Output('S');
    }
}
