<?php

use Illuminate\Foundation\Application;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\Password;
use Laravel\Fortify\Features;
use Laravel\Jetstream\Agent;

Route::get('/', function () {
    session()->regenerateToken();

    return response()
        ->view('landingPage')
        ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
        ->header('Pragma', 'no-cache')
        ->header('Expires', '0');
});

// Temporary debug route - remove after testing
Route::get('/who-am-i', function () {
    return response()->json([
        'authenticated' => auth()->check(),
        'user' => auth()->check() ? [
            'id' => auth()->user()->id,
            'email' => auth()->user()->email,
            'role' => auth()->user()->role,
            'name' => auth()->user()->name,
        ] : null,
        'guard' => config('auth.defaults.guard'),
    ]);
});

// DEBUG: Test PDF export tanpa auth - HAPUS setelah testing
Route::get('/debug-pdf/{id}', function ($id) {
    $dataKia = \App\Models\DataKia::with(['ibu', 'suami'])->findOrFail($id);

    $originalPath = resource_path('views/buku/Buku KIA (Permenkes).pdf');
    $convertedPath = storage_path('app/buku_kia_converted.pdf');
    $scriptPath = base_path('scripts/convert_pdf_fpdi.py');

    if (!file_exists($originalPath)) {
        return response()->json([
            'error' => 'File PDF template tidak ditemukan!',
            'path' => $originalPath,
            'data_kia' => $dataKia->toArray(),
        ]);
    }

    // Konversi PDF menggunakan Python/pikepdf agar kompatibel dengan FPDI
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
        }
    }

    return response($pdf->Output('S'), 200, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'inline; filename="debug_kia_' . $id . '.pdf"',
    ]);
});

// Role-specific dashboards and admin-only user management
Route::middleware(['auth'])->group(function () {
    $ensureRole = function (string $role) {
        abort_unless(auth()->check() && auth()->user()->role === $role, 403);
    };

    Route::get('/admin/dashboard', function () use ($ensureRole) {
        $ensureRole('admin');

        $pengguna = DB::table('pengguna')->orderBy('name')->get()->map(function ($user) {
            return (object) [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => 'pengguna',
                'is_hamil' => (int) ($user->is_hamil ?? 0),
            ];
        });

        $bidan = DB::table('bidan')->orderBy('name')->get()->map(function ($user) {
            return (object) [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => 'bidan',
                'is_hamil' => 0,
            ];
        });

        $dokter = DB::table('dokter')->orderBy('name')->get()->map(function ($user) {
            return (object) [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => 'dokter',
                'is_hamil' => 0,
            ];
        });

        $admin = DB::table('admin')->orderBy('name')->get()->map(function ($user) {
            return (object) [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => 'admin',
                'is_hamil' => 0,
            ];
        });

        $allUsers = collect()->merge($pengguna)->merge($bidan)->merge($dokter)->merge($admin)->values();

        return view('Admin.DashboardAdmin', [
            'penggunaCount' => $pengguna->count(),
            'bidanCount' => $bidan->count(),
            'dokterCount' => $dokter->count(),
            'adminCount' => $admin->count(),
            'ibuHamilAktifCount' => $pengguna->where('is_hamil', 1)->count(),
            'dashboardUsers' => $allUsers,
        ]);
    })->name('admin.dashboard');

    Route::get('/admin/users', function () use ($ensureRole) {
        $ensureRole('admin');

        $pengguna = DB::table('pengguna')->orderBy('name')->get();
        $bidan = DB::table('bidan')->orderBy('name')->get();
        $dokter = DB::table('dokter')->orderBy('name')->get();
        $admin = DB::table('admin')->orderBy('name')->get();

        $users = collect()
            ->merge($pengguna->map(fn($u) => (object) [...(array) $u, 'type' => 'pengguna', 'is_hamil' => (int) ($u->is_hamil ?? 0)]))
            ->merge($bidan->map(fn($u) => (object) [...(array) $u, 'type' => 'bidan', 'is_hamil' => 0]))
            ->merge($dokter->map(fn($u) => (object) [...(array) $u, 'type' => 'dokter', 'is_hamil' => 0]))
            ->merge($admin->map(fn($u) => (object) [...(array) $u, 'type' => 'admin', 'is_hamil' => 0]))
            ->sortBy('name');

        return view('Admin.ManajemenUser', [
            'users' => $users,
        ]);
    })->name('admin.users');

    Route::post('/admin/users', function (Request $request) use ($ensureRole) {
        $ensureRole('admin');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'role' => ['required', 'in:pengguna,bidan,dokter,admin'],
            'password' => ['required', 'string', 'min:8'],
        ]);
        $isHamil = $request->boolean('is_hamil');

        $hashedPassword = Hash::make($validated['password']);
        $roleTable = $validated['role'];

        // Check email uniqueness across all tables
        foreach (['pengguna', 'bidan', 'dokter', 'admin'] as $table) {
            abort_if(DB::table($table)->where('email', $validated['email'])->exists(), 422, 'Email sudah terdaftar.');
        }

        // Insert into users table
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'password' => $hashedPassword,
        ]);

        // Insert into role-specific table
        $roleInsert = [
            'id' => $user->id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $hashedPassword,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        if ($roleTable === 'pengguna') {
            $roleInsert['is_hamil'] = $isHamil ? 1 : 0;
        }

        DB::table($roleTable)->insert($roleInsert);

        return redirect()->route('admin.users')->with('success', 'Akun berhasil ditambahkan.');
    })->name('admin.users.store');

    Route::post('/admin/users/update', function (Request $request) use ($ensureRole) {
        $ensureRole('admin');

        $validated = $request->validate([
            'id' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'role' => ['required', 'in:pengguna,bidan,dokter,admin'],
            'password' => ['nullable', 'string', 'min:8'],
            'type' => ['required', 'in:pengguna,bidan,dokter,admin'],
        ]);
        $isHamil = $request->boolean('is_hamil');

        $user = User::findOrFail($validated['id']);
        abort_unless(auth()->id() !== $user->id, 403);
        abort_if($user->role === 'admin' && $validated['role'] !== 'admin', 403, 'Role admin tidak dapat diubah.');

        // Find user in the original table
        $originalTable = $validated['type'];
        $userInTable = DB::table($originalTable)->where('id', $validated['id'])->first();
        abort_if(!$userInTable, 404, 'User tidak ditemukan.');

        // Check email uniqueness across all tables
        foreach (['pengguna', 'bidan', 'dokter', 'admin'] as $table) {
            $exists = DB::table($table)->where('email', $validated['email'])->where('id', '!=', $validated['id'])->exists();
            abort_if($exists, 422, 'Email sudah dipakai.');
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $user->role === 'admin' ? 'admin' : $validated['role'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        // Update in role-specific table
        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'updated_at' => now(),
        ];

        if ($originalTable === 'pengguna') {
            $updateData['is_hamil'] = $isHamil ? 1 : 0;
        }

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        DB::table($originalTable)->where('id', $validated['id'])->update($updateData);

        return redirect()->route('admin.users')->with('success', 'Akun berhasil diupdate.');
    })->name('admin.users.update');

    Route::post('/admin/users/delete', function (Request $request) use ($ensureRole) {
        $ensureRole('admin');

        $validated = $request->validate([
            'id' => ['required', 'integer'],
            'type' => ['required', 'in:pengguna,bidan,dokter,admin'],
        ]);

        $user = User::findOrFail($validated['id']);
        abort_unless(auth()->id() !== $user->id, 403);
        abort_if($user->role === 'admin', 403, 'Akun admin tidak dapat dihapus.');

        $user->delete();

        // Delete from role-specific table
        DB::table($validated['type'])->where('id', $validated['id'])->delete();

        return redirect()->route('admin.users')->with('success', 'Akun berhasil dihapus.');
    })->name('admin.users.destroy');

    Route::get('/admin/articles', function () use ($ensureRole) {
        $ensureRole('admin');

        return view('Admin.ManajemenArtikel');
    })->name('admin.articles');

    Route::get('/admin/kia', function () use ($ensureRole) {
        $ensureRole('admin');

        return view('Admin.BukuKIA');
    })->name('admin.kia');

    Route::get('/admin/kia/pdf', function () use ($ensureRole) {
        $ensureRole('admin');

        $pdfPath = resource_path('views/buku/Buku KIA (Permenkes).pdf');

        abort_unless(file_exists($pdfPath), 404, 'File Buku KIA tidak ditemukan.');

        return response()->file($pdfPath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="Buku KIA (Permenkes).pdf"',
        ]);
    })->name('admin.kia.pdf');

    Route::get('/admin/kia/{id}/pdf', [\App\Http\Controllers\DataKiaController::class, 'exportPdf'])->name('admin.kia.export_pdf');

    Route::get('/admin/settings', function (Request $request) use ($ensureRole) {
        $ensureRole('admin');

        $adminUser = $request->user();
        $browserSessions = [];

        if (config('session.driver') === 'database') {
            $browserSessions = DB::connection(config('session.connection'))
                ->table(config('session.table', 'sessions'))
                ->where('user_id', $adminUser->getAuthIdentifier())
                ->orderByDesc('last_activity')
                ->get()
                ->map(function ($session) use ($request) {
                    $agent = tap(new Agent(), fn($agent) => $agent->setUserAgent($session->user_agent));

                    return [
                        'agent' => [
                            'is_desktop' => $agent->isDesktop(),
                            'platform' => $agent->platform(),
                            'browser' => $agent->browser(),
                        ],
                        'ip_address' => $session->ip_address,
                        'is_current_device' => $session->id === $request->session()->getId(),
                        'last_active' => Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
                    ];
                })
                ->all();
        }

        return view('Admin.PengaturanAdmin', [
            'adminUser' => $adminUser,
            'browserSessions' => $browserSessions,
            'twoFactorEnabled' => filled($adminUser->two_factor_secret),
            'twoFactorConfirmed' => filled($adminUser->two_factor_confirmed_at),
            'requiresTwoFactorConfirmation' => Features::optionEnabled(Features::twoFactorAuthentication(), 'confirm'),
        ]);
    })->name('admin.settings');

    Route::post('/admin/settings/profile', function (Request $request) use ($ensureRole) {
        $ensureRole('admin');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'no_telp' => ['nullable', 'string', 'max:30'],
        ]);

        $adminId = auth()->id();
        $user = User::findOrFail($adminId);

        foreach (['users', 'admin'] as $table) {
            $query = DB::table($table)->where('email', $validated['email']);
            if ($table === 'users' || Schema::hasColumn($table, 'id')) {
                $query->where('id', '!=', $adminId);
            }
            abort_if($query->exists(), 422, 'Email sudah dipakai.');
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        if (Schema::hasColumn('users', 'no_telp')) {
            $user->no_telp = $validated['no_telp'] ?? null;
        }
        $user->save();

        DB::table('admin')->where('id', $adminId)->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            ...(Schema::hasColumn('admin', 'no_telp') ? ['no_telp' => $validated['no_telp'] ?? null] : []),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Profil admin berhasil diperbarui.');
    })->name('admin.settings.profile');

    Route::post('/admin/settings/password', function (Request $request) use ($ensureRole) {
        $ensureRole('admin');

        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $adminId = auth()->id();
        $user = User::findOrFail($adminId);

        abort_unless(Hash::check($validated['current_password'], $user->password), 422, 'Password saat ini salah.');

        $hashedPassword = Hash::make($validated['new_password']);
        $user->password = $hashedPassword;
        $user->save();

        DB::table('admin')->where('id', $adminId)->update([
            'password' => $hashedPassword,
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Password admin berhasil diperbarui.');
    })->name('admin.settings.password');

    Route::get('/bidan/dashboard', function () use ($ensureRole) {
        $ensureRole('bidan');

        return view('bidan.dashboardBidan', [
            'penggunaCount' => DB::table('pengguna')->count(),
            'bidanCount' => DB::table('bidan')->count(),
            'dokterCount' => DB::table('dokter')->count(),
            'recentPengguna' => DB::table('pengguna')->latest('created_at')->take(5)->get(['name', 'email', 'created_at']),
            'recentBidan' => DB::table('bidan')->latest('created_at')->take(5)->get(['name', 'email', 'created_at']),
        ]);
    })->name('bidan.dashboard');

    Route::get('/dokter/dashboard', function () use ($ensureRole) {
        $ensureRole('dokter');

        return view('dokter.dashboardDokter', [
            'penggunaCount' => DB::table('pengguna')->count(),
            'bidanCount' => DB::table('bidan')->count(),
            'dokterCount' => DB::table('dokter')->count(),
            'recentPengguna' => DB::table('pengguna')->latest('created_at')->take(5)->get(['name', 'email', 'created_at']),
            'recentDokter' => DB::table('dokter')->latest('created_at')->take(5)->get(['name', 'email', 'created_at']),
        ]);
    })->name('dokter.dashboard');

    Route::get('/bidan/settings', function () use ($ensureRole) {
        $ensureRole('bidan');

        return view('shared.panel-placeholder', [
            'layout' => 'bidan.master',
            'title' => 'Pengaturan Bidan - MomSpire',
            'headerTitle' => 'Pengaturan Bidan',
            'headerSubtitle' => 'Kelola profil dan preferensi akun bidan.',
            'pageTitle' => 'Pengaturan Bidan',
            'message' => 'Halaman pengaturan bidan sedang disiapkan.',
            'primaryActionUrl' => route('bidan.dashboard'),
            'primaryActionLabel' => 'Kembali ke Dashboard',
        ]);
    })->name('bidan.settings');

    Route::get('/dokter/settings', function () use ($ensureRole) {
        $ensureRole('dokter');

        return view('shared.panel-placeholder', [
            'layout' => 'dokter.master',
            'title' => 'Pengaturan Dokter - MomSpire',
            'headerTitle' => 'Pengaturan Dokter',
            'headerSubtitle' => 'Kelola profil dan preferensi akun dokter.',
            'pageTitle' => 'Pengaturan Dokter',
            'message' => 'Halaman pengaturan dokter sedang disiapkan.',
            'primaryActionUrl' => route('dokter.dashboard'),
            'primaryActionLabel' => 'Kembali ke Dashboard',
        ]);
    })->name('dokter.settings');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    $ensureUserRole = function (string $role) {
        abort_unless(auth()->check() && auth()->user()->role === $role, 403);
    };

    Route::get('/dashboard', function () {
        $user = auth()->user();
        if ($user) {
            if ($user->role === 'admin') {
                return redirect()->to(url('/admin/dashboard'));
            }
            if ($user->role === 'bidan') {
                return redirect()->to(url('/bidan/dashboard'));
            }
            if ($user->role === 'dokter') {
                return redirect()->to(url('/dokter/dashboard'));
            }
            if ($user->role === 'pengguna') {
                return redirect()->route('pengguna.dashboard');
            }
            // Unknown role
            abort(403, 'Invalid user role.');
        }

        return redirect()->route('login');
    })->name('dashboard');
});

$ensureUserRole = function (string $role) {
    abort_unless(auth()->check() && auth()->user()->role === $role, 403);
};

Route::get('/pengguna/dashboard', function () use ($ensureUserRole) {
    $ensureUserRole('pengguna');

    // Screening check: Jika data KIA belum diisi (identitas ibu kosong), arahkan ke wizard
    $dataKia = \App\Models\DataKia::with('ibu')->where('user_id', auth()->id())->first();
    if (!$dataKia || !$dataKia->ibu || empty($dataKia->ibu->nama)) {
        return redirect()->route('pengguna.buku_kia')->with('info', 'Mohon lengkapi screening Buku KIA terlebih dahulu.');
    }

    // Use the modernized pengguna dashboard view
    return view('pengguna.dashboardPengguna');
})->name('pengguna.dashboard');

Route::get('/pengguna/artikel', function () use ($ensureUserRole) {
    $ensureUserRole('pengguna');

    return view('pengguna.artikel');
})->name('pengguna.artikel');

Route::get('/pengguna/konsultasi', function () use ($ensureUserRole) {
    $ensureUserRole('pengguna');

    return view('pengguna.konsultasi');
})->name('pengguna.konsultasi');

Route::get('/pengguna/kalkulator', function () use ($ensureUserRole) {
    $ensureUserRole('pengguna');

    return view('pengguna.kalkulator');
})->name('pengguna.kalkulator');

Route::get('/pengguna/jadwal', function () use ($ensureUserRole) {
    $ensureUserRole('pengguna');

    return view('pengguna.jadwal');
})->name('pengguna.jadwal');

Route::get('/pengguna/status-kehamilan', function () use ($ensureUserRole) {
    $ensureUserRole('pengguna');

    return view('pengguna.status-kehamilan');
})->name('pengguna.status_kehamilan');

Route::get('/pengguna/buku-kia', [\App\Http\Controllers\DataKiaController::class, 'wizard'])
    ->middleware('auth')->name('pengguna.buku_kia');

Route::post('/pengguna/kia/wizard/save', [\App\Http\Controllers\DataKiaController::class, 'saveWizard'])
    ->middleware('auth')->name('pengguna.kia.wizard.save');

Route::get('/pengguna/pengaturan', function () use ($ensureUserRole) {
    $ensureUserRole('pengguna');

    return view('pengguna.pengaturan');
})->name('pengguna.pengaturan');

// Forgot password routes (simple UI + send reset link)
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('password.request');

Route::post('/forgot-password', function (Illuminate\Http\Request $request) {
    $request->validate(['email' => 'required|email']);

    $status = Password::sendResetLink($request->only('email'));

    return $status === Password::RESET_LINK_SENT
        ? back()->with('status', __($status))
        : back()->withErrors(['email' => __($status)]);
})->name('password.email');

// Explicit logout route to ensure named route exists and redirects to landing page
Route::post('/logout', function (Illuminate\Http\Request $request) {
    \Illuminate\Support\Facades\Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');
