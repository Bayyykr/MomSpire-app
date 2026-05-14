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
    $dataKia = \App\Models\DataKia::with(['ibu', 'suami', 'anak', 'layanan', 'riwayat', 'ttdTrackings', 'pemantauanMingguans', 'absenKelasIbuHamils', 'persiapanMelahirkan', 'pemantauanIbuNifas', 'keluargaBerencana', 'bayiBaruLahir', 'pemantauanBayis', 'warnaTinja', 'absenKelasBalitas', 'pemantauanMingguanBayis', 'perkembanganBayi', 'pemantauanBulananBayis', 'perkembanganBayi6Bulan', 'pemantauanBulananBayi12s', 'perkembanganBayi9Bulan', 'perkembanganBayi12Bulan', 'pemantauanBulananAnak24s', 'perkembanganBayi18Bulan', 'perkembanganBayi24Bulan', 'pemantauanBulananAnak72s', 'perkembanganAnak36Bulan', 'perkembanganAnak48Bulan'])->findOrFail($id);

    $pdfService = new \App\Services\KiaPdfService();
    $pdfContent = $pdfService->generate($dataKia);

    return response($pdfContent, 200, [
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

    Route::get('/bidan/kia', [\App\Http\Controllers\DataKiaController::class, 'indexNakes'])->name('bidan.kia');
    Route::get('/bidan/kia/{id}/edit-riwayat', [\App\Http\Controllers\DataKiaController::class, 'editRiwayat'])->name('bidan.kia.edit_riwayat');
    Route::post('/bidan/kia/{id}/save-riwayat', [\App\Http\Controllers\DataKiaController::class, 'saveRiwayat'])->name('bidan.kia.save_riwayat');

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

    Route::get('/dokter/kia', [\App\Http\Controllers\DataKiaController::class, 'indexNakes'])->name('dokter.kia');
    Route::get('/dokter/kia/{id}/edit-riwayat', [\App\Http\Controllers\DataKiaController::class, 'editRiwayat'])->name('dokter.kia.edit_riwayat');
    Route::post('/dokter/kia/{id}/save-riwayat', [\App\Http\Controllers\DataKiaController::class, 'saveRiwayat'])->name('dokter.kia.save_riwayat');

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
    return view('pengguna.dashboardPengguna', compact('dataKia'));
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

Route::get('/pengguna/ttd', [\App\Http\Controllers\DataKiaController::class, 'ttdIndex'])
    ->middleware('auth')->name('pengguna.ttd');
Route::post('/pengguna/ttd/save', [\App\Http\Controllers\DataKiaController::class, 'ttdStore'])
    ->middleware('auth')->name('pengguna.ttd.store');

Route::get('/pengguna/pemantauan', [\App\Http\Controllers\DataKiaController::class, 'pemantauanIndex'])
    ->middleware('auth')->name('pengguna.pemantauan');
Route::post('/pengguna/pemantauan/save', [\App\Http\Controllers\DataKiaController::class, 'pemantauanStore'])
    ->middleware('auth')->name('pengguna.pemantauan.save');

Route::get('/pengguna/kelas-ibu', [\App\Http\Controllers\DataKiaController::class, 'kelasIbuIndex'])
    ->middleware('auth')->name('pengguna.kelas_ibu');
Route::post('/pengguna/kelas-ibu/save', [\App\Http\Controllers\DataKiaController::class, 'kelasIbuStore'])
    ->middleware('auth')->name('pengguna.kelas_ibu.save');

Route::get('/pengguna/persiapan-melahirkan', [\App\Http\Controllers\DataKiaController::class, 'persiapanIndex'])
    ->middleware('auth')->name('pengguna.persiapan');
Route::post('/pengguna/persiapan-melahirkan/save', [\App\Http\Controllers\DataKiaController::class, 'persiapanStore'])
    ->middleware('auth')->name('pengguna.persiapan.save');


Route::get('/pengguna/pemantauan-nifas', [\App\Http\Controllers\DataKiaController::class, 'nifasIndex'])
    ->middleware('auth')->name('pengguna.nifas');
Route::post('/pengguna/pemantauan-nifas/save', [\App\Http\Controllers\DataKiaController::class, 'nifasStore'])
    ->middleware('auth')->name('pengguna.nifas.save');

Route::get('/pengguna/keluarga-berencana', [\App\Http\Controllers\DataKiaController::class, 'kbIndex'])
    ->middleware('auth')->name('pengguna.kb');
Route::post('/pengguna/keluarga-berencana/save', [\App\Http\Controllers\DataKiaController::class, 'kbStore'])
    ->middleware('auth')->name('pengguna.kb.save');

Route::get('/pengguna/bayi-baru-lahir', [\App\Http\Controllers\DataKiaController::class, 'bayiIndex'])
    ->middleware('auth')->name('pengguna.bayi');
Route::post('/pengguna/bayi-baru-lahir/save', [\App\Http\Controllers\DataKiaController::class, 'bayiStore'])
    ->middleware('auth')->name('pengguna.bayi.save');

Route::get('/pengguna/pemantauan-bayi', [\App\Http\Controllers\DataKiaController::class, 'pemantauanBayiIndex'])
    ->middleware('auth')->name('pengguna.pemantauan_bayi');
Route::post('/pengguna/pemantauan-bayi/save', [\App\Http\Controllers\DataKiaController::class, 'pemantauanBayiStore'])
    ->middleware('auth')->name('pengguna.pemantauan_bayi.save');

Route::get('/pengguna/warna-tinja', [\App\Http\Controllers\DataKiaController::class, 'warnaTinjaIndex'])
    ->middleware('auth')->name('pengguna.warna_tinja');
Route::post('/pengguna/warna-tinja/save', [\App\Http\Controllers\DataKiaController::class, 'warnaTinjaStore'])
    ->middleware('auth')->name('pengguna.warna_tinja.save');

Route::get('/pengguna/kelas-balita', [\App\Http\Controllers\DataKiaController::class, 'kelasBalitaIndex'])
    ->middleware('auth')->name('pengguna.kelas_balita');
Route::post('/pengguna/kelas-balita/save', [\App\Http\Controllers\DataKiaController::class, 'kelasBalitaStore'])
    ->middleware('auth')->name('pengguna.kelas_balita.save');

Route::get('/pengguna/mingguan-bayi', [\App\Http\Controllers\DataKiaController::class, 'pemantauanMingguanBayiIndex'])
    ->middleware('auth')->name('pengguna.mingguan_bayi');
Route::post('/pengguna/mingguan-bayi/save', [\App\Http\Controllers\DataKiaController::class, 'pemantauanMingguanBayiStore'])
    ->middleware('auth')->name('pengguna.mingguan_bayi.save');
Route::post('/pengguna/perkembangan-bayi/save', [\App\Http\Controllers\DataKiaController::class, 'perkembanganBayiStore'])
    ->middleware('auth')->name('pengguna.perkembangan_bayi.save');

Route::get('/pengguna/bulanan-bayi', [\App\Http\Controllers\DataKiaController::class, 'pemantauanBulananBayiIndex'])
    ->middleware('auth')->name('pengguna.bulanan_bayi');
Route::post('/pengguna/bulanan-bayi/save', [\App\Http\Controllers\DataKiaController::class, 'pemantauanBulananBayiStore'])
    ->middleware('auth')->name('pengguna.bulanan_bayi.save');
Route::post('/pengguna/perkembangan-bayi-6-bulan/save', [\App\Http\Controllers\DataKiaController::class, 'perkembanganBayi6BulanStore'])
    ->middleware('auth')->name('pengguna.perkembangan_bayi_6_bulan.save');

Route::get('/pengguna/bulanan-bayi-12', [\App\Http\Controllers\DataKiaController::class, 'pemantauanBulananBayi12Index'])
    ->middleware('auth')->name('pengguna.bulanan_bayi_12');
Route::post('/pengguna/bulanan-bayi-12/save', [\App\Http\Controllers\DataKiaController::class, 'pemantauanBulananBayi12Store'])
    ->middleware('auth')->name('pengguna.bulanan_bayi_12.save');
Route::post('/pengguna/perkembangan-bayi-9-bulan/save', [\App\Http\Controllers\DataKiaController::class, 'perkembanganBayi9BulanStore'])
    ->middleware('auth')->name('pengguna.perkembangan_bayi_9_bulan.save');
Route::post('/pengguna/perkembangan-bayi-12-bulan/save', [\App\Http\Controllers\DataKiaController::class, 'perkembanganBayi12BulanStore'])
    ->middleware('auth')->name('pengguna.perkembangan_bayi_12_bulan.save');

Route::get('/pengguna/bulanan-anak-24', [\App\Http\Controllers\DataKiaController::class, 'pemantauanBulananAnak24Index'])
    ->middleware('auth')->name('pengguna.bulanan_anak_24');
Route::post('/pengguna/bulanan-anak-24/save', [\App\Http\Controllers\DataKiaController::class, 'pemantauanBulananAnak24Store'])
    ->middleware('auth')->name('pengguna.bulanan_anak_24.save');
Route::post('/pengguna/perkembangan-bayi-18-bulan/save', [\App\Http\Controllers\DataKiaController::class, 'perkembanganBayi18BulanStore'])
    ->middleware('auth')->name('pengguna.perkembangan_bayi_18_bulan.save');
Route::post('/pengguna/perkembangan-bayi-24-bulan/save', [\App\Http\Controllers\DataKiaController::class, 'perkembanganBayi24BulanStore'])
    ->middleware('auth')->name('pengguna.perkembangan_bayi_24_bulan.save');

Route::get('/pengguna/bulanan-anak-72', [\App\Http\Controllers\DataKiaController::class, 'pemantauanBulananAnak72Index'])
    ->middleware('auth')->name('pengguna.bulanan_anak_72');
Route::post('/pengguna/bulanan-anak-72/save', [\App\Http\Controllers\DataKiaController::class, 'pemantauanBulananAnak72Store'])
    ->middleware('auth')->name('pengguna.bulanan_anak_72.save');
Route::post('/pengguna/perkembangan-anak-36-bulan/save', [\App\Http\Controllers\DataKiaController::class, 'perkembanganAnak36BulanStore'])
    ->middleware('auth')->name('pengguna.perkembangan_anak_36_bulan.save');
Route::post('/pengguna/perkembangan-anak-48-bulan/save', [\App\Http\Controllers\DataKiaController::class, 'perkembanganAnak48BulanStore'])
    ->middleware('auth')->name('pengguna.perkembangan_anak_48_bulan.save');

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

