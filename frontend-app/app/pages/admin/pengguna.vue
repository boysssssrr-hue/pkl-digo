<template>
	<div class="dashboard" :class="[isDark ? 'theme-dark' : 'theme-light']">
		<!-- Global SVG Gradient Definitions -->
		<svg style="display: none;">
			<defs>
				<linearGradient id="gradient-lightning" x1="0%" y1="0%" x2="100%" y2="100%">
					<stop offset="0%" stop-color="#ff7a00" />
					<stop offset="100%" stop-color="#ffb800" />
				</linearGradient>
			</defs>
		</svg>

		<!-- Sidebar Navigation -->
		<aside class="sidebar" :class="{ open: menuOpen }">
			<div class="brand">
				<span class="brand-logo-diamond">
					<img src="/images/logo-simpel-icon.png" alt="Logo SIMPEL" style="width: 32px; height: 32px; object-fit: contain;" />
				</span>
				<div>
					<b class="brand-title font-brand text-emerald-400">SIMPEL</b>
					<small>Sistem Informasi Manajemen Pelayanan</small>
				</div>
			</div>

			<nav>
				<a v-for="item in menus" :key="item.name" href="#" :class="{ active: item.active }" @click.prevent="activate(item)">
					<i class="nav-svg-box" v-html="item.iconSvg"></i>
					<span>{{ item.name }}</span>
				</a>
			</nav>

			<div class="account" role="button" tabindex="0" @click="router.push('/admin/profil')">
				<div class="user-avatar">A</div>
				<div>
					<b>Admin</b>
					<small>Administrator</small>
				</div>
				<button type="button" @click.stop="router.push('/')" title="Kembali">
					<svg viewBox="0 0 24 24" class="svg-icon-sm" fill="currentColor"><path d="M10 9V5l-7 7 7 7v-4.1c5 0 8.5 1.6 11 5.1-1-5-4-10-11-11z"/></svg>
				</button>
			</div>
			<button class="logout" type="button" @click="router.push('/')">
				<svg viewBox="0 0 24 24" class="svg-icon-sm" fill="currentColor"><path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/></svg>
				<span>Logout</span>
			</button>
		</aside>

		<!-- Main Content Area -->
		<main class="main">
			<!-- Topbar -->
			<header class="topbar">
				<button class="mobile-menu" type="button" @click="menuOpen = !menuOpen">☰</button>
				<div class="global-search">
					<span class="search-lens">
						<svg viewBox="0 0 24 24" class="svg-icon-sm" fill="currentColor"><path d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
					</span>
					<input v-model="searchQuery" placeholder="Cari nama pengguna, NIP/username, atau divisi..." />
				</div>

				<div class="top-user">
					<!-- Realtime Live Date & Clock -->
					<RealtimeClock :theme="isDark ? 'dark' : 'light'" variant="admin" class="hidden sm:inline-flex" />

					<!-- Theme Mode Toggle (Gelap / Terang) -->
					<button
						type="button"
						class="theme-toggle-btn"
						:title="isDark ? 'Beralih ke Mode Terang' : 'Beralih ke Mode Gelap'"
						@click="toggleTheme"
					>
						<span class="theme-icon sun" v-if="!isDark">
							<svg viewBox="0 0 24 24" class="svg-icon-theme" fill="currentColor">
								<path d="M12 7c-2.76 0-5 2.24-5 5s2.24 5 5 5 5-2.24 5-5-2.24-5-5-5zM2 13h2c.55 0 1-.45 1-1s-.45-1-1-1H2c-.55 0-1 .45-1 1s.45 1 1 1zm18 0h2c.55 0 1-.45 1-1s-.45-1-1-1h-2c-.55 0-1 .45-1 1s.45 1 1 1zM11 2v2c0 .55.45 1 1 1s1-.45 1-1V2c0-.55-.45-1-1-1s-1 .45-1 1zm0 18v2c0 .55.45 1 1 1s1-.45 1-1v-2c0-.55-.45-1-1-1s-1 .45-1 1zM5.99 4.58a.996.996 0 0 0-1.41 0 .996.996 0 0 0 0 1.41l1.06 1.06c.39.39 1.03.39 1.41 0s.39-1.03 0-1.41L5.99 4.58zm12.37 12.37a.996.996 0 0 0-1.41 0 .996.996 0 0 0 0 1.41l1.06 1.06c.39.39 1.03.39 1.41 0s.39-1.03 0-1.41l-1.06-1.06zm1.06-10.96a.996.996 0 0 0 0-1.41.996.996 0 0 0-1.41 0l-1.06 1.06c-.39.39-.39 1.03 0 1.41s1.03.39 1.41 0l1.06-1.06zM7.05 18.36a.996.996 0 0 0 0-1.41.996.996 0 0 0 0-1.41l-1.06 1.06c-.39.39-.39 1.03 0 1.41s1.03.39 1.41 0l1.06-1.06z"/>
							</svg>
						</span>
						<span class="theme-icon moon" v-else>
							<svg viewBox="0 0 24 24" class="svg-icon-theme" fill="currentColor">
								<path d="M12.3 2a10 10 0 0 0-1.9 19.8 10 10 0 0 0 10.9-10.9A10 10 0 0 0 12.3 2z"/>
							</svg>
						</span>
						<span class="theme-label">{{ isDark ? 'Gelap' : 'Terang' }}</span>
					</button>

					<span class="bell-btn" title="Notifikasi Sistem">
						<svg viewBox="0 0 24 24" class="svg-icon-bell" fill="currentColor">
							<path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.63-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.64 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/>
						</svg>
					</span>
					<span class="user-dot">A</span>
					<b>Admin</b>
					<span class="chevron-down">▾</span>
				</div>
			</header>

			<!-- Page Body -->
			<div class="page-content">
				<!-- Toast Alert Notification -->
				<transition name="toast-fade">
					<div v-if="toast.show" class="toast-banner" :class="toast.type">
						<span class="toast-icon">
							<svg v-if="toast.type === 'success'" viewBox="0 0 24 24" class="svg-icon-check" fill="currentColor">
								<path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
							</svg>
							<svg v-else viewBox="0 0 24 24" class="svg-icon-check" fill="currentColor">
								<path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
							</svg>
						</span>
						<span class="toast-message">{{ toast.message }}</span>
						<button class="toast-close" type="button" @click="toast.show = false">✕</button>
					</div>
				</transition>

				<!-- Hero Header Section -->
				<section class="hero">
					<div>
						<p>Kontrol Hak Akses & Kredensial Pengguna <em>✦</em></p>
						<h1>Manajemen Akun Pengguna</h1>
						<span>Pantau daftar akun terdaftar di sistem. Anda dapat melihat kata sandi pengguna saat ini, memperbarui password jika diperlukan, atau menghapus akun nonaktif secara aman.</span>
					</div>
					<div class="hero-art">
						<svg viewBox="0 0 24 24" class="art-svg" fill="currentColor">
							<path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
						</svg>
					</div>
				</section>

				<!-- Summary Stats Grid -->
				<section class="summary">
					<article>
						<span class="stat-icon green">
							<svg viewBox="0 0 24 24" class="svg-icon-stat" fill="currentColor">
								<path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
							</svg>
						</span>
						<div>
							<small>Total Seluruh Pengguna</small>
							<strong>{{ users.length }} Akun</strong>
							<small class="text-green">Tersimpan di Database</small>
						</div>
					</article>

					<article>
						<span class="stat-icon amber">
							<svg viewBox="0 0 24 24" class="svg-icon-stat" fill="currentColor">
								<path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8z"/>
							</svg>
						</span>
						<div>
							<small>Akun Administrator</small>
							<strong>{{ adminCount }} Admin</strong>
							<small class="text-amber">Hak Akses Penuh</small>
						</div>
					</article>

					<article>
						<span class="stat-icon cyan">
							<svg viewBox="0 0 24 24" class="svg-icon-stat" fill="currentColor">
								<path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
							</svg>
						</span>
						<div>
							<small>Akun Pegawai / User</small>
							<strong>{{ userCount }} Pengguna</strong>
							<small class="text-cyan">Peminjam Fasilitas</small>
						</div>
					</article>

					<article>
						<span class="stat-icon purple">
							<svg viewBox="0 0 24 24" class="svg-icon-stat" fill="currentColor">
								<path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
							</svg>
						</span>
						<div>
							<small>Akun Berstatus Aktif</small>
							<strong>{{ activeCount }} Akun</strong>
							<small class="text-purple">Dapat Login Normal</small>
						</div>
					</article>
				</section>

				<!-- CORE USERS TABLE PANEL -->
				<section class="crypto-panel">
					<div class="crypto-header">
						<div class="header-left">
							<div class="title-with-badge">
								<h2>Tabel Data Pengguna</h2>
								<span class="live-indicator">
									<svg viewBox="0 0 24 24" class="svg-icon-bolt">
										<path d="M12 2L2 12l10 10 10-10L12 2zm1 14.5l-4-5.5h3.2V6.5l4 5.5h-3.2v4.5z" fill="url(#gradient-lightning)"/>
									</svg>
									Database Terhubung
								</span>
							</div>
							<p class="subtitle">Sesuai kebijakan keamanan, <b>Nama Lengkap</b> dan <b>NPP/Username</b> bersifat paten. Pada menu edit, Admin dapat melihat kata sandi lama dan mengubah password baru di bawahnya.</p>
						</div>

						<div class="crypto-actions-top">
							<button class="btn-refresh" type="button" @click="openCreateUserModal" title="Tambah Pengguna Baru">
								<svg viewBox="0 0 24 24" class="svg-icon-sync" fill="currentColor">
									<path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
								</svg>
								<span>Tambah Pengguna</span>
							</button>
							<button class="btn-refresh" type="button" @click="fetchUsers" :disabled="isLoading" title="Muat Ulang Pengguna">
								<svg viewBox="0 0 24 24" class="svg-icon-sync" :class="{ spinning: isLoading }" fill="currentColor">
									<path d="M12 4V1L8 5l4 4V6c3.31 0 6 2.69 6 6 0 1.01-.25 1.97-.7 2.8l1.46 1.46A7.93 7.93 0 0 0 20 12c0-4.42-3.58-8-8-8zm0 14c-3.31 0-6-2.69-6-6 0-1.01.25-1.97.7-2.8L5.24 7.74A7.93 7.93 0 0 0 4 12c0 4.42 3.58 8 8 8v3l4-4-4-4v3z"/>
								</svg>
								<span>Sinkron Pengguna</span>
							</button>
						</div>
					</div>

					<!-- Table Search & Filter Bar -->
					<div class="table-filters-row">
						<div class="search-box-wrap">
							<span class="search-icon">
								<svg viewBox="0 0 24 24" class="svg-icon-sm" fill="currentColor"><path d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
							</span>
							<input v-model="filterText" placeholder="Cari nama, NIP, atau unit kerja..." />
						</div>

						<div class="filters-right">
							<div class="select-group">
								<label>Role:</label>
								<select v-model="roleFilter">
									<option value="Semua">Semua Role</option>
									<option value="admin">Administrator</option>
									<option value="user">Pegawai / User</option>
									<option value="sopir">Pengemudi / Driver</option>
									<option value="ob">Office Boy (OB)</option>
								</select>
							</div>

							<div class="select-group">
								<label>Status:</label>
								<select v-model="statusFilter">
									<option value="Semua">Semua Status</option>
									<option value="active">Aktif</option>
									<option value="inactive">Nonaktif</option>
								</select>
							</div>
						</div>
					</div>

					<!-- Table Wrapper -->
					<div class="crypto-table-wrap">
						<table class="crypto-table">
							<thead>
								<tr>
									<th class="th-num">#</th>
									<th>Pengguna (Nama & NPP/Username)</th>
									<th>Role / Hak Akses</th>
									<th>Divisi / Unit Kerja</th>
									<th>Status Akun</th>
									<th>Terdaftar Sejak</th>
									<th class="th-actions">Aksi</th>
								</tr>
							</thead>

							<tbody>
								<tr v-if="isLoading">
									<td :colspan="7" class="empty-cell">
										<div class="loading-state">
											<span class="spinner"></span>
											<p>Menghubungkan ke database dan memuat daftar pengguna...</p>
										</div>
									</td>
								</tr>

								<tr v-else-if="filteredUsers.length === 0">
									<td :colspan="7" class="empty-cell">
										<div class="empty-state">
											<span class="empty-icon-box">
												<svg viewBox="0 0 24 24" class="svg-icon-empty" fill="currentColor">
													<path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
												</svg>
											</span>
											<h4>Tidak Ada Data Pengguna</h4>
											<p>Tidak ditemukan data pengguna yang cocok dengan filter atau kata kunci pencarian Anda.</p>
										</div>
									</td>
								</tr>

								<tr v-else v-for="(user, idx) in filteredUsers" :key="user.id" class="table-row">
									<td class="td-num">{{ idx + 1 }}</td>
									<td>
										<div class="user-cell">
											<div class="user-avatar-circle" :class="user.role === 'admin' ? 'bg-admin' : (user.role === 'sopir' ? 'bg-sopir' : (user.role === 'ob' ? 'bg-ob' : 'bg-user'))">
												{{ (user.name || 'U').charAt(0).toUpperCase() }}
											</div>
											<div>
												<strong class="user-fullname">{{ user.name }}</strong>
												<div class="user-nip-badge">
													<span class="nip-label">NPP / User:</span>
													<span class="nip-val">{{ user.nip }}</span>
												</div>
											</div>
										</div>
									</td>
									<td>
										<span class="role-badge" :class="user.role === 'admin' ? 'role-admin' : (user.role === 'sopir' ? 'role-sopir' : (user.role === 'ob' ? 'role-ob' : 'role-user'))">
											<svg v-if="user.role === 'admin'" viewBox="0 0 24 24" class="svg-icon-xs" fill="currentColor">
												<path d="M5 16L3 5l5.5 5L12 4l3.5 6L21 5l-2 11H5zm14 3c0 .55-.45 1-1 1H6c-.55 0-1-.45-1-1v-1h14v1z"/>
											</svg>
											<svg v-else-if="user.role === 'sopir'" viewBox="0 0 24 24" class="svg-icon-xs" fill="currentColor">
												<path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.85 7h10.29l1.04 3H5.81l1.04-3zM19 17H5v-4.66l.12-.34h13.77l.11.34V17z"/>
												<circle cx="7.5" cy="14.5" r="1.5"/>
												<circle cx="16.5" cy="14.5" r="1.5"/>
											</svg>
											<svg v-else-if="user.role === 'ob'" viewBox="0 0 24 24" class="svg-icon-xs" fill="currentColor">
												<path d="M19.36 10.04C18.67 6.59 15.64 4 12 4 9.11 4 6.6 5.64 5.35 8.04 2.34 8.36 0 10.91 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.64-4.96zM19 18H6c-2.21 0-4-1.79-4-4 0-2.05 1.53-3.76 3.56-3.97l1.07-.11.5-.95C8.08 7.14 9.94 6 12 6c2.62 0 4.88 1.86 5.39 4.43l.3 1.5 1.53.11c1.56.1 2.78 1.41 2.78 2.96 0 1.65-1.35 3-3 3z"/>
											</svg>
											<svg v-else viewBox="0 0 24 24" class="svg-icon-xs" fill="currentColor">
												<path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
											</svg>
											{{ user.role === 'admin' ? 'Administrator' : (user.role === 'sopir' ? 'Pengemudi / Driver' : (user.role === 'ob' ? 'Office Boy (OB)' : 'Pegawai')) }}
										</span>
									</td>
									<td>
										<span class="division-badge" v-if="user.division">
											<svg viewBox="0 0 24 24" class="svg-icon-building" fill="currentColor">
												<path d="M12 7V3H2v18h20V7H12zM6 19H4v-2h2v2zm0-4H4v-2h2v2zm0-4H4V9h2v2zm0-4H4V5h2v2zm4 12H8v-2h2v2zm0-4H8v-2h2v2zm0-4H8V9h2v2zm0-4H8V5h2v2zm10 12h-8v-2h2v-2h-2v-2h2v-2h-2V9h8v10zm-2-8h-2v2h2v-2zm0 4h-2v2h2v-2z"/>
											</svg>
											{{ user.division.name }}
										</span>
										<span class="division-badge text-muted" v-else>
											<svg viewBox="0 0 24 24" class="svg-icon-building" fill="currentColor">
												<path d="M12 7V3H2v18h20V7H12zM6 19H4v-2h2v2zm0-4H4v-2h2v2zm0-4H4V9h2v2zm0-4H4V5h2v2zm4 12H8v-2h2v2zm0-4H8v-2h2v2zm0-4H8V9h2v2zm0-4H8V5h2v2zm10 12h-8v-2h2v-2h-2v-2h2v-2h-2V9h8v10zm-2-8h-2v2h2v-2zm0 4h-2v2h2v-2z"/>
											</svg>
											Kantor Pusat / Manajemen
										</span>
									</td>
									<td>
										<span class="status-badge" :class="user.status === 'active' ? 'status-green' : 'status-red'">
											<i class="status-dot"></i> {{ user.status === 'active' ? 'Aktif' : 'Nonaktif' }}
										</span>
									</td>
									<td>
										<span class="date-text">{{ formatDate(user.created_at) }}</span>
									</td>
									<td class="td-actions">
										<div class="action-btn-group">
											<!-- Tombol Simbol: Ubah Role -->
											<button
												v-if="user.nip !== '12345'"
												class="btn-action-symbol role-btn"
												type="button"
												@click="openRoleModal(user)"
												title="Ubah Role & Hak Akses"
												aria-label="Ubah Role"
											>
												<svg viewBox="0 0 24 24" class="svg-icon-symbol" fill="currentColor">
													<path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/>
												</svg>
											</button>

											<!-- Tombol Simbol: Lihat & Edit Password -->
											<button
												class="btn-action-symbol password-btn"
												type="button"
												@click="openPasswordModal(user)"
												title="Lihat Password Lama & Ubah Password Baru"
												aria-label="Kelola Password"
											>
												<svg viewBox="0 0 24 24" class="svg-icon-symbol" fill="currentColor">
													<path d="M12.65 10C11.83 7.67 9.61 6 7 6c-3.31 0-6 2.69-6 6s2.69 6 6 6c2.61 0 4.83-1.67 5.65-4H17v4h4v-4h2v-4H12.65zM7 14c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2z"/>
												</svg>
											</button>

											<!-- Tombol Simbol: Hapus Pengguna -->
											<button
												v-if="user.nip !== '12345' && user.role !== 'admin'"
												class="btn-action-symbol delete-btn"
												type="button"
												@click="openDeleteModal(user)"
												title="Hapus Akun Pengguna"
												aria-label="Hapus Pengguna"
											>
												<svg viewBox="0 0 24 24" class="svg-icon-symbol" fill="currentColor">
													<path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
												</svg>
											</button>

											<!-- Admin Utama Dilindungi (Simbol Perisai) -->
											<span v-else class="protected-symbol" title="Akun Administrator utama dilindungi sistem">
												<svg viewBox="0 0 24 24" class="svg-icon-symbol" fill="currentColor">
													<path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm-2 16l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z"/>
												</svg>
											</span>
										</div>
									</td>
								</tr>
							</tbody>
						</table>
					</div>

					<!-- Table Footer Info -->
					<div class="crypto-footer">
						<div class="footer-info">
							Menampilkan: <b>{{ filteredUsers.length }}</b> dari <b>{{ users.length }}</b> total pengguna
						</div>
						<div class="footer-db-status">
							<span class="status-dot-green"></span> Database Server Pengguna Terhubung
						</div>
					</div>
				</section>
			</div>
		</main>

		<!-- ================================================================= -->
		<!-- MODAL EDIT PASSWORD PENGGUNA (LIHAT PASSWORD LAMA & UBAH DI BAWAH)-->
		<!-- ================================================================= -->
		<div v-if="showPasswordModal" class="crypto-modal-backdrop" @click.self="closePasswordModal">
			<div class="crypto-modal">
				<div class="modal-top">
					<div>
						<div class="modal-title-row">
							<span class="modal-key-icon">
								<svg viewBox="0 0 24 24" class="svg-icon-modal" fill="currentColor">
									<path d="M12.65 10C11.83 7.67 9.61 6 7 6c-3.31 0-6 2.69-6 6s2.69 6 6 6c2.61 0 4.83-1.67 5.65-4H17v4h4v-4h2v-4H12.65zM7 14c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2z"/>
								</svg>
							</span>
							<h3>Kelola Password Pengguna</h3>
						</div>
						<p>Nama dan NIP/Username bersifat paten. Anda dapat memeriksa kata sandi pengguna saat ini, atau membuat password baru di bawahnya.</p>
					</div>
					<button class="modal-close-btn" type="button" @click="closePasswordModal">✕</button>
				</div>

				<form @submit.prevent="submitChangePassword" class="modal-body-form">
					<div class="form-grid">
						<!-- 1. NAMA PENGGUNA (PATEN / READONLY) -->
						<div class="form-field-locked">
							<label>
								<span>Nama Lengkap Pengguna</span>
								<span class="lock-tag">
									<svg viewBox="0 0 24 24" class="svg-icon-locked" fill="currentColor"><path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/></svg>
									Paten (Terkunci)
								</span>
							</label>
							<div class="input-locked-wrap">
								<input type="text" :value="selectedUser?.name" readonly disabled class="input-locked" />
							</div>
						</div>

						<!-- 2. NIP / USERNAME (PATEN / READONLY) -->
						<div class="form-field-locked">
							<label>
								<span>NPP / Username Login</span>
								<span class="lock-tag">
									<svg viewBox="0 0 24 24" class="svg-icon-locked" fill="currentColor"><path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/></svg>
									Paten (Terkunci)
								</span>
							</label>
							<div class="input-locked-wrap">
								<input type="text" :value="selectedUser?.nip" readonly disabled class="input-locked font-mono" />
							</div>
						</div>

						<!-- ================================================= -->
						<!-- BAGIAN 1: LIHAT PASSWORD LAMA (SAAT INI)          -->
						<!-- ================================================= -->
						<div class="form-field-full old-password-box">
							<div class="old-password-header">
								<div class="label-with-icon">
									<svg viewBox="0 0 24 24" class="svg-icon-xs text-amber" fill="currentColor">
										<path d="M12.65 10C11.83 7.67 9.61 6 7 6c-3.31 0-6 2.69-6 6s2.69 6 6 6c2.61 0 4.83-1.67 5.65-4H17v4h4v-4h2v-4H12.65zM7 14c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2z"/>
									</svg>
									<strong>Kata Sandi Lama (Saat Ini Digunakan)</strong>
								</div>
								<span class="badge-status-current">Aktif</span>
							</div>

							<div class="old-password-input-group">
								<div class="old-password-display">
									<span v-if="showOldPassword" class="font-mono text-bold text-highlight">
										{{ selectedUser?.visible_password || '12345678' }}
									</span>
									<span v-else class="font-mono text-muted">
										••••••••••••
									</span>
								</div>

								<!-- Tombol Intip Password Lama -->
								<button
									type="button"
									class="btn-icon-control"
									@click="showOldPassword = !showOldPassword"
									:title="showOldPassword ? 'Sembunyikan Kata Sandi Lama' : 'Tampilkan Kata Sandi Lama'"
								>
									<!-- Eye Icon -->
									<svg v-if="!showOldPassword" viewBox="0 0 24 24" class="svg-icon-eye" fill="currentColor">
										<path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
									</svg>
									<!-- Eye-Off Icon -->
									<svg v-else viewBox="0 0 24 24" class="svg-icon-eye" fill="currentColor">
										<path d="M12 7c2.76 0 5 2.24 5 5 0 .65-.13 1.26-.36 1.83l2.92 2.92c1.51-1.26 2.7-2.89 3.44-4.75-1.73-4.39-6-7.5-11-7.5-1.4 0-2.74.25-3.98.7l2.16 2.16C10.74 7.13 11.35 7 12 7zM2 4.27l2.28 2.28.46.46C3.08 8.3 1.78 10.02 1 12c1.73 4.39 6 7.5 11 7.5 1.55 0 3.03-.3 4.38-.84l.42.42L19.73 22 21 20.73 3.27 3 2 4.27zM7.53 9.8l1.55 1.55c-.05.21-.08.43-.08.65 0 1.66 1.34 3 3 3 .22 0 .44-.03.65-.08l1.55 1.55c-.67.33-1.41.53-2.2.53-2.76 0-5-2.24-5-5 0-.79.2-1.53.53-2.2zm4.31-.78l3.15 3.15.02-.16c0-1.66-1.34-3-3-3l-.17.01z"/>
									</svg>
									<span>{{ showOldPassword ? 'Tutup' : 'Lihat' }}</span>
								</button>

								<!-- Tombol Salin Password Lama -->
								<button
									type="button"
									class="btn-icon-control"
									@click="copyOldPassword"
									title="Salin Kata Sandi ke Clipboard"
								>
									<svg v-if="!copiedOldPassword" viewBox="0 0 24 24" class="svg-icon-copy" fill="currentColor">
										<path d="M16 1H4c-1.1 0-2 .9-2 2v14h2V3h12V1zm3 4H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 16H8V7h11v14z"/>
									</svg>
									<svg v-else viewBox="0 0 24 24" class="svg-icon-check text-green" fill="currentColor">
										<path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
									</svg>
									<span>{{ copiedOldPassword ? 'Tersalin!' : 'Salin' }}</span>
								</button>
							</div>
							<small class="field-hint">Kredensial aktif pengguna saat ini. Jika tidak ingin mengubah password, Anda dapat langsung menutup dialog ini.</small>
						</div>

						<!-- ================================================= -->
						<!-- BAGIAN 2: GANTI KATA SANDI BARU (DI BAWAHNYA)     -->
						<!-- ================================================= -->
						<div class="divider-form form-field-full">
							<div class="divider-content">
								<svg viewBox="0 0 24 24" class="svg-icon-xs text-green" fill="currentColor">
									<path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
								</svg>
								<span>Ubah Kata Sandi Baru (Di Bawah Ini)</span>
							</div>
						</div>

						<!-- INPUT PASSWORD BARU -->
						<div class="form-field-full">
							<label>
								<span>Password Baru</span>
							</label>
							<div class="password-input-wrap">
								<input
									:type="showNewPassword ? 'text' : 'password'"
									v-model="passwordForm.newPassword"
									minlength="6"
									placeholder="Ketik kata sandi baru (minimal 6 karakter)..."
									class="input-password"
								/>
								<button
									type="button"
									class="btn-toggle-eye"
									@click="showNewPassword = !showNewPassword"
									:title="showNewPassword ? 'Sembunyikan' : 'Tampilkan'"
								>
									<svg v-if="!showNewPassword" viewBox="0 0 24 24" class="svg-icon-eye" fill="currentColor">
										<path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
									</svg>
									<svg v-else viewBox="0 0 24 24" class="svg-icon-eye" fill="currentColor">
										<path d="M12 7c2.76 0 5 2.24 5 5 0 .65-.13 1.26-.36 1.83l2.92 2.92c1.51-1.26 2.7-2.89 3.44-4.75-1.73-4.39-6-7.5-11-7.5-1.4 0-2.74.25-3.98.7l2.16 2.16C10.74 7.13 11.35 7 12 7zM2 4.27l2.28 2.28.46.46C3.08 8.3 1.78 10.02 1 12c1.73 4.39 6 7.5 11 7.5 1.55 0 3.03-.3 4.38-.84l.42.42L19.73 22 21 20.73 3.27 3 2 4.27zM7.53 9.8l1.55 1.55c-.05.21-.08.43-.08.65 0 1.66 1.34 3 3 3 .22 0 .44-.03.65-.08l1.55 1.55c-.67.33-1.41.53-2.2.53-2.76 0-5-2.24-5-5 0-.79.2-1.53.53-2.2zm4.31-.78l3.15 3.15.02-.16c0-1.66-1.34-3-3-3l-.17.01z"/>
									</svg>
								</button>
							</div>
						</div>

						<!-- INPUT KONFIRMASI PASSWORD BARU -->
						<div class="form-field-full">
							<label>
								<span>Konfirmasi Password Baru</span>
							</label>
							<div class="password-input-wrap">
								<input
									:type="showConfirmPassword ? 'text' : 'password'"
									v-model="passwordForm.confirmPassword"
									minlength="6"
									placeholder="Ketik ulang password baru..."
									class="input-password"
								/>
								<button
									type="button"
									class="btn-toggle-eye"
									@click="showConfirmPassword = !showConfirmPassword"
									:title="showConfirmPassword ? 'Sembunyikan' : 'Tampilkan'"
								>
									<svg v-if="!showConfirmPassword" viewBox="0 0 24 24" class="svg-icon-eye" fill="currentColor">
										<path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
									</svg>
									<svg v-else viewBox="0 0 24 24" class="svg-icon-eye" fill="currentColor">
										<path d="M12 7c2.76 0 5 2.24 5 5 0 .65-.13 1.26-.36 1.83l2.92 2.92c1.51-1.26 2.7-2.89 3.44-4.75-1.73-4.39-6-7.5-11-7.5-1.4 0-2.74.25-3.98.7l2.16 2.16C10.74 7.13 11.35 7 12 7zM2 4.27l2.28 2.28.46.46C3.08 8.3 1.78 10.02 1 12c1.73 4.39 6 7.5 11 7.5 1.55 0 3.03-.3 4.38-.84l.42.42L19.73 22 21 20.73 3.27 3 2 4.27zM7.53 9.8l1.55 1.55c-.05.21-.08.43-.08.65 0 1.66 1.34 3 3 3 .22 0 .44-.03.65-.08l1.55 1.55c-.67.33-1.41.53-2.2.53-2.76 0-5-2.24-5-5 0-.79.2-1.53.53-2.2zm4.31-.78l3.15 3.15.02-.16c0-1.66-1.34-3-3-3l-.17.01z"/>
									</svg>
								</button>
							</div>
							<span v-if="passwordError" class="password-error-msg">
								<svg viewBox="0 0 24 24" class="svg-icon-xs text-red" fill="currentColor">
									<path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
								</svg>
								{{ passwordError }}
							</span>
						</div>
					</div>

					<div class="modal-footer-actions">
						<button class="btn-modal-cancel" type="button" @click="closePasswordModal" :disabled="isSubmitting">
							Tutup
						</button>
						<button class="btn-modal-submit" type="submit" :disabled="isSubmitting || !passwordForm.newPassword">
							<span v-if="isSubmitting" class="spinner-sm"></span>
							<span>{{ isSubmitting ? 'Menyimpan...' : 'Simpan Password Baru' }}</span>
						</button>
					</div>
				</form>
			</div>
		</div>

		<!-- ================================================================= -->
		<!-- MODAL KONFIRMASI HAPUS PENGGUNA                                   -->
		<!-- ================================================================= -->
		<div v-if="showDeleteModal" class="crypto-modal-backdrop" @click.self="closeDeleteModal">
			<div class="crypto-modal modal-delete-confirm">
				<div class="modal-top delete-top">
					<div class="delete-icon-box">
						<svg viewBox="0 0 24 24" class="svg-icon-alert" fill="currentColor">
							<path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
						</svg>
					</div>
					<div>
						<h3>Hapus Akun Pengguna?</h3>
						<p>Konfirmasi penghapusan data akun dari database sistem.</p>
					</div>
					<button class="modal-close-btn" type="button" @click="closeDeleteModal">✕</button>
				</div>

				<div class="delete-body">
					<p>Anda akan menghapus akun berikut secara permanen:</p>
					<div class="target-user-card">
						<div class="user-avatar-circle bg-user">
							{{ (userToDelete?.name || 'U').charAt(0).toUpperCase() }}
						</div>
						<div>
							<strong>{{ userToDelete?.name }}</strong>
							<span class="block text-muted font-mono text-xs">NPP / Username: {{ userToDelete?.nip }}</span>
							<span class="block text-muted text-xs">Divisi: {{ userToDelete?.division?.name || 'Kantor Pusat' }}</span>
						</div>
					</div>

					<div class="danger-warning-box">
						<span class="danger-icon">
							<svg viewBox="0 0 24 24" class="svg-icon-sm text-red" fill="currentColor">
								<path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
							</svg>
						</span>
						<p>Tindakan ini tidak dapat dibatalkan. Kredensial login pengguna akan dihapus dan pengguna tidak akan bisa lagi mengakses portal sistem.</p>
					</div>
				</div>

				<div class="modal-footer-actions">
					<button class="btn-modal-cancel" type="button" @click="closeDeleteModal" :disabled="isDeleting">
						Batal
					</button>
					<button class="btn-modal-danger" type="button" @click="confirmDeleteUser" :disabled="isDeleting">
						<span v-if="isDeleting" class="spinner-sm"></span>
						<span>{{ isDeleting ? 'Menghapus...' : 'Ya, Hapus Pengguna' }}</span>
					</button>
				</div>
			</div>
		</div>

		<!-- ================================================================= -->
		<!-- MODAL UBAH ROLE & HAK AKSES PENGGUNA                              -->
		<!-- ================================================================= -->
		<div v-if="showRoleModal" class="crypto-modal-backdrop" @click.self="closeRoleModal">
			<div class="crypto-modal">
				<div class="modal-top">
					<div class="modal-title-row">
						<span class="modal-key-icon">
							<svg viewBox="0 0 24 24" class="svg-icon-modal" fill="currentColor">
								<path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/>
							</svg>
						</span>
						<div>
							<h3>Ubah Role & Hak Akses</h3>
							<p>Atur kewenangan pengguna (Administrator, Pegawai, Pengemudi, atau OB).</p>
						</div>
					</div>
					<button class="modal-close-btn" type="button" @click="closeRoleModal">✕</button>
				</div>

				<form @submit.prevent="submitChangeRole" class="modal-body-form">
					<!-- Alert Message -->
					<div v-if="roleModalMsg" class="modal-alert" :class="roleModalMsg.type === 'error' ? 'alert-error' : 'alert-success'">
						<svg viewBox="0 0 24 24" class="svg-icon-xs" fill="currentColor">
							<path v-if="roleModalMsg.type === 'error'" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
							<path v-else d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
						</svg>
						<span>{{ roleModalMsg.text }}</span>
					</div>

					<!-- Info Pengguna -->
					<div class="user-preview-card">
						<div class="user-avatar-circle" :class="roleForm.role === 'admin' ? 'bg-admin' : (roleForm.role === 'sopir' ? 'bg-sopir' : (roleForm.role === 'ob' ? 'bg-ob' : 'bg-user'))">
							{{ (selectedUserForRole?.name || 'U').charAt(0).toUpperCase() }}
						</div>
						<div class="user-preview-meta">
							<strong>{{ selectedUserForRole?.name }}</strong>
							<span class="nip-display">NPP / Username: {{ selectedUserForRole?.nip }}</span>
						</div>
					</div>

					<!-- Pilihan Role -->
					<div class="form-field-group">
						<label>Pilih Role / Hak Akses:</label>
						<select v-model="roleForm.role" class="input-modern" required>
							<option value="user">Pegawai / User (Akses Peminjaman Fasilitas)</option>
							<option value="sopir">Pengemudi / Driver (Scan Berangkat, Pulang & Sampai Kantor)</option>
							<option value="ob">Office Boy (OB) (Scan & Ambil Barang/ATK)</option>
							<option value="admin">Administrator (Akses Penuh Manajemen Sistem)</option>
						</select>
					</div>

					<!-- Pilihan Status Akun -->
					<div class="form-field-group">
						<label>Status Akun:</label>
						<select v-model="roleForm.status" class="input-modern" required>
							<option value="active">Aktif (Dapat Login & Beraktivitas)</option>
							<option value="inactive">Nonaktif (Akses Dinonaktifkan)</option>
						</select>
					</div>

					<div class="modal-footer-actions">
						<button class="btn-modal-cancel" type="button" @click="closeRoleModal" :disabled="isSubmittingRole">
							Batal
						</button>
						<button class="btn-modal-submit" type="submit" :disabled="isSubmittingRole">
							<span v-if="isSubmittingRole" class="spinner-sm"></span>
							<span>{{ isSubmittingRole ? 'Menyimpan...' : 'Simpan Perubahan Role' }}</span>
						</button>
					</div>
				</form>
			</div>
		</div>

		<!-- ================================================================= -->
		<!-- MODAL TAMBAH PENGGUNA BARU                                        -->
		<!-- ================================================================= -->
		<div v-if="showCreateModal" class="crypto-modal-backdrop" @click.self="closeCreateUserModal">
			<div class="crypto-modal">
				<div class="modal-top">
					<div class="modal-title-row">
						<span class="modal-key-icon">
							<svg viewBox="0 0 24 24" class="svg-icon-modal" fill="currentColor">
								<path d="M15 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm-9-2V7H4v3H1v2h3v3h2v-3h3v-2H6zm9 4c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
							</svg>
						</span>
						<div>
							<h3>Tambah Pengguna Baru</h3>
							<p>Daftarkan akun Pegawai, Pengemudi, OB, atau Administrator baru ke database.</p>
						</div>
					</div>
					<button class="modal-close-btn" type="button" @click="closeCreateUserModal">✕</button>
				</div>

				<form @submit.prevent="submitCreateUser" class="modal-body-form">
					<div v-if="createModalMsg" class="modal-alert" :class="createModalMsg.type === 'error' ? 'alert-error' : 'alert-success'">
						<span>{{ createModalMsg.text }}</span>
					</div>

					<div class="form-field-group">
						<label>NPP / Username Login:</label>
						<input v-model="createForm.nip" placeholder="Contoh: joko (Pengemudi) atau 260901 (Pegawai)" required class="input-modern" />
					</div>

					<div class="form-field-group">
						<label>Nama Lengkap:</label>
						<input v-model="createForm.name" placeholder="Contoh: Joko Pratama" required class="input-modern" />
					</div>

					<div class="form-field-group">
						<label>Role / Hak Akses:</label>
						<select v-model="createForm.role" class="input-modern" required>
							<option value="user">Pegawai / User Biasa</option>
							<option value="sopir">Pengemudi / Driver (Scan Perjalanan Mobil)</option>
							<option value="ob">Office Boy (OB) (Scan Ambil Barang ATK)</option>
							<option value="admin">Administrator</option>
						</select>
					</div>

					<div class="form-field-group">
						<label>Kata Sandi Awal:</label>
						<input v-model="createForm.password" type="text" placeholder="Minimal 6 karakter" required class="input-modern" />
					</div>

					<div class="form-field-group">
						<label>ID Chat Telegram (Opsional):</label>
						<input v-model="createForm.telegram_chat_id" placeholder="Contoh: 120007410" class="input-modern" />
					</div>

					<div class="modal-footer-actions">
						<button class="btn-modal-cancel" type="button" @click="closeCreateUserModal" :disabled="isCreatingUser">
							Batal
						</button>
						<button class="btn-modal-submit" type="submit" :disabled="isCreatingUser">
							<span v-if="isCreatingUser" class="spinner-sm"></span>
							<span>{{ isCreatingUser ? 'Mendaftarkan...' : 'Tambah Pengguna' }}</span>
						</button>
					</div>
				</form>
			</div>
		</div>

		<!-- POPUP MODAL PERINGATAN DATA DUPLIKAT -->
		<div v-if="duplicateModal.show" class="crypto-modal-backdrop" style="z-index: 9999;" @click.self="closeDuplicateModal">
			<div class="duplicate-warning-modal">
				<div class="duplicate-modal-header">
					<div class="duplicate-icon-wrapper">
						<svg viewBox="0 0 24 24" class="duplicate-warning-icon" fill="currentColor">
							<path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/>
						</svg>
					</div>
					<div style="flex: 1;">
						<h3 class="duplicate-modal-title">{{ duplicateModal.title || 'Data Duplikat Terdeteksi!' }}</h3>
						<p class="duplicate-modal-subtitle">{{ duplicateModal.subtitle || 'Sistem menemukan data yang sama sudah terdaftar' }}</p>
					</div>
					<button type="button" class="modal-close-btn" @click="closeDuplicateModal">✕</button>
				</div>
				<div class="duplicate-modal-body">
					<div class="duplicate-alert-banner">
						<span class="duplicate-field-tag">{{ duplicateModal.fieldName }}</span>
						<div class="duplicate-main-message">{{ duplicateModal.message }}</div>
					</div>
					<div v-if="duplicateModal.inputVal || duplicateModal.existingVal" class="duplicate-detail-box">
						<div class="duplicate-detail-row" v-if="duplicateModal.inputVal">
							<span class="detail-label">Data yang Diinput:</span>
							<strong class="detail-val-input">{{ duplicateModal.inputVal }}</strong>
						</div>
						<div class="duplicate-detail-row" v-if="duplicateModal.existingVal">
							<span class="detail-label">Data Bentrok / Terdaftar:</span>
							<strong class="detail-val-existing">{{ duplicateModal.existingVal }}</strong>
						</div>
					</div>
					<p class="duplicate-instruction">
						Sistem mengharuskan NIP, nama pengguna, dan akun Telegram bersifat unik demi keamanan hak akses akun. Silakan ubah data sebelum melanjutkan.
					</p>
				</div>
				<div class="duplicate-modal-footer">
					<button type="button" class="btn-duplicate-ok" @click="closeDuplicateModal">
						<span>Mengerti & Periksa Kembali</span>
						<span>➔</span>
					</button>
				</div>
			</div>
		</div>
	</div>
</template>

<script setup>
import { computed, onMounted, reactive, ref } from 'vue'

useHead({
	title: 'Manajemen Pengguna - SIMPEL'
})

const router = useRouter()
const config = useRuntimeConfig()
const apiBase = config.public?.apiBase || 'http://127.0.0.1:8000/api'

// Theme & Sidebar State
const isDark = ref(true)
const menuOpen = ref(false)
const searchQuery = ref('')
const filterText = ref('')
const roleFilter = ref('Semua')
const statusFilter = ref('Semua')
const isLoading = ref(false)

// Toast Alert State
const toast = reactive({
	show: false,
	type: 'success', // 'success' | 'error'
	message: ''
})

const showToast = (message, type = 'success') => {
	toast.show = true
	toast.type = type
	toast.message = message
	setTimeout(() => {
		toast.show = false
	}, 4000)
}

// Sidebar Navigation
const menus = ref([
	{
		name: 'Manajemen Alat',
		active: false,
		iconSvg: '<svg viewBox="0 0 24 24" class="svg-icon-nav" fill="currentColor"><path d="M4 8h4V4H4v4zm6 12h4v-4h-4v4zm-6 0h4v-4H4v4zm0-6h4v-4H4v4zm6 0h4v-4h-4v4zm6-10v4h4V4h-4zm-6 4h4V4h-4v4zm6 6h4v-4h-4v4zm0 6h4v-4h-4v4z"/></svg>'
	},
	{
		name: 'Laporan',
		active: false,
		iconSvg: '<svg viewBox="0 0 24 24" class="svg-icon-nav" fill="currentColor"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/></svg>'
	},
	{
		name: 'Pengguna',
		active: true,
		iconSvg: '<svg viewBox="0 0 24 24" class="svg-icon-nav" fill="currentColor"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>'
	},
	{
		name: 'Divisi',
		active: false,
		iconSvg: '<svg viewBox="0 0 24 24" class="svg-icon-nav" fill="currentColor"><path d="M10 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2h-8l-2-2z"/></svg>'
	},
])

// Users Data
const users = ref([])

// Stats Computed
const adminCount = computed(() => users.value.filter(u => u.role === 'admin').length)
const sopirCount = computed(() => users.value.filter(u => u.role === 'sopir').length)
const obCount = computed(() => users.value.filter(u => u.role === 'ob').length)
const userCount = computed(() => users.value.filter(u => u.role === 'user').length)
const activeCount = computed(() => users.value.filter(u => u.status === 'active').length)

// Filtered Users Computed
const filteredUsers = computed(() => {
	const query = (filterText.value || searchQuery.value || '').toLowerCase().trim()
	const role = roleFilter.value
	const status = statusFilter.value

	return users.value.filter(u => {
		// Filter role
		if (role !== 'Semua' && u.role !== role) return false

		// Filter status
		if (status !== 'Semua' && u.status !== status) return false

		// Search term
		if (!query) return true
		const searchHaystack = [
			u.name,
			u.nip,
			u.role,
			u.division?.name
		].filter(Boolean).join(' ').toLowerCase()

		return searchHaystack.includes(query)
	})
})

// Toggle Dark/Light Theme
const toggleTheme = () => {
	isDark.value = !isDark.value
	if (typeof window !== 'undefined') {
		localStorage.setItem('admin_theme', isDark.value ? 'dark' : 'light')
	}
}

// Sidebar Navigation Activate
const activate = async selected => {
	menus.value.forEach(item => { item.active = item.name === selected.name })
	if (selected.name === 'Manajemen Alat') await router.push('/admin/dashboard')
	if (selected.name === 'Laporan') await router.push('/admin/laporan')
	if (selected.name === 'Divisi') await router.push('/admin/divisi')
}

// Fetch Users from Backend API
const fetchUsers = async () => {
	isLoading.value = true
	try {
		const res = await $fetch(`${apiBase}/users`)
		if (Array.isArray(res)) {
			users.value = res
		} else if (res && res.data) {
			users.value = res.data
		}
	} catch (err) {
		console.warn('Gagal memuat /api/users, mencoba fallback endpoint /v1/manage/users...', err)
		try {
			const fallbackRes = await $fetch(`${apiBase}/v1/manage/users`)
			if (Array.isArray(fallbackRes)) {
				users.value = fallbackRes
			}
		} catch (fallbackErr) {
			console.error('Semua pemanggilan API users gagal:', fallbackErr)
			showToast('Gagal memuat data pengguna dari database.', 'error')
		}
	} finally {
		isLoading.value = false
	}
}

// ============================================================================
// PASSWORD MODAL LOGIC (LIHAT PASSWORD LAMA & GANTI DI BAWAHNYA)
// ============================================================================
const showPasswordModal = ref(false)
const selectedUser = ref(null)
const showOldPassword = ref(false)
const copiedOldPassword = ref(false)
const showNewPassword = ref(false)
const showConfirmPassword = ref(false)
const isSubmitting = ref(false)
const passwordError = ref('')

const passwordForm = reactive({
	newPassword: '',
	confirmPassword: ''
})

const openPasswordModal = (user) => {
	selectedUser.value = user
	showOldPassword.value = false
	copiedOldPassword.value = false
	showNewPassword.value = false
	showConfirmPassword.value = false
	passwordForm.newPassword = ''
	passwordForm.confirmPassword = ''
	passwordError.value = ''
	showPasswordModal.value = true
}

const closePasswordModal = () => {
	showPasswordModal.value = false
	selectedUser.value = null
	passwordError.value = ''
}

const copyOldPassword = async () => {
	const pwd = selectedUser.value?.visible_password || '12345678'
	try {
		if (navigator?.clipboard) {
			await navigator.clipboard.writeText(pwd)
		} else {
			const el = document.createElement('textarea')
			el.value = pwd
			document.body.appendChild(el)
			el.select()
			document.execCommand('copy')
			document.body.removeChild(el)
		}
		copiedOldPassword.value = true
		setTimeout(() => {
			copiedOldPassword.value = false
		}, 2000)
	} catch (e) {
		console.warn('Gagal menyalin:', e)
	}
}

const submitChangePassword = async () => {
	passwordError.value = ''

	if (!passwordForm.newPassword) {
		passwordError.value = 'Silakan masukkan kata sandi baru jika ingin mengubah password.'
		return
	}

	if (passwordForm.newPassword.length < 6) {
		passwordError.value = 'Password minimal harus 6 karakter.'
		return
	}

	if (passwordForm.newPassword !== passwordForm.confirmPassword) {
		passwordError.value = 'Konfirmasi password tidak cocok dengan password baru.'
		return
	}

	isSubmitting.value = true
	try {
		const res = await $fetch(`${apiBase}/users/${selectedUser.value.id}/password`, {
			method: 'PUT',
			body: {
				password: passwordForm.newPassword
			}
		})

		showToast(res.message || `Password untuk ${selectedUser.value.name} berhasil diperbarui!`)
		closePasswordModal()
		await fetchUsers()
	} catch (err) {
		console.error('Gagal memperbarui password:', err)
		try {
			const fallbackRes = await $fetch(`${apiBase}/v1/manage/users/${selectedUser.value.id}/password`, {
				method: 'PUT',
				body: {
					password: passwordForm.newPassword
				}
			})
			showToast(fallbackRes.message || `Password berhasil diperbarui!`)
			closePasswordModal()
			await fetchUsers()
		} catch (innerErr) {
			const msg = innerErr?.data?.message || 'Gagal mengubah password di server.'
			passwordError.value = msg
			showToast(msg, 'error')
		}
	} finally {
		isSubmitting.value = false
	}
}

// ============================================================================
// DELETE USER MODAL LOGIC
// ============================================================================
const showDeleteModal = ref(false)
const userToDelete = ref(null)
const isDeleting = ref(false)

const openDeleteModal = (user) => {
	if (user.nip === '12345' || user.role === 'admin') {
		showToast('Akun Administrator utama dilindungi dan tidak dapat dihapus.', 'error')
		return
	}
	userToDelete.value = user
	showDeleteModal.value = true
}

const closeDeleteModal = () => {
	showDeleteModal.value = false
	userToDelete.value = null
}

const confirmDeleteUser = async () => {
	if (!userToDelete.value) return

	isDeleting.value = true
	try {
		const res = await $fetch(`${apiBase}/users/${userToDelete.value.id}`, {
			method: 'DELETE'
		})

		showToast(res.message || `Akun ${userToDelete.value.name} berhasil dihapus.`)
		closeDeleteModal()
		await fetchUsers()
	} catch (err) {
		console.error('Gagal menghapus pengguna:', err)
		try {
			const fallbackRes = await $fetch(`${apiBase}/v1/manage/users/${userToDelete.value.id}`, {
				method: 'DELETE'
			})
			showToast(fallbackRes.message || `Akun berhasil dihapus.`)
			closeDeleteModal()
			await fetchUsers()
		} catch (innerErr) {
			const msg = innerErr?.data?.message || 'Gagal menghapus pengguna dari database.'
			showToast(msg, 'error')
		}
	} finally {
		isDeleting.value = false
	}
}

// ============================================================================
// UBAH ROLE & STATUS USER MODAL LOGIC
// ============================================================================
const showRoleModal = ref(false)
const selectedUserForRole = ref(null)
const isSubmittingRole = ref(false)
const roleModalMsg = ref(null)
const roleForm = reactive({
	role: 'user',
	status: 'active'
})

const openRoleModal = (user) => {
	selectedUserForRole.value = user
	roleForm.role = user.role || 'user'
	roleForm.status = user.status || 'active'
	roleModalMsg.value = null
	showRoleModal.value = true
}

const closeRoleModal = () => {
	showRoleModal.value = false
	selectedUserForRole.value = null
	roleModalMsg.value = null
}

const submitChangeRole = async () => {
	if (!selectedUserForRole.value) return
	isSubmittingRole.value = true
	roleModalMsg.value = null

	try {
		const res = await $fetch(`${apiBase}/users/${selectedUserForRole.value.id}`, {
			method: 'PUT',
			body: {
				role: roleForm.role,
				status: roleForm.status
			}
		})
		showToast(res.message || 'Role pengguna berhasil diperbarui!')
		closeRoleModal()
		await fetchUsers()
	} catch (err) {
		try {
			const fallbackRes = await $fetch(`${apiBase}/v1/manage/users/${selectedUserForRole.value.id}`, {
				method: 'PUT',
				body: {
					role: roleForm.role,
					status: roleForm.status
				}
			})
			showToast(fallbackRes.message || 'Role pengguna berhasil diperbarui!')
			closeRoleModal()
			await fetchUsers()
		} catch (innerErr) {
			const msg = innerErr?.data?.message || 'Gagal memperbarui role pengguna.'
			roleModalMsg.value = { type: 'error', text: msg }
			showToast(msg, 'error')
		}
	} finally {
		isSubmittingRole.value = false
	}
}

// ============================================================================
// TAMBAH PENGGUNA BARU MODAL LOGIC
// ============================================================================
const showCreateModal = ref(false)
const isCreatingUser = ref(false)
const createModalMsg = ref(null)
const createForm = reactive({
	nip: '',
	name: '',
	role: 'user',
	password: 'password123',
	telegram_chat_id: ''
})

// Duplicate Warning Popup Modal State
const duplicateModal = reactive({
	show: false,
	title: 'Data Duplikat Terdeteksi!',
	subtitle: 'Sistem menemukan data yang sama sudah terdaftar',
	fieldName: '',
	message: '',
	inputVal: '',
	existingVal: ''
})

const openDuplicateWarning = ({ title, subtitle, fieldName, message, inputVal, existingVal }) => {
	duplicateModal.title = title || 'Data Duplikat Terdeteksi!'
	duplicateModal.subtitle = subtitle || 'Sistem menolak penyimpanan karena data sudah terdaftar'
	duplicateModal.fieldName = fieldName || 'DATA BENTROK'
	duplicateModal.message = message || 'Data yang Anda masukkan sudah ada di sistem.'
	duplicateModal.inputVal = inputVal || ''
	duplicateModal.existingVal = existingVal || ''
	duplicateModal.show = true
}

const closeDuplicateModal = () => {
	duplicateModal.show = false
}

const openCreateUserModal = () => {
	createForm.nip = ''
	createForm.name = ''
	createForm.role = 'user'
	createForm.password = '12345678'
	createForm.telegram_chat_id = ''
	createModalMsg.value = null
	showCreateModal.value = true
}

const closeCreateUserModal = () => {
	showCreateModal.value = false
	createModalMsg.value = null
}

const submitCreateUser = async () => {
	const inputNip = (createForm.nip || '').trim().toLowerCase()
	const inputName = (createForm.name || '').trim().toLowerCase()
	const inputTele = (createForm.telegram_chat_id || '').trim()

	// 1. Validasi Duplikasi NIP / Username
	const dupNip = users.value.find(u => (u.nip || '').trim().toLowerCase() === inputNip)
	if (dupNip) {
		openDuplicateWarning({
			title: 'NIP / Username Sudah Digunakan!',
			subtitle: 'Setiap akun wajib memiliki NPP / NIP / Username unik',
			fieldName: 'NIP / USERNAME',
			message: `NIP / Username "${createForm.nip}" sudah digunakan oleh akun "${dupNip.name}". NIP tidak boleh sama!`,
			inputVal: createForm.nip,
			existingVal: `${dupNip.name} (Role: ${dupNip.role})`
		})
		return
	}

	// 2. Validasi Duplikasi Nama Pengguna
	const dupName = users.value.find(u => (u.name || '').trim().toLowerCase() === inputName)
	if (dupName) {
		openDuplicateWarning({
			title: 'Nama Pengguna Sudah Terdaftar!',
			subtitle: 'Ditemukan nama pengguna yang sama di dalam sistem',
			fieldName: 'NAMA LENGKAP',
			message: `Nama pengguna "${createForm.name}" sudah terdaftar dalam sistem (NIP: ${dupName.nip}). Nama tidak boleh sama!`,
			inputVal: createForm.name,
			existingVal: `${dupName.name} (NIP: ${dupName.nip})`
		})
		return
	}

	// 3. Validasi Duplikasi ID Telegram (jika diisi)
	if (inputTele) {
		const dupTele = users.value.find(u => (u.telegram_chat_id || '').trim() === inputTele)
		if (dupTele) {
			openDuplicateWarning({
				title: 'ID Telegram Sudah Digunakan!',
				subtitle: 'Satu ID Telegram hanya boleh terhubung ke 1 akun pengguna',
				fieldName: 'TELEGRAM CHAT ID',
				message: `ID Telegram "${inputTele}" sudah terdaftar dan terhubung pada akun "${dupTele.name}".`,
				inputVal: inputTele,
				existingVal: `${dupTele.name} (${dupTele.nip})`
			})
			return
		}
	}

	isCreatingUser.value = true
	createModalMsg.value = null

	const payload = {
		nip: createForm.nip,
		name: createForm.name,
		role: createForm.role,
		password: createForm.password,
		status: 'active'
	}
	if (inputTele) {
		payload.telegram_chat_id = inputTele
	}

	try {
		const res = await $fetch(`${apiBase}/users`, {
			method: 'POST',
			body: payload
		})
		showToast(res.message || 'Pengguna berhasil ditambahkan!')
		closeCreateUserModal()
		await fetchUsers()
	} catch (err) {
		console.error('Gagal tambah user:', err)

		// Tangani 422 errors
		const errErrors = err?.data?.errors
		if (errErrors && typeof errErrors === 'object') {
			const firstKey = Object.keys(errErrors)[0]
			const firstMsg = Array.isArray(errErrors[firstKey]) ? errErrors[firstKey][0] : String(errErrors[firstKey])
			openDuplicateWarning({
				title: 'Data Duplikat Ditolak Server!',
				subtitle: 'Database menolak penambahan pengguna karena data sudah terdaftar',
				fieldName: firstKey.toUpperCase().replace('_', ' '),
				message: firstMsg,
				inputVal: createForm[firstKey] || '-',
				existingVal: 'Sudah ada dalam database'
			})
			isCreatingUser.value = false
			return
		}

		try {
			const fallbackRes = await $fetch(`${apiBase}/v1/manage/users`, {
				method: 'POST',
				body: payload
			})
			showToast(fallbackRes.message || 'Pengguna berhasil ditambahkan!')
			closeCreateUserModal()
			await fetchUsers()
		} catch (innerErr) {
			const innerErrors = innerErr?.data?.errors
			if (innerErrors && typeof innerErrors === 'object') {
				const firstKey = Object.keys(innerErrors)[0]
				const firstMsg = Array.isArray(innerErrors[firstKey]) ? innerErrors[firstKey][0] : String(innerErrors[firstKey])
				openDuplicateWarning({
					title: 'Data Duplikat Ditolak Server!',
					subtitle: 'Database menolak penambahan pengguna karena data sudah terdaftar',
					fieldName: firstKey.toUpperCase().replace('_', ' '),
					message: firstMsg,
					inputVal: createForm[firstKey] || '-',
					existingVal: 'Sudah ada dalam database'
				})
				isCreatingUser.value = false
				return
			}

			const msg = innerErr?.data?.message || 'Gagal menambahkan pengguna baru.'
			if (msg.toLowerCase().includes('sudah') || msg.toLowerCase().includes('terdaftar') || msg.toLowerCase().includes('unique')) {
				openDuplicateWarning({
					title: 'Peringatan Duplikasi Pengguna!',
					subtitle: 'Validasi database menolak duplikasi data',
					fieldName: 'PENGGUNA',
					message: msg,
					inputVal: createForm.nip || createForm.name || '-',
					existingVal: 'Sudah terdaftar di sistem'
				})
			} else {
				createModalMsg.value = { type: 'error', text: msg }
				showToast(msg, 'error')
			}
		}
	} finally {
		isCreatingUser.value = false
	}
}

// Format Date Helper
const formatDate = (dateStr) => {
	if (!dateStr) return '-'
	try {
		const d = new Date(dateStr)
		return d.toLocaleDateString('id-ID', {
			day: '2-digit',
			month: 'short',
			year: 'numeric'
		})
	} catch {
		return dateStr
	}
}

// Lifecycle Hooks
onMounted(async () => {
	if (typeof window !== 'undefined') {
		const savedTheme = localStorage.getItem('admin_theme')
		if (savedTheme) {
			isDark.value = savedTheme === 'dark'
		}
	}
	await fetchUsers()
})
</script>

<style scoped>
/* ========================================================================== */
/* UNIFIED SVG ICONS SIZING & CONSISTENCY                                     */
/* ========================================================================== */
.svg-icon-diamond {
	width: 20px;
	height: 20px;
}
.svg-icon-nav {
	width: 17px;
	height: 17px;
}
.svg-icon-sm {
	width: 15px;
	height: 15px;
}
.svg-icon-xs {
	width: 13px;
	height: 13px;
	flex-shrink: 0;
}
.svg-icon-theme {
	width: 15px;
	height: 15px;
}
.svg-icon-bell {
	width: 16px;
	height: 16px;
}
.svg-icon-stat {
	width: 20px;
	height: 20px;
}
.svg-icon-sync {
	width: 14px;
	height: 14px;
}
.svg-icon-action {
	width: 14px;
	height: 14px;
}
.svg-icon-modal {
	width: 18px;
	height: 18px;
}
.svg-icon-locked {
	width: 11px;
	height: 11px;
}
.svg-icon-eye {
	width: 16px;
	height: 16px;
}
.svg-icon-copy {
	width: 14px;
	height: 14px;
}
.svg-icon-check {
	width: 15px;
	height: 15px;
}
.svg-icon-shield {
	width: 13px;
	height: 13px;
}
.svg-icon-building {
	width: 13px;
	height: 13px;
}
.svg-icon-alert {
	width: 20px;
	height: 20px;
}
.svg-icon-bolt {
	width: 12px;
	height: 12px;
}
.svg-icon-empty {
	width: 24px;
	height: 24px;
}

/* ========================================================================== */
/* BASE THEME & LAYOUT                                                        */
/* ========================================================================== */
.dashboard {
	display: flex;
	min-height: 100vh;
	font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
	transition: background-color 0.25s ease, color 0.25s ease;
}

/* THEME DARK */
.theme-dark {
	background: #0b0f19;
	color: #e2e8f0;
}
.theme-dark .sidebar {
	background: #0d1424;
	border-right: 1px solid rgba(255, 255, 255, 0.07);
}
.theme-dark .topbar {
	background: #0d1424;
	border-bottom: 1px solid rgba(255, 255, 255, 0.07);
}
.theme-dark .crypto-panel {
	background: #0f172a;
	border: 1px solid rgba(255, 255, 255, 0.08);
	box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
}
.theme-dark .crypto-table thead th {
	background: #131d33;
	color: #94a3b8;
	border-bottom: 1px solid rgba(255, 255, 255, 0.08);
}
.theme-dark .crypto-table tbody tr {
	border-bottom: 1px solid rgba(255, 255, 255, 0.04);
}
.theme-dark .crypto-table tbody tr:hover {
	background: rgba(255, 255, 255, 0.03);
}

/* THEME LIGHT */
.theme-light {
	background: #f1f5f9;
	color: #1e293b;
}
.theme-light .sidebar {
	background: #ffffff;
	border-right: 1px solid #e2e8f0;
}
.theme-light .topbar {
	background: #ffffff;
	border-bottom: 1px solid #e2e8f0;
}
.theme-light .brand b {
	color: #0f172a;
}
.theme-light .brand small {
	color: #64748b;
}
.theme-light .sidebar nav a {
	color: #475569;
}
.theme-light .sidebar nav a:hover {
	background: #f1f5f9;
	color: #0f172a;
}
.theme-light .sidebar nav a.active {
	background: #e6fcf5;
	color: #059669;
	font-weight: 700;
}
.theme-light .crypto-panel {
	background: #ffffff;
	border: 1px solid #e2e8f0;
	box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
}
.theme-light .crypto-header {
	border-bottom: 1px solid #f1f5f9;
}
.theme-light .crypto-header h2 {
	color: #0f172a;
}
.theme-light .crypto-header .subtitle {
	color: #64748b;
}
.theme-light .crypto-table thead th {
	background: #f8fafc;
	color: #475569;
	border-bottom: 1px solid #e2e8f0;
}
.theme-light .crypto-table tbody tr {
	border-bottom: 1px solid #f1f5f9;
}
.theme-light .crypto-table tbody tr:hover {
	background: #f8fafc;
}
.theme-light .summary article {
	background: #ffffff;
	border: 1px solid #e2e8f0;
	box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
}
.theme-light .summary article strong {
	color: #0f172a;
}
.theme-light .summary article small {
	color: #64748b;
}
.theme-light .table-filters-row {
	background: #f8fafc;
	border-bottom: 1px solid #e2e8f0;
}
.theme-light .search-box-wrap input,
.theme-light .select-group select {
	background: #ffffff;
	border-color: #cbd5e1;
	color: #0f172a;
}
.theme-light .crypto-footer {
	background: #f8fafc;
	border-top: 1px solid #e2e8f0;
	color: #64748b;
}
.theme-light .hero {
	background: linear-gradient(135deg, #ecfdf5 0%, #f0fdf4 100%);
	border-color: #a7f3d0;
}
.theme-light .hero h1 {
	color: #064e3b;
}
.theme-light .hero p {
	color: #059669;
}
.theme-light .hero span {
	color: #334155;
}
.theme-light .user-fullname {
	color: #0f172a;
}
.theme-light .crypto-modal {
	background: #ffffff;
	border-color: #e2e8f0;
	box-shadow: 0 25px 60px rgba(0, 0, 0, 0.15);
}
.theme-light .modal-top {
	border-bottom-color: #e2e8f0;
}
.theme-light .modal-top h3 {
	color: #0f172a;
}
.theme-light .modal-top p {
	color: #64748b;
}
.theme-light .form-field-locked label span:first-child,
.theme-light .form-field-full label span:first-child {
	color: #334155;
}
.theme-light .input-locked {
	background: #f1f5f9;
	border-color: #cbd5e1;
	color: #475569;
}
.theme-light .old-password-box {
	background: #fefce8;
	border-color: #fef08a;
}
.theme-light .old-password-box strong {
	color: #854d0e;
}
.theme-light .old-password-display {
	background: #ffffff;
	border-color: #fde047;
	color: #713f12;
}
.theme-light .btn-icon-control {
	background: #ffffff;
	border-color: #cbd5e1;
	color: #334155;
}
.theme-light .btn-icon-control:hover {
	background: #f1f5f9;
	color: #0f172a;
}
.theme-light .input-password {
	background: #f8fafc;
	border-color: #cbd5e1;
	color: #0f172a;
}
.theme-light .input-password:focus {
	background: #ffffff;
	border-color: #10b981;
}
.theme-light .modal-footer-actions {
	border-top-color: #e2e8f0;
}
.theme-light .btn-modal-cancel {
	background: #f1f5f9;
	border-color: #cbd5e1;
	color: #475569;
}
.theme-light .target-user-card {
	background: #f8fafc;
	border-color: #e2e8f0;
}
.theme-light .target-user-card strong {
	color: #0f172a;
}
.theme-light .btn-action-symbol.role-btn {
	background: #ecfdf5;
	color: #059669;
	border-color: #a7f3d0;
}
.theme-light .btn-action-symbol.role-btn:hover {
	background: #10b981;
	color: #ffffff;
	border-color: #10b981;
}
.theme-light .btn-action-symbol.password-btn {
	background: #fffbeb;
	color: #d97706;
	border-color: #fde68a;
}
.theme-light .btn-action-symbol.password-btn:hover {
	background: #f59e0b;
	color: #ffffff;
	border-color: #f59e0b;
}
.theme-light .btn-action-symbol.delete-btn {
	background: #fef2f2;
	color: #dc2626;
	border-color: #fecaca;
}
.theme-light .btn-action-symbol.delete-btn:hover {
	background: #ef4444;
	color: #ffffff;
	border-color: #ef4444;
}
.theme-light .protected-symbol {
	background: #f1f5f9;
	color: #64748b;
	border-color: #cbd5e1;
}

/* ========================================================================== */
/* SIDEBAR                                                                    */
/* ========================================================================== */
.sidebar {
	width: 250px;
	padding: 24px 18px;
	display: flex;
	flex-direction: column;
	gap: 22px;
	flex-shrink: 0;
	position: sticky;
	top: 0;
	height: 100vh;
	box-sizing: border-box;
	z-index: 100;
}
.brand {
	display: flex;
	align-items: center;
	gap: 12px;
	padding-bottom: 16px;
	border-bottom: 1px solid rgba(255, 255, 255, 0.08);
}
.brand-logo-diamond {
	display: grid;
	place-items: center;
	width: 38px;
	height: 38px;
	border-radius: 9px;
	background: #111a2e;
	box-shadow: 0 4px 12px rgba(255, 122, 0, 0.18);
	flex-shrink: 0;
}
.brand b {
	font-size: 15px;
	font-weight: 700;
	display: block;
	letter-spacing: -0.2px;
}
.brand small {
	font-size: 11px;
	color: #64748b;
	display: block;
}
.sidebar nav {
	display: flex;
	flex-direction: column;
	gap: 6px;
	flex-grow: 1;
}
.sidebar nav a {
	display: flex;
	align-items: center;
	gap: 12px;
	padding: 10px 14px;
	border-radius: 8px;
	text-decoration: none;
	color: #94a3b8;
	font-size: 13px;
	font-weight: 500;
	transition: all 0.18s ease;
}
.sidebar nav a:hover {
	background: rgba(255, 255, 255, 0.05);
	color: #ffffff;
}
.sidebar nav a.active {
	background: #10b981;
	color: #ffffff;
	font-weight: 600;
	box-shadow: 0 4px 14px rgba(16, 185, 129, 0.35);
}
.nav-svg-box {
	display: grid;
	place-items: center;
	width: 20px;
	height: 20px;
}
:deep(.svg-icon-nav) {
	width: 17px;
	height: 17px;
}
.account {
	display: flex;
	align-items: center;
	gap: 10px;
	padding: 10px 12px;
	border-radius: 8px;
	background: rgba(255, 255, 255, 0.04);
	border: 1px solid rgba(255, 255, 255, 0.06);
	cursor: pointer;
}
.account:hover {
	background: rgba(255, 255, 255, 0.08);
}
.user-avatar {
	display: grid;
	place-items: center;
	width: 32px;
	height: 32px;
	border-radius: 8px;
	background: linear-gradient(135deg, #10b981, #059669);
	color: #fff;
	font-weight: 700;
	font-size: 13px;
}
.account b {
	font-size: 13px;
	display: block;
}
.account small {
	font-size: 11px;
	color: #64748b;
	display: block;
}
.account button {
	margin-left: auto;
	background: transparent;
	border: none;
	color: #64748b;
	cursor: pointer;
	padding: 4px;
	display: grid;
	place-items: center;
}
.logout {
	display: flex;
	align-items: center;
	justify-content: center;
	gap: 8px;
	padding: 10px;
	border-radius: 8px;
	background: rgba(239, 68, 68, 0.1);
	border: 1px solid rgba(239, 68, 68, 0.2);
	color: #ef4444;
	font-weight: 600;
	font-size: 13px;
	cursor: pointer;
	transition: all 0.15s ease;
}
.logout:hover {
	background: #ef4444;
	color: #ffffff;
}

/* ========================================================================== */
/* MAIN CONTENT & TOPBAR                                                      */
/* ========================================================================== */
.main {
	flex-grow: 1;
	display: flex;
	flex-direction: column;
	min-width: 0;
}
.topbar {
	height: 64px;
	padding: 0 32px;
	display: flex;
	align-items: center;
	gap: 20px;
	position: sticky;
	top: 0;
	z-index: 50;
}
.mobile-menu {
	display: none;
	background: transparent;
	border: none;
	color: inherit;
	font-size: 20px;
	cursor: pointer;
}
.global-search {
	display: flex;
	align-items: center;
	gap: 10px;
	background: rgba(255, 255, 255, 0.05);
	border: 1px solid rgba(255, 255, 255, 0.08);
	border-radius: 8px;
	padding: 8px 14px;
	width: 380px;
}
.theme-light .global-search {
	background: #f1f5f9;
	border-color: #cbd5e1;
}
.search-lens {
	color: #64748b;
	display: grid;
	place-items: center;
}
.global-search input {
	background: transparent;
	border: none;
	outline: none;
	color: inherit;
	font-size: 13px;
	width: 100%;
}
.top-user {
	display: flex;
	align-items: center;
	gap: 12px;
	margin-left: auto;
	font-size: 13px;
}

/* THEME TOGGLE BUTTON */
.theme-toggle-btn {
	display: flex;
	align-items: center;
	gap: 7px;
	padding: 6px 12px;
	border-radius: 20px;
	background: #111a2e;
	border: 1px solid rgba(255, 255, 255, 0.12);
	color: #cbd5e1;
	font-size: 12px;
	font-weight: 600;
	cursor: pointer;
	transition: all 0.2s ease;
}
.theme-light .theme-toggle-btn {
	background: #e2e8f0;
	border-color: #cbd5e1;
	color: #0f172a;
}
.theme-toggle-btn:hover {
	transform: translateY(-1px);
	border-color: #10b981;
}
.theme-icon {
	display: grid;
	place-items: center;
}
.theme-icon.sun {
	color: #f59e0b;
}
.theme-icon.moon {
	color: #38bdf8;
}
.bell-btn {
	display: grid;
	place-items: center;
	width: 32px;
	height: 32px;
	border-radius: 8px;
	background: rgba(255, 255, 255, 0.06);
	color: #94a3b8;
	cursor: pointer;
}
.theme-light .bell-btn {
	background: #e2e8f0;
	color: #475569;
}
.user-dot {
	display: grid;
	place-items: center;
	width: 26px;
	height: 26px;
	border-radius: 50%;
	background: #10b981;
	color: #fff;
	font-weight: 700;
	font-size: 11px;
}
.chevron-down {
	color: #64748b;
	font-size: 10px;
}

.page-content {
	max-width: 1400px;
	margin: auto;
	padding: 24px 32px 48px;
	box-sizing: border-box;
	width: 100%;
}

/* ========================================================================== */
/* TOAST NOTIFICATION                                                         */
/* ========================================================================== */
.toast-banner {
	display: flex;
	align-items: center;
	gap: 12px;
	padding: 12px 18px;
	border-radius: 10px;
	margin-bottom: 20px;
	font-size: 13px;
	font-weight: 600;
	box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
}
.toast-banner.success {
	background: #064e3b;
	border: 1px solid #10b981;
	color: #6ee7b7;
}
.toast-banner.error {
	background: #7f1d1d;
	border: 1px solid #ef4444;
	color: #fca5a5;
}
.toast-icon {
	display: grid;
	place-items: center;
}
.toast-message {
	flex-grow: 1;
}
.toast-close {
	background: transparent;
	border: none;
	color: inherit;
	font-size: 16px;
	cursor: pointer;
}
.toast-fade-enter-active,
.toast-fade-leave-active {
	transition: opacity 0.25s, transform 0.25s;
}
.toast-fade-enter-from,
.toast-fade-leave-to {
	opacity: 0;
	transform: translateY(-8px);
}

/* ========================================================================== */
/* HERO SECTION                                                               */
/* ========================================================================== */
.hero {
	min-height: 98px;
	position: relative;
	overflow: hidden;
	display: flex;
	align-items: center;
	padding: 20px 26px;
	border-radius: 12px;
	background: linear-gradient(135deg, #0d231e 0%, #0d1726 100%);
	border: 1px solid rgba(52, 211, 153, 0.2);
	margin-bottom: 24px;
}
.hero p {
	margin: 0 0 6px;
	color: #34d399;
	font-size: 12px;
	font-weight: 600;
}
.hero em {
	color: #fbbf24;
	font-style: normal;
}
.hero h1 {
	margin: 0;
	color: #ffffff;
	font-size: clamp(20px, 2.2vw, 26px);
	letter-spacing: -0.5px;
	font-weight: 800;
}
.hero span {
	display: block;
	margin-top: 6px;
	color: #94a3b8;
	font-size: 12px;
	max-width: 750px;
	line-height: 1.5;
}
.hero-art {
	position: absolute;
	right: 6%;
	bottom: -12px;
	opacity: 0.22;
	user-select: none;
	pointer-events: none;
}
.art-svg {
	width: 78px;
	height: 78px;
	color: #10b981;
}

/* ========================================================================== */
/* SUMMARY STATS GRID                                                         */
/* ========================================================================== */
.summary {
	display: grid;
	grid-template-columns: repeat(4, 1fr);
	gap: 16px;
	margin-bottom: 28px;
}
.summary article {
	display: flex;
	align-items: center;
	gap: 14px;
	padding: 16px 20px;
	border-radius: 12px;
	background: #0f1623;
	border: 1px solid rgba(255, 255, 255, 0.06);
	box-shadow: 0 4px 16px rgba(0, 0, 0, 0.35);
	transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.summary article:hover {
	transform: translateY(-2px);
	box-shadow: 0 6px 20px rgba(0, 0, 0, 0.45);
}
.stat-icon {
	display: grid;
	place-items: center;
	width: 40px;
	height: 40px;
	border-radius: 9px;
	flex-shrink: 0;
}
.stat-icon.green {
	background: rgba(16, 185, 129, 0.15);
	color: #34d399;
}
.stat-icon.cyan {
	background: rgba(6, 182, 212, 0.15);
	color: #22d3ee;
}
.stat-icon.amber {
	background: rgba(245, 158, 11, 0.15);
	color: #fbbf24;
}
.stat-icon.purple {
	background: rgba(168, 85, 247, 0.15);
	color: #c084fc;
}
.summary article strong {
	display: block;
	font-size: 17px;
	font-weight: 700;
	margin: 2px 0 1px;
}
.summary article small {
	font-size: 11px;
	color: #64748b;
	display: block;
}
.text-green { color: #34d399 !important; }
.text-cyan { color: #22d3ee !important; }
.text-amber { color: #fbbf24 !important; }
.text-purple { color: #c084fc !important; }
.text-red { color: #f87171 !important; }

/* ========================================================================== */
/* CRYPTO PANEL & TABLE                                                       */
/* ========================================================================== */
.crypto-panel {
	border-radius: 14px;
	overflow: hidden;
	margin-bottom: 32px;
}
.crypto-header {
	display: flex;
	justify-content: space-between;
	align-items: center;
	padding: 20px 24px;
	border-bottom: 1px solid rgba(255, 255, 255, 0.08);
}
.title-with-badge {
	display: flex;
	align-items: center;
	gap: 12px;
}
.crypto-header h2 {
	margin: 0;
	font-size: 18px;
	font-weight: 700;
	letter-spacing: -0.3px;
}
.live-indicator {
	display: inline-flex;
	align-items: center;
	gap: 5px;
	padding: 3px 9px;
	border-radius: 12px;
	background: rgba(255, 122, 0, 0.14);
	border: 1px solid rgba(255, 122, 0, 0.35);
	color: #ff9800;
	font-size: 11px;
	font-weight: 700;
	letter-spacing: 0.3px;
}
.crypto-header .subtitle {
	margin: 5px 0 0;
	font-size: 12px;
	color: #94a3b8;
}
.crypto-actions-top {
	display: flex;
	align-items: center;
	gap: 10px;
	flex-wrap: wrap;
}
.btn-refresh {
	display: inline-flex;
	align-items: center;
	gap: 6px;
	padding: 8px 14px;
	border-radius: 8px;
	background: rgba(255, 255, 255, 0.06);
	border: 1px solid rgba(255, 255, 255, 0.1);
	color: inherit;
	font-size: 12px;
	font-weight: 600;
	cursor: pointer;
	transition: all 0.15s ease;
}
.theme-light .btn-refresh {
	background: #f1f5f9;
	border-color: #cbd5e1;
	color: #334155;
}
.btn-refresh:hover {
	border-color: #10b981;
}
.spinning {
	animation: spin 0.8s linear infinite;
}
@keyframes spin {
	from { transform: rotate(0deg); }
	to { transform: rotate(360deg); }
}

/* FILTERS ROW */
.table-filters-row {
	display: flex;
	align-items: center;
	justify-content: space-between;
	padding: 14px 24px;
	background: rgba(0, 0, 0, 0.15);
	border-bottom: 1px solid rgba(255, 255, 255, 0.06);
	gap: 16px;
	flex-wrap: wrap;
}
.search-box-wrap {
	display: flex;
	align-items: center;
	gap: 8px;
	background: rgba(255, 255, 255, 0.04);
	border: 1px solid rgba(255, 255, 255, 0.1);
	border-radius: 8px;
	padding: 6px 12px;
	min-width: 280px;
}
.search-icon {
	display: grid;
	place-items: center;
	color: #64748b;
}
.search-box-wrap input {
	background: transparent;
	border: none;
	outline: none;
	color: inherit;
	font-size: 12px;
	width: 100%;
}
.filters-right {
	display: flex;
	align-items: center;
	gap: 16px;
}
.select-group {
	display: flex;
	align-items: center;
	gap: 8px;
	font-size: 12px;
	color: #94a3b8;
}
.select-group select {
	background: #111a2e;
	border: 1px solid rgba(255, 255, 255, 0.12);
	color: #e2e8f0;
	padding: 6px 10px;
	border-radius: 6px;
	font-size: 12px;
	outline: none;
}

/* TABLE */
.crypto-table-wrap {
	overflow-x: auto;
}
.crypto-table {
	width: 100%;
	border-collapse: collapse;
	font-size: 12px;
	text-align: left;
}
.crypto-table th {
	padding: 13px 18px;
	font-size: 11px;
	font-weight: 700;
	letter-spacing: 0.4px;
	text-transform: uppercase;
	white-space: nowrap;
}
.crypto-table td {
	padding: 14px 18px;
	vertical-align: middle;
}
.th-num, .td-num {
	width: 45px;
	text-align: center;
	color: #64748b;
	font-weight: 600;
}
.th-actions {
	text-align: center;
	width: 140px;
}
.td-actions {
	text-align: center;
	white-space: nowrap;
}

/* USER CELL */
.user-cell {
	display: flex;
	align-items: center;
	gap: 12px;
}
.user-avatar-circle {
	display: grid;
	place-items: center;
	width: 36px;
	height: 36px;
	border-radius: 50%;
	font-weight: 700;
	font-size: 14px;
	color: #ffffff;
	flex-shrink: 0;
}
.bg-admin {
	background: linear-gradient(135deg, #f59e0b, #d97706);
	box-shadow: 0 2px 8px rgba(245, 158, 11, 0.35);
}
.bg-sopir {
	background: linear-gradient(135deg, #f97316, #ea580c);
	box-shadow: 0 2px 8px rgba(249, 115, 22, 0.35);
}
.bg-ob {
	background: linear-gradient(135deg, #14b8a6, #0d9488);
	box-shadow: 0 2px 8px rgba(20, 184, 166, 0.35);
}
.bg-user {
	background: linear-gradient(135deg, #0ea5e9, #0284c7);
	box-shadow: 0 2px 8px rgba(14, 165, 233, 0.35);
}
.user-fullname {
	display: block;
	font-size: 13px;
	font-weight: 700;
	color: #f1f5f9;
}
.user-nip-badge {
	display: inline-flex;
	align-items: center;
	gap: 4px;
	margin-top: 2px;
	font-family: monospace;
	font-size: 11px;
}
.nip-label {
	color: #64748b;
}
.nip-val {
	color: #10b981;
	font-weight: 600;
}

/* ROLES & BADGES */
.role-badge {
	display: inline-flex;
	align-items: center;
	gap: 6px;
	padding: 4px 10px;
	border-radius: 6px;
	font-size: 11px;
	font-weight: 700;
}
.role-admin {
	background: rgba(245, 158, 11, 0.15);
	border: 1px solid rgba(245, 158, 11, 0.3);
	color: #fbbf24;
}
.role-sopir {
	background: rgba(249, 115, 22, 0.15);
	border: 1px solid rgba(249, 115, 22, 0.3);
	color: #fb923c;
}
.role-ob {
	background: rgba(20, 184, 166, 0.15);
	border: 1px solid rgba(20, 184, 166, 0.3);
	color: #2dd4bf;
}
.role-user {
	background: rgba(6, 182, 212, 0.15);
	border: 1px solid rgba(6, 182, 212, 0.3);
	color: #22d3ee;
}
.user-preview-card {
	display: flex;
	align-items: center;
	gap: 12px;
	padding: 12px 14px;
	border-radius: 10px;
	background: rgba(255, 255, 255, 0.04);
	border: 1px solid rgba(255, 255, 255, 0.08);
	margin-bottom: 14px;
}
.user-preview-meta strong {
	display: block;
	font-size: 13px;
}
.nip-display {
	font-size: 11px;
	color: #94a3b8;
	font-family: monospace;
}
.form-field-group {
	display: flex;
	flex-direction: column;
	gap: 6px;
	margin-bottom: 14px;
}
.form-field-group label {
	font-size: 11.5px;
	font-weight: 600;
	color: #cbd5e1;
}
.input-modern {
	width: 100%;
	padding: 9px 12px;
	border-radius: 8px;
	background: rgba(255, 255, 255, 0.06);
	border: 1px solid rgba(255, 255, 255, 0.12);
	color: inherit;
	font-size: 12px;
	outline: none;
	transition: border-color 0.15s;
}
.input-modern:focus {
	border-color: #10b981;
}
.division-badge {
	display: inline-flex;
	align-items: center;
	gap: 6px;
	padding: 4px 9px;
	border-radius: 6px;
	background: rgba(255, 255, 255, 0.05);
	border: 1px solid rgba(255, 255, 255, 0.08);
	font-size: 11px;
	font-weight: 500;
}
.theme-light .division-badge {
	background: #f1f5f9;
	border-color: #cbd5e1;
	color: #334155;
}
.status-badge {
	display: inline-flex;
	align-items: center;
	gap: 6px;
	padding: 4px 9px;
	border-radius: 20px;
	font-size: 11px;
	font-weight: 700;
}
.status-dot {
	width: 6px;
	height: 6px;
	border-radius: 50%;
}
.status-green {
	background: rgba(16, 185, 129, 0.14);
	color: #34d399;
}
.status-green .status-dot {
	background: #10b981;
	box-shadow: 0 0 6px #10b981;
}
.status-red {
	background: rgba(239, 68, 68, 0.14);
	color: #f87171;
}
.status-red .status-dot {
	background: #ef4444;
}
.date-text {
	color: #94a3b8;
	font-size: 11px;
}

/* ACTIONS */
.action-btn-group {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	gap: 8px;
}
.btn-action-symbol {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	width: 34px;
	height: 34px;
	border-radius: 8px;
	cursor: pointer;
	border: 1px solid transparent;
	transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
	flex-shrink: 0;
	padding: 0;
}
.svg-icon-symbol {
	width: 16px;
	height: 16px;
}
.btn-action-symbol.role-btn {
	background: rgba(16, 185, 129, 0.14);
	color: #34d399;
	border-color: rgba(16, 185, 129, 0.3);
}
.btn-action-symbol.role-btn:hover {
	background: #10b981;
	color: #ffffff;
	transform: translateY(-2px);
	box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
}
.btn-action-symbol.password-btn {
	background: rgba(245, 158, 11, 0.14);
	color: #fbbf24;
	border-color: rgba(245, 158, 11, 0.3);
}
.btn-action-symbol.password-btn:hover {
	background: #f59e0b;
	color: #0f172a;
	transform: translateY(-2px);
	box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
}
.btn-action-symbol.delete-btn {
	background: rgba(239, 68, 68, 0.14);
	color: #f87171;
	border-color: rgba(239, 68, 68, 0.3);
}
.btn-action-symbol.delete-btn:hover {
	background: #ef4444;
	color: #ffffff;
	transform: translateY(-2px);
	box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
}
.protected-symbol {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	width: 34px;
	height: 34px;
	border-radius: 8px;
	background: rgba(245, 158, 11, 0.1);
	border: 1px solid rgba(245, 158, 11, 0.25);
	color: #fbbf24;
	flex-shrink: 0;
}

/* EMPTY & LOADING */
.empty-cell {
	text-align: center;
	padding: 48px 16px;
}
.loading-state {
	display: flex;
	flex-direction: column;
	align-items: center;
	gap: 12px;
	color: #94a3b8;
}
.spinner {
	width: 28px;
	height: 28px;
	border: 3px solid rgba(255, 255, 255, 0.1);
	border-top-color: #10b981;
	border-radius: 50%;
	animation: spin 0.8s linear infinite;
}
.empty-state {
	display: flex;
	flex-direction: column;
	align-items: center;
	max-width: 480px;
	margin: 0 auto;
}
.empty-icon-box {
	display: grid;
	place-items: center;
	width: 48px;
	height: 48px;
	border-radius: 12px;
	background: rgba(255, 255, 255, 0.05);
	color: #64748b;
	margin-bottom: 12px;
}
.empty-state h4 {
	margin: 0 0 6px;
	font-size: 15px;
	color: inherit;
}
.empty-state p {
	margin: 0;
	font-size: 12px;
	color: #94a3b8;
	line-height: 1.5;
}

/* FOOTER */
.crypto-footer {
	display: flex;
	justify-content: space-between;
	align-items: center;
	padding: 14px 24px;
	background: rgba(0, 0, 0, 0.15);
	border-top: 1px solid rgba(255, 255, 255, 0.06);
	font-size: 12px;
	color: #94a3b8;
}
.footer-db-status {
	display: flex;
	align-items: center;
	gap: 6px;
}
.status-dot-green {
	width: 7px;
	height: 7px;
	border-radius: 50%;
	background: #10b981;
	box-shadow: 0 0 6px #10b981;
}

/* ========================================================================== */
/* MODAL STYLES                                                               */
/* ========================================================================== */
.crypto-modal-backdrop {
	position: fixed;
	inset: 0;
	background: rgba(0, 0, 0, 0.75);
	backdrop-filter: blur(4px);
	display: grid;
	place-items: center;
	z-index: 1000;
	padding: 20px;
}
.crypto-modal {
	width: 100%;
	max-width: 530px;
	background: #0f172a;
	border: 1px solid rgba(255, 255, 255, 0.12);
	border-radius: 16px;
	box-shadow: 0 25px 60px rgba(0, 0, 0, 0.6);
	overflow: hidden;
	animation: modalPop 0.2s cubic-bezier(0.16, 1, 0.3, 1);
}
@keyframes modalPop {
	from { opacity: 0; transform: scale(0.96) translateY(8px); }
	to { opacity: 1; transform: scale(1) translateY(0); }
}
.modal-top {
	display: flex;
	justify-content: space-between;
	align-items: flex-start;
	padding: 20px 24px;
	border-bottom: 1px solid rgba(255, 255, 255, 0.08);
}
.modal-title-row {
	display: flex;
	align-items: center;
	gap: 10px;
}
.modal-key-icon {
	display: grid;
	place-items: center;
	width: 32px;
	height: 32px;
	border-radius: 8px;
	background: rgba(16, 185, 129, 0.15);
	color: #10b981;
}
.modal-top h3 {
	margin: 0;
	font-size: 16px;
	font-weight: 700;
	color: #ffffff;
}
.modal-top p {
	margin: 6px 0 0;
	font-size: 12px;
	color: #94a3b8;
	line-height: 1.4;
}
.modal-close-btn {
	background: rgba(255, 255, 255, 0.06);
	border: none;
	color: #94a3b8;
	width: 28px;
	height: 28px;
	border-radius: 6px;
	cursor: pointer;
	font-size: 14px;
	display: grid;
	place-items: center;
}
.modal-close-btn:hover {
	background: rgba(255, 255, 255, 0.12);
	color: #ffffff;
}

/* FORM GRID */
.modal-body-form {
	padding: 20px 24px;
}
.form-grid {
	display: grid;
	grid-template-columns: 1fr 1fr;
	gap: 14px;
}
.form-field-locked {
	grid-column: span 1;
}
.form-field-full {
	grid-column: span 2;
}
.form-field-locked label,
.form-field-full label {
	display: flex;
	justify-content: space-between;
	align-items: center;
	font-size: 12px;
	font-weight: 600;
	color: #cbd5e1;
	margin-bottom: 6px;
}
.lock-tag {
	display: inline-flex;
	align-items: center;
	gap: 4px;
	padding: 2px 7px;
	border-radius: 4px;
	background: rgba(245, 158, 11, 0.12);
	border: 1px solid rgba(245, 158, 11, 0.3);
	color: #fbbf24;
	font-size: 10px;
	font-weight: 700;
}
.input-locked {
	width: 100%;
	padding: 9px 12px;
	border-radius: 8px;
	background: #111a2e;
	border: 1px solid rgba(255, 255, 255, 0.08);
	color: #94a3b8;
	font-size: 13px;
	box-sizing: border-box;
	cursor: not-allowed;
	font-weight: 600;
}
.field-hint {
	display: block;
	margin-top: 5px;
	font-size: 11px;
	color: #64748b;
	line-height: 1.4;
}

/* OLD PASSWORD DISPLAY BOX */
.old-password-box {
	background: rgba(245, 158, 11, 0.08);
	border: 1px solid rgba(245, 158, 11, 0.25);
	border-radius: 10px;
	padding: 12px 14px;
}
.old-password-header {
	display: flex;
	justify-content: space-between;
	align-items: center;
	margin-bottom: 8px;
}
.label-with-icon {
	display: flex;
	align-items: center;
	gap: 6px;
	font-size: 12px;
	color: #fbbf24;
}
.badge-status-current {
	font-size: 10px;
	font-weight: 700;
	padding: 2px 6px;
	border-radius: 4px;
	background: rgba(16, 185, 129, 0.15);
	color: #34d399;
	border: 1px solid rgba(16, 185, 129, 0.3);
}
.old-password-input-group {
	display: flex;
	align-items: center;
	gap: 8px;
}
.old-password-display {
	flex-grow: 1;
	display: flex;
	align-items: center;
	background: #090e18;
	border: 1px solid rgba(245, 158, 11, 0.35);
	padding: 8px 12px;
	border-radius: 7px;
	min-height: 38px;
	box-sizing: border-box;
	font-size: 13px;
	letter-spacing: 0.5px;
}
.text-highlight {
	color: #fbbf24;
}
.text-bold {
	font-weight: 700;
}
.btn-icon-control {
	display: inline-flex;
	align-items: center;
	gap: 6px;
	padding: 8px 12px;
	border-radius: 7px;
	background: rgba(255, 255, 255, 0.08);
	border: 1px solid rgba(255, 255, 255, 0.15);
	color: #e2e8f0;
	font-size: 11px;
	font-weight: 600;
	cursor: pointer;
	transition: all 0.15s ease;
	flex-shrink: 0;
}
.btn-icon-control:hover {
	background: rgba(255, 255, 255, 0.15);
	color: #ffffff;
}

/* SECTION DIVIDER */
.divider-form {
	text-align: center;
	border-bottom: 1px solid rgba(255, 255, 255, 0.08);
	line-height: 0.1em;
	margin: 10px 0 8px;
}
.divider-content {
	display: inline-flex;
	align-items: center;
	gap: 6px;
	background: #0f172a;
	padding: 0 10px;
}
.theme-light .divider-content {
	background: #ffffff;
}
.divider-form span {
	color: #10b981;
	font-size: 11px;
	font-weight: 700;
	letter-spacing: 0.4px;
	text-transform: uppercase;
}

/* PASSWORD INPUT */
.password-input-wrap {
	position: relative;
	display: flex;
	align-items: center;
}
.input-password {
	width: 100%;
	padding: 9px 40px 9px 12px;
	border-radius: 8px;
	background: #131d33;
	border: 1px solid rgba(255, 255, 255, 0.14);
	color: #ffffff;
	font-size: 13px;
	box-sizing: border-box;
	outline: none;
	transition: all 0.15s ease;
}
.input-password:focus {
	border-color: #10b981;
	box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
}
.btn-toggle-eye {
	position: absolute;
	right: 10px;
	background: transparent;
	border: none;
	color: #94a3b8;
	cursor: pointer;
	display: grid;
	place-items: center;
	padding: 4px;
	transition: color 0.15s ease;
}
.btn-toggle-eye:hover {
	color: #ffffff;
}
.password-error-msg {
	display: flex;
	align-items: center;
	gap: 5px;
	margin-top: 6px;
	font-size: 11px;
	color: #f87171;
	font-weight: 600;
}

/* MODAL FOOTER */
.modal-footer-actions {
	display: flex;
	justify-content: flex-end;
	gap: 10px;
	padding-top: 18px;
	margin-top: 16px;
	border-top: 1px solid rgba(255, 255, 255, 0.08);
}
.btn-modal-cancel {
	padding: 9px 18px;
	border-radius: 8px;
	background: rgba(255, 255, 255, 0.06);
	border: 1px solid rgba(255, 255, 255, 0.1);
	color: #cbd5e1;
	font-size: 13px;
	font-weight: 600;
	cursor: pointer;
}
.btn-modal-cancel:hover {
	background: rgba(255, 255, 255, 0.12);
}
.btn-modal-submit {
	display: inline-flex;
	align-items: center;
	gap: 6px;
	padding: 9px 20px;
	border-radius: 8px;
	background: #10b981;
	border: none;
	color: #ffffff;
	font-size: 13px;
	font-weight: 700;
	cursor: pointer;
	box-shadow: 0 4px 14px rgba(16, 185, 129, 0.35);
}
.btn-modal-submit:hover:not(:disabled) {
	background: #059669;
}
.btn-modal-submit:disabled {
	opacity: 0.5;
	cursor: not-allowed;
}
.spinner-sm {
	width: 14px;
	height: 14px;
	border: 2px solid rgba(255, 255, 255, 0.3);
	border-top-color: #ffffff;
	border-radius: 50%;
	animation: spin 0.7s linear infinite;
}

/* DELETE MODAL */
.modal-delete-confirm {
	max-width: 460px;
}
.delete-top {
	align-items: center;
	gap: 14px;
}
.delete-icon-box {
	display: grid;
	place-items: center;
	width: 40px;
	height: 40px;
	border-radius: 10px;
	background: rgba(239, 68, 68, 0.15);
	color: #ef4444;
	flex-shrink: 0;
}
.delete-body {
	padding: 18px 24px;
}
.delete-body p {
	margin: 0 0 12px;
	font-size: 13px;
	color: #cbd5e1;
}
.target-user-card {
	display: flex;
	align-items: center;
	gap: 12px;
	padding: 12px 14px;
	background: rgba(255, 255, 255, 0.04);
	border: 1px solid rgba(255, 255, 255, 0.08);
	border-radius: 10px;
	margin-bottom: 14px;
}
.danger-warning-box {
	display: flex;
	gap: 10px;
	padding: 12px;
	border-radius: 8px;
	background: rgba(239, 68, 68, 0.1);
	border: 1px solid rgba(239, 68, 68, 0.25);
}
.danger-icon {
	display: grid;
	place-items: center;
	flex-shrink: 0;
}
.danger-warning-box p {
	margin: 0;
	font-size: 11px;
	color: #fca5a5;
	line-height: 1.4;
}
.btn-modal-danger {
	display: inline-flex;
	align-items: center;
	gap: 6px;
	padding: 9px 20px;
	border-radius: 8px;
	background: #ef4444;
	border: none;
	color: #ffffff;
	font-size: 13px;
	font-weight: 700;
	cursor: pointer;
	box-shadow: 0 4px 14px rgba(239, 68, 68, 0.35);
}
.btn-modal-danger:hover:not(:disabled) {
	background: #dc2626;
}
.btn-modal-danger:disabled {
	opacity: 0.6;
	cursor: not-allowed;
}

/* RESPONSIVE */
@media (max-width: 1024px) {
	.summary {
		grid-template-columns: repeat(2, 1fr);
	}
}
@media (max-width: 768px) {
	.sidebar {
		position: fixed;
		left: -260px;
		transition: left 0.25s ease;
	}
	.sidebar.open {
		left: 0;
	}
	.mobile-menu {
		display: block;
	}
	.global-search {
		display: none;
	}
	.page-content {
		padding: 16px;
	}
	.summary {
		grid-template-columns: 1fr;
	}
	.form-grid {
		grid-template-columns: 1fr;
	}
	.form-field-locked {
		grid-column: span 1;
	}
}

/* ==================== POPUP MODAL PERINGATAN DUPLIKAT ==================== */
.duplicate-warning-modal {
	background: #1e293b;
	border: 1px solid rgba(245, 158, 11, 0.4);
	border-radius: 20px;
	width: 100%;
	max-width: 490px;
	box-shadow: 0 25px 60px -12px rgba(0, 0, 0, 0.75), 0 0 25px rgba(245, 158, 11, 0.2);
	animation: modalPop 0.22s cubic-bezier(0.16, 1, 0.3, 1);
	overflow: hidden;
}
.theme-light .duplicate-warning-modal {
	background: #ffffff;
	border-color: rgba(245, 158, 11, 0.6);
	box-shadow: 0 25px 60px -12px rgba(100, 116, 139, 0.3), 0 0 25px rgba(245, 158, 11, 0.15);
}
.duplicate-modal-header {
	display: flex;
	align-items: center;
	gap: 14px;
	padding: 20px 24px;
	border-bottom: 1px solid rgba(255, 255, 255, 0.08);
	background: rgba(245, 158, 11, 0.08);
}
.theme-light .duplicate-modal-header {
	border-bottom-color: #f1f5f9;
	background: #fffbeb;
}
.duplicate-icon-wrapper {
	width: 44px;
	height: 44px;
	border-radius: 12px;
	background: linear-gradient(135deg, rgba(245, 158, 11, 0.25), rgba(239, 68, 68, 0.25));
	border: 1px solid rgba(245, 158, 11, 0.5);
	display: flex;
	align-items: center;
	justify-content: center;
	flex-shrink: 0;
}
.duplicate-warning-icon {
	width: 24px;
	height: 24px;
	color: #f59e0b;
}
.duplicate-modal-title {
	font-size: 17px;
	font-weight: 700;
	color: #f8fafc;
	margin: 0;
}
.theme-light .duplicate-modal-title {
	color: #0f172a;
}
.duplicate-modal-subtitle {
	font-size: 12px;
	color: #94a3b8;
	margin: 3px 0 0;
}
.theme-light .duplicate-modal-subtitle {
	color: #64748b;
}
.duplicate-modal-body {
	padding: 20px 24px;
}
.duplicate-alert-banner {
	background: rgba(245, 158, 11, 0.1);
	border: 1px solid rgba(245, 158, 11, 0.3);
	border-radius: 12px;
	padding: 14px 16px;
	margin-bottom: 16px;
}
.theme-light .duplicate-alert-banner {
	background: #fffbeb;
	border-color: #fde68a;
}
.duplicate-field-tag {
	display: inline-block;
	background: #f59e0b;
	color: #0f172a;
	font-size: 10px;
	font-weight: 800;
	text-transform: uppercase;
	letter-spacing: 0.5px;
	padding: 2px 8px;
	border-radius: 6px;
	margin-bottom: 6px;
}
.duplicate-main-message {
	font-size: 13.5px;
	font-weight: 600;
	color: #fde68a;
	line-height: 1.5;
}
.theme-light .duplicate-main-message {
	color: #92400e;
}
.duplicate-detail-box {
	background: rgba(15, 23, 42, 0.5);
	border: 1px solid rgba(255, 255, 255, 0.08);
	border-radius: 10px;
	padding: 12px 14px;
	margin-bottom: 14px;
}
.theme-light .duplicate-detail-box {
	background: #f8fafc;
	border-color: #e2e8f0;
}
.duplicate-detail-row {
	display: flex;
	justify-content: space-between;
	align-items: center;
	font-size: 12.5px;
	padding: 4px 0;
}
.detail-label {
	color: #94a3b8;
}
.theme-light .detail-label {
	color: #64748b;
}
.detail-val-input {
	color: #ef4444;
}
.detail-val-existing {
	color: #38bdf8;
}
.theme-light .detail-val-existing {
	color: #0284c7;
}
.duplicate-instruction {
	font-size: 12px;
	color: #64748b;
	margin: 0;
	line-height: 1.5;
}
.theme-light .duplicate-instruction {
	color: #64748b;
}
.duplicate-modal-footer {
	padding: 16px 24px;
	border-top: 1px solid rgba(255, 255, 255, 0.08);
	background: rgba(0, 0, 0, 0.2);
	display: flex;
	justify-content: flex-end;
}
.theme-light .duplicate-modal-footer {
	border-top-color: #f1f5f9;
	background: #f8fafc;
}
.btn-duplicate-ok {
	background: linear-gradient(135deg, #f59e0b, #d97706);
	color: #ffffff;
	border: none;
	padding: 10px 20px;
	border-radius: 10px;
	font-size: 13px;
	font-weight: 600;
	display: inline-flex;
	align-items: center;
	gap: 8px;
	cursor: pointer;
	box-shadow: 0 4px 14px rgba(245, 158, 11, 0.35);
	transition: all 0.15s ease;
}
.btn-duplicate-ok:hover {
	transform: translateY(-1px);
	box-shadow: 0 6px 18px rgba(245, 158, 11, 0.45);
}
</style>
