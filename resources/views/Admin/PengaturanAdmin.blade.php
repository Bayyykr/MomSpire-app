@extends('Admin.master')

@section('title', 'Pengaturan Admin - MomSpire')
@section('header_title', 'Pengaturan Admin')
@section('header_subtitle', 'Kelola profil akun admin, email, dan keamanan password.')

@push('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .settings-section {
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-radius: 18px;
            background: #ffffff;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.04);
        }

        .settings-section .card-header {
            background: transparent;
            border-bottom: 1px solid rgba(15, 23, 42, 0.06);
            padding: 1.2rem 1.35rem;
        }

        .settings-section .card-body {
            padding: 1.35rem;
        }

        .security-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.4rem 0.75rem;
            border-radius: 999px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .security-chip.enabled {
            background: rgba(0, 184, 148, 0.12);
            color: #00896e;
        }

        .security-chip.disabled {
            background: rgba(108, 117, 125, 0.12);
            color: #6c757d;
        }

        .security-chip.warning {
            background: rgba(255, 193, 7, 0.16);
            color: #b27a00;
        }

        .code-box {
            background: #f8fafc;
            border: 1px dashed rgba(15, 23, 42, 0.16);
            border-radius: 14px;
            padding: 1rem;
            word-break: break-word;
        }

        .session-card {
            border-radius: 18px;
            overflow: hidden;
        }
    </style>
@endpush

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4 mb-4 align-items-start">
        <div class="col-lg-4">
            <div class="ps-3">
                <h6 class="fw-semibold">Profile</h6>
                <p class="text-muted small">Perbarui informasi profil dan alamat email akun admin.</p>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="admin-card settings-section h-100">
                <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <h5 class="mb-0"><i class="bi bi-person-circle me-2"></i>Profil Admin</h5>
                    <span class="text-muted small">Kelola identitas akun yang dipakai login admin.</span>
                </div>
                <div class="card-body">
                    <form id="adminProfileForm" action="{{ route('admin.settings.profile') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" id="adminProfileNama" name="name" value="{{ old('name', $adminUser->name) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" id="adminProfileEmail" name="email" value="{{ old('email', $adminUser->email) }}" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">No. Telepon</label>
                                <input type="tel" class="form-control" id="adminProfileTelp" name="no_telp" value="{{ old('no_telp', $adminUser->no_telp ?? '') }}" placeholder="08xxxxxxxxxx">
                            </div>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary-custom">Simpan Profil</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4 align-items-start">
        <div class="col-lg-4">
            <div class="ps-3">
                <h6 class="fw-semibold">Ubah Password</h6>
                <p class="text-muted small">Gunakan password kuat agar akun tetap aman.</p>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="admin-card settings-section h-100">
                <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <h5 class="mb-0"><i class="bi bi-shield-lock-fill me-2"></i>Ubah Password</h5>
                    <span class="text-muted small">Password baru otomatis berlaku ke semua sesi.</span>
                </div>
                <div class="card-body">
                    <form id="adminPasswordForm" action="{{ route('admin.settings.password') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Password Saat Ini</label>
                            <input type="password" class="form-control" id="adminCurrentPassword" name="current_password" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password Baru</label>
                            <input type="password" class="form-control" id="adminNewPassword" name="new_password" minlength="8" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" class="form-control" id="adminConfirmPassword" name="new_password_confirmation" minlength="8" required>
                        </div>
                        <button type="submit" class="btn btn-primary-custom">Ubah Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4 align-items-start">
        <div class="col-lg-4">
            <div class="ps-3">
                <h6 class="fw-semibold">Autentikasi Dua Faktor</h6>
                <p class="text-muted small">Tambahkan lapisan keamanan ekstra menggunakan 2FA.</p>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="admin-card settings-section h-100">
                <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <h5 class="mb-0"><i class="bi bi-shield-check me-2"></i>Autentikasi Dua Faktor</h5>
                    @if($twoFactorEnabled)
                        @if($twoFactorConfirmed)
                            <span class="security-chip enabled">Aktif</span>
                        @else
                            <span class="security-chip warning">Aktif, belum dikonfirmasi</span>
                        @endif
                    @else
                        <span class="security-chip disabled">Belum aktif</span>
                    @endif
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">Tambahkan lapisan keamanan ekstra dengan kode OTP dari aplikasi authenticator.</p>

                    <div id="twoFactorDisabledState" class="{{ $twoFactorEnabled ? 'd-none' : '' }}">
                        <div class="alert alert-light border mb-3">
                            Fitur ini akan meminta password admin sebelum aktif, lalu menampilkan QR code untuk dipindai.
                        </div>
                        <button type="button" class="btn btn-primary-custom" id="enableTwoFactorBtn">Aktifkan 2FA</button>
                    </div>

                    <div id="twoFactorEnabledState" class="{{ $twoFactorEnabled ? '' : 'd-none' }}">
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <button type="button" class="btn btn-outline-primary btn-sm" id="showTwoFactorQrBtn">Tampilkan QR Code</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" id="showRecoveryCodesBtn">Lihat Recovery Codes</button>
                            <button type="button" class="btn btn-outline-warning btn-sm" id="regenerateRecoveryCodesBtn">Regenerasi Codes</button>
                            <button type="button" class="btn btn-outline-danger btn-sm" id="disableTwoFactorBtn">Nonaktifkan</button>
                        </div>

                        <div id="twoFactorStatusBox" class="alert {{ $twoFactorConfirmed ? 'alert-success' : 'alert-warning' }} mb-3">
                            <div class="fw-semibold">{{ $twoFactorConfirmed ? '2FA sudah aktif.' : '2FA sedang menunggu konfirmasi.' }}</div>
                            <div class="small mb-0">Gunakan QR code atau setup key untuk menautkan aplikasi authenticator.</div>
                        </div>

                        <div id="twoFactorConfirmBox" class="{{ $requiresTwoFactorConfirmation && $twoFactorEnabled && ! $twoFactorConfirmed ? '' : 'd-none' }}">
                            <div class="alert alert-warning mb-3">
                                Aktifkan 2FA selesai, lalu masukkan kode OTP untuk konfirmasi terakhir.
                            </div>
                            <label class="form-label">Kode OTP</label>
                            <input type="text" class="form-control" id="twoFactorOtpCode" inputmode="numeric" autocomplete="one-time-code" placeholder="123456">
                            <button type="button" class="btn btn-primary-custom mt-3" id="confirmTwoFactorBtn">Konfirmasi 2FA</button>
                        </div>

                        <div id="twoFactorQrArea" class="mt-3 d-none">
                            <div class="code-box" id="twoFactorQrCode"></div>
                            <div class="code-box mt-3 d-none" id="twoFactorSetupKey"></div>
                        </div>

                        <div id="twoFactorRecoveryArea" class="mt-3 d-none">
                            <div class="fw-semibold mb-2">Recovery Codes</div>
                            <div class="code-box">
                                <div id="twoFactorRecoveryCodes" class="font-monospace small"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4 align-items-start">
        <div class="col-lg-4">
            <div class="ps-3">
                <h6 class="fw-semibold">Browser Sessions</h6>
                <p class="text-muted small">Kelola sesi aktif dari perangkat lain.</p>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="admin-card settings-section session-card h-100">
                <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <h5 class="mb-0"><i class="bi bi-pc-display-horizontal me-2"></i>Browser Sessions</h5>
                    <span class="text-muted small">Lihat perangkat yang sedang login ke akun admin.</span>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">Jika perlu, keluarkan sesi lain dari perangkat berbeda. Data ini muncul dari tabel sessions Laravel.</p>

                    @if(count($browserSessions) > 0)
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>Perangkat</th>
                                        <th>IP</th>
                                        <th>Status</th>
                                        <th>Terakhir Aktif</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($browserSessions as $session)
                                        <tr>
                                            <td>
                                                <div class="fw-semibold">
                                                    {{ $session['agent']['platform'] ?: 'Unknown' }} - {{ $session['agent']['browser'] ?: 'Unknown' }}
                                                </div>
                                                <div class="text-muted small">{{ $session['agent']['is_desktop'] ? 'Desktop' : 'Mobile' }}</div>
                                            </td>
                                            <td>{{ $session['ip_address'] ?? '-' }}</td>
                                            <td>
                                                @if($session['is_current_device'])
                                                    <span class="badge text-bg-success">Perangkat ini</span>
                                                @else
                                                    <span class="badge text-bg-secondary">Sesi aktif</span>
                                                @endif
                                            </td>
                                            <td>{{ $session['last_active'] ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex flex-wrap gap-2 mt-3">
                            <button type="button" class="btn btn-outline-danger" id="logoutOtherSessionsBtn">Logout Perangkat Lain</button>
                            <span class="text-muted small align-self-center">Butuh password admin untuk konfirmasi.</span>
                        </div>
                    @else
                        <div class="alert alert-light border mb-0">
                            Tidak ada data sesi yang bisa ditampilkan saat ini.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="securityConfirmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="securityConfirmModalTitle">Konfirmasi Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="securityConfirmForm">
                    <div class="modal-body">
                        <p class="text-muted mb-3" id="securityConfirmModalText">Masukkan password admin untuk melanjutkan.</p>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" id="securityConfirmPassword" autocomplete="current-password" required>
                            <div class="text-danger small mt-2 d-none" id="securityConfirmError"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary-custom" id="securityConfirmSubmitBtn">Lanjutkan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    window.MOMSPIRE_ADMIN_SETTINGS = {
        csrfToken: @json(csrf_token()),
        passwordConfirmationUrl: @json(route('password.confirmation')),
        passwordConfirmUrl: @json(route('password.confirm')),
        twoFactorEnableUrl: @json(route('two-factor.enable')),
        twoFactorConfirmUrl: @json(route('two-factor.confirm')),
        twoFactorDisableUrl: @json(route('two-factor.disable')),
        twoFactorQrCodeUrl: @json(route('two-factor.qr-code')),
        twoFactorSecretKeyUrl: @json(route('two-factor.secret-key')),
        twoFactorRecoveryCodesUrl: @json(route('two-factor.recovery-codes')),
        otherBrowserSessionsUrl: @json(route('other-browser-sessions.destroy')),
        requiresTwoFactorConfirmation: @json($requiresTwoFactorConfirmation),
        twoFactorEnabled: @json($twoFactorEnabled),
        twoFactorConfirmed: @json($twoFactorConfirmed),
    };

    document.addEventListener('DOMContentLoaded', function() {
        const settings = window.MOMSPIRE_ADMIN_SETTINGS || {};

        if (window.axios && settings.csrfToken) {
            window.axios.defaults.headers.common['X-CSRF-TOKEN'] = settings.csrfToken;
        }

        const confirmModalEl = document.getElementById('securityConfirmModal');
        const confirmModal = confirmModalEl ? new bootstrap.Modal(confirmModalEl) : null;
        const confirmForm = document.getElementById('securityConfirmForm');
        const confirmTitle = document.getElementById('securityConfirmModalTitle');
        const confirmText = document.getElementById('securityConfirmModalText');
        const confirmPassword = document.getElementById('securityConfirmPassword');
        const confirmError = document.getElementById('securityConfirmError');
        const confirmSubmitBtn = document.getElementById('securityConfirmSubmitBtn');

        const twoFactorDisabledState = document.getElementById('twoFactorDisabledState');
        const twoFactorEnabledState = document.getElementById('twoFactorEnabledState');
        const twoFactorConfirmBox = document.getElementById('twoFactorConfirmBox');
        const twoFactorOtpCode = document.getElementById('twoFactorOtpCode');
        const twoFactorStatusBox = document.getElementById('twoFactorStatusBox');
        const twoFactorQrArea = document.getElementById('twoFactorQrArea');
        const twoFactorQrCode = document.getElementById('twoFactorQrCode');
        const twoFactorSetupKey = document.getElementById('twoFactorSetupKey');
        const twoFactorRecoveryArea = document.getElementById('twoFactorRecoveryArea');
        const twoFactorRecoveryCodes = document.getElementById('twoFactorRecoveryCodes');

        const enableTwoFactorBtn = document.getElementById('enableTwoFactorBtn');
        const confirmTwoFactorBtn = document.getElementById('confirmTwoFactorBtn');
        const disableTwoFactorBtn = document.getElementById('disableTwoFactorBtn');
        const showTwoFactorQrBtn = document.getElementById('showTwoFactorQrBtn');
        const showRecoveryCodesBtn = document.getElementById('showRecoveryCodesBtn');
        const regenerateRecoveryCodesBtn = document.getElementById('regenerateRecoveryCodesBtn');
        const logoutOtherSessionsBtn = document.getElementById('logoutOtherSessionsBtn');

        let pendingConfirmedAction = null;

        function setLoadingState(button, isLoading, text) {
            if (!button) return;

            button.disabled = isLoading;
            if (text) {
                button.dataset.originalText = button.dataset.originalText || button.textContent;
                button.textContent = isLoading ? text : button.dataset.originalText;
            }
        }

        function setConfirmError(message) {
            if (!confirmError) return;
            confirmError.textContent = message || '';
            confirmError.classList.toggle('d-none', !message);
        }

        function openPasswordModal(options) {
            pendingConfirmedAction = options.onConfirmed || null;
            if (confirmTitle) confirmTitle.textContent = options.title || 'Konfirmasi Password';
            if (confirmText) confirmText.textContent = options.text || 'Masukkan password admin untuk melanjutkan.';
            if (confirmPassword) confirmPassword.value = '';
            setConfirmError('');
            if (confirmModal) confirmModal.show();
            setTimeout(() => confirmPassword && confirmPassword.focus(), 150);
        }

        function hidePasswordModal() {
            if (confirmModal) confirmModal.hide();
        }

        async function passwordConfirmed() {
            try {
                const response = await window.axios.get(settings.passwordConfirmationUrl);
                return Boolean(response.data && response.data.confirmed);
            } catch (error) {
                return false;
            }
        }

        function requirePasswordConfirmation(options) {
            passwordConfirmed().then((confirmed) => {
                if (confirmed) {
                    options.onConfirmed();
                    return;
                }

                openPasswordModal(options);
            });
        }

        async function confirmPasswordSubmit(event) {
            event.preventDefault();

            setConfirmError('');
            setLoadingState(confirmSubmitBtn, true, 'Memproses...');

            try {
                await window.axios.post(settings.passwordConfirmUrl, {
                    password: confirmPassword ? confirmPassword.value : '',
                });

                hidePasswordModal();

                if (typeof pendingConfirmedAction === 'function') {
                    const callback = pendingConfirmedAction;
                    pendingConfirmedAction = null;
                    callback();
                }
            } catch (error) {
                const message = error?.response?.data?.errors?.password?.[0] || 'Password tidak valid.';
                setConfirmError(message);
            } finally {
                setLoadingState(confirmSubmitBtn, false);
            }
        }

        async function fetchQrCode() {
            const response = await window.axios.get(settings.twoFactorQrCodeUrl);
            if (twoFactorQrCode && response.data && response.data.svg) {
                twoFactorQrCode.innerHTML = response.data.svg;
            }

            if (twoFactorSetupKey) {
                const secretResponse = await window.axios.get(settings.twoFactorSecretKeyUrl);
                twoFactorSetupKey.innerHTML = `<strong>Setup Key:</strong> <span class="font-monospace">${secretResponse.data?.secretKey || ''}</span>`;
                twoFactorSetupKey.classList.remove('d-none');
            }

            if (twoFactorQrArea) {
                twoFactorQrArea.classList.remove('d-none');
            }
        }

        async function fetchRecoveryCodes() {
            const response = await window.axios.get(settings.twoFactorRecoveryCodesUrl);
            if (twoFactorRecoveryCodes && Array.isArray(response.data)) {
                twoFactorRecoveryCodes.innerHTML = response.data.map((code) => `<div>${code}</div>`).join('');
            }

            if (twoFactorRecoveryArea) {
                twoFactorRecoveryArea.classList.remove('d-none');
            }
        }

        function markTwoFactorEnabledState() {
            if (twoFactorDisabledState) twoFactorDisabledState.classList.add('d-none');
            if (twoFactorEnabledState) twoFactorEnabledState.classList.remove('d-none');
            if (twoFactorStatusBox) {
                twoFactorStatusBox.classList.remove('alert-secondary', 'alert-warning');
                twoFactorStatusBox.classList.add('alert-success');
                twoFactorStatusBox.innerHTML = '<div class="fw-semibold">2FA sudah aktif.</div><div class="small mb-0">Gunakan QR code atau recovery code untuk login dengan lebih aman.</div>';
            }
        }

        function markTwoFactorDisabledState() {
            if (twoFactorDisabledState) twoFactorDisabledState.classList.remove('d-none');
            if (twoFactorEnabledState) twoFactorEnabledState.classList.add('d-none');
        }

        function markTwoFactorNeedsConfirmation() {
            if (twoFactorConfirmBox) twoFactorConfirmBox.classList.remove('d-none');
            if (twoFactorStatusBox) {
                twoFactorStatusBox.classList.remove('alert-success');
                twoFactorStatusBox.classList.add('alert-warning');
                twoFactorStatusBox.innerHTML = '<div class="fw-semibold">2FA sedang menunggu konfirmasi.</div><div class="small mb-0">Masukkan kode OTP dari authenticator untuk menyelesaikan aktivasi.</div>';
            }
        }

        async function enableTwoFactor() {
            setLoadingState(enableTwoFactorBtn, true, 'Mengaktifkan...');

            try {
                await window.axios.post(settings.twoFactorEnableUrl, {});
                markTwoFactorEnabledState();
                if (settings.requiresTwoFactorConfirmation) {
                    markTwoFactorNeedsConfirmation();
                }
                await Promise.all([fetchQrCode(), fetchRecoveryCodes()]);
            } finally {
                setLoadingState(enableTwoFactorBtn, false);
            }
        }

        async function confirmTwoFactor() {
            if (!twoFactorOtpCode) return;

            setLoadingState(confirmTwoFactorBtn, true, 'Mengonfirmasi...');

            try {
                await window.axios.post(settings.twoFactorConfirmUrl, { code: twoFactorOtpCode.value });
                if (twoFactorConfirmBox) twoFactorConfirmBox.classList.add('d-none');
                if (twoFactorStatusBox) {
                    twoFactorStatusBox.classList.remove('alert-warning');
                    twoFactorStatusBox.classList.add('alert-success');
                    twoFactorStatusBox.innerHTML = '<div class="fw-semibold">2FA berhasil dikonfirmasi.</div><div class="small mb-0">Aktivasi sudah selesai dan siap digunakan.</div>';
                }
                twoFactorOtpCode.value = '';
            } finally {
                setLoadingState(confirmTwoFactorBtn, false);
            }
        }

        async function disableTwoFactor() {
            setLoadingState(disableTwoFactorBtn, true, 'Menonaktifkan...');

            try {
                await window.axios.delete(settings.twoFactorDisableUrl);
                if (twoFactorQrArea) twoFactorQrArea.classList.add('d-none');
                if (twoFactorRecoveryArea) twoFactorRecoveryArea.classList.add('d-none');
                if (twoFactorConfirmBox) twoFactorConfirmBox.classList.add('d-none');
                markTwoFactorDisabledState();
            } finally {
                setLoadingState(disableTwoFactorBtn, false);
            }
        }

        async function showTwoFactorCodes() {
            await fetchQrCode();
            await fetchRecoveryCodes();
        }

        async function reloadRecoveryCodes() {
            await window.axios.post(settings.twoFactorRecoveryCodesUrl, {});
            await fetchRecoveryCodes();
        }

        async function logoutOtherSessions() {
            if (!logoutOtherSessionsBtn) return;

            setLoadingState(logoutOtherSessionsBtn, true, 'Logout...');

            try {
                await window.axios.delete(settings.otherBrowserSessionsUrl, {
                    data: {
                        password: confirmPassword ? confirmPassword.value : '',
                    },
                });
                window.location.reload();
            } finally {
                setLoadingState(logoutOtherSessionsBtn, false);
            }
        }

        if (confirmForm) {
            confirmForm.addEventListener('submit', confirmPasswordSubmit);
        }

        if (enableTwoFactorBtn) {
            enableTwoFactorBtn.addEventListener('click', function() {
                requirePasswordConfirmation({
                    title: 'Aktifkan 2FA',
                    text: 'Masukkan password admin untuk mengaktifkan autentikasi dua faktor.',
                    onConfirmed: enableTwoFactor,
                });
            });
        }

        if (confirmTwoFactorBtn) {
            confirmTwoFactorBtn.addEventListener('click', confirmTwoFactor);
        }

        if (disableTwoFactorBtn) {
            disableTwoFactorBtn.addEventListener('click', function() {
                requirePasswordConfirmation({
                    title: 'Nonaktifkan 2FA',
                    text: 'Masukkan password admin untuk menonaktifkan autentikasi dua faktor.',
                    onConfirmed: disableTwoFactor,
                });
            });
        }

        if (showTwoFactorQrBtn) {
            showTwoFactorQrBtn.addEventListener('click', function() {
                requirePasswordConfirmation({
                    title: 'Tampilkan QR Code',
                    text: 'Masukkan password admin untuk melihat QR code 2FA.',
                    onConfirmed: showTwoFactorCodes,
                });
            });
        }

        if (showRecoveryCodesBtn) {
            showRecoveryCodesBtn.addEventListener('click', function() {
                requirePasswordConfirmation({
                    title: 'Lihat Recovery Codes',
                    text: 'Masukkan password admin untuk melihat recovery codes.',
                    onConfirmed: fetchRecoveryCodes,
                });
            });
        }

        if (regenerateRecoveryCodesBtn) {
            regenerateRecoveryCodesBtn.addEventListener('click', function() {
                requirePasswordConfirmation({
                    title: 'Regenerasi Recovery Codes',
                    text: 'Masukkan password admin untuk membuat recovery codes baru.',
                    onConfirmed: reloadRecoveryCodes,
                });
            });
        }

        if (logoutOtherSessionsBtn) {
            logoutOtherSessionsBtn.addEventListener('click', function() {
                requirePasswordConfirmation({
                    title: 'Logout Perangkat Lain',
                    text: 'Masukkan password admin untuk mengeluarkan sesi di perangkat lain.',
                    onConfirmed: logoutOtherSessions,
                });
            });
        }
    });
</script>
@endpush
