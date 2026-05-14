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

        $dataKia = DataKia::with(['ibu', 'suami', 'anak', 'layanan', 'riwayat', 'ttdTrackings', 'absenKelasIbuHamils', 'persiapanMelahirkan', 'pemantauanIbuNifas', 'keluargaBerencana', 'bayiBaruLahir', 'pemantauanBayis', 'warnaTinja', 'absenKelasBalitas', 'pemantauanMingguanBayis', 'perkembanganBayi', 'pemantauanBulananBayis', 'perkembanganBayi6Bulan', 'pemantauanBulananBayi12s', 'perkembanganBayi9Bulan', 'perkembanganBayi12Bulan', 'pemantauanBulananAnak24s'])
            ->findOrFail($id);

        // Pastikan relasi ttdTrackings, pemantauanMingguans, absenKelasIbuHamils, persiapanMelahirkan, pemantauanIbuNifas, keluargaBerencana, bayiBaruLahir, pemantauanBayis, warnaTinja, absenKelasBalitas, pemantauanMingguanBayis, perkembanganBayi, pemantauanBulananBayis, perkembanganBayi6Bulan, pemantauanBulananBayi12s, perkembanganBayi9Bulan, perkembanganBayi12Bulan, dan pemantauanBulananAnak24s selalu segar
        $dataKia->load(['ttdTrackings', 'pemantauanMingguans', 'absenKelasIbuHamils', 'persiapanMelahirkan', 'pemantauanIbuNifas', 'keluargaBerencana', 'bayiBaruLahir', 'pemantauanBayis', 'warnaTinja', 'absenKelasBalitas', 'pemantauanMingguanBayis', 'perkembanganBayi', 'pemantauanBulananBayis', 'perkembanganBayi6Bulan', 'pemantauanBulananBayi12s', 'perkembanganBayi9Bulan', 'perkembanganBayi12Bulan', 'pemantauanBulananAnak24s']);

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

    public function kbIndex()
    {
        $userId = auth()->id();
        $dataKia = DataKia::with('keluargaBerencana')->where('user_id', $userId)->first();

        if (!$dataKia) {
            return redirect()->route('pengguna.buku_kia')->with('info', 'Silakan lengkapi screening Buku KIA terlebih dahulu.');
        }

        $kb = $dataKia->keluargaBerencana;

        return view('pengguna.kia-kb', compact('dataKia', 'kb'));
    }

    public function kbStore(Request $request)
    {
        $userId = auth()->id();
        $dataKia = DataKia::where('user_id', $userId)->firstOrFail();

        $dataKia->keluargaBerencana()->updateOrCreate(
            ['data_kia_id' => $dataKia->id],
            ['paraf_ibu' => $request->paraf_ibu]
        );

        return back()->with('success', 'Catatan rencana Keluarga Berencana (KB) berhasil disimpan.');
    }

    public function bayiIndex()
    {
        $userId = auth()->id();
        $dataKia = DataKia::with('bayiBaruLahir')->where('user_id', $userId)->first();

        if (!$dataKia) {
            return redirect()->route('pengguna.buku_kia')->with('info', 'Silakan lengkapi screening Buku KIA terlebih dahulu.');
        }

        $bayi = $dataKia->bayiBaruLahir;

        return view('pengguna.kia-bayi', compact('dataKia', 'bayi'));
    }

    public function bayiStore(Request $request)
    {
        $userId = auth()->id();
        $dataKia = DataKia::where('user_id', $userId)->firstOrFail();

        $dataKia->bayiBaruLahir()->updateOrCreate(
            ['data_kia_id' => $dataKia->id],
            [
                'jam_0_6' => $request->has('jam_0_6'),
                'jam_6_48' => $request->has('jam_6_48'),
                'hari_3_7' => $request->has('hari_3_7'),
                'hari_8_28' => $request->has('hari_8_28'),
            ]
        );

        return back()->with('success', 'Ceklist pemeriksaan bayi baru lahir berhasil disimpan.');
    }

    public function pemantauanBayiIndex()
    {
        $userId = auth()->id();
        $dataKia = DataKia::with('pemantauanBayis')->where('user_id', $userId)->first();

        if (!$dataKia) {
            return redirect()->route('pengguna.buku_kia')->with('info', 'Silakan lengkapi screening Buku KIA terlebih dahulu.');
        }

        $pemantauans = $dataKia->pemantauanBayis->keyBy('hari_ke');

        return view('pengguna.kia-pemantauan-bayi', compact('dataKia', 'pemantauans'));
    }

    public function pemantauanBayiStore(Request $request)
    {
        $userId = auth()->id();
        $dataKia = DataKia::where('user_id', $userId)->firstOrFail();

        $data = [
            'sesak_napas'      => $request->has('sesak_napas'),
            'aktivitas_lemah'  => $request->has('aktivitas_lemah'),
            'warna_kulit_biru' => $request->has('warna_kulit_biru'),
            'hisapan_lemah'    => $request->has('hisapan_lemah'),
            'kejang'           => $request->has('kejang'),
            'suhu_abnormal'    => $request->has('suhu_abnormal'),
            'bab_abnormal'     => $request->has('bab_abnormal'),
            'kencing_sedikit'  => $request->has('kencing_sedikit'),
            'tali_pusat_merah' => $request->has('tali_pusat_merah'),
            'mata_merah'       => $request->has('mata_merah'),
            'kulit_bintil'     => $request->has('kulit_bintil'),
            'belum_imunisasi'  => $request->has('belum_imunisasi'),
        ];

        if ($request->has('paraf_kader_nakes')) {
            $data['paraf_kader_nakes'] = $request->paraf_kader_nakes;
        }

        $dataKia->pemantauanBayis()->updateOrCreate(
            ['data_kia_id' => $dataKia->id, 'hari_ke' => $request->hari_ke],
            $data
        );

        return back()->with('success', 'Catatan pemantauan harian bayi hari ke-' . $request->hari_ke . ' berhasil disimpan.');
    }

    public function warnaTinjaIndex()
    {
        $userId = auth()->id();
        $dataKia = DataKia::with('warnaTinja')->where('user_id', $userId)->first();

        if (!$dataKia) {
            return redirect()->route('pengguna.buku_kia')->with('info', 'Silakan lengkapi screening Buku KIA terlebih dahulu.');
        }

        return view('pengguna.kia-warna-tinja', compact('dataKia'));
    }

    public function warnaTinjaStore(Request $request)
    {
        $userId = auth()->id();
        $dataKia = DataKia::where('user_id', $userId)->firstOrFail();

        $dataKia->warnaTinja()->updateOrCreate(
            ['data_kia_id' => $dataKia->id],
            [
                'tanggal_2_minggu'  => $request->tanggal_2_minggu,
                'nomor_2_minggu'    => $request->nomor_2_minggu,
                'tanggal_1_bulan'   => $request->tanggal_1_bulan,
                'nomor_1_bulan'     => $request->nomor_1_bulan,
                'tanggal_2_4_bulan' => $request->tanggal_2_4_bulan,
                'nomor_2_4_bulan'   => $request->nomor_2_4_bulan,
            ]
        );

        return back()->with('success', 'Pemantauan warna tinja bayi berhasil disimpan.');
    }

    public function kelasBalitaIndex()
    {
        $userId = auth()->id();
        $dataKia = DataKia::with('absenKelasBalitas')->where('user_id', $userId)->first();

        if (!$dataKia) {
            return redirect()->route('pengguna.buku_kia')->with('info', 'Silakan lengkapi screening Buku KIA terlebih dahulu.');
        }

        $absensi = $dataKia->absenKelasBalitas->keyBy('kehadiran_ke');

        return view('pengguna.kia-kelas-balita', compact('dataKia', 'absensi'));
    }

    public function kelasBalitaStore(Request $request)
    {
        $userId = auth()->id();
        $dataKia = DataKia::where('user_id', $userId)->firstOrFail();

        $dataKia->absenKelasBalitas()->updateOrCreate(
            ['data_kia_id' => $dataKia->id, 'kehadiran_ke' => $request->kehadiran_ke],
            [
                'tanggal'    => $request->tanggal,
                'kader_info' => $request->kader_info,
            ]
        );

        return back()->with('success', 'Absensi kehadiran kelas ibu balita sesi ke-' . $request->kehadiran_ke . ' berhasil disimpan.');
    }

    public function pemantauanMingguanBayiIndex()
    {
        $userId = auth()->id();
        $dataKia = DataKia::with(['pemantauanMingguanBayis', 'perkembanganBayi'])->where('user_id', $userId)->first();

        if (!$dataKia) {
            return redirect()->route('pengguna.buku_kia')->with('info', 'Silakan lengkapi screening Buku KIA terlebih dahulu.');
        }

        $mingguan = $dataKia->pemantauanMingguanBayis->keyBy('minggu_ke');
        $perkembangan = $dataKia->perkembanganBayi;

        return view('pengguna.kia-mingguan-bayi', compact('dataKia', 'mingguan', 'perkembangan'));
    }

    public function pemantauanMingguanBayiStore(Request $request)
    {
        $userId = auth()->id();
        $dataKia = DataKia::where('user_id', $userId)->firstOrFail();

        $data = [
            'sesak_napas'     => $request->has('sesak_napas'),
            'batuk'           => $request->has('batuk'),
            'suhu_abnormal'   => $request->has('suhu_abnormal'),
            'bab_sering'      => $request->has('bab_sering'),
            'kencing_sedikit' => $request->has('kencing_sedikit'),
            'kulit_biru'      => $request->has('kulit_biru'),
            'aktivitas_lemah' => $request->has('aktivitas_lemah'),
            'hisapan_lemah'   => $request->has('hisapan_lemah'),
            'tidak_makan'     => $request->has('tidak_makan'),
        ];

        if ($request->has('paraf_kader_nakes')) {
            $data['paraf_kader_nakes'] = $request->paraf_kader_nakes;
        }

        $dataKia->pemantauanMingguanBayis()->updateOrCreate(
            ['data_kia_id' => $dataKia->id, 'minggu_ke' => $request->minggu_ke],
            $data
        );

        return back()->with('success', 'Catatan pemantauan mingguan bayi minggu ke-' . $request->minggu_ke . ' berhasil disimpan.')
            ->with('active_tab', 'mingguan')
            ->with('active_week', $request->minggu_ke);
    }

    public function perkembanganBayiStore(Request $request)
    {
        $userId = auth()->id();
        $dataKia = DataKia::where('user_id', $userId)->firstOrFail();

        $dataKia->perkembanganBayi()->updateOrCreate(
            ['data_kia_id' => $dataKia->id],
            [
                'angkat_kepala_45' => $request->has('angkat_kepala_45') ? ($request->angkat_kepala_45 === '1') : null,
                'gerak_kepala'     => $request->has('gerak_kepala') ? ($request->gerak_kepala === '1') : null,
                'tatap_wajah'      => $request->has('tatap_wajah') ? ($request->tatap_wajah === '1') : null,
                'ngoceh'           => $request->has('ngoceh') ? ($request->ngoceh === '1') : null,
                'tertawa_keras'    => $request->has('tertawa_keras') ? ($request->tertawa_keras === '1') : null,
                'terkejut_suara'   => $request->has('terkejut_suara') ? ($request->terkejut_suara === '1') : null,
                'tersenyum'        => $request->has('tersenyum') ? ($request->tersenyum === '1') : null,
                'mengenal_ibu'     => $request->has('mengenal_ibu') ? ($request->mengenal_ibu === '1') : null,
            ]
        );

        return back()->with('success', 'Checklist perkembangan bayi berhasil disimpan.')
            ->with('active_tab', 'perkembangan');
    }

    public function pemantauanBulananBayiIndex()
    {
        $userId = auth()->id();
        $dataKia = DataKia::with(['pemantauanBulananBayis', 'perkembanganBayi6Bulan'])->where('user_id', $userId)->first();

        if (!$dataKia) {
            return redirect()->route('pengguna.buku_kia')->with('info', 'Silakan lengkapi screening Buku KIA terlebih dahulu.');
        }

        $bulanan = $dataKia->pemantauanBulananBayis->keyBy('bulan_ke');
        $perkembangan = $dataKia->perkembanganBayi6Bulan;

        return view('pengguna.kia-bulanan-bayi', compact('dataKia', 'bulanan', 'perkembangan'));
    }

    public function pemantauanBulananBayiStore(Request $request)
    {
        $userId = auth()->id();
        $dataKia = DataKia::where('user_id', $userId)->firstOrFail();

        $data = [
            'sesak_napas'     => $request->has('sesak_napas'),
            'batuk'           => $request->has('batuk'),
            'suhu_abnormal'   => $request->has('suhu_abnormal'),
            'bab_sering'      => $request->has('bab_sering'),
            'kencing_sedikit' => $request->has('kencing_sedikit'),
            'kulit_biru'      => $request->has('kulit_biru'),
            'aktivitas_lemah' => $request->has('aktivitas_lemah'),
            'hisapan_lemah'   => $request->has('hisapan_lemah'),
            'tidak_makan'     => $request->has('tidak_makan'),
        ];

        if ($request->has('paraf_kader_nakes')) {
            $data['paraf_kader_nakes'] = $request->paraf_kader_nakes;
        }

        $dataKia->pemantauanBulananBayis()->updateOrCreate(
            ['data_kia_id' => $dataKia->id, 'bulan_ke' => $request->bulan_ke],
            $data
        );

        return back()->with('success', 'Catatan pemantauan bulanan bayi bulan ke-' . $request->bulan_ke . ' berhasil disimpan.')
            ->with('active_tab', 'bulanan')
            ->with('active_month', $request->bulan_ke);
    }

    public function perkembanganBayi6BulanStore(Request $request)
    {
        $userId = auth()->id();
        $dataKia = DataKia::where('user_id', $userId)->firstOrFail();

        $dataKia->perkembanganBayi6Bulan()->updateOrCreate(
            ['data_kia_id' => $dataKia->id],
            [
                'berbalik'        => $request->has('berbalik') ? ($request->berbalik === '1') : null,
                'kepala_tegak_90' => $request->has('kepala_tegak_90') ? ($request->kepala_tegak_90 === '1') : null,
                'kepala_stabil'   => $request->has('kepala_stabil') ? ($request->kepala_stabil === '1') : null,
                'genggam_mainan'  => $request->has('genggam_mainan') ? ($request->genggam_mainan === '1') : null,
                'raih_benda'      => $request->has('raih_benda') ? ($request->raih_benda === '1') : null,
                'amati_tangan'    => $request->has('amati_tangan') ? ($request->amati_tangan === '1') : null,
                'luas_pandang'    => $request->has('luas_pandang') ? ($request->luas_pandang === '1') : null,
                'arah_mata'       => $request->has('arah_mata') ? ($request->arah_mata === '1') : null,
                'suara_gembira'   => $request->has('suara_gembira') ? ($request->suara_gembira === '1') : null,
                'senyum_mainan'   => $request->has('senyum_mainan') ? ($request->senyum_mainan === '1') : null,
            ]
        );

        return back()->with('success', 'Checklist perkembangan bayi umur 3-6 bulan berhasil disimpan.')
            ->with('active_tab', 'perkembangan');
    }

    public function pemantauanBulananBayi12Index()
    {
        $userId = auth()->id();
        $dataKia = DataKia::with(['pemantauanBulananBayi12s', 'perkembanganBayi9Bulan', 'perkembanganBayi12Bulan'])->where('user_id', $userId)->first();

        if (!$dataKia) {
            return redirect()->route('pengguna.buku_kia')->with('info', 'Silakan lengkapi screening Buku KIA terlebih dahulu.');
        }

        $bulanan = $dataKia->pemantauanBulananBayi12s->keyBy('bulan_ke');
        $perkembangan9 = $dataKia->perkembanganBayi9Bulan;
        $perkembangan12 = $dataKia->perkembanganBayi12Bulan;

        return view('pengguna.kia-bulanan-bayi-12', compact('dataKia', 'bulanan', 'perkembangan9', 'perkembangan12'));
    }

    public function pemantauanBulananBayi12Store(Request $request)
    {
        $userId = auth()->id();
        $dataKia = DataKia::where('user_id', $userId)->firstOrFail();

        $data = [
            'sesak_napas'     => $request->has('sesak_napas'),
            'batuk'           => $request->has('batuk'),
            'suhu_abnormal'   => $request->has('suhu_abnormal'),
            'bab_sering'      => $request->has('bab_sering'),
            'kencing_sedikit' => $request->has('kencing_sedikit'),
            'kulit_biru'      => $request->has('kulit_biru'),
            'aktivitas_lemah' => $request->has('aktivitas_lemah'),
            'hisapan_lemah'   => $request->has('hisapan_lemah'),
            'tidak_makan'     => $request->has('tidak_makan'),
        ];

        if ($request->has('paraf_kader_nakes')) {
            $data['paraf_kader_nakes'] = $request->paraf_kader_nakes;
        }

        $dataKia->pemantauanBulananBayi12s()->updateOrCreate(
            ['data_kia_id' => $dataKia->id, 'bulan_ke' => $request->bulan_ke],
            $data
        );

        return back()->with('success', 'Catatan pemantauan bulanan bayi bulan ke-' . $request->bulan_ke . ' berhasil disimpan.')
            ->with('active_tab', 'bulanan')
            ->with('active_month', $request->bulan_ke);
    }

    public function perkembanganBayi9BulanStore(Request $request)
    {
        $userId = auth()->id();
        $dataKia = DataKia::where('user_id', $userId)->firstOrFail();

        $dataKia->perkembanganBayi9Bulan()->updateOrCreate(
            ['data_kia_id' => $dataKia->id],
            [
                'duduk_mandiri'       => $request->has('duduk_mandiri') ? ($request->duduk_mandiri === '1') : null,
                'tengkurap_dada'      => $request->has('tengkurap_dada') ? ($request->tengkurap_dada === '1') : null,
                'merangkak'           => $request->has('merangkak') ? ($request->merangkak === '1') : null,
                'pindah_benda'        => $request->has('pindah_benda') ? ($request->pindah_benda === '1') : null,
                'pungut_2_benda'      => $request->has('pungut_2_benda') ? ($request->pungut_2_benda === '1') : null,
                'pungut_kacang'       => $request->has('pungut_kacang') ? ($request->pungut_kacang === '1') : null,
                'bersuara_tanpa_arti' => $request->has('bersuara_tanpa_arti') ? ($request->bersuara_tanpa_arti === '1') : null,
                'cari_mainan'         => $request->has('cari_mainan') ? ($request->cari_mainan === '1') : null,
                'tepuk_tangan'        => $request->has('tepuk_tangan') ? ($request->tepuk_tangan === '1') : null,
                'lempar_benda'        => $request->has('lempar_benda') ? ($request->lempar_benda === '1') : null,
                'makan_kue'           => $request->has('makan_kue') ? ($request->makan_kue === '1') : null,
            ]
        );

        return back()->with('success', 'Checklist perkembangan bayi umur 6-9 bulan berhasil disimpan.')
            ->with('active_tab', 'perkembangan9');
    }

    public function perkembanganBayi12BulanStore(Request $request)
    {
        $userId = auth()->id();
        $dataKia = DataKia::where('user_id', $userId)->firstOrFail();

        $dataKia->perkembanganBayi12Bulan()->updateOrCreate(
            ['data_kia_id' => $dataKia->id],
            [
                'angkat_badan_berdiri' => $request->has('angkat_badan_berdiri') ? ($request->angkat_badan_berdiri === '1') : null,
                'belajar_berdiri'      => $request->has('belajar_berdiri') ? ($request->belajar_berdiri === '1') : null,
                'jalan_dituntun'       => $request->has('jalan_dituntun') ? ($request->jalan_dituntun === '1') : null,
                'ulur_tangan_raih'     => $request->has('ulur_tangan_raih') ? ($request->ulur_tangan_raih === '1') : null,
                'genggam_pensil'       => $request->has('genggam_pensil') ? ($request->genggam_pensil === '1') : null,
                'masuk_benda_mulut'    => $request->has('masuk_benda_mulut') ? ($request->masuk_benda_mulut === '1') : null,
                'tiru_bunyi'           => $request->has('tiru_bunyi') ? ($request->tiru_bunyi === '1') : null,
                'sebut_2_suku_kata'    => $request->has('sebut_2_suku_kata') ? ($request->sebut_2_suku_kata === '1') : null,
                'eksplorasi_sekitar'   => $request->has('eksplorasi_sekitar') ? ($request->eksplorasi_sekitar === '1') : null,
                'reaksi_panggilan'     => $request->has('reaksi_panggilan') ? ($request->reaksi_panggilan === '1') : null,
                'bermain_cilukba'      => $request->has('bermain_cilukba') ? ($request->bermain_cilukba === '1') : null,
                'kenal_keluarga'       => $request->has('kenal_keluarga') ? ($request->kenal_keluarga === '1') : null,
            ]
        );

        return back()->with('success', 'Checklist perkembangan bayi umur 9-12 bulan berhasil disimpan.')
            ->with('active_tab', 'perkembangan12');
    }

    public function pemantauanBulananAnak24Index()
    {
        $userId = auth()->id();
        $dataKia = DataKia::with(['pemantauanBulananAnak24s'])->where('user_id', $userId)->first();

        if (!$dataKia) {
            return redirect()->route('pengguna.buku_kia')->with('info', 'Silakan lengkapi screening Buku KIA terlebih dahulu.');
        }

        $bulanan = $dataKia->pemantauanBulananAnak24s->keyBy('bulan_ke');

        return view('pengguna.kia-bulanan-anak-24', compact('dataKia', 'bulanan'));
    }

    public function pemantauanBulananAnak24Store(Request $request)
    {
        $userId = auth()->id();
        $dataKia = DataKia::where('user_id', $userId)->firstOrFail();

        $data = [
            'sesak_napas'      => $request->has('sesak_napas'),
            'batuk'            => $request->has('batuk'),
            'suhu_abnormal'    => $request->has('suhu_abnormal'),
            'bab_sering'       => $request->has('bab_sering'),
            'kencing_sedikit'  => $request->has('kencing_sedikit'),
            'kulit_pucat_biru' => $request->has('kulit_pucat_biru'),
            'aktivitas_lemah'  => $request->has('aktivitas_lemah'),
            'telinga_cairan'   => $request->has('telinga_cairan'),
            'tidak_makan'      => $request->has('tidak_makan'),
        ];

        if ($request->has('paraf_kader_nakes')) {
            $data['paraf_kader_nakes'] = $request->paraf_kader_nakes;
        }

        $dataKia->pemantauanBulananAnak24s()->updateOrCreate(
            ['data_kia_id' => $dataKia->id, 'bulan_ke' => $request->bulan_ke],
            $data
        );

        return back()->with('success', 'Catatan pemantauan bulanan anak bulan ke-' . $request->bulan_ke . ' berhasil disimpan.')
            ->with('active_month', $request->bulan_ke);
    }
}

