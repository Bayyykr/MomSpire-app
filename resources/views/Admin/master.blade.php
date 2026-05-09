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
	@vite(['resources/css/style.css', 'resources/js/script.js'])
	<style>
		/* Mobile-first responsive improvements for admin layout */
		@media (max-width: 991.98px) {
			.admin-sidebar {
				width: 250px !important;
				z-index: 1050;
				box-shadow: 4px 0 20px rgba(15, 23, 42, 0.1);
			}
			.admin-main {
				margin-left: 0 !important;
				padding: 16px !important;
			}
			.admin-header {
				gap: 1rem !important;
				flex-wrap: wrap;
			}
			.header-title {
				font-size: clamp(1.25rem, 4vw, 1.5rem) !important;
			}
		}
		@media (max-width: 576px) {
			.admin-sidebar {
				width: 240px !important;
				padding: 16px 12px !important;
			}
			.admin-main {
				padding: 12px !important;
			}
			.admin-content {
				font-size: 0.95rem;
			}
			.stat-card {
				padding: 12px !important;
			}
			.admin-card {
				border-radius: 12px;
			}
			.card-header {
				flex-direction: column;
				align-items: flex-start;
				gap: 12px;
			}
			.card-header h5 {
				margin-bottom: 0;
			}
		}
		.admin-sidebar {
			transition: transform 0.32s cubic-bezier(0.2, 0.9, 0.2, 1), box-shadow 0.32s ease;
		}
		.admin-main {
			transition: margin-left 0.32s cubic-bezier(0.2, 0.9, 0.2, 1);
		}
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
			<span class="sidebar-tag">Admin Panel</span>
		</div>
		<nav class="sidebar-nav">
			<div class="nav-section">
				<span class="nav-label">MENU UTAMA</span>
				<a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
					<i class="bi bi-grid-1x2-fill"></i>
					<span>Dashboard</span>
				</a>
				<a href="{{ route('admin.users') }}" class="nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}">
					<i class="bi bi-people-fill"></i>
					<span>Manajemen User</span>
				</a>
				<a href="{{ route('admin.articles') }}" class="nav-link {{ request()->routeIs('admin.articles') ? 'active' : '' }}">
					<i class="bi bi-journal-text"></i>
					<span>Manajemen Artikel</span>
				</a>
				<a href="{{ route('admin.kia') }}" class="nav-link {{ request()->routeIs('admin.kia') || request()->routeIs('admin.kia.pdf') ? 'active' : '' }}">
					<i class="bi bi-book-fill"></i>
					<span>Data Buku KIA</span>
				</a>
			</div>
			<div class="nav-section">
				<span class="nav-label">SISTEM</span>
				<a href="{{ route('admin.settings') }}" class="nav-link {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
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
				<button type="button" class="btn btn-toggle-sidebar" id="toggleSidebar" onclick="return window.__momspireToggleSidebar(event)">
					<i class="bi bi-list"></i>
				</button>
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

	{{-- Common Modals & Toasts --}}

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
	<script>
		window.MOMSPIRE_USE_LEGACY_ADMIN_API = false;
		window.__momspireSidebarOpen = true;

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
		});
	</script>
	@stack('scripts')
</body>
</html>