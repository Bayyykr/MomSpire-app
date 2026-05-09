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
            ->merge($pengguna->map(fn($u) => (object)[...(array)$u, 'type' => 'pengguna', 'is_hamil' => (int)($u->is_hamil ?? 0)]))
            ->merge($bidan->map(fn($u) => (object)[...(array)$u, 'type' => 'bidan', 'is_hamil' => 0]))
            ->merge($dokter->map(fn($u) => (object)[...(array)$u, 'type' => 'dokter', 'is_hamil' => 0]))
            ->merge($admin->map(fn($u) => (object)[...(array)$u, 'type' => 'admin', 'is_hamil' => 0]))
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
                    $agent = tap(new Agent(), fn ($agent) => $agent->setUserAgent($session->user_agent));

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

    Route::get('/pengguna/buku-kia', function () use ($ensureUserRole) {
        $ensureUserRole('pengguna');

        return view('pengguna.buku-kia');
    })->name('pengguna.buku_kia');

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
