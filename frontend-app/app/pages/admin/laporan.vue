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
			<header class="topbar">
				<button class="mobile-menu" type="button" @click="menuOpen = !menuOpen">☰</button>
				<div class="global-search">
					<span class="search-lens">
						<svg viewBox="0 0 24 24" class="svg-icon-sm" fill="currentColor"><path d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
					</span>
					<input v-model="searchQuery" placeholder="Cari nama pegawai, NIP, agenda, atau divisi..." />
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

			<div class="page-content">
				<!-- Hero Header Section -->
				<section class="hero">
					<div>
						<p>Pusat Audit & Rekapitulasi Data <em>✦</em></p>
						<h1>Laporan Aktivitas Fasilitas & Alat Kantor</h1>
						<span>Pantau riwayat lengkap penggunaan dan permohonan layanan oleh seluruh pengguna secara real-time dari database.</span>
					</div>
					<div class="hero-art">
						<svg viewBox="0 0 24 24" class="art-svg" fill="currentColor">
							<path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
						</svg>
					</div>
				</section>

				<!-- FILTER & DATE RANGE CONTROL CARD -->
				<section class="filter-panel">
					<div class="filter-header">
						<div class="filter-heading-left">
							<!-- Calendar icon from Row 1 Col 3 -->
							<span class="filter-icon-box">
								<svg viewBox="0 0 24 24" class="svg-icon-filter" fill="currentColor">
									<path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20a2 2 0 0 0 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zm0-12H5V6h14v2z"/>
								</svg>
							</span>
							<div>
								<h3>Filter Periode Laporan</h3>
								<p>Pilih rentang tanggal aktivitas untuk menyaring data laporan.</p>
							</div>
						</div>

						<div class="quick-presets">
							<button type="button" class="preset-btn" :class="{ active: selectedPreset === 'all' }" @click="applyPreset('all')">Semua Waktu</button>
							<button type="button" class="preset-btn" :class="{ active: selectedPreset === 'today' }" @click="applyPreset('today')">Hari Ini</button>
							<button type="button" class="preset-btn" :class="{ active: selectedPreset === '7days' }" @click="applyPreset('7days')">7 Hari</button>
							<button type="button" class="preset-btn" :class="{ active: selectedPreset === 'month' }" @click="applyPreset('month')">Bulan Ini</button>
						</div>
					</div>

					<div class="filter-controls-row">
						<!-- 1. Filter Divisi -->
						<div class="filter-field-group">
							<label>
								<span>Filter Unit / Divisi</span>
								<div class="select-with-icon">
									<svg viewBox="0 0 24 24" class="svg-field-icon" fill="currentColor">
										<path d="M12 7V3H2v18h20V7H12zM6 19H4v-2h2v2zm0-4H4v-2h2v2zm0-4H4V9h2v2zm0-4H4V5h2v2zm4 12H8v-2h2v2zm0-4H8v-2h2v2zm0-4H8V9h2v2zm0-4H8V5h2v2zm10 12h-8v-2h2v-2h-2v-2h2v-2h-2V9h8v10zm-2-8h-2v2h2v-2zm0 4h-2v2h2v-2z"/>
									</svg>
									<select v-model="selectedDivision" @change="onDivisionChange" class="filter-select">
										<option value="all">Semua Divisi (Seluruh Unit)</option>
										<option v-for="div in divisionsList" :key="div.id" :value="div.id">
											{{ div.name }}
										</option>
									</select>
								</div>
							</label>
						</div>

						<!-- 2. Filter Tahun -->
						<div class="filter-field-group">
							<label>
								<span>Tahun Grafik</span>
								<select v-model="selectedYear" @change="onYearChange" class="filter-select select-sm">
									<option v-for="yr in availableYears" :key="yr" :value="yr">
										{{ yr }}
									</option>
								</select>
							</label>
						</div>

						<!-- 3. Filter Bulan -->
						<div class="filter-field-group">
							<label>
								<span>Bulan Spesifik</span>
								<select v-model="selectedMonth" @change="onMonthChange" class="filter-select select-sm">
									<option value="all">Semua Bulan (12 Bulan)</option>
									<option v-for="(mName, mIdx) in monthNames" :key="mIdx" :value="mIdx + 1">
										{{ mName }}
									</option>
								</select>
							</label>
						</div>

						<!-- 4. Rentang Tanggal Kalender -->
						<div class="date-input-group">
							<label>
								<span>Tanggal Mulai</span>
								<input type="date" v-model="startDate" />
							</label>
							<span class="date-separator">s/d</span>
							<label>
								<span>Tanggal Selesai</span>
								<input type="date" v-model="endDate" />
							</label>
							<button class="btn-apply-filter" type="button" @click="fetchReports">
								Terapkan Filter
							</button>
							<button v-if="hasActiveFilter" class="btn-reset-filter" type="button" @click="resetFilters" title="Reset Semua Filter">
								✕ Reset
							</button>
						</div>

						<!-- 5. Export Actions -->
						<div class="export-actions">
							<!-- Download Excel Button -->
							<button class="btn-export excel" type="button" @click="downloadExcel" title="Download data dan rekapitulasi diagram batang dalam format Excel CSV">
								<svg viewBox="0 0 24 24" class="svg-icon-export" fill="currentColor">
									<path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/>
								</svg>
								<span>Unduh Excel</span>
							</button>

							<!-- Download PDF Button -->
							<button class="btn-export pdf" type="button" @click="downloadPdf" title="Cetak atau unduh dokumen PDF resmi beserta Diagram Batang">
								<svg viewBox="0 0 24 24" class="svg-icon-export" fill="currentColor">
									<path d="M20 2H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-8.5 7.5c0 .83-.67 1.5-1.5 1.5H9v2H7.5V7H10c.83 0 1.5.67 1.5 1.5v1zm5 2c0 .83-.67 1.5-1.5 1.5h-2.5V7H15c.83 0 1.5.67 1.5 1.5v3zm4-3H19v1h1.5V11H19v2h-1.5V7h3v1.5zM9 9.5h1v-1H9v1zm4.5 2h1v-3h-1v3zM4 6H2v14c0 1.1.9 2 2 2h14v-2H4V6z"/>
								</svg>
								<span>Unduh PDF</span>
							</button>
						</div>
					</div>
				</section>

				<!-- Summary Stats for Activities -->
				<section class="summary">
					<article>
						<span class="stat-icon green">
							<svg viewBox="0 0 24 24" class="svg-icon-stat" fill="currentColor">
								<path d="M19.7 5.7L18.3 4.3c-.8-.8-2.1-.8-2.9 0L4.5 15.2c-.3.3-.5.7-.5 1.1l-1 4.7 4.7-1c.4 0 .8-.2 1.1-.5L19.7 8.6c.8-.8.8-2.1 0-2.9zM7.1 18.5l-1.6.3-.3-1.6L14.7 7.7l1.9 1.9L7.1 18.5z"/>
							</svg>
						</span>
						<div>
							<small>Aktivitas Peminjaman ATK</small>
							<strong>{{ filteredAtkCount }} Laporan</strong>
							<small class="text-green">Tersinkronisasi DB</small>
						</div>
					</article>

					<article>
						<span class="stat-icon cyan">
							<svg viewBox="0 0 24 24" class="svg-icon-stat" fill="currentColor">
								<path d="M12 3L2 8v13h20V8L12 3zm6 16H6v-7h12v7zm-2-5H8v1h8v-1zm0 2H8v1h8v-1z"/>
							</svg>
						</span>
						<div>
							<small>Aktivitas Ruangan Rapat</small>
							<strong>{{ filteredRoomCount }} Laporan</strong>
							<small class="text-cyan">Tersinkronisasi DB</small>
						</div>
					</article>

					<article>
						<span class="stat-icon amber">
							<svg viewBox="0 0 24 24" class="svg-icon-stat" fill="currentColor">
								<path d="M5 11l1.5-4.5C6.8 5.6 7.6 5 8.5 5h7c.9 0 1.7.6 2 1.5L19 11h1c.6 0 1 .4 1 1v4c0 .6-.4 1-1 1h-1v2c0 .6-.4 1-1 1h-1c-.6 0-1-.4-1-1v-2H8v2c0 .6-.4 1-1 1H6c-.6 0-1-.4-1-1v-2H4c-.6 0-1-.4-1-1v-4c0-.6.4-1 1-1h1zm3.2-4l-1 3h9.6l-1-3H8.2zM6.5 15a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zm11 0a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zm-7.5-1h4v-1.5h-4V14z"/>
							</svg>
						</span>
						<div>
							<small>Aktivitas Peminjaman Mobil</small>
							<strong>{{ filteredVehicleCount }} Laporan</strong>
							<small class="text-amber">Tersinkronisasi DB</small>
						</div>
					</article>

					<article>
						<span class="stat-icon purple">
							<svg viewBox="0 0 24 24" class="svg-icon-stat" fill="currentColor">
								<path d="M22.7 19l-9.1-9.1c.9-2.3.4-5-1.5-6.9-2-2-5-2.4-7.4-1.3L9 6 6 9 1.6 4.7C.4 7.1.9 10.1 2.9 12.1c1.9 1.9 4.6 2.4 6.9 1.5l9.1 9.1c.4.4 1 .4 1.4 0l2.3-2.3c.5-.4.5-1.1.1-1.4z"/>
							</svg>
						</span>
						<div>
							<small>Aktivitas Layanan Kebersihan</small>
							<strong>{{ filteredCleaningCount }} Laporan</strong>
							<small class="text-purple">Tersinkronisasi DB</small>
						</div>
					</article>
				</section>

				<!-- ================================================================= -->
				<!-- LEADERBOARD & PERINGKAT KHUSUS ATK (GAMIFIED LEADERBOARD MODEL)   -->
				<!-- 1. Paling Banyak Diambil  |  2. Sisa Stok Paling Sedikit (Kritis) -->
				<!-- ================================================================= -->
				<section v-if="currentTab === 'atk'" class="atk-leaderboard-panel">
					<!-- Leaderboard Header & Controls -->
					<div class="atk-leaderboard-header">
						<div class="lb-header-top">
							<div class="lb-header-info">
								<div class="title-with-pill">
									<span class="cute-badge-pulse">🏆 LEADERBOARD INVENTARIS</span>
									<h3>Leaderboard & Peringkat ATK</h3>
								</div>
								<p>Model leaderboard visual: Ranking barang paling banyak diambil vs radar sisa stok kritis di gudang.</p>
							</div>

							<!-- View Mode Toggles -->
							<div class="lb-view-mode-tabs">
								<button
									type="button"
									class="lb-mode-btn"
									:class="{ active: atkLeaderboardViewMode === 'both' }"
									@click="atkLeaderboardViewMode = 'both'"
								>
									<span>🗂️ Berdampingan (Semua)</span>
								</button>
								<button
									type="button"
									class="lb-mode-btn"
									:class="{ active: atkLeaderboardViewMode === 'taken' }"
									@click="atkLeaderboardViewMode = 'taken'"
								>
									<span>📦 Paling Banyak Diambil</span>
								</button>
								<button
									type="button"
									class="lb-mode-btn"
									:class="{ active: atkLeaderboardViewMode === 'stock' }"
									@click="atkLeaderboardViewMode = 'stock'"
								>
									<span>🚨 Sisa Stok Kritis</span>
								</button>
							</div>
						</div>

						<!-- Filter Rentang Tanggal Pengambilan -->
						<div class="chart-date-filter-box">
							<div class="filter-box-label">
								<span class="icon-cute">📅</span>
								<span>Filter Rentang Tanggal Pengambilan:</span>
							</div>
							<div class="date-controls-flex">
								<div class="date-input-wrap">
									<label>Dari:</label>
									<input type="date" v-model="atkChartStartDate" class="input-chart-date" />
								</div>
								<span class="date-arrow">➔</span>
								<div class="date-input-wrap">
									<label>Sampai:</label>
									<input type="date" v-model="atkChartEndDate" class="input-chart-date" />
								</div>
								<div class="preset-pills-row">
									<button type="button" :class="{ active: isAtkPresetActive('all') }" @click="applyAtkPreset('all')">Semua</button>
									<button type="button" :class="{ active: isAtkPresetActive('month') }" @click="applyAtkPreset('month')">Bulan Ini</button>
									<button type="button" :class="{ active: isAtkPresetActive('30days') }" @click="applyAtkPreset('30days')">30 Hari</button>
									<button type="button" :class="{ active: isAtkPresetActive('7days') }" @click="applyAtkPreset('7days')">7 Hari</button>
									<button v-if="atkChartStartDate || atkChartEndDate" type="button" class="btn-reset-dates" @click="applyAtkPreset('all')">✕ Reset</button>
								</div>
							</div>
						</div>
					</div>

					<!-- Leaderboard Main Grid -->
					<div class="atk-leaderboard-grid" :class="'grid-' + atkLeaderboardViewMode">
						<!-- ============================================================= -->
						<!-- LEADERBOARD 1: BARANG PALING BANYAK DIAMBIL                   -->
						<!-- ============================================================= -->
						<div v-if="atkLeaderboardViewMode === 'both' || atkLeaderboardViewMode === 'taken'" class="lb-card lb-card-taken">
							<div class="lb-card-header">
								<div class="card-title-group">
									<span class="icon-avatar-cute icon-emerald">
										<span class="emoji-cute">👑</span>
										<span class="pulse-ring"></span>
									</span>
									<div>
										<h4>Barang Paling Banyak Diambil</h4>
										<p>Ranking barang ATK dengan volume peminjaman tertinggi</p>
									</div>
								</div>

								<div class="tier-select-wrap">
									<label for="tier-taken-select">
										<span class="tier-label-text">Tampilkan Urutan:</span>
									</label>
									<select id="tier-taken-select" v-model="topTakenTier" class="tier-select-modern select-emerald">
										<option value="1-10">🥇 Top 1 - 10 Terbanyak</option>
										<option value="11-20">🥈 Peringkat 11 - 20</option>
										<option value="21-30">🥉 Peringkat 21 - 30</option>
										<option value="31-40">🏷️ Peringkat 31 - 40</option>
										<option value="41-50">📋 Peringkat 41 - 50</option>
										<option value="all">🌐 Tampilkan Semua ({{ atkMostTakenFullList.length }})</option>
									</select>
								</div>
							</div>

							<!-- Sub-stats Banner -->
							<div class="card-sub-stats taken-sub">
								<span>Total Barang Terambil: <b>{{ totalTakenQuantitySum }} Item</b></span>
								<span>Total Transaksi Pengambilan: <b>{{ totalTakenBorrowingsCount }} Aktivitas</b></span>
							</div>

							<!-- Content Container -->
							<div class="lb-card-body">
								<!-- PODIUM TOP 3 (Shown on Top 1-10 or All) -->
								<div v-if="(topTakenTier === '1-10' || topTakenTier === 'all') && atkTakenTop3.first" class="lb-podium-wrap">
									<!-- 2nd Place: Silver -->
									<div v-if="atkTakenTop3.second" class="podium-col rank-2">
										<div class="podium-avatar-wrap">
											<span class="podium-medal silver">🥈</span>
											<div class="podium-avatar silver-glow">
												<img :src="atkTakenTop3.second.image" :alt="atkTakenTop3.second.name" class="podium-img" @error="$event.target.style.display='none'" />
												<span class="podium-fallback-icon">{{ getItemCuteIcon(atkTakenTop3.second.name) }}</span>
											</div>
											<span class="podium-rank-tag silver">#2</span>
										</div>
										<div class="podium-meta">
											<h5 :title="atkTakenTop3.second.name">{{ atkTakenTop3.second.name }}</h5>
											<div class="podium-stat silver-text">
												<b>{{ atkTakenTop3.second.totalQty }}</b> {{ atkTakenTop3.second.unit }}
											</div>
											<span class="podium-sub">{{ atkTakenTop3.second.borrowCount }}x diajukan</span>
										</div>
										<div class="podium-pedestal pedestal-silver">
											<span class="pedestal-num">2</span>
											<span class="pedestal-label">RUNNER UP</span>
										</div>
									</div>

									<!-- 1st Place: Gold Champion -->
									<div v-if="atkTakenTop3.first" class="podium-col rank-1">
										<div class="podium-avatar-wrap">
											<span class="podium-crown gold">👑</span>
											<div class="podium-avatar gold-glow champion-pulse">
												<img :src="atkTakenTop3.first.image" :alt="atkTakenTop3.first.name" class="podium-img" @error="$event.target.style.display='none'" />
												<span class="podium-fallback-icon">{{ getItemCuteIcon(atkTakenTop3.first.name) }}</span>
											</div>
											<span class="podium-rank-tag gold">🥇 JUARA 1</span>
										</div>
										<div class="podium-meta">
											<h5 :title="atkTakenTop3.first.name">{{ atkTakenTop3.first.name }}</h5>
											<div class="podium-stat gold-text">
												<b>{{ atkTakenTop3.first.totalQty }}</b> {{ atkTakenTop3.first.unit }}
											</div>
											<span class="podium-sub">{{ atkTakenTop3.first.borrowCount }}x diajukan</span>
										</div>
										<div class="podium-pedestal pedestal-gold">
											<span class="pedestal-num">1</span>
											<span class="pedestal-label">TERFAVORIT</span>
										</div>
									</div>

									<!-- 3rd Place: Bronze -->
									<div v-if="atkTakenTop3.third" class="podium-col rank-3">
										<div class="podium-avatar-wrap">
											<span class="podium-medal bronze">🥉</span>
											<div class="podium-avatar bronze-glow">
												<img :src="atkTakenTop3.third.image" :alt="atkTakenTop3.third.name" class="podium-img" @error="$event.target.style.display='none'" />
												<span class="podium-fallback-icon">{{ getItemCuteIcon(atkTakenTop3.third.name) }}</span>
											</div>
											<span class="podium-rank-tag bronze">#3</span>
										</div>
										<div class="podium-meta">
											<h5 :title="atkTakenTop3.third.name">{{ atkTakenTop3.third.name }}</h5>
											<div class="podium-stat bronze-text">
												<b>{{ atkTakenTop3.third.totalQty }}</b> {{ atkTakenTop3.third.unit }}
											</div>
											<span class="podium-sub">{{ atkTakenTop3.third.borrowCount }}x diajukan</span>
										</div>
										<div class="podium-pedestal pedestal-bronze">
											<span class="pedestal-num">3</span>
											<span class="pedestal-label">3RD PLACE</span>
										</div>
									</div>
								</div>

								<!-- RANKED LIST: #4 ONWARDS OR CURRENT TIER -->
								<div v-if="atkTakenRestList.length > 0" class="lb-table-wrap">
									<div class="lb-table-header">
										<span class="th-rank">POS</span>
										<span class="th-item">BARANG ATK</span>
										<span class="th-progress">INTENSITAS PENGAMBILAN</span>
										<span class="th-score">TOTAL DIAMBIL</span>
									</div>
									<div class="lb-list-body">
										<div
											v-for="item in atkTakenRestList"
											:key="'taken-' + item.id + '-' + item.name"
											class="lb-row"
											:class="{ 'lb-row-top': item.rank <= 3 }"
										>
											<div class="lb-col-rank">
												<span class="lb-rank-badge" :class="getRankBadgeClass(item.rank)">
													{{ item.rank === 1 ? '🥇' : item.rank === 2 ? '🥈' : item.rank === 3 ? '🥉' : '#' + item.rank }}
												</span>
											</div>
											<div class="lb-col-item">
												<div class="lb-item-thumb">
													<img :src="item.image" :alt="item.name" class="lb-thumb-img" @error="$event.target.style.display='none'" />
													<span class="lb-fallback-icon">{{ getItemCuteIcon(item.name) }}</span>
												</div>
												<div class="lb-item-text">
													<span class="lb-item-name" :title="item.name">{{ item.name }}</span>
													<span class="lb-item-unit">{{ item.borrowCount }}x diajukan • Satuan: {{ item.unit }}</span>
												</div>
											</div>
											<div class="lb-col-progress">
												<div class="lb-progress-bar-wrap">
													<div
														class="lb-progress-bar-fill fill-emerald"
														:style="{ width: item.percentOfMax + '%' }"
													></div>
												</div>
												<span class="lb-progress-percent">{{ item.percentOfMax }}%</span>
											</div>
											<div class="lb-col-score">
												<span class="lb-score-main emerald-highlight">{{ item.totalQty }}</span>
												<span class="lb-score-unit">{{ item.unit }}</span>
											</div>
										</div>
									</div>
								</div>

								<!-- Empty State -->
								<div v-else-if="!atkTakenTop3.first" class="chart-empty-state">
									<span class="empty-emoji">📦</span>
									<p>Tidak ada transaksi pengambilan ATK pada rentang tanggal ini.</p>
								</div>
							</div>
						</div>

						<!-- ============================================================= -->
						<!-- LEADERBOARD 2: STATUS SISA STOK PALING SEDIKIT (KRITIS)       -->
						<!-- ============================================================= -->
						<div v-if="atkLeaderboardViewMode === 'both' || atkLeaderboardViewMode === 'stock'" class="lb-card lb-card-stock">
							<div class="lb-card-header">
								<div class="card-title-group">
									<span class="icon-avatar-cute icon-rose">
										<span class="emoji-cute">🚨</span>
										<span class="pulse-ring red"></span>
									</span>
									<div>
										<h4>Radar Sisa Stok Paling Sedikit (Kritis)</h4>
										<p>Prioritas restock: Barang dengan sisa stok fisik paling menipis di gudang</p>
									</div>
								</div>

								<div class="tier-select-wrap">
									<label for="tier-stock-select">
										<span class="tier-label-text">Tampilkan Urutan:</span>
									</label>
									<select id="tier-stock-select" v-model="lowestStockTier" class="tier-select-modern select-rose">
										<option value="1-10">🚨 Top 1 - 10 Paling Kritis</option>
										<option value="11-20">⚠️ Peringkat 11 - 20</option>
										<option value="21-30">🟡 Peringkat 21 - 30</option>
										<option value="31-40">📋 Peringkat 31 - 40</option>
										<option value="41-50">📦 Peringkat 41 - 50</option>
										<option value="all">🌐 Tampilkan Semua ({{ atkLowestStockFullList.length }})</option>
									</select>
								</div>
							</div>

							<!-- Sub-stats Banner -->
							<div class="card-sub-stats stock-sub">
								<span>Stok Habis (0): <b class="text-rose">{{ outOfStockCount }} Item</b></span>
								<span>Stok Kritis (1-5): <b class="text-amber">{{ criticalStockCount }} Item</b></span>
							</div>

							<!-- Content Container -->
							<div class="lb-card-body">
								<!-- PODIUM TOP 3 (Shown on Top 1-10 or All) -->
								<div v-if="(lowestStockTier === '1-10' || lowestStockTier === 'all') && atkStockTop3.first" class="lb-podium-wrap stock-podium">
									<!-- 2nd Most Critical: Rank 2 -->
									<div v-if="atkStockTop3.second" class="podium-col rank-2">
										<div class="podium-avatar-wrap">
											<span class="podium-medal warning">⚠️</span>
											<div class="podium-avatar warning-glow">
												<img :src="atkStockTop3.second.image" :alt="atkStockTop3.second.name" class="podium-img" @error="$event.target.style.display='none'" />
												<span class="podium-fallback-icon">{{ getItemCuteIcon(atkStockTop3.second.name) }}</span>
											</div>
											<span class="podium-rank-tag warning">#2 Kritis</span>
										</div>
										<div class="podium-meta">
											<h5 :title="atkStockTop3.second.name">{{ atkStockTop3.second.name }}</h5>
											<div class="podium-stat text-amber">
												<b>{{ atkStockTop3.second.stock }}</b> {{ atkStockTop3.second.unit }}
											</div>
											<span class="podium-status-tag" :class="getStockPillClass(atkStockTop3.second.stock)">
												{{ getStockStatusText(atkStockTop3.second.stock) }}
											</span>
										</div>
										<div class="podium-pedestal pedestal-stock-2">
											<span class="pedestal-num">2</span>
											<span class="pedestal-label">PRIORITAS 2</span>
										</div>
									</div>

									<!-- 1st Most Critical: Rank 1 -->
									<div v-if="atkStockTop3.first" class="podium-col rank-1">
										<div class="podium-avatar-wrap">
											<span class="podium-crown danger">🚨</span>
											<div class="podium-avatar danger-glow danger-pulse">
												<img :src="atkStockTop3.first.image" :alt="atkStockTop3.first.name" class="podium-img" @error="$event.target.style.display='none'" />
												<span class="podium-fallback-icon">{{ getItemCuteIcon(atkStockTop3.first.name) }}</span>
											</div>
											<span class="podium-rank-tag danger">🚨 PALING KRITIS</span>
										</div>
										<div class="podium-meta">
											<h5 :title="atkStockTop3.first.name">{{ atkStockTop3.first.name }}</h5>
											<div class="podium-stat text-rose">
												<b>{{ atkStockTop3.first.stock }}</b> {{ atkStockTop3.first.unit }}
											</div>
											<span class="podium-status-tag" :class="getStockPillClass(atkStockTop3.first.stock)">
												{{ getStockStatusText(atkStockTop3.first.stock) }}
											</span>
										</div>
										<div class="podium-pedestal pedestal-stock-1">
											<span class="pedestal-num">1</span>
											<span class="pedestal-label">DARURAT RESTOCK</span>
										</div>
									</div>

									<!-- 3rd Most Critical: Rank 3 -->
									<div v-if="atkStockTop3.third" class="podium-col rank-3">
										<div class="podium-avatar-wrap">
											<span class="podium-medal amber">🟡</span>
											<div class="podium-avatar amber-glow">
												<img :src="atkStockTop3.third.image" :alt="atkStockTop3.third.name" class="podium-img" @error="$event.target.style.display='none'" />
												<span class="podium-fallback-icon">{{ getItemCuteIcon(atkStockTop3.third.name) }}</span>
											</div>
											<span class="podium-rank-tag amber">#3 Kritis</span>
										</div>
										<div class="podium-meta">
											<h5 :title="atkStockTop3.third.name">{{ atkStockTop3.third.name }}</h5>
											<div class="podium-stat text-yellow">
												<b>{{ atkStockTop3.third.stock }}</b> {{ atkStockTop3.third.unit }}
											</div>
											<span class="podium-status-tag" :class="getStockPillClass(atkStockTop3.third.stock)">
												{{ getStockStatusText(atkStockTop3.third.stock) }}
											</span>
										</div>
										<div class="podium-pedestal pedestal-stock-3">
											<span class="pedestal-num">3</span>
											<span class="pedestal-label">PRIORITAS 3</span>
										</div>
									</div>
								</div>

								<!-- RANKED LIST: #4 ONWARDS OR CURRENT TIER -->
								<div v-if="atkStockRestList.length > 0" class="lb-table-wrap">
									<div class="lb-table-header">
										<span class="th-rank">POS</span>
										<span class="th-item">BARANG ATK</span>
										<span class="th-progress">LEVEL SISA STOK</span>
										<span class="th-score">STATUS & SISA</span>
									</div>
									<div class="lb-list-body">
										<div
											v-for="item in atkStockRestList"
											:key="'stock-' + item.id + '-' + item.name"
											class="lb-row"
											:class="{ 'lb-row-danger': item.stock === 0, 'lb-row-warning': item.stock > 0 && item.stock <= 5 }"
										>
											<div class="lb-col-rank">
												<span class="lb-rank-badge" :class="getStockRankBadgeClass(item.stock, item.rank)">
													{{ item.stock === 0 ? '🚨 0' : item.stock <= 5 ? '⚠️ ' + item.stock : '#' + item.rank }}
												</span>
											</div>
											<div class="lb-col-item">
												<div class="lb-item-thumb">
													<img :src="item.image" :alt="item.name" class="lb-thumb-img" @error="$event.target.style.display='none'" />
													<span class="lb-fallback-icon">{{ getItemCuteIcon(item.name) }}</span>
												</div>
												<div class="lb-item-text">
													<span class="lb-item-name" :title="item.name">{{ item.name }}</span>
													<span class="lb-item-unit">Satuan: {{ item.unit }}</span>
												</div>
											</div>
											<div class="lb-col-progress">
												<div class="lb-progress-bar-wrap">
													<div
														class="lb-progress-bar-fill"
														:class="getStockBarFillClass(item.stock)"
														:style="{ width: item.percentOfMax + '%' }"
													></div>
												</div>
												<span class="lb-progress-percent">{{ item.stock }} {{ item.unit }}</span>
											</div>
											<div class="lb-col-score">
												<span class="lb-status-pill" :class="getStockPillClass(item.stock)">
													{{ getStockStatusText(item.stock) }}
												</span>
											</div>
										</div>
									</div>
								</div>

								<!-- Empty State -->
								<div v-else-if="!atkStockTop3.first" class="chart-empty-state">
									<span class="empty-emoji">📦</span>
									<p>Tidak ada data inventaris barang.</p>
								</div>
							</div>
						</div>
					</div>
				</section>

				

				<!-- DIAGRAM BATANG TREN BULANAN (UNTUK KATEGORI LAIN) -->
				<section v-else class="chart-panel">
					<div class="chart-header">
						<div class="chart-heading-left">
							<span class="chart-icon-box">
								<svg viewBox="0 0 24 24" class="svg-icon-chart" fill="currentColor">
									<path d="M5 9.2h3V19H5zM10.6 5h2.8v14h-2.8zm5.6 8H19v6h-2.8z"/>
								</svg>
							</span>
							<div>
								<div class="title-with-badge">
									<h3>Diagram Batang Tren Aktivitas Bulanan</h3>
									<span class="badge-year">Tahun {{ selectedYear }}</span>
									<span v-if="selectedDivision !== 'all'" class="badge-active-division">
										{{ getSelectedDivisionName() }}
									</span>
									<span v-if="selectedMonth !== 'all'" class="badge-active-month">
										Bulan {{ monthNames[selectedMonth - 1] }}
									</span>
								</div>
								<p>Grafik aktivitas per bulan (Jan - Des). Klik pada batang untuk memfilter rincian tabel.</p>
							</div>
						</div>

						<div class="chart-actions-right">
							<!-- Mode Switcher: Akumulasi Semua vs Tab Aktif -->
							<div class="chart-mode-pill">
								<button
									type="button"
									:class="{ active: chartMode === 'all' }"
									@click="chartMode = 'all'"
								>
									Semua Kategori
								</button>
								<button
									type="button"
									:class="{ active: chartMode === 'tab' }"
									@click="chartMode = 'tab'"
								>
									{{ activeTabLabelShort }} Saja
								</button>
							</div>

							<!-- Button Cetak PDF khusus diagram -->
							<button class="btn-chart-print" type="button" @click="downloadPdf" title="Cetak Diagram Batang & Laporan ke PDF">
								<svg viewBox="0 0 24 24" class="svg-icon-btn" fill="currentColor">
									<path d="M19 8H5c-1.66 0-3 1.34-3 3v6h4v4h12v-4h4v-6c0-1.66-1.34-3-3-3zm-3 11H8v-5h8v5zm3-7c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm-1-9H6v4h12V3z"/>
								</svg>
								<span>Cetak PDF</span>
							</button>
						</div>
					</div>

					<!-- Chart Visualization Area -->
					<div class="chart-body" ref="chartBodyRef">
						<div class="svg-chart-wrapper">
							<svg class="monthly-bar-svg" viewBox="0 0 960 270" preserveAspectRatio="none">
								<!-- Gradients -->
								<defs>
									<linearGradient id="bar-grad-emerald" x1="0%" y1="0%" x2="0%" y2="100%">
										<stop offset="0%" stop-color="#34d399" />
										<stop offset="100%" stop-color="#059669" />
									</linearGradient>
									<linearGradient id="bar-grad-active" x1="0%" y1="0%" x2="0%" y2="100%">
										<stop offset="0%" stop-color="#fbbf24" />
										<stop offset="100%" stop-color="#d97706" />
									</linearGradient>
									<linearGradient id="bar-grad-cyan" x1="0%" y1="0%" x2="0%" y2="100%">
										<stop offset="0%" stop-color="#38bdf8" />
										<stop offset="100%" stop-color="#0284c7" />
									</linearGradient>
									<linearGradient id="bar-grad-purple" x1="0%" y1="0%" x2="0%" y2="100%">
										<stop offset="0%" stop-color="#c084fc" />
										<stop offset="100%" stop-color="#9333ea" />
									</linearGradient>
									<linearGradient id="bar-grad-amber" x1="0%" y1="0%" x2="0%" y2="100%">
										<stop offset="0%" stop-color="#fbbf24" />
										<stop offset="100%" stop-color="#b45309" />
									</linearGradient>
								</defs>

								<!-- Grid lines & Y Axis labels -->
								<g class="chart-grid">
									<line v-for="grid in chartGridLines" :key="grid.val"
										:x1="55" :y1="grid.y" :x2="945" :y2="grid.y"
										class="grid-line" />
									<text v-for="grid in chartGridLines" :key="'lbl-' + grid.val"
										:x="42" :y="grid.y + 4"
										class="y-axis-label">{{ grid.val }}</text>
								</g>

								<!-- 12 Monthly Bars -->
								<g class="chart-bars">
									<g v-for="m in monthlyChartBars" :key="m.month"
										class="bar-group"
										:class="{ 'bar-selected': selectedMonth === m.month, 'bar-hovered': hoveredMonth === m.month }"
										@mouseenter="onBarHover(m.month, $event)"
										@mouseleave="hoveredMonth = null"
										@click="selectMonth(m.month)"
									>
										<!-- Transparent Hitbox -->
										<rect
											:x="m.x - 14"
											y="15"
											width="64"
											height="230"
											class="bar-hover-zone"
										/>

										<!-- Bar Rectangle -->
										<rect
											:x="m.x"
											:y="225 - m.barHeight"
											width="36"
											:height="Math.max(m.barHeight, 3)"
											rx="5"
											ry="5"
											class="bar-rect"
											:fill="getBarFill(m)"
										/>

										<!-- Number on Top -->
										<text
											:x="m.x + 18"
											:y="225 - m.barHeight - 7"
											class="bar-val-text"
											:class="{ 'has-data': m.displayCount > 0 }"
										>
											{{ m.displayCount }}
										</text>

										<!-- Month Label on Bottom -->
										<text
											:x="m.x + 18"
											y="248"
											class="x-axis-label"
											:class="{ 'active-month': selectedMonth === m.month }"
										>
											{{ m.shortName }}
										</text>
									</g>
								</g>
							</svg>

							<!-- Floating Tooltip on Hover -->
							<div
								v-if="hoveredMonthData"
								class="chart-tooltip-floating"
								:style="{ left: tooltipPos.x + 'px', top: tooltipPos.y + 'px' }"
							>
								<div class="tooltip-header">
									<strong>{{ hoveredMonthData.fullName }} {{ selectedYear }}</strong>
									<span class="tooltip-badge">{{ hoveredMonthData.total }} Total</span>
								</div>
								<div class="tooltip-breakdown">
									<div class="tooltip-row text-green">
										<span>Peminjaman ATK:</span>
										<b>{{ hoveredMonthData.atk }}</b>
									</div>
									<div class="tooltip-row text-cyan">
										<span>Peminjaman Ruangan:</span>
										<b>{{ hoveredMonthData.rooms }}</b>
									</div>
									<div class="tooltip-row text-amber">
										<span>Peminjaman Kendaraan:</span>
										<b>{{ hoveredMonthData.vehicles }}</b>
									</div>
									<div class="tooltip-row text-purple">
										<span>Layanan Kebersihan:</span>
										<b>{{ hoveredMonthData.cleaning }}</b>
									</div>
								</div>
								<div class="tooltip-tip">
									{{ selectedMonth === hoveredMonthData.month ? 'Klik untuk kembali ke semua bulan' : 'Klik batang untuk memfilter tabel ke bulan ini' }}
								</div>
							</div>
						</div>

						<!-- Legend & Key KPI Cards -->
						<div class="chart-summary-row">
							<div class="chart-legend-box">
								<span class="legend-item" :class="{ 'legend-active': chartMode === 'all' || currentTab === 'atk' }">
									<i class="legend-dot green"></i> ATK
								</span>
								<span class="legend-item" :class="{ 'legend-active': chartMode === 'all' || currentTab === 'ruangan' }">
									<i class="legend-dot cyan"></i> Ruangan
								</span>
								<span class="legend-item" :class="{ 'legend-active': chartMode === 'all' || currentTab === 'mobil' }">
									<i class="legend-dot amber"></i> Mobil
								</span>
								<span class="legend-item" :class="{ 'legend-active': chartMode === 'all' || currentTab === 'cleaning' }">
									<i class="legend-dot purple"></i> Kebersihan
								</span>
							</div>

							<div class="chart-kpi-grid">
								<div class="kpi-card">
									<small>Total Aktivitas {{ selectedYear }}</small>
									<strong>{{ yearTotalCount }}</strong>
								</div>
								<div class="kpi-card">
									<small>Bulan Tertinggi</small>
									<strong class="text-emerald">{{ peakMonthName }} ({{ peakMonthCount }})</strong>
								</div>
								<div class="kpi-card">
									<small>Rata-rata Bulanan</small>
									<strong>{{ avgMonthlyCount }}/bln</strong>
								</div>
								<div class="kpi-card">
									<small>Divisi Aktif</small>
									<strong class="text-highlight truncate-text" :title="getSelectedDivisionName()">{{ getSelectedDivisionName() }}</strong>
								</div>
							</div>
						</div>
					</div>
				</section>

				<!-- CORE SECTION: TABEL LAPORAN (4 MENU TABS) -->
				<section class="crypto-panel">
					<div class="crypto-header">
						<div class="header-left">
							<div class="title-with-badge">
								<h2>Tabel Laporan: {{ activeTabTitle }}</h2>
								<span class="live-indicator">
									<svg viewBox="0 0 24 24" class="svg-icon-bolt">
										<path d="M12 2L2 12l10 10 10-10L12 2zm1 14.5l-4-5.5h3.2V6.5l4 5.5h-3.2v4.5z" fill="url(#gradient-lightning)"/>
									</svg>
									Live DB
								</span>
							</div>
							<p class="subtitle">Laporan aktivitas otomatis terbarui saat user melakukan permohonan melalui portal pengguna.</p>
						</div>

						<div class="crypto-actions-top">
							<button class="btn-refresh" type="button" @click="fetchReports" :disabled="isLoading" title="Muat Ulang Laporan">
								<span :class="{ spinning: isLoading }">↻</span> Sinkron Data
							</button>
						</div>
					</div>

					<!-- Approval Notification Banner -->
					<div v-if="approvalAlert" class="m-4 p-3.5 rounded-xl border flex items-center justify-between text-xs font-semibold transition" :class="approvalAlert.type === 'error' ? 'bg-red-500/15 border-red-500/40 text-red-300' : 'bg-emerald-500/15 border-emerald-500/40 text-emerald-300'">
						<div class="flex items-center gap-2.5">
							<span class="text-base">{{ approvalAlert.type === 'error' ? '⚠️' : '✅' }}</span>
							<span>{{ approvalAlert.message }}</span>
						</div>
						<button @click="approvalAlert = null" class="text-slate-400 hover:text-white px-2 py-0.5 rounded">✕</button>
					</div>

					<!-- TAB NAVBAR: 4 MENU (ATK, Ruangan, Mobil, Cleaning Service) -->
					<div class="navbar-pills-wrap">
						<div class="navbar-pills">
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
								<span class="pill-text">Peminjaman ATK</span>
								<span class="pill-count">{{ atkReports.length }}</span>
							</button>

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
								<span class="pill-text">Peminjaman Ruangan</span>
								<span class="pill-count">{{ roomReports.length }}</span>
							</button>

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
								<span class="pill-text">Peminjaman Mobil</span>
								<span class="pill-count">{{ vehicleReports.length }}</span>
							</button>

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
								<span class="pill-text">Layanan Cleaning</span>
								<span class="pill-count">{{ cleaningReports.length }}</span>
							</button>
						</div>
					</div>

					<!-- ============================================================= -->
					<!-- SUB-FILTER KHUSUS LAPORAN KENDARAAN (DRIVER, MOBIL, TANGGAL, BIDANG) -->
					<!-- ============================================================= -->
					<div v-if="currentTab === 'mobil'" class="vehicle-filter-toolbar">
						<div class="v-toolbar-header">
							<div class="v-toolbar-title-box">
								<span class="v-toolbar-badge">🚗 FILTER KENDARAAN DINAS</span>
								<h3 class="v-toolbar-heading">Penyaringan Spesifik Laporan Mobil</h3>
								<p class="v-toolbar-desc">Saring riwayat penggunaan armada mobil dinas berdasarkan pengemudi, kendaraan, tanggal kegiatan, dan bidang pemohon.</p>
							</div>

							<div class="v-toolbar-actions">
								<span class="v-result-counter">
									Menampilkan <strong>{{ filteredVehicleReports.length }}</strong> dari {{ vehicleReports.length }} Laporan
								</span>
								<button
									v-if="hasActiveVehicleFilter"
									type="button"
									class="btn-reset-vfilter"
									@click="resetVehicleFilters"
									title="Reset semua filter kendaraan"
								>
									✕ Reset Filter Kendaraan
								</button>
							</div>
						</div>

						<div class="v-toolbar-controls">
							<!-- 1. Filter Driver -->
							<div class="v-control-group">
								<label>
									<span class="v-control-label">👨‍✈️ Driver / Pengemudi</span>
									<div class="v-select-box">
										<select v-model="vehicleFilterDriver" class="v-control-select">
											<option value="all">Semua Pengemudi</option>
											<option value="unassigned">🏢 Pool (Belum Ada Sopir)</option>
											<option v-for="d in uniqueDriversList" :key="d.id || d.name" :value="d.name">
												{{ d.name }}
											</option>
										</select>
									</div>
								</label>
							</div>

							<!-- 2. Filter Mobil -->
							<div class="v-control-group">
								<label>
									<span class="v-control-label">🚗 Kendaraan / Mobil</span>
									<div class="v-select-box">
										<select v-model="vehicleFilterVehicle" class="v-control-select">
											<option value="all">Semua Mobil</option>
											<option v-for="v in uniqueVehiclesList" :key="v.id || v.license_plate" :value="v.id || v.name">
												{{ v.name }} {{ v.license_plate && v.license_plate !== '-' ? `(${v.license_plate})` : '' }}
											</option>
										</select>
									</div>
								</label>
							</div>

							<!-- 3. Filter Bidang yang Mengajukan -->
							<div class="v-control-group">
								<label>
									<span class="v-control-label">🏢 Bidang / Divisi Pemohon</span>
									<div class="v-select-box">
										<select v-model="vehicleFilterDivision" class="v-control-select">
											<option value="all">Semua Bidang / Divisi</option>
											<option v-for="div in uniqueDivisionsList" :key="div.id || div.name" :value="div.id || div.name">
												{{ div.name }}
											</option>
										</select>
									</div>
								</label>
							</div>

							<!-- 4. Filter Rentang Tanggal -->
							<div class="v-control-group v-control-group-date">
								<label>
									<span class="v-control-label">📅 Tanggal Kegiatan</span>
									<div class="v-date-range-box">
										<input
											type="date"
											v-model="vehicleFilterDateStart"
											class="v-control-date-input"
											title="Tanggal Mulai"
										/>
										<span class="v-date-sep">s/d</span>
										<input
											type="date"
											v-model="vehicleFilterDateEnd"
											class="v-control-date-input"
											title="Tanggal Selesai"
										/>
									</div>
								</label>
							</div>
						</div>
					</div>

					<!-- TABLE WRAPPER -->
					<div class="crypto-table-wrap">
						<table class="crypto-table">
							<!-- 1. ATK HEADER -->
							<thead v-if="currentTab === 'atk'">
								<tr>
									<th class="th-num">#</th>
									<th>Tanggal Pengajuan</th>
									<th>Peminjam (Nama & NIP)</th>
									<th>Divisi</th>
									<th>Daftar Barang & Jumlah</th>
									<th>Catatan Pengambilan</th>
									<th>Status Permohonan</th>
								</tr>
							</thead>

							<!-- 2. RUANGAN HEADER -->
							<thead v-else-if="currentTab === 'ruangan'">
								<tr>
									<th class="th-num">#</th>
									<th>Tanggal Permohonan</th>
									<th>Peminjam (Nama & NIP)</th>
									<th>Ruangan Dipinjam</th>
									<th>Agenda Kegiatan</th>
									<th>Waktu Pemakaian</th>
									<th>Status Booking</th>
									<th class="th-action">Aksi Persetujuan</th>
								</tr>
							</thead>

							<!-- 3. MOBIL HEADER -->
							<thead v-else-if="currentTab === 'mobil'">
								<tr>
									<th class="th-num">#</th>
									<th>Tanggal Permohonan</th>
									<th>Peminjam (Nama & NIP)</th>
									<th>Kendaraan & Plat Nomor</th>
									<th>Tujuan Kedinasan</th>
									<th>Waktu Perjalanan</th>
									<th>Penumpang</th>
									<th>Status Izin</th>
									<th class="th-action">Aksi Persetujuan</th>
								</tr>
							</thead>

							<!-- 4. CLEANING HEADER -->
							<thead v-else-if="currentTab === 'cleaning'">
								<tr>
									<th class="th-num">#</th>
									<th>Tanggal Permohonan</th>
									<th>Pemohon (Nama & NIP)</th>
									<th>Jenis Layanan</th>
									<th>Lokasi / Ruangan</th>
									<th>Alat Terkait</th>
									<th>Status Pengerjaan</th>
								</tr>
							</thead>

							<!-- TABLE BODY -->
							<tbody>
								<tr v-if="isLoading">
									<td :colspan="8" class="empty-cell">
										<div class="loading-state">
											<span class="spinner"></span>
											<p>Menghubungkan ke database dan memuat data laporan...</p>
										</div>
									</td>
								</tr>

								<!-- EMPTY STATE: DATA KOSONG KARENA BELUM ADA AKTIVITAS USER -->
								<tr v-else-if="currentFilteredItems.length === 0">
									<td :colspan="8" class="empty-cell">
										<div class="empty-state">
											<span class="empty-icon-box">
												<svg viewBox="0 0 24 24" class="svg-icon-empty" fill="currentColor">
													<path d="M10 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2h-8l-2-2z"/>
												</svg>
											</span>
											<h4>Belum Ada Aktivitas Pengguna</h4>
											<p>
												Database telah terhubung normal (<span class="text-emerald">Live DB Connected</span>). Belum ada data aktivitas peminjaman atau permohonan layanan yang diajukan oleh pengguna untuk kategori <b>{{ activeTabTitle }}</b> pada rentang tanggal ini.
											</p>
											<span class="empty-hint">
												Setiap kali ada pengguna yang meminjam atau meminta layanan melalui portal user, laporannya akan langsung tercatat dan tampil di tabel ini secara otomatis.
											</span>
										</div>
									</td>
								</tr>

								<!-- ROWS 1: ATK -->
								<template v-else-if="currentTab === 'atk'">
									<tr v-for="(item, idx) in currentFilteredItems" :key="item.id" class="table-row">
										<td class="td-num">{{ idx + 1 }}</td>
										<td><span class="font-mono text-muted">{{ item.date }}</span></td>
										<td>
											<strong class="text-user">{{ item.user_name }}</strong>
											<small class="block text-muted">NIP: {{ item.user_nip }}</small>
										</td>
										<td><span class="badge-subtle">{{ item.division }}</span></td>
										<td>
											<div class="report-asset-cell">
												<img
													:src="item.product_image || '/images/atk/default-atk.svg'"
													alt="ATK"
													class="report-asset-thumb"
													loading="lazy"
													@error="handleImgError($event, '/images/atk/default-atk.svg')"
												/>
												<span class="text-highlight">{{ item.items_summary || '-' }}</span>
											</div>
										</td>
										<td><span class="text-note">{{ item.request_note || '-' }}</span></td>
										<td>
											<span class="status-badge" :class="getStatusBadgeClass(item.status)">
												<i class="status-dot"></i> {{ formatStatus(item.status) }}
											</span>
										</td>
									</tr>
								</template>

								<!-- ROWS 2: RUANGAN -->
								<template v-else-if="currentTab === 'ruangan'">
									<tr v-for="(item, idx) in currentFilteredItems" :key="item.id" class="table-row">
										<td class="td-num">{{ idx + 1 }}</td>
										<td><span class="font-mono text-muted">{{ item.date }}</span></td>
										<td>
											<strong class="text-user">{{ item.user_name }}</strong>
											<small class="block text-muted">NIP: {{ item.user_nip }}</small>
											<span class="badge-subtle text-xs mt-1 inline-block">{{ item.division }}</span>
										</td>
										<td>
											<div class="report-asset-cell">
												<img
													:src="item.room_image || '/images/rooms/default-room.svg'"
													alt="Ruangan"
													class="report-asset-thumb"
													loading="lazy"
													@error="handleImgError($event, '/images/rooms/default-room.svg')"
												/>
												<div>
													<strong class="text-cyan">{{ item.room_name }}</strong>
													<small class="block font-mono text-muted">{{ item.room_code }}</small>
												</div>
											</div>
										</td>
										<td><span class="text-highlight">{{ item.agenda }}</span></td>
										<td><span class="font-mono text-xs">{{ item.start_time }} s/d {{ item.end_time }}</span></td>
										<td>
											<span class="status-badge" :class="getStatusBadgeClass(item.status)">
												<i class="status-dot"></i> {{ formatStatus(item.status) }}
											</span>
										</td>
										<td class="td-action text-center">
											<div v-if="item.status === 'pending'" class="action-buttons-wrap">
												<button
													type="button"
													@click="updateBookingApproval(item, 'ruangan', 'approved')"
													:disabled="actionLoading[item.id]"
													class="btn-approve-action"
													title="Setujui Peminjaman Ruangan"
												>
													<span v-if="actionLoading[item.id]" class="spinner-sm"></span>
													<span v-else>✓ Setuju</span>
												</button>
												<button
													type="button"
													@click="updateBookingApproval(item, 'ruangan', 'rejected')"
													:disabled="actionLoading[item.id]"
													class="btn-reject-action"
													title="Tolak Peminjaman Ruangan"
												>
													✕ Tolak
												</button>
											</div>
											<span v-else-if="item.status === 'approved'" class="badge-status-approved">
												✓ Disetujui
											</span>
											<span v-else-if="item.status === 'rejected'" class="badge-status-rejected">
												✕ Ditolak
											</span>
											<span v-else class="text-xs text-muted">
												Selesai
											</span>
										</td>
									</tr>
								</template>

								<!-- ROWS 3: MOBIL -->
								<template v-else-if="currentTab === 'mobil'">
									<tr v-for="(item, idx) in currentFilteredItems" :key="item.id" class="table-row">
										<td class="td-num">{{ idx + 1 }}</td>
										<td><span class="font-mono text-muted">{{ item.date }}</span></td>
										<td>
											<strong class="text-user">{{ item.borrower_name || (item.user_name !== 'admin' ? item.user_name : (item.passengers || 'Pegawai Pemohon')) }}</strong>
											<small class="block text-muted">NIP: {{ item.borrower_nip || (item.user_nip !== '-' ? item.user_nip : '-') }}</small>
											<span class="badge-subtle text-xs mt-1 inline-block">{{ item.borrower_division || item.division }}</span>
										</td>
										<td>
											<div class="report-asset-cell">
												<img
													:src="item.vehicle_image || '/images/vehicles/default-vehicle.svg'"
													alt="Mobil"
													class="report-asset-thumb"
													loading="lazy"
													@error="handleImgError($event, '/images/vehicles/default-vehicle.svg')"
												/>
												<div>
													<strong class="text-amber">{{ item.vehicle_name }}</strong>
													<small class="block font-mono text-muted">{{ item.license_plate }}</small>
													<small v-if="item.driver_name" class="block text-[10px] text-cyan-300">Pengemudi: {{ item.driver_name }}</small>
												</div>
											</div>
										</td>
										<td>
											<div class="space-y-1">
												<span class="text-highlight block font-semibold">{{ item.destination }}</span>
												<span v-if="item.is_routine || item.booking_type === 'routine'" class="inline-block text-[10px] text-cyan-400 font-bold px-2 py-0.5 rounded bg-cyan-950/50 border border-cyan-500/30">
													🔄 Rutin: {{ item.routine_schedule }}
												</span>
												<small v-if="item.purpose" class="block text-muted text-[10px]">
													🎯 Keperluan: {{ item.purpose }}
												</small>
											</div>
										</td>
										<td><span class="font-mono text-xs">{{ item.start_date }} s/d {{ item.end_date }}</span></td>
										<td><span class="badge-subtle">{{ item.passengers_count }} Orang</span></td>
										<td>
											<span class="status-badge" :class="getStatusBadgeClass(item.status)">
												<i class="status-dot"></i> {{ formatStatus(item.status) }}
											</span>
										</td>
										<td class="td-action text-center">
											<div v-if="item.status === 'pending'" class="action-buttons-wrap">
												<button
													type="button"
													@click="openVehicleApprovalModal(item)"
													:disabled="actionLoading[item.id]"
													class="btn-approve-action"
													title="Pilih Mobil & Sopir untuk Disetujui"
												>
													<span v-if="actionLoading[item.id]" class="spinner-sm"></span>
													<span v-else>✓ Pilih & Setujui</span>
												</button>
												<button
													type="button"
													@click="updateBookingApproval(item, 'mobil', 'rejected')"
													:disabled="actionLoading[item.id]"
													class="btn-reject-action"
													title="Tolak Peminjaman Mobil"
												>
													✕ Tolak
												</button>
											</div>
											<span v-else-if="item.status === 'approved'" class="badge-status-approved" :title="'Pengemudi: ' + (item.driver_name || 'Standby')">
												✓ Disetujui ({{ item.driver_name || 'Pengemudi' }})
											</span>
											<span v-else-if="item.status === 'rejected'" class="badge-status-rejected">
												✕ Ditolak
											</span>
											<span v-else-if="item.status === 'in_progress'" class="status-badge status-blue">
												Sedang Jalan
											</span>
											<span v-else class="text-xs text-muted">
												Selesai
											</span>
										</td>
									</tr>
								</template>

								<!-- ROWS 4: CLEANING -->
								<template v-else-if="currentTab === 'cleaning'">
									<tr v-for="(item, idx) in currentFilteredItems" :key="item.id" class="table-row">
										<td class="td-num">{{ idx + 1 }}</td>
										<td><span class="font-mono text-muted">{{ item.date }}</span></td>
										<td>
											<strong class="text-user">{{ item.user_name }}</strong>
											<small class="block text-muted">NIP: {{ item.user_nip }}</small>
											<span class="badge-subtle text-xs mt-1 inline-block">{{ item.division }}</span>
										</td>
										<td><strong class="text-purple">{{ item.service_type }}</strong></td>
										<td><span class="text-highlight">📍 {{ item.location }}</span></td>
										<td>
											<div class="report-asset-cell">
												<img
													:src="item.tool_image || '/images/cleaning/default-cleaning.svg'"
													alt="Alat"
													class="report-asset-thumb"
													loading="lazy"
													@error="handleImgError($event, '/images/cleaning/default-cleaning.svg')"
												/>
												<span class="badge-subtle">{{ item.tool_name }}</span>
											</div>
										</td>
										<td>
											<span class="status-badge" :class="getStatusBadgeClass(item.status)">
												<i class="status-dot"></i> {{ formatStatus(item.status) }}
											</span>
										</td>
									</tr>
								</template>
							</tbody>
						</table>
					</div>

					<!-- FOOTER -->
					<div class="crypto-footer">
						<div class="footer-info">
							Total Data Laporan {{ activeTabTitle }}: <b>{{ currentFilteredItems.length }}</b> entri
						</div>
						<div class="footer-db-status">
							<span class="status-dot-green"></span> Database Server Aktif & Tersinkronisasi
						</div>
					</div>
				</section>

				<!-- MODAL APPROVAL PENUGASAN MOBIL & SOPIR OLEH ADMIN -->
				<div
					v-if="isVehicleApprovalModalOpen && selectedVehicleBooking"
					class="admin-modal-backdrop"
					@click.self="isVehicleApprovalModalOpen = false"
				>
					<div class="admin-modal-card">
						<div class="admin-modal-header">
							<div class="flex items-center gap-3">
								<div class="w-10 h-10 rounded-xl bg-amber-500/20 text-amber-400 border border-amber-500/30 flex items-center justify-center text-xl shrink-0">
									🚗
								</div>
								<div>
									<h3 class="text-base font-bold text-white flex items-center gap-2">
										Persetujuan & Penugasan Mobil Dinas
									</h3>
									<span
										class="inline-block text-[10px] font-bold px-2 py-0.5 rounded-full border mt-0.5"
										:class="selectedVehicleBooking.is_routine || selectedVehicleBooking.booking_type === 'routine' ? 'bg-cyan-500/20 text-cyan-300 border-cyan-500/30' : 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30'"
									>
										{{ (selectedVehicleBooking.is_routine || selectedVehicleBooking.booking_type === 'routine') ? '🔄 Kunjungan Rutin Terjadwal' : '🚗 Kunjungan Sekali Jalan' }}
									</span>
								</div>
							</div>
							<button
								type="button"
								@click="isVehicleApprovalModalOpen = false"
								class="btn-close-modal"
							>
								✕
							</button>
						</div>

						<!-- Ringkasan Permohonan Pegawai -->
						<div class="modal-summary-box">
							<div class="grid grid-cols-2 gap-2 text-xs">
								<div>
									<span class="text-muted block text-[11px]">👤 Pemohon:</span>
									<strong class="text-white">{{ selectedVehicleBooking.borrower_name || (selectedVehicleBooking.user_name !== 'admin' ? selectedVehicleBooking.user_name : (selectedVehicleBooking.passengers || 'Pegawai Pemohon')) }}</strong>
									<small class="block text-muted text-[10px]">{{ selectedVehicleBooking.borrower_division || selectedVehicleBooking.division }} (NIP: {{ selectedVehicleBooking.borrower_nip || selectedVehicleBooking.user_nip }})</small>
								</div>
								<div>
									<span class="text-muted block text-[11px]">👥 Penumpang:</span>
									<strong class="text-emerald-400">{{ selectedVehicleBooking.passengers_count }} Orang</strong>
									<small class="block text-muted text-[10px] truncate" :title="selectedVehicleBooking.passengers">{{ selectedVehicleBooking.passengers || '-' }}</small>
								</div>
							</div>

							<div class="pt-2 border-t border-slate-800 text-xs">
								<span class="text-muted block text-[11px]">📍 Rute Tujuan:</span>
								<strong class="text-amber-300 block leading-snug">{{ selectedVehicleBooking.destination }}</strong>
							</div>

							<div v-if="selectedVehicleBooking.is_routine || selectedVehicleBooking.routine_schedule" class="pt-2 border-t border-slate-800 text-xs">
								<span class="text-cyan-400 block text-[11px] font-semibold">🗓️ Pola Jadwal Rutin:</span>
								<span class="text-cyan-300 font-medium leading-snug">{{ selectedVehicleBooking.routine_schedule }}</span>
							</div>

							<div v-if="selectedVehicleBooking.purpose" class="pt-2 border-t border-slate-800 text-xs">
								<span class="text-muted block text-[11px]">🎯 Keperluan Dinas:</span>
								<span class="text-slate-200 leading-snug">{{ selectedVehicleBooking.purpose }}</span>
							</div>
						</div>

						<!-- Formulir Pemilihan Mobil & Sopir -->
						<form @submit.prevent="submitVehicleApproval" class="space-y-4 pt-1 text-xs">
							<!-- 1. Pilihan Mobil -->
							<div>
								<label class="block font-bold text-white mb-1.5 flex items-center justify-between">
									<span>🚗 Pilih Unit Mobil Dinas (Armada):</span>
									<span class="text-emerald-400 font-mono text-[10px]">{{ vehiclesList.length }} Unit Terdaftar</span>
								</label>
								<select
									v-model="approvalVehicleForm.vehicle_id"
									@change="onVehicleSelectionChange"
									required
									class="admin-modal-select w-full p-2.5 rounded-xl border focus:outline-none"
								>
									<option :value="null" disabled>-- Pilih Armada Mobil Dinas --</option>
									<option
										v-for="v in vehiclesList"
										:key="v.id"
										:value="v.id"
									>
										{{ v.name }} • [{{ v.license_plate }}] • (Kapasitas: {{ v.capacity || 7 }} Org) • Status: {{ v.status || 'Tersedia' }}
									</option>
								</select>
								<small class="text-muted text-[10px] block mt-1">Pilih mobil yang sesuai dengan kapasitas jumlah penumpang.</small>
							</div>

							<!-- 2. Pilihan Sopir -->
							<div>
								<label class="block font-bold text-white mb-1.5 flex items-center justify-between">
									<span>👨‍✈️ Pilih Pengemudi / Sopir yang Ditugaskan:</span>
									<span class="text-cyan-400 font-mono text-[10px]">{{ driversList.length }} Sopir Aktif</span>
								</label>
								<select
									v-model="approvalVehicleForm.driver_id"
									@change="onDriverSelectionChange"
									required
									class="admin-modal-select w-full p-2.5 rounded-xl border focus:outline-none"
								>
									<option :value="null" disabled>-- Pilih Pengemudi Dinas --</option>
									<option
										v-for="d in driversList"
										:key="d.id"
										:value="d.id"
									>
										👤 {{ d.name }} (NIP: {{ d.nip }})
									</option>
								</select>
								<small class="text-muted text-[10px] block mt-1">Notifikasi tugas akan langsung masuk ke akun sopir yang dipilih.</small>
							</div>

							<!-- 3. Catatan / Arahan Admin -->
							<div>
								<label class="block font-bold text-white mb-1">💬 Catatan / Arahan untuk Sopir & Pemohon (Opsional):</label>
								<input
									v-model="approvalVehicleForm.notes"
									type="text"
									placeholder="Contoh: Standby di depan lobi kantor 15 menit sebelum keberangkatan..."
									class="admin-modal-input w-full p-2.5 rounded-xl border focus:outline-none"
								/>
							</div>

							<!-- Tombol Aksi Modal -->
							<div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-800">
								<button
									type="button"
									@click="isVehicleApprovalModalOpen = false"
									class="btn-modal-back px-4 py-2.5 rounded-xl font-semibold text-xs transition"
								>
									Batal
								</button>
								<button
									type="submit"
									:disabled="actionLoading[selectedVehicleBooking?.id]"
									class="btn-modal-confirm-approve px-5 py-2.5 rounded-xl font-bold text-xs flex items-center gap-2 shadow-lg transition"
								>
									<span v-if="actionLoading[selectedVehicleBooking?.id]" class="spinner-sm"></span>
									<span v-else>✓</span>
									<span>{{ actionLoading[selectedVehicleBooking?.id] ? 'Memproses...' : 'Setujui & Tugaskan Armada' }}</span>
								</button>
							</div>
						</form>
					</div>
				</div>

				<!-- POPUP MODAL PERINGATAN JADWAL BENTROK (PILIHAN: UBAH ATAU BATAL) -->
				<div
					v-if="scheduleConflictModal.show"
					class="admin-modal-backdrop"
					style="z-index: 9999;"
					@click.self="closeConflictModal('change')"
				>
					<div class="conflict-warning-card">
						<div class="conflict-card-header">
							<div class="conflict-icon-pulse">
								<span class="text-2xl">⚠️</span>
							</div>
							<div class="flex-1">
								<div class="flex items-center gap-2 flex-wrap">
									<span class="conflict-tag-badge">BENTROK WAKTU TERDETEKSI</span>
									<span class="text-[10px] text-amber-300 font-bold font-mono">JAM BERANGKAT &lt; ESTIMASI PULANG</span>
								</div>
								<h3 class="text-base font-bold text-white mt-1">
									{{ scheduleConflictModal.conflictReasonTitle || 'Armada / Pengemudi Sedang Di Jalan & Belum Pulang!' }}
								</h3>
								<p class="text-[11px] text-slate-300 mt-0.5">
									Sistem membaca kendaraan atau pengemudi yang dipilih masih dalam jadwal pemakaian oleh pegawai lain.
								</p>
							</div>
							<button type="button" @click="closeConflictModal('cancel')" class="btn-close-modal" title="Batal">✕</button>
						</div>

						<div class="conflict-card-body space-y-3 p-5">
							<!-- Banner Rincian Konflik -->
							<div class="conflict-comparison-grid">
								<!-- Kolom 1: Pemakaian yang Sedang Berjalan -->
								<div class="conflict-col conflict-col-active">
									<div class="col-title text-red-400 font-bold flex items-center gap-1.5 text-xs">
										<span>🚗</span>
										<span>Sedang Dipakai Pegawai Lain:</span>
									</div>
									<div class="mt-2 space-y-1 text-xs">
										<div class="font-bold text-white text-sm">
											{{ scheduleConflictModal.otherUserName }}
										</div>
										<div class="text-[11px] text-slate-300">
											Divisi: <b>{{ scheduleConflictModal.otherDivision }}</b>
										</div>
										<div class="text-[11px] text-amber-200 truncate" :title="scheduleConflictModal.otherDestination">
											📍 Rute: {{ scheduleConflictModal.otherDestination }}
										</div>
										<div class="mt-2.5 p-2 rounded-lg bg-red-950/40 border border-red-500/30">
											<span class="text-[10px] text-red-300 block font-semibold uppercase">Estimasi Pulang Tiba di Kantor:</span>
											<strong class="text-xs sm:text-sm text-red-400 font-mono font-bold block mt-0.5">
												⏰ {{ scheduleConflictModal.otherReturnTimeFormatted }} WIB
											</strong>
										</div>
									</div>
								</div>

								<!-- Kolom 2: Permohonan Baru yang Akan Disetujui -->
								<div class="conflict-col conflict-col-new">
									<div class="col-title text-amber-400 font-bold flex items-center gap-1.5 text-xs">
										<span>📋</span>
										<span>Booking Baru yang Ingin Disetujui:</span>
									</div>
									<div class="mt-2 space-y-1 text-xs">
										<div class="font-bold text-white text-sm">
											{{ scheduleConflictModal.newUserName }}
										</div>
										<div class="text-[11px] text-slate-300">
											Divisi: <b>{{ scheduleConflictModal.newDivision }}</b>
										</div>
										<div class="text-[11px] text-emerald-300 truncate" :title="scheduleConflictModal.newDestination">
											📍 Rute: {{ scheduleConflictModal.newDestination }}
										</div>
										<div class="mt-2.5 p-2 rounded-lg bg-amber-950/40 border border-amber-500/30">
											<span class="text-[10px] text-amber-300 block font-semibold uppercase">Jam Berangkat Booking Baru:</span>
											<strong class="text-xs sm:text-sm text-amber-300 font-mono font-bold block mt-0.5">
												⏰ {{ scheduleConflictModal.newStartTimeFormatted }} WIB
											</strong>
										</div>
									</div>
								</div>
							</div>

							<!-- Box Peringatan Bentrok Waktu -->
							<div class="p-3 rounded-xl bg-amber-500/10 border border-amber-500/30 flex items-start gap-2.5">
								<span class="text-lg shrink-0">⚠️</span>
								<div class="text-xs leading-relaxed text-amber-200">
									<p class="font-semibold text-white">
										{{ scheduleConflictModal.conflictMessage }}
									</p>
									<p class="text-[11px] text-slate-300 mt-1">
										Jam berangkat booking baru (<b class="text-amber-300">{{ scheduleConflictModal.newStartTimeFormatted }} WIB</b>) lebih awal dari jam estimasi kepulangan mobil/sopir dari perjalanan dinas sebelumnya (<b class="text-red-400">{{ scheduleConflictModal.otherReturnTimeFormatted }} WIB</b>).
									</p>
								</div>
							</div>
						</div>

						<!-- Footer dengan Pilihan: UBAH ATAU BATAL Sesuai Permintaan -->
						<div class="conflict-card-footer flex items-center justify-between p-4 bg-slate-950/60 border-t border-slate-800">
							<span class="text-[11px] text-slate-400 hidden sm:inline">Silakan pilih tindakan:</span>
							<div class="flex items-center gap-3 w-full sm:w-auto justify-end">
								<!-- Pilihan 1: BATAL -->
								<button
									type="button"
									@click="closeConflictModal('cancel')"
									class="btn-conflict-cancel px-4 py-2.5 rounded-xl font-bold text-xs flex items-center gap-1.5 transition cursor-pointer"
								>
									<span>✕</span>
									<span>Batal</span>
								</button>

								<!-- Pilihan 2: UBAH -->
								<button
									type="button"
									@click="closeConflictModal('change')"
									class="btn-conflict-change px-5 py-2.5 rounded-xl font-bold text-xs flex items-center gap-1.5 shadow-lg transition cursor-pointer"
								>
									<span>🔄</span>
									<span>Ubah Pilihan (Armada / Sopir Lain)</span>
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</main>
	</div>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue'

useHead({
	title: 'Laporan Aktivitas & Diagram Bulanan - SIMPEL'
})

const router = useRouter()
const config = useRuntimeConfig()
const apiBase = config.public?.apiBase || 'http://127.0.0.1:8000/api'

// Theme & UI state
const isDark = ref(true)
const menuOpen = ref(false)
const searchQuery = ref('')
const currentTab = ref('atk') // 'atk' | 'ruangan' | 'mobil' | 'cleaning'
const isLoading = ref(false)
const actionLoading = ref({})
const approvalAlert = ref(null)

// State Approval Peminjaman Mobil (Pilih Armada & Sopir)
const isVehicleApprovalModalOpen = ref(false)
const selectedVehicleBooking = ref(null)
const vehiclesList = ref([])
const driversList = ref([])
const approvalVehicleForm = ref({
	vehicle_id: null,
	driver_id: null,
	driver_name: '',
	notes: ''
})

// State Popup Modal Peringatan Jadwal Bentrok (Pilihan: Ubah atau Batal)
const scheduleConflictModal = ref({
	show: false,
	conflictReasonTitle: '',
	conflictMessage: '',
	otherUserName: '',
	otherDivision: '',
	otherDestination: '',
	otherReturnTimeFormatted: '',
	newUserName: '',
	newDivision: '',
	newDestination: '',
	newStartTimeFormatted: ''
})

const closeConflictModal = (action = 'change') => {
	scheduleConflictModal.value.show = false
	if (action === 'cancel') {
		// Batalkan proses persetujuan dan tutup modal penugasan
		isVehicleApprovalModalOpen.value = false
	}
	// Jika action === 'change', modal approval tetap terbuka agar admin dapat mengubah pilihan armada/sopir
}

// Division Filter State
const divisionsList = ref([])
const selectedDivision = ref('all') // 'all' | division id

// Year & Month Filter State
const currentYearNum = new Date().getFullYear()
const availableYears = ref([currentYearNum, currentYearNum - 1, currentYearNum - 2, currentYearNum - 3])
const selectedYear = ref(currentYearNum)
const selectedMonth = ref('all') // 'all' | 1..12

// Date Range Filter State
const startDate = ref('')
const endDate = ref('')
const selectedPreset = ref('all')

// Dedicated ATK Dual Chart State (Date Filter & Tier Selectors)
const atkChartStartDate = ref('')
const atkChartEndDate = ref('')
const topTakenTier = ref('1-10') // '1-10' | '11-20' | '21-30' | '31-40' | '41-50' | 'all'
const lowestStockTier = ref('1-10') // '1-10' | '11-20' | '21-30' | '31-40' | '41-50' | 'all'
const atkLeaderboardViewMode = ref('both') // 'both' | 'taken' | 'stock'
const atkProducts = ref([])

// Preset Handlers for ATK Dual Chart Date Filter
const isAtkPresetActive = (preset) => {
	const now = new Date()
	const todayStr = now.toISOString().split('T')[0]
	if (preset === 'all') return !atkChartStartDate.value && !atkChartEndDate.value
	if (preset === 'month') {
		const firstDayStr = new Date(now.getFullYear(), now.getMonth(), 1).toISOString().split('T')[0]
		return atkChartStartDate.value === firstDayStr && atkChartEndDate.value === todayStr
	}
	if (preset === '30days') {
		const past = new Date(now)
		past.setDate(past.getDate() - 30)
		return atkChartStartDate.value === past.toISOString().split('T')[0] && atkChartEndDate.value === todayStr
	}
	if (preset === '7days') {
		const past = new Date(now)
		past.setDate(past.getDate() - 7)
		return atkChartStartDate.value === past.toISOString().split('T')[0] && atkChartEndDate.value === todayStr
	}
	return false
}

const applyAtkPreset = (preset) => {
	const now = new Date()
	const todayStr = now.toISOString().split('T')[0]
	if (preset === 'all') {
		atkChartStartDate.value = ''
		atkChartEndDate.value = ''
	} else if (preset === 'month') {
		atkChartStartDate.value = new Date(now.getFullYear(), now.getMonth(), 1).toISOString().split('T')[0]
		atkChartEndDate.value = todayStr
	} else if (preset === '30days') {
		const past = new Date(now)
		past.setDate(past.getDate() - 30)
		atkChartStartDate.value = past.toISOString().split('T')[0]
		atkChartEndDate.value = todayStr
	} else if (preset === '7days') {
		const past = new Date(now)
		past.setDate(past.getDate() - 7)
		atkChartStartDate.value = past.toISOString().split('T')[0]
		atkChartEndDate.value = todayStr
	}
}

// Cute item emoji detector
const getItemCuteIcon = (name) => {
	const n = (name || '').toLowerCase()
	if (n.includes('kertas') || n.includes('hvs') || n.includes('paper') || n.includes('buku') || n.includes('notebook')) return '📄'
	if (n.includes('pulpen') || n.includes('pen') || n.includes('pensil') || n.includes('spidol') || n.includes('marker') || n.includes('stabilo')) return '✏️'
	if (n.includes('gunting') || n.includes('cutter') || n.includes('pisau')) return '✂️'
	if (n.includes('stapler') || n.includes('klip') || n.includes('clip') || n.includes('binder') || n.includes('hecter')) return '📎'
	if (n.includes('map') || n.includes('folder') || n.includes('amplop') || n.includes('ordner')) return '📁'
	if (n.includes('lakban') || n.includes('solasi') || n.includes('tape') || n.includes('lem') || n.includes('glue')) return '🩹'
	if (n.includes('tinta') || n.includes('toner') || n.includes('catridge')) return '🖨️'
	if (n.includes('penghapus') || n.includes('tipex') || n.includes('correction')) return '🧼'
	if (n.includes('penggaris') || n.includes('ruler')) return '📏'
	if (n.includes('baterai') || n.includes('battery')) return '🔋'
	return '📦'
}

// 1. Filter borrowings based on date filter and division
const atkBorrowingsForChart = computed(() => {
	return atkReports.value.filter(b => {
		// Division filter
		if (selectedDivision.value !== 'all') {
			const targetDivId = String(selectedDivision.value)
			const itemDivId = b.division_id ? String(b.division_id) : ''
			const targetDivName = getSelectedDivisionName().toLowerCase()
			const itemDivName = (b.division || '').toLowerCase()
			if (itemDivId !== targetDivId && itemDivName !== targetDivName) return false
		}
		// Date range filter
		const bDate = b.date ? b.date.substring(0, 10) : ''
		if (atkChartStartDate.value && bDate && bDate < atkChartStartDate.value) return false
		if (atkChartEndDate.value && bDate && bDate > atkChartEndDate.value) return false
		return true
	})
})

// 2. Full sorted list for Most Taken
const atkMostTakenFullList = computed(() => {
	const map = new Map()

	// Initialize all known catalog products
	atkProducts.value.forEach(p => {
		map.set(p.name, {
			id: p.id,
			name: p.name,
			unit: p.unit || 'Pcs',
			image: p.image || '/images/atk/default-atk.svg',
			stock: Number(p.stock || 0),
			totalQty: 0,
			borrowCount: 0,
		})
	})

	// Accumulate borrowed quantities
	atkBorrowingsForChart.value.forEach(b => {
		if (Array.isArray(b.items) && b.items.length > 0) {
			b.items.forEach(it => {
				const pName = it.product_name || 'Barang ATK'
				if (!map.has(pName)) {
					map.set(pName, {
						id: it.product_id,
						name: pName,
						unit: it.unit || 'Pcs',
						image: it.image || '/images/atk/default-atk.svg',
						stock: Number(it.stock || 0),
						totalQty: 0,
						borrowCount: 0,
					})
				}
				const entry = map.get(pName)
				entry.totalQty += Number(it.quantity || 0)
				entry.borrowCount += 1
			})
		} else if (b.items_summary) {
			const matches = b.items_summary.matchAll(/(.+?)\s*\((\d+)\s*([^)]*)\)/g)
			for (const m of matches) {
				const pName = m[1].trim()
				const q = Number(m[2]) || 0
				const u = m[3].trim() || 'Pcs'
				if (!map.has(pName)) {
					map.set(pName, {
						id: null,
						name: pName,
						unit: u,
						image: '/images/atk/default-atk.svg',
						stock: 0,
						totalQty: 0,
						borrowCount: 0,
					})
				}
				const entry = map.get(pName)
				entry.totalQty += q
				entry.borrowCount += 1
			}
		}
	})

	const arr = Array.from(map.values())
	arr.sort((a, b) => {
		if (b.totalQty !== a.totalQty) return b.totalQty - a.totalQty
		if (b.borrowCount !== a.borrowCount) return b.borrowCount - a.borrowCount
		return a.name.localeCompare(b.name)
	})

	const maxQty = Math.max(...arr.map(i => i.totalQty), 1)

	return arr.map((item, idx) => ({
		...item,
		rank: idx + 1,
		percentOfMax: item.totalQty > 0 ? Math.max(8, Math.round((item.totalQty / maxQty) * 100)) : 3,
	}))
})

// Paged list for Most Taken
const atkMostTakenPagedList = computed(() => {
	const full = atkMostTakenFullList.value
	if (topTakenTier.value === 'all') return full
	const [sStr, eStr] = topTakenTier.value.split('-')
	const start = (Number(sStr) || 1) - 1
	const end = Number(eStr) || 10
	return full.slice(start, end)
})

// 3. Full sorted list for Lowest Stock
const atkLowestStockFullList = computed(() => {
	let list = [...atkProducts.value]
	if (list.length === 0) {
		list = atkMostTakenFullList.value.map(i => ({
			id: i.id,
			name: i.name,
			stock: i.stock,
			unit: i.unit,
			image: i.image,
		}))
	}

	list.sort((a, b) => {
		const sA = Number(a.stock || 0)
		const sB = Number(b.stock || 0)
		if (sA !== sB) return sA - sB
		return (a.name || '').localeCompare(b.name || '')
	})

	const maxStock = Math.max(...list.map(i => Number(i.stock || 0)), 20)

	return list.map((item, idx) => {
		const stockNum = Number(item.stock || 0)
		return {
			...item,
			stock: stockNum,
			rank: idx + 1,
			percentOfMax: stockNum > 0 ? Math.max(6, Math.min(100, Math.round((stockNum / maxStock) * 100))) : 3,
		}
	})
})

// Paged list for Lowest Stock
const atkLowestStockPagedList = computed(() => {
	const full = atkLowestStockFullList.value
	if (lowestStockTier.value === 'all') return full
	const [sStr, eStr] = lowestStockTier.value.split('-')
	const start = (Number(sStr) || 1) - 1
	const end = Number(eStr) || 10
	return full.slice(start, end)
})

// Leaderboard Top 3 & Rest List Computed Properties
const atkTakenTop3 = computed(() => {
	const list = atkMostTakenFullList.value
	return {
		first: list[0] || null,
		second: list[1] || null,
		third: list[2] || null,
	}
})

const atkTakenRestList = computed(() => {
	const list = atkMostTakenPagedList.value
	if (topTakenTier.value === '1-10' || topTakenTier.value === 'all') {
		return list.slice(3)
	}
	return list
})

const atkStockTop3 = computed(() => {
	const list = atkLowestStockFullList.value
	return {
		first: list[0] || null,
		second: list[1] || null,
		third: list[2] || null,
	}
})

const atkStockRestList = computed(() => {
	const list = atkLowestStockPagedList.value
	if (lowestStockTier.value === '1-10' || lowestStockTier.value === 'all') {
		return list.slice(3)
	}
	return list
})

// Summary metrics for ATK Dual Chart
const totalTakenQuantitySum = computed(() => {
	return atkMostTakenFullList.value.reduce((acc, it) => acc + it.totalQty, 0)
})

const totalTakenBorrowingsCount = computed(() => {
	return atkBorrowingsForChart.value.length
})

const outOfStockCount = computed(() => {
	return atkLowestStockFullList.value.filter(it => it.stock === 0).length
})

const criticalStockCount = computed(() => {
	return atkLowestStockFullList.value.filter(it => it.stock > 0 && it.stock <= 5).length
})

// Visual badge & color helpers
const maxTakenQtyInView = computed(() => {
	const list = atkMostTakenPagedList.value
	if (!list.length) return 10
	const max = Math.max(...list.map(i => Number(i.totalQty) || 0))
	return max > 0 ? max : 10
})

const maxStockInView = computed(() => {
	const list = atkLowestStockPagedList.value
	if (!list.length) return 10
	const max = Math.max(...list.map(i => Number(i.stock) || 0))
	return max > 0 ? max : 10
})

const getTakenBarHeightPercent = (qty) => {
	const max = maxTakenQtyInView.value
	const val = Number(qty) || 0
	if (val <= 0) return 4
	return Math.max(8, Math.min(100, Math.round((val / max) * 100)))
}

const getStockBarHeightPercent = (stock) => {
	const max = maxStockInView.value
	const val = Number(stock) || 0
	if (val <= 0) return 6
	return Math.max(8, Math.min(100, Math.round((val / max) * 100)))
}

const getStockPillarClass = (stock) => {
	const s = Number(stock) || 0
	if (s === 0) return 'pillar-danger'
	if (s <= 5) return 'pillar-warning'
	if (s <= 15) return 'pillar-amber'
	return 'pillar-safe'
}

const getStockCapClass = (stock) => {
	const s = Number(stock) || 0
	if (s === 0) return 'cap-danger'
	if (s <= 5) return 'cap-warning'
	if (s <= 15) return 'cap-amber'
	return 'cap-safe'
}

const getRankBadgeClass = (rank) => {
	if (rank === 1) return 'rank-gold'
	if (rank === 2) return 'rank-silver'
	if (rank === 3) return 'rank-bronze'
	return 'rank-default'
}

const getStockRankBadgeClass = (stock, rank) => {
	if (stock === 0) return 'rank-danger-pulse'
	if (stock <= 5) return 'rank-warning'
	if (rank <= 3) return 'rank-amber'
	return 'rank-default'
}

const getStockStatusText = (stock) => {
	if (stock === 0) return '🚨 Habis (0)'
	if (stock <= 5) return '⚠️ Kritis (<=5)'
	if (stock <= 15) return '🟡 Menipis'
	return '🟢 Aman'
}

const getStockPillClass = (stock) => {
	if (stock === 0) return 'pill-danger'
	if (stock <= 5) return 'pill-warning'
	if (stock <= 15) return 'pill-amber'
	return 'pill-safe'
}

const getStockBarFillClass = (stock) => {
	if (stock === 0) return 'fill-danger'
	if (stock <= 5) return 'fill-warning'
	if (stock <= 15) return 'fill-amber'
	return 'fill-safe'
}

// Chart Interactive State
const chartMode = ref('all') // 'all' | 'tab'
const hoveredMonth = ref(null)
const tooltipPos = ref({ x: 0, y: 0 })
const chartBodyRef = ref(null)

const monthNames = [
	'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
	'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
]
const shortMonthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des']

// Sidebar menus
const menus = ref([
	{
		name: 'Manajemen Alat',
		active: false,
		iconSvg: '<svg viewBox="0 0 24 24" class="svg-icon-nav" fill="currentColor"><path d="M4 8h4V4H4v4zm6 12h4v-4h-4v4zm-6 0h4v-4H4v4zm0-6h4v-4H4v4zm6 0h4v-4h-4v4zm6-10v4h4V4h-4zm-6 4h4V4h-4v4zm6 6h4v-4h-4v4zm0 6h4v-4h-4v4z"/></svg>'
	},
	{
		name: 'Laporan',
		active: true,
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

// Report Collections from Database
const atkReports = ref([])
const roomReports = ref([])
const vehicleReports = ref([])
const cleaningReports = ref([])

const activeTabTitle = computed(() => {
	switch (currentTab.value) {
		case 'atk': return 'Peminjaman Alat Tulis Kantor'
		case 'ruangan': return 'Peminjaman Ruangan & Aula'
		case 'mobil': return 'Peminjaman Kendaraan Operasional'
		case 'cleaning': return 'Layanan Cleaning Service'
		default: return 'Laporan'
	}
})

const activeTabLabelShort = computed(() => {
	switch (currentTab.value) {
		case 'atk': return 'ATK'
		case 'ruangan': return 'Ruangan'
		case 'mobil': return 'Mobil'
		case 'cleaning': return 'Kebersihan'
		default: return 'Kategori'
	}
})

// Divisi Name Helper
const getSelectedDivisionName = () => {
	if (selectedDivision.value === 'all') return 'Semua Divisi'
	const found = divisionsList.value.find(d => String(d.id) === String(selectedDivision.value))
	return found ? found.name : 'Divisi Terpilih'
}

// State Filter Khusus Kendaraan (Driver, Mobil, Tanggal, Bidang Pemohon)
const vehicleFilterDriver = ref('all')
const vehicleFilterVehicle = ref('all')
const vehicleFilterDateStart = ref('')
const vehicleFilterDateEnd = ref('')
const vehicleFilterDivision = ref('all')

const hasActiveVehicleFilter = computed(() => {
	return vehicleFilterDriver.value !== 'all' ||
		vehicleFilterVehicle.value !== 'all' ||
		vehicleFilterDateStart.value !== '' ||
		vehicleFilterDateEnd.value !== '' ||
		vehicleFilterDivision.value !== 'all'
})

const resetVehicleFilters = () => {
	vehicleFilterDriver.value = 'all'
	vehicleFilterVehicle.value = 'all'
	vehicleFilterDateStart.value = ''
	vehicleFilterDateEnd.value = ''
	vehicleFilterDivision.value = 'all'
}

// Daftar Driver Unik (Kombinasi API Drivers + Riwayat Laporan)
const uniqueDriversList = computed(() => {
	const map = new Map()
	// Dari driversList yang di-fetch dari API
	driversList.value.forEach(d => {
		if (d?.name) map.set(d.name.toLowerCase().trim(), { id: d.id, name: d.name })
	})
	// Dari data vehicleReports yang ada
	vehicleReports.value.forEach(v => {
		const name = v.driver_name
		if (name && name !== 'Menunggu Penugasan' && !name.includes('Standby') && name !== '-' && !name.toLowerCase().includes('pool')) {
			const key = name.toLowerCase().trim()
			if (!map.has(key)) {
				map.set(key, { id: v.driver_id || key, name })
			}
		}
	})
	return Array.from(map.values()).sort((a, b) => a.name.localeCompare(b.name))
})

// Daftar Mobil Unik (Kombinasi API Vehicles + Riwayat Laporan)
const uniqueVehiclesList = computed(() => {
	const map = new Map()
	// Dari vehiclesList
	vehiclesList.value.forEach(v => {
		if (v?.name) {
			const label = `${v.name} (${v.license_plate || '-'})`
			map.set(String(v.id || v.license_plate || v.name), {
				id: v.id,
				name: v.name,
				license_plate: v.license_plate,
				label
			})
		}
	})
	// Dari vehicleReports
	vehicleReports.value.forEach(v => {
		if (v.vehicle_name && v.vehicle_name !== 'Kendaraan') {
			const key = String(v.vehicle_id || v.license_plate || v.vehicle_name)
			if (!map.has(key)) {
				map.set(key, {
					id: v.vehicle_id,
					name: v.vehicle_name,
					license_plate: v.license_plate,
					label: `${v.vehicle_name} (${v.license_plate || '-'})`
				})
			}
		}
	})
	return Array.from(map.values()).sort((a, b) => a.name.localeCompare(b.name))
})

// Daftar Bidang Pemohon Unik
const uniqueDivisionsList = computed(() => {
	const map = new Map()
	divisionsList.value.forEach(d => {
		if (d?.name) map.set(d.name.toLowerCase().trim(), { id: d.id, name: d.name })
	})
	vehicleReports.value.forEach(v => {
		const div = v.borrower_division || v.division
		if (div && div !== '-') {
			const key = div.toLowerCase().trim()
			if (!map.has(key)) {
				map.set(key, { id: v.division_id || key, name: div })
			}
		}
	})
	return Array.from(map.values()).sort((a, b) => a.name.localeCompare(b.name))
})

// Check active filter state for Reset button
const hasActiveFilter = computed(() => {
	return selectedDivision.value !== 'all' ||
		selectedMonth.value !== 'all' ||
		selectedYear.value !== currentYearNum ||
		startDate.value !== '' ||
		endDate.value !== '' ||
		hasActiveVehicleFilter.value
})

// Filter Match Checker
const isItemMatchFilter = (item, targetMonth = null) => {
	// 1. Filter Divisi Umum
	if (selectedDivision.value !== 'all') {
		const targetDivId = String(selectedDivision.value)
		const itemDivId = item.division_id ? String(item.division_id) : ''
		const targetDivName = getSelectedDivisionName().toLowerCase()
		const itemDivName = (item.division || item.borrower_division || '').toLowerCase()
		const matchDiv = itemDivId === targetDivId || itemDivName === targetDivName
		if (!matchDiv) return false
	}

	// 2. Filter Tahun
	const itemYear = item.year || (item.date ? Number(item.date.split('-')[0]) : null)
	if (itemYear && Number(itemYear) !== Number(selectedYear.value)) {
		return false
	}

	// 3. Filter Bulan (bila parameter targetMonth diberikan atau dari selectedMonth)
	const mToCheck = targetMonth !== null ? targetMonth : (selectedMonth.value !== 'all' ? Number(selectedMonth.value) : null)
	if (mToCheck !== null) {
		const itemMonth = item.month || (item.date ? Number(item.date.split('-')[1]) : null)
		if (itemMonth && Number(itemMonth) !== Number(mToCheck)) {
			return false
		}
	}

	return true
}

// Filtered per category for Summary Counters
const filteredAtkReports = computed(() => atkReports.value.filter(it => isItemMatchFilter(it)))
const filteredRoomReports = computed(() => roomReports.value.filter(it => isItemMatchFilter(it)))
const filteredCleaningReports = computed(() => cleaningReports.value.filter(it => isItemMatchFilter(it)))

// Filter Laporan Kendaraan dengan Filter Spesifik (Driver, Mobil, Tanggal, Bidang)
const filteredVehicleReports = computed(() => {
	return vehicleReports.value.filter(item => {
		// 1. Cek filter umum (tahun, bulan, search global jika ada)
		if (!isItemMatchFilter(item)) return false

		// 2. Filter Driver / Pengemudi
		if (vehicleFilterDriver.value !== 'all') {
			if (vehicleFilterDriver.value === 'unassigned') {
				const isUnassigned = !item.driver_name ||
					item.driver_name === 'Menunggu Penugasan' ||
					item.driver_name.includes('Standby') ||
					item.driver_name.toLowerCase().includes('pool') ||
					item.driver_name === '-' ||
					!item.driver_id
				if (!isUnassigned) return false
			} else {
				const targetDriver = vehicleFilterDriver.value.toLowerCase().trim()
				const driverName = (item.driver_name || '').toLowerCase().trim()
				const matchDriver = driverName.includes(targetDriver) || String(item.driver_id) === String(vehicleFilterDriver.value)
				if (!matchDriver) return false
			}
		}

		// 3. Filter Mobil / Kendaraan
		if (vehicleFilterVehicle.value !== 'all') {
			const targetVeh = String(vehicleFilterVehicle.value).toLowerCase().trim()
			const vehId = item.vehicle_id ? String(item.vehicle_id).toLowerCase() : ''
			const vehName = (item.vehicle_name || '').toLowerCase()
			const vehPlate = (item.license_plate || '').toLowerCase()
			const matchVehicle = vehId === targetVeh || vehPlate.includes(targetVeh) || vehName.includes(targetVeh)
			if (!matchVehicle) return false
		}

		// 4. Filter Bidang yang Mengajukan
		if (vehicleFilterDivision.value !== 'all') {
			const targetDiv = String(vehicleFilterDivision.value).toLowerCase().trim()
			const divId = item.division_id ? String(item.division_id).toLowerCase() : ''
			const divName = (item.borrower_division || item.division || '').toLowerCase().trim()
			const matchDiv = divId === targetDiv || divName.includes(targetDiv)
			if (!matchDiv) return false
		}

		// 5. Filter Tanggal (Start Date & End Date kegiatan/perjalanan)
		if (vehicleFilterDateStart.value || vehicleFilterDateEnd.value) {
			let itemDateStr = ''
			if (item.start_date && item.start_date !== '-') {
				itemDateStr = item.start_date.split(' ')[0]
			} else if (item.date && item.date !== '-') {
				itemDateStr = item.date.split(' ')[0]
			}

			if (itemDateStr) {
				if (vehicleFilterDateStart.value && itemDateStr < vehicleFilterDateStart.value) {
					return false
				}
				if (vehicleFilterDateEnd.value && itemDateStr > vehicleFilterDateEnd.value) {
					return false
				}
			}
		}

		return true
	})
})

const filteredAtkCount = computed(() => filteredAtkReports.value.length)
const filteredRoomCount = computed(() => filteredRoomReports.value.length)
const filteredVehicleCount = computed(() => filteredVehicleReports.value.length)
const filteredCleaningCount = computed(() => filteredCleaningReports.value.length)

// Current Collection according to active tab
const currentCollection = computed(() => {
	switch (currentTab.value) {
		case 'atk': return filteredAtkReports.value
		case 'ruangan': return filteredRoomReports.value
		case 'mobil': return filteredVehicleReports.value
		case 'cleaning': return filteredCleaningReports.value
		default: return []
	}
})

// Filtered items with search query
const currentFilteredItems = computed(() => {
	const query = searchQuery.value.toLowerCase().trim()
	if (!query) return currentCollection.value

	return currentCollection.value.filter(item => {
		const str = Object.values(item).join(' ').toLowerCase()
		return str.includes(query)
	})
})

// =========================================================================
// MONTHLY STATS & DIAGRAM BATANG CALCULATIONS
// =========================================================================
const monthlyStats = computed(() => {
	return monthNames.map((fullName, idx) => {
		const mNum = idx + 1
		const shortName = shortMonthNames[idx]

		const atkCount = atkReports.value.filter(it => isItemMatchFilter(it, mNum)).length
		const roomsCount = roomReports.value.filter(it => isItemMatchFilter(it, mNum)).length
		const vehiclesCount = vehicleReports.value.filter(it => isItemMatchFilter(it, mNum)).length
		const cleaningCount = cleaningReports.value.filter(it => isItemMatchFilter(it, mNum)).length
		const total = atkCount + roomsCount + vehiclesCount + cleaningCount

		let displayCount = total
		if (chartMode.value === 'tab') {
			if (currentTab.value === 'atk') displayCount = atkCount
			else if (currentTab.value === 'ruangan') displayCount = roomsCount
			else if (currentTab.value === 'mobil') displayCount = vehiclesCount
			else if (currentTab.value === 'cleaning') displayCount = cleaningCount
		}

		return {
			month: mNum,
			shortName,
			fullName,
			atk: atkCount,
			rooms: roomsCount,
			vehicles: vehiclesCount,
			cleaning: cleaningCount,
			total,
			displayCount
		}
	})
})

// Max Count for dynamic scaling of Y Axis
const maxMonthlyCount = computed(() => {
	const max = Math.max(...monthlyStats.value.map(m => m.displayCount), 0)
	if (max <= 5) return 5
	if (max <= 10) return 10
	if (max <= 20) return 20
	if (max <= 50) return Math.ceil(max / 10) * 10
	return Math.ceil(max / 20) * 20
})

// Bars with coordinates for SVG rendering
const monthlyChartBars = computed(() => {
	const maxVal = maxMonthlyCount.value
	return monthlyStats.value.map((m, idx) => {
		const rawHeight = (m.displayCount / maxVal) * 180
		return {
			...m,
			barHeight: m.displayCount > 0 ? Math.max(Math.round(rawHeight), 6) : 3,
			x: 75 + idx * 72
		}
	})
})

// 5 Horizontal Gridlines
const chartGridLines = computed(() => {
	const max = maxMonthlyCount.value
	const steps = [0, Math.round(max * 0.25), Math.round(max * 0.5), Math.round(max * 0.75), max]
	return steps.map(val => ({
		val,
		y: Math.round(225 - (val / max) * 180)
	}))
})

// Bar fill color
const getBarFill = (m) => {
	if (selectedMonth.value === m.month) return 'url(#bar-grad-active)'
	if (chartMode.value === 'tab') {
		if (currentTab.value === 'atk') return 'url(#bar-grad-emerald)'
		if (currentTab.value === 'ruangan') return 'url(#bar-grad-cyan)'
		if (currentTab.value === 'mobil') return 'url(#bar-grad-amber)'
		if (currentTab.value === 'cleaning') return 'url(#bar-grad-purple)'
	}
	return m.displayCount > 0 ? 'url(#bar-grad-emerald)' : 'rgba(255, 255, 255, 0.12)'
}

// Hover Tooltip Handlers
const onBarHover = (mNum, event) => {
	hoveredMonth.value = mNum
	if (chartBodyRef.value && event) {
		const rect = chartBodyRef.value.getBoundingClientRect()
		const clientX = event.clientX - rect.left
		const clientY = event.clientY - rect.top
		tooltipPos.value = {
			x: Math.min(Math.max(clientX - 90, 10), rect.width - 240),
			y: Math.max(clientY - 130, 10)
		}
	}
}

const hoveredMonthData = computed(() => {
	if (!hoveredMonth.value) return null
	return monthlyStats.value.find(m => m.month === hoveredMonth.value)
})

// Click Month to filter or unfilter
const selectMonth = (mNum) => {
	if (selectedMonth.value === mNum) {
		selectedMonth.value = 'all'
	} else {
		selectedMonth.value = mNum
	}
}

// Key KPI Metrics
const yearTotalCount = computed(() => {
	return monthlyStats.value.reduce((acc, m) => acc + m.displayCount, 0)
})

const peakMonth = computed(() => {
	let peak = { fullName: '-', displayCount: 0 }
	monthlyStats.value.forEach(m => {
		if (m.displayCount > peak.displayCount) {
			peak = m
		}
	})
	return peak
})

const peakMonthName = computed(() => peakMonth.value.displayCount > 0 ? peakMonth.value.fullName : 'Belum Ada')
const peakMonthCount = computed(() => peakMonth.value.displayCount)

const avgMonthlyCount = computed(() => {
	const total = yearTotalCount.value
	return (total / 12).toFixed(1)
})

// Toggle Theme
const toggleTheme = () => {
	isDark.value = !isDark.value
	if (typeof window !== 'undefined') {
		localStorage.setItem('admin_theme', isDark.value ? 'dark' : 'light')
	}
}

// Sidebar activation
const activate = async selected => {
	menus.value.forEach(item => { item.active = item.name === selected.name })
	if (selected.name === 'Manajemen Alat') await router.push('/admin/dashboard')
	if (selected.name === 'Pengguna') await router.push('/admin/pengguna')
	if (selected.name === 'Divisi') await router.push('/admin/divisi')
}

const handleImgError = (event, fallbackUrl) => {
	if (event && event.target && event.target.src !== fallbackUrl) {
		event.target.src = fallbackUrl
	}
}

// Preset Handlers
const applyPreset = (preset) => {
	selectedPreset.value = preset
	const now = new Date()

	if (preset === 'all') {
		startDate.value = ''
		endDate.value = ''
	} else if (preset === 'today') {
		const ymd = now.toISOString().split('T')[0]
		startDate.value = ymd
		endDate.value = ymd
	} else if (preset === '7days') {
		const past = new Date(now)
		past.setDate(past.getDate() - 7)
		startDate.value = past.toISOString().split('T')[0]
		endDate.value = now.toISOString().split('T')[0]
	} else if (preset === 'month') {
		const firstDay = new Date(now.getFullYear(), now.getMonth(), 1)
		startDate.value = firstDay.toISOString().split('T')[0]
		endDate.value = now.toISOString().split('T')[0]
	}

	fetchReports()
}

const onDivisionChange = () => {
	fetchReports()
}

const onYearChange = () => {
	fetchReports()
}

const onMonthChange = () => {
	// Reaktif via computed isItemMatchFilter
}

const resetFilters = () => {
	selectedDivision.value = 'all'
	selectedYear.value = currentYearNum
	selectedMonth.value = 'all'
	startDate.value = ''
	endDate.value = ''
	selectedPreset.value = 'all'
	searchQuery.value = ''
	resetVehicleFilters()
	fetchReports()
}

// Fetch Divisions List
const fetchDivisionsList = async () => {
	try {
		const res = await $fetch(`${apiBase}/divisions`).catch(() => null) || await $fetch(`${apiBase}/v1/manage/divisions`).catch(() => null)
		if (res && res.divisions) {
			divisionsList.value = res.divisions
		} else if (Array.isArray(res)) {
			divisionsList.value = res
		}
	} catch (e) {
		console.warn('Gagal memuat list divisi:', e)
	}
}

// Fetch reports from Backend API
const fetchReports = async () => {
	isLoading.value = true
	try {
		const params = new URLSearchParams()
		if (startDate.value) params.append('start_date', startDate.value)
		if (endDate.value) params.append('end_date', endDate.value)
		if (searchQuery.value) params.append('search', searchQuery.value)
		if (selectedDivision.value !== 'all') params.append('division_id', selectedDivision.value)
		if (selectedYear.value) params.append('year', selectedYear.value)

		const url = `${apiBase}/reports/all?${params.toString()}`
		const res = await $fetch(url)

		if (res) {
			atkReports.value = res.atk || []
			if (res.atk_products && Array.isArray(res.atk_products)) {
				atkProducts.value = res.atk_products
			}
			roomReports.value = res.rooms || []
			vehicleReports.value = res.vehicles || []
			cleaningReports.value = res.cleaning || []
		}
	} catch (e) {
		console.warn('Gagal memuat laporan dari API:', e)
		atkReports.value = []
		roomReports.value = []
		vehicleReports.value = []
		cleaningReports.value = []
	} finally {
		isLoading.value = false
		if (atkProducts.value.length === 0) {
			fetchAtkProducts()
		}
	}
}

// Fetch ATK products catalog for stock rankings
const fetchAtkProducts = async () => {
	try {
		const res = await $fetch(`${apiBase}/products`).catch(() => null)
			|| await $fetch(`${apiBase}/assets/atk`).catch(() => null)
		if (res && res.products && Array.isArray(res.products)) {
			atkProducts.value = res.products
		} else if (Array.isArray(res)) {
			atkProducts.value = res
		}
	} catch (e) {
		console.warn('Gagal memuat produk atk:', e)
	}
}

// Status badge helper
const getStatusBadgeClass = (status) => {
	const s = String(status || '').toLowerCase()
	if (s.includes('approved') || s.includes('selesai') || s.includes('completed')) return 'status-green'
	if (s.includes('pending') || s.includes('menunggu')) return 'status-amber'
	if (s.includes('progress') || s.includes('diproses')) return 'status-blue'
	return 'status-red'
}

const formatStatus = (status) => {
	const map = {
		pending: 'Menunggu Persetujuan',
		approved: 'Disetujui',
		completed: 'Selesai',
		rejected: 'Ditolak',
		in_progress: 'Sedang Dikerjakan',
		cancelled: 'Dibatalkan'
	}
	return map[status] || status || 'Pending'
}

// Handler Aksi Persetujuan Admin (Setuju / Tolak)
const updateBookingApproval = async (item, type, status, extraData = {}) => {
	actionLoading.value[item.id] = true
	approvalAlert.value = null

	const actionLabel = status === 'approved' ? 'disetujui' : 'ditolak'
	const endpoint = type === 'ruangan' 
		? `${apiBase}/room-bookings/${item.id}/status`
		: `${apiBase}/vehicle-bookings/${item.id}/status`
	const fallbackEndpoint = type === 'ruangan'
		? `${apiBase}/v1/manage/room-bookings/${item.id}/status`
		: `${apiBase}/v1/manage/vehicle-bookings/${item.id}/status`

	const body = { status, ...extraData }

	try {
		await $fetch(endpoint, {
			method: 'PATCH',
			body
		})
		const extraMsg = status === 'approved' && type === 'mobil' 
			? ` Armada ${extraData.vehicle_name ? '(' + extraData.vehicle_name + ')' : ''} dan Sopir ${extraData.driver_name ? '(' + extraData.driver_name + ')' : ''} berhasil ditugaskan!`
			: ''
		const borrowerNameLabel = item.borrower_name || (item.user_name !== 'admin' ? item.user_name : (item.passengers || 'Pegawai'))
		approvalAlert.value = {
			type: 'success',
			message: `Permohonan ${type === 'ruangan' ? 'ruangan' : 'peminjaman mobil'} dari ${borrowerNameLabel} berhasil ${actionLabel}!${extraMsg} ${status === 'approved' && type === 'mobil' ? 'Notifikasi telah masuk ke portal pengemudi.' : ''}`
		}
		item.status = status
		await fetchReports()
	} catch (err) {
		try {
			await $fetch(fallbackEndpoint, {
				method: 'PATCH',
				body
			})
			const extraMsg = status === 'approved' && type === 'mobil' 
				? ` Armada ${extraData.vehicle_name ? '(' + extraData.vehicle_name + ')' : ''} dan Sopir ${extraData.driver_name ? '(' + extraData.driver_name + ')' : ''} berhasil ditugaskan!`
				: ''
			const borrowerNameLabel = item.borrower_name || (item.user_name !== 'admin' ? item.user_name : (item.passengers || 'Pegawai'))
			approvalAlert.value = {
				type: 'success',
				message: `Permohonan ${type === 'ruangan' ? 'ruangan' : 'peminjaman mobil'} dari ${borrowerNameLabel} berhasil ${actionLabel}!${extraMsg} ${status === 'approved' && type === 'mobil' ? 'Notifikasi telah masuk ke portal pengemudi.' : ''}`
			}
			item.status = status
			await fetchReports()
		} catch (innerErr) {
			approvalAlert.value = {
				type: 'error',
				message: `Gagal memperbarui status permohonan: ${innerErr?.data?.message || err?.message || 'Terjadi kesalahan'}`
			}
		}
	} finally {
		actionLoading.value[item.id] = false
	}
}

// Fetch Armada Mobil & Daftar Sopir
const fetchVehiclesAndDrivers = async () => {
	try {
		const [vRes, dRes] = await Promise.all([
			$fetch(`${apiBase}/vehicles`).catch(() => []),
			$fetch(`${apiBase}/drivers`).catch(() => [])
		])
		vehiclesList.value = Array.isArray(vRes) ? vRes : []
		driversList.value = Array.isArray(dRes) ? dRes : []
		
		// Fallback drivers jika endpoint /drivers kosong: ambil dari user dengan role sopir
		if (driversList.value.length === 0) {
			const uRes = await $fetch(`${apiBase}/users`).catch(() => [])
			if (Array.isArray(uRes)) {
				driversList.value = uRes.filter(u => u.role === 'sopir')
			}
		}
	} catch (e) {
		console.warn('Gagal memuat kendaraan atau pengemudi:', e)
	}
}

// Buka Modal Approval Mobil & Sopir
const openVehicleApprovalModal = (item) => {
	selectedVehicleBooking.value = item
	fetchVehiclesAndDrivers()
	
	// Default selection
	const defaultVehicle = vehiclesList.value.find(v => v.status_key === 'available' || v.status === 'Tersedia') || vehiclesList.value[0] || null
	const defaultDriver = driversList.value.find(d => d.name === defaultVehicle?.current_driver_name) || driversList.value[0] || null

	approvalVehicleForm.value = {
		vehicle_id: defaultVehicle?.id || null,
		driver_id: defaultDriver?.id || null,
		driver_name: defaultDriver?.name || defaultVehicle?.current_driver_name || '',
		notes: ''
	}

	isVehicleApprovalModalOpen.value = true
}

const onVehicleSelectionChange = () => {
	const sel = vehiclesList.value.find(v => v.id === approvalVehicleForm.value.vehicle_id)
	if (sel && sel.current_driver_name) {
		const matchedDriver = driversList.value.find(d => d.name === sel.current_driver_name)
		if (matchedDriver) {
			approvalVehicleForm.value.driver_id = matchedDriver.id
			approvalVehicleForm.value.driver_name = matchedDriver.name
		} else {
			approvalVehicleForm.value.driver_name = sel.current_driver_name
		}
	}
}

const onDriverSelectionChange = () => {
	const sel = driversList.value.find(d => d.id === approvalVehicleForm.value.driver_id)
	if (sel) {
		approvalVehicleForm.value.driver_name = sel.name
	}
}

const submitVehicleApproval = async () => {
	if (!selectedVehicleBooking.value) return

	const targetVehicleId = approvalVehicleForm.value.vehicle_id
	const targetDriverId = approvalVehicleForm.value.driver_id
	const targetDriverName = (approvalVehicleForm.value.driver_name || '').trim().toLowerCase()

	const newBooking = selectedVehicleBooking.value
	const newBookingId = newBooking.id

	// Parse waktu mulai & selesai booking baru
	const newStartStr = newBooking.start_date || newBooking.date || ''
	const newEndStr = newBooking.end_date || ''

	// Lakukan pemeriksaan bentrok terhadap booking aktif lain di vehicleReports
	const conflictingBooking = (vehicleReports.value || []).find(other => {
		if (!other || other.id === newBookingId) return false
		
		// Status yang dianggap sedang aktif atau telah disetujui
		const otherStatus = String(other.status || '').toLowerCase()
		const isActiveTrip = otherStatus === 'approved' || otherStatus === 'in_progress' || otherStatus === 'departed' || otherStatus === 'returning'
		if (!isActiveTrip) return false

		// Cek apakah mobil sama atau pengemudi sama
		const isSameVehicle = targetVehicleId && (other.vehicle_id == targetVehicleId)
		const isSameDriver = (targetDriverId && other.driver_id == targetDriverId) || 
		                     (targetDriverName && other.driver_name && other.driver_name.toLowerCase().trim() === targetDriverName)
		
		if (!isSameVehicle && !isSameDriver) return false

		// Bandingkan jam:
		// other.end_date adalah estimasi pulang pemakai sebelumnya
		// other.start_date adalah jam berangkat pemakai sebelumnya
		// newStartStr adalah jam berangkat booking baru
		if (!other.end_date || other.end_date === '-') return false
		if (!newStartStr || newStartStr === '-') return false

		try {
			const otherEndRaw = other.end_date.includes('T') ? other.end_date : other.end_date.replace(' ', 'T')
			const otherStartRaw = other.start_date && other.start_date !== '-' ? (other.start_date.includes('T') ? other.start_date : other.start_date.replace(' ', 'T')) : otherEndRaw
			const newStartRaw = newStartStr.includes('T') ? newStartStr : newStartStr.replace(' ', 'T')

			const otherEndDate = new Date(otherEndRaw)
			const otherStartDate = new Date(otherStartRaw)
			const newStartDate = new Date(newStartRaw)
			
			// Jika format tanggal valid
			if (!isNaN(otherEndDate.getTime()) && !isNaN(newStartDate.getTime())) {
				// Cek tanggal yang sama
				const otherDateOnly = otherEndDate.toISOString().slice(0, 10)
				const newDateOnly = newStartDate.toISOString().slice(0, 10)

				// Jika hari yang sama, bandingkan jam berangkat baru dengan jam estimasi pulang sebelumnya
				if (otherDateOnly === newDateOnly) {
					// Jika jam berangkat booking baru KURANG DARI (<) jam estimasi pulang pemakai sebelumnya:
					return newStartDate < otherEndDate && newStartDate >= otherStartDate
				} else {
					// Jika multi-hari atau tanggal beririsan:
					const newEndDate = newEndStr && newEndStr !== '-' ? new Date(newEndStr.includes('T') ? newEndStr : newEndStr.replace(' ', 'T')) : newStartDate
					return newStartDate < otherEndDate && newEndDate > otherStartDate
				}
			}
		} catch (err) {
			console.warn('Gagal membandingkan tanggal booking:', err)
		}

		return false
	})

	if (conflictingBooking) {
		const selVeh = vehiclesList.value.find(v => v.id === targetVehicleId)
		const isSameVeh = targetVehicleId && (conflictingBooking.vehicle_id == targetVehicleId)
		const isSameDrv = (targetDriverId && conflictingBooking.driver_id == targetDriverId) ||
		                  (targetDriverName && conflictingBooking.driver_name && conflictingBooking.driver_name.toLowerCase().trim() === targetDriverName)

		let reasonTitle = ''
		let reasonMsg = ''
		if (isSameVeh && isSameDrv) {
			reasonTitle = `Mobil ${selVeh?.name || 'Dinas'} & Pengemudi ${approvalVehicleForm.value.driver_name || 'Sopir'} Sedang Bertugas Di Jalan!`
			reasonMsg = `Mobil ${selVeh?.name || ''} dan Pengemudi ${approvalVehicleForm.value.driver_name} saat ini masih dipakai dalam perjalanan dinas oleh pegawai lain.`
		} else if (isSameVeh) {
			reasonTitle = `Mobil ${selVeh?.name || 'Dinas'} Sedang Dipakai & Belum Pulang!`
			reasonMsg = `Kendaraan ${selVeh?.name || ''} (${selVeh?.license_plate || '-'}) saat ini masih dipakai di jalan dan baru diperkirakan pulang pada ${conflictingBooking.end_date} WIB.`
		} else {
			reasonTitle = `Pengemudi ${approvalVehicleForm.value.driver_name || 'Sopir'} Sedang Mengantar Perjalanan Lain!`
			reasonMsg = `Pengemudi ${approvalVehicleForm.value.driver_name} sedang bertugas di jalan dan baru diperkirakan pulang pada ${conflictingBooking.end_date} WIB.`
		}

		// Tampilkan popup modal peringatan dengan pilihan Ubah atau Batal
		scheduleConflictModal.value = {
			show: true,
			conflictReasonTitle: reasonTitle,
			conflictMessage: reasonMsg,
			otherUserName: conflictingBooking.borrower_name || conflictingBooking.user_name || 'Pegawai Pemohon Lain',
			otherDivision: conflictingBooking.borrower_division || conflictingBooking.division || 'Divisi Kantor',
			otherDestination: conflictingBooking.destination || 'Tujuan Dinas',
			otherReturnTimeFormatted: conflictingBooking.end_date || '-',
			newUserName: newBooking.borrower_name || newBooking.user_name || 'Pemohon Baru',
			newDivision: newBooking.borrower_division || newBooking.division || 'Divisi Pemohon',
			newDestination: newBooking.destination || 'Tujuan Dinas',
			newStartTimeFormatted: newStartStr || '-'
		}
		return
	}

	const selVeh = vehiclesList.value.find(v => v.id === approvalVehicleForm.value.vehicle_id)
	const payload = {
		vehicle_id: approvalVehicleForm.value.vehicle_id,
		vehicle_name: selVeh ? `${selVeh.name} (${selVeh.license_plate})` : '',
		driver_id: approvalVehicleForm.value.driver_id,
		driver_name: approvalVehicleForm.value.driver_name,
		notes: approvalVehicleForm.value.notes
	}

	await updateBookingApproval(selectedVehicleBooking.value, 'mobil', 'approved', payload)
	isVehicleApprovalModalOpen.value = false
}

// =========================================================================
// DOWNLOAD EXCEL (.XLS / FORMAL HITAM PUTIH RAPIH & PRESISI)
// =========================================================================
const downloadExcel = () => {
	const items = currentFilteredItems.value
	const tabName = activeTabTitle.value
	const divName = getSelectedDivisionName()
	const monthLabel = selectedMonth.value !== 'all' ? monthNames[selectedMonth.value - 1] : 'Seluruh Bulan (Jan - Des)'
	const dateRangeLabel = `${startDate.value || 'Awal'} s/d ${endDate.value || 'Sekarang'}`

	// Konfigurasi kolom dan lebar presisi untuk setiap tab
	let colConfigs = []
	if (currentTab.value === 'atk') {
		colConfigs = [
			{ name: 'NO', widthPt: 75, widthPx: 100, align: 'center', isText: false },
			{ name: 'TANGGAL', widthPt: 90, widthPx: 120, align: 'center', isText: true },
			{ name: 'NAMA PEGAWAI', widthPt: 160, widthPx: 210, align: 'left', isText: false },
			{ name: 'NIP', widthPt: 130, widthPx: 170, align: 'center', isText: true },
			{ name: 'DIVISI', widthPt: 130, widthPx: 170, align: 'left', isText: false },
			{ name: 'DAFTAR BARANG & JUMLAH', widthPt: 230, widthPx: 300, align: 'left', isText: false },
			{ name: 'CATATAN PENGAMBILAN', widthPt: 160, widthPx: 210, align: 'left', isText: false },
			{ name: 'STATUS', widthPt: 95, widthPx: 125, align: 'center', isText: false }
		]
	} else if (currentTab.value === 'ruangan') {
		colConfigs = [
			{ name: 'NO', widthPt: 45, widthPx: 60, align: 'center', isText: false },
			{ name: 'TANGGAL', widthPt: 90, widthPx: 120, align: 'center', isText: true },
			{ name: 'NAMA PEGAWAI', widthPt: 150, widthPx: 195, align: 'left', isText: false },
			{ name: 'NIP', widthPt: 130, widthPx: 170, align: 'center', isText: true },
			{ name: 'DIVISI', widthPt: 125, widthPx: 165, align: 'left', isText: false },
			{ name: 'RUANGAN', widthPt: 135, widthPx: 175, align: 'left', isText: false },
			{ name: 'KODE RUANG', widthPt: 85, widthPx: 110, align: 'center', isText: true },
			{ name: 'AGENDA', widthPt: 185, widthPx: 240, align: 'left', isText: false },
			{ name: 'WAKTU MULAI', widthPt: 90, widthPx: 120, align: 'center', isText: false },
			{ name: 'WAKTU SELESAI', widthPt: 90, widthPx: 120, align: 'center', isText: false },
			{ name: 'STATUS', widthPt: 95, widthPx: 125, align: 'center', isText: false }
		]
	} else if (currentTab.value === 'mobil') {
		colConfigs = [
			{ name: 'NO', widthPt: 45, widthPx: 60, align: 'center', isText: false },
			{ name: 'TANGGAL', widthPt: 90, widthPx: 120, align: 'center', isText: true },
			{ name: 'NAMA PEGAWAI', widthPt: 150, widthPx: 195, align: 'left', isText: false },
			{ name: 'NIP', widthPt: 130, widthPx: 170, align: 'center', isText: true },
			{ name: 'DIVISI', widthPt: 125, widthPx: 165, align: 'left', isText: false },
			{ name: 'KENDARAAN', widthPt: 135, widthPx: 175, align: 'left', isText: false },
			{ name: 'PLAT NOMOR', widthPt: 95, widthPx: 125, align: 'center', isText: true },
			{ name: 'TUJUAN', widthPt: 185, widthPx: 240, align: 'left', isText: false },
			{ name: 'WAKTU MULAI', widthPt: 90, widthPx: 120, align: 'center', isText: false },
			{ name: 'WAKTU SELESAI', widthPt: 90, widthPx: 120, align: 'center', isText: false },
			{ name: 'PENUMPANG', widthPt: 85, widthPx: 110, align: 'center', isText: false },
			{ name: 'STATUS', widthPt: 95, widthPx: 125, align: 'center', isText: false }
		]
	} else if (currentTab.value === 'cleaning') {
		colConfigs = [
			{ name: 'NO', widthPt: 45, widthPx: 60, align: 'center', isText: false },
			{ name: 'TANGGAL', widthPt: 90, widthPx: 120, align: 'center', isText: true },
			{ name: 'NAMA PEMOHON', widthPt: 150, widthPx: 195, align: 'left', isText: false },
			{ name: 'NIP', widthPt: 130, widthPx: 170, align: 'center', isText: true },
			{ name: 'DIVISI', widthPt: 125, widthPx: 165, align: 'left', isText: false },
			{ name: 'JENIS LAYANAN', widthPt: 135, widthPx: 175, align: 'left', isText: false },
			{ name: 'LOKASI', widthPt: 165, widthPx: 215, align: 'left', isText: false },
			{ name: 'ALAT', widthPt: 135, widthPx: 175, align: 'left', isText: false },
			{ name: 'STATUS', widthPt: 95, widthPx: 125, align: 'center', isText: false }
		]
	}

	const totalCols = colConfigs.length
	const colGroupHtml = colConfigs.map(c => `<col width="${c.widthPx}" style="width: ${c.widthPt}pt; mso-width-source: userset;">`).join('')

	let sectionsRowsHtml = ''

	// BAGIAN KHUSUS JIKA TAB ATK: Leaderboard & Radar Restock (Hitam Putih Murni)
	if (currentTab.value === 'atk') {
		// 1. Leaderboard Terbanyak (spans: 1 + 3 + 1 + 1 + 2 = 8 cols)
		const topTakenRows = (atkMostTakenFullList.value.slice(0, 15) || []).map((it) => `
			<tr style="height: 20pt; background-color: #ffffff;">
				<td colspan="1" style="text-align: center; border: 1px solid #000000; padding: 5px 8px; vertical-align: middle; color: #000000;">${it.rank}</td>
				<td colspan="3" style="text-align: left; border: 1px solid #000000; padding: 5px 8px; vertical-align: middle; color: #000000;">${it.name || '-'}</td>
				<td colspan="1" style="text-align: center; border: 1px solid #000000; padding: 5px 8px; vertical-align: middle; color: #000000;">${it.unit || '-'}</td>
				<td colspan="1" style="text-align: center; border: 1px solid #000000; padding: 5px 8px; vertical-align: middle; font-weight: bold; color: #000000;">${it.totalQty || 0}</td>
				<td colspan="2" style="text-align: center; border: 1px solid #000000; padding: 5px 8px; vertical-align: middle; color: #000000;">${it.borrowCount || 0} kali</td>
			</tr>
		`).join('')

		// 2. Radar Sisa Stok Kritis (spans: 1 + 3 + 1 + 1 + 2 = 8 cols)
		const lowStockRows = (atkLowestStockFullList.value.slice(0, 15) || []).map((it) => `
			<tr style="height: 20pt; background-color: #ffffff;">
				<td colspan="1" style="text-align: center; border: 1px solid #000000; padding: 5px 8px; vertical-align: middle; color: #000000;">${it.rank}</td>
				<td colspan="3" style="text-align: left; border: 1px solid #000000; padding: 5px 8px; vertical-align: middle; color: #000000;">${it.name || '-'}</td>
				<td colspan="1" style="text-align: center; border: 1px solid #000000; padding: 5px 8px; vertical-align: middle; font-weight: bold; color: #000000;">${it.stock ?? 0}</td>
				<td colspan="1" style="text-align: center; border: 1px solid #000000; padding: 5px 8px; vertical-align: middle; color: #000000;">${it.unit || '-'}</td>
				<td colspan="2" style="text-align: center; border: 1px solid #000000; padding: 5px 8px; vertical-align: middle; color: #000000;">${getStockStatusText(it.stock)}</td>
			</tr>
		`).join('')

		sectionsRowsHtml += `
			<!-- SECTION 1: LEADERBOARD -->
			<tr style="height: 24pt;">
				<td colspan="${totalCols}" style="background-color: #d9d9d9; font-weight: bold; font-size: 9.5pt; color: #000000; padding: 6px 10px; border: 1px solid #000000; vertical-align: middle; text-transform: uppercase;">
					1. LEADERBOARD TOP 15 BARANG PALING BANYAK DIAMBIL
				</td>
			</tr>
			<tr style="background-color: #f2f2f2; color: #000000; font-weight: bold; text-align: center; height: 24pt;">
				<th colspan="1" style="border: 1px solid #000000; padding: 6px 4px; color: #000000; font-size: 9pt; text-align: center; vertical-align: middle; background-color: #f2f2f2;">PERINGKAT</th>
				<th colspan="3" style="border: 1px solid #000000; padding: 6px 6px; color: #000000; font-size: 9pt; text-align: left; vertical-align: middle; background-color: #f2f2f2;">NAMA BARANG ATK</th>
				<th colspan="1" style="border: 1px solid #000000; padding: 6px 4px; color: #000000; font-size: 9pt; text-align: center; vertical-align: middle; background-color: #f2f2f2;">SATUAN</th>
				<th colspan="1" style="border: 1px solid #000000; padding: 6px 4px; color: #000000; font-size: 9pt; text-align: center; vertical-align: middle; background-color: #f2f2f2;">TOTAL DIAMBIL</th>
				<th colspan="2" style="border: 1px solid #000000; padding: 6px 4px; color: #000000; font-size: 9pt; text-align: center; vertical-align: middle; background-color: #f2f2f2;">FREKUENSI PENGAJUAN</th>
			</tr>
			${topTakenRows || `<tr><td colspan="${totalCols}" style="border: 1px solid #000000; text-align: center; padding: 8px; vertical-align: middle; color: #000000;">Tidak ada data pengambilan.</td></tr>`}

			<!-- PEMISAH ANTAR SECTION -->
			<tr style="height: 14pt;"><td colspan="${totalCols}" style="border: none; mso-border-alt: none; background-color: #ffffff;"></td></tr>

			<!-- SECTION 2: RADAR RESTOCK -->
			<tr style="height: 24pt;">
				<td colspan="${totalCols}" style="background-color: #d9d9d9; font-weight: bold; font-size: 9.5pt; color: #000000; padding: 6px 10px; border: 1px solid #000000; vertical-align: middle; text-transform: uppercase;">
					2. RADAR PRIORITAS RESTOCK: TOP 15 SISA STOK PALING SEDIKIT (KRITIS)
				</td>
			</tr>
			<tr style="background-color: #f2f2f2; color: #000000; font-weight: bold; text-align: center; height: 24pt;">
				<th colspan="1" style="border: 1px solid #000000; padding: 6px 4px; color: #000000; font-size: 9pt; text-align: center; vertical-align: middle; background-color: #f2f2f2;">URUTAN</th>
				<th colspan="3" style="border: 1px solid #000000; padding: 6px 6px; color: #000000; font-size: 9pt; text-align: left; vertical-align: middle; background-color: #f2f2f2;">NAMA BARANG ATK</th>
				<th colspan="1" style="border: 1px solid #000000; padding: 6px 4px; color: #000000; font-size: 9pt; text-align: center; vertical-align: middle; background-color: #f2f2f2;">SISA STOK GUDANG</th>
				<th colspan="1" style="border: 1px solid #000000; padding: 6px 4px; color: #000000; font-size: 9pt; text-align: center; vertical-align: middle; background-color: #f2f2f2;">SATUAN</th>
				<th colspan="2" style="border: 1px solid #000000; padding: 6px 4px; color: #000000; font-size: 9pt; text-align: center; vertical-align: middle; background-color: #f2f2f2;">STATUS INVENTARIS</th>
			</tr>
			${lowStockRows || `<tr><td colspan="${totalCols}" style="border: 1px solid #000000; text-align: center; padding: 8px; vertical-align: middle; color: #000000;">Tidak ada data inventaris.</td></tr>`}

			<!-- PEMISAH ANTAR SECTION -->
			<tr style="height: 14pt;"><td colspan="${totalCols}" style="border: none; mso-border-alt: none; background-color: #ffffff;"></td></tr>
		`
	} else {
		// Rekapitulasi Bulanan Ruangan, Mobil, Cleaning (Hitam Putih Murni)
		let sp = [2, 2, 2, 2, 2, 1] // default 11 cols (ruangan)
		if (totalCols === 12) {
			sp = [2, 2, 2, 2, 2, 2] // mobil
		} else if (totalCols === 9) {
			sp = [2, 1, 1, 1, 2, 2] // cleaning
		}

		const monthlySummaryRows = (monthlyStats.value || []).map((m) => `
			<tr style="height: 20pt; background-color: #ffffff;">
				<td colspan="${sp[0]}" style="border: 1px solid #000000; padding: 5px 8px; vertical-align: middle; text-align: left; color: #000000;">${m.fullName}</td>
				<td colspan="${sp[1]}" style="text-align: center; border: 1px solid #000000; padding: 5px 8px; vertical-align: middle; color: #000000;">${m.atk}</td>
				<td colspan="${sp[2]}" style="text-align: center; border: 1px solid #000000; padding: 5px 8px; vertical-align: middle; color: #000000;">${m.rooms}</td>
				<td colspan="${sp[3]}" style="text-align: center; border: 1px solid #000000; padding: 5px 8px; vertical-align: middle; color: #000000;">${m.vehicles}</td>
				<td colspan="${sp[4]}" style="text-align: center; border: 1px solid #000000; padding: 5px 8px; vertical-align: middle; color: #000000;">${m.cleaning}</td>
				<td colspan="${sp[5]}" style="text-align: center; border: 1px solid #000000; padding: 5px 8px; vertical-align: middle; font-weight: bold; color: #000000;">${m.total}</td>
			</tr>
		`).join('')

		const totalAtk = (monthlyStats.value || []).reduce((a, b) => a + (Number(b.atk) || 0), 0)
		const totalRooms = (monthlyStats.value || []).reduce((a, b) => a + (Number(b.rooms) || 0), 0)
		const totalVehicles = (monthlyStats.value || []).reduce((a, b) => a + (Number(b.vehicles) || 0), 0)
		const totalCleaning = (monthlyStats.value || []).reduce((a, b) => a + (Number(b.cleaning) || 0), 0)

		sectionsRowsHtml += `
			<!-- SECTION 1: REKAPITULASI AKTIVITAS BULANAN -->
			<tr style="height: 24pt;">
				<td colspan="${totalCols}" style="background-color: #d9d9d9; font-weight: bold; font-size: 9.5pt; color: #000000; padding: 6px 10px; border: 1px solid #000000; vertical-align: middle; text-transform: uppercase;">
					1. REKAPITULASI AKTIVITAS BULANAN TAHUN ${selectedYear.value}
				</td>
			</tr>
			<tr style="background-color: #f2f2f2; color: #000000; font-weight: bold; text-align: center; height: 24pt;">
				<th colspan="${sp[0]}" style="border: 1px solid #000000; padding: 6px 6px; color: #000000; font-size: 9pt; text-align: left; vertical-align: middle; background-color: #f2f2f2;">BULAN</th>
				<th colspan="${sp[1]}" style="border: 1px solid #000000; padding: 6px 4px; color: #000000; font-size: 9pt; text-align: center; vertical-align: middle; background-color: #f2f2f2;">PEMINJAMAN ATK</th>
				<th colspan="${sp[2]}" style="border: 1px solid #000000; padding: 6px 4px; color: #000000; font-size: 9pt; text-align: center; vertical-align: middle; background-color: #f2f2f2;">PEMINJAMAN RUANGAN</th>
				<th colspan="${sp[3]}" style="border: 1px solid #000000; padding: 6px 4px; color: #000000; font-size: 9pt; text-align: center; vertical-align: middle; background-color: #f2f2f2;">PEMINJAMAN MOBIL</th>
				<th colspan="${sp[4]}" style="border: 1px solid #000000; padding: 6px 4px; color: #000000; font-size: 9pt; text-align: center; vertical-align: middle; background-color: #f2f2f2;">LAYANAN KEBERSIHAN</th>
				<th colspan="${sp[5]}" style="border: 1px solid #000000; padding: 6px 4px; color: #000000; font-size: 9pt; text-align: center; vertical-align: middle; background-color: #f2f2f2;">TOTAL AKTIVITAS</th>
			</tr>
			${monthlySummaryRows}
			<tr style="background-color: #d9d9d9; font-weight: bold; height: 22pt;">
				<td colspan="${sp[0]}" style="border: 1px solid #000000; padding: 6px 8px; vertical-align: middle; text-align: left; color: #000000;">TOTAL KESELURUHAN</td>
				<td colspan="${sp[1]}" style="text-align: center; border: 1px solid #000000; padding: 6px 8px; vertical-align: middle; color: #000000;">${totalAtk}</td>
				<td colspan="${sp[2]}" style="text-align: center; border: 1px solid #000000; padding: 6px 8px; vertical-align: middle; color: #000000;">${totalRooms}</td>
				<td colspan="${sp[3]}" style="text-align: center; border: 1px solid #000000; padding: 6px 8px; vertical-align: middle; color: #000000;">${totalVehicles}</td>
				<td colspan="${sp[4]}" style="text-align: center; border: 1px solid #000000; padding: 6px 8px; vertical-align: middle; color: #000000;">${totalCleaning}</td>
				<td colspan="${sp[5]}" style="text-align: center; border: 1px solid #000000; padding: 6px 8px; vertical-align: middle; color: #000000;">${yearTotalCount.value || (totalAtk + totalRooms + totalVehicles + totalCleaning)}</td>
			</tr>

			<!-- PEMISAH ANTAR SECTION -->
			<tr style="height: 14pt;"><td colspan="${totalCols}" style="border: none; mso-border-alt: none; background-color: #ffffff;"></td></tr>
		`
	}

	// DETAIL TRANSAKSI SESUAI TAB (Hitam Putih Murni)
	let detailRowsHtml = ''
	if (currentTab.value === 'atk') {
		detailRowsHtml = items.map((it, i) => `
			<tr style="height: 21pt; background-color: #ffffff;">
				<td style="text-align: center; border: 1px solid #000000; padding: 5px 8px; vertical-align: middle; color: #000000;">${i + 1}</td>
				<td style="text-align: center; border: 1px solid #000000; padding: 5px 8px; vertical-align: middle; mso-number-format:'\\@'; color: #000000;">${it.date || '-'}</td>
				<td style="text-align: left; border: 1px solid #000000; padding: 5px 8px; vertical-align: middle; color: #000000;">${it.user_name || '-'}</td>
				<td style="text-align: center; border: 1px solid #000000; padding: 5px 8px; vertical-align: middle; mso-number-format:'\\@'; color: #000000;">${it.user_nip || '-'}</td>
				<td style="text-align: left; border: 1px solid #000000; padding: 5px 8px; vertical-align: middle; color: #000000;">${it.division || '-'}</td>
				<td style="text-align: left; border: 1px solid #000000; padding: 5px 8px; vertical-align: middle; color: #000000;">${it.items_summary || '-'}</td>
				<td style="text-align: left; border: 1px solid #000000; padding: 5px 8px; vertical-align: middle; color: #000000;">${it.request_note || '-'}</td>
				<td style="text-align: center; border: 1px solid #000000; padding: 5px 8px; vertical-align: middle; color: #000000;">${formatStatus(it.status)}</td>
			</tr>
		`).join('')
	} else if (currentTab.value === 'ruangan') {
		detailRowsHtml = items.map((it, i) => `
			<tr style="height: 21pt; background-color: #ffffff;">
				<td style="text-align: center; border: 1px solid #000000; padding: 5px 8px; vertical-align: middle; color: #000000;">${i + 1}</td>
				<td style="text-align: center; border: 1px solid #000000; padding: 5px 8px; vertical-align: middle; mso-number-format:'\\@'; color: #000000;">${it.date || '-'}</td>
				<td style="text-align: left; border: 1px solid #000000; padding: 5px 8px; vertical-align: middle; color: #000000;">${it.user_name || '-'}</td>
				<td style="text-align: center; border: 1px solid #000000; padding: 5px 8px; vertical-align: middle; mso-number-format:'\\@'; color: #000000;">${it.user_nip || '-'}</td>
				<td style="text-align: left; border: 1px solid #000000; padding: 5px 8px; vertical-align: middle; color: #000000;">${it.division || '-'}</td>
				<td style="text-align: left; border: 1px solid #000000; padding: 5px 8px; vertical-align: middle; color: #000000;">${it.room_name || '-'}</td>
				<td style="text-align: center; border: 1px solid #000000; padding: 5px 8px; vertical-align: middle; mso-number-format:'\\@'; color: #000000;">${it.room_code || '-'}</td>
				<td style="text-align: left; border: 1px solid #000000; padding: 5px 8px; vertical-align: middle; color: #000000;">${it.agenda || '-'}</td>
				<td style="text-align: center; border: 1px solid #000000; padding: 5px 8px; vertical-align: middle; color: #000000;">${it.start_time || '-'}</td>
				<td style="text-align: center; border: 1px solid #000000; padding: 5px 8px; vertical-align: middle; color: #000000;">${it.end_time || '-'}</td>
				<td style="text-align: center; border: 1px solid #000000; padding: 5px 8px; vertical-align: middle; color: #000000;">${formatStatus(it.status)}</td>
			</tr>
		`).join('')
	} else if (currentTab.value === 'mobil') {
		detailRowsHtml = items.map((it, i) => `
			<tr style="height: 21pt; background-color: #ffffff;">
				<td style="text-align: center; border: 1px solid #000000; padding: 5px 8px; vertical-align: middle; color: #000000;">${i + 1}</td>
				<td style="text-align: center; border: 1px solid #000000; padding: 5px 8px; vertical-align: middle; mso-number-format:'\\@'; color: #000000;">${it.date || '-'}</td>
				<td style="text-align: left; border: 1px solid #000000; padding: 5px 8px; vertical-align: middle; color: #000000;">${it.user_name || '-'}</td>
				<td style="text-align: center; border: 1px solid #000000; padding: 5px 8px; vertical-align: middle; mso-number-format:'\\@'; color: #000000;">${it.user_nip || '-'}</td>
				<td style="text-align: left; border: 1px solid #000000; padding: 5px 8px; vertical-align: middle; color: #000000;">${it.division || '-'}</td>
				<td style="text-align: left; border: 1px solid #000000; padding: 5px 8px; vertical-align: middle; color: #000000;">${it.vehicle_name || '-'}</td>
				<td style="text-align: center; border: 1px solid #000000; padding: 5px 8px; vertical-align: middle; mso-number-format:'\\@'; color: #000000;">${it.license_plate || '-'}</td>
				<td style="text-align: left; border: 1px solid #000000; padding: 5px 8px; vertical-align: middle; color: #000000;">${it.destination || '-'}</td>
				<td style="text-align: center; border: 1px solid #000000; padding: 5px 8px; vertical-align: middle; color: #000000;">${it.start_date || '-'}</td>
				<td style="text-align: center; border: 1px solid #000000; padding: 5px 8px; vertical-align: middle; color: #000000;">${it.end_date || '-'}</td>
				<td style="text-align: center; border: 1px solid #000000; padding: 5px 8px; vertical-align: middle; color: #000000;">${it.passengers_count ?? '-'}</td>
				<td style="text-align: center; border: 1px solid #000000; padding: 5px 8px; vertical-align: middle; color: #000000;">${formatStatus(it.status)}</td>
			</tr>
		`).join('')
	} else if (currentTab.value === 'cleaning') {
		detailRowsHtml = items.map((it, i) => `
			<tr style="height: 21pt; background-color: #ffffff;">
				<td style="text-align: center; border: 1px solid #000000; padding: 5px 8px; vertical-align: middle; color: #000000;">${i + 1}</td>
				<td style="text-align: center; border: 1px solid #000000; padding: 5px 8px; vertical-align: middle; mso-number-format:'\\@'; color: #000000;">${it.date || '-'}</td>
				<td style="text-align: left; border: 1px solid #000000; padding: 5px 8px; vertical-align: middle; color: #000000;">${it.user_name || '-'}</td>
				<td style="text-align: center; border: 1px solid #000000; padding: 5px 8px; vertical-align: middle; mso-number-format:'\\@'; color: #000000;">${it.user_nip || '-'}</td>
				<td style="text-align: left; border: 1px solid #000000; padding: 5px 8px; vertical-align: middle; color: #000000;">${it.division || '-'}</td>
				<td style="text-align: left; border: 1px solid #000000; padding: 5px 8px; vertical-align: middle; color: #000000;">${it.service_type || '-'}</td>
				<td style="text-align: left; border: 1px solid #000000; padding: 5px 8px; vertical-align: middle; color: #000000;">${it.location || '-'}</td>
				<td style="text-align: left; border: 1px solid #000000; padding: 5px 8px; vertical-align: middle; color: #000000;">${it.tool_name || '-'}</td>
				<td style="text-align: center; border: 1px solid #000000; padding: 5px 8px; vertical-align: middle; color: #000000;">${formatStatus(it.status)}</td>
			</tr>
		`).join('')
	}

	const detailTableThHtml = colConfigs.map(c => `
		<th style="border: 1px solid #000000; padding: 6px 4px; color: #000000; font-size: 9pt; text-align: center; vertical-align: middle; background-color: #f2f2f2;">${c.name}</th>
	`).join('')

	const fullExcelHtml = `
		<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
		<head>
			<meta charset="utf-8">
			<!--[if gte mso 9]>
			<xml>
				<x:ExcelWorkbook>
					<x:ExcelWorksheets>
						<x:ExcelWorksheet>
							<x:Name>Laporan ${tabName}</x:Name>
							<x:WorksheetOptions>
								<x:DisplayGridlines/>
								<x:Print>
									<x:ValidPrinterInfo/>
									<x:PaperSizeIndex>9</x:PaperSizeIndex>
									<x:HorizontalResolution>600</x:HorizontalResolution>
									<x:VerticalResolution>600</x:VerticalResolution>
								</x:Print>
							</x:WorksheetOptions>
						</x:ExcelWorksheet>
					</x:ExcelWorksheets>
				</x:ExcelWorkbook>
			</xml>
			<![endif]-->
			<style>
				body {
					font-family: Arial, Helvetica, sans-serif;
					font-size: 9pt;
					color: #000000;
					background-color: #ffffff;
				}
				table {
					border-collapse: collapse;
					mso-displayed-decimal-separator: "\\,";
					mso-displayed-thousand-separator: "\\.";
				}
				th, td {
					mso-font-charset: 0;
					font-family: Arial, Helvetica, sans-serif;
				}
			</style>
		</head>
		<body>
			<table border="1" cellspacing="0" cellpadding="0" style="border-collapse: collapse; font-family: Arial, sans-serif; font-size: 9pt; width: 100%;">
				<!-- DEFINISI LEBAR KOLOM PRESISI -->
				<colgroup>
					${colGroupHtml}
				</colgroup>

				<!-- KOP LAPORAN RESMI (HITAM PUTIH) -->
				<tr style="height: 24pt;">
					<td colspan="${totalCols}" style="text-align: center; font-size: 13pt; font-weight: bold; border: none; mso-border-alt: none; color: #000000; vertical-align: middle;">
						SISTEM INFORMASI MANAJEMEN PELAYANAN (SIMPEL)
					</td>
				</tr>
				<tr style="height: 20pt;">
					<td colspan="${totalCols}" style="text-align: center; font-size: 11pt; font-weight: bold; border: none; mso-border-alt: none; color: #000000; vertical-align: middle;">
						LAPORAN REKAPITULASI RESMI: ${tabName.toUpperCase()}
					</td>
				</tr>
				<tr style="height: 17pt;">
					<td colspan="${totalCols}" style="text-align: center; font-size: 9pt; border: none; mso-border-alt: none; color: #000000; vertical-align: middle;">
						Tahun: <b>${selectedYear.value}</b> &nbsp;|&nbsp; Periode: <b>${monthLabel}</b> &nbsp;|&nbsp; Rentang: <b>${dateRangeLabel}</b> &nbsp;|&nbsp; Unit: <b>${divName}</b>
					</td>
				</tr>
				<tr style="height: 15pt;">
					<td colspan="${totalCols}" style="text-align: right; font-size: 8pt; border: none; mso-border-alt: none; color: #555555; vertical-align: middle; font-style: italic;">
						Waktu Unduh: ${new Date().toLocaleString('id-ID')}
					</td>
				</tr>
				<tr style="height: 12pt;"><td colspan="${totalCols}" style="border: none; mso-border-alt: none; background-color: #ffffff;"></td></tr>

				<!-- SECTION REKAPITULASI / LEADERBOARD (JIKA ADA) -->
				${sectionsRowsHtml}

				<!-- SECTION UTAMA: RINCIAN TRANSAKSI AKTIVITAS -->
				<tr style="height: 24pt;">
					<td colspan="${totalCols}" style="background-color: #d9d9d9; font-weight: bold; font-size: 9.5pt; color: #000000; padding: 6px 10px; border: 1px solid #000000; vertical-align: middle; text-transform: uppercase;">
						${currentTab.value === 'atk' ? '3.' : '2.'} RINCIAN TRANSAKSI AKTIVITAS: ${tabName.toUpperCase()}
					</td>
				</tr>
				<tr style="background-color: #f2f2f2; color: #000000; font-weight: bold; text-align: center; height: 24pt;">
					${detailTableThHtml}
				</tr>
				<tbody>
					${detailRowsHtml || `<tr><td colspan="${totalCols}" style="text-align: center; border: 1px solid #000000; padding: 10px; vertical-align: middle; color: #000000;">Tidak ada catatan transaksi pada filter terpilih.</td></tr>`}
				</tbody>

				<!-- BLOK TANDA TANGAN RESMI -->
				<tr style="height: 16pt;"><td colspan="${totalCols}" style="border: none; mso-border-alt: none; background-color: #ffffff;"></td></tr>
				<tr>
					<td colspan="${Math.max(1, totalCols - 3)}" style="border: none; mso-border-alt: none;"></td>
					<td colspan="3" style="border: none; mso-border-alt: none; text-align: center; font-size: 9.5pt; color: #000000; vertical-align: middle;">Mengetahui,</td>
				</tr>
				<tr>
					<td colspan="${Math.max(1, totalCols - 3)}" style="border: none; mso-border-alt: none;"></td>
					<td colspan="3" style="border: none; mso-border-alt: none; text-align: center; font-size: 10pt; font-weight: bold; color: #000000; vertical-align: middle;">Kepala Bagian Umum</td>
				</tr>
				<tr style="height: 20pt;"><td colspan="${totalCols}" style="border: none; mso-border-alt: none;"></td></tr>
				<tr style="height: 20pt;"><td colspan="${totalCols}" style="border: none; mso-border-alt: none;"></td></tr>
				<tr>
					<td colspan="${Math.max(1, totalCols - 3)}" style="border: none; mso-border-alt: none;"></td>
					<td colspan="3" style="border: none; mso-border-alt: none; text-align: center; font-size: 10pt; font-weight: bold; text-decoration: underline; color: #000000; vertical-align: middle;">_______________________</td>
				</tr>
				<tr>
					<td colspan="${Math.max(1, totalCols - 3)}" style="border: none; mso-border-alt: none;"></td>
					<td colspan="3" style="border: none; mso-border-alt: none; text-align: center; font-size: 9pt; color: #000000; vertical-align: middle;">NIP. 198501012010011001</td>
				</tr>
			</table>
		</body>
		</html>
	`

	const blob = new Blob(['\ufeff' + fullExcelHtml], { type: 'application/vnd.ms-excel;charset=utf-8;' })
	const link = document.createElement('a')
	link.href = URL.createObjectURL(blob)
	link.download = `Laporan_SIMPEL_${currentTab.value}_${selectedYear.value}_${divName.replace(/\s+/g, '_')}.xls`
	link.click()
	URL.revokeObjectURL(link.href)
}

// =========================================================================
// DOWNLOAD / CETAK PDF (FORMAT FORMAL TABEL HITAM PUTIH RAPIH)
// =========================================================================
const downloadPdf = () => {
	const items = currentFilteredItems.value
	const tabName = activeTabTitle.value
	const divName = getSelectedDivisionName()
	const monthLabel = selectedMonth.value !== 'all' ? monthNames[selectedMonth.value - 1] : 'Seluruh Bulan (Jan - Des)'
	const dateRangeLabel = `${startDate.value || 'Seluruh Periode'} s/d ${endDate.value || 'Hari Ini'}`

	let visualSectionHtml = ''

	// KHUSUS TAB ATK: RENDER TABEL LEADERBOARD DALAM PDF (HITAM PUTIH)
	if (currentTab.value === 'atk') {
		// Top 10 Barang Diambil
		const topTakenRows = atkMostTakenFullList.value.slice(0, 10).map(it => `
			<tr>
				<td align="center" style="font-weight:bold;">${it.rank}</td>
				<td>${it.name}</td>
				<td align="center">${it.unit}</td>
				<td align="center" style="font-weight:bold;">${it.totalQty}</td>
				<td align="center">${it.borrowCount} kali</td>
			</tr>
		`).join('')

		// Top 10 Sisa Stok Kritis
		const lowStockRows = atkLowestStockFullList.value.slice(0, 10).map(it => `
			<tr>
				<td align="center" style="font-weight:bold;">${it.rank}</td>
				<td>${it.name}</td>
				<td align="center" style="font-weight:bold;">${it.stock}</td>
				<td align="center">${it.unit}</td>
				<td align="center">${getStockStatusText(it.stock)}</td>
			</tr>
		`).join('')

		visualSectionHtml = `
			<div style="display: flex; gap: 14px; margin-bottom: 14px; page-break-inside: avoid;">
				<div style="flex: 1;">
					<h3 class="section-title">1. Leaderboard: Top 10 Barang Paling Banyak Diambil</h3>
					<table class="bw-table">
						<thead>
							<tr>
								<th style="width: 32px;" class="center">POS</th>
								<th>NAMA BARANG ATK</th>
								<th style="width: 50px;" class="center">SATUAN</th>
								<th style="width: 70px;" class="center">TOTAL</th>
								<th style="width: 75px;" class="center">PENGAJUAN</th>
							</tr>
						</thead>
						<tbody>
							${topTakenRows || '<tr><td colspan="5" align="center">Tidak ada data.</td></tr>'}
						</tbody>
					</table>
				</div>
				<div style="flex: 1;">
					<h3 class="section-title">2. Radar: Top 10 Sisa Stok Paling Kritis (Gudang)</h3>
					<table class="bw-table">
						<thead>
							<tr>
								<th style="width: 32px;" class="center">URUT</th>
								<th>NAMA BARANG ATK</th>
								<th style="width: 60px;" class="center">SISA STOK</th>
								<th style="width: 50px;" class="center">SATUAN</th>
								<th style="width: 80px;" class="center">STATUS</th>
							</tr>
						</thead>
						<tbody>
							${lowStockRows || '<tr><td colspan="5" align="center">Tidak ada data.</td></tr>'}
						</tbody>
					</table>
				</div>
			</div>
		`
	} else {
		// DIAGRAM BATANG BULANAN MONOKROM HITAM PUTIH
		const maxVal = maxMonthlyCount.value
		const svgBars = monthlyChartBars.value.map(m => {
			const h = m.barHeight
			const y = 200 - h
			const barColor = m.displayCount > 0 ? '#111111' : '#e2e8f0'
			return `
				<g>
					<rect x="${m.x}" y="${y}" width="34" height="${Math.max(h, 2)}" rx="2" fill="${barColor}" stroke="#000000" stroke-width="0.5" />
					<text x="${m.x + 17}" y="${y - 6}" font-size="10" font-family="Arial, sans-serif" font-weight="bold" fill="#000000" text-anchor="middle">${m.displayCount}</text>
					<text x="${m.x + 17}" y="222" font-size="10" font-family="Arial, sans-serif" font-weight="600" fill="#000000" text-anchor="middle">${m.shortName}</text>
				</g>
			`
		}).join('')

		const svgGrid = chartGridLines.value.map(g => `
			<line x1="50" y1="${g.y - 25}" x2="940" y2="${g.y - 25}" stroke="#d4d4d4" stroke-width="1" stroke-dasharray="2" />
			<text x="40" y="${g.y - 21}" font-size="9" font-family="Arial, sans-serif" fill="#666666" text-anchor="end">${g.val}</text>
		`).join('')

		const printableSvg = `
			<svg width="100%" height="235" viewBox="0 0 960 235" style="background:#ffffff; border:1px solid #000000; margin: 6px 0 12px;">
				<g>${svgGrid}</g>
				<g>${svgBars}</g>
			</svg>
		`

		const monthlySummaryRows = monthlyStats.value.map(m => `
			<tr>
				<td><b>${m.fullName}</b></td>
				<td align="center">${m.atk}</td>
				<td align="center">${m.rooms}</td>
				<td align="center">${m.vehicles}</td>
				<td align="center">${m.cleaning}</td>
				<td align="center"><b>${m.total}</b></td>
			</tr>
		`).join('')

		const totalAtk = monthlyStats.value.reduce((a, b) => a + b.atk, 0)
		const totalRooms = monthlyStats.value.reduce((a, b) => a + b.rooms, 0)
		const totalVehicles = monthlyStats.value.reduce((a, b) => a + b.vehicles, 0)
		const totalCleaning = monthlyStats.value.reduce((a, b) => a + b.cleaning, 0)

		visualSectionHtml = `
			<div style="margin-bottom: 12px; page-break-inside: avoid;">
				<h3 class="section-title">Diagram Batang: Tren Aktivitas Bulanan (Jan - Des ${selectedYear.value})</h3>
				${printableSvg}
			</div>

			<h3 class="section-title">Tabel Rekapitulasi Aktivitas Bulanan Tahun ${selectedYear.value}</h3>
			<table class="bw-table">
				<thead>
					<tr>
						<th>Bulan</th>
						<th class="center">Peminjaman ATK</th>
						<th class="center">Peminjaman Ruangan</th>
						<th class="center">Peminjaman Mobil</th>
						<th class="center">Layanan Kebersihan</th>
						<th class="center">Total Aktivitas</th>
					</tr>
				</thead>
				<tbody>
					${monthlySummaryRows}
					<tr style="background:#ebebeb; font-weight:bold;">
						<td>TOTAL KESELURUHAN</td>
						<td align="center">${totalAtk}</td>
						<td align="center">${totalRooms}</td>
						<td align="center">${totalVehicles}</td>
						<td align="center">${totalCleaning}</td>
						<td align="center">${yearTotalCount.value}</td>
					</tr>
				</tbody>
			</table>
		`
	}

	// 2. Table Headers & Rows for Details
	let tableHeaders = ''
	let tableRows = ''

	if (currentTab.value === 'atk') {
		tableHeaders = '<th style="width:30px;" class="center">No</th><th style="width:75px;" class="center">Tanggal</th><th>Nama Pegawai</th><th style="width:70px;" class="center">NIP</th><th>Divisi</th><th>Barang & Jumlah</th><th>Catatan</th><th style="width:75px;" class="center">Status</th>'
		tableRows = items.map((it, i) => `<tr><td align="center">${i + 1}</td><td align="center">${it.date}</td><td>${it.user_name}</td><td align="center">${it.user_nip}</td><td>${it.division}</td><td>${it.items_summary}</td><td>${it.request_note || '-'}</td><td align="center">${formatStatus(it.status)}</td></tr>`).join('')
	} else if (currentTab.value === 'ruangan') {
		tableHeaders = '<th style="width:30px;" class="center">No</th><th style="width:75px;" class="center">Tanggal</th><th>Nama Pegawai</th><th style="width:70px;" class="center">NIP</th><th>Divisi</th><th>Ruangan</th><th>Agenda</th><th style="width:90px;" class="center">Waktu</th><th style="width:75px;" class="center">Status</th>'
		tableRows = items.map((it, i) => `<tr><td align="center">${i + 1}</td><td align="center">${it.date}</td><td>${it.user_name}</td><td align="center">${it.user_nip}</td><td>${it.division}</td><td>${it.room_name} (${it.room_code})</td><td>${it.agenda}</td><td align="center">${it.start_time} - ${it.end_time}</td><td align="center">${formatStatus(it.status)}</td></tr>`).join('')
	} else if (currentTab.value === 'mobil') {
		tableHeaders = '<th style="width:30px;" class="center">No</th><th style="width:75px;" class="center">Tanggal</th><th>Nama Pegawai</th><th style="width:70px;" class="center">NIP</th><th>Divisi</th><th>Kendaraan</th><th style="width:70px;" class="center">Plat</th><th>Tujuan</th><th style="width:90px;" class="center">Waktu</th><th style="width:75px;" class="center">Status</th>'
		tableRows = items.map((it, i) => `<tr><td align="center">${i + 1}</td><td align="center">${it.date}</td><td>${it.user_name}</td><td align="center">${it.user_nip}</td><td>${it.division}</td><td>${it.vehicle_name}</td><td align="center">${it.license_plate}</td><td>${it.destination}</td><td align="center">${it.start_date} - ${it.end_date}</td><td align="center">${formatStatus(it.status)}</td></tr>`).join('')
	} else if (currentTab.value === 'cleaning') {
		tableHeaders = '<th style="width:30px;" class="center">No</th><th style="width:75px;" class="center">Tanggal</th><th>Nama Pemohon</th><th style="width:70px;" class="center">NIP</th><th>Divisi</th><th>Jenis Layanan</th><th>Lokasi</th><th>Alat</th><th style="width:75px;" class="center">Status</th>'
		tableRows = items.map((it, i) => `<tr><td align="center">${i + 1}</td><td align="center">${it.date}</td><td>${it.user_name}</td><td align="center">${it.user_nip}</td><td>${it.division}</td><td>${it.service_type}</td><td>${it.location}</td><td>${it.tool_name}</td><td align="center">${formatStatus(it.status)}</td></tr>`).join('')
	}

	const printWindow = window.open('', '_blank', 'width=1000,height=850')
	if (!printWindow) return

	printWindow.document.write(`
		<!DOCTYPE html>
		<html>
		<head>
			<title>Laporan ${tabName} - SIMPEL</title>
			<style>
				@page { size: A4 portrait; margin: 12mm 14mm; }
				body { font-family: Arial, Helvetica, sans-serif; padding: 10px; color: #000000; line-height: 1.35; font-size: 9.5px; background: #ffffff; }
				.header { text-align: center; border-bottom: 3px double #000000; padding-bottom: 8px; margin-bottom: 12px; }
				.header h1 { margin: 0; font-size: 16px; color: #000000; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; }
				.header p { margin: 3px 0 0; font-size: 11px; color: #000000; font-weight: bold; }
				.meta-box { width: 100%; border-collapse: collapse; margin-bottom: 12px; }
				.meta-box td { border: 1px solid #000000; padding: 5px 8px; font-size: 9.5px; background: #ffffff; color: #000000; }
				.section-title { font-size: 10.5px; font-weight: bold; color: #000000; text-transform: uppercase; margin: 12px 0 5px; }
				table.bw-table { width: 100%; border-collapse: collapse; font-size: 9px; margin-top: 4px; margin-bottom: 12px; color: #000000; }
				table.bw-table th { background-color: #000000 !important; color: #ffffff !important; padding: 6px 5px; border: 1px solid #000000; text-align: left; font-weight: bold; text-transform: uppercase; font-size: 8.5px; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
				table.bw-table th.center, table.bw-table td.center { text-align: center; }
				table.bw-table td { padding: 5px; border: 1px solid #000000; color: #000000; background: #ffffff; }
				table.bw-table tr:nth-child(even) td { background-color: #f7f7f7 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
				.signature { margin-top: 24px; display: flex; justify-content: flex-end; page-break-inside: avoid; }
				.signature-box { text-align: center; width: 200px; font-size: 10px; color: #000000; }
				.signature-space { height: 50px; }
			</style>
		</head>
		<body>
			<div class="header">
				<h1>SISTEM INFORMASI MANAJEMEN PELAYANAN (SIMPEL)</h1>
				<p>LAPORAN REKAPITULASI RESMI: ${tabName.toUpperCase()}</p>
			</div>

			<table class="meta-box">
				<tr>
					<td style="width: 50%;">Unit / Divisi: <b>${divName}</b></td>
					<td style="width: 50%; text-align: right;">Periode: <b>${dateRangeLabel}</b></td>
				</tr>
				<tr>
					<td>Tahun Laporan: <b>${selectedYear.value}</b> (Bulan: <b>${monthLabel}</b>)</td>
					<td style="text-align: right;">Waktu Cetak: <b>${new Date().toLocaleString('id-ID')}</b></td>
				</tr>
			</table>

			${visualSectionHtml}

			<h3 class="section-title">
				${currentTab.value === 'atk' ? '3.' : '2.'} Rincian Transaksi Aktivitas: ${tabName}
			</h3>
			<table class="bw-table">
				<thead><tr>${tableHeaders}</tr></thead>
				<tbody>
					${tableRows || '<tr><td colspan="8" align="center" style="padding:15px; color:#666666;">Tidak ada catatan transaksi pada filter terpilih.</td></tr>'}
				</tbody>
			</table>

			<div class="signature">
				<div class="signature-box">
					<p>Mengetahui,<br><b>Kepala Bagian Umum</b></p>
					<div class="signature-space"></div>
					<p><b>_______________________</b><br>NIP. 198501012010011001</p>
				</div>
			</div>
		</body>
		</html>
	`)

	printWindow.document.close()
	printWindow.focus()
	setTimeout(() => {
		printWindow.print()
	}, 400)
}

onMounted(() => {
	if (typeof window !== 'undefined') {
		const savedTheme = localStorage.getItem('admin_theme')
		if (savedTheme) {
			isDark.value = savedTheme === 'dark'
		}
	}
	fetchDivisionsList()
	fetchReports()
	fetchAtkProducts()
	fetchVehiclesAndDrivers()
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
	transition: all 0.15s ease;
}
.bell-btn:hover {
	color: #ffffff;
	background: #1a2538;
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

/* ==================== FILTER & DATE CONTROLS ==================== */
.filter-panel {
	border-radius: 12px;
	background: #0f1623;
	border: 1px solid rgba(255, 255, 255, 0.08);
	padding: 18px 22px;
	margin-bottom: 22px;
	box-shadow: 0 4px 18px rgba(0, 0, 0, 0.3);
}
.filter-header {
	display: flex;
	align-items: center;
	justify-content: space-between;
	padding-bottom: 14px;
	border-bottom: 1px solid rgba(255, 255, 255, 0.06);
	flex-wrap: wrap;
	gap: 12px;
}
.filter-heading-left {
	display: flex;
	align-items: center;
	gap: 12px;
}
.filter-icon-box {
	display: grid;
	place-items: center;
	width: 34px;
	height: 34px;
	border-radius: 8px;
	background: rgba(16, 185, 129, 0.15);
	color: #34d399;
}
.svg-icon-filter {
	width: 18px;
	height: 18px;
}
.filter-heading-left h3 {
	margin: 0;
	font-size: 15px;
	color: #ffffff;
	font-weight: 700;
}
.filter-heading-left p {
	margin: 2px 0 0;
	color: #94a3b8;
	font-size: 11px;
}
.quick-presets {
	display: flex;
	gap: 6px;
}
.preset-btn {
	padding: 5px 12px;
	border-radius: 16px;
	background: #162030;
	border: 1px solid rgba(255, 255, 255, 0.08);
	color: #94a3b8;
	font-size: 11px;
	font-weight: 600;
	cursor: pointer;
	transition: all 0.15s ease;
}
.preset-btn:hover {
	color: #ffffff;
	background: #1e2d42;
}
.preset-btn.active {
	background: #10b981;
	color: #022c22;
	border-color: #10b981;
}

.filter-controls-row {
	display: flex;
	align-items: center;
	justify-content: space-between;
	padding-top: 14px;
	flex-wrap: wrap;
	gap: 16px;
}
.date-input-group {
	display: flex;
	align-items: center;
	gap: 10px;
	flex-wrap: wrap;
}
.date-input-group label {
	display: flex;
	flex-direction: column;
	gap: 4px;
	font-size: 10px;
	color: #94a3b8;
	font-weight: 600;
}
.date-input-group input[type="date"] {
	padding: 6px 10px;
	border-radius: 6px;
	background: #070a10;
	border: 1px solid rgba(255, 255, 255, 0.1);
	color: #f1f5f9;
	font-size: 11px;
	outline: none;
	color-scheme: dark;
}
.date-separator {
	color: #64748b;
	font-size: 11px;
	margin-top: 14px;
}
.btn-apply-filter {
	margin-top: 14px;
	padding: 8px 16px;
	border-radius: 6px;
	background: #1e2b3e;
	border: 1px solid rgba(255, 255, 255, 0.12);
	color: #ffffff;
	font-size: 11px;
	font-weight: 700;
	cursor: pointer;
	transition: all 0.15s ease;
}
.btn-apply-filter:hover {
	background: #10b981;
	color: #022c22;
	border-color: #10b981;
}

.export-actions {
	display: flex;
	gap: 10px;
}
.btn-export {
	display: flex;
	align-items: center;
	gap: 7px;
	padding: 8px 15px;
	border-radius: 8px;
	font-size: 11px;
	font-weight: 700;
	cursor: pointer;
	border: 0;
	transition: all 0.2s ease;
}
.btn-export.excel {
	background: #065f46;
	color: #ffffff;
	box-shadow: 0 2px 10px rgba(6, 95, 70, 0.4);
}
.btn-export.excel:hover {
	background: #047857;
	transform: translateY(-1px);
}
.btn-export.pdf {
	background: #b91c1c;
	color: #ffffff;
	box-shadow: 0 2px 10px rgba(185, 28, 28, 0.4);
}
.btn-export.pdf:hover {
	background: #dc2626;
	transform: translateY(-1px);
}
.svg-icon-export {
	width: 14px;
	height: 14px;
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

/* Filter Select Fields */
.filter-field-group {
	display: flex;
	flex-direction: column;
	gap: 4px;
}
.filter-field-group label {
	display: flex;
	flex-direction: column;
	gap: 4px;
	font-size: 10px;
	color: #94a3b8;
	font-weight: 600;
}
.select-with-icon {
	position: relative;
	display: flex;
	align-items: center;
}
.svg-field-icon {
	position: absolute;
	left: 10px;
	width: 14px;
	height: 14px;
	color: #34d399;
	pointer-events: none;
}
.filter-select {
	padding: 7px 12px 7px 32px;
	border-radius: 6px;
	background: #070a10;
	border: 1px solid rgba(255, 255, 255, 0.1);
	color: #f1f5f9;
	font-size: 11px;
	font-weight: 500;
	outline: none;
	cursor: pointer;
	min-width: 180px;
	transition: border-color 0.15s ease;
}
.filter-select.select-sm {
	padding-left: 12px;
	min-width: 120px;
}
.filter-select:focus {
	border-color: #10b981;
}
.btn-reset-filter {
	margin-top: 14px;
	padding: 8px 12px;
	border-radius: 6px;
	background: rgba(239, 68, 68, 0.15);
	border: 1px solid rgba(239, 68, 68, 0.3);
	color: #fca5a5;
	font-size: 11px;
	font-weight: 600;
	cursor: pointer;
	transition: all 0.15s ease;
}
.btn-reset-filter:hover {
	background: #ef4444;
	color: #ffffff;
}

/* ==================== DIAGRAM BATANG SEJAJAR KHUSUS ATK ==================== */
/* ==================== ATK LEADERBOARD PANEL ==================== */
.atk-leaderboard-panel {
	border-radius: 20px;
	background: #0b111c;
	border: 1px solid rgba(255, 255, 255, 0.08);
	box-shadow: 0 16px 40px rgba(0, 0, 0, 0.5);
	margin-bottom: 28px;
	overflow: hidden;
}
.atk-leaderboard-header {
	padding: 24px 26px 20px;
	border-bottom: 1px solid rgba(255, 255, 255, 0.06);
	background: linear-gradient(180deg, #111a28 0%, #0b111c 100%);
	display: flex;
	flex-direction: column;
	gap: 18px;
}
.lb-header-top {
	display: flex;
	align-items: center;
	justify-content: space-between;
	flex-wrap: wrap;
	gap: 16px;
}
.lb-header-info .title-with-pill {
	display: flex;
	align-items: center;
	gap: 10px;
	flex-wrap: wrap;
}
.cute-badge-pulse {
	display: inline-flex;
	align-items: center;
	padding: 4px 12px;
	border-radius: 999px;
	background: rgba(245, 158, 11, 0.15);
	color: #fbbf24;
	font-size: 10.5px;
	font-weight: 800;
	letter-spacing: 0.5px;
	border: 1px solid rgba(245, 158, 11, 0.35);
	box-shadow: 0 0 12px rgba(245, 158, 11, 0.2);
}
.lb-header-info h3 {
	margin: 0;
	font-size: 19px;
	font-weight: 800;
	color: #ffffff;
	letter-spacing: -0.3px;
}
.lb-header-info p {
	margin: 4px 0 0;
	font-size: 12.5px;
	color: #94a3b8;
}

/* View Mode Toggles */
.lb-view-mode-tabs {
	display: flex;
	align-items: center;
	background: rgba(255, 255, 255, 0.04);
	border: 1px solid rgba(255, 255, 255, 0.08);
	border-radius: 12px;
	padding: 4px;
	gap: 4px;
}
.lb-mode-btn {
	padding: 7px 14px;
	border-radius: 8px;
	background: transparent;
	border: none;
	color: #94a3b8;
	font-size: 11.5px;
	font-weight: 700;
	cursor: pointer;
	transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
	display: flex;
	align-items: center;
	gap: 6px;
}
.lb-mode-btn:hover {
	color: #ffffff;
	background: rgba(255, 255, 255, 0.06);
}
.lb-mode-btn.active {
	background: linear-gradient(135deg, #10b981 0%, #059669 100%);
	color: #ffffff;
	box-shadow: 0 4px 12px rgba(16, 185, 129, 0.35);
}

/* Date filter inside chart header */
.chart-date-filter-box {
	display: flex;
	align-items: center;
	justify-content: space-between;
	background: rgba(255, 255, 255, 0.025);
	border: 1px solid rgba(255, 255, 255, 0.07);
	border-radius: 12px;
	padding: 10px 16px;
	flex-wrap: wrap;
	gap: 12px;
}
.filter-box-label {
	display: flex;
	align-items: center;
	gap: 6px;
	font-size: 11px;
	font-weight: 700;
	color: #e2e8f0;
}
.date-controls-flex {
	display: flex;
	align-items: center;
	gap: 10px;
	flex-wrap: wrap;
}
.date-input-wrap {
	display: flex;
	align-items: center;
	gap: 6px;
	font-size: 11px;
	color: #94a3b8;
}
.input-chart-date {
	padding: 5px 8px;
	border-radius: 8px;
	background: #070a10;
	border: 1px solid rgba(255, 255, 255, 0.15);
	color: #f1f5f9;
	font-size: 11px;
	outline: none;
	transition: border-color 0.15s ease;
}
.input-chart-date:focus {
	border-color: #10b981;
}
.date-arrow {
	color: #64748b;
	font-size: 12px;
}
.preset-pills-row {
	display: flex;
	align-items: center;
	gap: 6px;
}
.preset-pills-row button {
	padding: 4px 10px;
	border-radius: 6px;
	background: rgba(255, 255, 255, 0.05);
	border: 1px solid rgba(255, 255, 255, 0.08);
	color: #94a3b8;
	font-size: 10.5px;
	font-weight: 600;
	cursor: pointer;
	transition: all 0.15s ease;
}
.preset-pills-row button:hover {
	background: rgba(255, 255, 255, 0.1);
	color: #ffffff;
}
.preset-pills-row button.active {
	background: #10b981;
	color: #ffffff;
	border-color: #10b981;
}
.btn-reset-dates {
	background: rgba(239, 68, 68, 0.15) !important;
	border-color: rgba(239, 68, 68, 0.3) !important;
	color: #fca5a5 !important;
}

/* ==================== LEADERBOARD MAIN GRID ==================== */
.atk-leaderboard-grid {
	display: grid;
	gap: 22px;
	padding: 24px;
}
.atk-leaderboard-grid.grid-both {
	grid-template-columns: repeat(2, 1fr);
}
.atk-leaderboard-grid.grid-taken,
.atk-leaderboard-grid.grid-stock {
	grid-template-columns: 1fr;
}
@media (max-width: 1080px) {
	.atk-leaderboard-grid.grid-both {
		grid-template-columns: 1fr;
	}
}

/* Card Styles */
.lb-card {
	border-radius: 16px;
	background: rgba(15, 23, 42, 0.6);
	border: 1px solid rgba(255, 255, 255, 0.07);
	display: flex;
	flex-direction: column;
	overflow: hidden;
	box-shadow: 0 4px 20px rgba(0, 0, 0, 0.25);
	backdrop-filter: blur(8px);
}
.lb-card-taken {
	border-top: 4px solid #10b981;
}
.lb-card-stock {
	border-top: 4px solid #f43f5e;
}
.lb-card-header {
	padding: 18px 20px;
	display: flex;
	align-items: center;
	justify-content: space-between;
	border-bottom: 1px solid rgba(255, 255, 255, 0.06);
	flex-wrap: wrap;
	gap: 12px;
	background: rgba(255, 255, 255, 0.015);
}
.lb-card-body {
	display: flex;
	flex-direction: column;
	flex: 1;
}
.card-title-group {
	display: flex;
	align-items: center;
	gap: 12px;
}
.icon-avatar-cute {
	position: relative;
	width: 40px;
	height: 40px;
	border-radius: 12px;
	display: grid;
	place-items: center;
	font-size: 19px;
	flex-shrink: 0;
}
.icon-avatar-cute.icon-emerald {
	background: rgba(16, 185, 129, 0.15);
	border: 1px solid rgba(16, 185, 129, 0.3);
}
.icon-avatar-cute.icon-rose {
	background: rgba(244, 63, 94, 0.15);
	border: 1px solid rgba(244, 63, 94, 0.3);
}
.pulse-ring {
	position: absolute;
	inset: -2px;
	border-radius: 14px;
	border: 1.5px solid rgba(16, 185, 129, 0.4);
	animation: pulseBorder 2.4s infinite;
	pointer-events: none;
}
.pulse-ring.red {
	border-color: rgba(244, 63, 94, 0.5);
}
@keyframes pulseBorder {
	0% { transform: scale(0.98); opacity: 0.8; }
	50% { transform: scale(1.06); opacity: 0; }
	100% { transform: scale(0.98); opacity: 0; }
}
.card-title-group h4 {
	margin: 0;
	font-size: 15px;
	font-weight: 800;
	color: #ffffff;
}
.card-title-group p {
	margin: 2px 0 0;
	font-size: 11.5px;
	color: #94a3b8;
}

/* Tier Selector Dropdown */
.tier-select-wrap {
	display: flex;
	align-items: center;
	gap: 6px;
}
.tier-label-text {
	font-size: 10.5px;
	color: #94a3b8;
	font-weight: 600;
}
.tier-select-modern {
	padding: 5px 10px;
	border-radius: 8px;
	background: #0f172a;
	border: 1px solid rgba(255, 255, 255, 0.15);
	color: #ffffff;
	font-size: 11px;
	font-weight: 600;
	cursor: pointer;
	outline: none;
	transition: all 0.15s ease;
}
.tier-select-modern.select-emerald:focus {
	border-color: #10b981;
	box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2);
}
.tier-select-modern.select-rose:focus {
	border-color: #f43f5e;
	box-shadow: 0 0 0 2px rgba(244, 63, 94, 0.2);
}

/* Sub-stats Banner */
.card-sub-stats {
	display: flex;
	justify-content: space-between;
	align-items: center;
	padding: 9px 20px;
	font-size: 11.5px;
	color: #94a3b8;
	border-bottom: 1px solid rgba(255, 255, 255, 0.04);
}
.card-sub-stats b {
	font-weight: 700;
	color: #ffffff;
}
.text-rose { color: #fb7185 !important; }
.text-amber { color: #fbbf24 !important; }
.text-yellow { color: #fde047 !important; }
.emerald-highlight { color: #34d399 !important; font-weight: 800; }

/* ==================== 3D PODIUM SECTION ==================== */
.lb-podium-wrap {
	display: flex;
	align-items: flex-end;
	justify-content: center;
	gap: 14px;
	padding: 30px 16px 12px;
	background: radial-gradient(circle at 50% 20%, rgba(255, 255, 255, 0.04) 0%, transparent 70%);
	border-bottom: 1px solid rgba(255, 255, 255, 0.06);
	min-height: 290px;
}
.podium-col {
	flex: 1;
	max-width: 140px;
	display: flex;
	flex-direction: column;
	align-items: center;
	text-align: center;
	position: relative;
	transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
}
.podium-col:hover {
	transform: translateY(-4px);
}
.podium-col.rank-1 {
	order: 2;
	max-width: 160px;
	z-index: 5;
}
.podium-col.rank-2 {
	order: 1;
	z-index: 3;
}
.podium-col.rank-3 {
	order: 3;
	z-index: 2;
}

/* Avatar & Badges */
.podium-avatar-wrap {
	position: relative;
	margin-bottom: 8px;
}
.podium-avatar {
	width: 58px;
	height: 58px;
	border-radius: 18px;
	overflow: hidden;
	background: #0f172a;
	display: flex;
	align-items: center;
	justify-content: center;
	position: relative;
	box-shadow: 0 8px 24px rgba(0, 0, 0, 0.5);
	transition: all 0.2s ease;
}
.podium-col.rank-1 .podium-avatar {
	width: 72px;
	height: 72px;
	border-radius: 22px;
}
.podium-img {
	width: 100%;
	height: 100%;
	object-fit: cover;
}
.podium-fallback-icon {
	font-size: 26px;
}
.podium-col.rank-1 .podium-fallback-icon {
	font-size: 34px;
}

/* Glow Badges */
.gold-glow {
	border: 2.5px solid #fbbf24;
	box-shadow: 0 0 25px rgba(251, 191, 36, 0.45);
}
.champion-pulse {
	animation: goldHalo 2.5s infinite alternate;
}
@keyframes goldHalo {
	0% { box-shadow: 0 0 15px rgba(251, 191, 36, 0.3); }
	100% { box-shadow: 0 0 32px rgba(251, 191, 36, 0.7); }
}
.silver-glow {
	border: 2px solid #cbd5e1;
	box-shadow: 0 0 18px rgba(203, 213, 225, 0.35);
}
.bronze-glow {
	border: 2px solid #d97706;
	box-shadow: 0 0 18px rgba(217, 119, 6, 0.35);
}
.danger-glow {
	border: 2.5px solid #f43f5e;
	box-shadow: 0 0 25px rgba(244, 63, 94, 0.5);
}
.danger-pulse {
	animation: dangerHalo 2s infinite alternate;
}
@keyframes dangerHalo {
	0% { box-shadow: 0 0 12px rgba(244, 63, 94, 0.3); }
	100% { box-shadow: 0 0 28px rgba(244, 63, 94, 0.8); }
}
.warning-glow {
	border: 2px solid #f59e0b;
	box-shadow: 0 0 18px rgba(245, 158, 11, 0.4);
}
.amber-glow {
	border: 2px solid #eab308;
	box-shadow: 0 0 18px rgba(234, 179, 8, 0.35);
}

/* Medals & Crown */
.podium-crown {
	position: absolute;
	top: -18px;
	left: 50%;
	transform: translateX(-50%);
	font-size: 22px;
	filter: drop-shadow(0 2px 6px rgba(0, 0, 0, 0.6));
	z-index: 10;
}
.podium-medal {
	position: absolute;
	top: -12px;
	left: 50%;
	transform: translateX(-50%);
	font-size: 18px;
	filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.5));
	z-index: 10;
}
.podium-rank-tag {
	position: absolute;
	bottom: -6px;
	left: 50%;
	transform: translateX(-50%);
	padding: 1.5px 7px;
	border-radius: 6px;
	font-size: 9.5px;
	font-weight: 800;
	white-space: nowrap;
	box-shadow: 0 2px 6px rgba(0, 0, 0, 0.4);
	z-index: 8;
}
.podium-rank-tag.gold {
	background: #fbbf24;
	color: #78350f;
}
.podium-rank-tag.silver {
	background: #cbd5e1;
	color: #1e293b;
}
.podium-rank-tag.bronze {
	background: #d97706;
	color: #fffbeb;
}
.podium-rank-tag.danger {
	background: #f43f5e;
	color: #ffffff;
}
.podium-rank-tag.warning {
	background: #f59e0b;
	color: #78350f;
}
.podium-rank-tag.amber {
	background: #eab308;
	color: #713f12;
}

/* Podium Meta Text */
.podium-meta {
	display: flex;
	flex-direction: column;
	align-items: center;
	margin-bottom: 12px;
	width: 100%;
}
.podium-meta h5 {
	margin: 2px 0;
	font-size: 11.5px;
	font-weight: 700;
	color: #ffffff;
	max-width: 120px;
	white-space: nowrap;
	overflow: hidden;
	text-overflow: ellipsis;
}
.podium-col.rank-1 .podium-meta h5 {
	font-size: 13px;
	max-width: 140px;
	color: #fef08a;
}
.podium-stat {
	font-size: 13px;
	font-weight: 700;
}
.podium-col.rank-1 .podium-stat {
	font-size: 15px;
}
.gold-text { color: #fbbf24; }
.silver-text { color: #e2e8f0; }
.bronze-text { color: #fb923c; }
.podium-sub {
	font-size: 9.5px;
	color: #64748b;
}
.podium-status-tag {
	font-size: 9px;
	font-weight: 700;
	padding: 1px 6px;
	border-radius: 4px;
	margin-top: 2px;
}

/* Pedestal Blocks */
.podium-pedestal {
	width: 100%;
	border-radius: 12px 12px 0 0;
	display: flex;
	flex-direction: column;
	align-items: center;
	justify-content: flex-start;
	padding-top: 10px;
	position: relative;
	box-shadow: inset 0 2px 6px rgba(255, 255, 255, 0.2);
}
.pedestal-gold {
	height: 115px;
	background: linear-gradient(180deg, rgba(245, 158, 11, 0.3) 0%, rgba(180, 83, 9, 0.15) 100%);
	border: 1px solid rgba(245, 158, 11, 0.4);
	border-bottom: none;
}
.pedestal-silver {
	height: 85px;
	background: linear-gradient(180deg, rgba(148, 163, 184, 0.25) 0%, rgba(71, 85, 105, 0.15) 100%);
	border: 1px solid rgba(148, 163, 184, 0.35);
	border-bottom: none;
}
.pedestal-bronze {
	height: 65px;
	background: linear-gradient(180deg, rgba(217, 119, 6, 0.25) 0%, rgba(146, 64, 14, 0.15) 100%);
	border: 1px solid rgba(217, 119, 6, 0.35);
	border-bottom: none;
}
.pedestal-stock-1 {
	height: 115px;
	background: linear-gradient(180deg, rgba(244, 63, 94, 0.3) 0%, rgba(159, 18, 57, 0.15) 100%);
	border: 1px solid rgba(244, 63, 94, 0.4);
	border-bottom: none;
}
.pedestal-stock-2 {
	height: 85px;
	background: linear-gradient(180deg, rgba(245, 158, 11, 0.25) 0%, rgba(180, 83, 9, 0.15) 100%);
	border: 1px solid rgba(245, 158, 11, 0.35);
	border-bottom: none;
}
.pedestal-stock-3 {
	height: 65px;
	background: linear-gradient(180deg, rgba(234, 179, 8, 0.25) 0%, rgba(161, 98, 7, 0.15) 100%);
	border: 1px solid rgba(234, 179, 8, 0.35);
	border-bottom: none;
}
.pedestal-num {
	font-size: 26px;
	font-weight: 900;
	line-height: 1;
	opacity: 0.9;
	color: #ffffff;
}
.pedestal-gold .pedestal-num { color: #fde047; }
.pedestal-silver .pedestal-num { color: #f1f5f9; }
.pedestal-bronze .pedestal-num { color: #fdba74; }
.pedestal-stock-1 .pedestal-num { color: #fca5a5; }
.pedestal-label {
	font-size: 8px;
	font-weight: 800;
	letter-spacing: 0.5px;
	opacity: 0.7;
	color: #ffffff;
	margin-top: 2px;
}

/* ==================== RANKED LIST TABLE ==================== */
.lb-table-wrap {
	display: flex;
	flex-direction: column;
	padding: 14px 18px 18px;
}
.lb-table-header {
	display: grid;
	grid-template-columns: 46px 1fr 150px 100px;
	padding: 8px 14px;
	font-size: 10px;
	font-weight: 800;
	color: #64748b;
	letter-spacing: 0.5px;
	border-bottom: 1px solid rgba(255, 255, 255, 0.06);
	align-items: center;
}
.th-score {
	text-align: right;
}
.lb-list-body {
	display: flex;
	flex-direction: column;
	gap: 6px;
	margin-top: 8px;
}
.lb-row {
	display: grid;
	grid-template-columns: 46px 1fr 150px 100px;
	align-items: center;
	padding: 10px 14px;
	border-radius: 12px;
	background: rgba(255, 255, 255, 0.02);
	border: 1px solid rgba(255, 255, 255, 0.04);
	transition: all 0.2s ease;
}
.lb-row:hover {
	background: rgba(255, 255, 255, 0.05);
	border-color: rgba(255, 255, 255, 0.1);
	transform: translateX(3px);
}
.lb-row-danger {
	border-color: rgba(239, 68, 68, 0.25);
	background: rgba(239, 68, 68, 0.04);
}
.lb-row-warning {
	border-color: rgba(245, 158, 11, 0.2);
}

/* Rank column */
.lb-col-rank {
	display: flex;
	align-items: center;
}
.lb-rank-badge {
	font-size: 11px;
	font-weight: 800;
	padding: 2px 7px;
	border-radius: 6px;
	display: inline-flex;
	align-items: center;
	justify-content: center;
	min-width: 26px;
}
.rank-gold { background: rgba(251, 191, 36, 0.2); color: #fbbf24; border: 1px solid rgba(251, 191, 36, 0.4); }
.rank-silver { background: rgba(203, 213, 225, 0.2); color: #cbd5e1; border: 1px solid rgba(203, 213, 225, 0.4); }
.rank-bronze { background: rgba(217, 119, 6, 0.2); color: #fb923c; border: 1px solid rgba(217, 119, 6, 0.4); }
.rank-default { background: rgba(255, 255, 255, 0.06); color: #94a3b8; }
.rank-danger-pulse {
	background: rgba(239, 68, 68, 0.25);
	color: #fca5a5;
	border: 1px solid rgba(239, 68, 68, 0.5);
	animation: pulseDanger 1.8s infinite;
}
.rank-warning { background: rgba(245, 158, 11, 0.2); color: #fde047; border: 1px solid rgba(245, 158, 11, 0.4); }
.rank-amber { background: rgba(234, 179, 8, 0.2); color: #facc15; border: 1px solid rgba(234, 179, 8, 0.4); }

/* Item Info Column */
.lb-col-item {
	display: flex;
	align-items: center;
	gap: 12px;
	min-width: 0;
	padding-right: 12px;
}
.lb-item-thumb {
	width: 36px;
	height: 36px;
	border-radius: 10px;
	overflow: hidden;
	background: #0f172a;
	border: 1px solid rgba(255, 255, 255, 0.1);
	display: flex;
	align-items: center;
	justify-content: center;
	flex-shrink: 0;
}
.lb-thumb-img {
	width: 100%;
	height: 100%;
	object-fit: cover;
}
.lb-fallback-icon {
	font-size: 18px;
}
.lb-item-text {
	display: flex;
	flex-direction: column;
	min-width: 0;
	overflow: hidden;
}
.lb-item-name {
	font-size: 12.5px;
	font-weight: 700;
	color: #ffffff;
	white-space: nowrap;
	overflow: hidden;
	text-overflow: ellipsis;
}
.lb-item-unit {
	font-size: 10.5px;
	color: #64748b;
}

/* Progress bar column */
.lb-col-progress {
	display: flex;
	align-items: center;
	gap: 10px;
	padding-right: 14px;
}
.lb-progress-bar-wrap {
	flex: 1;
	height: 8px;
	background: rgba(255, 255, 255, 0.06);
	border-radius: 999px;
	overflow: hidden;
}
.lb-progress-bar-fill {
	height: 100%;
	border-radius: 999px;
	transition: width 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
}
.fill-emerald {
	background: linear-gradient(90deg, #10b981 0%, #34d399 100%);
}
.fill-danger {
	background: linear-gradient(90deg, #be123c 0%, #f43f5e 100%);
}
.fill-warning {
	background: linear-gradient(90deg, #d97706 0%, #f59e0b 100%);
}
.fill-amber {
	background: linear-gradient(90deg, #ca8a04 0%, #eab308 100%);
}
.fill-safe {
	background: linear-gradient(90deg, #059669 0%, #10b981 100%);
}
.lb-progress-percent {
	font-size: 10.5px;
	font-weight: 700;
	color: #94a3b8;
	min-width: 36px;
	text-align: right;
}

/* Score / Action column */
.lb-col-score {
	display: flex;
	flex-direction: column;
	align-items: flex-end;
	text-align: right;
}
.lb-score-main {
	font-size: 14px;
	font-weight: 800;
	color: #ffffff;
}
.lb-score-unit {
	font-size: 10px;
	color: #64748b;
}
.lb-status-pill {
	display: inline-block;
	padding: 2.5px 8px;
	border-radius: 6px;
	font-size: 10.5px;
	font-weight: 800;
	white-space: nowrap;
}
.pill-danger {
	background: rgba(239, 68, 68, 0.2);
	color: #fca5a5;
	border: 1px solid rgba(239, 68, 68, 0.4);
}
.pill-warning {
	background: rgba(245, 158, 11, 0.2);
	color: #fde047;
	border: 1px solid rgba(245, 158, 11, 0.4);
}
.pill-amber {
	background: rgba(234, 179, 8, 0.2);
	color: #fef08a;
	border: 1px solid rgba(234, 179, 8, 0.4);
}
.pill-safe {
	background: rgba(16, 185, 129, 0.2);
	color: #6ee7b7;
	border: 1px solid rgba(16, 185, 129, 0.4);
}

.chart-empty-state {
	text-align: center;
	padding: 50px 10px;
	color: #64748b;
	font-size: 12.5px;
	width: 100%;
}
.empty-emoji {
	display: block;
	font-size: 34px;
	margin-bottom: 8px;
}

/* Responsive Table */
@media (max-width: 680px) {
	.lb-table-header {
		grid-template-columns: 36px 1fr 85px;
	}
	.th-progress {
		display: none;
	}
	.lb-row {
		grid-template-columns: 36px 1fr 85px;
	}
	.lb-col-progress {
		display: none;
	}
}

/* ==================== DIAGRAM BATANG BULANAN PANEL ==================== */
.chart-panel {
	border-radius: 14px;
	background: #0b111c;
	border: 1px solid rgba(255, 255, 255, 0.08);
	box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
	margin-bottom: 24px;
	overflow: hidden;
}
.chart-header {
	display: flex;
	align-items: center;
	justify-content: space-between;
	padding: 20px 24px 16px;
	border-bottom: 1px solid rgba(255, 255, 255, 0.06);
	background: linear-gradient(180deg, #101927 0%, #0b111c 100%);
	flex-wrap: wrap;
	gap: 14px;
}
.chart-heading-left {
	display: flex;
	align-items: center;
	gap: 12px;
}
.chart-icon-box {
	display: grid;
	place-items: center;
	width: 38px;
	height: 38px;
	border-radius: 10px;
	background: rgba(16, 185, 129, 0.15);
	color: #34d399;
}
.svg-icon-chart {
	width: 20px;
	height: 20px;
}
.chart-heading-left h3 {
	margin: 0;
	font-size: 17px;
	font-weight: 800;
	color: #ffffff;
}
.chart-heading-left p {
	margin: 3px 0 0;
	color: #94a3b8;
	font-size: 12px;
}
.badge-year {
	display: inline-block;
	padding: 2px 8px;
	border-radius: 12px;
	background: rgba(56, 189, 248, 0.15);
	color: #38bdf8;
	font-size: 10px;
	font-weight: 700;
	border: 1px solid rgba(56, 189, 248, 0.3);
}
.badge-active-division {
	display: inline-block;
	padding: 2px 8px;
	border-radius: 12px;
	background: rgba(16, 185, 129, 0.15);
	color: #34d399;
	font-size: 10px;
	font-weight: 700;
	border: 1px solid rgba(16, 185, 129, 0.3);
}
.badge-active-month {
	display: inline-block;
	padding: 2px 8px;
	border-radius: 12px;
	background: rgba(251, 191, 36, 0.15);
	color: #fbbf24;
	font-size: 10px;
	font-weight: 700;
	border: 1px solid rgba(251, 191, 36, 0.3);
}
.chart-actions-right {
	display: flex;
	align-items: center;
	gap: 12px;
	flex-wrap: wrap;
}
.chart-mode-pill {
	display: flex;
	background: #141f30;
	border-radius: 20px;
	padding: 3px;
	border: 1px solid rgba(255, 255, 255, 0.08);
}
.chart-mode-pill button {
	padding: 5px 12px;
	border-radius: 16px;
	background: none;
	border: 0;
	color: #94a3b8;
	font-size: 11px;
	font-weight: 600;
	cursor: pointer;
	transition: all 0.15s ease;
}
.chart-mode-pill button.active {
	background: #10b981;
	color: #022c22;
}
.btn-chart-print {
	display: flex;
	align-items: center;
	gap: 6px;
	padding: 7px 14px;
	border-radius: 8px;
	background: #1e293b;
	border: 1px solid rgba(255, 255, 255, 0.1);
	color: #f1f5f9;
	font-size: 11px;
	font-weight: 600;
	cursor: pointer;
	transition: all 0.15s ease;
}
.btn-chart-print:hover {
	background: #334155;
	color: #38bdf8;
}
.svg-icon-btn {
	width: 14px;
	height: 14px;
}

/* Chart Canvas Area */
.chart-body {
	position: relative;
	padding: 20px 24px 18px;
}
.svg-chart-wrapper {
	position: relative;
	width: 100%;
	overflow-x: auto;
}
.monthly-bar-svg {
	width: 100%;
	height: 250px;
	display: block;
	overflow: visible;
}
.grid-line {
	stroke: rgba(255, 255, 255, 0.08);
	stroke-dasharray: 4;
}
.y-axis-label {
	font-size: 10px;
	fill: #64748b;
	text-anchor: end;
	font-family: inherit;
}
.bar-group {
	cursor: pointer;
	transition: opacity 0.2s ease;
}
.bar-group:hover .bar-rect {
	filter: brightness(1.25) drop-shadow(0 0 10px rgba(52, 211, 153, 0.5));
}
.bar-hover-zone {
	fill: transparent;
}
.bar-rect {
	transition: height 0.3s cubic-bezier(0.4, 0, 0.2, 1), y 0.3s cubic-bezier(0.4, 0, 0.2, 1), filter 0.2s ease;
}
.bar-val-text {
	font-size: 10px;
	font-weight: 700;
	fill: #64748b;
	text-anchor: middle;
	font-family: inherit;
}
.bar-val-text.has-data {
	fill: #f1f5f9;
}
.x-axis-label {
	font-size: 11px;
	font-weight: 600;
	fill: #94a3b8;
	text-anchor: middle;
	font-family: inherit;
	transition: fill 0.2s ease;
}
.x-axis-label.active-month,
.bar-group:hover .x-axis-label {
	fill: #34d399;
	font-weight: 800;
}
.bar-selected .bar-rect {
	filter: drop-shadow(0 0 12px rgba(251, 191, 36, 0.6));
}

/* Floating Tooltip */
.chart-tooltip-floating {
	position: absolute;
	z-index: 30;
	pointer-events: none;
	background: #111a28;
	border: 1px solid rgba(52, 211, 153, 0.3);
	box-shadow: 0 10px 25px rgba(0, 0, 0, 0.6);
	border-radius: 8px;
	padding: 10px 14px;
	min-width: 200px;
	transition: left 0.1s ease, top 0.1s ease;
}
.tooltip-header {
	display: flex;
	align-items: center;
	justify-content: space-between;
	border-bottom: 1px solid rgba(255, 255, 255, 0.08);
	padding-bottom: 6px;
	margin-bottom: 8px;
}
.tooltip-header strong {
	font-size: 12px;
	color: #ffffff;
}
.tooltip-badge {
	font-size: 10px;
	font-weight: 700;
	background: rgba(16, 185, 129, 0.2);
	color: #34d399;
	padding: 2px 6px;
	border-radius: 4px;
}
.tooltip-breakdown {
	display: flex;
	flex-direction: column;
	gap: 4px;
	font-size: 11px;
}
.tooltip-row {
	display: flex;
	justify-content: space-between;
	gap: 12px;
}
.tooltip-tip {
	margin-top: 8px;
	padding-top: 6px;
	border-top: 1px dashed rgba(255, 255, 255, 0.08);
	font-size: 9px;
	color: #94a3b8;
	font-style: italic;
}

/* Legend & KPI Row */
.chart-summary-row {
	display: flex;
	align-items: center;
	justify-content: space-between;
	margin-top: 16px;
	padding-top: 16px;
	border-top: 1px solid rgba(255, 255, 255, 0.06);
	flex-wrap: wrap;
	gap: 16px;
}
.chart-legend-box {
	display: flex;
	align-items: center;
	gap: 14px;
}
.legend-item {
	display: flex;
	align-items: center;
	gap: 6px;
	font-size: 11px;
	color: #64748b;
	font-weight: 600;
	transition: color 0.15s ease;
}
.legend-item.legend-active {
	color: #cbd5e1;
}
.legend-dot {
	width: 9px;
	height: 9px;
	border-radius: 50%;
}
.legend-dot.green { background: #10b981; }
.legend-dot.cyan { background: #38bdf8; }
.legend-dot.amber { background: #fbbf24; }
.legend-dot.purple { background: #c084fc; }

.chart-kpi-grid {
	display: flex;
	align-items: center;
	gap: 14px;
	flex-wrap: wrap;
}
.kpi-card {
	background: #070d16;
	border: 1px solid rgba(255, 255, 255, 0.06);
	border-radius: 8px;
	padding: 8px 14px;
	min-width: 120px;
}
.kpi-card small {
	display: block;
	font-size: 10px;
	color: #64748b;
}
.kpi-card strong {
	display: block;
	font-size: 13px;
	color: #ffffff;
	margin-top: 2px;
}
.truncate-text {
	max-width: 140px;
	white-space: nowrap;
	overflow: hidden;
	text-overflow: ellipsis;
}

/* ==================== CORE: TABLE PANEL ==================== */
.crypto-panel {
	border-radius: 14px;
	background: #0b111c;
	border: 1px solid rgba(255, 255, 255, 0.08);
	box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
	overflow: hidden;
}

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
}
.spinning {
	display: inline-block;
	animation: spin 1s linear infinite;
}
@keyframes spin {
	100% { transform: rotate(360deg); }
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

/* ========================================================================= */
/* VEHICLE FILTER TOOLBAR STYLING                                           */
/* ========================================================================= */
.vehicle-filter-toolbar {
	margin: 16px 24px;
	padding: 16px 20px;
	background: rgba(15, 23, 42, 0.75);
	border: 1px solid rgba(245, 158, 11, 0.25);
	border-radius: 16px;
	box-shadow: 0 4px 20px rgba(0, 0, 0, 0.25);
}
.v-toolbar-header {
	display: flex;
	align-items: center;
	justify-content: space-between;
	flex-wrap: wrap;
	gap: 12px;
	margin-bottom: 14px;
	padding-bottom: 12px;
	border-bottom: 1px solid rgba(255, 255, 255, 0.07);
}
.v-toolbar-badge {
	display: inline-block;
	padding: 3px 8px;
	border-radius: 6px;
	background: rgba(245, 158, 11, 0.15);
	border: 1px solid rgba(245, 158, 11, 0.35);
	color: #fbbf24;
	font-size: 10px;
	font-weight: 800;
	letter-spacing: 0.5px;
	margin-bottom: 4px;
}
.v-toolbar-heading {
	font-size: 14px;
	font-weight: 700;
	color: #ffffff;
	margin: 0;
}
.v-toolbar-desc {
	font-size: 11px;
	color: #94a3b8;
	margin: 2px 0 0 0;
}
.v-toolbar-actions {
	display: flex;
	align-items: center;
	gap: 10px;
	flex-wrap: wrap;
}
.v-result-counter {
	font-size: 11px;
	color: #cbd5e1;
	background: rgba(255, 255, 255, 0.05);
	padding: 5px 10px;
	border-radius: 8px;
	border: 1px solid rgba(255, 255, 255, 0.08);
}
.v-result-counter strong {
	color: #fbbf24;
}
.btn-reset-vfilter {
	display: inline-flex;
	align-items: center;
	gap: 4px;
	padding: 5px 12px;
	border-radius: 8px;
	background: rgba(239, 68, 68, 0.15);
	border: 1px solid rgba(239, 68, 68, 0.35);
	color: #fca5a5;
	font-size: 11px;
	font-weight: 600;
	cursor: pointer;
	transition: all 0.2s ease;
}
.btn-reset-vfilter:hover {
	background: rgba(239, 68, 68, 0.25);
	color: #ffffff;
}
.v-toolbar-controls {
	display: grid;
	grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
	gap: 14px;
	align-items: flex-end;
}
.v-control-group {
	display: flex;
	flex-direction: column;
	gap: 5px;
}
.v-control-label {
	font-size: 11px;
	font-weight: 600;
	color: #e2e8f0;
	display: block;
}
.v-control-select {
	width: 100%;
	padding: 8px 12px;
	background: #090e17;
	border: 1px solid rgba(255, 255, 255, 0.12);
	border-radius: 10px;
	color: #f8fafc;
	font-size: 12px;
	font-weight: 500;
	outline: none;
	transition: border-color 0.2s;
}
.v-control-select:focus {
	border-color: #f59e0b;
}
.v-date-range-box {
	display: flex;
	align-items: center;
	gap: 6px;
}
.v-control-date-input {
	flex: 1;
	min-width: 0;
	padding: 7px 10px;
	background: #090e17;
	border: 1px solid rgba(255, 255, 255, 0.12);
	border-radius: 10px;
	color: #f8fafc;
	font-size: 11px;
	font-family: inherit;
	outline: none;
	transition: border-color 0.2s;
}
.v-control-date-input:focus {
	border-color: #f59e0b;
}
.v-date-sep {
	font-size: 10px;
	color: #64748b;
	font-weight: 600;
	flex-shrink: 0;
}

/* TABLE STYLING */
.crypto-table-wrap {
	overflow-x: auto;
}
.crypto-table {
	width: 100%;
	min-width: 900px;
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
.th-num, .td-num {
	width: 44px;
	color: #64748b;
	font-family: monospace;
	text-align: center;
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

.text-user {
	color: #ffffff;
	font-size: 13px;
	font-weight: 700;
}
.badge-subtle {
	display: inline-block;
	padding: 3px 8px;
	border-radius: 6px;
	background: #131c2b;
	color: #cbd5e1;
	font-size: 11px;
}
.text-highlight {
	color: #e2e8f0;
	font-weight: 600;
}
.text-note {
	color: #94a3b8;
	font-style: italic;
	font-size: 11px;
}

/* Status Badges */
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
.status-green .status-dot { background: #10b981; }

.status-blue {
	background: rgba(14, 165, 233, 0.12);
	border: 1px solid rgba(14, 165, 233, 0.35);
	color: #38bdf8;
}
.status-blue .status-dot { background: #0ea5e9; }

.status-amber {
	background: rgba(245, 158, 11, 0.12);
	border: 1px solid rgba(245, 158, 11, 0.35);
	color: #fbbf24;
}
.status-amber .status-dot { background: #f59e0b; }

.status-red {
	background: rgba(239, 68, 68, 0.12);
	border: 1px solid rgba(239, 68, 68, 0.35);
	color: #f87171;
}
.status-red .status-dot { background: #ef4444; }

/* Approval Action Buttons in Table */
.th-action {
	min-width: 150px;
	text-align: center;
}
.action-buttons-wrap {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	gap: 6px;
}
.btn-approve-action {
	background: rgba(16, 185, 129, 0.15);
	border: 1px solid rgba(16, 185, 129, 0.5);
	color: #34d399;
	padding: 5px 11px;
	border-radius: 8px;
	font-weight: 700;
	font-size: 11px;
	cursor: pointer;
	display: inline-flex;
	align-items: center;
	gap: 4px;
	transition: all 0.2s ease;
}
.btn-approve-action:hover:not(:disabled) {
	background: #10b981;
	color: #ffffff;
	box-shadow: 0 0 12px rgba(16, 185, 129, 0.4);
	transform: translateY(-1px);
}
.btn-reject-action {
	background: rgba(244, 63, 94, 0.12);
	border: 1px solid rgba(244, 63, 94, 0.4);
	color: #fb7185;
	padding: 5px 11px;
	border-radius: 8px;
	font-weight: 700;
	font-size: 11px;
	cursor: pointer;
	display: inline-flex;
	align-items: center;
	gap: 4px;
	transition: all 0.2s ease;
}
.btn-reject-action:hover:not(:disabled) {
	background: #f43f5e;
	color: #ffffff;
	box-shadow: 0 0 12px rgba(244, 63, 94, 0.4);
	transform: translateY(-1px);
}
.btn-approve-action:disabled,
.btn-reject-action:disabled {
	opacity: 0.5;
	cursor: not-allowed;
}
.badge-status-approved {
	display: inline-flex;
	align-items: center;
	gap: 4px;
	padding: 4px 10px;
	border-radius: 20px;
	background: rgba(16, 185, 129, 0.12);
	border: 1px solid rgba(16, 185, 129, 0.35);
	color: #34d399;
	font-size: 11px;
	font-weight: 700;
}
.badge-status-rejected {
	display: inline-flex;
	align-items: center;
	gap: 4px;
	padding: 4px 10px;
	border-radius: 20px;
	background: rgba(244, 63, 94, 0.12);
	border: 1px solid rgba(244, 63, 94, 0.35);
	color: #fb7185;
	font-size: 11px;
	font-weight: 700;
}
.spinner-sm {
	width: 11px;
	height: 11px;
	border: 2px solid currentColor;
	border-top-color: transparent;
	border-radius: 50%;
	display: inline-block;
	animation: spin-sm 0.6s linear infinite;
}
@keyframes spin-sm {
	to { transform: rotate(360deg); }
}

/* Empty State (Explicitly requested for clean connected database) */
.empty-cell {
	padding: 56px 24px;
	text-align: center;
}
.empty-state {
	display: flex;
	flex-direction: column;
	align-items: center;
	max-width: 580px;
	margin: auto;
	gap: 8px;
}
.empty-icon-box {
	display: grid;
	place-items: center;
	width: 56px;
	height: 56px;
	border-radius: 14px;
	background: rgba(255, 255, 255, 0.04);
	border: 1px solid rgba(255, 255, 255, 0.08);
	color: #64748b;
	margin-bottom: 6px;
}
.svg-icon-empty {
	width: 28px;
	height: 28px;
}
.empty-state h4 {
	margin: 0;
	font-size: 16px;
	font-weight: 700;
	color: #ffffff;
}
.empty-state p {
	margin: 0;
	color: #94a3b8;
	font-size: 12px;
	line-height: 1.6;
}
.text-emerald {
	color: #34d399;
	font-weight: 600;
}
.empty-hint {
	display: block;
	margin-top: 10px;
	padding: 8px 14px;
	border-radius: 8px;
	background: rgba(16, 185, 129, 0.08);
	border: 1px dashed rgba(16, 185, 129, 0.3);
	color: #6ee7b7;
	font-size: 11px;
}

/* Spinner */
.spinner {
	width: 32px;
	height: 32px;
	border: 3px solid rgba(52, 211, 153, 0.15);
	border-top-color: #34d399;
	border-radius: 50%;
	animation: spin 0.8s linear infinite;
	margin-bottom: 8px;
}

/* Footer */
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
.footer-db-status {
	display: flex;
	align-items: center;
	gap: 6px;
	color: #34d399;
	font-size: 11px;
	font-weight: 600;
}
.status-dot-green {
	width: 6px;
	height: 6px;
	border-radius: 50%;
	background: #10b981;
	box-shadow: 0 0 6px #10b981;
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
.theme-icon.sun { color: #f59e0b; }
.theme-icon.moon { color: #38bdf8; }
.svg-icon-theme { width: 14px; height: 14px; }
.theme-label { font-size: 11px; }

.svg-icon-sm { width: 14px; height: 14px; }
.mobile-menu { display: none; }

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
.theme-light .theme-toggle-btn {
	background: #ffffff;
	border-color: #cbd5e1;
	color: #1e293b;
	box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05);
}

.theme-light .hero {
	background: linear-gradient(135deg, #ecfdf5 0%, #f0fdf4 100%);
	border-color: #a7f3d0;
	box-shadow: 0 4px 20px rgba(16, 185, 129, 0.08);
}
.theme-light .hero p { color: #059669; }
.theme-light .hero h1 { color: #064e3b; }
.theme-light .hero span { color: #475569; }
.theme-light .art-svg { color: #10b981; opacity: 0.18; }

.theme-light .filter-panel {
	background: #ffffff;
	border-color: #e2e8f0;
	box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
}
.theme-light .filter-header {
	border-bottom-color: #e2e8f0;
}
.theme-light .filter-heading-left h3 {
	color: #0f172a;
}
.theme-light .preset-btn {
	background: #f1f5f9;
	border-color: #e2e8f0;
	color: #475569;
}
.theme-light .preset-btn.active {
	background: #10b981;
	color: #ffffff;
}
.theme-light .date-input-group input[type="date"] {
	background: #ffffff;
	border-color: #cbd5e1;
	color: #0f172a;
}
.theme-light .btn-apply-filter {
	background: #f1f5f9;
	border-color: #cbd5e1;
	color: #334155;
}

.theme-light .summary article {
	background: #ffffff;
	border-color: #e2e8f0;
	box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
}
.theme-light .summary small { color: #64748b; }
.theme-light .summary strong { color: #0f172a; }

.theme-light .crypto-panel {
	background: #ffffff;
	border-color: #e2e8f0;
	box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05);
}
.theme-light .crypto-header {
	background: linear-gradient(180deg, #f8fafc 0%, #ffffff 100%);
	border-bottom-color: #e2e8f0;
}
.theme-light .title-with-badge h2 { color: #0f172a; }
.theme-light .subtitle { color: #64748b; }
.theme-light .btn-refresh {
	background: #ffffff;
	border-color: #cbd5e1;
	color: #334155;
}

.theme-light .navbar-pills-wrap {
	background: #f8fafc;
	border-bottom-color: #e2e8f0;
}
.theme-light .nav-pill {
	background: #ffffff;
	border-color: #e2e8f0;
	color: #475569;
}
.theme-light .nav-pill.active {
	background: #ecfdf5;
	border-color: #10b981;
	color: #047857;
}
.theme-light .nav-pill .pill-count {
	background: #e2e8f0;
	color: #475569;
}
.theme-light .nav-pill.active .pill-count {
	background: #10b981;
	color: #ffffff;
}

.theme-light .vehicle-filter-toolbar {
	background: #ffffff;
	border: 1px solid #fed7aa;
	box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04);
}
.theme-light .v-toolbar-heading {
	color: #0f172a;
}
.theme-light .v-toolbar-desc {
	color: #64748b;
}
.theme-light .v-toolbar-header {
	border-bottom: 1px solid #f1f5f9;
}
.theme-light .v-control-select,
.theme-light .v-control-date-input {
	background: #f8fafc;
	border: 1px solid #cbd5e1;
	color: #0f172a;
}
.theme-light .v-control-label {
	color: #334155;
}
.theme-light .v-result-counter {
	background: #f8fafc;
	border-color: #e2e8f0;
	color: #334155;
}

.theme-light .crypto-table thead tr {
	background: #f8fafc;
	border-bottom-color: #e2e8f0;
}
.theme-light .crypto-table th { color: #475569; }
.theme-light .table-row { border-bottom-color: #f1f5f9; }
.theme-light .table-row:hover { background: rgba(16, 185, 129, 0.05); }
.theme-light .text-user { color: #0f172a; }
.theme-light .text-highlight { color: #1e293b; }
.theme-light .badge-subtle { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }

.theme-light .empty-state h4 { color: #0f172a; }
.theme-light .empty-icon-box { background: #f1f5f9; border-color: #e2e8f0; color: #94a3b8; }
.theme-light .empty-hint { background: #ecfdf5; border-color: #a7f3d0; color: #065f46; }

.theme-light .crypto-footer {
	background: #f8fafc;
	border-top-color: #e2e8f0;
	color: #64748b;
}
.theme-light .footer-info b { color: #0f172a; }

.theme-light .filter-select {
	background: #ffffff;
	border-color: #cbd5e1;
	color: #1e293b;
}
.theme-light .chart-panel {
	background: #ffffff;
	border-color: #e2e8f0;
	box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05);
}
.theme-light .chart-header {
	background: linear-gradient(180deg, #f8fafc 0%, #ffffff 100%);
	border-bottom-color: #e2e8f0;
}
.theme-light .chart-heading-left h3 { color: #0f172a; }
.theme-light .chart-heading-left p { color: #64748b; }
.theme-light .chart-mode-pill {
	background: #f1f5f9;
	border-color: #e2e8f0;
}
.theme-light .chart-mode-pill button.active {
	background: #10b981;
	color: #ffffff;
}
.theme-light .btn-chart-print {
	background: #f1f5f9;
	border-color: #cbd5e1;
	color: #334155;
}
.theme-light .btn-chart-print:hover {
	background: #e2e8f0;
	color: #0284c7;
}
.theme-light .grid-line {
	stroke: #e2e8f0;
}
.theme-light .y-axis-label {
	fill: #94a3b8;
}
.theme-light .bar-val-text.has-data {
	fill: #1e293b;
}
.theme-light .x-axis-label {
	fill: #64748b;
}
.theme-light .chart-summary-row {
	border-top-color: #f1f5f9;
}
.theme-light .kpi-card {
	background: #f8fafc;
	border-color: #e2e8f0;
}
.theme-light .kpi-card strong {
	color: #0f172a;
}
.theme-light .chart-tooltip-floating {
	background: #ffffff;
	border-color: #34d399;
	box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}
.theme-light .tooltip-header strong {
	color: #0f172a;
}

/* Light theme overrides for ATK Dual Bar Charts */
.theme-light .atk-charts-panel {
	background: #ffffff;
	border-color: #e2e8f0;
	box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
}
.theme-light .atk-charts-header {
	background: linear-gradient(180deg, #f8fafc 0%, #ffffff 100%);
	border-bottom-color: #e2e8f0;
}
.theme-light .header-info h3 {
	color: #0f172a;
}
.theme-light .header-info p {
	color: #64748b;
}
.theme-light .chart-date-filter-box {
	background: #f8fafc;
	border-color: #e2e8f0;
}
.theme-light .filter-box-label {
	color: #0f172a;
}
.theme-light .input-chart-date {
	background: #ffffff;
	border-color: #cbd5e1;
	color: #0f172a;
}
.theme-light .preset-pills-row button {
	background: #e2e8f0;
	border-color: #cbd5e1;
	color: #475569;
}
.theme-light .preset-pills-row button.active {
	background: #059669;
	color: #ffffff;
	border-color: #059669;
}
.theme-light .atk-chart-card {
	background: #ffffff;
	border-color: #e2e8f0;
	box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
}
.theme-light .card-header {
	background: #f8fafc;
	border-bottom-color: #e2e8f0;
}
.theme-light .card-title-group h4 {
	color: #0f172a;
}
.theme-light .card-title-group p {
	color: #64748b;
}
.theme-light .tier-select-modern {
	background: #ffffff;
	border-color: #cbd5e1;
	color: #0f172a;
}
.theme-light .card-sub-stats {
	background: #f8fafc;
	border-bottom-color: #f1f5f9;
	color: #64748b;
}
.theme-light .card-sub-stats b {
	color: #0f172a;
}
.theme-light .vbar-y-axis .y-tick {
	color: #64748b;
}
.theme-light .vgrid-line {
	border-bottom-color: #e2e8f0;
}
.theme-light .vgrid-line.baseline {
	border-bottom-color: #cbd5e1;
}
.theme-light .vbar-top-tag {
	box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
}
.theme-light .tag-palette-0 { background: #e0e7ff; color: #4338ca; border-color: #c7d2fe; }
.theme-light .tag-palette-1 { background: #e0f2fe; color: #0369a1; border-color: #bae6fd; }
.theme-light .tag-palette-2 { background: #d1fae5; color: #065f46; border-color: #a7f3d0; }
.theme-light .tag-palette-3 { background: #fef3c7; color: #92400e; border-color: #fde68a; }
.theme-light .tag-palette-4 { background: #ffe4e6; color: #9f1239; border-color: #fecdd3; }
.theme-light .tag-palette-5 { background: #ffedd5; color: #9a3412; border-color: #fed7aa; }
.theme-light .tag-palette-6 { background: #ccfbf1; color: #115e59; border-color: #99f6e4; }
.theme-light .tag-palette-7 { background: #fce7f3; color: #9d174d; border-color: #fbcfe8; }
.theme-light .tag-palette-8 { background: #ecfccb; color: #3f6212; border-color: #d9f99d; }
.theme-light .tag-palette-9 { background: #f3e8ff; color: #6b21a8; border-color: #e9d5ff; }
.theme-light .tag-danger { background: #fee2e2; color: #b91c1c; border-color: #fca5a5; }

.theme-light .vbar-pedestal-photo-box {
	background: #ffffff;
	border-color: #cbd5e1;
	box-shadow: 0 2px 6px rgba(0, 0, 0, 0.06);
}
.theme-light .vbar-column-item:hover .vbar-pedestal-photo-box {
	border-color: #94a3b8;
	box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
}
.theme-light .vbar-item-name {
	color: #475569;
}
.theme-light .vbar-qty-sub {
	color: #94a3b8;
}
.theme-light .vbar-column-item:hover .vbar-item-name {
	color: #0f172a;
}
.theme-light .vbar-tooltip {
	background: #ffffff;
	border-color: #cbd5e1;
	box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12), 0 0 12px rgba(16, 185, 129, 0.15);
}
.theme-light .vbar-tooltip::after {
	border-color: #ffffff transparent transparent transparent;
}
.theme-light .vtooltip-header {
	border-bottom-color: #f1f5f9;
}
.theme-light .vtooltip-thumb {
	background: #f8fafc;
	border-color: #e2e8f0;
}
.theme-light .vtooltip-title {
	color: #0f172a;
}
.theme-light .vtooltip-metric {
	color: #475569;
}
.theme-light .vtooltip-sub {
	color: #64748b;
}

/* Responsive Grid for Dual Charts */
@media (max-width: 1024px) {
	.atk-dual-charts-grid {
		grid-template-columns: 1fr;
	}
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
	.filter-controls-row {
		flex-direction: column;
		align-items: flex-start;
	}
}
@media (max-width: 600px) {
	.summary {
		grid-template-columns: 1fr;
	}
	.page-content {
		padding: 16px;
	}
	.filter-header {
		flex-direction: column;
		align-items: flex-start;
	}
	.export-actions {
		width: 100%;
		justify-content: space-between;
	}
	.crypto-header {
		flex-direction: column;
		align-items: flex-start;
		gap: 14px;
	}
}

/* Report Asset Cell & Thumbnail */
.report-asset-cell {
	display: flex;
	align-items: center;
	gap: 10px;
}
.report-asset-thumb {
	width: 36px;
	height: 36px;
	border-radius: 7px;
	object-fit: cover;
	flex-shrink: 0;
	background: #090e18;
	border: 1px solid rgba(255, 255, 255, 0.1);
}
.theme-light .report-asset-thumb {
	background: #f1f5f9;
	border-color: #cbd5e1;
}

/* ========================================================================= */
/* MODAL APPROVAL PENUGASAN MOBIL & SOPIR OLEH ADMIN                         */
/* ========================================================================= */
.admin-modal-backdrop {
	position: fixed;
	inset: 0;
	z-index: 9999;
	display: flex;
	align-items: center;
	justify-content: center;
	padding: 16px;
	background: rgba(4, 9, 18, 0.78);
	backdrop-filter: blur(12px);
	-webkit-backdrop-filter: blur(12px);
}
.admin-modal-card {
	background: rgba(13, 23, 42, 0.94);
	border: 1px solid rgba(16, 185, 129, 0.4);
	box-shadow: 0 20px 50px rgba(0, 0, 0, 0.6), 0 0 30px rgba(16, 185, 129, 0.15);
	border-radius: 20px;
	max-width: 540px;
	width: 100%;
	padding: 24px;
	color: #f8fafc;
	animation: modalPop 0.25s ease-out;
}
.theme-light .admin-modal-card {
	background: rgba(255, 255, 255, 0.96);
	border-color: #cbd5e1;
	box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
	color: #0f172a;
}
.admin-modal-header {
	display: flex;
	align-items: center;
	justify-content: space-between;
	padding-bottom: 14px;
	border-bottom: 1px solid rgba(255, 255, 255, 0.1);
	margin-bottom: 16px;
}
.theme-light .admin-modal-header {
	border-color: #e2e8f0;
}
.btn-close-modal {
	width: 32px;
	height: 32px;
	border-radius: 8px;
	background: rgba(255, 255, 255, 0.06);
	border: 1px solid rgba(255, 255, 255, 0.12);
	color: #94a3b8;
	display: flex;
	align-items: center;
	justify-content: center;
	font-size: 13px;
	cursor: pointer;
	transition: all 0.2s;
}
.btn-close-modal:hover {
	background: rgba(239, 68, 68, 0.2);
	color: #ef4444;
	border-color: #ef4444;
}
.modal-summary-box {
	background: rgba(0, 0, 0, 0.35);
	border: 1px solid rgba(255, 255, 255, 0.08);
	border-radius: 14px;
	padding: 14px;
	space-y: 10px;
	margin-bottom: 16px;
}
.theme-light .modal-summary-box {
	background: #f8fafc;
	border-color: #e2e8f0;
}
.admin-modal-select,
.admin-modal-input {
	background: rgba(6, 12, 23, 0.85);
	border: 1px solid rgba(255, 255, 255, 0.15);
	color: #ffffff;
}
.theme-light .admin-modal-select,
.theme-light .admin-modal-input {
	background: #ffffff;
	border-color: #cbd5e1;
	color: #0f172a;
}
.admin-modal-select:focus,
.admin-modal-input:focus {
	border-color: #10b981;
	box-shadow: 0 0 10px rgba(16, 185, 129, 0.3);
}
.btn-modal-back {
	background: rgba(255, 255, 255, 0.06);
	border: 1px solid rgba(255, 255, 255, 0.12);
	color: #94a3b8;
}
.btn-modal-back:hover {
	color: #ffffff;
	border-color: #cbd5e1;
}
.btn-modal-confirm-approve {
	background: linear-gradient(135deg, #10b981 0%, #059669 100%);
	color: #ffffff;
	box-shadow: 0 4px 16px rgba(16, 185, 129, 0.4);
}
.btn-modal-confirm-approve:hover:not(:disabled) {
	background: linear-gradient(135deg, #059669 0%, #047857 100%);
	transform: translateY(-1px);
}
@keyframes modalPop {
	from { opacity: 0; transform: scale(0.95); }
	to { opacity: 1; transform: scale(1); }
}

/* =========================================================================
   CONFLICT WARNING MODAL (BENTROK JADWAL ARMADA & SOPIR)
   ========================================================================= */
.conflict-warning-card {
	width: 100%;
	max-width: 600px;
	background: linear-gradient(180deg, #151d2e 0%, #0d1422 100%);
	border: 1px solid rgba(245, 158, 11, 0.4);
	border-radius: 20px;
	box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.7), 0 0 35px rgba(245, 158, 11, 0.15);
	overflow: hidden;
	animation: modalPop 0.25s cubic-bezier(0.16, 1, 0.3, 1);
	position: relative;
}

.theme-light .conflict-warning-card {
	background: #ffffff;
	border: 1px solid #f59e0b;
	box-shadow: 0 20px 45px -10px rgba(0, 0, 0, 0.15), 0 0 20px rgba(245, 158, 11, 0.15);
}

.conflict-card-header {
	padding: 18px 20px;
	display: flex;
	align-items: flex-start;
	gap: 14px;
	border-bottom: 1px solid rgba(255, 255, 255, 0.08);
	background: rgba(245, 158, 11, 0.04);
}

.theme-light .conflict-card-header {
	border-bottom: 1px solid #f1f5f9;
	background: #fffdf5;
}

.conflict-icon-pulse {
	width: 44px;
	height: 44px;
	border-radius: 14px;
	display: flex;
	align-items: center;
	justify-content: center;
	background: rgba(245, 158, 11, 0.16);
	border: 1px solid rgba(245, 158, 11, 0.35);
	flex-shrink: 0;
}

.conflict-tag-badge {
	background: rgba(239, 68, 68, 0.2);
	color: #f87171;
	border: 1px solid rgba(239, 68, 68, 0.35);
	font-size: 10px;
	font-weight: 800;
	padding: 2px 8px;
	border-radius: 6px;
	letter-spacing: 0.04em;
}

.conflict-comparison-grid {
	display: grid;
	grid-template-columns: 1fr 1fr;
	gap: 12px;
}

@media (max-width: 580px) {
	.conflict-comparison-grid {
		grid-template-columns: 1fr;
	}
}

.conflict-col {
	border-radius: 14px;
	padding: 14px;
	display: flex;
	flex-direction: column;
	justify-content: space-between;
}

.conflict-col-active {
	background: rgba(239, 68, 68, 0.08);
	border: 1px solid rgba(239, 68, 68, 0.25);
}

.theme-light .conflict-col-active {
	background: #fef2f2;
	border-color: #fecaca;
}

.conflict-col-new {
	background: rgba(245, 158, 11, 0.08);
	border: 1px solid rgba(245, 158, 11, 0.25);
}

.theme-light .conflict-col-new {
	background: #fffbeb;
	border-color: #fde68a;
}

.theme-light .conflict-warning-card h3 {
	color: #0f172a !important;
}

.theme-light .conflict-warning-card p {
	color: #475569 !important;
}

.theme-light .conflict-col .font-bold.text-white {
	color: #0f172a !important;
}

.theme-light .conflict-col .text-slate-300 {
	color: #475569 !important;
}

.btn-conflict-cancel {
	background: rgba(239, 68, 68, 0.15);
	border: 1px solid rgba(239, 68, 68, 0.35);
	color: #f87171;
}

.btn-conflict-cancel:hover {
	background: rgba(239, 68, 68, 0.25);
	color: #ffffff;
	border-color: #ef4444;
	transform: translateY(-1px);
}

.theme-light .btn-conflict-cancel {
	background: #fee2e2;
	color: #b91c1c;
	border-color: #fca5a5;
}

.theme-light .btn-conflict-cancel:hover {
	background: #fecaca;
	color: #991b1b;
}

.btn-conflict-change {
	background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
	color: #ffffff;
	border: none;
	box-shadow: 0 4px 14px rgba(245, 158, 11, 0.35);
}

.btn-conflict-change:hover {
	background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
	box-shadow: 0 6px 18px rgba(245, 158, 11, 0.45);
	transform: translateY(-1px);
}
</style>
