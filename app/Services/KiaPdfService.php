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

            // 13. PEMANTAUAN BAYI SECTION A (Halaman 23)
            if ($pageNo === 23) {
                $records = $dataKia->pemantauanBayis;
                if ($records && count($records) > 0) {
                    $yMap = [
                        'sesak_napas'      => 222,
                        'aktivitas_lemah'  => 192,
                        'warna_kulit_biru' => 162,
                        'hisapan_lemah'    => 132,
                        'kejang'           => 102,
                        'suhu_abnormal'    => 72,
                        'paraf'            => 56.5,
                    ];

                    $xMap = [
                        1  => 97.5,  2  => 107, 3  => 116, 4  => 125, 5  => 134,
                        6  => 143, 7  => 151, 8  => 160, 9  => 169, 10 => 209,
                        11 => 216.5, 12 => 224.5, 13 => 232, 14 => 240, 15 => 247,
                        16 => 255, 17 => 262, 18 => 270, 19 => 278, 20 => 285,
                        21 => 292, 22 => 300, 23 => 308, 24 => 315, 25 => 323,
                        26 => 331, 27 => 338, 28 => 346,
                    ];

                    $pdf->SetTextColor(0, 0, 0);

                    foreach ($records as $r) {
                        $day = $r->hari_ke;
                        $x = $xMap[$day] ?? null;

                        if (!$x) {
                            continue;
                        }

                        $pdf->SetFont('ZapfDingbats', '', 10);
                        if ($r->sesak_napas) {
                            $pdf->RotatedText($x, $yMap['sesak_napas'], chr(51), 90);
                        }
                        if ($r->aktivitas_lemah) {
                            $pdf->RotatedText($x, $yMap['aktivitas_lemah'], chr(51), 90);
                        }
                        if ($r->warna_kulit_biru) {
                            $pdf->RotatedText($x, $yMap['warna_kulit_biru'], chr(51), 90);
                        }
                        if ($r->hisapan_lemah) {
                            $pdf->RotatedText($x, $yMap['hisapan_lemah'], chr(51), 90);
                        }
                        if ($r->kejang) {
                            $pdf->RotatedText($x, $yMap['kejang'], chr(51), 90);
                        }
                        if ($r->suhu_abnormal) {
                            $pdf->RotatedText($x, $yMap['suhu_abnormal'], chr(51), 90);
                        }

                        if (!empty($r->paraf_kader_nakes)) {
                            $pdf->SetFont('Arial', '', 4);
                            $pdf->RotatedText($x, $yMap['paraf'], $r->paraf_kader_nakes, 90);
                        }
                    }
                }
            }

            // 14. PEMANTAUAN BAYI SECTION B (Halaman 24)
            if ($pageNo === 24) {
                $records = $dataKia->pemantauanBayis;
                if ($records && count($records) > 0) {
                    $yMap = [
                        'bab_abnormal'     => 222,
                        'kencing_sedikit'  => 192,
                        'tali_pusat_merah' => 162,
                        'mata_merah'       => 132,
                        'kulit_bintil'     => 102,
                        'belum_imunisasi'  => 72,
                        'paraf'            => 56.5,
                    ];

                    $xMap = [
                        1  => 97.5,  2  => 107, 3  => 116, 4  => 125, 5  => 134,
                        6  => 143, 7  => 151, 8  => 160, 9  => 169, 10 => 209,
                        11 => 216.5, 12 => 224.5, 13 => 232, 14 => 240, 15 => 247,
                        16 => 255, 17 => 262, 18 => 270, 19 => 278, 20 => 285,
                        21 => 292, 22 => 300, 23 => 308, 24 => 315, 25 => 323,
                        26 => 331, 27 => 338, 28 => 346,
                    ];

                    $pdf->SetTextColor(0, 0, 0);

                    foreach ($records as $r) {
                        $day = $r->hari_ke;
                        $x = $xMap[$day] ?? null;

                        if (!$x) {
                            continue;
                        }

                        $pdf->SetFont('ZapfDingbats', '', 10);
                        if ($r->bab_abnormal) {
                            $pdf->RotatedText($x, $yMap['bab_abnormal'], chr(51), 90);
                        }
                        if ($r->kencing_sedikit) {
                            $pdf->RotatedText($x, $yMap['kencing_sedikit'], chr(51), 90);
                        }
                        if ($r->tali_pusat_merah) {
                            $pdf->RotatedText($x, $yMap['tali_pusat_merah'], chr(51), 90);
                        }
                        if ($r->mata_merah) {
                            $pdf->RotatedText($x, $yMap['mata_merah'], chr(51), 90);
                        }
                        if ($r->kulit_bintil) {
                            $pdf->RotatedText($x, $yMap['kulit_bintil'], chr(51), 90);
                        }
                        if ($r->belum_imunisasi) {
                            $pdf->RotatedText($x, $yMap['belum_imunisasi'], chr(51), 90);
                        }

                        if (!empty($r->paraf_kader_nakes)) {
                            $pdf->SetFont('Arial', '', 4);
                            $pdf->RotatedText($x, $yMap['paraf'], $r->paraf_kader_nakes, 90);
                        }
                    }
                }
            }

            // 15. WARNA TINJA BAYI (Halaman 25)
            if ($pageNo === 25) {
                $t = $dataKia->warnaTinja;
                if ($t) {
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetFont('Arial', 'B', 10);

                    // 2 Minggu
                    if (!empty($t->tanggal_2_minggu)) {
                        $pdf->Text(239, 208, $t->tanggal_2_minggu);
                    }
                    if (!empty($t->nomor_2_minggu)) {
                        $pdf->Text(247, 228, $t->nomor_2_minggu);
                    }

                    // 1 Bulan
                    if (!empty($t->tanggal_1_bulan)) {
                        $pdf->Text(270, 208, $t->tanggal_1_bulan);
                    }
                    if (!empty($t->nomor_1_bulan)) {
                        $pdf->Text(278, 228, $t->nomor_1_bulan);
                    }

                    // 2 - 4 Bulan
                    if (!empty($t->tanggal_2_4_bulan)) {
                        $pdf->Text(300, 208, $t->tanggal_2_4_bulan);
                    }
                    if (!empty($t->nomor_2_4_bulan)) {
                        $pdf->Text(308, 228, $t->nomor_2_4_bulan);
                    }
                }
            }

            // 16. KELAS IBU BALITA (Halaman 27)
            if ($pageNo === 27) {
                $absensi = $dataKia->absenKelasBalitas->keyBy('kehadiran_ke');

                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetFont('Arial', '', 6);

                $yMapRows = [
                    1  => 117,   2  => 122, 3  => 126, 4  => 130.5, 5  => 135,
                    6  => 139.5,   7  => 143.5, 8  => 148, 9  => 153, 10 => 158,
                    11 => 162.5,   12 => 167.5, 13 => 172, 14 => 176.5, 15 => 181,
                    16 => 185,   17 => 189.5, 18 => 194, 19 => 198.8, 20 => 203.3,
                    21 => 207.8,   22 => 212.5, 23 => 217, 24 => 221.5, 25 => 226,
                    26 => 231,   27 => 236, 28 => 240.5, 29 => 245, 30 => 250,
                ];

                // Tabel Kiri (Sesi 1 - 30)
                $xTanggalKiri = 40;
                $xKaderKiri = 75;

                foreach (range(1, 30) as $k) {
                    $item = $absensi->get($k);
                    if ($item) {
                        $y = $yMapRows[$k] ?? null;
                        if ($y) {
                            if (!empty($item->tanggal)) {
                                $pdf->Text($xTanggalKiri, $y, $item->tanggal);
                            }
                            if (!empty($item->kader_info)) {
                                $pdf->Text($xKaderKiri, $y, $item->kader_info);
                            }
                        }
                    }
                }

                // Tabel Kanan (Sesi 31 - 60)
                $xTanggalKanan = 114;
                $xKaderKanan = 149;

                foreach (range(31, 60) as $k) {
                    $item = $absensi->get($k);
                    if ($item) {
                        $rowIdx = $k - 30;
                        $y = $yMapRows[$rowIdx] ?? null;
                        if ($y) {
                            if (!empty($item->tanggal)) {
                                $pdf->Text($xTanggalKanan, $y, $item->tanggal);
                            }
                            if (!empty($item->kader_info)) {
                                $pdf->Text($xKaderKanan, $y, $item->kader_info);
                            }
                        }
                    }
                }
            }

            // 17. PEMANTAUAN MINGGUAN & PERKEMBANGAN BAYI (Halaman 28)
            if ($pageNo === 28) {
                // A. Tabel Pemantauan Mingguan (Minggu 5 - 9) -> Rotated 90 degrees
                $mingguan = $dataKia->pemantauanMingguanBayis;
                if ($mingguan && count($mingguan) > 0) {
                    $yMapMingguan = [
                        'sesak_napas'     => 227,
                        'batuk'           => 207    ,
                        'suhu_abnormal'   => 187,
                        'bab_sering'      => 167,
                        'kencing_sedikit' => 147,
                        'kulit_biru'      => 127,
                        'aktivitas_lemah' => 107,
                        'hisapan_lemah'   => 87,
                        'tidak_makan'     => 67,
                        'paraf'           => 57,
                    ];

                    $xMapMingguan = [
                        5 => 117, 6 => 130, 7 => 142, 8 => 153.5, 9 => 165.5,
                    ];

                    $pdf->SetTextColor(0, 0, 0);

                    foreach ($mingguan as $r) {
                        $week = $r->minggu_ke;
                        $x = $xMapMingguan[$week] ?? null;

                        if (!$x) {
                            continue;
                        }

                        $pdf->SetFont('ZapfDingbats', '', 10);
                        foreach (['sesak_napas', 'batuk', 'suhu_abnormal', 'bab_sering', 'kencing_sedikit', 'kulit_biru', 'aktivitas_lemah', 'hisapan_lemah', 'tidak_makan'] as $field) {
                            if ($r->{$field}) {
                                $pdf->RotatedText($x, $yMapMingguan[$field], chr(51), 90);
                            }
                        }

                        if (!empty($r->paraf_kader_nakes)) {
                            $pdf->SetFont('Arial', '', 5);
                            $pdf->RotatedText($x, $yMapMingguan['paraf'], $r->paraf_kader_nakes, 90);
                        }
                    }
                }

                // B. Tabel Checklist Perkembangan Bayi (Ya / Tidak) -> Normal Upright Text
                $perk = $dataKia->perkembanganBayi;
                if ($perk) {
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetFont('ZapfDingbats', '', 10);

                    $xYa = 329;
                    $xTidak = 341;

                    $yPerk = [
                        'angkat_kepala_45' => 173,
                        'gerak_kepala'     => 184,
                        'tatap_wajah'      => 195,
                        'ngoceh'           => 205,
                        'tertawa_keras'    => 216,
                        'terkejut_suara'   => 226,
                        'tersenyum'        => 236,
                        'mengenal_ibu'     => 247,
                    ];

                    foreach ($yPerk as $field => $y) {
                        $val = $perk->{$field};
                        if ($val === true) {
                            $pdf->Text($xYa, $y, chr(51));
                        } elseif ($val === false) {
                            $pdf->Text($xTidak, $y, chr(51));
                        }
                    }
                }
            }

            // 18. PEMANTAUAN BULANAN & PERKEMBANGAN BAYI 3-6 BULAN (Halaman 29)
            if ($pageNo === 29) {
                // A. Tabel Pemantauan Bulanan (Bulan 3 - 5) -> Rotated 90 degrees
                $bulanan = $dataKia->pemantauanBulananBayis;
                if ($bulanan && count($bulanan) > 0) {
                    $yMapBulanan = [
                        'sesak_napas'     => 227,
                        'batuk'           => 207,
                        'suhu_abnormal'   => 187,
                        'bab_sering'      => 167,
                        'kencing_sedikit' => 147,
                        'kulit_biru'      => 127,
                        'aktivitas_lemah' => 107,
                        'hisapan_lemah'   => 87,
                        'tidak_makan'     => 67,
                        'paraf'           => 56.5,
                    ];

                    $xMapBulanan = [
                        3 => 120, 4 => 142, 5 => 164,
                    ];

                    $pdf->SetTextColor(0, 0, 0);

                    foreach ($bulanan as $r) {
                        $month = $r->bulan_ke;
                        $x = $xMapBulanan[$month] ?? null;

                        if (!$x) {
                            continue;
                        }

                        $pdf->SetFont('ZapfDingbats', '', 10);
                        foreach (['sesak_napas', 'batuk', 'suhu_abnormal', 'bab_sering', 'kencing_sedikit', 'kulit_biru', 'aktivitas_lemah', 'hisapan_lemah', 'tidak_makan'] as $field) {
                            if ($r->{$field}) {
                                $pdf->RotatedText($x, $yMapBulanan[$field], chr(51), 90);
                            }
                        }

                        if (!empty($r->paraf_kader_nakes)) {
                            $pdf->SetFont('Arial', '', 4);
                            $pdf->RotatedText($x, $yMapBulanan['paraf'], $r->paraf_kader_nakes, 90);
                        }
                    }
                }

                // B. Tabel Checklist Perkembangan Bayi 3-6 Bulan (Ya / Tidak) -> Normal Upright Text
                $perk6 = $dataKia->perkembanganBayi6Bulan;
                if ($perk6) {
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetFont('ZapfDingbats', '', 10);

                    $xYa = 329;
                    $xTidak = 341;

                    $yPerk6 = [
                        'berbalik'        => 161,
                        'kepala_tegak_90' => 170,
                        'kepala_stabil'   => 180,
                        'genggam_mainan'  => 190,
                        'raih_benda'      => 199,
                        'amati_tangan'    => 209,
                        'luas_pandang'    => 219,
                        'arah_mata'       => 228,
                        'suara_gembira'   => 238,
                        'senyum_mainan'   => 248,
                    ];

                    foreach ($yPerk6 as $field => $y) {
                        $val = $perk6->{$field};
                        if ($val === true) {
                            $pdf->Text($xYa, $y, chr(51));
                        } elseif ($val === false) {
                            $pdf->Text($xTidak, $y, chr(51));
                        }
                    }
                }
            }

            // 19. PEMANTAUAN BULANAN BAYI 6 - 12 BULAN (Halaman 32)
            if ($pageNo === 32) {
                $bulanan12 = $dataKia->pemantauanBulananBayi12s;
                if ($bulanan12 && count($bulanan12) > 0) {
                    $yMapBulanan12 = [
                        'sesak_napas'     => 227,
                        'batuk'           => 207,
                        'suhu_abnormal'   => 187,
                        'bab_sering'      => 167,
                        'kencing_sedikit' => 147,
                        'kulit_biru'      => 127,
                        'aktivitas_lemah' => 107,
                        'hisapan_lemah'   => 87,
                        'tidak_makan'     => 67,
                        'paraf'           => 56.5,
                    ];

                    $xMapBulanan12 = [
                        6 => 292, 7 => 302, 8 => 312, 9 => 322, 10 => 333, 11 => 343,
                    ];

                    $pdf->SetTextColor(0, 0, 0);

                    foreach ($bulanan12 as $r) {
                        $month = $r->bulan_ke;
                        $x = $xMapBulanan12[$month] ?? null;

                        if (!$x) {
                            continue;
                        }

                        $pdf->SetFont('ZapfDingbats', '', 10);
                        foreach (['sesak_napas', 'batuk', 'suhu_abnormal', 'bab_sering', 'kencing_sedikit', 'kulit_biru', 'aktivitas_lemah', 'hisapan_lemah', 'tidak_makan'] as $field) {
                            if ($r->{$field}) {
                                $pdf->RotatedText($x, $yMapBulanan12[$field], chr(51), 90);
                            }
                        }

                        if (!empty($r->paraf_kader_nakes)) {
                            $pdf->SetFont('Arial', '', 4);
                            $pdf->RotatedText($x, $yMapBulanan12['paraf'], $r->paraf_kader_nakes, 90);
                        }
                    }
                }
            }

            // 20. TUMBUH KEMBANG BAYI 6-9 BULAN & 9-12 BULAN (Halaman 33)
            if ($pageNo === 33) {
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetFont('ZapfDingbats', '', 10);

                // A. Tumbuh Kembang 6 - 9 Bulan (Sisi Kiri)
                $perk9 = $dataKia->perkembanganBayi9Bulan;
                if ($perk9) {
                    $xYa9 = 153.5;
                    $xTidak9 = 165.5;

                    $yPerk9 = [
                        'duduk_mandiri'       => 188,
                        'tengkurap_dada'      => 194,
                        'merangkak'           => 200,
                        'pindah_benda'        => 206,
                        'pungut_2_benda'      => 212,
                        'pungut_kacang'       => 218,
                        'bersuara_tanpa_arti' => 224,
                        'cari_mainan'         => 231,
                        'tepuk_tangan'        => 237,
                        'lempar_benda'        => 243,
                        'makan_kue'           => 249,
                    ];

                    foreach ($yPerk9 as $field => $y) {
                        $val = $perk9->{$field};
                        if ($val === true) {
                            $pdf->Text($xYa9, $y, chr(51));
                        } elseif ($val === false) {
                            $pdf->Text($xTidak9, $y, chr(51));
                        }
                    }
                }

                // B. Tumbuh Kembang 9 - 12 Bulan (Sisi Kanan)
                $perk12 = $dataKia->perkembanganBayi12Bulan;
                if ($perk12) {
                    $xYa12 = 329;
                    $xTidak12 = 341;

                    $yPerk12 = [
                        'angkat_badan_berdiri' => 178,
                        'belajar_berdiri'      => 184,
                        'jalan_dituntun'       => 191,
                        'ulur_tangan_raih'     => 197,
                        'genggam_pensil'       => 204,
                        'masuk_benda_mulut'    => 210,
                        'tiru_bunyi'           => 217,
                        'sebut_2_suku_kata'    => 223,
                        'eksplorasi_sekitar'   => 229,
                        'reaksi_panggilan'     => 235,
                        'bermain_cilukba'      => 242,
                        'kenal_keluarga'       => 248,
                    ];

                    foreach ($yPerk12 as $field => $y) {
                        $val = $perk12->{$field};
                        if ($val === true) {
                            $pdf->Text($xYa12, $y, chr(51));
                        } elseif ($val === false) {
                            $pdf->Text($xTidak12, $y, chr(51));
                        }
                    }
                }
            }

            // 21. PEMANTAUAN BULANAN ANAK 1 - 2 TAHUN (Halaman 35)
            if ($pageNo === 35) {
                $bulanan24 = $dataKia->pemantauanBulananAnak24s;
                if ($bulanan24 && count($bulanan24) > 0) {
                    $yMapBulanan24 = [
                        'sesak_napas'      => 227,
                        'batuk'            => 207,
                        'suhu_abnormal'    => 187,
                        'bab_sering'       => 167,
                        'kencing_sedikit'  => 147,
                        'kulit_pucat_biru' => 127,
                        'aktivitas_lemah'  => 107,
                        'telinga_cairan'   => 87,
                        'tidak_makan'      => 67,
                        'paraf'            => 56.5,
                    ];

                    $xMapBulanan24 = [
                        12 => 121,   13 => 141, 14 => 162,   15 => 213,
                        16 => 230,   17 => 246, 18 => 263,   19 => 279,
                        20 => 295,   21 => 311, 22 => 327,   23 => 343,
                    ];

                    $pdf->SetTextColor(0, 0, 0);

                    foreach ($bulanan24 as $r) {
                        $month = $r->bulan_ke;
                        $x = $xMapBulanan24[$month] ?? null;

                        if (!$x) {
                            continue;
                        }

                        $pdf->SetFont('ZapfDingbats', '', 10);
                        foreach (['sesak_napas', 'batuk', 'suhu_abnormal', 'bab_sering', 'kencing_sedikit', 'kulit_pucat_biru', 'aktivitas_lemah', 'telinga_cairan', 'tidak_makan'] as $field) {
                            if ($r->{$field}) {
                                $pdf->RotatedText($x, $yMapBulanan24[$field], chr(51), 90);
                            }
                        }

                        if (!empty($r->paraf_kader_nakes)) {
                            $pdf->SetFont('Arial', '', 4);
                            $pdf->RotatedText($x, $yMapBulanan24['paraf'], $r->paraf_kader_nakes, 90);
                        }
                    }
                }
            }

            // 22. TUMBUH KEMBANG BAYI 12-18 BULAN & 18-24 BULAN (Halaman 36)
            if ($pageNo === 36) {
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetFont('ZapfDingbats', '', 10);

                // A. Tumbuh Kembang 12 - 18 Bulan (Sisi Kiri)
                $perk18 = $dataKia->perkembanganBayi18Bulan;
                if ($perk18) {
                    $xYa18 = 153.5;
                    $xTidak18 = 165.5;

                    $yPerk18 = [
                        'berdiri_tanpa_pegangan' => 195,
                        'bungkuk_pungut_mainan'  => 203,
                        'jalan_mundur_5_langkah' => 210.5,
                        'panggil_papa_mama'      => 217.5,
                        'tumpuk_2_kubus'         => 224.5,
                        'masuk_kubus_kotak'      => 232,
                        'tunjuk_tanpa_nangis'    => 239.5,
                        'rasa_cemburu'           => 248,
                    ];

                    foreach ($yPerk18 as $field => $y) {
                        $val = $perk18->{$field};
                        if ($val === true) {
                            $pdf->Text($xYa18, $y, chr(51));
                        } elseif ($val === false) {
                            $pdf->Text($xTidak18, $y, chr(51));
                        }
                    }
                }

                // B. Tumbuh Kembang 18 - 24 Bulan (Sisi Kanan)
                $perk24 = $dataKia->perkembanganBayi24Bulan;
                if ($perk24) {
                    $xYa24 = 329;
                    $xTidak24 = 341;

                    $yPerk24 = [
                        'berdiri_30_detik'       => 184,
                        'jalan_tanpa_huyung'     => 193,
                        'tumpuk_4_kubus'         => 202,
                        'pungut_benda_kecil'     => 211,
                        'gelinding_bola'         => 220,
                        'sebut_3_6_kata'         => 229,
                        'bantu_pekerjaan_rumah'  => 238,
                        'pegang_cangkir_sendiri' => 247,
                    ];

                    foreach ($yPerk24 as $field => $y) {
                        $val = $perk24->{$field};
                        if ($val === true) {
                            $pdf->Text($xYa24, $y, chr(51));
                        } elseif ($val === false) {
                            $pdf->Text($xTidak24, $y, chr(51));
                        }
                    }
                }
            }

            // 23. PEMANTAUAN BULANAN ANAK 2-6 TAHUN (Halaman 39: Bulan 24-47)
            if ($pageNo === 39) {
                $bulanan72 = $dataKia->pemantauanBulananAnak72s;
                if ($bulanan72 && $bulanan72->count() > 0) {
                    $pdf->SetTextColor(0, 0, 0);

                    // Peta X untuk bulan 24 s.d. 47
                    $xMapBulanan72 = [
                        24 => 116, 25 => 126, 26 => 136.5, 27 => 146.5, 28 => 157, 29 => 167,
                        30 => 209, 31 => 217, 32 => 225, 33 => 233, 34 => 241, 35 => 249,
                        36 => 257, 37 => 265, 38 => 273, 39 => 281, 40 => 289, 41 => 297,
                        42 => 305, 43 => 313, 44 => 321, 45 => 329, 46 => 337, 47 => 345,
                    ];

                    $yMapBulanan72 = [
                        'sesak_napas'      => 227,
                        'batuk'            => 207,
                        'suhu_abnormal'    => 187,
                        'bab_sering'       => 167,
                        'kencing_sedikit'  => 147,
                        'kulit_pucat_biru' => 127,
                        'aktivitas_lemah'  => 107,
                        'telinga_cairan'   => 87,
                        'tidak_makan'      => 67,
                        'paraf'            => 56.5,
                    ];

                    foreach ($bulanan72 as $r) {
                        $m = $r->bulan_ke;
                        if (!isset($xMapBulanan72[$m])) continue;
                        $x = $xMapBulanan72[$m];

                        $pdf->SetFont('ZapfDingbats', '', 10);
                        foreach (['sesak_napas', 'batuk', 'suhu_abnormal', 'bab_sering', 'kencing_sedikit', 'kulit_pucat_biru', 'aktivitas_lemah', 'telinga_cairan', 'tidak_makan'] as $field) {
                            if ($r->{$field}) {
                                $pdf->RotatedText($x, $yMapBulanan72[$field], chr(51), 90);
                            }
                        }

                        if (!empty($r->paraf_kader_nakes)) {
                            $pdf->SetFont('Arial', '', 4);
                            $pdf->RotatedText($x, $yMapBulanan72['paraf'], $r->paraf_kader_nakes, 90);
                        }
                    }
                }
            }

            // 24. PEMANTAUAN BULANAN ANAK 2-6 TAHUN (Halaman 40: Bulan 48-71)
            if ($pageNo === 40) {
                $bulanan72 = $dataKia->pemantauanBulananAnak72s;
                if ($bulanan72 && $bulanan72->count() > 0) {
                    $pdf->SetTextColor(0, 0, 0);

                    // Peta X untuk bulan 48 s.d. 71
                    $xMapBulanan72 = [
                        48 => 116,  49 => 126,  50 => 136.5,  51 => 146.5,  52 => 157,  53 => 167,
                        54 => 209, 55 => 217, 56 => 225, 57 => 233, 58 => 241, 59 => 249,
                        60 => 257, 61 => 265, 62 => 273, 63 => 281, 64 => 289, 65 => 297,
                        66 => 305, 67 => 313, 68 => 321, 69 => 329, 70 => 337, 71 => 345,
                    ];

                    $yMapBulanan72 = [
                        'sesak_napas'      => 227,
                        'batuk'            => 207,
                        'suhu_abnormal'    => 187,
                        'bab_sering'       => 167,
                        'kencing_sedikit'  => 147,
                        'kulit_pucat_biru' => 127,
                        'aktivitas_lemah'  => 107,
                        'telinga_cairan'   => 87,
                        'tidak_makan'      => 67,
                        'paraf'            => 56.5,
                    ];

                    foreach ($bulanan72 as $r) {
                        $m = $r->bulan_ke;
                        if (!isset($xMapBulanan72[$m])) continue;
                        $x = $xMapBulanan72[$m];

                        $pdf->SetFont('ZapfDingbats', '', 10);
                        foreach (['sesak_napas', 'batuk', 'suhu_abnormal', 'bab_sering', 'kencing_sedikit', 'kulit_pucat_biru', 'aktivitas_lemah', 'telinga_cairan', 'tidak_makan'] as $field) {
                            if ($r->{$field}) {
                                $pdf->RotatedText($x, $yMapBulanan72[$field], chr(51), 90);
                            }
                        }

                        if (!empty($r->paraf_kader_nakes)) {
                            $pdf->SetFont('Arial', '', 4);
                            $pdf->RotatedText($x, $yMapBulanan72['paraf'], $r->paraf_kader_nakes, 90);
                        }
                    }
                }
            }
        }

        return $pdf->Output('S');
    }
}
