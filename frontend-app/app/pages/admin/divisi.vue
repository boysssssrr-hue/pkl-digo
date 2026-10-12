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
					<input v-model="globalSearch" placeholder="Cari divisi, deskripsi, atau barang ATK..." />
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
						<p>Alokasi Kuota Pengambilan ATK <em>✦</em></p>
						<h1>Manajemen Divisi & Kuota Barang (Per 6 Bulan)</h1>
						<span>Tentukan jumlah anggota divisi dan batas kuota pengambilan setiap barang ATK untuk masing-masing divisi dalam rentang 6 bulan ke depan.</span>
					</div>
					<div class="hero-art">
						<svg viewBox="0 0 24 24" class="art-svg" fill="currentColor">
							<path d="M10 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2h-8l-2-2z"/>
						</svg>
					</div>
				</section>

				<!-- Summary Stats Grid -->
				<section class="summary">
					<article>
						<span class="stat-icon green">
							<svg viewBox="0 0 24 24" class="svg-icon-stat" fill="currentColor">
								<path d="M10 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2h-8l-2-2z"/>
							</svg>
						</span>
						<div>
							<small>Total Unit Divisi</small>
							<strong>{{ divisions.length }} Divisi</strong>
							<small class="text-green">Tersimpan di Database</small>
						</div>
					</article>

					<article>
						<span class="stat-icon cyan">
							<svg viewBox="0 0 24 24" class="svg-icon-stat" fill="currentColor">
								<path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
							</svg>
						</span>
						<div>
							<small>Total Anggota Pegawai</small>
							<strong>{{ totalMembers }} Pegawai</strong>
							<small class="text-cyan">Jumlah Seluruh Divisi</small>
						</div>
					</article>

					<article>
						<span class="stat-icon amber">
							<svg viewBox="0 0 24 24" class="svg-icon-stat" fill="currentColor">
								<path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
							</svg>
						</span>
						<div>
							<small>Aturan Kuota Ditetapkan</small>
							<strong>{{ totalLimitsCount }} Aturan</strong>
							<small class="text-amber">Batas Kuota Aktif</small>
						</div>
					</article>

					<article>
						<span class="stat-icon purple">
							<svg viewBox="0 0 24 24" class="svg-icon-stat" fill="currentColor">
								<path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20a2 2 0 0 0 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zm0-12H5V6h14v2z"/>
							</svg>
						</span>
						<div>
							<small>Siklus Alokasi Kuota</small>
							<strong>Per 6 Bulan</strong>
							<small class="text-purple">Semester Berjalan</small>
						</div>
					</article>
				</section>

				<!-- ========================================================= -->
				<!-- TAMPILAN 1: MASTER TABLE DAFTAR SELURUH DIVISI            -->
				<!-- ========================================================= -->
				<section v-if="currentView === 'master'" class="crypto-panel">
					<div class="crypto-header">
						<div class="header-left">
							<div class="title-with-badge">
								<h2>Tabel Master Divisi & Unit Kerja</h2>
								<span class="live-indicator">
									<svg viewBox="0 0 24 24" class="svg-icon-bolt">
										<path d="M12 2L2 12l10 10 10-10L12 2zm1 14.5l-4-5.5h3.2V6.5l4 5.5h-3.2v4.5z" fill="url(#gradient-lightning)"/>
									</svg>
									Database Terhubung
								</span>
							</div>
							<p class="subtitle">Daftar divisi instansi. Klik tombol <b>Detail Kuota</b> pada divisi terkait untuk menavigasi ke tabel kuota masing-masing divisi.</p>
						</div>

						<div class="crypto-actions-top">
							<!-- Tombol Tambah Divisi Baru -->
							<button class="btn-primary-add" type="button" @click="openCreateModal">
								<svg viewBox="0 0 24 24" class="svg-icon-action" fill="currentColor">
									<path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
								</svg>
								<span>Tambah Divisi Baru</span>
							</button>

							<button class="btn-refresh" type="button" @click="fetchDivisions" :disabled="isLoading" title="Muat Ulang Divisi">
								<svg viewBox="0 0 24 24" class="svg-icon-sync" :class="{ spinning: isLoading }" fill="currentColor">
									<path d="M12 4V1L8 5l4 4V6c3.31 0 6 2.69 6 6 0 1.01-.25 1.97-.7 2.8l1.46 1.46A7.93 7.93 0 0 0 20 12c0-4.42-3.58-8-8-8zm0 14c-3.31 0-6-2.69-6-6 0-1.01.25-1.97.7-2.8L5.24 7.74A7.93 7.93 0 0 0 4 12c0 4.42 3.58 8 8 8v3l4-4-4-4v3z"/>
								</svg>
								<span>Sinkron Data</span>
							</button>
						</div>
					</div>

					<!-- Table Search & Filter Bar -->
					<div class="table-filters-row">
						<div class="search-box-wrap">
							<span class="search-icon">
								<svg viewBox="0 0 24 24" class="svg-icon-sm" fill="currentColor"><path d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
							</span>
							<input v-model="filterText" placeholder="Cari nama divisi atau deskripsi..." />
						</div>

						<div class="filters-right">
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

					<!-- Table Wrapper Master Divisi -->
					<div class="crypto-table-wrap">
						<table class="crypto-table">
							<thead>
								<tr>
									<th class="th-num">#</th>
									<th>Nama Divisi & Deskripsi</th>
									<th>Jumlah Anggota</th>
									<th>Periode Kuota</th>
									<th>Alokasi Kuota ATK (Per 6 Bulan)</th>
									<th>Status</th>
									<th class="th-actions">Aksi</th>
								</tr>
							</thead>

							<tbody>
								<tr v-if="isLoading">
									<td :colspan="7" class="empty-cell">
										<div class="loading-state">
											<span class="spinner"></span>
											<p>Menghubungkan ke database dan memuat daftar divisi...</p>
										</div>
									</td>
								</tr>

								<tr v-else-if="filteredDivisions.length === 0">
									<td :colspan="7" class="empty-cell">
										<div class="empty-state">
											<span class="empty-icon-box">
												<svg viewBox="0 0 24 24" class="svg-icon-empty" fill="currentColor">
													<path d="M10 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2h-8l-2-2z"/>
												</svg>
											</span>
											<h4>Tidak Ada Data Divisi</h4>
											<p>Tidak ditemukan data divisi yang cocok dengan filter atau kata kunci pencarian Anda.</p>
										</div>
									</td>
								</tr>

								<tr v-else v-for="(div, idx) in filteredDivisions" :key="div.id" class="table-row">
									<td class="td-num">{{ idx + 1 }}</td>
									<td>
										<div class="division-cell">
											<div class="division-icon-circle">
												<svg viewBox="0 0 24 24" class="svg-icon-building" fill="currentColor">
													<path d="M12 7V3H2v18h20V7H12zM6 19H4v-2h2v2zm0-4H4v-2h2v2zm0-4H4V9h2v2zm0-4H4V5h2v2zm4 12H8v-2h2v2zm0-4H8v-2h2v2zm0-4H8V9h2v2zm0-4H8V5h2v2zm10 12h-8v-2h2v-2h-2v-2h2v-2h-2V9h8v10zm-2-8h-2v2h2v-2zm0 4h-2v2h2v-2z"/>
												</svg>
											</div>
											<div>
												<strong class="division-name-text">{{ div.name }}</strong>
												<span class="block text-muted text-xs">{{ div.description || 'Divisi operasional instansi' }}</span>
											</div>
										</div>
									</td>
									<td>
										<div class="member-chip">
											<svg viewBox="0 0 24 24" class="svg-icon-xs text-cyan" fill="currentColor">
												<path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
											</svg>
											<strong>{{ div.member_count || 1 }}</strong> Pegawai
										</div>
									</td>
									<td>
										<span class="period-pill">
											<svg viewBox="0 0 24 24" class="svg-icon-xs text-purple" fill="currentColor">
												<path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20a2 2 0 0 0 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zm0-12H5V6h14v2z"/>
											</svg>
											Per 6 Bulan
										</span>
									</td>
									<td>
										<!-- BUTTON DETAIL KUOTA SIMBOL -->
										<div class="quota-summary-cell">
											<span class="quota-count-tag" :class="(div.limits?.length || 0) > 0 ? 'has-quota' : 'no-quota'">
												{{ (div.limits?.length || 0) }} Jenis ATK Ditetapkan
											</span>
											<button
												class="btn-detail-quota"
												type="button"
												@click="viewDivisionQuotaDetail(div)"
												title="Buka Tabel Detail Kuota ATK untuk Divisi Ini"
												aria-label="Detail Kuota"
											>
												<svg viewBox="0 0 24 24" class="svg-icon-xs" fill="currentColor">
													<path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
												</svg>
											</button>
										</div>
									</td>
									<td>
										<span class="status-badge" :class="div.is_active ? 'status-green' : 'status-red'">
											<i class="status-dot"></i> {{ div.is_active ? 'Aktif' : 'Nonaktif' }}
										</span>
									</td>
									<td class="td-actions">
										<div class="action-btn-group">
											<!-- Tombol Simbol: Edit Divisi -->
											<button
												class="btn-action edit"
												type="button"
												@click="openEditModal(div)"
												title="Ubah Identitas & Kuota Divisi"
												aria-label="Edit Divisi"
											>
												<svg viewBox="0 0 24 24" class="svg-icon-action" fill="currentColor">
													<path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
												</svg>
											</button>

											<!-- Tombol Simbol: Hapus Divisi -->
											<button
												class="btn-action delete"
												type="button"
												@click="openDeleteModal(div)"
												title="Hapus Divisi"
												aria-label="Hapus Divisi"
											>
												<svg viewBox="0 0 24 24" class="svg-icon-action" fill="currentColor">
													<path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
												</svg>
											</button>
										</div>
									</td>
								</tr>
							</tbody>
						</table>
					</div>

					<!-- Table Footer Info -->
					<div class="crypto-footer">
						<div class="footer-info">
							Total Master Divisi: <b>{{ filteredDivisions.length }}</b> divisi terdaftar
						</div>
						<div class="footer-db-status">
							<span class="status-dot-green"></span> Database Server Divisi Aktif
						</div>
					</div>
				</section>

				<!-- ========================================================= -->
				<!-- TAMPILAN 2: TABEL KHUSUS DETAIL KUOTA PER DIVISI          -->
				<!-- ========================================================= -->
				<section v-else-if="currentView === 'detail'" class="crypto-panel">
					<!-- Header Detail View -->
					<div class="crypto-header">
						<div class="header-left">
							<button class="btn-back-master" type="button" @click="backToMaster">
								<svg viewBox="0 0 24 24" class="svg-icon-sm" fill="currentColor">
									<path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
								</svg>
								<span>Kembali ke Master Divisi</span>
							</button>

							<div class="title-with-badge mt-2">
								<h2>Tabel Kuota ATK: <span class="text-emerald">{{ activeDivision?.name }}</span></h2>
								<span class="period-pill">
									<svg viewBox="0 0 24 24" class="svg-icon-xs text-purple" fill="currentColor">
										<path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20a2 2 0 0 0 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zm0-12H5V6h14v2z"/>
									</svg>
									Periode 6 Bulan
								</span>
							</div>
							<p class="subtitle">Batas maksimal kuota pengambilan barang untuk divisi <b>{{ activeDivision?.name }}</b> yang memiliki <b>{{ activeDivision?.member_count || 1 }} Pegawai</b>.</p>
						</div>

						<div class="crypto-actions-top">
							<!-- Tombol Atur / Tambah Kuota untuk divisi ini -->
							<button class="btn-primary-add" type="button" @click="openEditModal(activeDivision)">
								<svg viewBox="0 0 24 24" class="svg-icon-action" fill="currentColor">
									<path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
								</svg>
								<span>Atur Kuota Divisi Ini</span>
							</button>
						</div>
					</div>

					<!-- NAVBAR PILLS UNTUK BERPINDAH KE TABEL DIVISI LAINNYA DENGAN CEPAT -->
					<div class="navbar-pills-wrap">
						<div class="navbar-pills">
							<button
								v-for="d in divisions"
								:key="d.id"
								type="button"
								class="nav-pill"
								:class="{ active: selectedDivisionId === d.id }"
								@click="switchDivisionDetail(d.id)"
							>
								<span class="pill-icon-svg">
									<svg viewBox="0 0 24 24" class="svg-icon-xs" fill="currentColor">
										<path d="M12 7V3H2v18h20V7H12zM6 19H4v-2h2v2zm0-4H4v-2h2v2zm0-4H4V9h2v2zm0-4H4V5h2v2zm4 12H8v-2h2v2zm0-4H8v-2h2v2zm0-4H8V9h2v2zm0-4H8V5h2v2zm10 12h-8v-2h2v-2h-2v-2h2v-2h-2V9h8v10zm-2-8h-2v2h2v-2zm0 4h-2v2h2v-2z"/>
									</svg>
								</span>
								<span class="pill-text">{{ d.name }}</span>
								<span class="pill-count">{{ d.limits ? d.limits.length : 0 }}</span>
							</button>
						</div>
					</div>

					<!-- Division Info Overview Card -->
					<div class="division-overview-card">
						<div class="overview-item">
							<small>Nama Unit Divisi</small>
							<strong>{{ activeDivision?.name }}</strong>
						</div>
						<div class="overview-item">
							<small>Jumlah Anggota Resmi</small>
							<strong>{{ activeDivision?.member_count || 1 }} Pegawai</strong>
						</div>
						<div class="overview-item">
							<small>Siklus Alokasi</small>
							<strong class="text-purple">Per 6 Bulan (Semester)</strong>
						</div>
						<div class="overview-item">
							<small>Total ATK Dialokasikan</small>
							<strong class="text-green">{{ activeDivisionQuotas.length }} Jenis Barang</strong>
						</div>
					</div>

					<!-- TABEL DETAIL KUOTA KHUSUS DIVISI INI -->
					<div class="crypto-table-wrap">
						<table class="crypto-table">
							<thead>
								<tr>
									<th class="th-num">#</th>
									<th>Barang ATK & Kode Barcode</th>
									<th>Kategori</th>
									<th>Satuan</th>
									<th>Stok Gudang Tersedia</th>
									<th>Batas Maksimal Kuota (Per 6 Bulan)</th>
									<th>Alokasi Rata-rata per Pegawai</th>
									<th>Status Kuota</th>
								</tr>
							</thead>

							<tbody>
								<tr v-if="activeDivisionQuotas.length === 0">
									<td :colspan="8" class="empty-cell">
										<div class="empty-state">
											<span class="empty-icon-box">
												<svg viewBox="0 0 24 24" class="svg-icon-empty" fill="currentColor">
													<path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
												</svg>
											</span>
											<h4>Belum Ada Kuota ATK untuk Divisi Ini</h4>
											<p>Divisi <b>{{ activeDivision?.name }}</b> belum memiliki batas kuota pengambilan barang yang ditetapkan untuk siklus 6 bulan.</p>
											<button class="btn-primary-add mt-3" type="button" @click="openEditModal(activeDivision)">
												+ Tentukan Kuota ATK Sekarang
											</button>
										</div>
									</td>
								</tr>

								<tr v-else v-for="(item, qIdx) in activeDivisionQuotas" :key="item.id" class="table-row">
									<td class="td-num">{{ qIdx + 1 }}</td>
									<td>
										<strong class="text-highlight-prod">{{ item.product?.name || 'Barang ATK' }}</strong>
										<span class="block font-mono text-muted text-xs">{{ item.product?.barcode || 'ATK' }}</span>
									</td>
									<td><span class="badge-subtle">{{ item.product?.category || 'Perlengkapan' }}</span></td>
									<td><span class="badge-unit">{{ item.product?.unit || 'Pcs' }}</span></td>
									<td>
										<span class="stock-badge" :class="(item.product?.stock || 0) > 10 ? 'stock-safe' : 'stock-low'">
											{{ item.product?.stock || 0 }} {{ item.product?.unit || 'Pcs' }}
										</span>
									</td>
									<td>
										<div class="quota-pill-highlight">
											<strong>{{ item.quantity_limit }}</strong>
											<span>{{ item.product?.unit || 'Pcs' }} / 6 Bulan</span>
										</div>
									</td>
									<td>
										<span class="per-member-tag">
											~{{ calcPerMember(item.quantity_limit, activeDivision?.member_count) }} {{ item.product?.unit || 'Pcs' }} / pegawai
										</span>
									</td>
									<td>
										<span class="status-badge status-green">
											<i class="status-dot"></i> Aktif Berjalan
										</span>
									</td>
								</tr>
							</tbody>
						</table>
					</div>

					<!-- Footer Detail -->
					<div class="crypto-footer">
						<div class="footer-info">
							Menampilkan: <b>{{ activeDivisionQuotas.length }}</b> batas kuota barang untuk <b>{{ activeDivision?.name }}</b>
						</div>
						<div class="footer-actions-sub">
							<button class="btn-link" type="button" @click="openEditModal(activeDivision)">Ubah Kuota Barang</button>
							<button class="btn-link text-muted" type="button" @click="backToMaster">Kembali ke Semua Divisi</button>
						</div>
					</div>
				</section>
			</div>
		</main>

		<!-- ================================================================= -->
		<!-- MODAL FORM: TAMBAH / EDIT DIVISI & KUOTA ATK 6 BULAN              -->
		<!-- ================================================================= -->
		<div v-if="showModal" class="crypto-modal-backdrop" @click.self="closeModal">
			<div class="crypto-modal modal-division-form">
				<div class="modal-top">
					<div>
						<div class="modal-title-row">
							<span class="modal-building-icon">
								<svg viewBox="0 0 24 24" class="svg-icon-modal" fill="currentColor">
									<path d="M12 7V3H2v18h20V7H12zM6 19H4v-2h2v2zm0-4H4v-2h2v2zm0-4H4V9h2v2zm0-4H4V5h2v2zm4 12H8v-2h2v2zm0-4H8v-2h2v2zm0-4H8V9h2v2zm0-4H8V5h2v2zm10 12h-8v-2h2v-2h-2v-2h2v-2h-2V9h8v10zm-2-8h-2v2h2v-2zm0 4h-2v2h2v-2z"/>
								</svg>
							</span>
							<h3>{{ editingId ? 'Edit Divisi & Kuota ATK' : 'Tambah Divisi & Kuota ATK Baru' }}</h3>
						</div>
						<p>Tentukan identitas divisi, jumlah anggota, serta batas maksimal kuota pengambilan setiap barang ATK untuk masa 6 bulan ke depan.</p>
					</div>
					<button class="modal-close-btn" type="button" @click="closeModal">✕</button>
				</div>

				<form @submit.prevent="submitDivisionForm" class="modal-body-scroll">
					<div class="form-section-title">
						<svg viewBox="0 0 24 24" class="svg-icon-xs text-amber" fill="currentColor">
							<path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/>
						</svg>
						<span>1. Identitas Unit Divisi</span>
					</div>

					<div class="form-grid">
						<!-- NAMA DIVISI -->
						<div class="form-field">
							<label>
								<span>Nama Divisi <span class="required-star">*</span></span>
							</label>
							<input
								v-model="formData.name"
								required
								placeholder="Contoh: Divisi Keuangan & Anggaran"
								class="input-control"
							/>
						</div>

						<!-- JUMLAH ANGGOTA -->
						<div class="form-field">
							<label>
								<span>Jumlah Anggota Divisi <span class="required-star">*</span></span>
							</label>
							<div class="input-with-suffix">
								<input
									v-model.number="formData.member_count"
									type="number"
									min="1"
									required
									placeholder="Contoh: 8"
									class="input-control"
								/>
								<span class="input-suffix">Pegawai</span>
							</div>
						</div>

						<!-- STATUS AKTIF -->
						<div class="form-field">
							<label>
								<span>Status Operasional</span>
							</label>
							<select v-model="formData.is_active" class="input-control">
								<option :value="true">Aktif (Dapat Mengambil ATK)</option>
								<option :value="false">Nonaktif (Dibekukan)</option>
							</select>
						</div>

						<!-- DESKRIPSI -->
						<div class="form-field">
							<label>
								<span>Deskripsi / Peruntukan</span>
							</label>
							<input
								v-model="formData.description"
								placeholder="Keterangan tugas atau fungsi divisi..."
								class="input-control"
							/>
						</div>
					</div>

					<!-- SECTION 2: ALOKASI KUOTA SETIAP BARANG ATK PER 6 BULAN -->
					<div class="form-section-title mt-4">
						<svg viewBox="0 0 24 24" class="svg-icon-xs text-green" fill="currentColor">
							<path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
						</svg>
						<span>2. Alokasi Kuota Maksimal ATK (Siklus 6 Bulan)</span>
					</div>

					<div class="quota-info-callout">
						<span class="callout-icon">💡</span>
						<p>Setiap divisi memiliki kuota berbeda. Masukkan batas maksimal pengambilan barang selama periode <b>6 Bulan</b>. Kosongkan atau isi <b>0</b> jika barang tidak dialokasikan untuk divisi ini.</p>
					</div>

					<!-- Filter Pencarian Barang di dalam Modal -->
					<div class="modal-product-search">
						<input
							v-model="productSearchInModal"
							placeholder="Cari nama barang ATK (contoh: Pulpen, Kertas, Spidol)..."
							class="input-control input-sm"
						/>
						<div class="quick-preset-btns">
							<button type="button" class="btn-preset-sm" @click="applyDefaultQuota(5)">Set Seragam (5)</button>
							<button type="button" class="btn-preset-sm" @click="applyDefaultQuota(10)">Set Seragam (10)</button>
							<button type="button" class="btn-preset-sm" @click="clearAllQuotas">Kosongkan</button>
						</div>
					</div>

					<!-- DAFTAR BARANG ATK DAN INPUT KUOTA -->
					<div class="quota-table-wrap">
						<table class="quota-table">
							<thead>
								<tr>
									<th>#</th>
									<th>Nama Barang ATK</th>
									<th>Satuan</th>
									<th>Stok Gudang</th>
									<th class="th-quota-input">Maksimal Kuota (Per 6 Bulan)</th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="(prod, pIdx) in modalFilteredProducts" :key="prod.id">
									<td class="td-num text-xs">{{ pIdx + 1 }}</td>
									<td>
										<strong class="text-highlight-prod">{{ prod.name }}</strong>
										<small class="block text-muted text-xs">{{ prod.category }} ({{ prod.barcode }})</small>
									</td>
									<td><span class="badge-unit">{{ prod.unit }}</span></td>
									<td>
										<span class="stock-status" :class="prod.stock > 10 ? 'text-green' : 'text-amber'">
											{{ prod.stock }} {{ prod.unit }}
										</span>
									</td>
									<td class="td-quota-input">
										<div class="quota-input-box">
											<input
												type="number"
												min="0"
												v-model.number="itemQuotas[prod.id]"
												placeholder="0"
												class="input-quota-number"
											/>
											<span class="quota-unit-tag">{{ prod.unit }} / 6bln</span>
										</div>
									</td>
								</tr>
							</tbody>
						</table>
					</div>

					<div class="modal-footer-actions">
						<button class="btn-modal-cancel" type="button" @click="closeModal" :disabled="isSubmitting">
							Batal
						</button>
						<button class="btn-modal-submit" type="submit" :disabled="isSubmitting">
							<span v-if="isSubmitting" class="spinner-sm"></span>
							<span>{{ isSubmitting ? 'Menyimpan...' : (editingId ? 'Simpan Perubahan Divisi' : 'Simpan Divisi & Kuota') }}</span>
						</button>
					</div>
				</form>
			</div>
		</div>

		<!-- ================================================================= -->
		<!-- MODAL KONFIRMASI HAPUS DIVISI                                     -->
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
						<h3>Hapus Data Divisi?</h3>
						<p>Konfirmasi penghapusan unit kerja dan batas kuotanya dari database.</p>
					</div>
					<button class="modal-close-btn" type="button" @click="closeDeleteModal">✕</button>
				</div>

				<div class="delete-body">
					<p>Anda akan menghapus divisi berikut secara permanen:</p>
					<div class="target-division-card">
						<div class="division-icon-circle bg-amber">
							<svg viewBox="0 0 24 24" class="svg-icon-building text-white" fill="currentColor">
								<path d="M12 7V3H2v18h20V7H12zM6 19H4v-2h2v2zm0-4H4v-2h2v2zm0-4H4V9h2v2zm0-4H4V5h2v2zm4 12H8v-2h2v2zm0-4H8v-2h2v2zm0-4H8V9h2v2zm0-4H8V5h2v2zm10 12h-8v-2h2v-2h-2v-2h2v-2h-2V9h8v10zm-2-8h-2v2h2v-2zm0 4h-2v2h2v-2z"/>
							</svg>
						</div>
						<div>
							<strong>{{ divisionToDelete?.name }}</strong>
							<span class="block text-muted text-xs font-mono">Anggota: {{ divisionToDelete?.member_count || 1 }} Pegawai</span>
							<span class="block text-muted text-xs">Total Aturan Kuota: {{ divisionToDelete?.limits?.length || 0 }} Barang</span>
						</div>
					</div>

					<div class="danger-warning-box">
						<span class="danger-icon">
							<svg viewBox="0 0 24 24" class="svg-icon-sm text-red" fill="currentColor">
								<path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
							</svg>
						</span>
						<p>Semua aturan kuota ATK 6 bulan untuk divisi ini akan dihapus. Pengguna yang terdaftar di divisi ini akan otomatis dialihkan ke status non-divisi.</p>
					</div>
				</div>

				<div class="modal-footer-actions">
					<button class="btn-modal-cancel" type="button" @click="closeDeleteModal" :disabled="isDeleting">
						Batal
					</button>
					<button class="btn-modal-danger" type="button" @click="confirmDeleteDivision" :disabled="isDeleting">
						<span v-if="isDeleting" class="spinner-sm"></span>
						<span>{{ isDeleting ? 'Menghapus...' : 'Ya, Hapus Divisi' }}</span>
					</button>
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
						Sistem mengharuskan nama setiap bidang/divisi unik agar alokasi kuota ATK dan penempatan pegawai tidak bentrok. Silakan periksa kembali isian Anda.
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

const router = useRouter()
const config = useRuntimeConfig()
const apiBase = config.public?.apiBase || 'http://127.0.0.1:8000/api'

// Theme & Navigation State
const isDark = ref(true)
const menuOpen = ref(false)
const globalSearch = ref('')
const filterText = ref('')
const statusFilter = ref('Semua')
const isLoading = ref(false)

// VIEW STATE: 'master' (Tabel Divisi) | 'detail' (Tabel Kuota Khusus Divisi Terpilih)
const currentView = ref('master')
const selectedDivisionId = ref(null)

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

// Sidebar Navigation Items
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
		active: false,
		iconSvg: '<svg viewBox="0 0 24 24" class="svg-icon-nav" fill="currentColor"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>'
	},
	{
		name: 'Divisi',
		active: true,
		iconSvg: '<svg viewBox="0 0 24 24" class="svg-icon-nav" fill="currentColor"><path d="M10 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2h-8l-2-2z"/></svg>'
	},
])

// Collections
const divisions = ref([])
const products = ref([])

// Active Selected Division for Detail View
const activeDivision = computed(() => {
	return divisions.value.find(d => d.id === selectedDivisionId.value) || divisions.value[0] || null
})

const activeDivisionQuotas = computed(() => {
	if (!activeDivision.value) return []
	return activeDivision.value.limits || []
})

// Stats Computed
const totalMembers = computed(() => {
	return divisions.value.reduce((sum, d) => sum + (Number(d.member_count) || 1), 0)
})

const totalLimitsCount = computed(() => {
	return divisions.value.reduce((sum, d) => sum + (d.limits ? d.limits.length : 0), 0)
})

// Filtered Divisions Computed for Master View
const filteredDivisions = computed(() => {
	const query = (filterText.value || globalSearch.value || '').toLowerCase().trim()
	const status = statusFilter.value

	return divisions.value.filter(d => {
		// Filter status
		if (status === 'active' && !d.is_active) return false
		if (status === 'inactive' && d.is_active) return false

		// Search term across division name and description
		if (!query) return true
		const searchHaystack = [d.name, d.description].filter(Boolean).join(' ').toLowerCase()

		return searchHaystack.includes(query)
	})
})

// Detail View Navigation Handlers
const viewDivisionQuotaDetail = (div) => {
	selectedDivisionId.value = div.id
	currentView.value = 'detail'
	window.scrollTo({ top: 400, behavior: 'smooth' })
}

const switchDivisionDetail = (divId) => {
	selectedDivisionId.value = divId
}

const backToMaster = () => {
	currentView.value = 'master'
}

// Calculate Quota Per Member
const calcPerMember = (quota, members) => {
	const m = Number(members) || 1
	const q = Number(quota) || 0
	if (m === 0) return q
	const res = q / m
	return Number.isInteger(res) ? res : res.toFixed(1)
}

// Toggle Dark/Light Theme
const toggleTheme = () => {
	isDark.value = !isDark.value
	if (typeof window !== 'undefined') {
		localStorage.setItem('admin_theme', isDark.value ? 'dark' : 'light')
	}
}

// Sidebar Navigation
const activate = async selected => {
	menus.value.forEach(item => { item.active = item.name === selected.name })
	if (selected.name === 'Manajemen Alat') await router.push('/admin/dashboard')
	if (selected.name === 'Laporan') await router.push('/admin/laporan')
	if (selected.name === 'Pengguna') await router.push('/admin/pengguna')
}

// Fetch Divisions & Products from Backend API
const fetchDivisions = async () => {
	isLoading.value = true
	try {
		const res = await $fetch(`${apiBase}/divisions`)
		if (res && res.divisions) {
			divisions.value = res.divisions
			products.value = res.products || []
			if (!selectedDivisionId.value && divisions.value.length > 0) {
				selectedDivisionId.value = divisions.value[0].id
			}
		} else if (Array.isArray(res)) {
			divisions.value = res
		}
	} catch (err) {
		console.warn('Gagal memuat /api/divisions, mencoba fallback endpoint /v1/manage/divisions...', err)
		try {
			const fallbackRes = await $fetch(`${apiBase}/v1/manage/divisions`)
			if (fallbackRes && fallbackRes.divisions) {
				divisions.value = fallbackRes.divisions
				products.value = fallbackRes.products || []
				if (!selectedDivisionId.value && divisions.value.length > 0) {
					selectedDivisionId.value = divisions.value[0].id
				}
			} else if (Array.isArray(fallbackRes)) {
				divisions.value = fallbackRes
			}
		} catch (fallbackErr) {
			console.error('Semua pemanggilan API divisi gagal:', fallbackErr)
			showToast('Gagal memuat data divisi dari database.', 'error')
		}
	} finally {
		isLoading.value = false
	}
}

// ============================================================================
// MODAL LOGIC: CREATE / EDIT DIVISI & KUOTA ATK 6 BULAN
// ============================================================================
const showModal = ref(false)
const editingId = ref(null)
const isSubmitting = ref(false)
const productSearchInModal = ref('')

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

const formData = reactive({
	name: '',
	member_count: 5,
	description: '',
	is_active: true
})

// Map of productId -> quantity_limit
const itemQuotas = reactive({})

const modalFilteredProducts = computed(() => {
	const q = productSearchInModal.value.toLowerCase().trim()
	if (!q) return products.value
	return products.value.filter(p => {
		return (p.name || '').toLowerCase().includes(q) ||
		       (p.category || '').toLowerCase().includes(q) ||
		       (p.barcode || '').toLowerCase().includes(q)
	})
})

const openCreateModal = () => {
	editingId.value = null
	formData.name = ''
	formData.member_count = 5
	formData.description = ''
	formData.is_active = true
	productSearchInModal.value = ''

	// Reset quotas
	Object.keys(itemQuotas).forEach(k => delete itemQuotas[k])
	products.value.forEach(p => {
		itemQuotas[p.id] = 5 // Default quota per 6 bulan
	})

	showModal.value = true
}

const openEditModal = (div) => {
	if (!div) return
	editingId.value = div.id
	formData.name = div.name
	formData.member_count = div.member_count || 5
	formData.description = div.description || ''
	formData.is_active = Boolean(div.is_active)
	productSearchInModal.value = ''

	// Load existing quotas
	Object.keys(itemQuotas).forEach(k => delete itemQuotas[k])
	products.value.forEach(p => {
		const existing = (div.limits || []).find(l => l.product_id === p.id)
		itemQuotas[p.id] = existing ? existing.quantity_limit : 0
	})

	showModal.value = true
}

const closeModal = () => {
	showModal.value = false
	editingId.value = null
}

const applyDefaultQuota = (qty) => {
	products.value.forEach(p => {
		itemQuotas[p.id] = qty
	})
}

const clearAllQuotas = () => {
	products.value.forEach(p => {
		itemQuotas[p.id] = 0
	})
}

const submitDivisionForm = async () => {
	// 1. Validasi Duplikasi Nama Divisi / Bidang di sisi Client
	const targetName = (formData.name || '').trim().toLowerCase()
	if (!targetName) {
		openDuplicateWarning({
			title: 'Nama Divisi Wajib Diisi!',
			subtitle: 'Kolom identitas divisi tidak boleh kosong',
			fieldName: 'NAMA DIVISI',
			message: 'Mohon masukkan nama divisi atau bidang instansi sebelum menyimpan.',
			inputVal: '',
			existingVal: ''
		})
		return
	}

	const dupDiv = divisions.value.find(d => (!editingId.value || d.id !== editingId.value) && (d.name || '').trim().toLowerCase() === targetName)
	if (dupDiv) {
		openDuplicateWarning({
			title: 'Divisi / Bidang Sudah Terdaftar!',
			subtitle: 'Ditemukan nama bidang atau divisi yang sama dalam sistem',
			fieldName: 'NAMA DIVISI / BIDANG',
			message: `Divisi dengan nama "${formData.name}" sudah terdaftar dalam sistem. Setiap nama divisi/bidang wajib unik agar alokasi kuota ATK dan penempatan pegawai tidak tumpang tindih.`,
			inputVal: formData.name,
			existingVal: `${dupDiv.name} (${dupDiv.users_count || (dupDiv.users?.length || 0)} Anggota)`
		})
		return
	}

	isSubmitting.value = true

	// Build limits payload
	const limitsPayload = Object.entries(itemQuotas)
		.map(([prodId, qty]) => ({
			product_id: Number(prodId),
			quantity_limit: Number(qty) || 0
		}))

	const payload = {
		name: formData.name,
		member_count: Number(formData.member_count) || 1,
		description: formData.description,
		is_active: formData.is_active,
		limits: limitsPayload
	}

	try {
		const url = editingId.value
			? `${apiBase}/divisions/${editingId.value}`
			: `${apiBase}/divisions`
		const method = editingId.value ? 'PUT' : 'POST'

		const res = await $fetch(url, {
			method,
			body: payload
		})

		showToast(res.message || 'Data divisi dan kuota ATK 6 bulan berhasil disimpan!')
		closeModal()
		await fetchDivisions()
	} catch (err) {
		console.error('Gagal menyimpan divisi:', err)

		// Periksa apakah error adalah penolakan data duplikat (422)
		const errErrors = err?.data?.errors
		if (errErrors && (errErrors.name || typeof errErrors === 'object')) {
			const firstKey = Object.keys(errErrors)[0]
			const firstMsg = Array.isArray(errErrors[firstKey]) ? errErrors[firstKey][0] : String(errErrors[firstKey])
			openDuplicateWarning({
				title: 'Divisi Sudah Terdaftar di Server!',
				subtitle: 'Database menolak penyimpanan karena nama divisi sudah terdaftar',
				fieldName: 'NAMA DIVISI / BIDANG',
				message: firstMsg,
				inputVal: formData.name,
				existingVal: 'Sudah tersimpan di database'
			})
			isSubmitting.value = false
			return
		}

		try {
			const fallbackUrl = editingId.value
				? `${apiBase}/v1/manage/divisions/${editingId.value}`
				: `${apiBase}/v1/manage/divisions`
			const method = editingId.value ? 'PUT' : 'POST'

			const fallbackRes = await $fetch(fallbackUrl, {
				method,
				body: payload
			})

			showToast(fallbackRes.message || 'Data divisi berhasil disimpan!')
			closeModal()
			await fetchDivisions()
		} catch (innerErr) {
			const innerErrors = innerErr?.data?.errors
			if (innerErrors && (innerErrors.name || typeof innerErrors === 'object')) {
				const firstKey = Object.keys(innerErrors)[0]
				const firstMsg = Array.isArray(innerErrors[firstKey]) ? innerErrors[firstKey][0] : String(innerErrors[firstKey])
				openDuplicateWarning({
					title: 'Divisi Sudah Terdaftar di Server!',
					subtitle: 'Database menolak penyimpanan karena nama divisi sudah terdaftar',
					fieldName: 'NAMA DIVISI / BIDANG',
					message: firstMsg,
					inputVal: formData.name,
					existingVal: 'Sudah tersimpan di database'
				})
				isSubmitting.value = false
				return
			}

			const msg = innerErr?.data?.message || 'Gagal menyimpan divisi ke database.'
			if (msg.toLowerCase().includes('sudah') || msg.toLowerCase().includes('terdaftar') || msg.toLowerCase().includes('unique')) {
				openDuplicateWarning({
					title: 'Peringatan Duplikasi Divisi!',
					subtitle: 'Validasi database menolak nama yang sama',
					fieldName: 'NAMA DIVISI / BIDANG',
					message: msg,
					inputVal: formData.name,
					existingVal: 'Sudah terdaftar di sistem'
				})
			} else {
				showToast(msg, 'error')
			}
		}
	} finally {
		isSubmitting.value = false
	}
}

// ============================================================================
// DELETE DIVISION MODAL LOGIC
// ============================================================================
const showDeleteModal = ref(false)
const divisionToDelete = ref(null)
const isDeleting = ref(false)

const openDeleteModal = (div) => {
	divisionToDelete.value = div
	showDeleteModal.value = true
}

const closeDeleteModal = () => {
	showDeleteModal.value = false
	divisionToDelete.value = null
}

const confirmDeleteDivision = async () => {
	if (!divisionToDelete.value) return

	isDeleting.value = true
	try {
		const res = await $fetch(`${apiBase}/divisions/${divisionToDelete.value.id}`, {
			method: 'DELETE'
		})

		showToast(res.message || `Divisi ${divisionToDelete.value.name} berhasil dihapus.`)
		closeDeleteModal()
		if (selectedDivisionId.value === divisionToDelete.value.id) {
			currentView.value = 'master'
		}
		await fetchDivisions()
	} catch (err) {
		console.error('Gagal menghapus divisi:', err)
		try {
			const fallbackRes = await $fetch(`${apiBase}/v1/manage/divisions/${divisionToDelete.value.id}`, {
				method: 'DELETE'
			})
			showToast(fallbackRes.message || 'Divisi berhasil dihapus.')
			closeDeleteModal()
			if (selectedDivisionId.value === divisionToDelete.value.id) {
				currentView.value = 'master'
			}
			await fetchDivisions()
		} catch (innerErr) {
			const msg = innerErr?.data?.message || 'Gagal menghapus divisi dari database.'
			showToast(msg, 'error')
		}
	} finally {
		isDeleting.value = false
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
	await fetchDivisions()
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
.svg-icon-building {
	width: 14px;
	height: 14px;
}
.svg-icon-check {
	width: 15px;
	height: 15px;
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
.theme-light .division-name-text {
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
.theme-light .input-control {
	background: #f8fafc;
	border-color: #cbd5e1;
	color: #0f172a;
}
.theme-light .input-control:focus {
	background: #ffffff;
	border-color: #10b981;
}
.theme-light .input-suffix {
	background: #e2e8f0;
	color: #475569;
	border-left-color: #cbd5e1;
}
.theme-light .quota-table th {
	background: #f8fafc;
	color: #475569;
	border-bottom-color: #e2e8f0;
}
.theme-light .quota-table td {
	border-bottom-color: #f1f5f9;
}
.theme-light .input-quota-number {
	background: #ffffff;
	border-color: #cbd5e1;
	color: #0f172a;
}
.theme-light .quota-unit-tag {
	color: #64748b;
}
.theme-light .modal-footer-actions {
	border-top-color: #e2e8f0;
}
.theme-light .btn-modal-cancel {
	background: #f1f5f9;
	border-color: #cbd5e1;
	color: #475569;
}
.theme-light .target-division-card {
	background: #f8fafc;
	border-color: #e2e8f0;
}
.theme-light .target-division-card strong {
	color: #0f172a;
}
.theme-light .quota-info-callout {
	background: #eff6ff;
	border-color: #bfdbfe;
	color: #1e40af;
}
.theme-light .quota-info-callout p {
	color: #1e3a8a;
}
.theme-light .btn-detail-quota {
	background: #f1f5f9;
	border-color: #cbd5e1;
	color: #0f172a;
}
.theme-light .btn-detail-quota:hover {
	background: #e6fcf5;
	border-color: #10b981;
	color: #059669;
}
.theme-light .division-overview-card {
	background: #f8fafc;
	border-color: #e2e8f0;
}
.theme-light .overview-item strong {
	color: #0f172a;
}
.theme-light .text-highlight-prod {
	color: #0f172a;
}
.theme-light .quota-pill-highlight {
	background: #e6fcf5;
	border-color: #a7f3d0;
	color: #065f46;
}
.theme-light .btn-back-master {
	background: #f1f5f9;
	border-color: #cbd5e1;
	color: #334155;
}
.theme-light .btn-action.edit {
	background: #ecfdf5;
	color: #059669;
	border-color: #a7f3d0;
}
.theme-light .btn-action.edit:hover {
	background: #10b981;
	color: #ffffff;
	border-color: #10b981;
}
.theme-light .btn-action.delete {
	background: #fef2f2;
	color: #dc2626;
	border-color: #fecaca;
}
.theme-light .btn-action.delete:hover {
	background: #ef4444;
	color: #ffffff;
	border-color: #ef4444;
}
.theme-light .btn-back-master:hover {
	background: #e2e8f0;
	color: #0f172a;
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
.text-emerald { color: #10b981 !important; }

/* ========================================================================== */
/* CRYPTO PANEL & TABLE (CONSISTENT SIZING)                                   */
/* ========================================================================== */
.crypto-panel {
	border-radius: 14px;
	overflow: hidden;
}
.crypto-header {
	display: flex;
	justify-content: space-between;
	align-items: center;
	padding: 20px 24px;
	border-bottom: 1px solid rgba(255, 255, 255, 0.08);
	gap: 16px;
	flex-wrap: wrap;
}
.title-with-badge {
	display: flex;
	align-items: center;
	gap: 12px;
	flex-wrap: wrap;
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
}
.btn-primary-add {
	display: inline-flex;
	align-items: center;
	gap: 6px;
	padding: 8px 16px;
	border-radius: 8px;
	background: #10b981;
	border: none;
	color: #ffffff;
	font-size: 12px;
	font-weight: 700;
	cursor: pointer;
	box-shadow: 0 4px 14px rgba(16, 185, 129, 0.35);
	transition: all 0.15s ease;
}
.btn-primary-add:hover {
	background: #059669;
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

/* BACK TO MASTER BUTTON */
.btn-back-master {
	display: inline-flex;
	align-items: center;
	gap: 6px;
	padding: 6px 12px;
	border-radius: 7px;
	background: rgba(255, 255, 255, 0.08);
	border: 1px solid rgba(255, 255, 255, 0.12);
	color: #cbd5e1;
	font-size: 12px;
	font-weight: 600;
	cursor: pointer;
	transition: all 0.15s ease;
}
.btn-back-master:hover {
	background: rgba(255, 255, 255, 0.15);
	color: #ffffff;
}

/* NAVBAR PILLS (SAME AS DASHBOARD & LAPORAN) */
.navbar-pills-wrap {
	padding: 14px 24px;
	background: rgba(0, 0, 0, 0.12);
	border-bottom: 1px solid rgba(255, 255, 255, 0.06);
	overflow-x: auto;
}
.navbar-pills {
	display: flex;
	align-items: center;
	gap: 8px;
	min-width: max-content;
}
.nav-pill {
	display: inline-flex;
	align-items: center;
	gap: 8px;
	padding: 8px 16px;
	border-radius: 9px;
	background: rgba(255, 255, 255, 0.05);
	border: 1px solid rgba(255, 255, 255, 0.08);
	color: #94a3b8;
	font-size: 12px;
	font-weight: 600;
	cursor: pointer;
	transition: all 0.18s ease;
}
.nav-pill:hover {
	background: rgba(255, 255, 255, 0.09);
	color: #ffffff;
}
.nav-pill.active {
	background: #10b981;
	color: #ffffff;
	border-color: #10b981;
	box-shadow: 0 4px 14px rgba(16, 185, 129, 0.35);
}
.pill-icon-svg {
	display: grid;
	place-items: center;
}
.pill-count {
	display: inline-block;
	padding: 1px 6px;
	border-radius: 10px;
	background: rgba(0, 0, 0, 0.25);
	font-size: 10px;
	font-weight: 700;
}

/* DIVISION OVERVIEW CARD IN DETAIL VIEW */
.division-overview-card {
	display: grid;
	grid-template-columns: repeat(4, 1fr);
	gap: 16px;
	padding: 16px 24px;
	background: rgba(255, 255, 255, 0.02);
	border-bottom: 1px solid rgba(255, 255, 255, 0.06);
}
.overview-item small {
	display: block;
	font-size: 11px;
	color: #64748b;
	margin-bottom: 3px;
}
.overview-item strong {
	display: block;
	font-size: 14px;
	font-weight: 700;
	color: #f1f5f9;
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

/* TABLE (CONSISTENT METRICS) */
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
.th-actions, .td-actions {
	text-align: center;
	width: 110px;
}

/* DIVISION CELL */
.division-cell {
	display: flex;
	align-items: center;
	gap: 12px;
	min-width: 180px;
}
.division-icon-circle {
	display: grid;
	place-items: center;
	width: 36px;
	height: 36px;
	border-radius: 9px;
	background: linear-gradient(135deg, #10b981, #059669);
	color: #ffffff;
	flex-shrink: 0;
	box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
}
.division-icon-circle.bg-amber {
	background: linear-gradient(135deg, #f59e0b, #d97706);
	box-shadow: 0 2px 8px rgba(245, 158, 11, 0.3);
}
.division-name-text {
	display: block;
	font-size: 13px;
	font-weight: 700;
	color: #f1f5f9;
}
.member-chip {
	display: inline-flex;
	align-items: center;
	gap: 5px;
	padding: 4px 10px;
	border-radius: 6px;
	background: rgba(6, 182, 212, 0.12);
	border: 1px solid rgba(6, 182, 212, 0.25);
	font-size: 11px;
	color: #22d3ee;
}
.period-pill {
	display: inline-flex;
	align-items: center;
	gap: 5px;
	padding: 4px 9px;
	border-radius: 20px;
	background: rgba(168, 85, 247, 0.12);
	border: 1px solid rgba(168, 85, 247, 0.25);
	font-size: 11px;
	font-weight: 600;
	color: #c084fc;
	white-space: nowrap;
}

/* QUOTA SUMMARY CELL IN MASTER TABLE (NEAT BUTTON DETAIL) */
.quota-summary-cell {
	display: flex;
	align-items: center;
	gap: 10px;
}
.quota-count-tag {
	display: inline-block;
	padding: 4px 9px;
	border-radius: 6px;
	font-size: 11px;
	font-weight: 600;
	white-space: nowrap;
}
.quota-count-tag.has-quota {
	background: rgba(16, 185, 129, 0.12);
	border: 1px solid rgba(16, 185, 129, 0.3);
	color: #34d399;
}
.quota-count-tag.no-quota {
	background: rgba(255, 255, 255, 0.05);
	border: 1px solid rgba(255, 255, 255, 0.1);
	color: #94a3b8;
}
.btn-detail-quota {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	width: 32px;
	height: 32px;
	border-radius: 8px;
	background: #111a2e;
	border: 1px solid rgba(255, 255, 255, 0.15);
	color: #22d3ee;
	cursor: pointer;
	transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
	flex-shrink: 0;
	padding: 0;
}
.btn-detail-quota:hover {
	background: #06b6d4;
	border-color: #06b6d4;
	color: #ffffff;
	transform: translateY(-2px);
	box-shadow: 0 4px 12px rgba(6, 182, 212, 0.35);
}

/* DETAIL QUOTA TABLE SPECIFICS */
.quota-pill-highlight {
	display: inline-flex;
	align-items: baseline;
	gap: 5px;
	padding: 4px 10px;
	border-radius: 6px;
	background: rgba(16, 185, 129, 0.12);
	border: 1px solid rgba(16, 185, 129, 0.3);
	color: #34d399;
}
.quota-pill-highlight strong {
	font-size: 13px;
}
.quota-pill-highlight span {
	font-size: 11px;
}
.per-member-tag {
	font-size: 11px;
	color: #94a3b8;
	font-family: monospace;
}
.stock-badge {
	display: inline-block;
	padding: 3px 8px;
	border-radius: 5px;
	font-size: 11px;
	font-weight: 600;
}
.stock-safe {
	background: rgba(16, 185, 129, 0.12);
	color: #34d399;
}
.stock-low {
	background: rgba(245, 158, 11, 0.12);
	color: #fbbf24;
}
.badge-subtle {
	display: inline-block;
	padding: 3px 8px;
	border-radius: 5px;
	background: rgba(255, 255, 255, 0.05);
	color: #94a3b8;
	font-size: 11px;
}
.badge-unit {
	display: inline-block;
	padding: 2px 7px;
	border-radius: 4px;
	background: rgba(255, 255, 255, 0.06);
	color: #cbd5e1;
	font-size: 10px;
	font-weight: 600;
}

/* BADGES & ACTIONS */
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

.action-btn-group {
	display: inline-flex;
	align-items: center;
	gap: 8px;
	justify-content: center;
}
.btn-action {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	width: 34px;
	height: 34px;
	border-radius: 8px;
	cursor: pointer;
	transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
	border: 1px solid transparent;
	padding: 0;
	flex-shrink: 0;
}
.btn-action.edit {
	background: rgba(16, 185, 129, 0.14);
	color: #34d399;
	border-color: rgba(16, 185, 129, 0.3);
}
.btn-action.edit:hover {
	background: #10b981;
	color: #ffffff;
	transform: translateY(-2px);
	box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
}
.btn-action.delete {
	background: rgba(239, 68, 68, 0.14);
	color: #f87171;
	border-color: rgba(239, 68, 68, 0.3);
}
.btn-action.delete:hover {
	background: #ef4444;
	color: #ffffff;
	transform: translateY(-2px);
	box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
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
	flex-wrap: wrap;
	gap: 10px;
}
.footer-actions-sub {
	display: flex;
	gap: 12px;
}
.btn-link {
	background: transparent;
	border: none;
	color: #10b981;
	font-size: 12px;
	font-weight: 600;
	cursor: pointer;
}
.btn-link:hover {
	text-decoration: underline;
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
/* MODAL STYLES (DIVISION & QUOTA FORM)                                       */
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
	max-width: 740px;
	background: #0f172a;
	border: 1px solid rgba(255, 255, 255, 0.12);
	border-radius: 16px;
	box-shadow: 0 25px 60px rgba(0, 0, 0, 0.6);
	overflow: hidden;
	animation: modalPop 0.2s cubic-bezier(0.16, 1, 0.3, 1);
	max-height: 90vh;
	display: flex;
	flex-direction: column;
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
	flex-shrink: 0;
}
.modal-title-row {
	display: flex;
	align-items: center;
	gap: 10px;
}
.modal-building-icon {
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

.modal-body-scroll {
	padding: 20px 24px;
	overflow-y: auto;
	flex-grow: 1;
}
.form-section-title {
	display: flex;
	align-items: center;
	gap: 8px;
	font-size: 12px;
	font-weight: 700;
	text-transform: uppercase;
	letter-spacing: 0.5px;
	color: #e2e8f0;
	margin-bottom: 12px;
}
.form-grid {
	display: grid;
	grid-template-columns: 1fr 1fr;
	gap: 14px;
	margin-bottom: 10px;
}
.form-field {
	display: flex;
	flex-direction: column;
}
.form-field label {
	font-size: 12px;
	font-weight: 600;
	color: #cbd5e1;
	margin-bottom: 6px;
}
.required-star {
	color: #ef4444;
}
.input-control {
	width: 100%;
	padding: 9px 12px;
	border-radius: 8px;
	background: #131d33;
	border: 1px solid rgba(255, 255, 255, 0.12);
	color: #ffffff;
	font-size: 13px;
	box-sizing: border-box;
	outline: none;
	transition: all 0.15s ease;
}
.input-control:focus {
	border-color: #10b981;
	box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
}
.input-sm {
	padding: 7px 10px;
	font-size: 12px;
}

/* INPUT SUFFIX */
.input-with-suffix {
	position: relative;
	display: flex;
	align-items: center;
}
.input-with-suffix input {
	padding-right: 75px;
}
.input-suffix {
	position: absolute;
	right: 1px;
	height: calc(100% - 2px);
	padding: 0 10px;
	display: grid;
	place-items: center;
	font-size: 11px;
	color: #94a3b8;
	background: rgba(255, 255, 255, 0.05);
	border-left: 1px solid rgba(255, 255, 255, 0.1);
	border-radius: 0 7px 7px 0;
	pointer-events: none;
}

/* QUOTA CALLOUT & SEARCH */
.quota-info-callout {
	display: flex;
	gap: 10px;
	padding: 10px 14px;
	border-radius: 8px;
	background: rgba(59, 130, 246, 0.1);
	border: 1px solid rgba(59, 130, 246, 0.25);
	margin-bottom: 14px;
	font-size: 12px;
	color: #93c5fd;
	line-height: 1.4;
}
.callout-icon {
	font-size: 14px;
}
.quota-info-callout p {
	margin: 0;
}
.modal-product-search {
	display: flex;
	justify-content: space-between;
	align-items: center;
	gap: 12px;
	margin-bottom: 12px;
	flex-wrap: wrap;
}
.modal-product-search input {
	flex-grow: 1;
	min-width: 220px;
}
.quick-preset-btns {
	display: flex;
	align-items: center;
	gap: 6px;
}
.btn-preset-sm {
	padding: 6px 10px;
	border-radius: 6px;
	background: rgba(255, 255, 255, 0.06);
	border: 1px solid rgba(255, 255, 255, 0.1);
	color: #cbd5e1;
	font-size: 11px;
	font-weight: 600;
	cursor: pointer;
	transition: all 0.15s ease;
}
.btn-preset-sm:hover {
	background: #10b981;
	color: #ffffff;
	border-color: #10b981;
}

/* QUOTA TABLE IN MODAL */
.quota-table-wrap {
	max-height: 280px;
	overflow-y: auto;
	border: 1px solid rgba(255, 255, 255, 0.08);
	border-radius: 8px;
	margin-bottom: 10px;
}
.quota-table {
	width: 100%;
	border-collapse: collapse;
	font-size: 12px;
	text-align: left;
}
.quota-table th {
	padding: 10px 12px;
	background: #111a2e;
	color: #94a3b8;
	font-size: 11px;
	font-weight: 700;
	border-bottom: 1px solid rgba(255, 255, 255, 0.08);
	position: sticky;
	top: 0;
	z-index: 5;
}
.quota-table td {
	padding: 8px 12px;
	border-bottom: 1px solid rgba(255, 255, 255, 0.04);
	vertical-align: middle;
}
.th-quota-input, .td-quota-input {
	width: 180px;
	text-align: right;
}
.text-highlight-prod {
	color: #f1f5f9;
	font-size: 12px;
}
.stock-status {
	font-size: 11px;
	font-weight: 600;
}
.quota-input-box {
	display: inline-flex;
	align-items: center;
	gap: 6px;
	justify-content: flex-end;
}
.input-quota-number {
	width: 65px;
	padding: 6px 8px;
	border-radius: 6px;
	background: #0d1424;
	border: 1px solid rgba(255, 255, 255, 0.15);
	color: #10b981;
	font-size: 12px;
	font-weight: 700;
	text-align: center;
	outline: none;
}
.input-quota-number:focus {
	border-color: #10b981;
	box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2);
}
.quota-unit-tag {
	font-size: 10px;
	color: #94a3b8;
}

/* MODAL FOOTER */
.modal-footer-actions {
	display: flex;
	justify-content: flex-end;
	gap: 10px;
	padding-top: 16px;
	margin-top: 14px;
	border-top: 1px solid rgba(255, 255, 255, 0.08);
	flex-shrink: 0;
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
.target-division-card {
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
	.division-overview-card {
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
	.crypto-header {
		flex-direction: column;
		align-items: flex-start;
	}
	.division-overview-card {
		grid-template-columns: 1fr;
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
