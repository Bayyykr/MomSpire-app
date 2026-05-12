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

        $dataKia = DataKia::with(['ibu', 'suami', 'anak', 'layanan', 'riwayat', 'ttdTrackings', 'absenKelasIbuHamils', 'persiapanMelahirkan', 'prosesMelahirkan', 'pemantauanIbuNifas'])
            ->findOrFail($id);

        // Pastikan relasi ttdTrackings, pemantauanMingguans, absenKelasIbuHamils, persiapanMelahirkan, prosesMelahirkan, dan pemantauanIbuNifas selalu segar
        $dataKia->load(['ttdTrackings', 'pemantauanMingguans', 'absenKelasIbuHamils', 'persiapanMelahirkan', 'prosesMelahirkan', 'pemantauanIbuNifas']);

        if ($user->role === 'pengguna') {
            abort_unless($dataKia->user_id === $user->id, 403);
        } else {
            abort_unless(in_array($user->role, ['admin', 'bidan', 'dokter']), 403);
        }

        $pdfService = new \App\Services\KiaPdfService();
        $pdfContent = $pdfService->generate($dataKia);

        return response($pdfContent, 200, [
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

    public function nifasIndex()
    {
        $userId = auth()->id();
        $dataKia = DataKia::with('pemantauanIbuNifas')->where('user_id', $userId)->first();

        if (!$dataKia) {
            return redirect()->route('pengguna.buku_kia')->with('info', 'Silakan lengkapi screening Buku KIA terlebih dahulu.');
        }

        $records = $dataKia->pemantauanIbuNifas->keyBy('hari_ke');

        return view('pengguna.kia-pemantauan-nifas', compact('dataKia', 'records'));
    }

    public function nifasStore(Request $request)
    {
        $request->validate([
            'hari_ke' => 'required|integer|between:1,42',
            'paraf_kader_nakes' => 'nullable|string|max:100',
        ]);

        $userId = auth()->id();
        $dataKia = DataKia::where('user_id', $userId)->firstOrFail();

        $fields = [
            'pemeriksaan_nifas',
            'konsumsi_vitamin_a',
            'konsumsi_ttd',
            'pemenuhan_gizi',
            'masalah_jiwa',
            'demam',
            'sakit_kepala',
            'pandangan_kabur',
            'nyeri_ulu_hati',
            'jantung_berdebar',
            'keluar_cairan_lahir',
            'napas_pendek',
            'payudara_bengkak',
            'gangguan_bak',
            'kelamin_bengkak',
            'darah_nifas_berbau',
            'pendarahan_hebat',
            'keputihan',
        ];

        $data = [];
        foreach ($fields as $field) {
            $data[$field] = $request->has($field);
        }
        
        $data['paraf_kader_nakes'] = $request->paraf_kader_nakes;

        $dataKia->pemantauanIbuNifas()->updateOrCreate(
            ['hari_ke' => $request->hari_ke],
            $data
        );

        return back()->with('success', 'Catatan pemantauan ibu nifas hari ke-' . $request->hari_ke . ' berhasil disimpan.');
    }
}

