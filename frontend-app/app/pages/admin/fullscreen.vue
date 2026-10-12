<template>
	<div class="fs-root" :class="[isDark ? 'theme-dark' : 'theme-light']">

		<!-- ═══════════════════════════════════════════ TOPBAR ═══ -->
		<header class="fs-topbar">
			<div class="fs-topbar-left">
				<!-- Tombol Kembali ke Admin Dashboard -->
				<NuxtLink to="/Admin/dashboard" class="btn-back-admin" title="Kembali ke Dashboard Admin">
					<svg viewBox="0 0 24 24" fill="currentColor" class="icon-xs"><path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/></svg>
					<span>Panel Admin</span>
				</NuxtLink>

				<div class="brand-block">
					<div class="brand-icon-wrap">
						<img src="/images/logo-simpel-icon.png" alt="SIMPEL" style="width: 28px; height: 28px; object-fit: contain;" />
					</div>
					<div>
						<div class="brand-live-badge">
							<span class="live-dot-red"></span>
							<span>LIVE COMMAND CENTER • SMART OFFICE MONITOR</span>
							<span class="sync-pill">3s SYNC</span>
						</div>
						<div class="brand-title-text">Status Real-Time Pintu Ruang Rapat &amp; Live Tracking Armada Dinas</div>
					</div>
				</div>
			</div>

			<div class="fs-topbar-right">
				<!-- Status Counters Chips -->
				<div class="status-count-chips">
					<div class="count-chip chip-green" title="Ruangan Kosong / Siap Digunakan">
						<svg viewBox="0 0 24 24" fill="currentColor" class="icon-xs"><path d="M19 19V5c0-1.1-.9-2-2-2H7c-1.1 0-2 .9-2 2v14H3v2h18v-2h-2zm-4-2H7V5h8v12zm-3-6h2v2h-2z"/></svg>
						<span>{{ availableRoomsCount }} Ruang Kosong</span>
					</div>
					<div class="count-chip chip-red" title="Ruangan Sedang Digunakan">
						<svg viewBox="0 0 24 24" fill="currentColor" class="icon-xs"><path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/></svg>
						<span>{{ occupiedRoomsCount }} Rapat Aktif</span>
					</div>
					<div class="count-divider"></div>
					<div class="count-chip chip-green" title="Mobil Standby di Garasi Pool">
						<svg viewBox="0 0 24 24" fill="currentColor" class="icon-xs"><path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99z"/></svg>
						<span>{{ availableCarsCount }} Mobil Pool</span>
					</div>
					<div class="count-chip chip-cyan" title="Mobil Sedang Dalam Perjalanan Dinas">
						<svg viewBox="0 0 24 24" fill="currentColor" class="icon-xs"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
						<span>{{ departedCarsCount }} Sedang Jalan</span>
					</div>
					<div class="count-chip chip-slate" title="Ruangan / Mobil Dalam Pemeliharaan">
						<svg viewBox="0 0 24 24" fill="currentColor" class="icon-xs"><path d="M13.7 22.1l1.4-1.4-2.8-2.8 1.4-1.4 2.8 2.8 1.4-1.4-2.8-2.8 4.2-4.2c.78-.78.78-2.05 0-2.83L16.8 5.7c-.78-.78-2.05-.78-2.83 0L9.77 9.9l-1.4-1.41-1.41 1.41 1.41 1.41-5.65 5.66c-.78.78-.78 2.05 0 2.83L4.1 21.2c.78.78 2.05.78 2.83 0l5.66-5.65 1.41 1.41-1.41 1.41 1.41 1.41-2.82 2.83z"/></svg>
						<span>{{ maintCount }} Pemeliharaan</span>
					</div>
				</div>

				<!-- Jam Real-Time Lengkap -->
				<div class="fs-clock">
					<svg viewBox="0 0 24 24" fill="currentColor" class="icon-xs"><path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67V7z"/></svg>
					<span>{{ currentTimeString }}</span>
				</div>

				<!-- Tombol Ubah Tema -->
				<button class="theme-btn" @click="toggleTheme" :title="isDark ? 'Mode Terang' : 'Mode Gelap'">
					<svg v-if="isDark" viewBox="0 0 24 24" fill="currentColor" class="icon-xs"><path d="M12 7c-2.76 0-5 2.24-5 5s2.24 5 5 5 5-2.24 5-5-2.24-5-5-5zM2 13h2c.55 0 1-.45 1-1s-.45-1-1-1H2c-.55 0-1 .45-1 1s.45 1 1 1zm18 0h2c.55 0 1-.45 1-1s-.45-1-1-1h-2c-.55 0-1 .45-1 1s.45 1 1 1zM11 2v2c0 .55.45 1 1 1s1-.45 1-1V2c0-.55-.45-1-1-1s-1 .45-1 1zm0 18v2c0 .55.45 1 1 1s1-.45 1-1v-2c0-.55-.45-1-1-1s-1 .45-1 1z"/></svg>
					<svg v-else viewBox="0 0 24 24" fill="currentColor" class="icon-xs"><path d="M12.3 2a10 10 0 0 0-1.9 19.8 10 10 0 0 0 10.9-10.9A10 10 0 0 0 12.3 2z"/></svg>
				</button>
			</div>
		</header>

		<!-- ═══════════════════════════════════════════ MAIN MONITORING SEJAJAR 2 KOLOM ═══ -->
		<main class="fs-main-parallel">

			<!-- ───────────────────────────────────────── KOLOM 1: STATUS RUANGAN RAPAT (LEFT) ─── -->
			<section class="parallel-col col-rooms">
				<div class="col-head">
					<div class="col-head-left">
						<div class="panel-tag tag-amber">
							<span class="live-dot-amber"></span>
							<span>LIVE STATUS RUANGAN</span>
						</div>
						<h2 class="col-title">Monitoring Pintu &amp; Ruang Rapat</h2>
						<span class="col-desc">Otomatis sinkron dengan jadwal persetujuan booking &amp; scan barcode pintu</span>
					</div>
					<div class="col-legend">
						<span class="legend-chip green"><i class="leg-dot"></i> Buka (Kosong)</span>
						<span class="legend-chip red"><i class="leg-dot"></i> Kunci (Terisi)</span>
						<span class="legend-chip gray"><i class="leg-dot"></i> Tang (Servis)</span>
					</div>
				</div>

				<!-- LIST KARTU RUANGAN -->
				<div class="rooms-vertical-container">
					<div v-if="roomList.length === 0" class="empty-state-notice">
						<svg viewBox="0 0 24 24" fill="currentColor" class="icon-sm"><path d="M19 19V5c0-1.1-.9-2-2-2H7c-1.1 0-2 .9-2 2v14H3v2h18v-2h-2zm-4-2H7V5h8v12zm-3-6h2v2h-2z"/></svg>
						<span>Memuat data ruangan rapat...</span>
					</div>

					<div 
						v-for="room in roomList" 
						:key="room.id" 
						class="room-icon-card"
						:class="{
							'card-open': room.status === 'Tersedia',
							'card-closed': room.status === 'Sedang Digunakan',
							'card-tang': room.status === 'Pemeliharaan'
						}"
					>
						<!-- Left: Door or Tang Icon Graphic -->
						<div class="room-graphic-area">
							<!-- 1. PINTU TERBUKA (KOSONG / TERSEDIA) -->
							<div v-if="room.status === 'Tersedia'" class="graphic-box door-box-open" title="Pintu Terbuka - Ruangan Kosong & Siap Dipakai">
								<svg viewBox="0 0 36 36" class="door-svg-detail">
									<!-- Door frame -->
									<rect x="4" y="3" width="28" height="30" rx="3" fill="#0f1d18" stroke="#10b981" stroke-width="1.8"/>
									<!-- Open door swinging outward -->
									<path d="M6 5 L20 7 L20 29 L6 31 Z" fill="#10b981" fill-opacity="0.3" stroke="#10b981" stroke-width="2"/>
									<!-- Door handle -->
									<circle cx="18" cy="18" r="1.8" fill="#34d399"/>
									<!-- Light beam casting through open door -->
									<polygon points="20,7 30,5 30,31 20,29" fill="#10b981" fill-opacity="0.15"/>
									<line x1="20" y1="7" x2="30" y2="5" stroke="#10b981" stroke-width="1" stroke-dasharray="2,2"/>
									<line x1="20" y1="29" x2="30" y2="31" stroke="#10b981" stroke-width="1" stroke-dasharray="2,2"/>
								</svg>
							</div>

							<!-- 2. PINTU TERTUTUP (TERISI / SEDANG DIGUNAKAN) -->
							<div v-else-if="room.status === 'Sedang Digunakan'" class="graphic-box door-box-closed" title="Pintu Terkunci - Ruangan Sedang Digunakan Rapat">
								<svg viewBox="0 0 36 36" class="door-svg-detail">
									<!-- Door frame -->
									<rect x="4" y="3" width="28" height="30" rx="3" fill="#1f1315" stroke="#ef4444" stroke-width="1.8"/>
									<!-- Closed solid door panel -->
									<rect x="7" y="6" width="22" height="24" rx="2" fill="#ef4444" fill-opacity="0.25" stroke="#ef4444" stroke-width="1.4"/>
									<!-- Door wood grain / panel dividers -->
									<rect x="10" y="8" width="16" height="8" rx="1" fill="#ef4444" fill-opacity="0.1" stroke="#ef4444" stroke-width="0.8"/>
									<rect x="10" y="18" width="16" height="9" rx="1" fill="#ef4444" fill-opacity="0.1" stroke="#ef4444" stroke-width="0.8"/>
									<!-- Digital Padlock in center -->
									<rect x="14.5" y="14" width="7" height="6" rx="1" fill="#ef4444"/>
									<path d="M16 14 V12 A2 2 0 0 1 20 12 V14" stroke="#ef4444" stroke-width="1.5" fill="none"/>
								</svg>
							</div>

							<!-- 3. ICON TANG (PERAWATAN / STERILISASI) -->
							<div v-else class="graphic-box tang-box-maint" title="Icon Tang - Sedang Pemeliharaan / Sterilisasi Ruangan">
								<svg viewBox="0 0 36 36" class="door-svg-detail">
									<rect x="4" y="3" width="28" height="30" rx="3" fill="#181d24" stroke="#94a3b8" stroke-width="1.8"/>
									<path d="M11 11 L16 16 M16 16 L22 10 M16 16 L20 20" stroke="#f59e0b" stroke-width="2.5" stroke-linecap="round"/>
									<line x1="16" y1="16" x2="10" y2="27" stroke="#64748b" stroke-width="2.8" stroke-linecap="round"/>
									<line x1="16" y1="16" x2="25" y2="25" stroke="#f59e0b" stroke-width="2.8" stroke-linecap="round"/>
									<circle cx="16" cy="16" r="2.5" fill="#f8fafc" stroke="#334155" stroke-width="1"/>
								</svg>
							</div>
						</div>

						<!-- Center: Room Information & Divisi Pengguna -->
						<div class="room-details-content">
							<div class="room-title-line">
								<strong class="room-name-heading">{{ room.name }}</strong>
								<span class="room-floor-tag">{{ room.floor }} • Kapasitas: {{ room.capacity }} Kursi</span>
							</div>

							<!-- INFORMASI PENGGUNAAN RUANGAN (OTOMATIS DARI DATABASE) -->
							<div class="room-usage-box">
								<!-- Jika Sedang Terisi / Terpakai Rapat -->
								<div v-if="room.status === 'Sedang Digunakan'" class="usage-active">
									<div class="usage-top-row">
										<span class="usage-division-badge">
											<svg viewBox="0 0 24 24" fill="currentColor" class="icon-xxs"><path d="M12 7V3H2v18h20V7H12zM6 19H4v-2h2v2zm0-4H4v-2h2v2zm0-4H4V9h2v2zm0-4H4V5h2v2zm4 12H8v-2h2v2zm0-4H8v-2h2v2zm0-4H8V9h2v2zm0-4H8V5h2v2zm10 12h-8v-2h2v-2h-2v-2h2v-2h-2V9h8v10zm-2-8h-2v2h2v-2zm0 4h-2v2h2v-2z"/></svg>
											<span>Divisi: <strong>{{ room.current_division || 'Operasional' }}</strong></span>
										</span>
										<span class="meeting-live-pulse">
											<span class="pulse-mini-dot"></span>
											<span>RAPAT AKTIF</span>
										</span>
									</div>
									<div class="usage-user-row">
										<span class="user-name">👤 {{ room.current_user || 'Pegawai Instansi' }}</span>
										<span class="agenda-text">📋 {{ room.current_agenda || 'Rapat Koordinasi' }}</span>
										<span class="time-text">⏰ {{ room.current_time || 'Sedang Berlangsung' }}</span>
									</div>
								</div>

								<!-- Jika Kosong / Tersedia -->
								<div v-else-if="room.status === 'Tersedia'" class="usage-free">
									<span class="free-text">✨ <strong>PINTU TERBUKA</strong> • Ruangan kosong steril &amp; siap digunakan</span>
								</div>

								<!-- Jika Perawatan / Pembersihan -->
								<div v-else class="usage-maint">
									<span class="maint-text">🛠️ <strong>PEMELIHARAAN / STERILISASI</strong> • Petugas Umum &amp; Teknisi</span>
								</div>
							</div>
						</div>

						<!-- Right: Status Badge & Micro Quick Actions -->
						<div class="room-side-action">
							<!-- Status Pill -->
							<div 
								class="room-badge-pill" 
								:class="{
									'pill-avail': room.status === 'Tersedia',
									'pill-busy': room.status === 'Sedang Digunakan',
									'pill-maint': room.status === 'Pemeliharaan'
								}"
							>
								<span v-if="room.status === 'Tersedia'">🟢 KOSONG</span>
								<span v-else-if="room.status === 'Sedang Digunakan'">🔴 TERISI</span>
								<span v-else>🛠️ SERVIS</span>
							</div>

							<!-- Micro Override Switcher (Menyimpan Langsung ke Backend) -->
							<div class="room-switcher-group">
								<button 
									class="btn-switch-mini btn-sw-open" 
									:class="{ active: room.status === 'Tersedia' }" 
									@click="changeRoomStatus(room, 'Tersedia')" 
									title="Buka Pintu (Set Kosong / Siap)"
								>
									🚪 Buka
								</button>
								<button 
									class="btn-switch-mini btn-sw-close" 
									:class="{ active: room.status === 'Sedang Digunakan' }" 
									@click="changeRoomStatus(room, 'Sedang Digunakan')" 
									title="Kunci Pintu (Set Sedang Rapat)"
								>
									🔒 Kunci
								</button>
								<button 
									class="btn-switch-mini btn-sw-tang" 
									:class="{ active: room.status === 'Pemeliharaan' }" 
									@click="changeRoomStatus(room, 'Pemeliharaan')" 
									title="Icon Tang (Set Pemeliharaan)"
								>
									🛠️ Tang
								</button>
							</div>
						</div>
					</div>
				</div>
			</section>

			<!-- ───────────────────────────────────────── KOLOM 2: LIVE TRACKING MOBIL DINAS (RIGHT) ─── -->
			<section class="parallel-col col-cars">
				<div class="col-head">
					<div class="col-head-left">
						<div class="panel-tag tag-cyan">
							<span class="live-dot-cyan"></span>
							<span>LIVE ARMADA MOBIL DINAS</span>
						</div>
						<h2 class="col-title">Live Tracking Armada Kendaraan</h2>
						<span class="col-desc">Jalur Anti Tabrakan • Lampu Menyala Kuning Saat Beroperasi • Real-Time Trip</span>
					</div>
					<div class="col-legend">
						<span class="legend-chip green"><i class="leg-dot"></i> Pool</span>
						<span class="legend-chip cyan"><i class="leg-dot"></i> Jalan 💡</span>
						<span class="legend-chip amber"><i class="leg-dot"></i> Pulang</span>
					</div>
				</div>

				<!-- LIST KARTU MOBIL -->
				<div class="cars-vertical-container">
					<div v-if="trackCars.length === 0" class="empty-state-notice">
						<svg viewBox="0 0 24 24" fill="currentColor" class="icon-sm"><path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99z"/></svg>
						<span>Memuat armada mobil dinas...</span>
					</div>

					<div 
						v-for="(car, idx) in trackCars" 
						:key="car.id" 
						class="car-track-card"
						:class="`cstate-${car.statusKey}`"
					>
						<!-- Top Info Line: Number, Plate, Name, Divisi & Live Status -->
						<div class="car-card-topbar">
							<div class="car-id-box">
								<span class="car-num-tag">#0{{ idx + 1 }}</span>
								<span class="car-plate-code">{{ car.license_plate }}</span>
								<strong class="car-title-text">{{ car.name }}</strong>
							</div>

							<!-- Divisi Badge & Status Badge -->
							<div class="car-tags-cluster">
								<div class="car-divisi-pill" :title="`Digunakan oleh Divisi ${car.division}`">
									<svg viewBox="0 0 24 24" fill="currentColor" class="icon-xxs"><path d="M12 7V3H2v18h20V7H12zM6 19H4v-2h2v2zm0-4H4v-2h2v2zm0-4H4V9h2v2zm0-4H4V5h2v2zm4 12H8v-2h2v2zm0-4H8v-2h2v2zm0-4H8V9h2v2zm0-4H8V5h2v2zm10 12h-8v-2h2v-2h-2v-2h2v-2h-2V9h8v10zm-2-8h-2v2h2v-2zm0 4h-2v2h2v-2z"/></svg>
									<span>Div: <strong>{{ car.division }}</strong></span>
								</div>

								<!-- Badge Status Mobil -->
								<div 
									class="car-state-badge" 
									:class="{
										'badge-pool': car.statusKey === 'available' && !car.has_scheduled,
										'badge-scheduled': car.statusKey === 'available' && car.has_scheduled,
										'badge-jalan': car.statusKey === 'departed',
										'badge-pulang': car.statusKey === 'returning'
									}"
								>
									<span v-if="car.statusKey === 'available' && !car.has_scheduled">🟢 POOL KANTOR</span>
									<span v-else-if="car.statusKey === 'available' && car.has_scheduled">📅 TERJADWAL</span>
									<span v-else-if="car.statusKey === 'departed'">🛫 JALAN (LAMPU ON)</span>
									<span v-else>🔄 MENUJU POOL</span>
								</div>
							</div>
						</div>

						<!-- Middle Section: Driver, Destination, & Road Track in Parallel -->
						<div class="car-middle-row">
							<!-- Left mini info: Driver + Destination -->
							<div class="car-trip-summary">
								<div v-if="car.statusKey === 'departed'" class="trip-active-text">
									<span class="driver-lead">👤 {{ car.driver }}</span>
									<span class="dest-lead">📍 {{ car.destination }}</span>
								</div>
								<div v-else-if="car.statusKey === 'returning'" class="trip-return-text">
									<span class="driver-lead">👤 {{ car.driver }}</span>
									<span class="dest-lead">🔄 Menuju Pool Kantor</span>
								</div>
								<div v-else-if="car.has_scheduled" class="trip-scheduled-text">
									<span class="driver-lead">👤 {{ car.driver }}</span>
									<span class="dest-lead">📅 {{ car.scheduled_time }}: {{ car.destination }}</span>
								</div>
								<div v-else class="trip-standby-text">
									<span class="driver-lead">👤 {{ car.driver || 'Pengemudi Siaga' }} (Standby)</span>
									<span class="dest-lead">Garasi Pool Siap Operasional</span>
								</div>
							</div>

							<!-- Right: CLEAN ASPHALT TRACK (ANTI TUMPANG TINDIH & DYNAMIC HEADLIGHTS) -->
							<div class="dedicated-road-track" :class="`road-${car.statusKey}`">
								<!-- Garis marka jalan aspal putih putus-putus -->
								<div class="road-center-stripe"></div>

								<!-- Marker Kiri: Kantor / Garasi Pool -->
								<div class="track-gate-marker marker-left" title="Garasi Pool Kantor">
									<svg viewBox="0 0 16 16" fill="currentColor" class="gate-icon"><path d="M1 14V3l7-2 7 2v11h-4v-4H5v4H1z"/></svg>
								</div>

								<!-- Marker Kanan: Lokasi Tujuan Dinas -->
								<div class="track-gate-marker marker-right" :class="`gate-${car.statusKey}`" title="Lokasi Tujuan">
									<svg v-if="car.statusKey === 'available'" viewBox="0 0 16 16" fill="currentColor" class="gate-icon"><path d="M8 1a7 7 0 1 0 0 14A7 7 0 0 0 8 1zm3.25 5.25L7 10.5 4.75 8.25l1.06-1.06L7 8.38l3.19-3.19 1.06 1.06z"/></svg>
									<svg v-else-if="car.statusKey === 'departed'" viewBox="0 0 16 16" fill="currentColor" class="gate-icon"><path d="M2 13.5V3l11 5-11 5.5z"/></svg>
									<svg v-else viewBox="0 0 16 16" fill="currentColor" class="gate-icon"><path d="M14 8H3.83l3.59-3.59L6 3 1 8l5 5 1.41-1.41L3.83 9H14V8z"/></svg>
								</div>

								<!-- SPRITE MOBIL DINAS TERANIMASI -->
								<div class="track-car-vehicle" :class="car.statusKey">
									<svg viewBox="0 0 40 16" class="car-sprite-svg">
										<!-- Bodi Mobil -->
										<rect x="1" y="8" width="35" height="6" rx="2" :fill="car.bodyColor || '#0284c7'"/>
										<!-- Kabin Atap Mobil -->
										<path d="M7 8 L10 3 Q13 1 18 1 L25 1 Q29 1 32 4 L35 8 Z" :fill="car.color || '#38bdf8'"/>
										<!-- Kaca Mobil & Pantulan Cahaya -->
										<rect x="11" y="3" width="6" height="4" rx="1" fill="rgba(200,240,255,0.5)"/>
										<rect x="19" y="3" width="7" height="4" rx="1" fill="rgba(200,240,255,0.35)"/>
										
										<!-- LAMPU DEPAN: MENYALA KUNING TERANG JIKA STATUS JALAN (DEPARTED) -->
										<circle cx="34.5" cy="10" r="1.5" :fill="car.statusKey === 'departed' ? '#fde047' : '#fef9c3'" :class="{ 'headlight-glow-yellow': car.statusKey === 'departed' }"/>
										<!-- Sorot Cahaya Kuning ke Depan -->
										<polygon v-if="car.statusKey === 'departed'" points="35.5,9.5 39.5,8 39.5,12 35.5,10.5" fill="#fde047" opacity="0.85"/>

										<!-- Lampu Belakang (Merah) -->
										<rect x="1" y="9" width="2" height="3" rx="0.5" fill="#fca5a5" opacity="0.8"/>
										
										<!-- Roda Mobil -->
										<circle cx="10" cy="13.5" r="2.5" fill="#0f172a" stroke="#475569" stroke-width="0.8"/>
										<circle cx="28" cy="13.5" r="2.5" fill="#0f172a" stroke="#475569" stroke-width="0.8"/>
										<circle cx="10" cy="13.5" r="1.3" fill="#334155" stroke="#94a3b8" stroke-width="0.5"/>
										<circle cx="28" cy="13.5" r="1.3" fill="#334155" stroke="#94a3b8" stroke-width="0.5"/>
										<circle cx="10" cy="13.5" r="0.6" fill="#cbd5e1"/>
										<circle cx="28" cy="13.5" r="0.6" fill="#cbd5e1"/>
									</svg>
								</div>
							</div>

							<!-- Quick Switch Buttons Admin / Driver Override -->
							<div class="car-controls-cluster">
								<button 
									class="btn-car-sw btn-sw-go" 
									:class="{ active: car.statusKey === 'departed' }" 
									@click="changeCarStatus(car, 'departed')" 
									title="Set Mobil Berangkat (Lampu Kuning Menyala)"
								>
									🛫 Jalan
								</button>
								<button 
									class="btn-car-sw btn-sw-back" 
									:class="{ active: car.statusKey === 'returning' }" 
									@click="changeCarStatus(car, 'returning')" 
									title="Set Mobil Pulang ke Pool"
								>
									🏢 Pulang
								</button>
								<button 
									class="btn-car-sw btn-sw-park" 
									:class="{ active: car.statusKey === 'available' }" 
									@click="changeCarStatus(car, 'available')" 
									title="Set Standby di Pool"
								>
									🅿️ Pool
								</button>
							</div>
						</div>
					</div>
				</div>
			</section>

		</main>
	</div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'

const isDark = ref(true)
const currentTimeString = ref('')
let clockTimer = null

// Update jam & tanggal real-time
const updateClock = () => {
	const now = new Date()
	const datePart = now.toLocaleDateString('id-ID', { weekday: 'short', day: 'numeric', month: 'short' })
	const timePart = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' })
	currentTimeString.value = `${datePart} • ${timePart} WIB`
}

// ═══════════════════════════════════════════════════════════════════════════
// DATA RUANG RAPAT
// ═══════════════════════════════════════════════════════════════════════════
const roomList = ref([])

// ═══════════════════════════════════════════════════════════════════════════
// DATA ARMADA MOBIL DINAS
// ═══════════════════════════════════════════════════════════════════════════
const trackCars = ref([])

const config = useRuntimeConfig()
const apiBase = config.public?.apiBase || 'http://127.0.0.1:8000/api'
let syncTimer = null

// Warna-warni palet mobil agar terlihat menarik dan khas
const carColorPresets = [
	{ color: '#38bdf8', body: '#0284c7' }, // Sky Blue
	{ color: '#10b981', body: '#059669' }, // Emerald Green
	{ color: '#f59e0b', body: '#d97706' }, // Warm Amber
	{ color: '#a855f7', body: '#7e22ce' }, // Purple
	{ color: '#ec4899', body: '#be185d' }, // Rose
	{ color: '#06b6d4', body: '#0891b2' }, // Cyan
	{ color: '#e2e8f0', body: '#64748b' }  // Titanium Silver
]

// 1. Fetch Ruangan Rapat dari Backend
const fetchRooms = async () => {
	try {
		const res = await $fetch(`${apiBase}/rooms`)
		if (Array.isArray(res)) {
			roomList.value = res.map(r => ({
				id: r.id,
				name: r.name,
				capacity: r.capacity || 0,
				floor: r.floor || 'Lt 1',
				facilities: r.facilities || '',
				status: r.status || 'Tersedia',
				current_user: r.current_user || null,
				current_division: r.current_division || null,
				current_agenda: r.current_agenda || null,
				current_time: r.current_time || null,
				active_booking_id: r.active_booking_id || null
			}))
		}
	} catch (e) {
		console.warn('Gagal memuat status ruangan:', e)
	}
}

// 2. Fetch Armada Kendaraan dari Backend
const fetchLiveVehicles = async () => {
	try {
		const res = await $fetch(`${apiBase}/vehicles`)
		if (Array.isArray(res)) {
			trackCars.value = res.map((apiCar, idx) => {
				const statusKey = apiCar.status_key || (apiCar.status === 'Tersedia' ? 'available' : (apiCar.status === 'Perjalanan Pulang' ? 'returning' : 'departed'))
				const preset = carColorPresets[idx % carColorPresets.length]

				return {
					id: apiCar.id,
					name: apiCar.name,
					license_plate: apiCar.license_plate,
					statusKey: statusKey,
					status: apiCar.status || 'Tersedia',
					driver: apiCar.current_driver_name || (apiCar.has_scheduled_booking ? apiCar.scheduled_driver : 'Pengemudi Operasional'),
					division: apiCar.current_division || (apiCar.has_scheduled_booking ? apiCar.scheduled_division : 'Operasional'),
					destination: apiCar.current_destination || (apiCar.has_scheduled_booking ? apiCar.scheduled_destination : 'Pool Kantor'),
					has_scheduled: !!apiCar.has_scheduled_booking,
					scheduled_time: apiCar.scheduled_time || null,
					scheduled_user: apiCar.scheduled_user || null,
					color: apiCar.color || preset.color,
					bodyColor: apiCar.body_color || preset.body
				}
			})
		}
	} catch (e) {
		console.warn('Gagal memuat status kendaraan:', e)
	}
}

// 3. Aksi Cepat: Ubah Status Ruangan (Langsung tersimpan ke Database API)
const changeRoomStatus = async (room, s) => {
	room.status = s
	if (s === 'Sedang Digunakan' && !room.current_user) {
		room.current_user = 'Ahmad Subagja'
		room.current_division = 'Divisi Operasional'
		room.current_agenda = 'Rapat Koordinasi Mingguan'
		room.current_time = '09:00 - 11:30 WIB'
	} else if (s === 'Tersedia') {
		room.current_user = null
		room.current_division = null
		room.current_agenda = null
		room.current_time = null
	}

	try {
		await $fetch(`${apiBase}/rooms/${room.id}`, {
			method: 'PUT',
			body: { status: s }
		})
	} catch (e) {
		console.warn('Gagal update status ruangan ke backend:', e)
	}
}

// 4. Aksi Cepat: Ubah Status Mobil (Langsung sinkron dengan sistem Driver Trip)
const changeCarStatus = async (car, s) => {
	car.statusKey = s
	try {
		const endpoint = s === 'departed' ? '/driver/trip/depart' : (s === 'returning' ? '/driver/trip/return' : '/driver/trip/arrive')
		await $fetch(`${apiBase}${endpoint}`, {
			method: 'POST',
			body: {
				vehicle_id: car.id,
				license_plate: car.license_plate,
				destination: car.destination || 'Kunjungan Lapangan',
				passengers: 'Pegawai Instansi',
				division: car.division || 'Operasional'
			}
		}).catch(() => null)
	} catch (e) {
		console.warn('Gagal update status mobil:', e)
	}
}

// Computed statistik ringkasan di Topbar
const availableRoomsCount = computed(() => roomList.value.filter(r => r.status === 'Tersedia').length)
const occupiedRoomsCount = computed(() => roomList.value.filter(r => r.status === 'Sedang Digunakan').length)
const availableCarsCount = computed(() => trackCars.value.filter(c => c.statusKey === 'available').length)
const departedCarsCount = computed(() => trackCars.value.filter(c => c.statusKey === 'departed').length)
const maintCount = computed(() => roomList.value.filter(r => r.status === 'Pemeliharaan').length)

// Toggle Dark / Light Mode
const toggleTheme = () => {
	isDark.value = !isDark.value
	if (typeof window !== 'undefined') localStorage.setItem('admin_fs_theme', isDark.value ? 'dark' : 'light')
}

onMounted(() => {
	// Proteksi role (sopir & ob tidak diperkenankan akses dashboard admin command center)
	const userCookie = useCookie('auth_user')
	if (userCookie.value?.role === 'sopir') {
		navigateTo('/sopir/dashboard', { replace: true })
		return
	} else if (userCookie.value?.role === 'ob') {
		navigateTo('/ob/dashboard', { replace: true })
		return
	}

	updateClock()
	clockTimer = setInterval(updateClock, 1000)

	if (typeof window !== 'undefined') {
		const savedTheme = localStorage.getItem('admin_fs_theme') || localStorage.getItem('admin_theme')
		if (savedTheme) isDark.value = savedTheme === 'dark'
	}

	// Panggilan awal
	fetchRooms()
	fetchLiveVehicles()

	// Smart Polling setiap 3 detik (Opsi 1: Otomatis berubah secara real-time tanpa refresh halaman)
	syncTimer = setInterval(() => {
		fetchRooms()
		fetchLiveVehicles()
	}, 3000)
})

onUnmounted(() => {
	if (clockTimer) clearInterval(clockTimer)
	if (syncTimer) clearInterval(syncTimer)
})
</script>

<style scoped>
/* ══════════════════════════════════════════════════════════ RESET & BASE ═══ */
*, *::before, *::after {
	box-sizing: border-box;
	margin: 0;
	padding: 0;
}

.fs-root {
	height: 100vh;
	max-height: 100vh;
	overflow: hidden;
	display: flex;
	flex-direction: column;
	font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
	font-size: 11.5px;
	user-select: none;
	transition: background-color 0.2s ease, color 0.2s ease;
}

/* PALET TEMA MODERN */
.theme-dark {
	background-color: #070d17;
	color: #f1f5f9;
	--bg-header: rgba(11, 20, 35, 0.95);
	--border-subtle: rgba(255, 255, 255, 0.08);
	--border-strong: rgba(255, 255, 255, 0.15);
	--card-bg: rgba(14, 25, 42, 0.85);
	--card-bg-hover: rgba(20, 36, 60, 0.95);
	--card-inner: rgba(0, 0, 0, 0.25);
	--text-primary: #f8fafc;
	--text-secondary: #94a3b8;
	--text-muted: #64748b;
	--road-bg: #070f18;
}

.theme-light {
	background-color: #f1f5f9;
	color: #0f172a;
	--bg-header: rgba(255, 255, 255, 0.96);
	--border-subtle: rgba(0, 0, 0, 0.08);
	--border-strong: rgba(0, 0, 0, 0.15);
	--card-bg: #ffffff;
	--card-bg-hover: #f8fafc;
	--card-inner: rgba(0, 0, 0, 0.03);
	--text-primary: #0f172a;
	--text-secondary: #475569;
	--text-muted: #94a3b8;
	--road-bg: #e2e8f0;
}

/* ══════════════════════════════════════════════════════════ TOPBAR ═══ */
.fs-topbar {
	height: 48px;
	min-height: 48px;
	background: var(--bg-header);
	backdrop-filter: blur(12px);
	border-bottom: 1px solid var(--border-subtle);
	display: flex;
	align-items: center;
	justify-content: space-between;
	padding: 0 16px;
	z-index: 50;
}

.fs-topbar-left {
	display: flex;
	align-items: center;
	gap: 12px;
}

.btn-back-admin {
	display: inline-flex;
	align-items: center;
	gap: 6px;
	padding: 5px 12px;
	border-radius: 6px;
	background: rgba(245, 158, 11, 0.15);
	border: 1px solid rgba(245, 158, 11, 0.35);
	color: #f59e0b;
	font-weight: 700;
	font-size: 11px;
	text-decoration: none;
	transition: all 0.15s ease;
}
.btn-back-admin:hover {
	background: #f59e0b;
	color: #000000;
	transform: translateX(-2px);
}

.brand-block {
	display: flex;
	align-items: center;
	gap: 10px;
}
.brand-icon-wrap {
	width: 28px;
	height: 28px;
	display: flex;
	align-items: center;
	justify-content: center;
}
.icon-brand { width: 26px; height: 26px; }

.brand-pulse-ring {
	animation: pulseRings 2s infinite cubic-bezier(0.4, 0, 0.6, 1);
	transform-origin: center;
}
@keyframes pulseRings {
	0%, 100% { transform: scale(1); opacity: 0.8; }
	50% { transform: scale(1.6); opacity: 0; }
}

.brand-live-badge {
	display: flex;
	align-items: center;
	gap: 6px;
	font-size: 9.5px;
	font-weight: 800;
	letter-spacing: 0.06em;
	color: #ef4444;
}
.live-dot-red {
	width: 6px;
	height: 6px;
	background: #ef4444;
	border-radius: 50%;
	animation: blinkRed 1.2s infinite;
}
@keyframes blinkRed {
	0%, 100% { opacity: 1; transform: scale(1); }
	50% { opacity: 0.3; transform: scale(0.7); }
}

.sync-pill {
	background: rgba(16, 185, 129, 0.15);
	border: 1px solid rgba(16, 185, 129, 0.3);
	color: #10b981;
	font-size: 8px;
	padding: 1px 5px;
	border-radius: 4px;
	font-weight: 700;
}

.brand-title-text {
	font-size: 12px;
	font-weight: 700;
	color: var(--text-primary);
	line-height: 1.15;
}

.fs-topbar-right {
	display: flex;
	align-items: center;
	gap: 10px;
}

.status-count-chips {
	display: flex;
	align-items: center;
	gap: 6px;
}
.count-chip {
	display: inline-flex;
	align-items: center;
	gap: 5px;
	padding: 3.5px 8px;
	border-radius: 5px;
	font-size: 10.5px;
	font-weight: 600;
}
.chip-green { background: rgba(16, 185, 129, 0.14); border: 1px solid rgba(16, 185, 129, 0.3); color: #10b981; }
.chip-red   { background: rgba(239, 68, 68, 0.14); border: 1px solid rgba(239, 68, 68, 0.3); color: #ef4444; }
.chip-cyan  { background: rgba(6, 182, 212, 0.14); border: 1px solid rgba(6, 182, 212, 0.3); color: #06b6d4; }
.chip-slate { background: rgba(100, 116, 139, 0.14); border: 1px solid rgba(100, 116, 139, 0.3); color: #94a3b8; }

.count-divider {
	width: 1px;
	height: 18px;
	background: var(--border-subtle);
	margin: 0 2px;
}

.fs-clock {
	display: inline-flex;
	align-items: center;
	gap: 6px;
	font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
	font-size: 11px;
	font-weight: 700;
	color: #38bdf8;
	background: rgba(56, 189, 248, 0.1);
	border: 1px solid rgba(56, 189, 248, 0.25);
	padding: 4px 10px;
	border-radius: 6px;
}

.theme-btn {
	width: 28px;
	height: 28px;
	display: flex;
	align-items: center;
	justify-content: center;
	border-radius: 6px;
	border: 1px solid var(--border-subtle);
	background: transparent;
	color: var(--text-secondary);
	cursor: pointer;
	transition: all 0.15s ease;
}
.theme-btn:hover {
	color: #facc15;
	border-color: #facc15;
}

/* ══════════════════════════════════════════════════════════ LAYOUT UTAMA SEJAJAR 2 KOLOM ═══ */
.fs-main-parallel {
	flex: 1;
	min-height: 0;
	display: grid;
	grid-template-columns: 1fr 1fr;
	gap: 12px;
	padding: 10px 14px;
	overflow: hidden;
}

.parallel-col {
	display: flex;
	flex-direction: column;
	background: var(--card-bg);
	border: 1px solid var(--border-subtle);
	border-radius: 10px;
	padding: 10px 12px;
	min-height: 0;
	overflow: hidden;
	box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
}

.col-head {
	display: flex;
	align-items: center;
	justify-content: space-between;
	padding-bottom: 8px;
	border-bottom: 1px solid var(--border-subtle);
	margin-bottom: 8px;
	flex-shrink: 0;
}
.col-head-left {
	display: flex;
	align-items: center;
	gap: 8px;
	flex-wrap: wrap;
}
.panel-tag {
	display: inline-flex;
	align-items: center;
	gap: 4px;
	font-size: 8.5px;
	font-weight: 800;
	padding: 2px 6px;
	border-radius: 4px;
}
.tag-amber { background: rgba(245, 158, 11, 0.15); color: #f59e0b; border: 1px solid rgba(245, 158, 11, 0.35); }
.tag-cyan  { background: rgba(6, 182, 212, 0.15); color: #06b6d4; border: 1px solid rgba(6, 182, 212, 0.35); }

.live-dot-amber, .live-dot-cyan {
	width: 5px;
	height: 5px;
	border-radius: 50%;
	animation: blinkRed 1.2s infinite;
}
.live-dot-amber { background: #f59e0b; }
.live-dot-cyan  { background: #06b6d4; }

.col-title {
	font-size: 12.5px;
	font-weight: 700;
	color: var(--text-primary);
}
.col-desc {
	font-size: 9.5px;
	color: var(--text-muted);
}
.col-legend {
	display: flex;
	align-items: center;
	gap: 8px;
}
.legend-chip {
	display: inline-flex;
	align-items: center;
	gap: 3px;
	font-size: 8.5px;
	font-weight: 700;
}
.leg-dot { width: 6px; height: 6px; border-radius: 50%; }
.legend-chip.green { color: #10b981; }
.legend-chip.green .leg-dot { background: #10b981; }
.legend-chip.red   { color: #ef4444; }
.legend-chip.red .leg-dot   { background: #ef4444; }
.legend-chip.gray  { color: #94a3b8; }
.legend-chip.gray .leg-dot  { background: #94a3b8; }
.legend-chip.cyan  { color: #06b6d4; }
.legend-chip.cyan .leg-dot  { background: #06b6d4; }
.legend-chip.amber { color: #f59e0b; }
.legend-chip.amber .leg-dot { background: #f59e0b; }

.empty-state-notice {
	display: flex;
	align-items: center;
	justify-content: center;
	gap: 8px;
	padding: 40px 16px;
	color: var(--text-muted);
	font-size: 11px;
}

/* ══════════════════════════════════════════════════════════ KOLOM 1: RUANG RAPAT CARDS ═══ */
.rooms-vertical-container {
	flex: 1;
	min-height: 0;
	display: flex;
	flex-direction: column;
	gap: 6px;
	overflow: hidden;
}

.room-icon-card {
	flex: 1;
	min-height: 0;
	display: flex;
	align-items: center;
	gap: 10px;
	background: var(--card-inner);
	border: 1px solid var(--border-subtle);
	border-radius: 8px;
	padding: 6px 10px;
	transition: all 0.2s ease;
}
.room-icon-card:hover {
	border-color: var(--border-strong);
	background: var(--card-bg-hover);
}
.room-icon-card.card-open {
	border-left: 3.5px solid #10b981;
}
.room-icon-card.card-closed {
	border-left: 3.5px solid #ef4444;
	background: rgba(239, 68, 68, 0.05);
}
.room-icon-card.card-tang {
	border-left: 3.5px solid #94a3b8;
}

.room-graphic-area {
	width: 44px;
	height: 44px;
	flex-shrink: 0;
	display: flex;
	align-items: center;
	justify-content: center;
}
.graphic-box {
	width: 42px;
	height: 42px;
	display: flex;
	align-items: center;
	justify-content: center;
	border-radius: 6px;
	transition: transform 0.15s ease;
}
.graphic-box:hover {
	transform: scale(1.05);
}
.door-svg-detail {
	width: 36px;
	height: 36px;
}

.room-details-content {
	flex: 1;
	min-width: 0;
	display: flex;
	flex-direction: column;
	justify-content: center;
	gap: 3px;
}
.room-title-line {
	display: flex;
	align-items: baseline;
	gap: 8px;
}
.room-name-heading {
	font-size: 11.5px;
	font-weight: 700;
	color: var(--text-primary);
	white-space: nowrap;
	overflow: hidden;
	text-overflow: ellipsis;
}
.room-floor-tag {
	font-size: 9px;
	color: var(--text-muted);
	white-space: nowrap;
}

/* INFORMASI PENGGUNAAN RUANGAN */
.room-usage-box {
	font-size: 9.5px;
	line-height: 1.25;
}
.usage-active {
	display: flex;
	flex-direction: column;
	gap: 2px;
}
.usage-top-row {
	display: flex;
	align-items: center;
	gap: 8px;
}
.usage-division-badge {
	display: inline-flex;
	align-items: center;
	gap: 4px;
	background: rgba(239, 68, 68, 0.14);
	color: #f87171;
	border: 1px solid rgba(239, 68, 68, 0.25);
	padding: 1.5px 6px;
	border-radius: 3px;
	font-size: 9px;
	width: fit-content;
}
.meeting-live-pulse {
	display: inline-flex;
	align-items: center;
	gap: 4px;
	font-size: 8.5px;
	font-weight: 800;
	color: #ef4444;
	letter-spacing: 0.04em;
}
.pulse-mini-dot {
	width: 5px;
	height: 5px;
	background: #ef4444;
	border-radius: 50%;
	animation: blinkRed 1s infinite;
}

.usage-user-row {
	display: flex;
	align-items: center;
	gap: 8px;
	color: var(--text-secondary);
	font-size: 8.5px;
	white-space: nowrap;
	overflow: hidden;
	text-overflow: ellipsis;
}
.user-name { font-weight: 600; color: var(--text-primary); }
.agenda-text { color: var(--text-muted); }
.time-text { color: #f59e0b; font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace; font-weight: 600; }

.free-text {
	color: #10b981;
	font-size: 9px;
}
.maint-text {
	color: #94a3b8;
	font-size: 9px;
}

.room-side-action {
	display: flex;
	flex-direction: column;
	align-items: flex-end;
	gap: 4px;
	flex-shrink: 0;
}
.room-badge-pill {
	display: inline-block;
	font-size: 8.5px;
	font-weight: 800;
	padding: 2px 7px;
	border-radius: 4px;
	letter-spacing: 0.04em;
	white-space: nowrap;
}
.pill-avail { background: rgba(16, 185, 129, 0.15); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.3); }
.pill-busy  { background: rgba(239, 68, 68, 0.15); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.3); }
.pill-maint { background: rgba(100, 116, 139, 0.15); color: #94a3b8; border: 1px solid rgba(100, 116, 139, 0.3); }

.room-switcher-group {
	display: flex;
	gap: 3px;
}
.btn-switch-mini {
	padding: 2.5px 6px;
	border-radius: 4px;
	border: 1px solid var(--border-subtle);
	background: var(--card-inner);
	color: var(--text-muted);
	font-size: 8px;
	font-weight: 700;
	cursor: pointer;
	transition: all 0.12s ease;
}
.btn-switch-mini:hover {
	color: var(--text-primary);
	border-color: var(--border-strong);
}
.btn-sw-open.active  { background: #10b981; color: #ffffff; border-color: #059669; }
.btn-sw-close.active { background: #ef4444; color: #ffffff; border-color: #dc2626; }
.btn-sw-tang.active  { background: #64748b; color: #ffffff; border-color: #475569; }

/* ══════════════════════════════════════════════════════════ KOLOM 2: MOBIL DINAS CARDS ═══ */
.cars-vertical-container {
	flex: 1;
	min-height: 0;
	display: flex;
	flex-direction: column;
	gap: 5px;
	overflow: hidden;
}

.car-track-card {
	flex: 1;
	min-height: 0;
	display: flex;
	flex-direction: column;
	justify-content: space-between;
	background: var(--card-inner);
	border: 1px solid var(--border-subtle);
	border-radius: 8px;
	padding: 5px 8px;
	transition: all 0.2s ease;
}
.car-track-card:hover {
	border-color: var(--border-strong);
	background: var(--card-bg-hover);
}
.car-track-card.cstate-available { border-left: 3.5px solid #10b981; }
.car-track-card.cstate-departed  { border-left: 3.5px solid #06b6d4; background: rgba(6, 182, 212, 0.05); }
.car-track-card.cstate-returning { border-left: 3.5px solid #f59e0b; background: rgba(245, 158, 11, 0.04); }

.car-card-topbar {
	display: flex;
	align-items: center;
	justify-content: space-between;
	gap: 6px;
}
.car-id-box {
	display: flex;
	align-items: center;
	gap: 5px;
	min-width: 0;
}
.car-num-tag { font-size: 8.5px; font-weight: 800; color: var(--text-muted); }
.car-plate-code {
	font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
	font-size: 8.5px;
	font-weight: 700;
	background: rgba(255, 255, 255, 0.08);
	border: 1px solid var(--border-subtle);
	padding: 1px 5px;
	border-radius: 3px;
	color: #38bdf8;
	white-space: nowrap;
}
.car-title-text {
	font-size: 10.5px;
	font-weight: 700;
	color: var(--text-primary);
	white-space: nowrap;
	overflow: hidden;
	text-overflow: ellipsis;
}

.car-tags-cluster {
	display: flex;
	align-items: center;
	gap: 5px;
	flex-shrink: 0;
}
.car-divisi-pill {
	display: inline-flex;
	align-items: center;
	gap: 3px;
	background: rgba(56, 189, 248, 0.12);
	border: 1px solid rgba(56, 189, 248, 0.25);
	color: #38bdf8;
	font-size: 8px;
	padding: 1px 5px;
	border-radius: 3px;
	white-space: nowrap;
}

.car-state-badge {
	font-size: 8px;
	font-weight: 800;
	padding: 1.5px 5px;
	border-radius: 3px;
	letter-spacing: 0.03em;
	white-space: nowrap;
}
.badge-pool      { background: rgba(16, 185, 129, 0.15); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.3); }
.badge-scheduled { background: rgba(245, 158, 11, 0.18); color: #f59e0b; border: 1px solid rgba(245, 158, 11, 0.35); }
.badge-jalan     { background: rgba(6, 182, 212, 0.15); color: #06b6d4; border: 1px solid rgba(6, 182, 212, 0.3); }
.badge-pulang    { background: rgba(245, 158, 11, 0.15); color: #f59e0b; border: 1px solid rgba(245, 158, 11, 0.3); }

/* BARIS TENGAH: DRIVER + ASPHALT TRACK */
.car-middle-row {
	display: flex;
	align-items: center;
	gap: 8px;
	margin-top: 2px;
}
.car-trip-summary {
	width: 145px;
	flex-shrink: 0;
	font-size: 8.5px;
	line-height: 1.25;
}
.driver-lead { display: block; font-weight: 600; color: var(--text-primary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.dest-lead   { display: block; color: var(--text-muted); font-size: 8px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

/* ─── DEDICATED ROAD TRACK (ANTI TUMPANG TINDIH) ─── */
.dedicated-road-track {
	position: relative;
	flex: 1;
	height: 22px;
	background: var(--road-bg);
	border-radius: 4px;
	border: 1px solid var(--border-subtle);
	overflow: hidden;
}
.road-available { border-color: rgba(16, 185, 129, 0.25); background: linear-gradient(90deg, rgba(16,185,129,0.06), var(--road-bg)); }
.road-departed  { border-color: rgba(6, 182, 212, 0.3); background: linear-gradient(90deg, rgba(6,182,212,0.05), var(--road-bg), rgba(6,182,212,0.05)); }
.road-returning { border-color: rgba(245, 158, 11, 0.25); background: linear-gradient(90deg, rgba(245,158,11,0.06), var(--road-bg)); }

.road-center-stripe {
	position: absolute;
	top: 50%;
	transform: translateY(-50%);
	left: 20px;
	right: 20px;
	height: 1.5px;
	background: repeating-linear-gradient(90deg, rgba(255,255,255,0.25) 0px, rgba(255,255,255,0.25) 6px, transparent 6px, transparent 12px);
}

.track-gate-marker {
	position: absolute;
	top: 50%;
	transform: translateY(-50%);
	width: 14px;
	height: 14px;
	display: flex;
	align-items: center;
	justify-content: center;
	z-index: 1;
	opacity: 0.7;
}
.marker-left  { left: 3px; color: #64748b; }
.marker-right { right: 3px; }
.gate-icon { width: 10px; height: 10px; }
.gate-available { color: #10b981; opacity: 0.9; }
.gate-departed  { color: #06b6d4; opacity: 0.9; }
.gate-returning { color: #f59e0b; opacity: 0.9; }

/* ANIMASI MOBIL: BATAS PASTI ANTARA 18px DAN calc(100% - 46px) */
.track-car-vehicle {
	position: absolute;
	top: 50%;
	transform: translateY(-52%);
	z-index: 5;
}
.car-sprite-svg {
	width: 36px;
	height: 15px;
	display: block;
}

/* ARAH & PERJALANAN MOBIL:
   - Berangkat (departed): bergerak lancar menuju tujuan (kanan)
   - Kembali (returning): bergerak lancar kembali menuju pool (kiri)
   - Tersedia (available): diam rapi di pool kiri
*/
.track-car-vehicle.available {
	left: 18px;
	transform: translateY(-52%) scaleX(1);
	animation: none;
}

.track-car-vehicle.departed {
	transform: translateY(-52%) scaleX(1);
	animation: carJalanKiriKeKanan 4.2s infinite ease-in-out;
}
@keyframes carJalanKiriKeKanan {
	0%   { left: 18px; }
	100% { left: calc(100% - 46px); }
}

.track-car-vehicle.returning {
	transform: translateY(-52%) scaleX(-1);
	animation: carPulangKananKeKiri 4.2s infinite ease-in-out;
}
@keyframes carPulangKananKeKiri {
	0%   { left: calc(100% - 46px); }
	100% { left: 18px; }
}

/* SOROT LAMPU KUNING TERANG */
.headlight-glow-yellow {
	filter: drop-shadow(0 0 5px #fde047);
}

/* CAR CONTROLS */
.car-controls-cluster {
	display: flex;
	gap: 3px;
	flex-shrink: 0;
}
.btn-car-sw {
	padding: 2.5px 6px;
	border-radius: 4px;
	border: 1px solid var(--border-subtle);
	background: var(--card-inner);
	color: var(--text-muted);
	font-size: 8px;
	font-weight: 700;
	cursor: pointer;
	transition: all 0.12s ease;
}
.btn-car-sw:hover { color: var(--text-primary); border-color: var(--border-strong); }
.btn-sw-go.active   { background: #06b6d4; color: #ffffff; border-color: #0891b2; }
.btn-sw-back.active { background: #f59e0b; color: #ffffff; border-color: #d97706; }
.btn-sw-park.active { background: #10b981; color: #ffffff; border-color: #059669; }

/* UTILITY ICONS */
.icon-xxs { width: 10px; height: 10px; }
.icon-xs  { width: 13px; height: 13px; }
.icon-sm  { width: 16px; height: 16px; }
</style>
