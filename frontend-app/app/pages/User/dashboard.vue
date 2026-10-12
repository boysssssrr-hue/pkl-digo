<template>
	<div class="user-dashboard min-h-screen font-sans pb-16 relative" :class="[isDark ? 'theme-dark' : 'theme-light']">
		<!-- BACKGROUND GEDUNG KANTOR BPJS KESEHATAN (TETAP NATURAL, TANPA LAPISAN GELAP/TERANG) -->
		<div class="dashboard-bg-backdrop" aria-hidden="true"></div>

		<!-- TOPBAR TERPADU -->
		<UserTopbar />

		<!-- MAIN OVERVIEW CONTAINER -->
		<main class="dashboard-main relative z-10">
			<!-- SIMPLE QUICK SERVICES ICONS (SEDERHANA & ELEGAN) -->
			<section class="quick-services-simple">
				<NuxtLink to="/User/ATK" class="service-card-simple">
					<div class="service-icon-box bg-emerald">
						<svg viewBox="0 0 24 24" class="service-svg" fill="currentColor">
							<path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
						</svg>
					</div>
					<div class="service-info">
						<strong>Alat Tulis Kantor (ATK)</strong>
						<small>Pengambilan barang &amp; kuota</small>
					</div>
					<span class="service-arrow">→</span>
				</NuxtLink>

				<NuxtLink to="/User/ruangan" class="service-card-simple">
					<div class="service-icon-box bg-amber">
						<svg viewBox="0 0 24 24" class="service-svg" fill="currentColor">
							<path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
						</svg>
					</div>
					<div class="service-info">
						<strong>Peminjaman Ruangan</strong>
						<small>{{ availableRoomsCount }} dari {{ roomList.length }} ruang siap</small>
					</div>
					<span class="service-arrow">→</span>
				</NuxtLink>

				<NuxtLink to="/User/kendaraan" class="service-card-simple">
					<div class="service-icon-box bg-cyan">
						<svg viewBox="0 0 24 24" class="service-svg" fill="currentColor">
							<path d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-1h12v1c0 .55.45 1 1 1h1c.55 0 1-.45 1-1v-8l-2.08-5.99zM6.85 7h10.29l1.04 3H5.81l1.04-3zM19 17H5v-4.66l.12-.34h13.77l.11.34V17z"/>
						</svg>
					</div>
					<div class="service-info">
						<strong>Peminjaman Mobil</strong>
						<small>{{ availableVehiclesCount }} dari {{ vehicleList.length }} mobil siap</small>
					</div>
					<span class="service-arrow">→</span>
				</NuxtLink>

				<NuxtLink to="/User/riwayat" class="service-card-simple">
					<div class="service-icon-box bg-purple">
						<svg viewBox="0 0 24 24" class="service-svg" fill="currentColor">
							<path d="M13 3a9 9 0 0 0-9 9H1l3.89 3.89.07.14L9 12H6c0-3.87 3.13-7 7-7s7 3.13 7 7-3.13 7-7 7c-1.93 0-3.68-.79-4.94-2.06l-1.42 1.42A8.954 8.954 0 0 0 13 21a9 9 0 0 0 0-18zm-1 5v5l4.28 2.54.72-1.21-3.5-2.08V8H12z"/>
						</svg>
					</div>
					<div class="service-info">
						<strong>Riwayat Peminjaman</strong>
						<small>Tracking status peminjaman</small>
					</div>
					<span class="service-arrow">→</span>
				</NuxtLink>
			</section>

			<!-- TOP CONTROLS: DROPDOWN TABLE SELECTOR & SEARCH BAR -->
			<div class="overview-header-row">
				<!-- DROPDOWN PILIHAN TABEL (SESUAI REQUEST USER) -->
				<div class="control-dropdown-wrap">
					<svg class="control-icon" viewBox="0 0 24 24" fill="currentColor">
						<path d="M3 13h2v-2H3v2zm0 4h2v-2H3v2zm0-8h2V7H3v2zm4 4h14v-2H7v2zm0 4h14v-2H7v2zM7 7v2h14V7H7z"/>
					</svg>
					<select v-model="activeTableTab" class="table-select-input">
						<option value="both">Semua Tabel (Ruangan &amp; Mobil)</option>
						<option value="ruangan">Tabel Ruangan ({{ availableRoomsCount }} Siap)</option>
						<option value="mobil">Tabel Mobil ({{ availableVehiclesCount }} Siap)</option>
					</select>
					<svg class="dropdown-chevron" viewBox="0 0 20 20" fill="currentColor">
						<path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
					</svg>
				</div>

				<!-- SEARCH INPUT (MATCHING THE PHOTO: Q Search) -->
				<div class="search-input-wrap">
					<svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
						<circle cx="11" cy="11" r="8"></circle>
						<line x1="21" y1="21" x2="16.65" y2="16.65"></line>
					</svg>
					<input
						v-model="searchQuery"
						type="text"
						placeholder="Search..."
						class="search-input"
					/>
					<button v-if="searchQuery" @click="searchQuery = ''" class="clear-search-btn" type="button" title="Bersihkan">✕</button>
				</div>
			</div>

			<!-- ================================================================= -->
			<!-- TABEL SEJAJAR MENYAMPING (SIDE-BY-SIDE 2 COLUMNS)                  -->
			<!-- "ga ush diikuti tabel tumpuk bawah tapi tetap sejajar menyamping"  -->
			<!-- ================================================================= -->
			<div class="dashboard-tables-grid" :class="{ 'single-view': activeTableTab !== 'both' }">
				
				<!-- TABEL 1: MONITORING RUANGAN -->
				<section v-if="activeTableTab === 'both' || activeTableTab === 'ruangan'" class="table-card">
					<div class="table-card-header">
						<div class="table-card-title-group">
							<h2 class="table-card-title">MONITORING RUANGAN</h2>
							<span class="table-count-badge">{{ filteredRooms.length }} Ruang</span>
						</div>
						<NuxtLink to="/User/ruangan" class="btn-pinjam-header" title="Buka Halaman Peminjaman Ruangan">
							+ Pinjam
						</NuxtLink>
					</div>

					<!-- DESKTOP TABLE -->
					<div class="hidden sm:block table-responsive-box">
						<table class="minimal-table">
							<thead>
								<tr>
									<th class="th-num">#</th>
									<th>Ruangan</th>
									<th>Status</th>
									<th>Pemakai / Divisi</th>
									<th>Agenda &amp; Kapasitas</th>
									<th class="text-right">Aksi</th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="(room, rIdx) in filteredRooms" :key="room.id" class="table-row">
									<td class="td-num">{{ rIdx + 1 }}</td>
									<td>
										<div class="resource-title-wrap">
											<strong class="resource-title" :title="room.name">{{ room.name }}</strong>
											<span class="resource-sub">{{ room.floor }}</span>
										</div>
									</td>
									<td>
										<span
											class="status-pill"
											:class="{
												'pill-green': room.status === 'Tersedia',
												'pill-amber': room.status === 'Sedang Digunakan',
												'pill-red': room.status === 'Pemeliharaan'
											}"
										>
											{{ room.status }}
										</span>
									</td>
									<td>
										<div v-if="room.status === 'Sedang Digunakan'" class="user-row-cell">
											<div class="user-avatar-circle">
												{{ getInitials(room.current_user) }}
											</div>
											<div class="user-info-text">
												<span class="user-name" :title="room.current_user">{{ room.current_user }}</span>
												<span class="user-division">{{ room.current_division || 'Pegawai' }}</span>
											</div>
										</div>
										<span v-else-if="room.status === 'Tersedia'" class="text-available">
											Bebas Digunakan
										</span>
										<span v-else class="text-subtle">
											Pemeliharaan
										</span>
									</td>
									<td>
										<div v-if="room.status === 'Sedang Digunakan'" class="agenda-row-cell">
											<span class="agenda-title" :title="room.current_agenda">{{ room.current_agenda }}</span>
											<span class="agenda-meta">{{ room.current_time }} ({{ room.current_attendees }}/{{ room.capacity }})</span>
										</div>
										<span v-else-if="room.status === 'Tersedia'" class="text-available-desc">
											Siap dibooking (Kap: {{ room.capacity }})
										</span>
										<span v-else class="text-subtle">
											Perawatan rutin
										</span>
									</td>
									<td class="text-right">
										<NuxtLink
											to="/User/ruangan"
											class="btn-row-action"
											:class="{ 'action-active': room.status === 'Tersedia' }"
											:title="room.status === 'Tersedia' ? 'Booking Ruangan Ini' : 'Lihat Jadwal Ruangan'"
										>
											{{ room.status === 'Tersedia' ? 'Pinjam' : 'Detail' }}
										</NuxtLink>
									</td>
								</tr>

								<tr v-if="filteredRooms.length === 0">
									<td colspan="6" class="empty-table-cell">
										Tidak ada data ruangan rapat yang sesuai.
									</td>
								</tr>
							</tbody>
						</table>
					</div>

					<!-- MOBILE CARDS (SM:HIDDEN) -->
					<div class="sm:hidden divide-y divide-slate-800/40">
						<div v-for="(room, rIdx) in filteredRooms" :key="room.id" class="p-3.5 space-y-2.5">
							<div class="flex items-center justify-between">
								<div class="flex items-center gap-2">
									<span class="w-5 h-5 rounded-md bg-slate-800 text-slate-400 font-bold text-[10px] flex items-center justify-center shrink-0">
										{{ rIdx + 1 }}
									</span>
									<div>
										<strong class="text-xs font-bold block" :class="isDark ? 'text-white' : 'text-slate-900'">{{ room.name }}</strong>
										<span class="text-[10px] text-slate-400">🏢 {{ room.floor }}</span>
									</div>
								</div>
								<span
									class="status-pill text-[10px]"
									:class="{
										'pill-green': room.status === 'Tersedia',
										'pill-amber': room.status === 'Sedang Digunakan',
										'pill-red': room.status === 'Pemeliharaan'
									}"
								>
									{{ room.status }}
								</span>
							</div>

							<!-- Usage details if in use -->
							<div v-if="room.status === 'Sedang Digunakan'" class="p-2.5 rounded-xl bg-slate-950/40 border border-slate-800/60 space-y-1 text-[11px]">
								<div class="flex items-center justify-between">
									<span class="text-slate-400">👤 Pemakai:</span>
									<span class="font-semibold text-emerald-400">{{ room.current_user }} ({{ room.current_division || 'Pegawai' }})</span>
								</div>
								<div class="flex items-center justify-between">
									<span class="text-slate-400">📌 Agenda:</span>
									<span class="font-medium text-slate-200 truncate max-w-[180px]">{{ room.current_agenda }}</span>
								</div>
								<div class="flex items-center justify-between">
									<span class="text-slate-400">🕒 Waktu:</span>
									<span class="font-mono text-slate-300">{{ room.current_time }} ({{ room.current_attendees }}/{{ room.capacity }})</span>
								</div>
							</div>
							<div v-else-if="room.status === 'Tersedia'" class="flex items-center justify-between text-[11px]">
								<span class="text-emerald-500 font-medium">Bebas Digunakan</span>
								<span class="text-slate-400">Kapasitas: {{ room.capacity }} Orang</span>
							</div>

							<!-- Action -->
							<div class="pt-1 flex justify-end">
								<NuxtLink
									to="/User/ruangan"
									class="btn-row-action w-full text-center"
									:class="{ 'action-active': room.status === 'Tersedia' }"
								>
									{{ room.status === 'Tersedia' ? '📅 Pinjam Ruangan' : 'Lihat Jadwal' }}
								</NuxtLink>
							</div>
						</div>

						<div v-if="filteredRooms.length === 0" class="empty-table-cell">
							Tidak ada data ruangan rapat yang sesuai.
						</div>
					</div>
				</section>

				<!-- TABEL 2: MONITORING MOBIL DINAS -->
				<section v-if="activeTableTab === 'both' || activeTableTab === 'mobil'" class="table-card">
					<div class="table-card-header">
						<div class="table-card-title-group">
							<h2 class="table-card-title">MONITORING MOBIL DINAS</h2>
							<span class="table-count-badge">{{ filteredVehicles.length }} Mobil</span>
						</div>
						<NuxtLink to="/User/kendaraan" class="btn-pinjam-header" title="Buka Halaman Peminjaman Mobil">
							+ Pinjam
						</NuxtLink>
					</div>

					<!-- DESKTOP TABLE -->
					<div class="hidden sm:block table-responsive-box">
						<table class="minimal-table">
							<thead>
								<tr>
									<th class="th-num">#</th>
									<th>Kendaraan</th>
									<th>Status</th>
									<th>Pengemudi / Divisi</th>
									<th>Tujuan &amp; Muatan</th>
									<th class="text-right">Aksi</th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="(car, cIdx) in filteredVehicles" :key="car.id" class="table-row">
									<td class="td-num">{{ cIdx + 1 }}</td>
									<td>
										<div class="resource-title-wrap">
											<strong class="resource-title" :title="car.name">{{ car.name }}</strong>
											<span class="resource-sub font-mono">{{ car.license_plate }}</span>
										</div>
									</td>
									<td>
										<span
											class="status-pill"
											:class="{
												'pill-green': car.status === 'Tersedia',
												'pill-amber': car.status === 'Sedang Digunakan',
												'pill-slate': car.status === 'Servis'
											}"
										>
											{{ car.status }}
										</span>
									</td>
									<td>
										<div v-if="car.status === 'Sedang Digunakan'" class="user-row-cell">
											<div class="user-avatar-circle avatar-cyan">
												{{ getInitials(car.current_user) }}
											</div>
											<div class="user-info-text">
												<span class="user-name" :title="car.current_user">{{ car.current_user }}</span>
												<span class="user-division">{{ car.current_division || 'Driver Pool' }}</span>
											</div>
										</div>
										<span v-else-if="car.status === 'Tersedia'" class="text-available">
											Siap di Pool
										</span>
										<span v-else class="text-subtle">
											Servis Rutin
										</span>
									</td>
									<td>
										<div v-if="car.status === 'Sedang Digunakan'" class="agenda-row-cell">
											<span class="agenda-title" :title="car.current_destination">{{ car.current_destination }}</span>
											<span class="agenda-meta">{{ car.current_duration }} ({{ car.current_passengers }}/{{ car.capacity }} seat)</span>
										</div>
										<span v-else-if="car.status === 'Tersedia'" class="text-available-desc">
											Siap pakai (Kap: {{ car.capacity }} seat)
										</span>
										<span v-else class="text-subtle">
											Perawatan berkala
										</span>
									</td>
									<td class="text-right">
										<NuxtLink
											to="/User/kendaraan"
											class="btn-row-action"
											:class="{ 'action-active': car.status === 'Tersedia' }"
											:title="car.status === 'Tersedia' ? 'Pinjam Mobil Dinas Ini' : 'Lihat Detail Armada'"
										>
											{{ car.status === 'Tersedia' ? 'Pinjam' : 'Detail' }}
										</NuxtLink>
									</td>
								</tr>

								<tr v-if="filteredVehicles.length === 0">
									<td colspan="6" class="empty-table-cell">
										Tidak ada armada mobil dinas yang sesuai.
									</td>
								</tr>
							</tbody>
						</table>
					</div>

					<!-- MOBILE CARDS (SM:HIDDEN) -->
					<div class="sm:hidden divide-y divide-slate-800/40">
						<div v-for="(car, cIdx) in filteredVehicles" :key="car.id" class="p-3.5 space-y-2.5">
							<div class="flex items-center justify-between">
								<div class="flex items-center gap-2">
									<span class="w-5 h-5 rounded-md bg-slate-800 text-slate-400 font-bold text-[10px] flex items-center justify-center shrink-0">
										{{ cIdx + 1 }}
									</span>
									<div>
										<strong class="text-xs font-bold block" :class="isDark ? 'text-white' : 'text-slate-900'">{{ car.name }}</strong>
										<span class="text-[10px] font-mono text-slate-400">🚗 {{ car.license_plate }}</span>
									</div>
								</div>
								<span
									class="status-pill text-[10px]"
									:class="{
										'pill-green': car.status === 'Tersedia',
										'pill-amber': car.status === 'Sedang Digunakan',
										'pill-slate': car.status === 'Servis'
									}"
								>
									{{ car.status }}
								</span>
							</div>

							<!-- Usage details if in use -->
							<div v-if="car.status === 'Sedang Digunakan'" class="p-2.5 rounded-xl bg-slate-950/40 border border-slate-800/60 space-y-1 text-[11px]">
								<div class="flex items-center justify-between">
									<span class="text-slate-400">👨‍✈️ Driver:</span>
									<span class="font-semibold text-cyan-400">{{ car.current_user }}</span>
								</div>
								<div class="flex items-center justify-between">
									<span class="text-slate-400">📍 Tujuan:</span>
									<span class="font-medium text-slate-200 truncate max-w-[180px]">{{ car.current_destination }}</span>
								</div>
								<div class="flex items-center justify-between">
									<span class="text-slate-400">🕒 Berangkat:</span>
									<span class="font-mono text-slate-300">{{ car.current_duration }} ({{ car.current_passengers }}/{{ car.capacity }} seat)</span>
								</div>
							</div>
							<div v-else-if="car.status === 'Tersedia'" class="flex items-center justify-between text-[11px]">
								<span class="text-emerald-500 font-medium">Siap di Pool</span>
								<span class="text-slate-400">Kapasitas: {{ car.capacity }} Kursi</span>
							</div>

							<!-- Action -->
							<div class="pt-1 flex justify-end">
								<NuxtLink
									to="/User/kendaraan"
									class="btn-row-action w-full text-center"
									:class="{ 'action-active': car.status === 'Tersedia' }"
								>
									{{ car.status === 'Tersedia' ? '🚗 Pinjam Mobil' : 'Detail Armada' }}
								</NuxtLink>
							</div>
						</div>

						<div v-if="filteredVehicles.length === 0" class="empty-table-cell">
							Tidak ada armada mobil dinas yang sesuai.
						</div>
					</div>
				</section>
			</div>
		</main>
	</div>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue'

const router = useRouter()
const config = useRuntimeConfig()
const apiBase = config.public?.apiBase || 'http://127.0.0.1:8000/api'

// Global Shared Theme State (synced with UserTopbar)
const isDark = useState('user_theme_dark', () => true)

// Dropdown Table Selection: 'both' | 'ruangan' | 'mobil'
const activeTableTab = ref('both')

// Search Query for live filter
const searchQuery = ref('')

// Access Nuxt Cookies for Authentication
const userCookie = useCookie('auth_user')
const tokenCookie = useCookie('auth_token')

const getInitialUser = () => {
	if (userCookie.value) {
		try {
			const u = typeof userCookie.value === 'string' ? JSON.parse(userCookie.value) : userCookie.value
			if (u && u.name) return u
		} catch (e) {
			if (userCookie.value && userCookie.value.name) return userCookie.value
		}
	}
	return {
		id: null,
		name: 'Pegawai',
		nip: '',
		role: 'user',
		division: null
	}
}

const currentUser = ref(getInitialUser())

// Data Ruangan & Mobil
const roomList = ref([])
const vehicleList = ref([])

// Counts
const availableRoomsCount = computed(() => roomList.value.filter(r => r.status === 'Tersedia').length)
const availableVehiclesCount = computed(() => vehicleList.value.filter(v => v.status === 'Tersedia').length)

// Reactive Filtered Lists based on Search Query
const filteredRooms = computed(() => {
	const q = searchQuery.value.trim().toLowerCase()
	if (!q) return roomList.value
	return roomList.value.filter(r => {
		return (
			(r.name && r.name.toLowerCase().includes(q)) ||
			(r.code && r.code.toLowerCase().includes(q)) ||
			(r.floor && r.floor.toLowerCase().includes(q)) ||
			(r.status && r.status.toLowerCase().includes(q)) ||
			(r.current_user && r.current_user.toLowerCase().includes(q)) ||
			(r.current_agenda && r.current_agenda.toLowerCase().includes(q)) ||
			(r.current_division && r.current_division.toLowerCase().includes(q))
		)
	})
})

const filteredVehicles = computed(() => {
	const q = searchQuery.value.trim().toLowerCase()
	if (!q) return vehicleList.value
	return vehicleList.value.filter(v => {
		return (
			(v.name && v.name.toLowerCase().includes(q)) ||
			(v.license_plate && v.license_plate.toLowerCase().includes(q)) ||
			(v.brand && v.brand.toLowerCase().includes(q)) ||
			(v.status && v.status.toLowerCase().includes(q)) ||
			(v.current_user && v.current_user.toLowerCase().includes(q)) ||
			(v.current_destination && v.current_destination.toLowerCase().includes(q)) ||
			(v.current_division && v.current_division.toLowerCase().includes(q))
		)
	})
})

// Helper Get Initials
const getInitials = name => {
	if (!name) return 'U'
	const words = name.trim().split(' ')
	if (words.length === 1) return words[0].substring(0, 2).toUpperCase()
	return (words[0][0] + words[1][0]).toUpperCase()
}

// Toggle Theme (Dark / Light)
const toggleTheme = () => {
	isDark.value = !isDark.value
	if (typeof window !== 'undefined') {
		localStorage.setItem('user_theme', isDark.value ? 'dark' : 'light')
	}
}

// Lifecycle Mounted: Load Auth User, Theme & Data
onMounted(async () => {
	if (typeof window !== 'undefined') {
		const savedTheme = localStorage.getItem('user_theme')
		if (savedTheme) {
			isDark.value = savedTheme === 'dark'
		}

		if (userCookie.value) {
			try {
				const cookieData = typeof userCookie.value === 'string' ? JSON.parse(userCookie.value) : userCookie.value
				if (cookieData && cookieData.name) {
					currentUser.value = cookieData
					localStorage.setItem('auth_user', JSON.stringify(cookieData))
				}
			} catch (e) {
				if (userCookie.value && userCookie.value.name) {
					currentUser.value = userCookie.value
				}
			}
		}

		const activeToken = tokenCookie.value || localStorage.getItem('auth_token')
		if (activeToken) {
			try {
				const freshUser = await $fetch(`${apiBase}/me`, {
					headers: { Authorization: `Bearer ${activeToken}` }
				})
				if (freshUser && freshUser.name) {
					currentUser.value = freshUser
					userCookie.value = freshUser
					localStorage.setItem('auth_user', JSON.stringify(freshUser))
				}
			} catch (err) {
				console.log('Sesi lokal aktif digunakan.', err)
			}
		}
	}

	// Fetch Data Ruangan & Armada Mobil dari backend API
	try {
		const [roomsRes, vehiclesRes] = await Promise.all([
			$fetch(`${apiBase}/rooms`).catch(() => []),
			$fetch(`${apiBase}/vehicles`).catch(() => [])
		])

		if (Array.isArray(roomsRes)) {
			roomList.value = roomsRes.map(apiRoom => ({
				id: apiRoom.id,
				code: apiRoom.code || `RNG-${apiRoom.id}`,
				name: apiRoom.name,
				capacity: apiRoom.capacity || 0,
				floor: apiRoom.floor || 'Lantai 1',
				facilities: apiRoom.facility || apiRoom.facilities || '-',
				status: apiRoom.status || 'Tersedia',
				current_user: apiRoom.current_user || null,
				current_nip: apiRoom.current_nip || null,
				current_division: apiRoom.current_division || null,
				current_attendees: apiRoom.current_attendees || null,
				current_agenda: apiRoom.current_agenda || null,
				current_time: apiRoom.current_time || null,
				image: apiRoom.image || '/images/rooms/default-room.svg'
			}))
		} else {
			roomList.value = []
		}

		if (Array.isArray(vehiclesRes)) {
			vehicleList.value = vehiclesRes.map(apiCar => ({
				id: apiCar.id,
				license_plate: apiCar.license_plate,
				name: apiCar.name,
				brand: apiCar.brand || '-',
				transmission: apiCar.transmission || 'Automatic',
				capacity: apiCar.capacity || 0,
				status: apiCar.status || 'Tersedia',
				current_user: apiCar.current_driver_name || apiCar.current_user || null,
				current_nip: apiCar.current_nip || null,
				current_division: apiCar.current_division || null,
				current_passengers: apiCar.current_passengers || null,
				current_destination: apiCar.current_destination || null,
				current_duration: apiCar.current_duration || null,
				image: apiCar.image || '/images/vehicles/default-vehicle.svg'
			}))
		} else {
			vehicleList.value = []
		}
	} catch (err) {
		console.warn('Gagal memuat status fasilitas dari database:', err)
		roomList.value = []
		vehicleList.value = []
	}
})
</script>

<style scoped>
/* ========================================================================== */
/* COLOR SYSTEM & THEME TOKENS (MATCHING THE SCREENSHOT EXACTLY)             */
/* ========================================================================== */
.user-dashboard {
	min-height: 100vh;
	font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
	transition: background-color 0.25s ease, color 0.25s ease;
	background-color: transparent !important;
	color: var(--text-body);
	position: relative;
	overflow-x: hidden;
}

/* BACKGROUND GEDUNG KANTOR BPJS KESEHATAN (TETAP NATURAL, TANPA LAPISAN GELAP/TERANG) */
.dashboard-bg-backdrop {
	position: fixed;
	inset: 0;
	background-image: url('/images/dashboard-bg.jpg');
	background-size: cover;
	background-position: center center;
	background-repeat: no-repeat;
	pointer-events: none;
	z-index: 0;
	opacity: 1;
}

/* THEME DARK: KOTAK TRANSPARAN & GARIS HIJAU NYALA SEPERTI LAMPU */
.theme-dark {
	--bg-page: transparent;
	--bg-card: rgba(11, 19, 32, 0.55);
	--border-card: #10b981;
	--border-table: rgba(16, 185, 129, 0.45);
	--border-row: rgba(16, 185, 129, 0.2);
	--card-shadow: 0 0 22px rgba(16, 185, 129, 0.38), inset 0 0 16px rgba(16, 185, 129, 0.08);
	--text-heading: #ffffff;
	--text-body: #f8fafc;
	--text-muted: #cbd5e1;
	--text-subtle: #94a3b8;
	--bg-input: rgba(5, 10, 20, 0.75);
	--border-input: rgba(16, 185, 129, 0.55);
	--row-hover: rgba(16, 185, 129, 0.12);
	--th-bg: rgba(11, 19, 32, 0.85);
	--chip-green-bg: rgba(34, 197, 94, 0.25);
	--chip-green-text: #4ade80;
	--chip-amber-bg: rgba(245, 158, 11, 0.25);
	--chip-amber-text: #fbbf24;
	--chip-red-bg: rgba(239, 68, 68, 0.25);
	--chip-red-text: #f87171;
	--chip-slate-bg: rgba(148, 163, 184, 0.2);
	--chip-slate-text: #cbd5e1;
	--avatar-bg: #1e293b;
	--avatar-text: #cbd5e1;
}

/* THEME LIGHT: KOTAK TRANSPARAN & GARIS WARNA ELEGAN */
.theme-light {
	--bg-page: transparent;
	--bg-card: rgba(255, 255, 255, 0.72);
	--border-card: rgba(203, 213, 225, 0.85);
	--border-table: rgba(226, 232, 240, 0.9);
	--border-row: rgba(241, 245, 249, 0.85);
	--card-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
	--text-heading: #0f172a;
	--text-body: #1e293b;
	--text-muted: #475569;
	--text-subtle: #64748b;
	--bg-input: rgba(255, 255, 255, 0.85);
	--border-input: #cbd5e1;
	--row-hover: rgba(16, 185, 129, 0.06);
	--th-bg: rgba(255, 255, 255, 0.9);
	--chip-green-bg: #dcfce7;
	--chip-green-text: #16a34a;
	--chip-amber-bg: #fef3c7;
	--chip-amber-text: #d97706;
	--chip-red-bg: #fee2e2;
	--chip-red-text: #dc2626;
	--chip-slate-bg: #f1f5f9;
	--chip-slate-text: #64748b;
	--avatar-bg: #e2e8f0;
	--avatar-text: #475569;
}

/* MAIN CONTAINER */
.dashboard-main {
	max-width: 1280px;
	margin: 0 auto;
	padding: 20px 16px 48px;
}

@media (min-width: 640px) {
	.dashboard-main {
		padding: 24px 24px 56px;
	}
}

@media (min-width: 1024px) {
	.dashboard-main {
		padding: 28px 32px 64px;
	}
}

/* ========================================================================== */
/* QUICK SERVICES SIMPLE CARDS                                                */
/* ========================================================================== */
.quick-services-simple {
	display: grid;
	grid-template-columns: repeat(2, minmax(0, 1fr));
	gap: 12px;
	margin-bottom: 24px;
}

@media (min-width: 1024px) {
	.quick-services-simple {
		grid-template-columns: repeat(4, minmax(0, 1fr));
		gap: 16px;
		margin-bottom: 28px;
	}
}

@media (max-width: 640px) {
	.quick-services-simple {
		grid-template-columns: repeat(2, minmax(0, 1fr));
		gap: 10px;
		margin-bottom: 18px;
	}
	.service-card-simple {
		padding: 10px 12px;
		gap: 8px;
	}
	.service-icon-box {
		width: 32px;
		height: 32px;
	}
	.service-info strong {
		font-size: 11px;
	}
	.service-info small {
		font-size: 9px;
	}
	.service-arrow {
		display: none;
	}
}

.service-card-simple {
	display: flex;
	align-items: center;
	gap: 12px;
	padding: 12px 16px;
	background-color: var(--bg-card);
	border: 1px solid var(--border-card);
	border-radius: 12px;
	text-decoration: none;
	color: var(--text-heading);
	box-shadow: var(--card-shadow);
	backdrop-filter: blur(16px);
	-webkit-backdrop-filter: blur(16px);
	transition: all 0.2s ease;
}

.service-card-simple:hover {
	border-color: #10b981;
	transform: translateY(-2px);
	box-shadow: 0 8px 24px rgba(16, 185, 129, 0.25);
}

.service-icon-box {
	width: 38px;
	height: 38px;
	border-radius: 10px;
	display: flex;
	align-items: center;
	justify-content: center;
	flex-shrink: 0;
}

.service-icon-box.bg-emerald { background: rgba(16, 185, 129, 0.15); color: #10b981; }
.service-icon-box.bg-amber { background: rgba(245, 158, 11, 0.15); color: #f59e0b; }
.service-icon-box.bg-cyan { background: rgba(6, 182, 212, 0.15); color: #06b6d4; }
.service-icon-box.bg-purple { background: rgba(168, 85, 247, 0.15); color: #a855f7; }

.service-svg {
	width: 20px;
	height: 20px;
}

.service-info {
	display: flex;
	flex-direction: column;
	gap: 2px;
	flex: 1;
	min-width: 0;
}

.service-info strong {
	font-size: 13px;
	font-weight: 700;
	color: var(--text-heading);
	white-space: nowrap;
	overflow: hidden;
	text-overflow: ellipsis;
}

.service-info small {
	font-size: 11px;
	color: var(--text-muted);
	white-space: nowrap;
	overflow: hidden;
	text-overflow: ellipsis;
}

.service-arrow {
	font-size: 14px;
	color: var(--text-muted);
	transition: transform 0.15s ease, color 0.15s ease;
}

.service-card-simple:hover .service-arrow {
	transform: translateX(3px);
	color: #10b981;
}

/* ========================================================================== */
/* OVERVIEW HEADER ROW (MATCHING SCREENSHOT)                                 */
/* ========================================================================== */
.overview-header-row {
	display: flex;
	flex-direction: column;
	gap: 16px;
	margin-bottom: 24px;
}

@media (min-width: 768px) {
	.overview-header-row {
		flex-direction: row;
		align-items: center;
		justify-content: space-between;
	}
}

.overview-title-wrap {
	display: flex;
	align-items: center;
	gap: 12px;
}

.overview-title {
	font-size: 24px;
	font-weight: 800;
	letter-spacing: -0.02em;
	color: var(--text-heading);
	margin: 0;
}

/* OVERVIEW CONTROLS (DROPDOWN + SEARCH BAR) */
.overview-controls {
	display: flex;
	flex-wrap: wrap;
	align-items: center;
	gap: 12px;
}

/* CUSTOM DROPDOWN SELECT (MENU PILIHAN TABEL) */
.control-dropdown-wrap {
	position: relative;
	display: flex;
	align-items: center;
}

.control-icon {
	position: absolute;
	left: 12px;
	width: 16px;
	height: 16px;
	color: var(--text-muted);
	pointer-events: none;
}

.table-select-input {
	appearance: none;
	-webkit-appearance: none;
	background-color: var(--bg-card);
	border: 1px solid var(--border-input);
	color: var(--text-heading);
	font-size: 13px;
	font-weight: 600;
	padding: 8px 36px 8px 34px;
	border-radius: 10px;
	cursor: pointer;
	outline: none;
	box-shadow: var(--card-shadow);
	backdrop-filter: blur(12px);
	-webkit-backdrop-filter: blur(12px);
	transition: border-color 0.15s ease, box-shadow 0.15s ease;
}

.table-select-input option {
	background-color: #0b1320;
	color: #ffffff;
}

.theme-light .table-select-input option {
	background-color: #ffffff;
	color: #0f172a;
}

.table-select-input:focus {
	border-color: #10b981;
	box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2);
}

.dropdown-chevron {
	position: absolute;
	right: 10px;
	width: 16px;
	height: 16px;
	color: var(--text-muted);
	pointer-events: none;
}

/* SEARCH INPUT WRAP (Q Search IN PHOTO) */
.search-input-wrap {
	position: relative;
	display: flex;
	align-items: center;
	min-width: 200px;
	flex: 1;
}

@media (min-width: 768px) {
	.search-input-wrap {
		flex: initial;
		width: 220px;
	}
}

.search-icon {
	position: absolute;
	left: 12px;
	width: 15px;
	height: 15px;
	color: var(--text-muted);
	pointer-events: none;
}

.search-input {
	width: 100%;
	background-color: var(--bg-card);
	border: 1px solid var(--border-input);
	color: var(--text-heading);
	font-size: 13px;
	padding: 8px 30px 8px 34px;
	border-radius: 10px;
	outline: none;
	box-shadow: var(--card-shadow);
	backdrop-filter: blur(12px);
	-webkit-backdrop-filter: blur(12px);
	transition: border-color 0.15s ease, box-shadow 0.15s ease;
}

.search-input::placeholder {
	color: var(--text-muted);
}

.search-input:focus {
	border-color: #10b981;
	box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2);
}

.clear-search-btn {
	position: absolute;
	right: 10px;
	background: none;
	border: none;
	color: var(--text-muted);
	font-size: 12px;
	cursor: pointer;
	padding: 2px 4px;
}

.clear-search-btn:hover {
	color: var(--text-heading);
}

/* ========================================================================== */
/* TABLES GRID: SEJAJAR MENYAMPING (SIDE-BY-SIDE)                             */
/* ========================================================================== */
.dashboard-tables-grid {
	display: grid;
	grid-template-columns: 1fr;
	gap: 20px;
	align-items: start;
	width: 100%;
}

/* ON LARGE SCREENS (DESKTOP / LAPTOP): SEJAJAR MENYAMPING (2 EQUAL COLUMNS) */
@media (min-width: 1024px) {
	.dashboard-tables-grid {
		grid-template-columns: repeat(2, minmax(0, 1fr));
		gap: 20px;
	}
}

.dashboard-tables-grid.single-view {
	grid-template-columns: 1fr;
}

/* ========================================================================== */
/* TABLE CARD STYLING (CLEAN, PRECISE & STRICT BOUNDARIES)                    */
/* ========================================================================== */
.table-card {
	background-color: var(--bg-card);
	border: 1px solid var(--border-card);
	border-radius: 16px;
	overflow: hidden;
	box-shadow: var(--card-shadow);
	backdrop-filter: blur(16px);
	-webkit-backdrop-filter: blur(16px);
	display: flex;
	flex-direction: column;
	min-width: 0;
	width: 100%;
	box-sizing: border-box;
}

.table-card-header {
	display: flex;
	align-items: center;
	justify-content: space-between;
	padding: 14px 18px;
	border-bottom: 1px solid var(--border-table);
	flex-shrink: 0;
}

.table-card-title-group {
	display: flex;
	align-items: center;
	gap: 8px;
	min-width: 0;
}

.table-card-title {
	font-size: 11px;
	font-weight: 800;
	letter-spacing: 0.08em;
	text-transform: uppercase;
	color: var(--text-muted);
	margin: 0;
	white-space: nowrap;
}

.table-count-badge {
	font-size: 10px;
	font-weight: 700;
	padding: 2px 7px;
	border-radius: 9999px;
	background: var(--border-row);
	color: var(--text-muted);
	white-space: nowrap;
}

.btn-pinjam-header {
	font-size: 11px;
	font-weight: 700;
	color: #10b981;
	background: rgba(16, 185, 129, 0.1);
	padding: 4px 10px;
	border-radius: 8px;
	text-decoration: none;
	transition: all 0.15s ease;
	white-space: nowrap;
}

.btn-pinjam-header:hover {
	background: #10b981;
	color: #ffffff;
}

/* ========================================================================== */
/* TABLE STRUCTURE & CELLS (PRECISE PADDING & CUSTOM SLEEK SCROLLBAR)         */
/* ========================================================================== */
.table-responsive-box {
	overflow-x: auto;
	width: 100%;
	scrollbar-width: thin;
	scrollbar-color: rgba(16, 185, 129, 0.3) transparent;
}

.table-responsive-box::-webkit-scrollbar {
	height: 5px;
}

.table-responsive-box::-webkit-scrollbar-track {
	background: transparent;
}

.table-responsive-box::-webkit-scrollbar-thumb {
	background: rgba(16, 185, 129, 0.28);
	border-radius: 9999px;
}

.table-responsive-box::-webkit-scrollbar-thumb:hover {
	background: rgba(16, 185, 129, 0.5);
}

.minimal-table {
	width: 100%;
	border-collapse: collapse;
	text-align: left;
	font-size: 11.5px;
	min-width: 480px;
}

.minimal-table thead th {
	background-color: var(--th-bg);
	color: var(--text-muted);
	font-size: 10.5px;
	font-weight: 700;
	letter-spacing: 0.02em;
	padding: 10px 12px;
	border-bottom: 1px solid var(--border-table);
	white-space: nowrap;
}

.th-num {
	width: 28px;
	text-align: center;
	padding-left: 10px !important;
	padding-right: 4px !important;
}

.minimal-table tbody tr {
	border-bottom: 1px solid var(--border-row);
	transition: background-color 0.15s ease;
}

.minimal-table tbody tr:last-child {
	border-bottom: none;
}

.minimal-table tbody tr:hover {
	background-color: var(--row-hover);
}

.minimal-table td {
	padding: 10px 12px;
	vertical-align: middle;
	color: var(--text-body);
}

.td-num {
	text-align: center;
	font-size: 10.5px;
	color: var(--text-subtle);
	font-weight: 600;
	padding-left: 10px !important;
	padding-right: 4px !important;
}

/* RESOURCE TITLE (ROOM / VEHICLE) */
.resource-title-wrap {
	display: flex;
	flex-direction: column;
	gap: 2px;
	min-width: 0;
	max-width: 140px;
}

.resource-title {
	font-size: 12.5px;
	font-weight: 700;
	color: var(--text-heading);
	line-height: 1.25;
	white-space: nowrap;
	overflow: hidden;
	text-overflow: ellipsis;
}

.resource-sub {
	font-size: 10.5px;
	color: var(--text-muted);
	white-space: nowrap;
	overflow: hidden;
	text-overflow: ellipsis;
}

/* STATUS PILL BADGE (MATCHING SCREENSHOT SHIPPED / PENDING / ONLINE) */
.status-pill {
	display: inline-flex;
	align-items: center;
	padding: 3px 9px;
	border-radius: 9999px;
	font-size: 10.5px;
	font-weight: 700;
	line-height: 1.2;
	white-space: nowrap;
}

.pill-green {
	background-color: var(--chip-green-bg);
	color: var(--chip-green-text);
}

.pill-amber {
	background-color: var(--chip-amber-bg);
	color: var(--chip-amber-text);
}

.pill-red {
	background-color: var(--chip-red-bg);
	color: var(--chip-red-text);
}

.pill-slate {
	background-color: var(--chip-slate-bg);
	color: var(--chip-slate-text);
}

/* USER / DRIVER CELL (ACTIVE USERS STYLE) */
.user-row-cell {
	display: flex;
	align-items: center;
	gap: 8px;
	min-width: 0;
	max-width: 130px;
}

.user-avatar-circle {
	width: 26px;
	height: 26px;
	border-radius: 50%;
	background-color: var(--avatar-bg);
	color: var(--avatar-text);
	display: flex;
	align-items: center;
	justify-content: center;
	font-size: 9.5px;
	font-weight: 800;
	flex-shrink: 0;
}

.avatar-cyan {
	background-color: rgba(6, 182, 212, 0.15);
	color: #06b6d4;
}

.user-info-text {
	display: flex;
	flex-direction: column;
	gap: 1px;
	min-width: 0;
	flex: 1;
}

.user-name {
	font-size: 11.5px;
	font-weight: 700;
	color: var(--text-heading);
	white-space: nowrap;
	overflow: hidden;
	text-overflow: ellipsis;
}

.user-division {
	font-size: 9.5px;
	color: var(--text-muted);
	white-space: nowrap;
	overflow: hidden;
	text-overflow: ellipsis;
}

.text-available {
	font-size: 11px;
	font-weight: 600;
	color: #10b981;
	white-space: nowrap;
}

.text-subtle {
	font-size: 11px;
	color: var(--text-subtle);
	white-space: nowrap;
}

/* AGENDA & TIME CELL */
.agenda-row-cell {
	display: flex;
	flex-direction: column;
	gap: 2px;
	min-width: 0;
	max-width: 150px;
}

.agenda-title {
	font-size: 11.5px;
	font-weight: 600;
	color: var(--text-heading);
	white-space: nowrap;
	overflow: hidden;
	text-overflow: ellipsis;
}

.agenda-meta {
	font-size: 9.5px;
	color: var(--text-muted);
	white-space: nowrap;
	overflow: hidden;
	text-overflow: ellipsis;
}

.text-available-desc {
	font-size: 11px;
	color: var(--text-muted);
	white-space: nowrap;
}

/* ACTION BUTTON */
.btn-row-action {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	padding: 4px 10px;
	border-radius: 6px;
	font-size: 11px;
	font-weight: 700;
	text-decoration: none;
	background: var(--border-row);
	color: var(--text-muted);
	transition: all 0.15s ease;
}

.btn-row-action.action-active {
	background: rgba(16, 185, 129, 0.15);
	color: #10b981;
}

.btn-row-action.action-active:hover {
	background: #10b981;
	color: #ffffff;
}

.btn-row-action:hover:not(.action-active) {
	background: var(--border-input);
	color: var(--text-heading);
}

.empty-table-cell {
	text-align: center;
	padding: 32px 16px;
	color: var(--text-muted);
	font-size: 12px;
}

.text-right {
	text-align: right;
}
</style>
