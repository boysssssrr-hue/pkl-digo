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
				<!-- Brand Logo using Row 1 Col 1 Diamond Lightning -->
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
				<NuxtLink to="/Admin/fullscreen" class="nav-fullscreen-entry">
					<i class="nav-svg-box">
						<svg viewBox="0 0 24 24" class="svg-icon-nav" fill="currentColor"><path d="M7 14H5v5h5v-2H7v-3zm-2-4h2V7h3V5H5v5zm12 7h-3v2h5v-5h-2v3zM14 5v2h3v3h2V5h-5z"/></svg>
					</i>
					<span>Full Screen</span>
				</NuxtLink>
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
			<header class="topbar">
				<button class="mobile-menu" type="button" @click="menuOpen = !menuOpen">☰</button>
				<div class="global-search">
					<span class="search-lens">
						<svg viewBox="0 0 24 24" class="svg-icon-sm" fill="currentColor"><path d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
					</span>
					<input v-model="globalSearch" placeholder="Cari fasilitas, ATK, ruangan, kendaraan dinas..." />
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
								<path d="M12 7c-2.76 0-5 2.24-5 5s2.24 5 5 5 5-2.24 5-5-2.24-5-5-5zM2 13h2c.55 0 1-.45 1-1s-.45-1-1-1H2c-.55 0-1 .45-1 1s.45 1 1 1zm18 0h2c.55 0 1-.45 1-1s-.45-1-1-1h-2c-.55 0-1 .45-1 1s.45 1 1 1zM11 2v2c0 .55.45 1 1 1s1-.45 1-1V2c0-.55-.45-1-1-1s-1 .45-1 1zm0 18v2c0 .55.45 1 1 1s1-.45 1-1v-2c0-.55-.45-1-1-1s-1 .45-1 1zM5.99 4.58a.996.996 0 0 0-1.41 0 .996.996 0 0 0 0 1.41l1.06 1.06c.39.39 1.03.39 1.41 0s.39-1.03 0-1.41L5.99 4.58zm12.37 12.37a.996.996 0 0 0-1.41 0 .996.996 0 0 0 0 1.41l1.06 1.06c.39.39 1.03.39 1.41 0s.39-1.03 0-1.41l-1.06-1.06zm1.06-10.96a.996.996 0 0 0 0-1.41.996.996 0 0 0-1.41 0l-1.06 1.06c-.39.39-.39 1.03 0 1.41s1.03.39 1.41 0l1.06-1.06zM7.05 18.36a.996.996 0 0 0 0-1.41.996.996 0 0 0-1.41 0l-1.06 1.06c-.39.39-.39 1.03 0 1.41s1.03.39 1.41 0l1.06-1.06z"/>
							</svg>
						</span>
						<span class="theme-icon moon" v-else>
							<svg viewBox="0 0 24 24" class="svg-icon-theme" fill="currentColor">
								<path d="M12.3 2a10 10 0 0 0-1.9 19.8 10 10 0 0 0 10.9-10.9A10 10 0 0 0 12.3 2z"/>
							</svg>
						</span>
						<span class="theme-label">{{ isDark ? 'Gelap' : 'Terang' }}</span>
					</button>

					<!-- Icon Bell with Interactive Notification Dropdown -->
					<div class="notif-wrapper" style="position: relative;">
						<button
							type="button"
							class="bell-btn"
							:class="{ 'has-badge': pendingNotifs.length > 0 }"
							title="Notifikasi Permohonan Masuk"
							@click="showNotifDropdown = !showNotifDropdown"
						>
							<svg viewBox="0 0 24 24" class="svg-icon-bell" fill="currentColor">
								<path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.63-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.64 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/>
							</svg>
							<span v-if="pendingNotifs.length > 0" class="notif-pill-badge">
								{{ pendingNotifs.length > 9 ? '9+' : pendingNotifs.length }}
							</span>
						</button>

						<!-- Dropdown Panel Notifikasi Permohonan Masuk -->
						<div v-if="showNotifDropdown" class="notif-popover">
							<div class="notif-header">
								<div class="notif-title-row">
									<strong class="notif-title">Permohonan Masuk</strong>
									<span v-if="pendingNotifs.length > 0" class="notif-counter-tag">{{ pendingNotifs.length }} Baru</span>
								</div>
							</div>

							<div class="notif-body">
								<div v-if="pendingNotifs.length === 0" class="notif-empty">
									<span style="font-size: 20px;">✅</span>
									<p style="margin: 4px 0 0; font-size: 11px; color: #94a3b8;">Semua permohonan kendaraan & fasilitas telah ditindaklanjuti.</p>
								</div>
								<div v-else class="notif-list">
									<div v-for="b in pendingNotifs.slice(0, 5)" :key="b.id" class="notif-item">
										<div class="notif-item-icon">🚗</div>
										<div class="notif-item-content">
											<div class="notif-item-top">
												<strong class="notif-name">{{ b.borrower_name || b.requester_name || (b.user?.name !== 'admin' ? b.user?.name : (b.passengers || 'Pegawai')) }}</strong>
												<small class="notif-time">{{ formatDateTimeShort(b.created_at) }}</small>
											</div>
											<div class="notif-item-dest">
												📍 {{ b.destination }}
											</div>
											<div class="notif-item-dept">
												Divisi: {{ b.borrower_division || b.requester_division || b.user?.division?.name || 'Operasional' }}
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="notif-footer">
								<NuxtLink to="/Admin/laporan" @click="showNotifDropdown = false" class="notif-all-btn">
									<span>Kelola & Setujui di Menu Laporan</span>
									<span>➔</span>
								</NuxtLink>
							</div>
						</div>
					</div>
					<span class="user-dot">A</span>
					<b>Admin</b>
					<span class="chevron-down">▾</span>
				</div>
			</header>

			<div class="page-content">
				<!-- Hero Header Section -->
				<section class="hero">
					<div>
						<p>Selamat datang di Panel Utama, Admin <em>✦</em></p>
						<h1>Pengelolaan Fasilitas & Alat Kantor</h1>
						<span>Pusat inventaris terpadu untuk memantau ATK, Ruangan Rapat, Kendaraan Operasional, dan Alat Kebersihan secara real-time.</span>
						<div class="hero-fs-row">
							<NuxtLink to="/Admin/fullscreen" class="btn-hero-launch-fs">
								<svg viewBox="0 0 24 24" class="svg-icon-sm" fill="currentColor"><path d="M7 14H5v5h5v-2H7v-3zm-2-4h2V7h3V5H5v5zm12 7h-3v2h5v-5h-2v3zM14 5v2h3v3h2V5h-5z"/></svg>
								<span>🖥️ Buka Live Monitoring Full Screen</span>
							</NuxtLink>
						</div>
					</div>
					<div class="hero-art">
						<!-- Angled Pencil icon from Row 3 Col 2 & Warehouse from Row 3 Col 3 -->
						<svg viewBox="0 0 24 24" class="art-svg" fill="currentColor">
							<path d="M19.7 5.7L18.3 4.3c-.8-.8-2.1-.8-2.9 0L4.5 15.2c-.3.3-.5.7-.5 1.1l-1 4.7 4.7-1c.4 0 .8-.2 1.1-.5L19.7 8.6c.8-.8.8-2.1 0-2.9zM7.1 18.5l-1.6.3-.3-1.6L14.7 7.7l1.9 1.9L7.1 18.5z"/>
						</svg>
					</div>
				</section>

				<!-- Summary Stats Cards (Matching icons: Pencil, Building, Car, Tools) -->
				<section class="summary">
					<article>
						<!-- Row 3 Col 2: Pencil for ATK -->
						<span class="stat-icon green">
							<svg viewBox="0 0 24 24" class="svg-icon-stat" fill="currentColor">
								<path d="M19.7 5.7L18.3 4.3c-.8-.8-2.1-.8-2.9 0L4.5 15.2c-.3.3-.5.7-.5 1.1l-1 4.7 4.7-1c.4 0 .8-.2 1.1-.5L19.7 8.6c.8-.8.8-2.1 0-2.9zM7.1 18.5l-1.6.3-.3-1.6L14.7 7.7l1.9 1.9L7.1 18.5z"/>
							</svg>
						</span>
						<div>
							<small>Total Inventaris ATK</small>
							<strong>{{ atkItems.length }} Item</strong>
							<small class="text-green">{{ atkItems.reduce((acc, i) => acc + (Number(i.stock) || 0), 0) }} unit tersedia</small>
						</div>
					</article>

					<article>
						<!-- Row 3 Col 3: Building / Warehouse for Ruangan -->
						<span class="stat-icon cyan">
							<svg viewBox="0 0 24 24" class="svg-icon-stat" fill="currentColor">
								<path d="M12 3L2 8v13h20V8L12 3zm6 16H6v-7h12v7zm-2-5H8v1h8v-1zm0 2H8v1h8v-1z"/>
							</svg>
						</span>
						<div>
							<small>Ruangan Rapat & Aula</small>
							<strong>{{ roomItems.length }} Ruangan</strong>
							<small class="text-cyan">{{ roomItems.filter(r => r.status === 'Tersedia').length }} ruangan siap pakai</small>
						</div>
					</article>

					<article>
						<!-- Row 2 Col 3: Car for Mobil -->
						<span class="stat-icon amber">
							<svg viewBox="0 0 24 24" class="svg-icon-stat" fill="currentColor">
								<path d="M5 11l1.5-4.5C6.8 5.6 7.6 5 8.5 5h7c.9 0 1.7.6 2 1.5L19 11h1c.6 0 1 .4 1 1v4c0 .6-.4 1-1 1h-1v2c0 .6-.4 1-1 1h-1c-.6 0-1-.4-1-1v-2H8v2c0 .6-.4 1-1 1H6c-.6 0-1-.4-1-1v-2H4c-.6 0-1-.4-1-1v-4c0-.6.4-1 1-1h1zm3.2-4l-1 3h9.6l-1-3H8.2zM6.5 15a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zm11 0a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zm-7.5-1h4v-1.5h-4V14z"/>
							</svg>
						</span>
						<div>
							<small>Mobil Operasional</small>
							<strong>{{ vehicleItems.length }} Kendaraan</strong>
							<small class="text-amber">{{ vehicleItems.filter(v => v.status === 'Tersedia').length }} unit siap jalan</small>
						</div>
					</article>

					<article>
						<!-- Row 4 Col 3: Crossed Tools for Cleaning Service -->
						<span class="stat-icon purple">
							<svg viewBox="0 0 24 24" class="svg-icon-stat" fill="currentColor">
								<path d="M22.7 19l-9.1-9.1c.9-2.3.4-5-1.5-6.9-2-2-5-2.4-7.4-1.3L9 6 6 9 1.6 4.7C.4 7.1.9 10.1 2.9 12.1c1.9 1.9 4.6 2.4 6.9 1.5l9.1 9.1c.4.4 1 .4 1.4 0l2.3-2.3c.5-.4.5-1.1.1-1.4z"/>
							</svg>
						</span>
						<div>
							<small>Alat Cleaning Service</small>
							<strong>{{ cleaningItems.length }} Jenis Alat</strong>
							<small class="text-purple">{{ cleaningItems.reduce((acc, c) => acc + (Number(c.quantity) || 0), 0) }} total unit alat</small>
						</div>
					</article>
				</section>


				<!-- CORE SECTION: MANAJEMEN ALAT (DARK MODERN TRADING-STYLE TABLE) -->
				<section class="crypto-panel">
					<!-- Top Title & Navigation Bar -->
					<div class="crypto-header">
						<div class="header-left">
							<div class="title-with-badge">
								<h2>Manajemen Alat</h2>
								<!-- Row 1 Col 1 Diamond Lightning for Live DB badge -->
								<span class="live-indicator">
									<svg viewBox="0 0 24 24" class="svg-icon-bolt">
										<path d="M12 2L2 12l10 10 10-10L12 2zm1 14.5l-4-5.5h3.2V6.5l4 5.5h-3.2v4.5z" fill="url(#gradient-lightning)"/>
									</svg>
									Live DB
								</span>
							</div>
							<p class="subtitle">Pilih navigasi kategori di bawah ini untuk menampilkan dan mengelola data tabel terkait.</p>
						</div>

						<div class="crypto-actions-top">
							<button class="btn-refresh" type="button" @click="fetchData" :disabled="isLoading" title="Muat Ulang Data">
								<span :class="{ spinning: isLoading }">↻</span> Sinkron Database
							</button>
							<button class="btn-add-crypto" type="button" @click="openAddModal">
								<span>＋</span> Tambah {{ activeTabLabel }}
							</button>
						</div>
					</div>

					<!-- TAB NAVBAR: 4 MENU (Icons from Row 3 Col 2, Row 3 Col 3, Row 2 Col 3, Row 4 Col 3) -->
					<div class="navbar-pills-wrap">
						<div class="navbar-pills">
							<!-- 1. ATK -> Pencil (Row 3 Col 2) -->
							<button
								type="button"
								class="nav-pill"
								:class="{ active: currentTab === 'atk' }"
								@click="currentTab = 'atk'"
							>
								<span class="pill-icon-svg">
									<svg viewBox="0 0 24 24" class="svg-icon-tab" fill="currentColor">
										<path d="M19.7 5.7L18.3 4.3c-.8-.8-2.1-.8-2.9 0L4.5 15.2c-.3.3-.5.7-.5 1.1l-1 4.7 4.7-1c.4 0 .8-.2 1.1-.5L19.7 8.6c.8-.8.8-2.1 0-2.9zM7.1 18.5l-1.6.3-.3-1.6L14.7 7.7l1.9 1.9L7.1 18.5z"/>
									</svg>
								</span>
								<span class="pill-text">ATK (Alat Tulis)</span>
								<span class="pill-count">{{ atkItems.length }}</span>
							</button>

							<!-- 2. Ruangan -> Building/Garage (Row 3 Col 3) -->
							<button
								type="button"
								class="nav-pill"
								:class="{ active: currentTab === 'ruangan' }"
								@click="currentTab = 'ruangan'"
							>
								<span class="pill-icon-svg">
									<svg viewBox="0 0 24 24" class="svg-icon-tab" fill="currentColor">
										<path d="M12 3L2 8v13h20V8L12 3zm6 16H6v-7h12v7zm-2-5H8v1h8v-1zm0 2H8v1h8v-1z"/>
									</svg>
								</span>
								<span class="pill-text">Ruangan</span>
								<span class="pill-count">{{ roomItems.length }}</span>
							</button>

							<!-- 3. Mobil -> Car (Row 2 Col 3) -->
							<button
								type="button"
								class="nav-pill"
								:class="{ active: currentTab === 'mobil' }"
								@click="currentTab = 'mobil'"
							>
								<span class="pill-icon-svg">
									<svg viewBox="0 0 24 24" class="svg-icon-tab" fill="currentColor">
										<path d="M5 11l1.5-4.5C6.8 5.6 7.6 5 8.5 5h7c.9 0 1.7.6 2 1.5L19 11h1c.6 0 1 .4 1 1v4c0 .6-.4 1-1 1h-1v2c0 .6-.4 1-1 1h-1c-.6 0-1-.4-1-1v-2H8v2c0 .6-.4 1-1 1H6c-.6 0-1-.4-1-1v-2H4c-.6 0-1-.4-1-1v-4c0-.6.4-1 1-1h1zm3.2-4l-1 3h9.6l-1-3H8.2zM6.5 15a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zm11 0a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zm-7.5-1h4v-1.5h-4V14z"/>
									</svg>
								</span>
								<span class="pill-text">Mobil Operasional</span>
								<span class="pill-count">{{ vehicleItems.length }}</span>
							</button>

							<!-- 4. Cleaning Service -> Crossed Tools (Row 4 Col 3) -->
							<button
								type="button"
								class="nav-pill"
								:class="{ active: currentTab === 'cleaning' }"
								@click="currentTab = 'cleaning'"
							>
								<span class="pill-icon-svg">
									<svg viewBox="0 0 24 24" class="svg-icon-tab" fill="currentColor">
										<path d="M22.7 19l-9.1-9.1c.9-2.3.4-5-1.5-6.9-2-2-5-2.4-7.4-1.3L9 6 6 9 1.6 4.7C.4 7.1.9 10.1 2.9 12.1c1.9 1.9 4.6 2.4 6.9 1.5l9.1 9.1c.4.4 1 .4 1.4 0l2.3-2.3c.5-.4.5-1.1.1-1.4z"/>
									</svg>
								</span>
								<span class="pill-text">Cleaning Service</span>
								<span class="pill-count">{{ cleaningItems.length }}</span>
							</button>
						</div>
					</div>

					<!-- CONTROLS & FILTER ROW -->
					<div class="crypto-toolbar">
						<div class="search-box">
							<span class="search-ico">
								<svg viewBox="0 0 24 24" class="svg-icon-sm" fill="currentColor"><path d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
							</span>
							<input
								v-model="query"
								:placeholder="`Cari di tabel ${activeTabLabel.toLowerCase()} (nama, kode, spesifikasi)...`"
							/>
							<span v-if="query" class="clear-search" @click="query = ''">×</span>
						</div>

						<div class="filter-group">
							<div class="select-wrapper">
								<span class="filter-label">Status:</span>
								<select v-model="statusFilter">
									<option value="Semua">Semua Status</option>
									<option value="Tersedia">Tersedia</option>
									<option value="Sedang Digunakan">Sedang Digunakan / Dipakai</option>
									<option value="Stok Menipis">Stok Menipis / Perawatan</option>
								</select>
							</div>

							<div class="select-wrapper">
								<span class="filter-label">Baris:</span>
								<select v-model="perPage">
									<option :value="5">5</option>
									<option :value="10">10</option>
									<option :value="20">20</option>
								</select>
							</div>
						</div>
					</div>

					<!-- THE TABLE (DARK THEME WITH ICONS MATCHING GRID REFERENCE) -->
					<div class="crypto-table-wrap">
						<table class="crypto-table">
							<!-- 1. ATK TABLE HEADER -->
							<thead v-if="currentTab === 'atk'">
								<tr>
									<th class="th-check"><input type="checkbox" /></th>
									<th class="th-num">#</th>
									<th>Nama Alat Tulis</th>
									<th>Kategori</th>
									<th>Stok</th>
									<th>Satuan</th>
									<th>Status Ketersediaan</th>
									<th class="th-actions">Aksi</th>
								</tr>
							</thead>

							<!-- 2. RUANGAN TABLE HEADER -->
							<thead v-else-if="currentTab === 'ruangan'">
								<tr>
									<th class="th-check"><input type="checkbox" /></th>
									<th class="th-num">#</th>
									<th>Nama Ruangan & Kode</th>
									<th>Kapasitas</th>
									<th>Lantai / Lokasi</th>
									<th>Fasilitas Utama</th>
									<th>Status Ruangan</th>
									<th class="th-actions">Aksi</th>
								</tr>
							</thead>

							<!-- 3. MOBIL TABLE HEADER -->
							<thead v-else-if="currentTab === 'mobil'">
								<tr>
									<th class="th-check"><input type="checkbox" /></th>
									<th class="th-num">#</th>
									<th>Kendaraan & Plat Nomor</th>
									<th>Merk & Model</th>
									<th>Transmisi & Kursi</th>
									<th>Bahan Bakar</th>
									<th>Status Operasional</th>
									<th class="th-actions">Aksi</th>
								</tr>
							</thead>

							<!-- 4. CLEANING SERVICE TABLE HEADER -->
							<thead v-else-if="currentTab === 'cleaning'">
								<tr>
									<th class="th-check"><input type="checkbox" /></th>
									<th class="th-num">#</th>
									<th>Alat Kebersihan & Kode</th>
									<th>Kategori Alat</th>
									<th>Jumlah Unit</th>
									<th>Lokasi Penyimpanan</th>
									<th>Kondisi & Status</th>
									<th class="th-actions">Aksi</th>
								</tr>
							</thead>

							<!-- TABLE BODY (DYNAMIC ROWS WITH MATCHING ICONS) -->
							<tbody>
								<tr v-if="isLoading">
									<td :colspan="8" class="empty-cell">
										<div class="loading-state">
											<span class="spinner"></span>
											<p>Memuat data dari database...</p>
										</div>
									</td>
								</tr>

								<tr v-else-if="paginatedItems.length === 0">
									<td :colspan="8" class="empty-cell">
										<div class="empty-state">
											<!-- Row 1 Col 4: Folder icon for empty state -->
											<span class="empty-icon-svg">
												<svg viewBox="0 0 24 24" class="svg-icon-lg" fill="currentColor">
													<path d="M10 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2h-8l-2-2z"/>
												</svg>
											</span>
											<p>Tidak ada data {{ activeTabLabel }} yang sesuai dengan pencarian atau filter.</p>
											<button class="btn-reset" type="button" @click="resetFilters">Reset Filter</button>
										</div>
									</td>
								</tr>

								<!-- 1. ATK ROWS -> Pencil icon avatar (Row 3 Col 2) -->
								<template v-else-if="currentTab === 'atk'">
									<tr v-for="(item, idx) in paginatedItems" :key="item.id || idx" class="table-row">
										<td class="td-check"><input type="checkbox" /></td>
										<td class="td-num">{{ (currentPage - 1) * perPage + idx + 1 }}</td>
										<td class="td-primary">
											<div class="item-id-cell">
												<div class="item-avatar atk" title="Alat Tulis">
													<img
														:src="item.image || '/images/atk/default-atk.svg'"
														:alt="item.name"
														class="item-img-admin"
														loading="lazy"
														@error="handleImgError($event, '/images/atk/default-atk.svg')"
													/>
												</div>
												<div class="item-info">
													<strong class="item-title">{{ item.name }}</strong>
													<span class="item-subtitle">{{ item.barcode || `ATK-${String(item.id).padStart(3, '0')}` }}</span>
												</div>
											</div>
										</td>
										<td>
											<span class="cat-badge">{{ item.category || 'Alat Tulis' }}</span>
										</td>
										<td>
											<span class="metric-value font-mono">{{ item.stock }}</span>
										</td>
										<td>
											<span class="unit-text">{{ item.unit || 'Pcs' }}</span>
										</td>
										<td>
											<span class="status-badge" :class="getStatusBadgeClass(item.status, 'atk')">
												<i class="status-dot"></i> {{ formatStatusText(item.status) }}
											</span>
										</td>
										<td class="td-actions">
											<div class="actions-group">
												<!-- Barcode View & Print Button -->
												<button class="btn-act barcode" title="Lihat & Cetak Barcode Resmi" @click="openBarcodeModal(item)">
													<svg viewBox="0 0 24 24" class="svg-act-icon" fill="currentColor">
														<path d="M2 5v14h2V5H2zm4 0v14h1V5H6zm3 0v14h2V5H9zm4 0v14h1V5h-1zm3 0v14h2V5h-2zm4 0v14h2V5h-2z"/>
													</svg>
												</button>
												<!-- Row 3 Col 2: Pencil for Edit -->
												<button class="btn-act edit" title="Edit Data" @click="openEditModal(item)">
													<svg viewBox="0 0 24 24" class="svg-act-icon" fill="currentColor">
														<path d="M19.7 5.7L18.3 4.3c-.8-.8-2.1-.8-2.9 0L4.5 15.2c-.3.3-.5.7-.5 1.1l-1 4.7 4.7-1c.4 0 .8-.2 1.1-.5L19.7 8.6c.8-.8.8-2.1 0-2.9zM7.1 18.5l-1.6.3-.3-1.6L14.7 7.7l1.9 1.9L7.1 18.5z"/>
													</svg>
												</button>
												<!-- Row 1 Col 5: Trash Bin for Delete -->
												<button class="btn-act delete" title="Hapus Data" @click="deleteItem(item)">
													<svg viewBox="0 0 24 24" class="svg-act-icon" fill="currentColor">
														<path d="M9 3h6v2h5v2H4V5h5V3zm-3 5h12l-1.2 12.5c-.1.8-.8 1.5-1.6 1.5H8.8c-.8 0-1.5-.7-1.6-1.5L6 8zm6 3l-3 3h2v4h2v-4h2l-3-3z"/>
													</svg>
												</button>
											</div>
										</td>
									</tr>
								</template>

								<!-- 2. RUANGAN ROWS -> Building/Garage avatar (Row 3 Col 3) -->
								<template v-else-if="currentTab === 'ruangan'">
									<tr v-for="(item, idx) in paginatedItems" :key="item.id || idx" class="table-row">
										<td class="td-check"><input type="checkbox" /></td>
										<td class="td-num">{{ (currentPage - 1) * perPage + idx + 1 }}</td>
										<td class="td-primary">
											<div class="item-id-cell">
												<div class="item-avatar room" title="Ruangan">
													<img
														:src="item.image || '/images/rooms/default-room.svg'"
														:alt="item.name"
														class="item-img-admin"
														loading="lazy"
														@error="handleImgError($event, '/images/rooms/default-room.svg')"
													/>
												</div>
												<div class="item-info">
													<strong class="item-title">{{ item.name }}</strong>
													<span class="item-subtitle">{{ item.code }}</span>
												</div>
											</div>
										</td>
										<td>
											<span class="metric-pill">{{ item.capacity }} Orang</span>
										</td>
										<td>
											<span class="location-text">📍 {{ item.floor }}</span>
										</td>
										<td>
											<span class="facility-preview" :title="item.facilities">{{ truncateText(item.facilities, 38) }}</span>
										</td>
										<td>
											<span class="status-badge" :class="getStatusBadgeClass(item.status, 'ruangan')">
												<i class="status-dot"></i> {{ item.status }}
											</span>
										</td>
										<td class="td-actions">
											<div class="actions-group">
												<button class="btn-act barcode" title="Lihat & Cetak QR Code Ruangan" @click="openBarcodeModal(item, 'ruangan')">
													<svg viewBox="0 0 24 24" class="svg-act-icon" fill="currentColor">
														<path d="M2 5v14h2V5H2zm4 0v14h1V5H6zm3 0v14h2V5H9zm4 0v14h1V5h-1zm3 0v14h2V5h-2zm4 0v14h2V5h-2z"/>
													</svg>
												</button>
												<button class="btn-act edit" title="Edit Data" @click="openEditModal(item)">
													<svg viewBox="0 0 24 24" class="svg-act-icon" fill="currentColor">
														<path d="M19.7 5.7L18.3 4.3c-.8-.8-2.1-.8-2.9 0L4.5 15.2c-.3.3-.5.7-.5 1.1l-1 4.7 4.7-1c.4 0 .8-.2 1.1-.5L19.7 8.6c.8-.8.8-2.1 0-2.9zM7.1 18.5l-1.6.3-.3-1.6L14.7 7.7l1.9 1.9L7.1 18.5z"/>
													</svg>
												</button>
												<button class="btn-act delete" title="Hapus Data" @click="deleteItem(item)">
													<svg viewBox="0 0 24 24" class="svg-act-icon" fill="currentColor">
														<path d="M9 3h6v2h5v2H4V5h5V3zm-3 5h12l-1.2 12.5c-.1.8-.8 1.5-1.6 1.5H8.8c-.8 0-1.5-.7-1.6-1.5L6 8zm6 3l-3 3h2v4h2v-4h2l-3-3z"/>
													</svg>
												</button>
											</div>
										</td>
									</tr>
								</template>

								<!-- 3. MOBIL ROWS -> Car front avatar (Row 2 Col 3) -->
								<template v-else-if="currentTab === 'mobil'">
									<tr v-for="(item, idx) in paginatedItems" :key="item.id || idx" class="table-row">
										<td class="td-check"><input type="checkbox" /></td>
										<td class="td-num">{{ (currentPage - 1) * perPage + idx + 1 }}</td>
										<td class="td-primary">
											<div class="item-id-cell">
												<div class="item-avatar vehicle" title="Kendaraan">
													<img
														:src="item.image || '/images/vehicles/default-vehicle.svg'"
														:alt="item.name"
														class="item-img-admin"
														loading="lazy"
														@error="handleImgError($event, '/images/vehicles/default-vehicle.svg')"
													/>
												</div>
												<div class="item-info">
													<strong class="item-title">{{ item.name }}</strong>
													<div style="display: flex; align-items: center; gap: 6px; margin-top: 2px;">
														<span class="item-plate">{{ item.license_plate }}</span>
														<span v-if="item.current_driver_name" style="font-size: 10px; font-weight: 700; color: #10b981; background: rgba(16, 185, 129, 0.1); padding: 1px 6px; border-radius: 4px; border: 1px solid rgba(16, 185, 129, 0.25);">
															👤 {{ item.current_driver_name }}
														</span>
													</div>
												</div>
											</div>
										</td>
										<td>
											<span class="brand-badge">{{ item.brand || 'Toyota' }}</span>
										</td>
										<td>
											<span class="metric-pill">{{ item.transmission }} • {{ item.capacity }} Seat</span>
										</td>
										<td>
											<span class="fuel-text">⛽ {{ item.fuel_type }}</span>
										</td>
										<td>
											<span class="status-badge" :class="getStatusBadgeClass(item.status, 'mobil')">
												<i class="status-dot"></i> {{ item.status }}
											</span>
										</td>
										<td class="td-actions">
											<div class="actions-group">
												<button class="btn-act barcode" title="Lihat & Cetak QR Code Kendaraan" @click="openBarcodeModal(item, 'mobil')">
													<svg viewBox="0 0 24 24" class="svg-act-icon" fill="currentColor">
														<path d="M2 5v14h2V5H2zm4 0v14h1V5H6zm3 0v14h2V5H9zm4 0v14h1V5h-1zm3 0v14h2V5h-2zm4 0v14h2V5h-2z"/>
													</svg>
												</button>
												<button class="btn-act edit" title="Edit Data" @click="openEditModal(item)">
													<svg viewBox="0 0 24 24" class="svg-act-icon" fill="currentColor">
														<path d="M19.7 5.7L18.3 4.3c-.8-.8-2.1-.8-2.9 0L4.5 15.2c-.3.3-.5.7-.5 1.1l-1 4.7 4.7-1c.4 0 .8-.2 1.1-.5L19.7 8.6c.8-.8.8-2.1 0-2.9zM7.1 18.5l-1.6.3-.3-1.6L14.7 7.7l1.9 1.9L7.1 18.5z"/>
													</svg>
												</button>
												<button class="btn-act delete" title="Hapus Data" @click="deleteItem(item)">
													<svg viewBox="0 0 24 24" class="svg-act-icon" fill="currentColor">
														<path d="M9 3h6v2h5v2H4V5h5V3zm-3 5h12l-1.2 12.5c-.1.8-.8 1.5-1.6 1.5H8.8c-.8 0-1.5-.7-1.6-1.5L6 8zm6 3l-3 3h2v4h2v-4h2l-3-3z"/>
													</svg>
												</button>
											</div>
										</td>
									</tr>
								</template>

								<!-- 4. CLEANING SERVICE ROWS -> Crossed tools avatar (Row 4 Col 3) -->
								<template v-else-if="currentTab === 'cleaning'">
									<tr v-for="(item, idx) in paginatedItems" :key="item.id || idx" class="table-row">
										<td class="td-check"><input type="checkbox" /></td>
										<td class="td-num">{{ (currentPage - 1) * perPage + idx + 1 }}</td>
										<td class="td-primary">
											<div class="item-id-cell">
												<div class="item-avatar cleaning" title="Alat Cleaning Service">
													<img
														:src="item.image || '/images/cleaning/default-cleaning.svg'"
														:alt="item.name"
														class="item-img-admin"
														loading="lazy"
														@error="handleImgError($event, '/images/cleaning/default-cleaning.svg')"
													/>
												</div>
												<div class="item-info">
													<strong class="item-title">{{ item.name }}</strong>
													<span class="item-subtitle">{{ item.code }}</span>
												</div>
											</div>
										</td>
										<td>
											<span class="cat-badge">{{ item.category }}</span>
										</td>
										<td>
											<span class="metric-value font-mono">{{ item.quantity }} {{ item.unit || 'Unit' }}</span>
										</td>
										<td>
											<span class="location-text">📦 {{ item.location }}</span>
										</td>
										<td>
											<span class="status-badge" :class="getStatusBadgeClass(item.status, 'cleaning')">
												<i class="status-dot"></i> {{ item.status }}
											</span>
										</td>
										<td class="td-actions">
											<div class="actions-group">
												<button class="btn-act edit" title="Edit Data" @click="openEditModal(item)">
													<svg viewBox="0 0 24 24" class="svg-act-icon" fill="currentColor">
														<path d="M19.7 5.7L18.3 4.3c-.8-.8-2.1-.8-2.9 0L4.5 15.2c-.3.3-.5.7-.5 1.1l-1 4.7 4.7-1c.4 0 .8-.2 1.1-.5L19.7 8.6c.8-.8.8-2.1 0-2.9zM7.1 18.5l-1.6.3-.3-1.6L14.7 7.7l1.9 1.9L7.1 18.5z"/>
													</svg>
												</button>
												<button class="btn-act delete" title="Hapus Data" @click="deleteItem(item)">
													<svg viewBox="0 0 24 24" class="svg-act-icon" fill="currentColor">
														<path d="M9 3h6v2h5v2H4V5h5V3zm-3 5h12l-1.2 12.5c-.1.8-.8 1.5-1.6 1.5H8.8c-.8 0-1.5-.7-1.6-1.5L6 8zm6 3l-3 3h2v4h2v-4h2l-3-3z"/>
													</svg>
												</button>
											</div>
										</td>
									</tr>
								</template>
							</tbody>
						</table>
					</div>

					<!-- TABLE FOOTER & PAGINATION -->
					<div class="crypto-footer">
						<div class="footer-info">
							Menampilkan <b>{{ paginatedItems.length }}</b> dari <b>{{ filteredItems.length }}</b> data {{ activeTabLabel }}
						</div>
						<div class="footer-pagination">
							<button
								class="page-btn"
								:disabled="currentPage === 1"
								@click="currentPage--"
							>‹ Prev</button>
							<span class="page-indicator">{{ currentPage }} / {{ totalPages || 1 }}</span>
							<button
								class="page-btn"
								:disabled="currentPage >= totalPages"
								@click="currentPage++"
							>Next ›</button>
						</div>
					</div>
				</section>
			</div>
		</main>

		<!-- POPUP MODAL: TAMBAH & EDIT DATA (DYNAMIC FORM SESUAI TAB) -->
		<div v-if="showModal" class="modal-backdrop" @click.self="closeModal">
			<div class="crypto-modal">
				<div class="modal-top">
					<div>
						<span class="modal-tag">Kategori: {{ activeTabLabel }}</span>
						<h3>{{ editingId ? 'Edit Data ' + activeTabLabel : 'Tambah Data ' + activeTabLabel + ' Baru' }}</h3>
						<p>Lengkapi formulir di bawah ini untuk menyimpan ke database sistem.</p>
					</div>
					<button class="modal-close-btn" type="button" @click="closeModal">×</button>
				</div>

				<form @submit.prevent="submitModalForm" class="modal-body-form">
					<!-- 1. FORM ATK -->
					<template v-if="currentTab === 'atk'">
						<div class="form-grid">
							<label>
								Nama Barang ATK
								<input v-model="formData.name" required placeholder="Contoh: Kertas HVS Sinarmas A4" />
							</label>
							<label>
								Kode Barcode
								<input v-model="formData.barcode" required placeholder="Contoh: ATK-015" />
							</label>
							<label>
								Kategori
								<select v-model="formData.category">
									<option>Alat Tulis</option>
									<option>Buku & Kertas</option>
									<option>Perlengkapan</option>
									<option>Folder & Arsip</option>
								</select>
							</label>
							<label>
								Satuan
								<select v-model="formData.unit">
									<option>Pcs</option>
									<option>Rim</option>
									<option>Box</option>
									<option>Buku</option>
									<option>Set</option>
								</select>
							</label>
							<label>
								Jumlah Stok
								<input v-model.number="formData.stock" type="number" min="0" required />
							</label>
							<label>
								Status Ketersediaan
								<select v-model="formData.status">
									<option value="available">Tersedia (Available)</option>
									<option value="low_stock">Stok Menipis (Low Stock)</option>
									<option value="out_of_stock">Habis (Out of Stock)</option>
								</select>
							</label>
							<label class="col-span-2">
								Deskripsi / Keterangan
								<input v-model="formData.description" placeholder="Keterangan singkat spesifikasi barang..." />
							</label>
						</div>
					</template>

					<!-- 2. FORM RUANGAN -->
					<template v-else-if="currentTab === 'ruangan'">
						<div class="form-grid">
							<label>
								Nama Ruangan
								<input v-model="formData.name" required placeholder="Contoh: Ruang Rapat Komisi A" />
							</label>
							<label>
								Kode Ruangan
								<input v-model="formData.code" required placeholder="Contoh: RNG-06" />
							</label>
							<label>
								Kapasitas (Orang)
								<input v-model.number="formData.capacity" type="number" min="1" required />
							</label>
							<label>
								Lantai / Lokasi
								<input v-model="formData.floor" required placeholder="Contoh: Lantai 2 - Gedung B" />
							</label>
							<label>
								Status Ruangan
								<select v-model="formData.status">
									<option value="Tersedia">Tersedia</option>
									<option value="Sedang Digunakan">Sedang Digunakan</option>
									<option value="Pemeliharaan">Pemeliharaan</option>
								</select>
							</label>
							<label class="col-span-2">
								Fasilitas Utama
								<input v-model="formData.facilities" placeholder="Contoh: Smart TV 65, AC, Polycom, Mic Wireless, Whiteboard" />
							</label>
							<label class="col-span-2">
								Deskripsi
								<input v-model="formData.description" placeholder="Keterangan penggunaan ruangan..." />
							</label>
						</div>
					</template>

					<!-- 3. FORM MOBIL -->
					<template v-else-if="currentTab === 'mobil'">
						<div class="form-grid">
							<label>
								Nama Kendaraan
								<input v-model="formData.name" required placeholder="Contoh: Toyota Innova Zenix 2.0" />
							</label>
							<label>
								Plat Nomor
								<input v-model="formData.license_plate" required placeholder="Contoh: B 1234 ABC" />
							</label>
							<label>
								Merk Kendaraan
								<input v-model="formData.brand" required placeholder="Contoh: Toyota / Mitsubishi / Honda" />
							</label>
							<label>
								Transmisi
								<select v-model="formData.transmission">
									<option value="Automatic">Automatic</option>
									<option value="Manual">Manual</option>
									<option value="Automatic CVT">Automatic CVT</option>
								</select>
							</label>
							<label>
								Kapasitas Penumpang
								<input v-model.number="formData.capacity" type="number" min="1" required />
							</label>
							<label>
								Jenis Bahan Bakar
								<select v-model="formData.fuel_type">
									<option value="Bensin">Bensin</option>
									<option value="Diesel">Diesel</option>
									<option value="Bensin Hybrid">Bensin Hybrid</option>
									<option value="Listrik">Listrik (EV)</option>
								</select>
							</label>
							<label>
								Status Operasional
								<select v-model="formData.status">
									<option value="Tersedia">Tersedia</option>
									<option value="Sedang Dipakai">Sedang Dipakai</option>
									<option value="Servis Berkala">Servis Berkala</option>
								</select>
							</label>
							<label class="col-span-2">
								Deskripsi / Peruntukan
								<input v-model="formData.description" placeholder="Contoh: Kendaraan dinas harian operasional..." />
							</label>
						</div>
					</template>

					<!-- 4. FORM CLEANING SERVICE -->
					<template v-else-if="currentTab === 'cleaning'">
						<div class="form-grid">
							<label>
								Nama Alat Kebersihan
								<input v-model="formData.name" required placeholder="Contoh: Mesin Vacuum Cleaner Wet & Dry" />
							</label>
							<label>
								Kode Alat
								<input v-model="formData.code" required placeholder="Contoh: CLN-010" />
							</label>
							<label>
								Kategori Alat
								<select v-model="formData.category">
									<option>Mesin Pembersih</option>
									<option>Mesin Penyedot Debu</option>
									<option>Mesin Cuci Tekanan</option>
									<option>Trolley & Keranjang</option>
									<option>Peralatan Manual</option>
									<option>Mesin Otomatis</option>
								</select>
							</label>
							<label>
								Jumlah Unit
								<input v-model.number="formData.quantity" type="number" min="1" required />
							</label>
							<label>
								Satuan
								<select v-model="formData.unit">
									<option>Unit</option>
									<option>Set</option>
									<option>Pcs</option>
								</select>
							</label>
							<label>
								Lokasi Penyimpanan
								<input v-model="formData.location" required placeholder="Contoh: Gudang Janitor Lt. 1" />
							</label>
							<label>
								Kondisi & Status
								<select v-model="formData.status">
									<option value="Tersedia">Tersedia</option>
									<option value="Sedang Digunakan">Sedang Digunakan</option>
									<option value="Perbaikan">Perbaikan</option>
								</select>
							</label>
							<label class="col-span-2">
								Deskripsi
								<input v-model="formData.description" placeholder="Keterangan spesifikasi / pemakaian alat..." />
							</label>
						</div>
					</template>

					<!-- FOTO BARANG (UPLOAD / URL / PRESET) -->
					<div class="photo-upload-section">
						<label class="photo-section-label">
							<span>Foto {{ activeTabLabel }}</span>
							<small>Unggah file foto, input link gambar, atau pilih preset instan</small>
						</label>

						<div class="photo-uploader-box">
							<div class="photo-preview-wrap">
								<img
									:src="formData.image || defaultImgForCurrentTab"
									:alt="formData.name || 'Preview'"
									class="photo-preview-img"
									@error="handleImgError($event, defaultImgForCurrentTab)"
								/>
								<span class="preview-badge" :class="{ custom: !!formData.image }">
									{{ formData.image ? 'Foto Aktif' : 'Default' }}
								</span>
							</div>

							<div class="photo-inputs-wrap">
								<div class="upload-btn-row">
									<label class="btn-upload-file" :class="{ disabled: isUploadingImage }">
										<svg viewBox="0 0 24 24" class="svg-upload-icon" fill="currentColor">
											<path d="M19.35 10.04C18.67 6.59 15.64 4 12 4 9.11 4 6.6 5.64 5.35 8.04 2.34 8.36 0 10.91 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96zM14 13v4h-4v-4H7l5-5 5 5h-3z"/>
										</svg>
										<span>{{ isUploadingImage ? 'Mengunggah...' : 'Pilih File Gambar' }}</span>
										<input
											type="file"
											accept="image/png, image/jpeg, image/webp, image/svg+xml"
											@change="uploadImageFile($event)"
											:disabled="isUploadingImage"
											style="display: none;"
										/>
									</label>

									<button
										v-if="formData.image"
										type="button"
										class="btn-clear-photo"
										@click="formData.image = ''"
										title="Gunakan Default Preset"
									>
										Gunakan Default
									</button>
								</div>

								<div class="url-input-wrap">
									<input
										v-model="formData.image"
										type="text"
										placeholder="Path foto atau URL (contoh: /images/atk/ATK-001.png atau https://...)"
										class="photo-url-input"
									/>
								</div>

								<!-- Quick Preset Chips -->
								<div class="presets-selector">
									<span class="presets-label">Pilihan Preset Gambar Cepat:</span>
									<div class="presets-chips">
										<button
											v-for="(preset, pIdx) in currentTabPresets"
											:key="pIdx"
											type="button"
											class="preset-chip"
											:class="{ active: formData.image === preset.url }"
											@click="formData.image = preset.url"
										>
											<img :src="preset.url" :alt="preset.label" class="preset-chip-thumb" />
											<span>{{ preset.label }}</span>
										</button>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="modal-footer-actions">
						<button class="btn-modal-cancel" type="button" @click="closeModal">Batal</button>
						<button class="btn-modal-submit" type="submit" :disabled="isSubmitting || isUploadingImage">
							<span v-if="isSubmitting">Menyimpan...</span>
							<span v-else>{{ editingId ? 'Simpan Perubahan' : 'Tambah ke Database' }}</span>
						</button>
					</div>
				</form>
			</div>
		</div>

		<!-- MODAL LIHAT & CETAK QR CODE RESMI (ATK, RUANGAN, MOBIL - ADMIN) -->
		<div v-if="barcodeModalItem" class="modal-backdrop" @click.self="barcodeModalItem = null">
			<div class="crypto-modal" style="max-width: 440px;">
				<div class="modal-top">
					<div>
						<span class="modal-tag">{{ barcodeModalType === 'mobil' ? 'Kendaraan Dinas' : (barcodeModalType === 'ruangan' ? 'Fasilitas Ruangan' : 'Inventaris ATK') }}</span>
						<h3>Label QR Code Resmi {{ barcodeModalType === 'mobil' ? 'Kendaraan' : (barcodeModalType === 'ruangan' ? 'Ruangan' : 'Barang') }}</h3>
						<p>{{ barcodeModalType === 'mobil' ? 'QR Code resmi penandaan mobil dinas dan pemindaian cepat pengemudi.' : (barcodeModalType === 'ruangan' ? 'QR Code resmi untuk penandaan pintu ruangan dan reservasi cepat.' : 'QR Code resmi untuk penandaan fisik barang dan pemindaian cepat.') }}</p>
					</div>
					<button class="modal-close-btn" type="button" @click="barcodeModalItem = null">×</button>
				</div>

				<div style="padding: 20px; display: flex; flex-direction: column; gap: 16px;">
					<div style="background: #ffffff; color: #0f172a; border: 2px dashed #cbd5e1; border-radius: 14px; padding: 20px; text-align: center;">
						<div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #e2e8f0; padding-bottom: 8px; margin-bottom: 12px;">
							<span style="font-size: 11px; font-weight: 800; color: #059669; letter-spacing: 1px;">SIMPEL • BPJS KESEHATAN</span>
							<span style="font-size: 10px; font-weight: 700; background: #f1f5f9; color: #475569; padding: 2px 8px; border-radius: 9999px;">
								{{ barcodeModalType === 'mobil' ? (barcodeModalItem.brand || 'Toyota') : (barcodeModalType === 'ruangan' ? (barcodeModalItem.floor || 'Ruang Rapat') : (barcodeModalItem.category || 'Alat Tulis')) }}
							</span>
						</div>
						<div style="display: flex; align-items: center; gap: 12px; text-align: left; margin-bottom: 14px;">
							<img
								:src="barcodeModalItem.image || (barcodeModalType === 'mobil' ? '/images/vehicles/default-vehicle.svg' : (barcodeModalType === 'ruangan' ? '/images/rooms/default-room.svg' : '/images/atk/default-atk.svg'))"
								style="width: 48px; height: 48px; border-radius: 8px; object-fit: cover; border: 1px solid #e2e8f0;"
								@error="handleImgError($event, barcodeModalType === 'mobil' ? '/images/vehicles/default-vehicle.svg' : (barcodeModalType === 'ruangan' ? '/images/rooms/default-room.svg' : '/images/atk/default-atk.svg'))"
							/>
							<div>
								<div style="font-size: 13px; font-weight: 800; color: #0f172a; line-height: 1.3;">{{ barcodeModalItem.name }}</div>
								<div style="font-size: 11px; color: #64748b; margin-top: 2px;">
									<template v-if="barcodeModalType === 'mobil'">
										Plat: <b>{{ barcodeModalItem.license_plate }}</b> • Driver: <b style="color: #059669;">{{ barcodeModalItem.current_driver_name || '-' }}</b>
									</template>
									<template v-else-if="barcodeModalType === 'ruangan'">
										Kode: <b>{{ barcodeModalItem.code }}</b> • Kapasitas: <b>{{ barcodeModalItem.capacity }} Orang</b>
									</template>
									<template v-else>
										Kode: <b>{{ barcodeModalItem.barcode || `ATK-${String(barcodeModalItem.id).padStart(3, '0')}` }}</b> • Stok: <b>{{ barcodeModalItem.stock }}</b>
									</template>
								</div>
							</div>
						</div>
						<div style="display: flex; flex-direction: column; align-items: center; justify-content: center; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px; margin-bottom: 4px;">
							<img
								:src="`/images/barcodes/qr-${barcodeModalType === 'mobil' ? (barcodeModalItem.license_plate || '').replace(/\s+/g, '-') : (barcodeModalType === 'ruangan' ? barcodeModalItem.code : (barcodeModalItem.barcode || `ATK-${String(barcodeModalItem.id).padStart(3, '0')}`))}.svg`"
								style="width: 170px; height: 170px; display: block;"
							/>
							<span style="margin-top: 8px; font-size: 11px; font-weight: 800; color: #059669; background: #ecfdf5; padding: 2px 10px; border-radius: 9999px;">
								{{ barcodeModalType === 'mobil' ? 'QR CODE RESMI KENDARAAN' : (barcodeModalType === 'ruangan' ? 'QR CODE PINTU RUANGAN' : 'QR CODE INVENTARIS') }}
							</span>
						</div>
					</div>

					<div style="display: flex; flex-direction: column; gap: 8px;">
						<button type="button" class="btn-modal-submit" style="width: 100%; justify-content: center; background: #10b981;" @click="printBarcodeLabel(barcodeModalItem, barcodeModalType)">
							🖨️ {{ barcodeModalType === 'mobil' ? 'Cetak / Print Stiker Label Kendaraan' : (barcodeModalType === 'ruangan' ? 'Cetak / Print Stiker Label Pintu Ruangan' : 'Cetak / Print Stiker Label QR Code') }}
						</button>
						<a
							:href="`/images/barcodes/qr-${barcodeModalType === 'mobil' ? (barcodeModalItem.license_plate || '').replace(/\s+/g, '-') : (barcodeModalType === 'ruangan' ? barcodeModalItem.code : (barcodeModalItem.barcode || `ATK-${String(barcodeModalItem.id).padStart(3, '0')}`))}.svg`"
							:download="`QR-${barcodeModalType === 'mobil' ? (barcodeModalItem.license_plate || '').replace(/\s+/g, '-') : (barcodeModalType === 'ruangan' ? barcodeModalItem.code : (barcodeModalItem.barcode || barcodeModalItem.id))}.svg`"
							class="btn-modal-cancel"
							style="width: 100%; text-align: center; text-decoration: none; justify-content: center; display: flex; align-items: center;"
						>
							📥 Unduh QR Code (SVG)
						</a>
					</div>
				</div>
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
						Sistem mengharuskan data unik pada setiap item master data agar tidak terjadi kerancuan inventaris. Silakan periksa atau ubah data tersebut sebelum menyimpan.
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
import { computed, onMounted, onUnmounted, reactive, ref, watch } from 'vue'
useHead({
	title: 'Manajemen Alat & Aset - SIMPEL'
})

const router = useRouter()
const config = useRuntimeConfig()
const apiBase = config.public?.apiBase || 'http://127.0.0.1:8000/api'

// Admin Real-Time Notification State
const showNotifDropdown = ref(false)
const pendingNotifs = ref([])
const knownAdminBookingIds = ref(new Set())
let isFirstAdminBookingLoad = true
let adminNotifPollTimer = null

const formatDateTimeShort = (str) => {
	if (!str) return '-'
	try {
		const d = new Date(str)
		return d.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) + ' WIB'
	} catch {
		return str
	}
}

const fetchAdminPendingNotifs = async () => {
	try {
		const res = await $fetch(`${apiBase}/driver/bookings`).catch(() => null)
			|| await $fetch(`${apiBase}/v1/manage/driver/bookings`).catch(() => null)
		if (res?.status === 'success' && Array.isArray(res.pending)) {
			pendingNotifs.value = res.pending
		}
	} catch (e) {
		console.warn('Gagal memuat notifikasi admin:', e)
	}
}

// UI state
const isDark = ref(true)
const menuOpen = ref(false)
const globalSearch = ref('')
const query = ref('')
const statusFilter = ref('Semua')
const currentTab = ref('atk') // 'atk' | 'ruangan' | 'mobil' | 'cleaning'
const perPage = ref(10)
const currentPage = ref(1)
const isLoading = ref(false)
const isSubmitting = ref(false)

const toggleTheme = () => {
	isDark.value = !isDark.value
	if (typeof window !== 'undefined') {
		localStorage.setItem('admin_theme', isDark.value ? 'dark' : 'light')
	}
}

// Modal state
const showModal = ref(false)
const editingId = ref(null)
const formData = reactive({})

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

// Sidebar navigation items with matching SVG shapes
const menus = ref([
	{
		name: 'Manajemen Alat',
		active: true,
		iconSvg: '<svg viewBox="0 0 24 24" class="svg-icon-nav" fill="currentColor"><path d="M4 8h4V4H4v4zm6 12h4v-4h-4v4zm-6 0h4v-4H4v4zm0-6h4v-4H4v4zm6 0h4v-4h-4v4zm6-10v4h4V4h-4zm-6 4h4V4h-4v4zm6 6h4v-4h-4v4zm0 6h4v-4h-4v4z"/></svg>'
	},
	{
		name: 'Laporan',
		active: false,
		iconSvg: '<svg viewBox="0 0 24 24" class="svg-icon-nav" fill="currentColor"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/></svg>'
	},
	{
		name: 'Pengguna',
		active: false,
		iconSvg: '<svg viewBox="0 0 24 24" class="svg-icon-nav" fill="currentColor"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>'
	},
	{
		name: 'Divisi',
		active: false,
		iconSvg: '<svg viewBox="0 0 24 24" class="svg-icon-nav" fill="currentColor"><path d="M10 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2h-8l-2-2z"/></svg>'
	},
])

// 4 Collections for our 4 tabs
const atkItems = ref([])
const roomItems = ref([])
const vehicleItems = ref([])
const cleaningItems = ref([])

// Label helper
const activeTabLabel = computed(() => {
	switch (currentTab.value) {
		case 'atk': return 'Alat Tulis Kantor'
		case 'ruangan': return 'Ruangan'
		case 'mobil': return 'Mobil Operasional'
		case 'cleaning': return 'Alat Cleaning Service'
		default: return 'Alat'
	}
})

// Current collection based on tab
const currentCollection = computed(() => {
	switch (currentTab.value) {
		case 'atk': return atkItems.value
		case 'ruangan': return roomItems.value
		case 'mobil': return vehicleItems.value
		case 'cleaning': return cleaningItems.value
		default: return []
	}
})

// Filtered items
const filteredItems = computed(() => {
	const search = (query.value || globalSearch.value || '').toLowerCase()
	const status = statusFilter.value

	return currentCollection.value.filter(item => {
		// Filter status
		if (status !== 'Semua') {
			const itemStatus = String(item.status || '').toLowerCase()
			if (status === 'Tersedia' && !itemStatus.includes('tersedia') && itemStatus !== 'available') return false
			if (status === 'Sedang Digunakan' && !itemStatus.includes('guna') && !itemStatus.includes('pakai')) return false
			if (status === 'Stok Menipis' && !itemStatus.includes('menipis') && !itemStatus.includes('servis') && !itemStatus.includes('perbaikan') && !itemStatus.includes('rawat') && itemStatus !== 'low_stock') return false
		}

		// Search term across fields
		if (!search) return true
		const searchHaystack = [
			item.name,
			item.barcode,
			item.code,
			item.license_plate,
			item.category,
			item.brand,
			item.floor,
			item.facilities,
			item.location,
			item.status,
			item.description
		].filter(Boolean).join(' ').toLowerCase()

		return searchHaystack.includes(search)
	})
})

// Paginated items
const totalPages = computed(() => Math.ceil(filteredItems.value.length / perPage.value) || 1)
const paginatedItems = computed(() => {
	const start = (currentPage.value - 1) * perPage.value
	return filteredItems.value.slice(start, start + perPage.value)
})

// Reset page on tab or filter change
watch([currentTab, query, statusFilter, perPage], () => {
	currentPage.value = 1
})

// Status badge styling helper
const getStatusBadgeClass = (status, category) => {
	const s = String(status || '').toLowerCase()
	if (s.includes('tersedia') || s === 'available') return 'status-green'
	if (s.includes('guna') || s.includes('pakai')) return 'status-blue'
	if (s.includes('menipis') || s.includes('servis') || s.includes('perbaikan') || s.includes('rawat') || s === 'low_stock') return 'status-amber'
	return 'status-red'
}

const formatStatusText = (status) => {
	if (status === 'available') return 'Tersedia'
	if (status === 'low_stock') return 'Stok Menipis'
	if (status === 'out_of_stock') return 'Habis'
	return status || 'Tersedia'
}

const truncateText = (txt, max) => {
	if (!txt) return '-'
	return txt.length > max ? txt.substring(0, max) + '...' : txt
}

const resetFilters = () => {
	query.value = ''
	globalSearch.value = ''
	statusFilter.value = 'Semua'
}

// Fetch all 4 tables from backend API
const fetchData = async () => {
	isLoading.value = true
	try {
		const res = await $fetch(`${apiBase}/assets/all`)
		if (res) {
			atkItems.value = res.atk || []
			roomItems.value = res.rooms || []
			vehicleItems.value = res.vehicles || []
			cleaningItems.value = res.cleaning_tools || []
		}
	} catch (err) {
		console.warn('Gagal memuat data dari /api/assets/all, mencoba fallback endpoint terpisah...', err)
		try {
			const [atk, rooms, vehicles, tools] = await Promise.allSettled([
				$fetch(`${apiBase}/products`),
				$fetch(`${apiBase}/rooms`),
				$fetch(`${apiBase}/vehicles`),
				$fetch(`${apiBase}/cleaning-tools`),
			])
			if (atk.status === 'fulfilled') atkItems.value = atk.value || []
			if (rooms.status === 'fulfilled') roomItems.value = rooms.value || []
			if (vehicles.status === 'fulfilled') vehicleItems.value = vehicles.value || []
			if (tools.status === 'fulfilled') cleaningItems.value = tools.value || []
		} catch (innerErr) {
			console.error('Semua pemanggilan API gagal:', innerErr)
		}
	} finally {
		isLoading.value = false
	}
}

// Sidebar activation
const activate = async selected => {
	menus.value.forEach(item => { item.active = item.name === selected.name })
	if (selected.name === 'Pengguna') await router.push('/admin/pengguna')
	if (selected.name === 'Laporan') await router.push('/admin/laporan')
	if (selected.name === 'Divisi') await router.push('/admin/divisi')
}

// Modal handling & Photo logic
const isUploadingImage = ref(false)

const defaultImgForCurrentTab = computed(() => {
	switch (currentTab.value) {
		case 'atk': return '/images/atk/default-atk.svg'
		case 'ruangan': return '/images/rooms/default-room.svg'
		case 'mobil': return '/images/vehicles/default-vehicle.svg'
		case 'cleaning': return '/images/cleaning/default-cleaning.svg'
		default: return '/images/atk/default-atk.svg'
	}
})

const currentTabPresets = computed(() => {
	switch (currentTab.value) {
		case 'atk':
			return [
				{ label: 'Kertas HVS A4', url: '/images/atk/atk-001.svg' },
				{ label: 'Pulpen Gel Black', url: '/images/atk/atk-002.svg' },
				{ label: 'Spidol Whiteboard', url: '/images/atk/atk-003.svg' },
				{ label: 'Stapler Heavy', url: '/images/atk/atk-004.svg' },
				{ label: 'Buku Agenda', url: '/images/atk/atk-005.svg' },
				{ label: 'Binder Clip', url: '/images/atk/atk-006.svg' },
				{ label: 'Sticky Notes', url: '/images/atk/atk-007.svg' },
				{ label: 'Gunting Kantor', url: '/images/atk/atk-008.svg' },
				{ label: 'Tipe-X Tape', url: '/images/atk/atk-009.svg' },
				{ label: 'Map Dokumen', url: '/images/atk/atk-010.svg' },
			]
		case 'ruangan':
			return [
				{ label: 'Auditorium Utama', url: '/images/rooms/rng-01.svg' },
				{ label: 'Ruang Rapat VIP', url: '/images/rooms/rng-02.svg' },
				{ label: 'Meeting Room A', url: '/images/rooms/rng-03.svg' },
				{ label: 'Coworking Space', url: '/images/rooms/rng-04.svg' },
				{ label: 'Studio Multimedia', url: '/images/rooms/rng-05.svg' },
			]
		case 'mobil':
			return [
				{ label: 'Innova Zenix', url: '/images/vehicles/b-1024-pq.svg' },
				{ label: 'Avanza Veloz', url: '/images/vehicles/b-2831-uy.svg' },
				{ label: 'Ioniq 5 EV', url: '/images/vehicles/b-2026-ev.svg' },
				{ label: 'HiAce Commuter', url: '/images/vehicles/b-3418-cd.svg' },
				{ label: 'Alphard VIP', url: '/images/vehicles/b-7788-vip.svg' },
			]
		case 'cleaning':
			return [
				{ label: 'Vacuum Cleaner', url: '/images/cleaning/cln-001.svg' },
				{ label: 'Floor Polisher', url: '/images/cleaning/cln-002.svg' },
				{ label: 'Pressure Washer', url: '/images/cleaning/cln-003.svg' },
				{ label: 'Janitor Cart', url: '/images/cleaning/cln-004.svg' },
				{ label: 'Microfiber Set', url: '/images/cleaning/cln-005.svg' },
				{ label: 'Spin Mop', url: '/images/cleaning/cln-006.svg' },
				{ label: 'Sapu & Pengki', url: '/images/cleaning/cln-007.svg' },
				{ label: 'Pembersih Kaca', url: '/images/cleaning/cln-008.svg' },
				{ label: 'Caution Floor', url: '/images/cleaning/cln-009.svg' },
				{ label: 'Tangga Lipat', url: '/images/cleaning/cln-010.svg' },
			]
		default:
			return []
	}
})

const handleImgError = (event, fallbackUrl) => {
	if (event && event.target && event.target.src !== fallbackUrl) {
		event.target.src = fallbackUrl
	}
}

const uploadImageFile = async (event) => {
	const file = event?.target?.files?.[0]
	if (!file) return

	isUploadingImage.value = true
	try {
		const fd = new FormData()
		fd.append('image', file)

		const res = await $fetch(`${apiBase}/upload-image`, {
			method: 'POST',
			body: fd
		})

		if (res && res.url) {
			formData.image = res.url
		}
	} catch (err) {
		console.error('Gagal mengunggah foto ke backend:', err)
		const reader = new FileReader()
		reader.onload = (e) => {
			if (e.target?.result) {
				formData.image = e.target.result
			}
		}
		reader.readAsDataURL(file)
	} finally {
		isUploadingImage.value = false
		if (event.target) event.target.value = ''
	}
}

const resetFormData = () => {
	Object.keys(formData).forEach(k => delete formData[k])
	if (currentTab.value === 'atk') {
		Object.assign(formData, {
			name: '', barcode: `ATK-${String(atkItems.value.length + 1).padStart(3, '0')}`,
			category: 'Alat Tulis', unit: 'Pcs', stock: 10, status: 'available', description: '', image: ''
		})
	} else if (currentTab.value === 'ruangan') {
		Object.assign(formData, {
			name: '', code: `RNG-0${roomItems.value.length + 1}`,
			capacity: 20, floor: 'Lantai 1', facilities: 'AC, Smart TV, Whiteboard', status: 'Tersedia', description: '', image: ''
		})
	} else if (currentTab.value === 'mobil') {
		Object.assign(formData, {
			name: '', license_plate: 'B ' + Math.floor(1000 + Math.random() * 9000) + ' PKL',
			brand: 'Toyota', transmission: 'Automatic', capacity: 7, fuel_type: 'Bensin', status: 'Tersedia', description: '', image: ''
		})
	} else if (currentTab.value === 'cleaning') {
		Object.assign(formData, {
			name: '', code: `CLN-0${cleaningItems.value.length + 1}`,
			category: 'Mesin Pembersih', quantity: 1, unit: 'Unit', location: 'Gudang Janitor Lt. 1', status: 'Tersedia', description: '', image: ''
		})
	}
}

const openAddModal = () => {
	editingId.value = null
	resetFormData()
	showModal.value = true
}

const openEditModal = (item) => {
	editingId.value = item.id
	Object.keys(formData).forEach(k => delete formData[k])
	Object.assign(formData, JSON.parse(JSON.stringify(item)))
	showModal.value = true
}

const closeModal = () => {
	showModal.value = false
	editingId.value = null
}

// Barcode viewer & printing in admin
const barcodeModalItem = ref(null)
const barcodeModalType = ref('atk')

const openBarcodeModal = (item, type = 'atk') => {
	barcodeModalType.value = type
	barcodeModalItem.value = item
}

const printBarcodeLabel = (item, type = barcodeModalType.value) => {
	if (typeof window === 'undefined') return
	const printWindow = window.open('', '_blank', 'width=550,height=650')
	if (!printWindow) {
		alert('Pop-up terblokir oleh browser. Harap izinkan pop-up untuk mencetak QR Code.')
		return
	}

	const isMobil = type === 'mobil'
	const isRoom = type === 'ruangan'
	const barcodeCode = isMobil
		? (item.license_plate || '').replace(/\s+/g, '-')
		: (isRoom ? item.code : (item.barcode || `ATK-${String(item.id).padStart(3, '0')}`))
	const qrSvg = `/images/barcodes/qr-${barcodeCode}.svg`
	const headerTitle = isMobil
		? 'KENDARAAN DINAS OPERASIONAL BPJS KESEHATAN'
		: (isRoom ? 'FASILITAS RUANG RAPAT BPJS KESEHATAN' : 'INVENTARIS ATK BPJS KESEHATAN')

	printWindow.document.write(`
		<!DOCTYPE html>
		<html>
		<head>
			<title>Label QR Code - ${barcodeCode} - ${item.name}</title>
			<style>
				@page { size: auto; margin: 8mm; }
				body {
					font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif;
					background: #ffffff;
					color: #0f172a;
					display: flex;
					flex-direction: column;
					align-items: center;
					justify-content: center;
					padding: 24px;
					margin: 0;
				}
				.label-card {
					border: 2px dashed #0f172a;
					border-radius: 16px;
					padding: 24px;
					width: 320px;
					text-align: center;
					box-sizing: border-box;
				}
				.header-tag {
					font-size: 11px;
					font-weight: 800;
					letter-spacing: 2px;
					color: #059669;
					margin-bottom: 6px;
				}
				.item-name {
					font-size: 16px;
					font-weight: bold;
					margin: 0 0 16px;
					color: #0f172a;
					line-height: 1.3;
				}
				.qr-wrap {
					display: flex;
					align-items: center;
					justify-content: center;
					margin: 12px 0;
				}
				.qr-img {
					width: 170px;
					height: 170px;
				}
				.meta-text {
					background: #f8fafc;
					border-radius: 8px;
					padding: 10px;
					font-size: 12px;
					color: #475569;
					line-height: 1.6;
					margin-top: 10px;
				}
				.meta-text b { color: #0f172a; }
				.footer-note {
					font-size: 9px;
					color: #94a3b8;
					margin-top: 14px;
				}
			</style>
		</head>
		<body>
			<div class="label-card">
				<div class="header-tag">${headerTitle}</div>
				<h3 class="item-name">${item.name}</h3>
				<div class="qr-wrap">
					<img src="${qrSvg}" class="qr-img" alt="QR Code" />
				</div>
				<div class="meta-text">
					${isMobil ? `
						<div>Plat Nomor: <b>${item.license_plate}</b></div>
						<div>Driver Resmi: <b>${item.current_driver_name || 'Pool Standby'}</b></div>
						<div>Transmisi: <b>${item.transmission || '-'}</b></div>
						<div>Kapasitas: <b>${item.capacity || 7} Penumpang</b></div>
						<div>Bahan Bakar: <b>${item.fuel_type || 'Bensin'}</b></div>
					` : (isRoom ? `
						<div>Kode Ruangan: <b>${item.code}</b></div>
						<div>Kapasitas: <b>${item.capacity || 20} Orang</b></div>
						<div>Lokasi: <b>${item.floor || 'Gedung Kantor'}</b></div>
						<div>Fasilitas: <b>${item.facilities || 'Lengkap'}</b></div>
					` : `
						<div>Kode: <b>${barcodeCode}</b></div>
						<div>Kategori: <b>${item.category || 'Alat Tulis'}</b></div>
						<div>Satuan: <b>${item.unit || 'Pcs'}</b></div>
					`)}
				</div>
				<div class="footer-note">Dicetak dari Sistem SIMPEL • Pindai Menggunakan Kamera Scanner / HP</div>
			</div>
			<script>
				window.onload = function() {
					window.print();
				};
			<\/script>
		</body>
		</html>
	`)
	printWindow.document.close()
}

// Save or Update data
const submitModalForm = async () => {
	// 1. Validasi Duplikasi Data ATK
	if (currentTab.value === 'atk') {
		const targetName = (formData.name || '').trim().toLowerCase()
		const targetBarcode = (formData.barcode || '').trim().toLowerCase()

		const dupName = atkItems.value.find(i => (!editingId.value || i.id !== editingId.value) && (i.name || '').trim().toLowerCase() === targetName)
		if (dupName) {
			openDuplicateWarning({
				title: 'Barang ATK Sudah Terdaftar!',
				subtitle: 'Ditemukan nama barang yang persis sama dalam inventaris ATK',
				fieldName: 'NAMA BARANG ATK',
				message: `Barang ATK dengan nama "${formData.name}" sudah ada dalam inventaris dengan Barcode ${dupName.barcode || '-'}. Setiap item ATK wajib memiliki nama yang unik.`,
				inputVal: formData.name,
				existingVal: `${dupName.name} (Barcode: ${dupName.barcode || '-'})`
			})
			return
		}

		const dupBarcode = atkItems.value.find(i => (!editingId.value || i.id !== editingId.value) && (i.barcode || '').trim().toLowerCase() === targetBarcode)
		if (dupBarcode) {
			openDuplicateWarning({
				title: 'Kode Barcode Sudah Digunakan!',
				subtitle: 'Setiap barang wajib memiliki kode barcode unik',
				fieldName: 'KODE BARCODE ATK',
				message: `Kode barcode "${formData.barcode}" sudah digunakan oleh barang "${dupBarcode.name}". Kode barcode tidak boleh ganda.`,
				inputVal: formData.barcode,
				existingVal: `Digunakan oleh: ${dupBarcode.name}`
			})
			return
		}
	}
	// 2. Validasi Duplikasi Data Alat Kebersihan
	else if (currentTab.value === 'cleaning') {
		const targetName = (formData.name || '').trim().toLowerCase()
		const targetCode = (formData.code || '').trim().toLowerCase()

		const dupName = cleaningItems.value.find(i => (!editingId.value || i.id !== editingId.value) && (i.name || '').trim().toLowerCase() === targetName)
		if (dupName) {
			openDuplicateWarning({
				title: 'Alat Kebersihan Sudah Ada!',
				subtitle: 'Ditemukan nama alat kebersihan yang persis sama',
				fieldName: 'NAMA ALAT KEBERSIHAN',
				message: `Alat kebersihan dengan nama "${formData.name}" sudah terdaftar dengan Kode ${dupName.code || '-'}. Mohon gunakan nama yang berbeda.`,
				inputVal: formData.name,
				existingVal: `${dupName.name} (Kode: ${dupName.code || '-'})`
			})
			return
		}

		const dupCode = cleaningItems.value.find(i => (!editingId.value || i.id !== editingId.value) && (i.code || '').trim().toLowerCase() === targetCode)
		if (dupCode) {
			openDuplicateWarning({
				title: 'Kode Alat Sudah Digunakan!',
				subtitle: 'Kode identifikasi alat kebersihan tidak boleh duplikat',
				fieldName: 'KODE ALAT KEBERSIHAN',
				message: `Kode alat "${formData.code}" sudah digunakan oleh alat "${dupCode.name}".`,
				inputVal: formData.code,
				existingVal: `Digunakan oleh: ${dupCode.name}`
			})
			return
		}
	}
	// 3. Validasi Duplikasi Data Ruangan
	else if (currentTab.value === 'ruangan') {
		const targetName = (formData.name || '').trim().toLowerCase()
		const targetCode = (formData.code || '').trim().toLowerCase()

		const dupName = roomItems.value.find(i => (!editingId.value || i.id !== editingId.value) && (i.name || '').trim().toLowerCase() === targetName)
		if (dupName) {
			openDuplicateWarning({
				title: 'Nama Ruangan Sudah Terdaftar!',
				subtitle: 'Ditemukan ruangan dengan nama yang sama',
				fieldName: 'NAMA RUANGAN',
				message: `Ruangan dengan nama "${formData.name}" sudah terdaftar (Kode: ${dupName.code || '-'}). Gunakan nama ruangan yang berbeda.`,
				inputVal: formData.name,
				existingVal: `${dupName.name} (Lantai: ${dupName.floor || '-'})`
			})
			return
		}

		const dupCode = roomItems.value.find(i => (!editingId.value || i.id !== editingId.value) && (i.code || '').trim().toLowerCase() === targetCode)
		if (dupCode) {
			openDuplicateWarning({
				title: 'Kode Ruangan Sudah Digunakan!',
				subtitle: 'Kode identifikasi ruangan harus unik',
				fieldName: 'KODE RUANGAN',
				message: `Kode ruangan "${formData.code}" sudah digunakan oleh ruangan "${dupCode.name}".`,
				inputVal: formData.code,
				existingVal: `Digunakan oleh: ${dupCode.name}`
			})
			return
		}
	}
	// 4. Validasi Duplikasi Data Mobil
	else if (currentTab.value === 'mobil') {
		const targetPlate = (formData.license_plate || '').replace(/\s+/g, '').toLowerCase()
		const targetName = (formData.name || '').trim().toLowerCase()

		const dupPlate = vehicleItems.value.find(i => (!editingId.value || i.id !== editingId.value) && (i.license_plate || '').replace(/\s+/g, '').toLowerCase() === targetPlate)
		if (dupPlate) {
			openDuplicateWarning({
				title: 'Plat Nomor Sudah Terdaftar!',
				subtitle: 'Plat nomor kendaraan operasional tidak boleh ganda',
				fieldName: 'PLAT NOMOR KENDARAAN',
				message: `Plat nomor "${formData.license_plate}" sudah terdaftar untuk kendaraan "${dupPlate.name}". Setiap kendaraan harus memiliki plat nomor yang unik.`,
				inputVal: formData.license_plate,
				existingVal: `${dupPlate.name} (${dupPlate.brand || '-'})`
			})
			return
		}

		const dupVehicleName = vehicleItems.value.find(i => (!editingId.value || i.id !== editingId.value) && (i.name || '').trim().toLowerCase() === targetName)
		if (dupVehicleName) {
			openDuplicateWarning({
				title: 'Nama Kendaraan Sudah Terdaftar!',
				subtitle: 'Ditemukan kendaraan dengan nama yang sama',
				fieldName: 'NAMA KENDARAAN',
				message: `Kendaraan dengan nama "${formData.name}" sudah terdaftar (Plat: ${dupVehicleName.license_plate || '-'}). Gunakan nama yang berbeda atau tambahkan unit kendaraan.`,
				inputVal: formData.name,
				existingVal: `${dupVehicleName.name} (Plat: ${dupVehicleName.license_plate || '-'})`
			})
			return
		}
	}

	isSubmitting.value = true
	try {
		let endpoint = ''
		let method = editingId.value ? 'PUT' : 'POST'

		if (currentTab.value === 'atk') {
			endpoint = editingId.value ? `${apiBase}/v1/manage/products/${editingId.value}` : `${apiBase}/v1/manage/products`
		} else if (currentTab.value === 'ruangan') {
			endpoint = editingId.value ? `${apiBase}/v1/manage/rooms/${editingId.value}` : `${apiBase}/v1/manage/rooms`
		} else if (currentTab.value === 'mobil') {
			endpoint = editingId.value ? `${apiBase}/v1/manage/vehicles/${editingId.value}` : `${apiBase}/v1/manage/vehicles`
		} else if (currentTab.value === 'cleaning') {
			endpoint = editingId.value ? `${apiBase}/v1/manage/cleaning-tools/${editingId.value}` : `${apiBase}/v1/manage/cleaning-tools`
		}

		await $fetch(endpoint, {
			method,
			body: formData
		})

		closeModal()
		await fetchData()
	} catch (error) {
		console.error('Gagal menyimpan data:', error)
		const errErrors = error?.data?.errors
		if (errErrors && typeof errErrors === 'object') {
			const firstKey = Object.keys(errErrors)[0]
			const firstMsg = Array.isArray(errErrors[firstKey]) ? errErrors[firstKey][0] : String(errErrors[firstKey])
			openDuplicateWarning({
				title: 'Data Duplikat Terdeteksi!',
				subtitle: 'Database menolak penyimpanan karena ada data yang sudah terdaftar',
				fieldName: firstKey.toUpperCase().replace('_', ' '),
				message: firstMsg,
				inputVal: formData[firstKey] || '-',
				existingVal: 'Sudah ada dalam basis data'
			})
			return
		}

		const msg = error?.data?.message || error?.message || ''
		if (msg && (msg.toLowerCase().includes('sudah') || msg.toLowerCase().includes('terdaftar') || msg.toLowerCase().includes('unique') || msg.toLowerCase().includes('duplicate'))) {
			openDuplicateWarning({
				title: 'Peringatan Data Duplikat!',
				subtitle: 'Validasi server menolak duplikasi data',
				fieldName: activeTabLabel.value.toUpperCase(),
				message: msg,
				inputVal: formData.name || formData.license_plate || formData.barcode || formData.code || '-',
				existingVal: 'Sudah terdaftar di sistem'
			})
			return
		}

		openDuplicateWarning({
			title: 'Gagal Menyimpan Data',
			subtitle: 'Terjadi kendala saat memproses permohonan data',
			fieldName: 'SISTEM',
			message: msg || 'Terjadi kesalahan pada server saat menyimpan data.',
			inputVal: '-',
			existingVal: '-'
		})
	} finally {
		isSubmitting.value = false
	}
}

// Delete item
const deleteItem = async (item) => {
	if (!confirm(`Yakin ingin menghapus "${item.name}" dari inventaris ${activeTabLabel.value}?`)) {
		return
	}

	try {
		let endpoint = ''
		if (currentTab.value === 'atk') endpoint = `${apiBase}/v1/manage/products/${item.id}`
		else if (currentTab.value === 'ruangan') endpoint = `${apiBase}/v1/manage/rooms/${item.id}`
		else if (currentTab.value === 'mobil') endpoint = `${apiBase}/v1/manage/vehicles/${item.id}`
		else if (currentTab.value === 'cleaning') endpoint = `${apiBase}/v1/manage/cleaning-tools/${item.id}`

		await $fetch(endpoint, { method: 'DELETE' })
		await fetchData()
	} catch (e) {
		console.warn('Gagal menghapus via API, menghapus dari state lokal:', e)
		if (currentTab.value === 'atk') atkItems.value = atkItems.value.filter(i => i.id !== item.id)
		else if (currentTab.value === 'ruangan') roomItems.value = roomItems.value.filter(i => i.id !== item.id)
		else if (currentTab.value === 'mobil') vehicleItems.value = vehicleItems.value.filter(i => i.id !== item.id)
		else if (currentTab.value === 'cleaning') cleaningItems.value = cleaningItems.value.filter(i => i.id !== item.id)
	}
}

onMounted(() => {
	if (typeof window !== 'undefined') {
		const savedTheme = localStorage.getItem('admin_theme')
		if (savedTheme) {
			isDark.value = savedTheme === 'dark'
		}
	}
	fetchData()
	fetchAdminPendingNotifs()
	adminNotifPollTimer = setInterval(fetchAdminPendingNotifs, 6000)
})

onUnmounted(() => {
	if (adminNotifPollTimer) clearInterval(adminNotifPollTimer)
})
</script>

<style scoped>
/* ==================== GLOBAL & LAYOUT ==================== */
.dashboard {
	min-height: 100vh;
	display: flex;
	background: #090d14;
	color: #e2e8f0;
	font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

/* Sidebar */
.sidebar {
	position: fixed;
	inset: 0 auto 0 0;
	z-index: 20;
	width: 224px;
	padding: 21px 15px;
	display: flex;
	flex-direction: column;
	color: #d9eee7;
	background: linear-gradient(180deg, #0f1724 0%, #080d14 100%);
	border-right: 1px solid rgba(255, 255, 255, 0.07);
}
.brand {
	display: flex;
	align-items: center;
	gap: 10px;
	padding: 0 6px 26px;
}
.brand-logo-diamond {
	display: grid;
	place-items: center;
	width: 32px;
	height: 32px;
	flex-shrink: 0;
}
.brand-logo-diamond .svg-icon {
	width: 30px;
	height: 30px;
	filter: drop-shadow(0 0 8px rgba(255, 153, 0, 0.5));
}
.brand b {
	font-size: 14px;
	display: block;
	color: #f8fafc;
	letter-spacing: -0.2px;
}
.brand small {
	display: block;
	color: #94a3b8;
	font-size: 9px;
	margin-top: 2px;
}
.sidebar nav {
	display: grid;
	gap: 7px;
}
.sidebar nav a {
	display: flex;
	gap: 12px;
	align-items: center;
	padding: 10px 12px;
	color: #94a3b8;
	border-radius: 8px;
	font-size: 12px;
	text-decoration: none;
	transition: all 0.15s ease;
}
.sidebar nav a.active,
.sidebar nav a:hover {
	color: #ffffff;
	background: rgba(16, 185, 129, 0.18);
	border: 1px solid rgba(16, 185, 129, 0.3);
}
.nav-svg-box {
	display: grid;
	place-items: center;
	width: 18px;
	height: 18px;
	color: #34d399;
}
.nav-svg-box :deep(svg) {
	width: 17px;
	height: 17px;
	fill: currentColor;
}
.account {
	display: flex;
	align-items: center;
	gap: 9px;
	margin-top: auto;
	padding: 14px 4px;
	border-top: 1px solid rgba(255, 255, 255, 0.08);
	cursor: pointer;
}
.account b {
	font-size: 11px;
	display: block;
	color: #f1f5f9;
}
.account small {
	font-size: 9px;
	display: block;
	color: #64748b;
}
.account button {
	margin-left: auto;
	border: 0;
	color: #94a3b8;
	background: none;
	cursor: pointer;
	padding: 4px;
}
.user-avatar,
.user-dot {
	display: grid;
	place-items: center;
	color: #064e3b;
	border-radius: 50%;
	background: #34d399;
	font-weight: 700;
}
.user-avatar {
	width: 28px;
	height: 28px;
	font-size: 11px;
}
.logout {
	border: 0;
	color: #94a3b8;
	background: none;
	font-size: 11px;
	text-align: left;
	cursor: pointer;
	padding: 8px 4px;
	display: flex;
	align-items: center;
	gap: 8px;
}
.logout:hover {
	color: #f87171;
}

/* Main & Topbar */
.main {
	width: calc(100% - 224px);
	margin-left: 224px;
	min-height: 100vh;
	background: #080c14;
}
.topbar {
	height: 60px;
	display: flex;
	align-items: center;
	padding: 0 32px;
	border-bottom: 1px solid rgba(255, 255, 255, 0.06);
	background: rgba(11, 16, 26, 0.85);
	backdrop-filter: blur(8px);
	position: sticky;
	top: 0;
	z-index: 10;
}
.global-search {
	display: flex;
	align-items: center;
	gap: 10px;
	color: #64748b;
	border: 1px solid rgba(255, 255, 255, 0.08);
	border-radius: 8px;
	background: #0f1724;
	width: 380px;
	padding: 0 12px;
}
.search-lens {
	display: flex;
	align-items: center;
	color: #64748b;
}
.global-search input {
	width: 100%;
	height: 36px;
	border: 0;
	outline: 0;
	color: #f8fafc;
	background: transparent;
	font-size: 12px;
}
.top-user {
	display: flex;
	align-items: center;
	gap: 10px;
	margin-left: auto;
	color: #cbd5e1;
	font-size: 12px;
}
.bell-btn {
	display: grid;
	place-items: center;
	width: 32px;
	height: 32px;
	border-radius: 8px;
	background: #131c2c;
	color: #94a3b8;
	margin-right: 8px;
	cursor: pointer;
	position: relative;
	transition: all 0.15s ease;
	border: 1px solid rgba(255, 255, 255, 0.08);
}
.bell-btn:hover {
	color: #ffffff;
	background: #1a2538;
}
.bell-btn.has-badge {
	color: #fbbf24;
	border-color: rgba(245, 158, 11, 0.4);
	background: rgba(245, 158, 11, 0.12);
}
.notif-pill-badge {
	position: absolute;
	top: -4px;
	right: -4px;
	min-width: 17px;
	height: 17px;
	padding: 0 4px;
	border-radius: 10px;
	background: #ef4444;
	color: #ffffff;
	font-size: 9px;
	font-weight: 800;
	display: flex;
	align-items: center;
	justify-content: center;
	border: 2px solid #0b101a;
	animation: pulse 2s infinite;
}
.notif-popover {
	position: absolute;
	top: 42px;
	right: 0;
	width: 330px;
	max-width: 90vw;
	background: #0f172a;
	border: 1px solid rgba(255, 255, 255, 0.12);
	border-radius: 12px;
	box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5), 0 8px 10px -6px rgba(0, 0, 0, 0.5);
	z-index: 100;
	overflow: hidden;
	display: flex;
	flex-direction: column;
	animation: popoverFadeIn 0.2s cubic-bezier(0.16, 1, 0.3, 1);
}
@keyframes popoverFadeIn {
	from { opacity: 0; transform: translateY(-8px) scale(0.97); }
	to { opacity: 1; transform: translateY(0) scale(1); }
}
.notif-header {
	padding: 12px 14px;
	background: #131d33;
	border-bottom: 1px solid rgba(255, 255, 255, 0.08);
	display: flex;
	align-items: center;
	justify-content: space-between;
}
.notif-title-row {
	display: flex;
	align-items: center;
	gap: 6px;
}
.notif-title {
	font-size: 12px;
	font-weight: 700;
	color: #f8fafc;
}
.notif-counter-tag {
	font-size: 10px;
	font-weight: 800;
	background: #f59e0b;
	color: #0f172a;
	padding: 1px 6px;
	border-radius: 6px;
}
.notif-sound-bar {
	display: flex;
	align-items: center;
	gap: 4px;
}
.sound-chip {
	font-size: 10px;
	font-weight: 700;
	padding: 2px 7px;
	border-radius: 6px;
	border: 1px solid rgba(255, 255, 255, 0.1);
	background: #1e293b;
	color: #94a3b8;
	cursor: pointer;
	display: flex;
	align-items: center;
	gap: 3px;
	transition: all 0.15s;
}
.sound-chip.active {
	background: rgba(16, 185, 129, 0.15);
	color: #34d399;
	border-color: rgba(16, 185, 129, 0.35);
}
.sound-test-chip {
	font-size: 10px;
	font-weight: 600;
	padding: 2px 6px;
	border-radius: 6px;
	border: 1px solid rgba(245, 158, 11, 0.25);
	background: rgba(245, 158, 11, 0.1);
	color: #fbbf24;
	cursor: pointer;
	transition: all 0.15s;
}
.sound-test-chip:hover {
	background: rgba(245, 158, 11, 0.25);
}
.notif-body {
	max-height: 260px;
	overflow-y: auto;
	padding: 8px;
}
.notif-empty {
	text-align: center;
	padding: 24px 12px;
}
.notif-list {
	display: flex;
	flex-direction: column;
	gap: 6px;
}
.notif-item {
	display: flex;
	align-items: flex-start;
	gap: 10px;
	padding: 10px;
	border-radius: 8px;
	background: rgba(255, 255, 255, 0.03);
	border: 1px solid rgba(255, 255, 255, 0.06);
	transition: background 0.15s;
}
.notif-item:hover {
	background: rgba(255, 255, 255, 0.06);
}
.notif-item-icon {
	font-size: 16px;
	padding: 4px;
	border-radius: 6px;
	background: rgba(59, 130, 246, 0.15);
	line-height: 1;
}
.notif-item-content {
	flex: 1;
	min-width: 0;
}
.notif-item-top {
	display: flex;
	align-items: center;
	justify-content: space-between;
	gap: 6px;
}
.notif-name {
	font-size: 11px;
	font-weight: 700;
	color: #f1f5f9;
	white-space: nowrap;
	overflow: hidden;
	text-overflow: ellipsis;
}
.notif-time {
	font-size: 9px;
	color: #64748b;
	font-family: monospace;
}
.notif-item-dest {
	font-size: 11px;
	color: #fbbf24;
	margin-top: 2px;
	white-space: nowrap;
	overflow: hidden;
	text-overflow: ellipsis;
}
.notif-item-dept {
	font-size: 10px;
	color: #94a3b8;
	margin-top: 2px;
}
.notif-footer {
	padding: 8px 12px;
	border-top: 1px solid rgba(255, 255, 255, 0.08);
	background: #0b1120;
}
.notif-all-btn {
	display: flex;
	align-items: center;
	justify-content: space-between;
	font-size: 11px;
	font-weight: 700;
	color: #38bdf8;
	text-decoration: none;
	padding: 4px 6px;
	border-radius: 6px;
	transition: all 0.15s;
}
.notif-all-btn:hover {
	color: #7dd3fc;
	background: rgba(56, 189, 248, 0.1);
}
.svg-icon-bell {
	width: 16px;
	height: 16px;
}
.user-dot {
	width: 22px;
	height: 22px;
	font-size: 9px;
}
.chevron-down {
	color: #64748b;
	font-size: 10px;
}
.page-content {
	max-width: 1400px;
	margin: auto;
	padding: 24px 32px 48px;
}

/* Hero Section */
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
	margin-bottom: 20px;
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
.hero-fs-row {
	margin-top: 12px;
}
.btn-hero-launch-fs {
	display: inline-flex;
	align-items: center;
	gap: 8px;
	padding: 8px 16px;
	background: linear-gradient(135deg, rgba(245, 158, 11, 0.2), rgba(217, 119, 6, 0.3));
	border: 1px solid rgba(245, 158, 11, 0.5);
	border-radius: 8px;
	color: #fbbf24;
	font-size: 12px;
	font-weight: 700;
	text-decoration: none;
	transition: all 0.2s ease;
	box-shadow: 0 2px 10px rgba(245, 158, 11, 0.15);
}
.btn-hero-launch-fs:hover {
	background: #f59e0b;
	color: #000000;
	transform: translateY(-1px);
	box-shadow: 0 4px 16px rgba(245, 158, 11, 0.3);
}

.nav-fullscreen-entry {
	display: flex;
	gap: 12px;
	align-items: center;
	padding: 10px 12px;
	color: #fbbf24 !important;
	background: rgba(245, 158, 11, 0.1);
	border: 1px solid rgba(245, 158, 11, 0.25);
	border-radius: 8px;
	font-size: 12px;
	font-weight: 700;
	text-decoration: none;
	margin-top: 4px;
	transition: all 0.15s ease;
}
.nav-fullscreen-entry:hover {
	background: #f59e0b !important;
	color: #000000 !important;
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

/* Summary Grid */
.summary {
	display: grid;
	grid-template-columns: repeat(4, 1fr);
	gap: 14px;
	margin-bottom: 24px;
}
.summary article {
	display: flex;
	align-items: center;
	gap: 14px;
	padding: 14px 18px;
	border-radius: 10px;
	background: #0f1623;
	border: 1px solid rgba(255, 255, 255, 0.06);
	box-shadow: 0 4px 16px rgba(0, 0, 0, 0.35);
}
.stat-icon {
	display: grid;
	place-items: center;
	width: 40px;
	height: 40px;
	border-radius: 9px;
	flex-shrink: 0;
}
.svg-icon-stat {
	width: 20px;
	height: 20px;
}
.stat-icon.green { background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.3); color: #34d399; }
.stat-icon.cyan { background: rgba(14, 165, 233, 0.15); border: 1px solid rgba(14, 165, 233, 0.3); color: #38bdf8; }
.stat-icon.amber { background: rgba(245, 158, 11, 0.15); border: 1px solid rgba(245, 158, 11, 0.3); color: #fbbf24; }
.stat-icon.purple { background: rgba(168, 85, 247, 0.15); border: 1px solid rgba(168, 85, 247, 0.3); color: #c084fc; }
.summary small {
	display: block;
	color: #64748b;
	font-size: 11px;
}
.summary strong {
	display: block;
	margin: 2px 0;
	font-size: 18px;
	color: #f8fafc;
	font-weight: 700;
}
.text-green { color: #34d399 !important; }
.text-cyan { color: #38bdf8 !important; }
.text-amber { color: #fbbf24 !important; }
.text-purple { color: #c084fc !important; }

/* ==================== CORE: MANAJEMEN ALAT PANEL ==================== */
.crypto-panel {
	border-radius: 14px;
	background: #0b111c;
	border: 1px solid rgba(255, 255, 255, 0.08);
	box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
	overflow: hidden;
}

/* Header */
.crypto-header {
	display: flex;
	align-items: center;
	justify-content: space-between;
	padding: 22px 24px 18px;
	border-bottom: 1px solid rgba(255, 255, 255, 0.06);
	background: linear-gradient(180deg, #101927 0%, #0b111c 100%);
}
.title-with-badge {
	display: flex;
	align-items: center;
	gap: 12px;
}
.title-with-badge h2 {
	margin: 0;
	font-size: 20px;
	font-weight: 800;
	color: #ffffff;
	letter-spacing: -0.3px;
}
.live-indicator {
	display: inline-flex;
	align-items: center;
	gap: 6px;
	padding: 3px 9px;
	border-radius: 20px;
	background: rgba(255, 153, 0, 0.12);
	border: 1px solid rgba(255, 153, 0, 0.35);
	color: #fbbf24;
	font-size: 10px;
	font-weight: 700;
	text-transform: uppercase;
}
.svg-icon-bolt {
	width: 13px;
	height: 13px;
}
.subtitle {
	margin: 4px 0 0;
	color: #94a3b8;
	font-size: 12px;
}
.crypto-actions-top {
	display: flex;
	gap: 10px;
}
.btn-refresh {
	display: flex;
	align-items: center;
	gap: 6px;
	padding: 9px 14px;
	border-radius: 8px;
	background: #131c2c;
	border: 1px solid rgba(255, 255, 255, 0.1);
	color: #cbd5e1;
	font-size: 11px;
	font-weight: 600;
	cursor: pointer;
	transition: all 0.15s ease;
}
.btn-refresh:hover {
	background: #1a2538;
	color: #ffffff;
	border-color: rgba(255, 255, 255, 0.2);
}
.spinning {
	display: inline-block;
	animation: spin 1s linear infinite;
}
@keyframes spin {
	100% { transform: rotate(360deg); }
}
.btn-add-crypto {
	display: flex;
	align-items: center;
	gap: 6px;
	padding: 9px 16px;
	border-radius: 8px;
	background: linear-gradient(135deg, #059669 0%, #10b981 100%);
	border: 1px solid rgba(52, 211, 153, 0.4);
	color: #ffffff;
	font-size: 11px;
	font-weight: 700;
	cursor: pointer;
	box-shadow: 0 4px 15px rgba(16, 185, 129, 0.35);
	transition: all 0.2s ease;
}
.btn-add-crypto:hover {
	background: linear-gradient(135deg, #047857 0%, #059669 100%);
	box-shadow: 0 6px 20px rgba(16, 185, 129, 0.5);
	transform: translateY(-1px);
}

/* NAVBAR PILLS: 4 MENU TABS */
.navbar-pills-wrap {
	padding: 16px 24px;
	background: #090e17;
	border-bottom: 1px solid rgba(255, 255, 255, 0.06);
}
.navbar-pills {
	display: flex;
	gap: 10px;
	overflow-x: auto;
	scrollbar-width: none;
}
.nav-pill {
	display: flex;
	align-items: center;
	gap: 8px;
	padding: 9px 18px;
	border-radius: 24px;
	background: #101724;
	border: 1px solid rgba(255, 255, 255, 0.08);
	color: #94a3b8;
	font-size: 12px;
	font-weight: 600;
	cursor: pointer;
	white-space: nowrap;
	transition: all 0.2s ease;
}
.pill-icon-svg {
	display: flex;
	align-items: center;
	justify-content: center;
}
.svg-icon-tab {
	width: 15px;
	height: 15px;
	fill: currentColor;
}
.nav-pill:hover {
	color: #ffffff;
	background: #172234;
	border-color: rgba(255, 255, 255, 0.15);
}
.nav-pill.active {
	color: #ffffff;
	background: linear-gradient(135deg, rgba(16, 185, 129, 0.25) 0%, rgba(6, 78, 59, 0.35) 100%);
	border-color: #10b981;
	box-shadow: 0 0 16px rgba(16, 185, 129, 0.25);
}
.pill-count {
	display: inline-block;
	padding: 2px 7px;
	border-radius: 12px;
	background: rgba(255, 255, 255, 0.08);
	color: #cbd5e1;
	font-size: 10px;
	font-weight: 700;
}
.nav-pill.active .pill-count {
	background: #10b981;
	color: #022c22;
}

/* TOOLBAR & SEARCH ROW */
.crypto-toolbar {
	display: flex;
	align-items: center;
	justify-content: space-between;
	padding: 14px 24px;
	background: #0c121e;
	border-bottom: 1px solid rgba(255, 255, 255, 0.05);
	gap: 16px;
	flex-wrap: wrap;
}
.search-box {
	display: flex;
	align-items: center;
	gap: 8px;
	background: #070a10;
	border: 1px solid rgba(255, 255, 255, 0.09);
	border-radius: 8px;
	padding: 0 12px;
	width: min(380px, 100%);
}
.search-ico {
	color: #64748b;
	display: flex;
	align-items: center;
}
.search-box input {
	width: 100%;
	height: 34px;
	border: 0;
	outline: 0;
	background: transparent;
	color: #f1f5f9;
	font-size: 12px;
}
.clear-search {
	color: #64748b;
	cursor: pointer;
	font-size: 16px;
}
.clear-search:hover {
	color: #ffffff;
}
.filter-group {
	display: flex;
	align-items: center;
	gap: 12px;
}
.select-wrapper {
	display: flex;
	align-items: center;
	gap: 6px;
	font-size: 11px;
	color: #94a3b8;
}
.select-wrapper select {
	padding: 6px 10px;
	border-radius: 6px;
	background: #080c13;
	border: 1px solid rgba(255, 255, 255, 0.09);
	color: #f1f5f9;
	font-size: 11px;
	outline: none;
}

/* TABLE STYLING */
.crypto-table-wrap {
	overflow-x: auto;
}
.crypto-table {
	width: 100%;
	min-width: 860px;
	border-collapse: collapse;
	font-size: 12px;
	text-align: left;
}
.crypto-table thead tr {
	background: #080c13;
	border-bottom: 1px solid rgba(255, 255, 255, 0.07);
}
.crypto-table th {
	padding: 12px 14px;
	color: #64748b;
	font-size: 11px;
	font-weight: 700;
	letter-spacing: 0.4px;
	text-transform: uppercase;
}
.th-check, .td-check {
	width: 36px;
	text-align: center;
}
.th-num, .td-num {
	width: 44px;
	color: #64748b;
	font-family: monospace;
}
.th-actions, .td-actions {
	text-align: center;
	width: 100px;
}
.table-row {
	border-bottom: 1px solid rgba(255, 255, 255, 0.04);
	transition: background 0.15s ease;
}
.table-row:hover {
	background: rgba(16, 185, 129, 0.04);
}
.crypto-table td {
	padding: 12px 14px;
	vertical-align: middle;
}

/* Item Identity Column (Icons from grid) */
.item-id-cell {
	display: flex;
	align-items: center;
	gap: 12px;
}
.item-avatar {
	display: grid;
	place-items: center;
	width: 42px;
	height: 42px;
	border-radius: 8px;
	flex-shrink: 0;
	overflow: hidden;
	background: #090e18;
}
.item-img-admin {
	width: 100%;
	height: 100%;
	object-fit: cover;
	display: block;
	border-radius: 7px;
}
.svg-icon-row {
	width: 17px;
	height: 17px;
}
.item-avatar.atk { background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.3); color: #34d399; }
.item-avatar.room { background: rgba(14, 165, 233, 0.15); border: 1px solid rgba(14, 165, 233, 0.3); color: #38bdf8; }
.item-avatar.vehicle { background: rgba(245, 158, 11, 0.15); border: 1px solid rgba(245, 158, 11, 0.3); color: #fbbf24; }
.item-avatar.cleaning { background: rgba(168, 85, 247, 0.15); border: 1px solid rgba(168, 85, 247, 0.3); color: #c084fc; }

.item-info {
	display: flex;
	flex-direction: column;
	gap: 2px;
}
.item-title {
	color: #ffffff;
	font-size: 13px;
	font-weight: 700;
}
.item-subtitle {
	color: #38bdf8;
	font-size: 11px;
	font-family: monospace;
}
.item-plate {
	display: inline-block;
	color: #fbbf24;
	font-size: 11px;
	font-family: monospace;
	font-weight: 700;
}

/* Badges & Metrics */
.cat-badge {
	display: inline-block;
	padding: 4px 9px;
	border-radius: 6px;
	background: #141f2e;
	border: 1px solid rgba(255, 255, 255, 0.08);
	color: #94a3b8;
	font-size: 11px;
	font-weight: 500;
}
.brand-badge {
	display: inline-block;
	padding: 3px 8px;
	border-radius: 6px;
	background: rgba(56, 189, 248, 0.1);
	border: 1px solid rgba(56, 189, 248, 0.25);
	color: #38bdf8;
	font-size: 11px;
	font-weight: 600;
}
.metric-value {
	font-size: 14px;
	font-weight: 700;
	color: #ffffff;
}
.metric-pill {
	display: inline-block;
	padding: 4px 9px;
	border-radius: 6px;
	background: #131c2b;
	color: #e2e8f0;
	font-size: 11px;
	font-weight: 600;
}
.unit-text, .location-text, .fuel-text {
	color: #94a3b8;
	font-size: 12px;
}
.facility-preview {
	color: #cbd5e1;
	font-size: 11px;
	display: block;
}

/* Status Glowing Badges */
.status-badge {
	display: inline-flex;
	align-items: center;
	gap: 6px;
	padding: 4px 10px;
	border-radius: 20px;
	font-size: 11px;
	font-weight: 700;
	white-space: nowrap;
}
.status-badge .status-dot {
	width: 6px;
	height: 6px;
	border-radius: 50%;
}
.status-green {
	background: rgba(16, 185, 129, 0.12);
	border: 1px solid rgba(16, 185, 129, 0.35);
	color: #34d399;
}
.status-green .status-dot {
	background: #10b981;
	box-shadow: 0 0 6px #10b981;
}

.status-blue {
	background: rgba(14, 165, 233, 0.12);
	border: 1px solid rgba(14, 165, 233, 0.35);
	color: #38bdf8;
}
.status-blue .status-dot {
	background: #0ea5e9;
	box-shadow: 0 0 6px #0ea5e9;
}

.status-amber {
	background: rgba(245, 158, 11, 0.12);
	border: 1px solid rgba(245, 158, 11, 0.35);
	color: #fbbf24;
}
.status-amber .status-dot {
	background: #f59e0b;
	box-shadow: 0 0 6px #f59e0b;
}

.status-red {
	background: rgba(239, 68, 68, 0.12);
	border: 1px solid rgba(239, 68, 68, 0.35);
	color: #f87171;
}
.status-red .status-dot {
	background: #ef4444;
	box-shadow: 0 0 6px #ef4444;
}

/* Actions Column (Edit Pencil Row 3 Col 2 & Delete Trash Row 1 Col 5) */
.actions-group {
	display: flex;
	align-items: center;
	justify-content: center;
	gap: 6px;
}
.btn-act {
	width: 28px;
	height: 28px;
	display: grid;
	place-items: center;
	border-radius: 6px;
	border: 1px solid rgba(255, 255, 255, 0.1);
	background: #131c2c;
	color: #cbd5e1;
	cursor: pointer;
	transition: all 0.15s ease;
	padding: 0;
}
.svg-act-icon {
	width: 14px;
	height: 14px;
}
.btn-act.edit:hover {
	background: #10b981;
	color: #042f2e;
	border-color: #10b981;
}
.btn-act.delete:hover {
	background: #ef4444;
	color: #ffffff;
	border-color: #ef4444;
}
.btn-act.barcode:hover {
	background: #059669;
	color: #ffffff;
	border-color: #059669;
}

/* Loading & Empty State */
.empty-cell {
	padding: 48px 20px;
	text-align: center;
}
.loading-state, .empty-state {
	display: flex;
	flex-direction: column;
	align-items: center;
	gap: 12px;
	color: #94a3b8;
}
.empty-icon-svg {
	color: #64748b;
}
.svg-icon-lg {
	width: 42px;
	height: 42px;
}
.spinner {
	width: 32px;
	height: 32px;
	border: 3px solid rgba(52, 211, 153, 0.15);
	border-top-color: #34d399;
	border-radius: 50%;
	animation: spin 0.8s linear infinite;
}
.btn-reset {
	padding: 6px 14px;
	border-radius: 6px;
	background: #162235;
	border: 1px solid rgba(255, 255, 255, 0.1);
	color: #cbd5e1;
	font-size: 11px;
	cursor: pointer;
}

/* FOOTER & PAGINATION */
.crypto-footer {
	display: flex;
	align-items: center;
	justify-content: space-between;
	padding: 14px 24px;
	background: #080c13;
	border-top: 1px solid rgba(255, 255, 255, 0.06);
	font-size: 12px;
	color: #64748b;
}
.footer-info b {
	color: #e2e8f0;
}
.footer-pagination {
	display: flex;
	align-items: center;
	gap: 8px;
}
.page-btn {
	padding: 5px 12px;
	border-radius: 6px;
	background: #121927;
	border: 1px solid rgba(255, 255, 255, 0.1);
	color: #cbd5e1;
	font-size: 11px;
	cursor: pointer;
	transition: all 0.15s ease;
}
.page-btn:hover:not(:disabled) {
	background: #1e293b;
	color: #ffffff;
}
.page-btn:disabled {
	opacity: 0.4;
	cursor: not-allowed;
}
.page-indicator {
	padding: 4px 10px;
	font-family: monospace;
	color: #94a3b8;
}

/* ==================== MODAL FORM STYLING ==================== */
.modal-backdrop {
	position: fixed;
	inset: 0;
	z-index: 50;
	display: grid;
	place-items: center;
	padding: 20px;
	background: rgba(4, 7, 13, 0.85);
	backdrop-filter: blur(8px);
}
.crypto-modal {
	width: min(650px, 100%);
	max-height: calc(100vh - 40px);
	overflow-y: auto;
	padding: 26px;
	border-radius: 14px;
	background: #0d1421;
	border: 1px solid rgba(255, 255, 255, 0.12);
	box-shadow: 0 25px 60px rgba(0, 0, 0, 0.8);
}
.modal-top {
	display: flex;
	align-items: flex-start;
	justify-content: space-between;
	margin-bottom: 20px;
	border-bottom: 1px solid rgba(255, 255, 255, 0.08);
	padding-bottom: 14px;
}
.modal-tag {
	display: inline-block;
	font-size: 10px;
	font-weight: 700;
	color: #34d399;
	text-transform: uppercase;
	letter-spacing: 0.5px;
	margin-bottom: 4px;
}
.modal-top h3 {
	margin: 0;
	font-size: 18px;
	font-weight: 800;
	color: #ffffff;
}
.modal-top p {
	margin: 4px 0 0;
	color: #94a3b8;
	font-size: 11px;
}
.modal-close-btn {
	width: 32px;
	height: 32px;
	border: 0;
	border-radius: 8px;
	background: #162030;
	color: #94a3b8;
	font-size: 20px;
	cursor: pointer;
}
.modal-close-btn:hover {
	color: #ffffff;
	background: #202d42;
}

.form-grid {
	display: grid;
	grid-template-columns: repeat(2, 1fr);
	gap: 14px;
}
.col-span-2 {
	grid-column: 1 / -1;
}
.form-grid label {
	display: grid;
	gap: 6px;
	color: #94a3b8;
	font-size: 11px;
	font-weight: 600;
}
.form-grid input,
.form-grid select {
	width: 100%;
	min-height: 40px;
	padding: 8px 12px;
	border-radius: 8px;
	background: #070b12;
	border: 1px solid rgba(255, 255, 255, 0.1);
	color: #ffffff;
	font-size: 12px;
	outline: none;
	box-sizing: border-box;
}
.form-grid input:focus,
.form-grid select:focus {
	border-color: #10b981;
	box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2);
}

/* Photo Uploader Component */
.photo-upload-section {
	margin-top: 16px;
	padding: 14px;
	border-radius: 10px;
	background: rgba(15, 23, 42, 0.6);
	border: 1px solid rgba(255, 255, 255, 0.08);
}
.photo-section-label {
	display: flex;
	flex-direction: column;
	gap: 2px;
	margin-bottom: 12px;
}
.photo-section-label span {
	font-size: 12px;
	font-weight: 700;
	color: #f1f5f9;
}
.photo-section-label small {
	font-size: 11px;
	color: #94a3b8;
}
.photo-uploader-box {
	display: flex;
	gap: 16px;
	align-items: flex-start;
}
.photo-preview-wrap {
	position: relative;
	width: 96px;
	height: 96px;
	border-radius: 10px;
	overflow: hidden;
	background: #070b12;
	border: 2px dashed rgba(255, 255, 255, 0.15);
	flex-shrink: 0;
	display: grid;
	place-items: center;
}
.photo-preview-img {
	width: 100%;
	height: 100%;
	object-fit: cover;
	display: block;
}
.preview-badge {
	position: absolute;
	bottom: 4px;
	left: 4px;
	right: 4px;
	text-align: center;
	padding: 2px 4px;
	font-size: 9px;
	font-weight: 700;
	border-radius: 4px;
	background: rgba(15, 23, 42, 0.85);
	color: #94a3b8;
	backdrop-filter: blur(4px);
}
.preview-badge.custom {
	background: rgba(16, 185, 129, 0.85);
	color: #ffffff;
}
.photo-inputs-wrap {
	flex: 1;
	display: flex;
	flex-direction: column;
	gap: 10px;
	min-width: 0;
}
.upload-btn-row {
	display: flex;
	align-items: center;
	gap: 10px;
}
.btn-upload-file {
	display: inline-flex;
	align-items: center;
	gap: 6px;
	padding: 8px 14px;
	border-radius: 8px;
	background: #1e293b;
	border: 1px solid rgba(255, 255, 255, 0.15);
	color: #f8fafc;
	font-size: 11px;
	font-weight: 600;
	cursor: pointer;
	transition: all 0.2s ease;
}
.btn-upload-file:hover:not(.disabled) {
	background: #334155;
	border-color: #10b981;
}
.btn-upload-file.disabled {
	opacity: 0.6;
	cursor: not-allowed;
}
.svg-upload-icon {
	width: 14px;
	height: 14px;
	fill: currentColor;
}
.btn-clear-photo {
	padding: 7px 12px;
	border-radius: 8px;
	background: transparent;
	border: 1px solid rgba(239, 68, 68, 0.35);
	color: #f87171;
	font-size: 11px;
	cursor: pointer;
	transition: all 0.15s ease;
}
.btn-clear-photo:hover {
	background: rgba(239, 68, 68, 0.15);
}
.photo-url-input {
	width: 100%;
	height: 36px;
	padding: 6px 10px;
	border-radius: 6px;
	background: #070b12;
	border: 1px solid rgba(255, 255, 255, 0.1);
	color: #ffffff;
	font-size: 11px;
	outline: none;
	box-sizing: border-box;
}
.photo-url-input:focus {
	border-color: #10b981;
}
.presets-selector {
	display: flex;
	flex-direction: column;
	gap: 6px;
}
.presets-label {
	font-size: 10px;
	color: #94a3b8;
	font-weight: 600;
	text-transform: uppercase;
	letter-spacing: 0.3px;
}
.presets-chips {
	display: flex;
	flex-wrap: wrap;
	gap: 6px;
	max-height: 90px;
	overflow-y: auto;
	padding-right: 4px;
}
.preset-chip {
	display: inline-flex;
	align-items: center;
	gap: 6px;
	padding: 4px 8px;
	border-radius: 6px;
	background: #111a28;
	border: 1px solid rgba(255, 255, 255, 0.08);
	color: #cbd5e1;
	font-size: 10px;
	cursor: pointer;
	transition: all 0.15s ease;
}
.preset-chip:hover {
	background: #1a273b;
	border-color: rgba(255, 255, 255, 0.2);
	color: #ffffff;
}
.preset-chip.active {
	background: rgba(16, 185, 129, 0.2);
	border-color: #10b981;
	color: #34d399;
	font-weight: 700;
}
.preset-chip-thumb {
	width: 18px;
	height: 18px;
	border-radius: 3px;
	object-fit: cover;
}

.modal-footer-actions {
	display: flex;
	justify-content: flex-end;
	gap: 10px;
	margin-top: 22px;
	padding-top: 14px;
	border-top: 1px solid rgba(255, 255, 255, 0.08);
}
.btn-modal-cancel {
	padding: 10px 18px;
	border-radius: 8px;
	background: #162030;
	border: 1px solid rgba(255, 255, 255, 0.1);
	color: #cbd5e1;
	font-size: 12px;
	font-weight: 600;
	cursor: pointer;
}
.btn-modal-submit {
	padding: 10px 20px;
	border-radius: 8px;
	background: linear-gradient(135deg, #059669 0%, #10b981 100%);
	border: 0;
	color: #ffffff;
	font-size: 12px;
	font-weight: 700;
	cursor: pointer;
	box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4);
}
.btn-modal-submit:hover:not(:disabled) {
	background: linear-gradient(135deg, #047857 0%, #059669 100%);
}

/* Common small SVG icons */
.svg-icon-sm {
	width: 14px;
	height: 14px;
}

/* Responsive */
@media (max-width: 900px) {
	.sidebar {
		transform: translateX(-100%);
		transition: transform 0.25s ease;
	}
	.sidebar.open {
		transform: translateX(0);
	}
	.main {
		width: 100%;
		margin-left: 0;
	}
	.mobile-menu {
		display: block;
		margin-right: 14px;
		border: 0;
		background: none;
		color: #ffffff;
		font-size: 22px;
		cursor: pointer;
	}
	.summary {
		grid-template-columns: repeat(2, 1fr);
	}
}
@media (max-width: 600px) {
	.summary {
		grid-template-columns: 1fr;
	}
	.page-content {
		padding: 16px;
	}
	.form-grid {
		grid-template-columns: 1fr;
	}
	.col-span-2 {
		grid-column: span 1;
	}
	.crypto-header {
		flex-direction: column;
		align-items: flex-start;
		gap: 14px;
	}
	.crypto-actions-top {
		width: 100%;
		justify-content: space-between;
	}
}
.mobile-menu {
	display: none;
}

/* Theme Toggle Button */
.theme-toggle-btn {
	display: flex;
	align-items: center;
	gap: 6px;
	padding: 6px 12px;
	border-radius: 20px;
	background: #131c2c;
	border: 1px solid rgba(255, 255, 255, 0.12);
	color: #cbd5e1;
	font-size: 11px;
	font-weight: 600;
	cursor: pointer;
	transition: all 0.2s ease;
	margin-right: 4px;
}
.theme-toggle-btn:hover {
	background: #1e2b40;
	color: #ffffff;
}
.theme-icon {
	display: flex;
	align-items: center;
}
.theme-icon.sun {
	color: #f59e0b;
}
.theme-icon.moon {
	color: #38bdf8;
}
.svg-icon-theme {
	width: 14px;
	height: 14px;
}
.theme-label {
	font-size: 11px;
}

/* ==================== LIGHT THEME OVERRIDES ==================== */
.theme-light {
	background: #f1f5f9;
	color: #1e293b;
}
.theme-light .main {
	background: #f8fafc;
}
.theme-light .topbar {
	background: rgba(255, 255, 255, 0.88);
	border-bottom: 1px solid #e2e8f0;
}
.theme-light .global-search {
	background: #ffffff;
	border-color: #cbd5e1;
}
.theme-light .global-search input {
	color: #0f172a;
}
.theme-light .top-user {
	color: #0f172a;
}
.theme-light .bell-btn {
	background: #f1f5f9;
	color: #475569;
	border: 1px solid #e2e8f0;
}
.theme-light .bell-btn:hover {
	background: #e2e8f0;
	color: #0f172a;
}
.theme-light .theme-toggle-btn {
	background: #ffffff;
	border-color: #cbd5e1;
	color: #1e293b;
	box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05);
}
.theme-light .theme-toggle-btn:hover {
	background: #f8fafc;
	border-color: #94a3b8;
}

/* Light Hero */
.theme-light .hero {
	background: linear-gradient(135deg, #ecfdf5 0%, #f0fdf4 100%);
	border-color: #a7f3d0;
	box-shadow: 0 4px 20px rgba(16, 185, 129, 0.08);
}
.theme-light .hero p {
	color: #059669;
}
.theme-light .hero h1 {
	color: #064e3b;
}
.theme-light .hero span {
	color: #475569;
}
.theme-light .art-svg {
	color: #10b981;
	opacity: 0.18;
}

/* Light Summary Cards */
.theme-light .summary article {
	background: #ffffff;
	border-color: #e2e8f0;
	box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
}
.theme-light .summary small {
	color: #64748b;
}
.theme-light .summary strong {
	color: #0f172a;
}

/* Light Panel & Table */
.theme-light .crypto-panel {
	background: #ffffff;
	border-color: #e2e8f0;
	box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05);
}
.theme-light .crypto-header {
	background: linear-gradient(180deg, #f8fafc 0%, #ffffff 100%);
	border-bottom-color: #e2e8f0;
}
.theme-light .title-with-badge h2 {
	color: #0f172a;
}
.theme-light .subtitle {
	color: #64748b;
}
.theme-light .live-indicator {
	background: #fffbeb;
	border-color: #fde68a;
	color: #b45309;
}
.theme-light .btn-refresh {
	background: #ffffff;
	border-color: #cbd5e1;
	color: #334155;
}
.theme-light .btn-refresh:hover {
	background: #f1f5f9;
	border-color: #94a3b8;
}

/* Light Nav Pills */
.theme-light .navbar-pills-wrap {
	background: #f8fafc;
	border-bottom-color: #e2e8f0;
}
.theme-light .nav-pill {
	background: #ffffff;
	border-color: #e2e8f0;
	color: #475569;
}
.theme-light .nav-pill:hover {
	background: #f1f5f9;
	color: #0f172a;
	border-color: #cbd5e1;
}
.theme-light .nav-pill.active {
	background: #ecfdf5;
	border-color: #10b981;
	color: #047857;
	box-shadow: 0 2px 10px rgba(16, 185, 129, 0.2);
}
.theme-light .nav-pill .pill-count {
	background: #e2e8f0;
	color: #475569;
}
.theme-light .nav-pill.active .pill-count {
	background: #10b981;
	color: #ffffff;
}

/* Light Toolbar */
.theme-light .crypto-toolbar {
	background: #ffffff;
	border-bottom-color: #e2e8f0;
}
.theme-light .search-box {
	background: #f8fafc;
	border-color: #cbd5e1;
}
.theme-light .search-box input {
	color: #0f172a;
}
.theme-light .select-wrapper {
	color: #64748b;
}
.theme-light .select-wrapper select {
	background: #ffffff;
	border-color: #cbd5e1;
	color: #0f172a;
}

/* Light Table */
.theme-light .crypto-table thead tr {
	background: #f8fafc;
	border-bottom-color: #e2e8f0;
}
.theme-light .crypto-table th {
	color: #475569;
}
.theme-light .table-row {
	border-bottom-color: #f1f5f9;
}
.theme-light .table-row:hover {
	background: rgba(16, 185, 129, 0.05);
}
.theme-light .item-title {
	color: #0f172a;
}
.theme-light .item-subtitle {
	color: #0284c7;
}
.theme-light .item-plate {
	color: #d97706;
}
.theme-light .cat-badge {
	background: #f1f5f9;
	border-color: #e2e8f0;
	color: #475569;
}
.theme-light .brand-badge {
	background: #e0f2fe;
	border-color: #bae6fd;
	color: #0284c7;
}
.theme-light .metric-value {
	color: #0f172a;
}
.theme-light .metric-pill {
	background: #f1f5f9;
	color: #1e293b;
	border: 1px solid #e2e8f0;
}
.theme-light .unit-text,
.theme-light .location-text,
.theme-light .fuel-text {
	color: #64748b;
}
.theme-light .facility-preview {
	color: #334155;
}
.theme-light .btn-act {
	background: #f1f5f9;
	border-color: #cbd5e1;
	color: #475569;
}
.theme-light .btn-act:hover {
	color: #ffffff;
}

/* Light Footer & Pagination */
.theme-light .crypto-footer {
	background: #f8fafc;
	border-top-color: #e2e8f0;
	color: #64748b;
}
.theme-light .footer-info b {
	color: #0f172a;
}
.theme-light .page-btn {
	background: #ffffff;
	border-color: #cbd5e1;
	color: #334155;
}
.theme-light .page-btn:hover:not(:disabled) {
	background: #f1f5f9;
}

/* Light Modal */
.theme-light .modal-backdrop {
	background: rgba(15, 23, 42, 0.55);
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
.theme-light .modal-close-btn {
	background: #f1f5f9;
	color: #475569;
}
.theme-light .modal-close-btn:hover {
	background: #e2e8f0;
	color: #0f172a;
}
.theme-light .form-grid label {
	color: #334155;
}
.theme-light .form-grid input,
.theme-light .form-grid select {
	background: #f8fafc;
	border-color: #cbd5e1;
	color: #0f172a;
}
.theme-light .form-grid input:focus,
.theme-light .form-grid select:focus {
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
.theme-light .btn-modal-cancel:hover {
	background: #e2e8f0;
	color: #0f172a;
}

/* Light Mode Photo Uploader */
.theme-light .photo-upload-section {
	background: #f8fafc;
	border-color: #e2e8f0;
}
.theme-light .photo-section-label span {
	color: #0f172a;
}
.theme-light .photo-section-label small {
	color: #64748b;
}
.theme-light .photo-preview-wrap {
	background: #ffffff;
	border-color: #cbd5e1;
}
.theme-light .preview-badge {
	background: rgba(241, 245, 249, 0.95);
	color: #475569;
}
.theme-light .preview-badge.custom {
	background: #10b981;
	color: #ffffff;
}
.theme-light .btn-upload-file {
	background: #ffffff;
	border-color: #cbd5e1;
	color: #334155;
}
.theme-light .btn-upload-file:hover:not(.disabled) {
	background: #f1f5f9;
	border-color: #10b981;
}
.theme-light .photo-url-input {
	background: #ffffff;
	border-color: #cbd5e1;
	color: #0f172a;
}
.theme-light .photo-url-input:focus {
	border-color: #10b981;
}
.theme-light .presets-label {
	color: #64748b;
}
.theme-light .preset-chip {
	background: #ffffff;
	border-color: #cbd5e1;
	color: #334155;
}
.theme-light .preset-chip:hover {
	background: #f1f5f9;
}
.theme-light .preset-chip.active {
	background: #dcfce7;
	border-color: #10b981;
	color: #047857;
}
.theme-light .item-avatar {
	background: #f1f5f9;
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
