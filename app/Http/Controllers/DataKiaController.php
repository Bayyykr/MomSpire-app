<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DataKia;

class DataKiaController extends Controller
{
    public function wizard()
    {
        abort_unless(auth()->check() && auth()->user()->role === 'pengguna', 403);

        $dataKia = DataKia::with(['ibu', 'suami', 'anak', 'layanan', 'riwayat'])
            ->firstOrCreate(['user_id' => auth()->id()]);

        return view('pengguna.kia-wizard', compact('dataKia'));
    }

    public function saveWizard(Request $request)
    {
        abort_unless(auth()->check() && auth()->user()->role === 'pengguna', 403);
        $dataKia = DataKia::firstOrCreate(['user_id' => auth()->id()]);

        // Helper to convert empty strings to null
        $clean = function ($val) {
            return $val === '' ? null : $val; };

        // 1. Core Data
        $dataKia->update([
            'faskes_dikeluarkan' => $clean($request->faskes_dikeluarkan),
            'tanggal_dikeluarkan' => $clean($request->tanggal_dikeluarkan),
            'kab_kota_dikeluarkan' => $clean($request->kab_kota_dikeluarkan),
            'provinsi_dikeluarkan' => $clean($request->provinsi_dikeluarkan),
        ]);

        // 2. Identitas Ibu
        $dataKia->ibu()->updateOrCreate([], [
            'nama' => $clean($request->nama_ibu),
            'nik' => $clean($request->nik),
            'no_jkn' => $clean($request->no_jkn_ibu),
            'faskes_tk1' => $clean($request->faskes_tk1_ibu),
            'faskes_rujukan' => $clean($request->faskes_rujukan_ibu),
            'tempat_lahir' => $clean($request->tempat_lahir),
            'tanggal_lahir' => $clean($request->tanggal_lahir),
            'pendidikan' => $clean($request->pendidikan),
            'pekerjaan' => $clean($request->pekerjaan),
            'alamat' => $clean($request->alamat),
            'telepon' => $clean($request->telepon_ibu),
            'golongan_darah' => $clean($request->golongan_darah),
        ]);

        // 3. Identitas Suami
        $dataKia->suami()->updateOrCreate([], [
            'nama' => $clean($request->nama_suami),
            'nik' => $clean($request->nik_suami),
            'no_jkn' => $clean($request->no_jkn_suami),
            'faskes_tk1' => $clean($request->faskes_tk1_suami),
            'faskes_rujukan' => $clean($request->faskes_rujukan_suami),
            'tempat_lahir' => $clean($request->tempat_lahir_suami),
            'tanggal_lahir' => $clean($request->tanggal_lahir_suami),
            'pendidikan' => $clean($request->pendidikan_suami),
            'pekerjaan' => $clean($request->pekerjaan_suami),
            'alamat' => $clean($request->alamat_rumah_suami),
            'telepon' => $clean($request->telepon_suami),
            'golongan_darah' => $clean($request->golongan_darah_suami),
        ]);

        // 4. Identitas Anak
        $dataKia->anak()->updateOrCreate([], [
            'nama' => $clean($request->nama_anak),
            'nik' => $clean($request->nik_anak),
            'no_jkn' => $clean($request->no_jkn_anak),
            'faskes_tk1' => $clean($request->faskes_tk1_anak),
            'faskes_rujukan' => $clean($request->faskes_rujukan_anak),
            'tempat_lahir' => $clean($request->tempat_lahir_anak),
            'tanggal_lahir' => $clean($request->tanggal_lahir_anak),
            'anak_ke' => $clean($request->anak_ke),
            'no_akta_kelahiran' => $clean($request->no_akta_kelahiran_anak),
            'telepon' => $clean($request->telepon_anak),
            'alamat' => $clean($request->alamat_anak),
            'golongan_darah' => $clean($request->golongan_darah_anak),
        ]);

        // 5. Layanan & Pembiayaan
        $dataKia->layanan()->updateOrCreate([], [
            // Ibu
            'puskesmas_domisili' => $clean($request->puskesmas_domisili),
            'no_reg_kohort_ibu' => $clean($request->no_reg_kohort_ibu),
            'no_reg_kohort_bayi' => $clean($request->no_reg_kohort_bayi),
            'no_reg_kohort_balita' => $clean($request->no_reg_kohort_balita),
            'no_catatan_medik_rs' => $clean($request->no_catatan_medik_rs),
            'asuransi_lain' => $clean($request->asuransi_lain),
            'no_asuransi_lain' => $clean($request->no_asuransi_lain),
            'tanggal_berlaku_asuransi_lain' => $clean($request->tanggal_berlaku_asuransi_lain),

            // Suami
            'asuransi_suami' => $clean($request->asuransi_suami),
            'no_asuransi_suami' => $clean($request->no_asuransi_suami),
            'tanggal_berlaku_asuransi_suami' => $clean($request->tanggal_berlaku_asuransi_suami),
            'puskesmas_domisili_suami' => $clean($request->puskesmas_domisili_suami),
            'no_catatan_medik_rs_suami' => $clean($request->no_catatan_medik_rs_suami),

            // Anak
            'asuransi_anak' => $clean($request->asuransi_anak),
            'no_asuransi_anak' => $clean($request->no_asuransi_anak),
            'tanggal_berlaku_asuransi_anak' => $clean($request->tanggal_berlaku_asuransi_anak),
            'puskesmas_domisili_anak' => $clean($request->puskesmas_domisili_anak),
            'no_catatan_medik_rs_anak' => $clean($request->no_catatan_medik_rs_anak),
        ]);

        // 6. Riwayat Kesehatan (Nakes fields excluded to prevent overwrite)
        $dataKia->riwayat()->updateOrCreate([], [
            'hpht' => $clean($request->hpht),
            'htp' => $clean($request->htp),
            // Existing fields from old migration that I kept in riwayat
            'lingkar_lengan_atas' => $clean($request->lingkar_lengan_atas),
            'tinggi_badan' => $clean($request->tinggi_badan),
            'trimester_1' => $clean($request->trimester_1),
            'trimester_2' => $clean($request->trimester_2),
            'trimester_3' => $clean($request->trimester_3),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil disimpan secara terpisah.'
        ]);
    }

    public function exportPdf($id)
    {
        $user = auth()->user();
        abort_unless($user, 403);

        $dataKia = DataKia::with(['ibu', 'suami', 'anak', 'layanan', 'riwayat', 'ttdTrackings', 'absenKelasIbuHamils', 'persiapanMelahirkan', 'prosesMelahirkan'])
            ->findOrFail($id);

        // Pastikan relasi ttdTrackings, pemantauanMingguans, absenKelasIbuHamils, persiapanMelahirkan, dan prosesMelahirkan selalu segar
        $dataKia->load(['ttdTrackings', 'pemantauanMingguans', 'absenKelasIbuHamils', 'persiapanMelahirkan', 'prosesMelahirkan']);

        if ($user->role === 'pengguna') {
            abort_unless($dataKia->user_id === $user->id, 403);
        } else {
            abort_unless(in_array($user->role, ['admin', 'bidan', 'dokter']), 403);
        }

        $originalPath = resource_path('views/buku/Buku KIA (Permenkes).pdf');
        $convertedPath = storage_path('app/buku_kia_converted.pdf');
        $scriptPath = base_path('scripts/convert_pdf_fpdi.py');

        if (!file_exists($originalPath)) {
            abort(404, 'File template PDF tidak ditemukan.');
        }

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

            if ($pageNo === 1) {
                // COVER MAPPING
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

            if ($pageNo === 2) {
                // IDENTITAS TABLE MAPPING
                $ibu = $dataKia->ibu;
                $suami = $dataKia->suami;
                $anak = $dataKia->anak;

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
                $layanan = $dataKia->layanan;
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

            if ($pageNo === 5) {
                // TTD TRACKING MAPPING (Page 5 - Vertical Format)
                $trackings = $dataKia->ttdTrackings->keyBy('bulan_ke');

                // PEMETAAN MANUAL KOORDINAT X (Bisa Anda ubah satu-persatu secara bebas jika ada kolom bulan yang kurang pas!)
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

                // PEMETAAN MANUAL KOORDINAT Y (Bisa Anda ubah satu-persatu secara bebas jika ada baris yang kurang pas!)
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

                foreach (range(1, 10) as $m) {
                    $tracking = $trackings->get($m);
                    if ($tracking) {
                        $visualX = $xMap[$m] ?? 257.5;

                        // 1. Plot Checkmarks (Hari 1-31)
                        $pdf->SetFont('ZapfDingbats', '', 9);
                        for ($i = 1; $i <= 31; $i++) {
                            if ($tracking->{"h$i"}) {
                                $visualY = $yMap[$i] ?? 211.0;
                                // Cetak tepat di tengah kotak dengan mengimbangi efek rotasi (-4.5)
                                $pdf->RotatedText($visualX, $visualY - 4.5, chr(51), 90);
                            }
                        }

                        // 2. Usia Kehamilan (Sesuai koordinat pas Anda - JANGAN DIUBAH)
                        $pdf->SetFont('Arial', '', 9);
                        $pdf->RotatedText($visualX, 218, $tracking->usia_kehamilan ?? '', 90);

                        // 3. Bulan / Tahun (Sesuai koordinat pas Anda - JANGAN DIUBAH)
                        $pdf->SetFont('Arial', '', 9);
                        $pdf->RotatedText($visualX, 236.5, $tracking->bulan_tahun ?? '', 90);
                }
            }
        }

        if ($pageNo === 7) {
            // LEMBAR PEMANTAUAN TRIMESTER I & II (Page 7 - Landscape Format)
            $pemantauans = $dataKia->pemantauanMingguans->keyBy('minggu_ke');

            // PEMETAAN MANUAL KOORDINAT X UNTUK 11 KOLOM (Silakan sesuaikan jika ada yang bergeser!)
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

            // PEMETAAN MANUAL KOORDINAT Y UNTUK MINGGU 4 SAMPAI 24
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
                        // Plot setiap indikator jika dicentang
                        foreach ($xMap as $field => $visualX) {
                            // Untuk minggu 4-24, kolom gerakan_bayi diabaikan karena abu-abu di buku KIA
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

        if ($pageNo === 8) {
            // LEMBAR PEMANTAUAN TRIMESTER II & III (Page 8 - Landscape Format)
            $pemantauans = $dataKia->pemantauanMingguans->keyBy('minggu_ke');

            // PEMETAAN MANUAL KOORDINAT X UNTUK 11 KOLOM (Silakan sesuaikan jika ada yang bergeser!)
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

            // PEMETAAN MANUAL KOORDINAT Y UNTUK MINGGU 25 SAMPAI 42
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
                        // Plot setiap indikator jika dicentang
                        foreach ($xMap as $field => $visualX) {
                            if ($p->{$field}) {
                                $pdf->Text($visualX, $visualY, chr(51));
                            }
                        }
                    }
                }
            }
        }

        if ($pageNo === 9) {
            // ABSENSI KEHADIRAN KELAS IBU HAMIL (Page 9 - Landscape Format - Right Page)
            $absensi = $dataKia->absenKelasIbuHamils->keyBy('kehadiran_ke');

            // PEMETAAN MANUAL KOORDINAT X UNTUK KOLOM (Silakan sesuaikan!)
            $xMap = [
                'tanggal'    => 222, // Kolom Tanggal
                'kader_info' => 303, // Kolom Tanggal, Nama & Paraf Kader
            ];

            // PEMETAAN MANUAL KOORDINAT Y UNTUK BARIS 1 SAMPAI 9
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

            foreach (range(1, 9) as $i) {
                $item = $absensi->get($i);
                if ($item) {
                    $visualY = $yMap[$i] ?? null;
                    if ($visualY) {
                        if (!empty($item->tanggal)) {
                            $pdf->Text($xMap['tanggal'], $visualY, $item->tanggal);
                        }
                        if (!empty($item->kader_info)) {
                            $pdf->Text($xMap['kader_info'], $visualY, $item->kader_info);
                        }
                    }
                }
            }
        }

        if ($pageNo === 11) {
            // PERSIAPAN MELAHIRKAN (Page 11 - Landscape Format - Left Page)
            $p = $dataKia->persiapanMelahirkan;
            if ($p) {
                // PEMETAAN MANUAL KOORDINAT X UNTUK CHECKBOX (Silakan sesuaikan!)
                $checkboxX = [
                    'col1' => 32, // Kolom Kiri Checkbox
                    'col2' => 102, // Kolom Kanan Checkbox
                ];

                // PEMETAAN MANUAL KOORDINAT Y UNTUK BARIS 1 SAMPAI 5 (Silakan sesuaikan!)
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

                // Gambar Isian Teks (Tanggal, Bulan, Tahun, Metode KB)
                $pdf->SetFont('Arial', '', 9);
                if (!empty($p->hpl_tanggal)) {
                    $pdf->Text(49, $rowY[1] + 9, $p->hpl_tanggal); // Baris HPL bawah dikit atau sebaris
                }
                if (!empty($p->hpl_bulan)) {
                    $pdf->Text(68.3, $rowY[1] + 9, $p->hpl_bulan);
                }
                if (!empty($p->hpl_tahun)) {
                    $pdf->Text(90, $rowY[1] + 9, $p->hpl_tahun);
                }
                if (!empty($p->metode_kb)) {
                    $pdf->Text(135, $rowY[5] + 4.5, $p->metode_kb);
                }
            }
        }

        if ($pageNo === 14) {
            // PROSES MELAHIRKAN (Page 14 - Landscape Format - Left Page)
            $p = $dataKia->prosesMelahirkan;
            if ($p) {
                // KOORDINAT INDIVIDUAL UNTUK SETIAP CHECKBOX (Dapat diubah sendiri-sendiri!)
                $coords = [
                    // Kolom Kiri
                    'mulas_teratur'         => ['x' => 32.0, 'y' => 177.7],
                    'durasi_persalinan'     => ['x' => 32.0, 'y' => 195.7],
                    'hak_pendamping'        => ['x' => 32.0, 'y' => 223.5],
                    'hak_posisi'            => ['x' => 32.0, 'y' => 237.5],

                    // Kolom Kanan
                    'ingin_bab'             => ['x' => 102.0, 'y' => 177.7],
                    'kurangi_sakit'         => ['x' => 102.0, 'y' => 191.2],
                    'inisiasi_menyusu_dini' => ['x' => 102.0, 'y' => 209.5],
                ];

                $pdf->SetTextColor(0, 0, 0);
                $pdf->SetFont('ZapfDingbats', '', 10);

                foreach ($coords as $field => $coord) {
                    if ($p->$field) {
                        $pdf->Text($coord['x'], $coord['y'], $coord['char'] ?? chr(51));
                    }
                }
            }
        }
    }

        return response($pdf->Output('S'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="Buku_KIA_' . ($dataKia->ibu->nama ?? 'Identitas') . '.pdf"',
        ]);
    }

    public function indexNakes()
    {
        $role = auth()->user()->role;
        $dataKias = DataKia::with(['ibu'])->latest()->get();

        return view('nakes.kia-index', compact('dataKias', 'role'));
    }

    public function editRiwayat($id)
    {
        $role = auth()->user()->role;
        $dataKia = DataKia::with(['ibu', 'riwayat'])->findOrFail($id);

        return view('nakes.kia-edit-riwayat', compact('dataKia', 'role'));
    }

    public function saveRiwayat(Request $request, $id)
    {
        $dataKia = DataKia::findOrFail($id);

        $clean = function ($val) {
            return $val === '' ? null : $val;
        };

        $dataKia->riwayat()->updateOrCreate(
            ['data_kia_id' => $dataKia->id],
            [
                'usia_ibu' => $clean($request->usia_ibu),
                'kehamilan_ke' => $clean($request->kehamilan_ke),
                'jumlah_anak_hidup' => $clean($request->jumlah_anak_lahir_hidup),
                'riwayat_keguguran' => $clean($request->riwayat_keguguran),
                'riwayat_penyakit_ibu' => $clean($request->riwayat_penyakit_ibu),
            ]
        );

        $role = auth()->user()->role;
        return redirect()->route($role . '.kia')->with('success', 'Riwayat kesehatan berhasil diperbarui.');
    }
    public function ttdIndex()
    {
        abort_unless(auth()->check() && auth()->user()->role === 'pengguna', 403);

        $dataKia = DataKia::with('ttdTrackings')->firstOrCreate(['user_id' => auth()->id()]);
        $trackings = $dataKia->ttdTrackings->keyBy('bulan_ke');

        return view('pengguna.kia-ttd', compact('dataKia', 'trackings'));
    }

    public function ttdStore(Request $request)
    {
        abort_unless(auth()->check() && auth()->user()->role === 'pengguna', 403);
        $dataKia = DataKia::firstOrCreate(['user_id' => auth()->id()]);

        $bulanKe = $request->bulan_ke;
        $data = [
            'usia_kehamilan' => $request->usia_kehamilan,
            'bulan_tahun' => $request->bulan_tahun,
        ];

        for ($i = 1; $i <= 31; $i++) {
            $data["h$i"] = $request->has("h$i");
        }

        $dataKia->ttdTrackings()->updateOrCreate(
            ['bulan_ke' => $bulanKe],
            $data
        );

        return back()->with('success', 'Catatan minum TTD bulan ke-' . $bulanKe . ' berhasil disimpan.');
    }

    public function pemantauanIndex()
    {
        abort_unless(auth()->check() && auth()->user()->role === 'pengguna', 403);

        $dataKia = DataKia::with('pemantauanMingguans')->firstOrCreate(['user_id' => auth()->id()]);
        $pemantauans = $dataKia->pemantauanMingguans->keyBy('minggu_ke');

        return view('pengguna.kia-pemantauan', compact('dataKia', 'pemantauans'));
    }

    public function pemantauanStore(Request $request)
    {
        abort_unless(auth()->check() && auth()->user()->role === 'pengguna', 403);
        $dataKia = DataKia::firstOrCreate(['user_id' => auth()->id()]);

        $mingguKe = intval($request->minggu_ke);

        $fields = [
            'pemeriksaan_kehamilan',
            'kelas_ibu_hamil',
            'demam_lebih_2_hari',
            'pusing_sakit_kepala',
            'sulit_tidur_cemas',
            'risiko_tb',
            'gerakan_bayi',
            'nyeri_perut_hebat',
            'keluar_cairan_lahir',
            'sakit_saat_kencing',
            'diare_berulang',
        ];

        $data = [];
        foreach ($fields as $field) {
            $data[$field] = $request->has($field);
        }

        $dataKia->pemantauanMingguans()->updateOrCreate(
            ['minggu_ke' => $mingguKe],
            $data
        );

        return back()->with('success', 'Catatan pemantauan minggu ke-' . $mingguKe . ' berhasil disimpan.');
    }

    public function kelasIbuIndex()
    {
        $userId = auth()->id();
        $dataKia = DataKia::with('absenKelasIbuHamils')->where('user_id', $userId)->first();

        if (!$dataKia) {
            return redirect()->route('pengguna.buku_kia')->with('info', 'Silakan lengkapi screening Buku KIA terlebih dahulu.');
        }

        $absen = $dataKia->absenKelasIbuHamils->keyBy('kehadiran_ke');

        return view('pengguna.kia-kelas-ibu', compact('dataKia', 'absen'));
    }

    public function kelasIbuStore(Request $request)
    {
        $request->validate([
            'kehadiran_ke' => 'required|integer|between:1,9',
            'tanggal'      => 'nullable|string|max:100',
            'kader_info'   => 'nullable|string|max:255',
        ]);

        $userId = auth()->id();
        $dataKia = DataKia::where('user_id', $userId)->firstOrFail();

        $dataKia->absenKelasIbuHamils()->updateOrCreate(
            ['kehadiran_ke' => $request->kehadiran_ke],
            [
                'tanggal'    => $request->tanggal,
                'kader_info' => $request->kader_info,
            ]
        );

        return back()->with('success', 'Data absensi kelas ibu hamil ke-' . $request->kehadiran_ke . ' berhasil disimpan.');
    }

    public function persiapanIndex()
    {
        $userId = auth()->id();
        $dataKia = DataKia::with('persiapanMelahirkan')->where('user_id', $userId)->first();

        if (!$dataKia) {
            return redirect()->route('pengguna.buku_kia')->with('info', 'Silakan lengkapi screening Buku KIA terlebih dahulu.');
        }

        $persiapan = $dataKia->persiapanMelahirkan;

        return view('pengguna.kia-persiapan', compact('dataKia', 'persiapan'));
    }

    public function persiapanStore(Request $request)
    {
        $userId = auth()->id();
        $dataKia = DataKia::where('user_id', $userId)->firstOrFail();

        $fields = [
            'tanya_tanggal_perkiraan',
            'minta_dampingi',
            'siap_tabungan',
            'kartu_jkn',
            'tempat_melahirkan',
            'siap_ktp_kk',
            'siap_pendonor',
            'siap_kendaraan',
            'sepakat_stiker_p4k',
            'rencana_kb',
        ];

        $data = [];
        foreach ($fields as $field) {
            $data[$field] = $request->has($field);
        }

        $data['hpl_tanggal'] = $request->hpl_tanggal;
        $data['hpl_bulan']   = $request->hpl_bulan;
        $data['hpl_tahun']   = $request->hpl_tahun;
        $data['metode_kb']   = $request->metode_kb;

        $dataKia->persiapanMelahirkan()->updateOrCreate(
            ['data_kia_id' => $dataKia->id],
            $data
        );

        return back()->with('success', 'Persiapan melahirkan berhasil disimpan.');
    }

    public function prosesIndex()
    {
        $userId = auth()->id();
        $dataKia = DataKia::with('prosesMelahirkan')->where('user_id', $userId)->first();

        if (!$dataKia) {
            return redirect()->route('pengguna.buku_kia')->with('info', 'Silakan lengkapi screening Buku KIA terlebih dahulu.');
        }

        $proses = $dataKia->prosesMelahirkan;

        return view('pengguna.kia-proses', compact('dataKia', 'proses'));
    }

    public function prosesStore(Request $request)
    {
        $userId = auth()->id();
        $dataKia = DataKia::where('user_id', $userId)->firstOrFail();

        $fields = [
            'mulas_teratur',
            'durasi_persalinan',
            'hak_pendamping',
            'hak_posisi',
            'ingin_bab',
            'kurangi_sakit',
            'inisiasi_menyusu_dini',
        ];

        $data = [];
        foreach ($fields as $field) {
            $data[$field] = $request->has($field);
        }

        $dataKia->prosesMelahirkan()->updateOrCreate(
            ['data_kia_id' => $dataKia->id],
            $data
        );

        return back()->with('success', 'Proses melahirkan berhasil disimpan.');
    }
}

if (!class_exists('MyFpdi')) {
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

