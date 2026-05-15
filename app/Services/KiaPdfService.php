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

        function Ellipse($x, $y, $rx, $ry, $style = 'D')
        {
            if ($style == 'F')
                $op = 'f';
            elseif ($style == 'FD' || $style == 'DF')
                $op = 'B';
            else
                $op = 'S';
            $lx = 4 / 3 * (M_SQRT2 - 1) * $rx;
            $ly = 4 / 3 * (M_SQRT2 - 1) * $ry;
            $k = $this->k;
            $h = $this->h;
            $this->_out(sprintf(
                '%.2F %.2F m %.2F %.2F %.2F %.2F %.2F %.2F c',
                ($x + $rx) * $k,
                ($h - $y) * $k,
                ($x + $rx) * $k,
                ($h - ($y - $ly)) * $k,
                ($x + $lx) * $k,
                ($h - ($y - $ry)) * $k,
                $x * $k,
                ($h - ($y - $ry)) * $k
            ));
            $this->_out(sprintf(
                '%.2F %.2F %.2F %.2F %.2F %.2F c',
                ($x - $lx) * $k,
                ($h - ($y - $ry)) * $k,
                ($x - $rx) * $k,
                ($h - ($y - $ly)) * $k,
                ($x - $rx) * $k,
                ($h - $y) * $k
            ));
            $this->_out(sprintf(
                '%.2F %.2F %.2F %.2F %.2F %.2F c',
                ($x - $rx) * $k,
                ($h - ($y + $ly)) * $k,
                ($x - $lx) * $k,
                ($h - ($y + $ry)) * $k,
                $x * $k,
                ($h - ($y + $ry)) * $k
            ));
            $this->_out(sprintf(
                '%.2F %.2F %.2F %.2F %.2F %.2F c %s',
                ($x + $lx) * $k,
                ($h - ($y + $ry)) * $k,
                ($x + $rx) * $k,
                ($h - ($y + $ly)) * $k,
                ($x + $rx) * $k,
                ($h - $y) * $k,
                $op
            ));
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
        ini_set('memory_limit', '1024M'); // Tingkatkan memory limit untuk menangani PDF besar
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
                $pdf->SetXY(276, 166);
                $pdf->Write(0, '-');
                $pdf->SetXY(312, 166);
                $pdf->Write(0, '-');

                // Baris 22: No. Reg. Kohort Bayi
                $pdf->SetXY(240, 176);
                $pdf->Write(0, '-');
                $pdf->SetXY(276, 176);
                $pdf->Write(0, '-');
                $pdf->SetXY(312, 176);
                $pdf->Write(0, $layanan->no_reg_kohort_bayi ?? '-');

                // Baris 23: No. Reg. Kohort Balita
                $pdf->SetXY(240, 186);
                $pdf->Write(0, '-');
                $pdf->SetXY(276, 186);
                $pdf->Write(0, '-');
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
                    $pdf->SetXY(240, 220);
                    $pdf->Write(0, ($riwayat->usia_ibu ?? '-') . ' Tahun');
                    $pdf->SetXY(240, 225);
                    $pdf->Write(0, $riwayat->kehamilan_ke ?? '-');
                    $pdf->SetXY(240, 231);
                    $pdf->Write(0, $riwayat->jumlah_anak_hidup ?? '-');
                    $pdf->SetXY(240, 237);
                    $pdf->Write(0, $riwayat->riwayat_keguguran ?? '-');
                    $pdf->SetXY(240, 241);
                    $pdf->MultiCell(100, 4, $riwayat->riwayat_penyakit_ibu ?? '-', 0, 'L');
                } else {
                    $pdf->SetXY(240, 220);
                    $pdf->Write(0, '-');
                    $pdf->SetXY(240, 225);
                    $pdf->Write(0, '-');
                    $pdf->SetXY(240, 227);
                    $pdf->Write(0, '-');
                    $pdf->SetXY(240, 229);
                    $pdf->Write(0, '-');
                    $pdf->SetXY(240, 246);
                    $pdf->Write(0, '-');
                }
            }

            // 3. TTD TRACKING MAPPING (Halaman 5)
            if ($pageNo === 5) {
                $trackings = $dataKia->ttdTrackings->keyBy('bulan_ke');

                $xMap = [
                    1 => 257.5,  // Bulan 1
                    2 => 266.35, // Bulan 2
                    3 => 276.2,  // Bulan 3
                    4 => 286.05, // Bulan 4
                    5 => 295.9,  // Bulan 5
                    6 => 305.75, // Bulan 6
                    7 => 315.6,  // Bulan 7
                    8 => 325.45, // Bulan 8
                    9 => 335.3,  // Bulan 9
                    10 => 345.15, // Bulan 10
                ];

                $yMap = [
                    1 => 211, // Hari 1
                    2 => 206, // Hari 2
                    3 => 200, // Hari 3
                    4 => 195, // Hari 4
                    5 => 189.5, // Hari 5
                    6 => 184, // Hari 6
                    7 => 178.5, // Hari 7
                    8 => 173.5, // Hari 8
                    9 => 168, // Hari 9
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
                    'kelas_ibu_hamil' => 82,  // Kolom 2
                    'demam_lebih_2_hari' => 108,  // Kolom 3
                    'pusing_sakit_kepala' => 133,  // Kolom 4
                    'sulit_tidur_cemas' => 158, // Kolom 5
                    'risiko_tb' => 215, // Kolom 6 (Halaman Kanan)
                    'gerakan_bayi' => 240, // Kolom 7
                    'nyeri_perut_hebat' => 263, // Kolom 8
                    'keluar_cairan_lahir' => 286, // Kolom 9
                    'sakit_saat_kencing' => 310, // Kolom 10
                    'diare_berulang' => 335, // Kolom 11
                ];

                $yMap = [
                    4 => 123,
                    5 => 129,
                    6 => 136,
                    7 => 142,
                    8 => 148,
                    9 => 154,
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
                    'kelas_ibu_hamil' => 82,  // Kolom 2
                    'demam_lebih_2_hari' => 108,  // Kolom 3
                    'pusing_sakit_kepala' => 133,  // Kolom 4
                    'sulit_tidur_cemas' => 158, // Kolom 5
                    'risiko_tb' => 215, // Kolom 6 (Halaman Kanan)
                    'gerakan_bayi' => 239, // Kolom 7
                    'nyeri_perut_hebat' => 263, // Kolom 8
                    'keluar_cairan_lahir' => 286, // Kolom 9
                    'sakit_saat_kencing' => 310, // Kolom 10
                    'diare_berulang' => 335, // Kolom 11
                ];

                $yMap = [
                    25 => 123,
                    26 => 131,
                    27 => 139,
                    28 => 146,
                    29 => 153,
                    30 => 161,
                    31 => 168,
                    32 => 176,
                    33 => 183,
                    34 => 190,
                    35 => 198,
                    36 => 205,
                    37 => 212,
                    38 => 220,
                    39 => 227,
                    40 => 234,
                    41 => 242,
                    42 => 249,
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
                    'tanggal' => 222,
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
                        'pemeriksaan_nifas' => 227,
                        'konsumsi_vitamin_a' => 207,
                        'konsumsi_ttd' => 187,
                        'pemenuhan_gizi' => 167,
                        'masalah_jiwa' => 147,
                        'demam' => 127,
                        'sakit_kepala' => 107,
                        'pandangan_kabur' => 87,
                        'nyeri_ulu_hati' => 67,
                        'paraf' => 56.5,
                    ];

                    $xMap = [
                        1 => 96.5,
                        2 => 102,
                        3 => 108,
                        4 => 113.5,
                        5 => 119.5,
                        6 => 125,
                        7 => 130.5,
                        8 => 136,
                        9 => 142,
                        10 => 148,
                        11 => 153,
                        12 => 159,
                        13 => 164.5,
                        14 => 170,
                        15 => 207.5,
                        16 => 212.5,
                        17 => 218,
                        18 => 223,
                        19 => 228,
                        20 => 233,
                        21 => 238,
                        22 => 243,
                        23 => 248.5,
                        24 => 254,
                        25 => 259,
                        26 => 264.5,
                        27 => 269.5,
                        28 => 274.5,
                        29 => 279.5,
                        30 => 285,
                        31 => 290,
                        32 => 295,
                        33 => 300,
                        34 => 305,
                        35 => 310,
                        36 => 315,
                        37 => 320.5,
                        38 => 325.5,
                        39 => 331,
                        40 => 336,
                        41 => 341,
                        42 => 346,
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
                        'jantung_berdebar' => 227,
                        'keluar_cairan_lahir' => 207,
                        'napas_pendek' => 187,
                        'payudara_bengkak' => 167,
                        'gangguan_bak' => 147,
                        'kelamin_bengkak' => 127,
                        'darah_nifas_berbau' => 107,
                        'pendarahan_hebat' => 87,
                        'keputihan' => 67,
                        'paraf' => 56.5,
                    ];

                    $xMap = [
                        1 => 96.5,
                        2 => 102,
                        3 => 108,
                        4 => 113.5,
                        5 => 119.5,
                        6 => 125,
                        7 => 130.5,
                        8 => 136,
                        9 => 142,
                        10 => 148,
                        11 => 153,
                        12 => 159,
                        13 => 164.5,
                        14 => 170,
                        15 => 207.5,
                        16 => 212.5,
                        17 => 218,
                        18 => 223,
                        19 => 228,
                        20 => 233,
                        21 => 238,
                        22 => 243,
                        23 => 248.5,
                        24 => 254,
                        25 => 259,
                        26 => 264.5,
                        27 => 269.5,
                        28 => 274.5,
                        29 => 279.5,
                        30 => 285,
                        31 => 290,
                        32 => 295,
                        33 => 300,
                        34 => 305,
                        35 => 310,
                        36 => 315,
                        37 => 320.5,
                        38 => 325.5,
                        39 => 331,
                        40 => 336,
                        41 => 341,
                        42 => 346,
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
                        'sesak_napas' => 222,
                        'aktivitas_lemah' => 192,
                        'warna_kulit_biru' => 162,
                        'hisapan_lemah' => 132,
                        'kejang' => 102,
                        'suhu_abnormal' => 72,
                        'paraf' => 56.5,
                    ];

                    $xMap = [
                        1 => 97.5,
                        2 => 107,
                        3 => 116,
                        4 => 125,
                        5 => 134,
                        6 => 143,
                        7 => 151,
                        8 => 160,
                        9 => 169,
                        10 => 209,
                        11 => 216.5,
                        12 => 224.5,
                        13 => 232,
                        14 => 240,
                        15 => 247,
                        16 => 255,
                        17 => 262,
                        18 => 270,
                        19 => 278,
                        20 => 285,
                        21 => 292,
                        22 => 300,
                        23 => 308,
                        24 => 315,
                        25 => 323,
                        26 => 331,
                        27 => 338,
                        28 => 346,
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
                        'bab_abnormal' => 222,
                        'kencing_sedikit' => 192,
                        'tali_pusat_merah' => 162,
                        'mata_merah' => 132,
                        'kulit_bintil' => 102,
                        'belum_imunisasi' => 72,
                        'paraf' => 56.5,
                    ];

                    $xMap = [
                        1 => 97.5,
                        2 => 107,
                        3 => 116,
                        4 => 125,
                        5 => 134,
                        6 => 143,
                        7 => 151,
                        8 => 160,
                        9 => 169,
                        10 => 209,
                        11 => 216.5,
                        12 => 224.5,
                        13 => 232,
                        14 => 240,
                        15 => 247,
                        16 => 255,
                        17 => 262,
                        18 => 270,
                        19 => 278,
                        20 => 285,
                        21 => 292,
                        22 => 300,
                        23 => 308,
                        24 => 315,
                        25 => 323,
                        26 => 331,
                        27 => 338,
                        28 => 346,
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
                    1 => 117,
                    2 => 122,
                    3 => 126,
                    4 => 130.5,
                    5 => 135,
                    6 => 139.5,
                    7 => 143.5,
                    8 => 148,
                    9 => 153,
                    10 => 158,
                    11 => 162.5,
                    12 => 167.5,
                    13 => 172,
                    14 => 176.5,
                    15 => 181,
                    16 => 185,
                    17 => 189.5,
                    18 => 194,
                    19 => 198.8,
                    20 => 203.3,
                    21 => 207.8,
                    22 => 212.5,
                    23 => 217,
                    24 => 221.5,
                    25 => 226,
                    26 => 231,
                    27 => 236,
                    28 => 240.5,
                    29 => 245,
                    30 => 250,
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
                        'sesak_napas' => 227,
                        'batuk' => 207,
                        'suhu_abnormal' => 187,
                        'bab_sering' => 167,
                        'kencing_sedikit' => 147,
                        'kulit_biru' => 127,
                        'aktivitas_lemah' => 107,
                        'hisapan_lemah' => 87,
                        'tidak_makan' => 67,
                        'paraf' => 57,
                    ];

                    $xMapMingguan = [
                        5 => 117,
                        6 => 130,
                        7 => 142,
                        8 => 153.5,
                        9 => 165.5,
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
                        'gerak_kepala' => 184,
                        'tatap_wajah' => 195,
                        'ngoceh' => 205,
                        'tertawa_keras' => 216,
                        'terkejut_suara' => 226,
                        'tersenyum' => 236,
                        'mengenal_ibu' => 247,
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
                        'sesak_napas' => 227,
                        'batuk' => 207,
                        'suhu_abnormal' => 187,
                        'bab_sering' => 167,
                        'kencing_sedikit' => 147,
                        'kulit_biru' => 127,
                        'aktivitas_lemah' => 107,
                        'hisapan_lemah' => 87,
                        'tidak_makan' => 67,
                        'paraf' => 56.5,
                    ];

                    $xMapBulanan = [
                        3 => 120,
                        4 => 142,
                        5 => 164,
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
                        'berbalik' => 161,
                        'kepala_tegak_90' => 170,
                        'kepala_stabil' => 180,
                        'genggam_mainan' => 190,
                        'raih_benda' => 199,
                        'amati_tangan' => 209,
                        'luas_pandang' => 219,
                        'arah_mata' => 228,
                        'suara_gembira' => 238,
                        'senyum_mainan' => 248,
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
                        'sesak_napas' => 227,
                        'batuk' => 207,
                        'suhu_abnormal' => 187,
                        'bab_sering' => 167,
                        'kencing_sedikit' => 147,
                        'kulit_biru' => 127,
                        'aktivitas_lemah' => 107,
                        'hisapan_lemah' => 87,
                        'tidak_makan' => 67,
                        'paraf' => 56.5,
                    ];

                    $xMapBulanan12 = [
                        6 => 292,
                        7 => 302,
                        8 => 312,
                        9 => 322,
                        10 => 333,
                        11 => 343,
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
                        'duduk_mandiri' => 188,
                        'tengkurap_dada' => 194,
                        'merangkak' => 200,
                        'pindah_benda' => 206,
                        'pungut_2_benda' => 212,
                        'pungut_kacang' => 218,
                        'bersuara_tanpa_arti' => 224,
                        'cari_mainan' => 231,
                        'tepuk_tangan' => 237,
                        'lempar_benda' => 243,
                        'makan_kue' => 249,
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
                        'belajar_berdiri' => 184,
                        'jalan_dituntun' => 191,
                        'ulur_tangan_raih' => 197,
                        'genggam_pensil' => 204,
                        'masuk_benda_mulut' => 210,
                        'tiru_bunyi' => 217,
                        'sebut_2_suku_kata' => 223,
                        'eksplorasi_sekitar' => 229,
                        'reaksi_panggilan' => 235,
                        'bermain_cilukba' => 242,
                        'kenal_keluarga' => 248,
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
                        'sesak_napas' => 227,
                        'batuk' => 207,
                        'suhu_abnormal' => 187,
                        'bab_sering' => 167,
                        'kencing_sedikit' => 147,
                        'kulit_pucat_biru' => 127,
                        'aktivitas_lemah' => 107,
                        'telinga_cairan' => 87,
                        'tidak_makan' => 67,
                        'paraf' => 56.5,
                    ];

                    $xMapBulanan24 = [
                        12 => 121,
                        13 => 141,
                        14 => 162,
                        15 => 213,
                        16 => 230,
                        17 => 246,
                        18 => 263,
                        19 => 279,
                        20 => 295,
                        21 => 311,
                        22 => 327,
                        23 => 343,
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
                        'bungkuk_pungut_mainan' => 203,
                        'jalan_mundur_5_langkah' => 210.5,
                        'panggil_papa_mama' => 217.5,
                        'tumpuk_2_kubus' => 224.5,
                        'masuk_kubus_kotak' => 232,
                        'tunjuk_tanpa_nangis' => 239.5,
                        'rasa_cemburu' => 248,
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
                        'berdiri_30_detik' => 184,
                        'jalan_tanpa_huyung' => 193,
                        'tumpuk_4_kubus' => 202,
                        'pungut_benda_kecil' => 211,
                        'gelinding_bola' => 220,
                        'sebut_3_6_kata' => 229,
                        'bantu_pekerjaan_rumah' => 238,
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
                        24 => 116,
                        25 => 126,
                        26 => 136.5,
                        27 => 146.5,
                        28 => 157,
                        29 => 167,
                        30 => 209,
                        31 => 217,
                        32 => 225,
                        33 => 233,
                        34 => 241,
                        35 => 249,
                        36 => 257,
                        37 => 265,
                        38 => 273,
                        39 => 281,
                        40 => 289,
                        41 => 297,
                        42 => 305,
                        43 => 313,
                        44 => 321,
                        45 => 329,
                        46 => 337,
                        47 => 345,
                    ];

                    $yMapBulanan72 = [
                        'sesak_napas' => 227,
                        'batuk' => 207,
                        'suhu_abnormal' => 187,
                        'bab_sering' => 167,
                        'kencing_sedikit' => 147,
                        'kulit_pucat_biru' => 127,
                        'aktivitas_lemah' => 107,
                        'telinga_cairan' => 87,
                        'tidak_makan' => 67,
                        'paraf' => 56.5,
                    ];

                    foreach ($bulanan72 as $r) {
                        $m = $r->bulan_ke;
                        if (!isset($xMapBulanan72[$m]))
                            continue;
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
                        48 => 116,
                        49 => 126,
                        50 => 136.5,
                        51 => 146.5,
                        52 => 157,
                        53 => 167,
                        54 => 209,
                        55 => 217,
                        56 => 225,
                        57 => 233,
                        58 => 241,
                        59 => 249,
                        60 => 257,
                        61 => 265,
                        62 => 273,
                        63 => 281,
                        64 => 289,
                        65 => 297,
                        66 => 305,
                        67 => 313,
                        68 => 321,
                        69 => 329,
                        70 => 337,
                        71 => 345,
                    ];

                    $yMapBulanan72 = [
                        'sesak_napas' => 227,
                        'batuk' => 207,
                        'suhu_abnormal' => 187,
                        'bab_sering' => 167,
                        'kencing_sedikit' => 147,
                        'kulit_pucat_biru' => 127,
                        'aktivitas_lemah' => 107,
                        'telinga_cairan' => 87,
                        'tidak_makan' => 67,
                        'paraf' => 56.5,
                    ];

                    foreach ($bulanan72 as $r) {
                        $m = $r->bulan_ke;
                        if (!isset($xMapBulanan72[$m]))
                            continue;
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

            // 25. PERAWATAN ANAK UMUR 2 - 3 TAHUN (Halaman 41 Sisi Kanan)
            if ($pageNo === 41) {
                $perk36 = $dataKia->perkembanganAnak36Bulan;
                if ($perk36) {
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetFont('ZapfDingbats', '', 10);

                    $xYa36 = 329;
                    $xTidak36 = 341;

                    $yPerk36 = [
                        'naik_tangga' => 175,
                        'tendang_bola' => 184,
                        'coret_kertas' => 193,
                        'bicara_2_kata' => 201,
                        'tunjuk_bagian_tubuh' => 211,
                        'sebut_nama_benda' => 220,
                        'pungut_mainan' => 229,
                        'makan_nasi_sendiri' => 238,
                        'lepas_pakaian' => 247,
                    ];

                    foreach ($yPerk36 as $field => $y) {
                        $val = $perk36->{$field};
                        if ($val === true) {
                            $pdf->Text($xYa36, $y, chr(51));
                        } elseif ($val === false) {
                            $pdf->Text($xTidak36, $y, chr(51));
                        }
                    }
                }
            }

            // 26. PERAWATAN ANAK UMUR 3 - 4 TAHUN (Halaman 42 Sisi Kanan)
            if ($pageNo === 42) {
                $perk48 = $dataKia->perkembanganAnak48Bulan;
                if ($perk48) {
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetFont('ZapfDingbats', '', 10);

                    $xYa48 = 329;
                    $xTidak48 = 341;

                    $yPerk48 = [
                        'berdiri_1_kaki_2_detik' => 166,
                        'lompat_kedua_kaki' => 173,
                        'kayuh_sepeda_roda_3' => 180,
                        'gambar_garis_lurus' => 187,
                        'tumpuk_8_kubus' => 194,
                        'kenal_2_4_warna' => 201,
                        'sebut_nama_umur_tempat' => 208,
                        'mengerti_arti_kata_posisi' => 215,
                        'dengar_cerita' => 222,
                        'cuci_tangan_sendiri' => 228,
                        'bermain_dengan_teman' => 235,
                        'pakai_sepatu_sendiri' => 242,
                        'pakai_celana_baju_sendiri' => 249,
                    ];

                    foreach ($yPerk48 as $field => $y) {
                        $val = $perk48->{$field};
                        if ($val === true) {
                            $pdf->Text($xYa48, $y, chr(51));
                        } elseif ($val === false) {
                            $pdf->Text($xTidak48, $y, chr(51));
                        }
                    }
                }
            }

            // 27. PERAWATAN ANAK UMUR 4 - 5 TAHUN & 5 - 6 TAHUN (Halaman 43 Sisi Kiri & Kanan)
            if ($pageNo === 43) {
                // Sisi Kiri: Umur 4 - 5 Tahun (60 Bulan)
                $perk60 = $dataKia->perkembanganAnak60Bulan;
                if ($perk60) {
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetFont('ZapfDingbats', '', 10);

                    $xYa60 = 154;
                    $xTidak60 = 166;

                    $yPerk60 = [
                        'berdiri_1_kaki_6_detik' => 163,
                        'lompat_1_kaki' => 169,
                        'menari' => 176,
                        'gambar_tanda_silang' => 182,
                        'gambar_lingkaran' => 189,
                        'gambar_orang_3_bagian' => 196,
                        'kancing_baju_boneka' => 202,
                        'sebut_nama_lengkap' => 209,
                        'senang_sebut_kata_baru' => 215,
                        'senang_bertanya' => 222,
                        'jawab_pertanyaan_kata_benar' => 229,
                        'bicara_mudah_dimengerti' => 235,
                        'banding_ukuran_bentuk' => 242,
                        'sebut_angka_hitung_jari' => 249,
                    ];

                    foreach ($yPerk60 as $field => $y) {
                        $val = $perk60->{$field};
                        if ($val === true) {
                            $pdf->Text($xYa60, $y, chr(51));
                        } elseif ($val === false) {
                            $pdf->Text($xTidak60, $y, chr(51));
                        }
                    }
                }

                // Sisi Kanan: Umur 5 - 6 Tahun (72 Bulan)
                $perk72 = $dataKia->perkembanganAnak72Bulan;
                if ($perk72) {
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetFont('ZapfDingbats', '', 10);

                    $xYa72 = 329;
                    $xTidak72 = 341;

                    $yPerk72 = [
                        'berjalan_lurus' => 184,
                        'berdiri_1_kaki_11_detik' => 189,
                        'gambar_6_bagian_orang_lengkap' => 195,
                        'tangkap_bola_kecil' => 200,
                        'gambar_segi_empat' => 206,
                        'mengerti_lawan_kata' => 211,
                        'mengerti_pembicaraan_7_kata' => 216,
                        'jawab_bahan_guna_benda' => 222,
                        'kenal_angka_hitung_5_10' => 227,
                        'kenal_warna_warni' => 233,
                        'ungkapkan_simpati' => 238,
                        'ikut_aturan_permainan' => 244,
                        'pakaian_sendiri_tanpa_bantu' => 249,
                    ];

                    foreach ($yPerk72 as $field => $y) {
                        $val = $perk72->{$field};
                        if ($val === true) {
                            $pdf->Text($xYa72, $y, chr(51));
                        } elseif ($val === false) {
                            $pdf->Text($xTidak72, $y, chr(51));
                        }
                    }
                }
            }

            // 28. KESEHATAN LINGKUNGAN (Halaman 47)
            if ($pageNo === 47) {
                $lingk = $dataKia->kesehatanLingkungan;
                if ($lingk) {
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetFont('ZapfDingbats', '', 10);

                    // Kolom Kiri (X = 30)
                    if ($lingk->bab_sembarangan) {
                        $pdf->Text(214, 67, chr(51));
                    }
                    if ($lingk->bab_jamban_sendiri) {
                        $pdf->Text(214, 71, chr(51));
                    }
                    if ($lingk->penampung_tangki_septik) {
                        $pdf->Text(214, 84, chr(51));
                    }
                    if ($lingk->penampung_cubluk) {
                        $pdf->Text(214, 97, chr(51));
                    }
                    if ($lingk->penampung_drainase) {
                        $pdf->Text(214, 101, chr(51));
                    }
                    if ($lingk->kloset_leher_angsa) {
                        $pdf->Text(214, 118, chr(51));
                    }

                    if ($lingk->ctps_sarana) {
                        $pdf->Text(214, 142, chr(51));
                    }
                    if ($lingk->ctps_air_mengalir) {
                        $pdf->Text(214, 146.5, chr(51));
                    }
                    if ($lingk->ctps_sabun) {
                        $pdf->Text(214, 151, chr(51));
                    }

                    if ($lingk->ctps_waktu_sebelum_makan) {
                        $pdf->Text(214, 173, chr(51));
                    }
                    if ($lingk->ctps_waktu_sebelum_mengolah) {
                        $pdf->Text(214, 177.5, chr(51));
                    }
                    if ($lingk->ctps_waktu_sebelum_menyusui) {
                        $pdf->Text(214, 186, chr(51));
                    }
                    if ($lingk->ctps_waktu_setelah_bab) {
                        $pdf->Text(214, 194.5, chr(51));
                    }

                    if ($lingk->sumber_air_pipa) {
                        $pdf->Text(214, 209.5, chr(51));
                    }
                    if ($lingk->sumber_air_kran) {
                        $pdf->Text(214, 214, chr(51));
                    }
                    if ($lingk->sumber_air_sumur_terlindungi) {
                        $pdf->Text(214, 218, chr(51));
                    }
                    if ($lingk->sumber_air_mata_air_terlindungi) {
                        $pdf->Text(214, 226.5, chr(51));
                    }
                    if ($lingk->sumber_air_sungai) {
                        $pdf->Text(214, 230.5, chr(51));
                    }
                    if ($lingk->sumber_air_danau) {
                        $pdf->Text(214, 235, chr(51));
                    }
                    if ($lingk->sumber_air_hujan) {
                        $pdf->Text(214, 239.5, chr(51));
                    }
                    if ($lingk->sumber_air_waduk) {
                        $pdf->Text(214, 243.5, chr(51));
                    }

                    // Kolom Kanan (X = 113)
                    if ($lingk->sumber_air_kolam) {
                        $pdf->Text(284, 57.5, chr(51));
                    }
                    if ($lingk->sumber_air_irigasi) {
                        $pdf->Text(284, 61.5, chr(51));
                    }

                    if ($lingk->kelola_air_rebus) {
                        $pdf->Text(284, 75, chr(51));
                    }
                    if ($lingk->kelola_air_endap_saring) {
                        $pdf->Text(284, 79, chr(51));
                    }
                    if ($lingk->kelola_air_wadah_tertutup) {
                        $pdf->Text(284, 87.5, chr(51));
                    }

                    if ($lingk->kelola_makanan_tertutup) {
                        $pdf->Text(284, 109.5, chr(51));
                    }
                    if ($lingk->kelola_makanan_jauh_bahan_berbahaya) {
                        $pdf->Text(284, 118, chr(51));
                    }
                    if ($lingk->kelola_makanan_baik_benar) {
                        $pdf->Text(284, 130.5, chr(51));
                    }

                    if ($lingk->sampah_tidak_berserakan) {
                        $pdf->Text(284, 167, chr(51));
                    }
                    if ($lingk->sampah_tempat_tertutup) {
                        $pdf->Text(284, 175.5, chr(51));
                    }
                    if ($lingk->sampah_dipilah) {
                        $pdf->Text(284, 184, chr(51));
                    }
                    if ($lingk->sampah_tidak_dibakar) {
                        $pdf->Text(284, 188, chr(51));
                    }
                    if ($lingk->sampah_tidak_dibuang_sembarangan) {
                        $pdf->Text(284, 192, chr(51));
                    }

                    if ($lingk->limbah_tidak_menggenang) {
                        $pdf->Text(284, 221, chr(51));
                    }
                    if ($lingk->limbah_saluran_tertutup) {
                        $pdf->Text(284, 225, chr(51));
                    }
                    if ($lingk->limbah_terhubung_resapan) {
                        $pdf->Text(284, 233.5, chr(51));
                    }
                }
            }

            // 29. PELAYANAN KESEHATAN IBU (Halaman 50)
            if ($pageNo === 50) {
                $pelayanan = $dataKia->pelayananKesehatanIbu;
                if ($pelayanan && $pelayanan->count() > 0) {
                    $pdf->SetTextColor(0, 0, 0);
                    $pdf->SetFont('Arial', '', 7);

                    // Estimasi koordinat X untuk Kunjungan 1-6
                    $xMap = [
                        1 => 66,
                        2 => 84,
                        3 => 102,
                        4 => 120,
                        5 => 138,
                        6 => 156,
                    ];

                    $yMap = [
                        'tanggal_periksa' => 87,
                        'tempat_periksa' => 94,
                        'berat_badan' => 111,
                        'tinggi_badan' => 120.5,
                        'lingkar_lengan_atas' => 129.5,
                        'tekanan_darah' => 137.5,
                        'tinggi_rahim' => 145,
                        'letak_denyut_jantung' => 154,
                        'status_imunisasi_tt' => 164,
                        'konseling' => 174,
                        'skrining_dokter' => 183,
                        'tablet_tambah_darah' => 191.5,
                        'tes_lab_hb' => 200,
                        'tes_golongan_darah' => 208,
                        'tes_lab_protein_urine' => 216,
                        'tes_lab_gula_darah' => 224.5,
                        'usg' => 232.5,
                        'tripel_eliminasi' => 241,
                        'tata_laksana_kasus' => 249,
                    ];

                    foreach ($pelayanan as $p) {
                        $x = $xMap[$p->kunjungan_ke] ?? null;
                        if (!$x)
                            continue;

                        if ($p->tanggal_periksa) {
                            $pdf->Text($x, $yMap['tanggal_periksa'], date('d/m/Y', strtotime($p->tanggal_periksa)));
                        }
                        if ($p->tempat_periksa) {
                            $pdf->SetXY($x, $yMap['tempat_periksa'] - 2);
                            $pdf->MultiCell(18, 3, $p->tempat_periksa, 0, 'L');
                        }
                        if ($p->berat_badan) {
                            $pdf->Text($x, $yMap['berat_badan'], $p->berat_badan);
                        }

                        if ($p->tinggi_badan && in_array($p->kunjungan_ke, [1])) {
                            $pdf->Text($x, $yMap['tinggi_badan'], $p->tinggi_badan);
                        }

                        if ($p->lingkar_lengan_atas) {
                            $pdf->Text($x, $yMap['lingkar_lengan_atas'], $p->lingkar_lengan_atas);
                        }
                        if ($p->tekanan_darah) {
                            $pdf->Text($x, $yMap['tekanan_darah'], $p->tekanan_darah);
                        }
                        if ($p->tinggi_rahim) {
                            $pdf->Text($x, $yMap['tinggi_rahim'], $p->tinggi_rahim);
                        }

                        $letakDenyut = trim(($p->letak_janin ?? '') . ' / ' . ($p->denyut_jantung_bayi ?? ''), ' /');
                        if ($letakDenyut) {
                            $pdf->Text($x, $yMap['letak_denyut_jantung'], substr($letakDenyut, 0, 15));
                        }

                        if ($p->status_imunisasi_tt) {
                            $pdf->Text($x, $yMap['status_imunisasi_tt'], substr($p->status_imunisasi_tt, 0, 15));
                        }
                        if ($p->konseling) {
                            $pdf->Text($x, $yMap['konseling'], substr($p->konseling, 0, 15));
                        }
                        if ($p->skrining_dokter) {
                            $pdf->Text($x, $yMap['skrining_dokter'], substr($p->skrining_dokter, 0, 15));
                        }
                        if ($p->tablet_tambah_darah) {
                            $pdf->Text($x, $yMap['tablet_tambah_darah'], $p->tablet_tambah_darah);
                        }

                        if ($p->tes_lab_hb && in_array($p->kunjungan_ke, [1, 4, 5])) {
                            $pdf->Text($x, $yMap['tes_lab_hb'], $p->tes_lab_hb);
                        }

                        if ($p->tes_golongan_darah && in_array($p->kunjungan_ke, [1])) {
                            $pdf->Text($x, $yMap['tes_golongan_darah'], $p->tes_golongan_darah);
                        }

                        if ($p->tes_lab_protein_urine && in_array($p->kunjungan_ke, [2, 3, 4, 5, 6])) {
                            $pdf->Text($x, $yMap['tes_lab_protein_urine'], $p->tes_lab_protein_urine);
                        }

                        if ($p->tes_lab_gula_darah && in_array($p->kunjungan_ke, [4, 5, 6])) {
                            $pdf->Text($x, $yMap['tes_lab_gula_darah'], $p->tes_lab_gula_darah);
                        }

                        if ($p->usg && in_array($p->kunjungan_ke, [1, 5])) {
                            $pdf->Text($x, $yMap['usg'], substr($p->usg, 0, 10));
                        }

                        if ($p->tripel_eliminasi) {
                            // Split value by comma, space, or slash
                            $tripelArr = preg_split('/[,\s\/]+/', $p->tripel_eliminasi);
                            $h = $tripelArr[0] ?? '';
                            $s = $tripelArr[1] ?? '';
                            $hepB = $tripelArr[2] ?? '';

                            $pdf->Text($x, $yMap['tripel_eliminasi'], substr($h, 0, 3));
                            $pdf->Text($x + 6, $yMap['tripel_eliminasi'], substr($s, 0, 3));
                            $pdf->Text($x + 12, $yMap['tripel_eliminasi'], substr($hepB, 0, 3));
                        }

                        if ($p->tata_laksana_kasus) {
                            $pdf->Text($x, $yMap['tata_laksana_kasus'], substr($p->tata_laksana_kasus, 0, 15));
                        }
                    }
                }
            }
            // 30. EVALUASI KESEHATAN IBU HAMIL (Halaman 51)
            if ($pageNo === 51) {
                $eval = $dataKia->evaluasiKesehatanIbu;
                if ($eval) {
                    $pdf->SetTextColor(0, 0, 0);

                    // Gunakan font ZapfDingbats untuk checkmark
                    $checkFont = 'ZapfDingbats';
                    $checkChar = chr(51);

                    // --- Informasi Dasar ---
                    $pdf->SetFont('Arial', '', 9);
                    if ($eval->nama_dokter) {
                        $pdf->Text(50, 46, substr($eval->nama_dokter, 0, 30));
                    }
                    if ($eval->tanggal_periksa) {
                        $pdf->Text(129, 46, date('d/m/Y', strtotime($eval->tanggal_periksa)));
                    }
                    if ($eval->fasilitas_kesehatan) {
                        $pdf->Text(59, 53, substr($eval->fasilitas_kesehatan, 0, 30));
                    }

                    // --- Kondisi Kesehatan Ibu (Sesuai Gambar User) ---
                    $pdf->SetFont('Arial', '', 8);
                    if ($eval->tb) { $pdf->Text(38.5, 71.3, $eval->tb); }
                    if ($eval->bb) { $pdf->Text(38.5, 77.5, $eval->bb); }
                    if ($eval->lila) { $pdf->Text(38.5, 83.5, $eval->lila); }

                    // Baris LiLa 4 Kolom (Di bawah Kurus, Normal, Gemuk, Obesitas)
                    if ($eval->lila_kurus) { $pdf->Text(51, 83, $eval->lila_kurus); }
                    if ($eval->lila_normal) { $pdf->Text(64, 83, $eval->lila_normal); }
                    if ($eval->lila_gemuk) { $pdf->Text(75, 83, $eval->lila_gemuk); }
                    if ($eval->lila_obesitas) { $pdf->Text(89, 83, $eval->lila_obesitas); }

                    // --- Status Imunisasi TD ---
                    $pdf->SetFont('ZapfDingbats', '', 10);
                    $checkChar = chr(51); // Karakter checklist

                    if ($eval->imunisasi_tt_1) {
                        $pdf->Text(92, 105, $checkChar);
                    }
                    if ($eval->imunisasi_tt_2) {
                        $pdf->Text(92, 111, $checkChar);
                    }
                    if ($eval->imunisasi_tt_3) {
                        $pdf->Text(92, 117, $checkChar);
                    }
                    if ($eval->imunisasi_tt_4) {
                        $pdf->Text(92, 123, $checkChar);
                    }
                    if ($eval->imunisasi_tt_5) {
                        $pdf->Text(92, 129, $checkChar);
                    }

                    $pdf->SetFont('Arial', '', 8);

                    // --- Riwayat Kesehatan Ibu Sekarang ---
                    $rkes = $eval->riwayat_kesehatan_ibu ?? [];
                    if (in_array('Alergi', $rkes)) {
                        $pdf->Ellipse(107.5, 70.5, 5, 3);
                    }
                    if (in_array('Autoimun', $rkes)) {
                        $pdf->Ellipse(110, 76.5, 7.8, 2.5);
                    }
                    if (in_array('Hepatitis B', $rkes)) {
                        $pdf->Ellipse(110.5, 82.5, 8, 2.5);
                    }
                    if (in_array('Jantung', $rkes)) {
                        $pdf->Ellipse(109.1, 89, 5.7, 2.5);
                    }
                    if (in_array('Sifilis', $rkes)) {
                        $pdf->Ellipse(107.5, 95, 5, 2.5);
                    }
                    if (in_array('Asma', $rkes)) {
                        $pdf->Ellipse(143, 70.5, 5.5, 2.5);
                    }
                    if (in_array('Diabetes', $rkes)) {
                        $pdf->Ellipse(144, 76.5, 6.8, 2.5);
                    }
                    if (in_array('Hipertensi', $rkes)) {
                        $pdf->Ellipse(145, 82.5, 8, 2.5);
                    }
                    if (in_array('Jiwa', $rkes)) {
                        $pdf->Ellipse(142, 88.5, 4, 3);
                    }
                    if (in_array('TB', $rkes)) {
                        $pdf->Ellipse(140.5, 95, 3, 3);
                    }
                    if ($eval->riwayat_kesehatan_ibu_lainnya) {
                        $pdf->Text(116, 102, substr($eval->riwayat_kesehatan_ibu_lainnya, 0, 20));
                    }

                    // --- Riwayat Perilaku Berisiko ---
                    $rper = $eval->riwayat_perilaku ?? [];
                    if (in_array('Aktivitas fisik kurang', $rper)) {
                        $pdf->Ellipse(116.5, 123, 14, 3);
                    }
                    if (in_array('Kosmetik berbahaya', $rper)) {
                        $pdf->Ellipse(114, 132.5, 12, 6.6);
                    }
                    if (in_array('Obat Teratogenik', $rper)) {
                        $pdf->Ellipse(115.5, 142, 13, 3);
                    }
                    if (in_array('Alkohol', $rper)) {
                        $pdf->Ellipse(143.5, 123, 6, 2.5);
                    }
                    if (in_array('Merokok', $rper)) {
                        $pdf->Ellipse(144, 133, 6.5, 2.7);
                    }
                    if (in_array('Pola makan berisiko', $rper)) {
                        $pdf->Ellipse(152, 142.5, 14, 3);
                    }
                    if ($eval->riwayat_perilaku_lainnya) {
                        $pdf->Text(115, 149.5, substr($eval->riwayat_perilaku_lainnya, 0, 20));
                    }

                    // --- Riwayat Penyakit Keluarga ---
                    $rkel = $eval->riwayat_penyakit_keluarga ?? [];
                    if (in_array('Alergi', $rkel)) {
                        $pdf->Ellipse(107.5, 166, 5, 3);
                    }
                    if (in_array('Autoimun', $rkel)) {
                        $pdf->Ellipse(110, 172, 7.8, 2.5);
                    }
                    if (in_array('Hepatitis B', $rkel)) {
                        $pdf->Ellipse(110.5, 178, 8, 2.5);
                    }
                    if (in_array('Jantung', $rkel)) {
                        $pdf->Ellipse(109.1, 184.5, 5.7, 2.5);
                    }
                    if (in_array('Sifilis', $rkel)) {
                        $pdf->Ellipse(107.5, 190.5, 5, 2.5);
                    }
                    if (in_array('Asma', $rkel)) {
                        $pdf->Ellipse(143, 166, 5.5, 2.5);
                    }
                    if (in_array('Diabetes', $rkel)) {
                        $pdf->Ellipse(144, 172, 6.8, 2.5);
                    }
                    if (in_array('Hipertensi', $rkel)) {
                        $pdf->Ellipse(145, 178, 8, 2.5);
                    }
                    if (in_array('Jiwa', $rkel)) {
                        $pdf->Ellipse(142, 184, 4, 3);
                    }
                    if (in_array('TB', $rkel)) {
                        $pdf->Ellipse(140.5, 190.5, 3, 3);
                    }
                    if ($eval->riwayat_penyakit_keluarga_lainnya) {
                        $pdf->Text(115, 197.5, substr($eval->riwayat_penyakit_keluarga_lainnya, 0, 20));
                    }

                    // --- Pemeriksaan Khusus ---
                    // Logika: DILINGKARI (Ellipse) opsi yang DIPILIH

                    if ($eval->inspeksi_porsio) {
                        if ($eval->inspeksi_porsio === 'Normal') {
                            $pdf->Ellipse(67, 149.5, 8, 3);
                        } // Lingkar 'Normal'
                        else {
                            $pdf->Ellipse(86.5, 149.5, 9, 3);
                        } // Lingkar 'Tidak normal'
                    }
                    if ($eval->inspeksi_uretra) {
                        if ($eval->inspeksi_uretra === 'Normal') {
                            $pdf->Ellipse(67, 155.5, 8, 3);
                        } else {
                            $pdf->Ellipse(86.5, 156, 9, 3);
                        }
                    }
                    if ($eval->inspeksi_vagina) {
                        if ($eval->inspeksi_vagina === 'Normal') {
                            $pdf->Ellipse(67, 161.8, 8, 3);
                        } else {
                            $pdf->Ellipse(86.5, 162, 9, 3);
                        }
                    }
                    if ($eval->inspeksi_vulva) {
                        if ($eval->inspeksi_vulva === 'Normal') {
                            $pdf->Ellipse(67, 168, 8, 3);
                        } else {
                            $pdf->Ellipse(86.5, 168, 9, 3);
                        }
                    }

                    if ($eval->inspeksi_fluksus) {
                        if ($eval->inspeksi_fluksus === '+') {
                            $pdf->Ellipse(64.5, 174, 2, 2);
                        } // Lingkar '+'
                        else {
                            $pdf->Ellipse(88.5, 174, 2, 2);
                        } // Lingkar '-'
                    }
                    if ($eval->inspeksi_fluor) {
                        if ($eval->inspeksi_fluor === '+') {
                            $pdf->Ellipse(64.5, 180, 2, 2);
                        } else {
                            $pdf->Ellipse(88.5, 180, 2, 2);
                        }
                    }

                    // --- Riwayat Kehamilan ---
                    $rk = $eval->riwayat_kehamilan ?? [];
                    $rkY = 233; // Estimasi posisi Y tabel bawah
                    foreach ($rk as $i => $k) {
                        if (empty($k['tahun']) && empty($k['bb']))
                            continue;
                        $y = $rkY + ($i * 7.5);
                        if (!empty($k['tahun'])) {
                            $pdf->Text(40, $y, $k['tahun']);
                        }
                        if (!empty($k['bb'])) {
                            $pdf->Text(55, $y, $k['bb']);
                        }
                        if (!empty($k['proses'])) {
                            $pdf->Text(64, $y, $k['proses']);
                        }
                        if (!empty($k['penolong'])) {
                            $pdf->Text(101, $y, $k['penolong']);
                        }
                        if (!empty($k['masalah'])) {
                            $pdf->Text(137, $y, $k['masalah']);
                        }
                    }
                }

                // --- SISI KANAN: PEMERIKSAAN DOKTER TRIMESTER 1 ---
                $pemeriksaan = $dataKia->pemeriksaanTrimester1;
                if ($pemeriksaan) {
                    $pdf->SetFont('Arial', '', 8);
                    if ($eval->nama_dokter) {
                        $pdf->Text(225, 51, substr($eval->nama_dokter, 0, 30));
                    }
                    if ($eval->tanggal_periksa) {
                        $pdf->Text(304, 51, date('d/m/Y', strtotime($eval->tanggal_periksa)));
                    }

                    // Keadaan Umum
                    if ($pemeriksaan->konjungtiva) {
                        if ($pemeriksaan->konjungtiva === 'Anemia') {
                            $pdf->Ellipse(247, 79.8, 7, 2.8);
                        } else {
                            $pdf->Ellipse(271, 79.7, 10, 3);
                        }
                    }
                    if ($pemeriksaan->keterangan_konjungtiva) {
                        $pdf->Text(285, 80.5, substr($pemeriksaan->keterangan_konjungtiva, 0, 20));
                    }

                    if ($pemeriksaan->sklera) {
                        if ($pemeriksaan->sklera === 'Ikterik') {
                            $pdf->Ellipse(247, 85.6, 6, 2.5);
                        } else {
                            $pdf->Ellipse(271, 85.5, 10, 3);
                        }
                    }
                    if ($pemeriksaan->keterangan_sklera) {
                        $pdf->Text(285, 86.3, substr($pemeriksaan->keterangan_sklera, 0, 20));
                    }

                    if ($pemeriksaan->kulit) {
                        if ($pemeriksaan->kulit === 'Normal') {
                            $pdf->Ellipse(247, 90.5, 7, 2.8);
                        } else {
                            $pdf->Ellipse(271, 91, 10, 3);
                        }
                    }
                    if ($pemeriksaan->keterangan_kulit) {
                        $pdf->Text(285, 91.5, substr($pemeriksaan->keterangan_kulit, 0, 20));
                    }
                    if ($pemeriksaan->leher) {
                        if ($pemeriksaan->leher === 'Normal') {
                            $pdf->Ellipse(247, 96, 7, 2.5);
                        } else {
                            $pdf->Ellipse(271, 96.5, 10, 3);
                        }
                    }
                    if ($pemeriksaan->keterangan_leher) {
                        $pdf->Text(285, 96.5, substr($pemeriksaan->keterangan_leher, 0, 20));
                    }

                    if ($pemeriksaan->gigi_mulut) {
                        if ($pemeriksaan->gigi_mulut === 'Normal') {
                            $pdf->Ellipse(247, 101, 7, 2.5);
                        } else {
                            $pdf->Ellipse(271, 101.5, 10, 3);
                        }
                    }
                    if ($pemeriksaan->keterangan_gigi_mulut) {
                        $pdf->Text(285, 101.5, substr($pemeriksaan->keterangan_gigi_mulut, 0, 20));
                    }

                    if ($pemeriksaan->tht) {
                        if ($pemeriksaan->tht === 'Normal') {
                            $pdf->Ellipse(247, 107, 7, 2.5);
                        } else {
                            $pdf->Ellipse(271, 107, 10, 3);
                        }
                    }
                    if ($pemeriksaan->keterangan_tht) {
                        $pdf->Text(285, 107.5, substr($pemeriksaan->keterangan_tht, 0, 20));
                    }

                    if ($pemeriksaan->dada_jantung) {
                        if ($pemeriksaan->dada_jantung === 'Normal') {
                            $pdf->Ellipse(247, 112.7, 7, 2.5);
                        } else {
                            $pdf->Ellipse(271, 113, 10, 3);
                        }
                    }
                    if ($pemeriksaan->keterangan_dada_jantung) {
                        $pdf->Text(285, 113.5, substr($pemeriksaan->keterangan_dada_jantung, 0, 20));
                    }

                    if ($pemeriksaan->dada_paru) {
                        if ($pemeriksaan->dada_paru === 'Normal') {
                            $pdf->Ellipse(247, 118.8, 7, 2.5);
                        } else {
                            $pdf->Ellipse(271, 119, 10, 3);
                        }
                    }
                    if ($pemeriksaan->keterangan_dada_paru) {
                        $pdf->Text(285, 119, substr($pemeriksaan->keterangan_dada_paru, 0, 20));
                    }

                    if ($pemeriksaan->perut) {
                        if ($pemeriksaan->perut === 'Normal') {
                            $pdf->Ellipse(247, 124.9, 7, 2.5);
                        } else {
                            $pdf->Ellipse(271, 124.5, 10, 3);
                        }
                    }
                    if ($pemeriksaan->keterangan_perut) {
                        $pdf->Text(285, 125, substr($pemeriksaan->keterangan_perut, 0, 20));
                    }

                    if ($pemeriksaan->tungkai) {
                        if ($pemeriksaan->tungkai === 'Normal') {
                            $pdf->Ellipse(247, 131, 7, 2.5);
                        } else {
                            $pdf->Ellipse(271, 130.5, 10, 3);
                        }
                    }
                    if ($pemeriksaan->keterangan_tungkai) {
                        $pdf->Text(285, 131.5, substr($pemeriksaan->keterangan_tungkai, 0, 20));
                    }

                    // USG Trimester 1
                    if ($pemeriksaan->hpht) {
                        $pdf->Text(215, 146, date('d/m/Y', strtotime($pemeriksaan->hpht)));
                    }
                    if ($pemeriksaan->keteraturan_haid) {
                        if ($pemeriksaan->keteraturan_haid === 'Teratur') {
                            $pdf->Ellipse(237, 153, 6, 2.5);
                        } else {
                            $pdf->Ellipse(253, 152, 10, 3.5);
                        }
                    }
                    if ($pemeriksaan->usia_kehamilan_hpht) {
                        $pdf->Text(288, 158.5, $pemeriksaan->usia_kehamilan_hpht);
                    }
                    if ($pemeriksaan->hpl_hpht) {
                        $pdf->Text(277, 165, date('d/m/Y', strtotime($pemeriksaan->hpl_hpht)));
                    }
                    if ($pemeriksaan->usia_kehamilan_usg) {
                        $pdf->Text(254, 171.5, $pemeriksaan->usia_kehamilan_usg);
                    }
                    if ($pemeriksaan->hpl_usg) {
                        $pdf->Text(238, 178, date('d/m/Y', strtotime($pemeriksaan->hpl_usg)));
                    }

                    // Table USG
                    if ($pemeriksaan->jumlah_gs) {
                        if ($pemeriksaan->jumlah_gs === 'Tunggal') {
                            $pdf->Ellipse(242.5, 185, 6, 2.5);
                        } else {
                            $pdf->Ellipse(254, 185, 6, 2.5);
                        }
                    }
                    if ($pemeriksaan->diameter_gs_cm) {
                        $pdf->Text(240, 191.5, $pemeriksaan->diameter_gs_cm);
                    }
                    if ($pemeriksaan->diameter_gs_minggu) {
                        $pdf->Text(297, 191.5, $pemeriksaan->diameter_gs_minggu);
                    }
                    if ($pemeriksaan->diameter_gs_hari) {
                        $pdf->Text(319, 191.5, $pemeriksaan->diameter_gs_hari);
                    }

                    if ($pemeriksaan->jumlah_bayi) {
                        if ($pemeriksaan->jumlah_bayi === 'Tunggal') {
                            $pdf->Ellipse(242.5, 197.5, 6, 2.5);
                        } else {
                            $pdf->Ellipse(254, 198, 6, 2.5);
                        }
                    }
                    if ($pemeriksaan->crl_cm) {
                        $pdf->Text(240, 203.5, $pemeriksaan->crl_cm);
                    }
                    if ($pemeriksaan->crl_minggu) {
                        $pdf->Text(297, 203.5, $pemeriksaan->crl_minggu);
                    }
                    if ($pemeriksaan->crl_hari) {
                        $pdf->Text(319, 203.5, $pemeriksaan->crl_hari);
                    }

                    if ($pemeriksaan->letak_produk_kehamilan) {
                        if ($pemeriksaan->letak_produk_kehamilan === 'Intrauterin') {
                            $pdf->Ellipse(242, 211, 8, 3);
                        } elseif ($pemeriksaan->letak_produk_kehamilan === 'Ekstrauterin') {
                            $pdf->Ellipse(258, 211.5, 8, 3);
                        } else {
                            $pdf->Ellipse(280, 211.5, 15, 3);
                        }
                    }

                    if ($pemeriksaan->pulsasi_jantung) {
                        if ($pemeriksaan->pulsasi_jantung === 'Tampak') {
                            $pdf->Ellipse(242, 219, 6, 2.5);
                        } else {
                            $pdf->Ellipse(257, 219, 9, 3);
                        }
                    }

                    if ($pemeriksaan->kecurigaan_temuan_abnormal) {
                        if ($pemeriksaan->kecurigaan_temuan_abnormal === 'Ya') {
                            $pdf->Ellipse(239, 227, 3, 3);
                        } else {
                            $pdf->Ellipse(245, 227, 4, 2.5);
                        }
                    }
                    if ($pemeriksaan->kecurigaan_temuan_abnormal_sebutkan) {
                        $pdf->Text(264, 226.5, substr($pemeriksaan->kecurigaan_temuan_abnormal_sebutkan, 0, 20));
                    }
                }
            }

            // 31. HASIL PEMERIKSAAN DOKTER PADA TRIMESTER 1 / USG (Halaman 52-53 PDF)
            if ($pageNo === 52) {
                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetFont('Arial', '', 9);

                // --- SISI KIRI (Pemeriksaan Trimester 1) ---
                $pemeriksaan = $dataKia->pemeriksaanTrimester1;
                if ($pemeriksaan) {
                    // Gambar USG
                    if ($pemeriksaan->gambar_usg) {
                        // Bersihkan path dari prefix 'storage/' atau 'public/' jika ada
                        $cleanPath = str_replace(['storage/', 'public/', 'storage\\', 'public\\'], '', $pemeriksaan->gambar_usg);
                        
                        // Cek di storage/app/public (Disk Public)
                        $imagePath = storage_path('app/public/' . $cleanPath);
                        
                        // Fallback: Cek jika tersimpan di private (disk local default yang salah sebelumnya)
                        if (!file_exists($imagePath)) {
                            $imagePath = storage_path('app/private/public/' . $cleanPath);
                        }
                        
                        if (file_exists($imagePath)) {
                            // X=25, Y=25, Lebar=110, Tinggi=90
                            $pdf->Image($imagePath, 44, 52, 110, 90);
                        }
                    }

                    // Pemeriksaan Laboratorium
                    if ($pemeriksaan->tgl_periksa_lab) {
                        $tgl = strtotime($pemeriksaan->tgl_periksa_lab);
                        $pdf->Text(142, 152.5, date('d', $tgl)); // Hari
                        $pdf->Text(151.5, 152.5, date('m', $tgl)); // Bulan
                        $pdf->Text(166, 152.5, date('y', $tgl)); // Tahun
                    }
                    if ($pemeriksaan->lab_hemoglobin) {
                        $pdf->Text(71, 166.5, $pemeriksaan->lab_hemoglobin);
                    }
                    if ($pemeriksaan->lab_gol_darah) {
                        $pdf->Text(71, 172.5, $pemeriksaan->lab_gol_darah);
                    }
                    if ($pemeriksaan->lab_gula_darah) {
                        $pdf->Text(71, 178.5, $pemeriksaan->lab_gula_darah);
                    }

                    $pdf->SetDrawColor(0, 0, 0);
                    $pdf->SetLineWidth(0.3);

                    // Tripel Eliminasi (Lingkari yang DIPILIH)
                    if ($pemeriksaan->lab_tripel_h) {
                        if ($pemeriksaan->lab_tripel_h === 'Reaktif') {
                            $pdf->Ellipse(75.5, 188.5, 6, 3);
                        } else {
                            $pdf->Ellipse(93, 188.5, 9, 3);
                        }
                    }
                    if ($pemeriksaan->lab_tripel_s) {
                        if ($pemeriksaan->lab_tripel_s === 'Reaktif') {
                            $pdf->Ellipse(75.5, 194.5, 6, 3);
                        } else {
                            $pdf->Ellipse(93, 194.5, 9, 3);
                        }
                    }
                    if ($pemeriksaan->lab_tripel_hep_b) {
                        if ($pemeriksaan->lab_tripel_hep_b === 'Reaktif') {
                            $pdf->Ellipse(75.5, 200.5, 6, 3);
                        } else {
                            $pdf->Ellipse(93, 200.5, 9, 3);
                        }
                    }

                    // Skrining Kesehatan Jiwa
                    if ($pemeriksaan->tgl_skrining_jiwa) {
                        $tglS = strtotime($pemeriksaan->tgl_skrining_jiwa);
                        $pdf->Text(142, 209.5, date('d', $tglS)); // Hari
                        $pdf->Text(151.5, 209.5, date('m', $tglS)); // Bulan
                        $pdf->Text(166, 209.5, date('y', $tglS)); // Tahun
                    }
                    if ($pemeriksaan->skrining_jiwa) {
                        if ($pemeriksaan->skrining_jiwa === 'Ya') {
                            $pdf->Ellipse(118, 215, 6, 3);
                        } else {
                            $pdf->Ellipse(153, 215, 8, 3);
                        }
                    }
                    if ($pemeriksaan->tindak_lanjut_jiwa) {
                        if ($pemeriksaan->tindak_lanjut_jiwa === 'Edukasi') {
                            $pdf->Ellipse(118, 221, 10, 3);
                        } else {
                            $pdf->Ellipse(153, 221.5, 10, 3);
                        }
                    }
                    if ($pemeriksaan->rujukan_jiwa) {
                        if ($pemeriksaan->rujukan_jiwa === 'Ya') {
                            $pdf->Ellipse(118, 227, 6, 3);
                        } else {
                            $pdf->Ellipse(153, 228, 8, 3);
                        }
                    }

                    if ($pemeriksaan->kesimpulan) {
                        $pdf->Text(47, 239, $pemeriksaan->kesimpulan);
                    }
                    if ($pemeriksaan->rekomendasi) {
                        $pdf->Text(50, 245.5, $pemeriksaan->rekomendasi);
                    }
                }

                // --- SISI KANAN (Catatan Pelayanan) ---
                $catatanList = $dataKia->catatanPelayananTrimester1;
                if ($catatanList && $catatanList->count() > 0) {
                    $pdf->SetFont('Arial', '', 9);
                    $currentY = 61; // Y awal untuk tabel catatan
                    $maxY = 249; // Batas bawah

                    foreach ($catatanList as $cat) {
                        if ($currentY > $maxY - 20) {
                            break; // Jika melebihi batas bawah, berhenti (karena keterbatasan 1 halaman PDF)
                        }

                        $pdf->SetXY(206, $currentY);
                        $tglPeriksa = $cat->tanggal_periksa ? date('d/m/Y', strtotime($cat->tanggal_periksa)) : '';
                        $pdf->MultiCell(25, 5, $tglPeriksa, 0, 'C');

                        $startY = $pdf->GetY();
                        $pdf->SetXY(236, $currentY);
                        $pdf->MultiCell(72, 5, $cat->catatan, 0, 'L');
                        $endYCatatan = $pdf->GetY();

                        $pdf->SetXY(315, $currentY);
                        $tglKembali = $cat->tanggal_kembali ? date('d/m/Y', strtotime($cat->tanggal_kembali)) : '';
                        $pdf->MultiCell(25, 5, $tglKembali, 0, 'C');
                        $endYKembali = $pdf->GetY();

                        // Ambil Y terbesar sebagai awal row berikutnya
                        $currentY = max($startY, $endYCatatan, $endYKembali) + 5;

                        // Garis pemisah antar baris
                        $pdf->Line(204, $currentY - 2, 348, $currentY - 2);
                    }
                }
            }

            // 32. HASIL PEMERIKSAAN DOKTER PADA TRIMESTER 2 / SKRINING (Halaman 53 PDF)
            if ($pageNo === 53) {
                $pdf->SetTextColor(0, 0, 0);
                
                // --- SISI KIRI (Skrining) ---
                $pemeriksaan2 = $dataKia->pemeriksaanTrimester2;
                if ($pemeriksaan2) {
                    $pdf->SetFont('Arial', '', 9);
                    $skrining = $pemeriksaan2->skrining_preeklampsia ?? [];
                    
                    // Koordinat Checklist Preeklampsia (ZapfDingbats chr(51))
                    $pdf->SetFont('ZapfDingbats', '', 10);
                    $check = chr(51);

                    // Row mapping untuk kriteria (Estimasi Y berdasarkan gambar)
                    $criteriaY = [
                        'multipara_pasangan_baru' => 91,
                        'teknologi_reproduksi' => 97,
                        'umur_35' => 104,
                        'nullipara' => 109,
                        'jarak_10' => 114,
                        'riwayat_ibu_saudara' => 119,
                        'obesitas' => 125,
                        'riwayat_preeklampsia_sebelumnya' => 130,
                        'kehamilan_multipel' => 135,
                        'diabetes' => 140,
                        'hipertensi' => 145,
                        'ginjal' => 150,
                        'autoimun' => 155,
                        'aps' => 160,
                        'map_90' => 171,
                        'proteinuria' => 177,
                    ];

                    $countRisikoSedang = 0;
                    $hasRisikoTinggi = false;

                    foreach ($skrining as $key => $val) {
                        if ($val === 'Risiko Sedang' && isset($criteriaY[$key])) {
                            // Risiko Sedang (Kolom Orange) tampil di baris masing-masing
                            $pdf->Text(123, $criteriaY[$key], $check);
                            $countRisikoSedang++;
                        } elseif ($val === 'Risiko Tinggi' && isset($criteriaY[$key])) {
                            // Risiko Tinggi (Kolom Merah) tampil di baris masing-masing (tidak di grup)
                            $pdf->Text(155, $criteriaY[$key], $check);
                            $hasRisikoTinggi = true;
                        }
                    }

                    $pdf->SetFont('Arial', '', 9);
                    if ($pemeriksaan2->kesimpulan_preeklampsia) {
                        $pdf->Text(48, 205.5, $pemeriksaan2->kesimpulan_preeklampsia); // Samakan dengan p52
                    }

                    // Skrining Diabetes
                    if ($pemeriksaan2->lab_gula_darah_puasa) {
                        $pdf->Text(74, 225, $pemeriksaan2->lab_gula_darah_puasa);
                    }
                    if ($pemeriksaan2->lab_gula_darah_2jam) {
                        $pdf->Text(74, 230.5, $pemeriksaan2->lab_gula_darah_2jam);
                    }
                    if ($pemeriksaan2->tindak_lanjut_puasa) {
                        $pdf->Text(106, 225, $pemeriksaan2->tindak_lanjut_puasa);
                    }
                    if ($pemeriksaan2->tindak_lanjut_2jam) {
                        $pdf->Text(106, 230.5, $pemeriksaan2->tindak_lanjut_2jam);
                    }
                    if ($pemeriksaan2->tgl_periksa_diabetes) {
                        $pdf->Text(54, 237, date('d/m/Y', strtotime($pemeriksaan2->tgl_periksa_diabetes)));
                    }
                    if ($pemeriksaan2->nama_dokter_diabetes) {
                        $pdf->Text(152, 250, $pemeriksaan2->nama_dokter_diabetes);
                    }
                }

                // --- SISI KANAN (Catatan Pelayanan Trimester 2) ---
                $catatanList2 = $dataKia->catatanPelayananTrimester2;
                if ($catatanList2 && $catatanList2->count() > 0) {
                    $pdf->SetFont('Arial', '', 9);
                    $currentY = 61; // Samakan koordinat dengan Trimester 1 di p52
                    $maxY = 249;

                    foreach ($catatanList2 as $cat) {
                        if ($currentY > $maxY - 20) break;

                        $startY = $currentY;
                        $pdf->SetXY(206, $currentY);
                        $tglPeriksa = $cat->tanggal_periksa ? date('d/m/Y', strtotime($cat->tanggal_periksa)) : '';
                        $pdf->MultiCell(25, 5, $tglPeriksa, 0, 'C');

                        $pdf->SetXY(236, $currentY);
                        $pdf->MultiCell(72, 5, $cat->catatan, 0, 'L');
                        $endYCatatan = $pdf->GetY();

                        $pdf->SetXY(315, $currentY);
                        $tglKembali = $cat->tanggal_kembali ? date('d/m/Y', strtotime($cat->tanggal_kembali)) : '';
                        $pdf->MultiCell(25, 5, $tglKembali, 0, 'C');
                        $endYKembali = $pdf->GetY();

                        $currentY = max($startY + 5, $endYCatatan, $endYKembali) + 5;
                        $pdf->Line(204, $currentY - 2, 348, $currentY - 2);
                    }
                }
            }
        }

        return $pdf->Output('S');
    }
}
