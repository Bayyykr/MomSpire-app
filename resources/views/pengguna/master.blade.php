<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>@yield('title', config('app.name', 'MomSpire'))</title>
	@php
		$currentUser = auth()->user();
		$currentUserPayload = $currentUser ? [
			'id' => $currentUser->id,
			'nama' => $currentUser->name,
			'email' => $currentUser->email,
			'role' => $currentUser->role,
			'no_telp' => $currentUser->no_telp ?? '',
		] : null;
	@endphp
	<script>
		window.MOMSPIRE_CURRENT_USER = @json($currentUserPayload);
	</script>

	<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
	<link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
	@vite(['resources/css/style.css', 'resources/js/script.js'])
	<style>
		:root{ --muted:#6b7280; --accent1:#6d28d9; --accent2:#ec4899; --accent3:#ff8c42; }
		body.admin-body{ font-family: 'Plus Jakarta Sans', system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial; background: #f6f9fc; color:#0f172a; }
		.role-hero{ background: linear-gradient(135deg, var(--accent1) 0%, var(--accent3) 100%); color:#fff; border-radius:20px; }
		.role-card{ border:0; border-radius:16px; box-shadow: 0 12px 30px rgba(2,6,23,.06); }

		/* Layout */
		.admin-sidebar{ width:280px; position:fixed; left:0; top:0; bottom:0; background:linear-gradient(180deg,#fff,#fbfbfd); padding:28px 18px; border-right:1px solid rgba(15,23,42,.04); box-shadow:8px 0 30px rgba(15,23,42,.03); z-index:1000; transition:transform .32s cubic-bezier(.2,.9,.2,1); }
		.admin-main{ margin-left:280px; transition:margin-left .32s cubic-bezier(.2,.9,.2,1); padding:28px 40px; min-height:100vh; }
		.admin-header{ display:flex; align-items:center; gap:18px; padding:8px 0 16px; }

		/* Nudge the sidebar toggle slightly to the right for balanced spacing */
		.admin-header .btn-toggle-sidebar { margin-right: 7px; transform: translateX(7px); }
		.header-title{ font-size:20px; font-weight:700; margin:0; color:#0f172a; }
		.header-subtitle{ margin:0; color:var(--muted); font-size:13px; }

		/* Cards and actions */
		.action-card, .service-card, .content-card{ background:#fff; border-radius:14px; padding:18px; box-shadow:0 12px 30px rgba(2,6,23,.06); transition:transform .18s ease, box-shadow .18s ease; }
		.action-card:hover, .service-card:hover{ transform:translateY(-6px); box-shadow:0 18px 40px rgba(2,6,23,.09); }

		/* Gradient text helper */
		.text-gradient{ background:linear-gradient(90deg,var(--accent1),var(--accent2)); -webkit-background-clip:text; background-clip:text; color:transparent; }

		/* Buttons */
		.btn-primary, .btn-add{ border-radius:12px; padding:10px 16px; }

		/* Small helpers */
		.muted-small{ color:var(--muted); }

		/* Responsive */
		@media (max-width:991px){ .admin-main{ margin-left:0; padding:18px; } .admin-sidebar{ transform:translateX(-100%);} }
	</style>
	@stack('head')
</head>
<body class="admin-body">

	<aside class="admin-sidebar" id="adminSidebar">
		<div class="sidebar-header">
			<a href="{{ url('/') }}" class="sidebar-brand">
				<img src="{{ asset('foto/logo.jpg') }}" alt="Logo MomSpire" class="brand-logo brand-logo-image">
				<span>MomSpire</span>
			</a>
			<span class="sidebar-tag">Pengguna Panel</span>
		</div>
		<nav class="sidebar-nav">
			<div class="nav-section">
				<span class="nav-label">MENU UTAMA</span>
				<a href="{{ route('pengguna.dashboard') }}" class="nav-link {{ request()->routeIs('pengguna.dashboard') ? 'active' : '' }}">
					<i class="bi bi-grid-1x2-fill"></i>
					<span>Dashboard</span>
				</a>
				<a href="{{ route('pengguna.artikel') }}" class="nav-link {{ request()->routeIs('pengguna.artikel') ? 'active' : '' }}">
					<i class="bi bi-journal-text"></i>
					<span>Artikel Edukasi</span>
				</a>
				<a href="{{ route('pengguna.konsultasi') }}" class="nav-link {{ request()->routeIs('pengguna.konsultasi') ? 'active' : '' }}">
					<i class="bi bi-chat-dots-fill"></i>
					<span>Konsultasi</span>
				</a>
			</div>
			<div class="nav-section">
				<span class="nav-label">SISTEM</span>
				<a href="{{ route('pengguna.pengaturan') }}" class="nav-link {{ request()->routeIs('pengguna.pengaturan') ? 'active' : '' }}">
					<i class="bi bi-gear-fill"></i>
					<span>Pengaturan</span>
				</a>
			</div>
		</nav>
		<div class="sidebar-footer">
			<a href="#" id="logoutBtn" class="btn btn-logout" data-bs-toggle="modal" data-bs-target="#logoutModal">
				<i class="bi bi-box-arrow-left"></i>
				<span>Keluar</span>
			</a>
		</div>
	</aside>

	<main class="admin-main">
		<header class="admin-header">
			<div class="header-left">
				@if (View::hasSection('header_action'))
					@yield('header_action')
				@else
					<button type="button" class="btn btn-toggle-sidebar" id="toggleSidebar" onclick="return window.__momspireToggleSidebar(event)">
						<i class="bi bi-list"></i>
					</button>
				@endif
				<div>
					<h1 class="header-title">@yield('header_title')</h1>
					<p class="header-subtitle">@yield('header_subtitle')</p>
				</div>
			</div>
		</header>

		<div class="admin-content">
			@yield('content')
		</div>
	</main>

	<div class="modal fade" id="logoutModal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Konfirmasi Keluar</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
				</div>
				<div class="modal-body">
					<p>Anda akan keluar dari akun. Apakah yakin?</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
					<button type="button" id="logoutConfirmBtn" class="btn btn-danger">Keluar</button>
				</div>
			</div>
		</div>
	</div>

	<form id="adminLogoutForm" method="POST" action="{{ route('logout') }}" class="d-none">
		@csrf
	</form>

	<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1100;">
		<div id="adminToast" class="toast align-items-center text-bg-dark border-0" role="alert">
			<div class="d-flex">
				<div class="toast-body">Berhasil!</div>
				<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
			</div>
		</div>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
	<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
	<script>
		window.MOMSPIRE_USE_LEGACY_ADMIN_API = false;
		window.__momspireSidebarOpen = window.__MOMSPIRE_SIDEBAR_OPEN !== undefined ? !!window.__MOMSPIRE_SIDEBAR_OPEN : true;

		window.__momspireSyncSidebar = function() {
			const sidebar = document.getElementById('adminSidebar');
			const main = document.querySelector('.admin-main');
			if (!sidebar) return;

			const isMobile = window.matchMedia('(max-width: 991px)').matches;

			if (isMobile) {
				sidebar.style.transform = window.__momspireSidebarOpen ? 'translateX(0)' : 'translateX(-100%)';
				if (main) main.style.marginLeft = '0';
				return;
			}

			sidebar.style.transform = window.__momspireSidebarOpen ? 'translateX(0)' : 'translateX(-100%)';
			if (main) main.style.marginLeft = window.__momspireSidebarOpen ? '280px' : '0';
		};

		window.__momspireToggleSidebar = function(event) {
			if (event) {
				event.preventDefault();
				event.stopImmediatePropagation();
			}

			const sidebar = document.getElementById('adminSidebar');
			if (!sidebar) return false;

			window.__momspireSidebarOpen = !window.__momspireSidebarOpen;
			window.__momspireSyncSidebar();
			return false;
		};

		document.addEventListener('DOMContentLoaded', function() {
			window.__momspireSyncSidebar();
			window.addEventListener('resize', window.__momspireSyncSidebar);

			// Init AOS for subtle entrance animations
			if (window.AOS) {
				AOS.init({ duration: 650, easing: 'ease-out-cubic', once: true, mirror: false });
			}

			// Logout handler
			const logoutConfirmBtn = document.getElementById('logoutConfirmBtn');
			if (logoutConfirmBtn) {
				logoutConfirmBtn.addEventListener('click', function(){
					document.getElementById('adminLogoutForm').submit();
				});
			}

			// Micro-interactions for nav links
			document.querySelectorAll('.sidebar-nav .nav-link').forEach(function(el){
				el.addEventListener('mouseenter', function(){ el.classList.add('hovered'); });
				el.addEventListener('mouseleave', function(){ el.classList.remove('hovered'); });
			});
		});
	</script>
	@stack('scripts')
</body>
</html>