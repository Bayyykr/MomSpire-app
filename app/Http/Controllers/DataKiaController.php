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
        $clean = function($val) { return $val === '' ? null : $val; };

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

        // 6. Riwayat Kesehatan
        $dataKia->riwayat()->updateOrCreate([], [
            'usia_ibu' => $clean($request->usia_ibu),
            'kehamilan_ke' => $clean($request->kehamilan_ke),
            'jumlah_anak_hidup' => $clean($request->jumlah_anak_hidup),
            'riwayat_keguguran' => $clean($request->riwayat_keguguran),
            'riwayat_penyakit_ibu' => $clean($request->riwayat_penyakit_ibu),
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
        abort_unless(auth()->check() && auth()->user()->role === 'admin', 403);
        $dataKia = DataKia::with(['ibu', 'suami', 'anak', 'layanan', 'riwayat'])->findOrFail($id);

        $originalPath  = resource_path('views/buku/Buku KIA (Permenkes).pdf');
        $convertedPath = storage_path('app/buku_kia_converted.pdf');
        $scriptPath    = base_path('scripts/convert_pdf_fpdi.py');

        if (!file_exists($originalPath)) {
            abort(404, 'File template PDF tidak ditemukan.');
        }

        if (!file_exists($convertedPath)) {
            $output = shell_exec("python \"$scriptPath\" \"$originalPath\" \"$convertedPath\" 2>&1");
            if (!file_exists($convertedPath)) {
                abort(500, 'Gagal mengkonversi PDF: ' . $output);
            }
        }

        $pdf = new \setasign\Fpdi\Fpdi();
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
                $pdf->SetXY(148, 181); $pdf->Write(0, $ibu->nama ?? '');
                $pdf->SetXY(93, 132); $pdf->Write(0, $dataKia->faskes_dikeluarkan ?? '');
                $pdf->SetXY(303, 132); $pdf->Write(0, $dataKia->kab_kota_dikeluarkan ?? '');
                $pdf->SetXY(93, 94); $pdf->Write(0, $dataKia->tanggal_dikeluarkan ? date('d-m-Y', strtotime($dataKia->tanggal_dikeluarkan)) : '');
                $pdf->SetXY(303, 94); $pdf->Write(0, $dataKia->provinsi_dikeluarkan ?? '');
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
            }
        }

        return response($pdf->Output('S'), 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="Buku_KIA_' . ($dataKia->ibu->nama ?? 'Identitas') . '.pdf"',
        ]);
    }
}
