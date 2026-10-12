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
				<NuxtLink to="/Admin/fullscreen" class="nav-fullscreen-entry">
					<i class="nav-svg-box">
						<svg viewBox="0 0 24 24" class="svg-icon-nav" fill="currentColor"><path d="M7 14H5v5h5v-2H7v-3zm-2-4h2V7h3V5H5v5zm12 7h-3v2h5v-5h-2v3zM14 5v2h3v3h2V5h-5z"/></svg>
					</i>
					<span>Full Screen</span>
				</NuxtLink>
			</nav>

			<div class="account active" role="button" tabindex="0">
				<div class="user-avatar overflow-hidden">
					<img v-if="adminUser.avatar" :src="adminUser.avatar" class="w-full h-full object-cover" />
					<span v-else>{{ adminAvatarLetter }}</span>
				</div>
				<div>
					<b>{{ adminUser.name || 'Admin' }}</b>
					<small>Administrator</small>
				</div>
				<button type="button" @click.stop="router.push('/admin/dashboard')" title="Kembali ke Dashboard">
					<svg viewBox="0 0 24 24" class="svg-icon-sm" fill="currentColor"><path d="M10 9V5l-7 7 7 7v-4.1c5 0 8.5 1.6 11 5.1-1-5-4-10-11-11z"/></svg>
				</button>
			</div>
			<button class="logout" type="button" @click="handleLogout">
				<svg viewBox="0 0 24 24" class="svg-icon-sm" fill="currentColor"><path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/></svg>
				<span>Logout</span>
			</button>
		</aside>

		<!-- Main Content Area -->
		<main class="main">
			<!-- Topbar -->
			<header class="topbar">
				<button class="mobile-menu" type="button" @click="menuOpen = !menuOpen">☰</button>
				<div class="topbar-title-wrap">
					<h2 class="text-base font-bold text-slate-100 flex items-center gap-2">
						<span>Profil Administrator</span>
						<span class="px-2 py-0.5 rounded-md text-[10px] font-black bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">SUPER ADMIN</span>
					</h2>
				</div>

				<div class="top-user">
					<!-- Realtime Live Date & Clock -->
					<RealtimeClock :theme="isDark ? 'dark' : 'light'" variant="admin" class="hidden sm:inline-flex" />

					<!-- Theme Mode Toggle -->
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

					<div class="user-chip-top">
						<span class="user-dot overflow-hidden">
							<img v-if="adminUser.avatar" :src="adminUser.avatar" class="w-full h-full object-cover" />
							<span v-else>{{ adminAvatarLetter }}</span>
						</span>
						<b>{{ adminUser.name || 'Admin' }}</b>
					</div>
				</div>
			</header>

			<!-- Toast Notification -->
			<transition enter-active-class="transition duration-300 ease-out" enter-from-class="transform -translate-y-4 opacity-0" enter-to-class="transform translate-y-0 opacity-100" leave-active-class="transition duration-200 ease-in" leave-from-class="opacity-100" leave-to-class="opacity-0">
				<div v-if="toast.show" class="fixed top-20 right-6 z-50 max-w-md px-5 py-4 rounded-2xl shadow-2xl flex items-center gap-3 border backdrop-blur-md" :class="toast.type === 'success' ? 'bg-emerald-950/95 border-emerald-500/50 text-emerald-100' : 'bg-rose-950/95 border-rose-500/50 text-rose-100'">
					<span class="text-2xl">{{ toast.type === 'success' ? '✅' : '⚠️' }}</span>
					<div class="flex-1 text-xs">
						<strong class="block font-bold text-sm mb-0.5">{{ toast.title }}</strong>
						<span>{{ toast.message }}</span>
					</div>
					<button @click="toast.show = false" class="text-slate-400 hover:text-white text-base">✕</button>
				</div>
			</transition>

			<!-- Page Body -->
			<div class="page-content">
				<!-- Hero Banner -->
				<section class="profile-hero-banner">
					<div class="banner-text">
						<div class="badge-role-admin">
							<span class="pulse-dot-green"></span>
							<span>HAK AKSES ADMINISTRATOR LENGKAP</span>
						</div>
						<h1>Pengaturan Profil &amp; Kredensial Administrator</h1>
						<p>
							Sebagai <strong>Administrator Sistem</strong>, Anda memiliki wewenang penuh untuk mengubah <strong>seluruh data</strong> akun Anda sendiri, meliputi Foto Profil, Nama Lengkap, NIP/Username Login, dan Kata Sandi.
						</p>
					</div>
				</section>

				<!-- Main Form Grid -->
				<div class="profile-grid">
					
					<!-- LEFT: FOTO PROFIL SECTION -->
					<div class="profile-card avatar-card">
						<div class="card-header-row">
							<h3 class="card-title">Foto Profil Administrator</h3>
							<span class="status-badge-blue">Bisa Diubah</span>
						</div>

						<div class="avatar-preview-box">
							<div class="avatar-circle-lg">
								<img
									v-if="previewAvatar"
									:src="previewAvatar"
									:alt="form.name"
									class="avatar-img-fit"
									@error="previewAvatar = null"
								/>
								<div v-else class="avatar-initials-lg">
									{{ adminAvatarLetter }}
								</div>
								<button
									type="button"
									class="btn-camera-overlay"
									@click="triggerFileInput"
									title="Ganti Foto Profil"
								>
									📷
								</button>
							</div>

							<!-- Hidden File Input -->
							<input
								ref="fileInputRef"
								type="file"
								accept="image/png,image/jpeg,image/jpg,image/webp"
								class="hidden"
								@change="onFileSelected"
							/>

							<div class="avatar-meta mt-3 text-center">
								<strong class="block text-base text-white">{{ form.name || 'Administrator' }}</strong>
								<span class="text-xs text-emerald-400 font-mono">NIP: {{ form.nip || '-' }}</span>
							</div>
						</div>

						<!-- Avatar Action Buttons -->
						<div class="avatar-controls space-y-3 mt-4">
							<button
								type="button"
								class="btn-upload-file"
								@click="triggerFileInput"
							>
								📁 Pilih Foto dari Komputer
							</button>

							<!-- Preset Quick Selection -->
							<div class="preset-wrap">
								<span class="preset-label">Atau Pilih Avatar Favorit:</span>
								<div class="preset-grid">
									<button
										v-for="(av, idx) in presetAvatars"
										:key="idx"
										type="button"
										class="preset-item"
										:class="{ active: previewAvatar === av.url }"
										@click="selectPreset(av)"
										:title="av.name"
									>
										<img :src="av.url" :alt="av.name" class="preset-img" />
									</button>
								</div>
							</div>

							<!-- URL Input Option -->
							<div class="url-input-wrap">
								<span class="preset-label">Tautan / URL Gambar:</span>
								<div class="url-flex">
									<input
										v-model="customUrlInput"
										type="url"
										placeholder="https://.../foto.png"
										class="input-custom-url"
									/>
									<button type="button" class="btn-url-apply" @click="applyCustomUrl">
										Pakai
									</button>
								</div>
							</div>

							<button
								v-if="previewAvatar"
								type="button"
								class="btn-remove-avatar"
								@click="removeAvatar"
							>
								🗑️ Reset / Hapus Foto
							</button>
						</div>
					</div>

					<!-- RIGHT: DATA AKUN & KREDENSIAL (BISA MERUBAH SEMUANYA) -->
					<div class="profile-card form-card">
						<div class="card-header-row">
							<div>
								<h3 class="card-title">Kredensial &amp; Data Akun</h3>
								<p class="card-subtitle">Semua bagian di bawah ini dapat Anda ubah sesuai kebutuhan.</p>
							</div>
							<span class="status-badge-green">✨ Bebas Diubah</span>
						</div>

						<form @submit.prevent="saveAllChanges" class="space-y-4 mt-2">
							<!-- 1. NAMA LENGKAP -->
							<div class="form-group">
								<label class="form-label">
									<span>Nama Lengkap Administrator</span>
									<span class="badge-edit-privilege">Bisa Diubah</span>
								</label>
								<div class="input-with-icon">
									<span class="input-icon">👤</span>
									<input
										v-model="form.name"
										type="text"
										required
										placeholder="Masukkan nama lengkap"
										class="form-control"
									/>
								</div>
								<small class="field-hint">Nama resmi akun yang akan ditampilkan di seluruh sistem SIMPEL.</small>
							</div>

							<!-- 2. NIP / USERNAME LOGIN -->
							<div class="form-group">
								<label class="form-label">
									<span>Nomor Induk Pegawai (NIP) / Username Login</span>
									<span class="badge-edit-privilege">Bisa Diubah</span>
								</label>
								<div class="input-with-icon">
									<span class="input-icon">🪪</span>
									<input
										v-model="form.nip"
										type="text"
										required
										placeholder="Contoh: 12345"
										class="form-control font-mono font-bold"
									/>
								</div>
								<small class="field-hint">NIP digunakan sebagai username untuk masuk/login ke sistem.</small>
							</div>

							<!-- 3. KATA SANDI (PASSWORD) -->
							<div class="form-group">
								<label class="form-label">
									<span>Kata Sandi (Password Akun)</span>
									<span class="badge-edit-privilege">Bisa Diubah</span>
								</label>
								<div class="input-with-icon">
									<span class="input-icon">🔑</span>
									<input
										v-model="form.password"
										:type="showPassword ? 'text' : 'password'"
										required
										minlength="4"
										placeholder="Ketik password baru"
										class="form-control font-mono font-bold"
									/>
									<button
										type="button"
										class="btn-toggle-eye"
										@click="showPassword = !showPassword"
										title="Lihat / Sembunyikan"
									>
										{{ showPassword ? '🙈' : '👁️' }}
									</button>
								</div>
								<small class="field-hint">Password saat ini ditampilkan / dapat diganti langsung dengan yang baru.</small>
							</div>

							<!-- 4. ROLE & HAK AKSES -->
							<div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-2">
								<div class="p-3.5 rounded-xl bg-slate-900/80 border border-slate-800">
									<small class="text-slate-400 block text-[10px] uppercase font-bold tracking-wider">Peran Akun</small>
									<strong class="text-emerald-400 text-sm block mt-0.5">Administrator Utama</strong>
									<span class="text-[10px] text-slate-500">Akses penuh seluruh modul</span>
								</div>

								<div class="p-3.5 rounded-xl bg-slate-900/80 border border-slate-800">
									<small class="text-slate-400 block text-[10px] uppercase font-bold tracking-wider">Status Akun</small>
									<strong class="text-white text-sm flex items-center gap-1.5 mt-0.5">
										<span class="w-2 h-2 rounded-full bg-emerald-400"></span>
										Aktif
									</strong>
									<span class="text-[10px] text-slate-500">Terautentikasi di database</span>
								</div>
							</div>

							<!-- ACTION BUTTONS -->
							<div class="form-actions pt-4 border-t border-slate-800 flex items-center gap-3">
								<button
									type="submit"
									:disabled="isSubmitting"
									class="btn-submit-all"
								>
									<span v-if="isSubmitting" class="animate-spin text-base">⏳</span>
									<span v-else class="text-base">💾</span>
									<span>{{ isSubmitting ? 'Menyimpan Perubahan...' : 'Simpan Semua Perubahan' }}</span>
								</button>

								<button
									type="button"
									class="btn-reset"
									@click="resetFormToCurrent"
									:disabled="isSubmitting"
								>
									Batal
								</button>
							</div>
						</form>
					</div>

				</div>
			</div>
		</main>
	</div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue'

const router = useRouter()
const config = useRuntimeConfig()
const apiBase = config.public?.apiBase || 'http://127.0.0.1:8000/api'

// Cookies
const userCookie = useCookie('auth_user')
const tokenCookie = useCookie('auth_token')

// Theme state
const isDark = ref(true)
const menuOpen = ref(false)

// Admin User State
const adminUser = ref({
	id: null,
	name: 'Administrator',
	nip: '12345',
	visible_password: '',
	avatar: null,
	role: 'admin',
	status: 'active'
})

// Original NIP to identify user if NIP changes
const originNip = ref('12345')

// Form State (BISA MERUBAH SEMUANYA)
const form = reactive({
	name: '',
	nip: '',
	password: ''
})

// Photo state
const previewAvatar = ref(null)
const selectedFile = ref(null)
const customUrlInput = ref('')
const fileInputRef = ref(null)
const showPassword = ref(false)
const isSubmitting = ref(false)

// Preset Avatars
const presetAvatars = [
	{ name: 'Admin Pria 1', url: 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=150&auto=format&fit=crop&q=80' },
	{ name: 'Admin Wanita 1', url: 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=150&auto=format&fit=crop&q=80' },
	{ name: 'Admin Pria 2', url: 'https://images.unsplash.com/photo-1570295999919-56ceb5ecca61?w=150&auto=format&fit=crop&q=80' },
	{ name: 'Admin Wanita 2', url: 'https://images.unsplash.com/photo-1580489944761-15a19d654956?w=150&auto=format&fit=crop&q=80' }
]

// Toast state
const toast = ref({
	show: false,
	title: '',
	message: '',
	type: 'success'
})

const showToast = (title, message, type = 'success') => {
	toast.value = { show: true, title, message, type }
	setTimeout(() => {
		toast.value.show = false
	}, 4000)
}

// Menus Sidebar
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
		active: false,
		iconSvg: '<svg viewBox="0 0 24 24" class="svg-icon-nav" fill="currentColor"><path d="M10 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2h-8l-2-2z"/></svg>'
	}
])

const activate = async (selected) => {
	menus.value.forEach(item => { item.active = item.name === selected.name })
	if (selected.name === 'Manajemen Alat') await router.push('/admin/dashboard')
	if (selected.name === 'Laporan') await router.push('/admin/laporan')
	if (selected.name === 'Pengguna') await router.push('/admin/pengguna')
	if (selected.name === 'Divisi') await router.push('/admin/divisi')
}

const toggleTheme = () => {
	isDark.value = !isDark.value
	if (typeof window !== 'undefined') {
		localStorage.setItem('admin_theme', isDark.value ? 'dark' : 'light')
	}
}

const adminAvatarLetter = computed(() => {
	return (form.name || adminUser.value?.name || 'A').charAt(0).toUpperCase()
})

const triggerFileInput = () => {
	if (fileInputRef.value) fileInputRef.value.click()
}

const onFileSelected = (event) => {
	const file = event.target.files?.[0]
	if (!file) return

	if (file.size > 10 * 1024 * 1024) {
		showToast('Ukuran Terlalu Besar', 'Maksimal ukuran foto adalah 10MB.', 'error')
		return
	}

	selectedFile.value = file
	const reader = new FileReader()
	reader.onload = (e) => {
		previewAvatar.value = e.target.result
		showToast('Foto Dipilih', 'Foto siap disimpan bersama perubahan profil lainnya.', 'success')
	}
	reader.readAsDataURL(file)
}

const selectPreset = (preset) => {
	selectedFile.value = null
	previewAvatar.value = preset.url
	showToast('Avatar Dipilih', `Preset ${preset.name} dipilih.`, 'success')
}

const applyCustomUrl = () => {
	if (!customUrlInput.value.trim()) {
		showToast('URL Kosong', 'Ketikkan URL gambar yang valid.', 'error')
		return
	}
	selectedFile.value = null
	previewAvatar.value = customUrlInput.value.trim()
	customUrlInput.value = ''
	showToast('URL Diterapkan', 'Foto siap disimpan.', 'success')
}

const removeAvatar = () => {
	selectedFile.value = null
	previewAvatar.value = null
	showToast('Foto Dihapus', 'Foto profil direset.', 'success')
}

const resetFormToCurrent = () => {
	form.name = adminUser.value.name || 'Administrator'
	form.nip = adminUser.value.nip || '12345'
	form.password = adminUser.value.visible_password || '12345678'
	previewAvatar.value = adminUser.value.avatar || null
	selectedFile.value = null
	showToast('Form Direset', 'Data dikembalikan ke pengaturan terakhir.', 'info')
}

// SUBMIT ALL CHANGES (Foto, Nama, NIP, Password)
const saveAllChanges = async () => {
	if (!form.name.trim() || !form.nip.trim()) {
		showToast('Validasi Gagal', 'Nama dan NIP wajib diisi.', 'error')
		return
	}

	isSubmitting.value = true

	try {
		let responseData = null

		if (selectedFile.value) {
			const formData = new FormData()
			formData.append('image', selectedFile.value)
			formData.append('name', form.name.trim())
			formData.append('nip', form.nip.trim())
			if (form.password) formData.append('password', form.password)
			if (adminUser.value.id) formData.append('user_id', adminUser.value.id)
			formData.append('nip_origin', originNip.value)

			const res = await fetch(`${apiBase}/profile/update`, {
				method: 'POST',
				headers: {
					...(tokenCookie.value ? { 'Authorization': `Bearer ${tokenCookie.value}` } : {}),
					'Accept': 'application/json'
				},
				body: formData
			})

			if (!res.ok) {
				const err = await res.json().catch(() => ({}))
				throw new Error(err.message || 'Gagal memperbarui profil admin.')
			}

			responseData = await res.json()
		} else {
			const res = await fetch(`${apiBase}/profile/update`, {
				method: 'POST',
				headers: {
					'Content-Type': 'application/json',
					'Accept': 'application/json',
					...(tokenCookie.value ? { 'Authorization': `Bearer ${tokenCookie.value}` } : {})
				},
				body: JSON.stringify({
					user_id: adminUser.value.id,
					nip_origin: originNip.value,
					name: form.name.trim(),
					nip: form.nip.trim(),
					password: form.password,
					avatar: previewAvatar.value || ''
				})
			})

			if (!res.ok) {
				const err = await res.json().catch(() => ({}))
				throw new Error(err.message || 'Gagal memperbarui profil admin.')
			}

			responseData = await res.json()
		}

		// Update Admin state
		const updated = responseData.user || {
			...adminUser.value,
			name: form.name.trim(),
			nip: form.nip.trim(),
			visible_password: form.password,
			avatar: responseData.avatar || previewAvatar.value
		}

		adminUser.value = updated
		originNip.value = updated.nip
		previewAvatar.value = updated.avatar
		selectedFile.value = null

		// Sync with cookie & localStorage
		userCookie.value = JSON.stringify(updated)
		if (typeof window !== 'undefined') {
			localStorage.setItem('auth_user', JSON.stringify(updated))
			window.dispatchEvent(new Event('storage'))
		}

		showToast('Semua Berhasil Diperbarui!', 'Nama, NIP, Password, dan Foto Profil Admin sukses disimpan.', 'success')
	} catch (err) {
		console.error('Error updating admin profile:', err)
		showToast('Gagal Menyimpan', err.message || 'Terjadi kendala saat menyimpan perubahan.', 'error')
	} finally {
		isSubmitting.value = false
	}
}

const handleLogout = () => {
	userCookie.value = null
	tokenCookie.value = null
	if (typeof window !== 'undefined') {
		localStorage.removeItem('auth_user')
		localStorage.removeItem('auth_token')
	}
	router.push('/login')
}

// Mount and sync
onMounted(async () => {
	if (typeof window !== 'undefined') {
		const savedTheme = localStorage.getItem('admin_theme')
		if (savedTheme) isDark.value = savedTheme === 'dark'

		const raw = localStorage.getItem('auth_user')
		if (raw) {
			try {
				const u = JSON.parse(raw)
				if (u?.name) {
					adminUser.value = { ...adminUser.value, ...u }
					originNip.value = u.nip || '12345'
					form.name = u.name
					form.nip = u.nip
					form.password = u.visible_password || '12345678'
					previewAvatar.value = u.avatar || null
				}
			} catch (e) {}
		}
	}

	if (!adminUser.value.id && userCookie.value) {
		try {
			const u = typeof userCookie.value === 'string' ? JSON.parse(userCookie.value) : userCookie.value
			if (u?.name) {
				adminUser.value = { ...adminUser.value, ...u }
				originNip.value = u.nip || '12345'
				form.name = u.name
				form.nip = u.nip
				form.password = u.visible_password || '12345678'
				previewAvatar.value = u.avatar || null
			}
		} catch (e) {}
	}

	// Fetch fresh data from API
	try {
		const token = tokenCookie.value
		if (token) {
			const res = await fetch(`${apiBase}/me`, {
				headers: {
					'Authorization': `Bearer ${token}`,
					'Accept': 'application/json'
				}
			})
			if (res.ok) {
				const fresh = await res.json()
				if (fresh?.name) {
					adminUser.value = { ...adminUser.value, ...fresh }
					originNip.value = fresh.nip || '12345'
					form.name = fresh.name
					form.nip = fresh.nip
					form.password = fresh.visible_password || '12345678'
					previewAvatar.value = fresh.avatar || null
				}
			}
		}
	} catch (e) {
		console.warn('Could not refresh admin from /api/me', e)
	}
})
</script>

<style scoped>
/* Admin Profile Styling matching admin dashboard */
.dashboard {
	display: flex;
	min-height: 100vh;
	font-family: inherit;
	transition: background-color 0.2s ease, color 0.2s ease;
}

.theme-dark {
	background-color: #0b1120;
	color: #f1f5f9;
}

.theme-light {
	background-color: #f8fafc;
	color: #0f172a;
}

/* Sidebar */
.sidebar {
	width: 260px;
	background-color: #0f172a;
	border-right: 1px solid #1e293b;
	display: flex;
	flex-direction: column;
	padding: 1.25rem 1rem;
	flex-shrink: 0;
	z-index: 40;
}

.brand {
	display: flex;
	align-items: center;
	gap: 0.75rem;
	padding-bottom: 1.5rem;
	border-bottom: 1px solid #1e293b;
	margin-bottom: 1.25rem;
}

.brand-logo-diamond {
	width: 36px;
	height: 36px;
	border-radius: 10px;
	background: #10b98120;
	border: 1px solid #10b98140;
	display: flex;
	align-items: center;
	justify-content: center;
	color: #10b981;
}

.brand-logo-diamond .svg-icon-diamond {
	width: 22px;
	height: 22px;
}

.brand-title {
	font-size: 1.1rem;
	font-weight: 900;
	letter-spacing: 0.05em;
	display: block;
	line-height: 1.2;
}

.brand small {
	font-size: 0.65rem;
	color: #64748b;
	display: block;
}

.sidebar nav {
	display: flex;
	flex-direction: column;
	gap: 0.35rem;
	flex: 1;
}

.sidebar nav a {
	display: flex;
	align-items: center;
	gap: 0.75rem;
	padding: 0.7rem 0.85rem;
	border-radius: 0.75rem;
	color: #94a3b8;
	font-size: 0.85rem;
	font-weight: 600;
	text-decoration: none;
	transition: all 0.2s;
}

.sidebar nav a:hover,
.sidebar nav a.active {
	background-color: #1e293b;
	color: #10b981;
}

.nav-fullscreen-entry {
	margin-top: 0.5rem;
	border: 1px dashed #334155 !important;
}

.nav-svg-box {
	width: 18px;
	height: 18px;
	display: flex;
	align-items: center;
	justify-content: center;
}

.account {
	display: flex;
	align-items: center;
	gap: 0.75rem;
	padding: 0.75rem;
	border-radius: 0.85rem;
	background-color: #1e293b;
	border: 1px solid #10b98140;
	margin-top: auto;
	margin-bottom: 0.5rem;
}

.account.active {
	box-shadow: 0 0 15px #10b98125;
}

.user-avatar {
	width: 34px;
	height: 34px;
	border-radius: 8px;
	background: #10b981;
	color: #0f172a;
	font-weight: 900;
	font-size: 0.85rem;
	display: flex;
	align-items: center;
	justify-content: center;
	flex-shrink: 0;
}

.account div {
	flex: 1;
	min-width: 0;
}

.account strong,
.account b {
	font-size: 0.8rem;
	color: #f8fafc;
	display: block;
	white-space: nowrap;
	overflow: hidden;
	text-overflow: ellipsis;
}

.account small {
	font-size: 0.65rem;
	color: #10b981;
	display: block;
}

.account button {
	background: none;
	border: none;
	color: #64748b;
	cursor: pointer;
}

.logout {
	display: flex;
	align-items: center;
	justify-content: center;
	gap: 0.5rem;
	padding: 0.6rem;
	border-radius: 0.75rem;
	background-color: #ef444415;
	border: 1px solid #ef444430;
	color: #f87171;
	font-size: 0.8rem;
	font-weight: 700;
	cursor: pointer;
	transition: all 0.2s;
}

.logout:hover {
	background-color: #ef444430;
}

/* Main Area */
.main {
	flex: 1;
	display: flex;
	flex-direction: column;
	min-width: 0;
	overflow-y: auto;
}

.topbar {
	height: 64px;
	padding: 0 1.5rem;
	display: flex;
	align-items: center;
	justify-content: space-between;
	border-bottom: 1px solid #1e293b;
	background-color: rgba(15, 23, 42, 0.7);
	backdrop-filter: blur(8px);
	position: sticky;
	top: 0;
	z-index: 30;
}

.mobile-menu {
	display: none;
	background: none;
	border: none;
	color: inherit;
	font-size: 1.5rem;
	cursor: pointer;
}

.top-user {
	display: flex;
	align-items: center;
	gap: 1rem;
}

.theme-toggle-btn {
	display: flex;
	align-items: center;
	gap: 0.4rem;
	padding: 0.4rem 0.75rem;
	border-radius: 9999px;
	background: #1e293b;
	border: 1px solid #334155;
	color: #cbd5e1;
	font-size: 0.75rem;
	font-weight: 700;
	cursor: pointer;
}

.svg-icon-theme {
	width: 14px;
	height: 14px;
}

.user-chip-top {
	display: flex;
	align-items: center;
	gap: 0.5rem;
	font-size: 0.8rem;
	font-weight: 700;
}

.user-dot {
	width: 28px;
	height: 28px;
	border-radius: 7px;
	background: #10b981;
	color: #0f172a;
	display: flex;
	align-items: center;
	justify-content: center;
	font-weight: 800;
	font-size: 0.75rem;
}

/* Page Content */
.page-content {
	padding: 2rem;
	max-width: 1100px;
	margin: 0 auto;
	width: 100%;
}

.profile-hero-banner {
	background: linear-gradient(135deg, rgba(6, 78, 59, 0.4) 0%, rgba(15, 23, 42, 0.9) 100%);
	border: 1px solid rgba(16, 185, 129, 0.3);
	border-radius: 1.5rem;
	padding: 1.75rem 2rem;
	margin-bottom: 2rem;
	box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3);
}

.badge-role-admin {
	display: inline-flex;
	align-items: center;
	gap: 0.5rem;
	padding: 0.25rem 0.75rem;
	border-radius: 9999px;
	background: rgba(16, 185, 129, 0.15);
	border: 1px solid rgba(16, 185, 129, 0.35);
	color: #34d399;
	font-size: 0.7rem;
	font-weight: 800;
	margin-bottom: 0.75rem;
}

.pulse-dot-green {
	width: 7px;
	height: 7px;
	border-radius: 9999px;
	background-color: #10b981;
	animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

.profile-hero-banner h1 {
	font-size: 1.5rem;
	font-weight: 900;
	color: #ffffff;
	margin: 0 0 0.5rem 0;
	letter-spacing: -0.02em;
}

.profile-hero-banner p {
	font-size: 0.85rem;
	color: #cbd5e1;
	line-height: 1.6;
	margin: 0;
	max-width: 800px;
}

/* Profile Grid */
.profile-grid {
	display: grid;
	grid-template-columns: 340px 1fr;
	gap: 2rem;
}

.profile-card {
	background: #0f172a95;
	border: 1px solid #1e293b;
	border-radius: 1.5rem;
	padding: 1.75rem;
	box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
}

.card-header-row {
	display: flex;
	align-items: flex-start;
	justify-content: space-between;
	border-bottom: 1px solid #1e293b;
	padding-bottom: 1rem;
	margin-bottom: 1.25rem;
}

.card-title {
	font-size: 1.05rem;
	font-weight: 800;
	color: #ffffff;
	margin: 0;
}

.card-subtitle {
	font-size: 0.75rem;
	color: #94a3b8;
	margin: 0.25rem 0 0 0;
}

.status-badge-blue {
	background: rgba(56, 189, 248, 0.15);
	color: #38bdf8;
	border: 1px solid rgba(56, 189, 248, 0.3);
	font-size: 0.7rem;
	font-weight: 700;
	padding: 0.2rem 0.6rem;
	border-radius: 9999px;
}

.status-badge-green {
	background: rgba(16, 185, 129, 0.15);
	color: #34d399;
	border: 1px solid rgba(16, 185, 129, 0.3);
	font-size: 0.7rem;
	font-weight: 700;
	padding: 0.2rem 0.6rem;
	border-radius: 9999px;
}

/* Avatar Preview Box */
.avatar-preview-box {
	display: flex;
	flex-direction: column;
	align-items: center;
}

.avatar-circle-lg {
	width: 140px;
	height: 140px;
	border-radius: 1.5rem;
	background: #020617;
	border: 3px solid #334155;
	position: relative;
	overflow: hidden;
	box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4);
}

.avatar-img-fit {
	width: 100%;
	height: 100%;
	object-fit: cover;
}

.avatar-initials-lg {
	width: 100%;
	height: 100%;
	background: linear-gradient(135deg, #10b981 0%, #059669 100%);
	color: #ffffff;
	font-size: 3.5rem;
	font-weight: 900;
	display: flex;
	align-items: center;
	justify-content: center;
}

.btn-camera-overlay {
	position: absolute;
	bottom: 6px;
	right: 6px;
	width: 36px;
	height: 36px;
	border-radius: 10px;
	background: #10b981;
	border: 2px solid #0f172a;
	color: #0f172a;
	font-size: 1rem;
	display: flex;
	align-items: center;
	justify-content: center;
	cursor: pointer;
	transition: transform 0.15s;
}

.btn-camera-overlay:hover {
	transform: scale(1.08);
}

/* Controls */
.btn-upload-file {
	width: 100%;
	padding: 0.7rem;
	border-radius: 0.75rem;
	background: #1e293b;
	border: 1px solid #334155;
	color: #e2e8f0;
	font-size: 0.8rem;
	font-weight: 700;
	cursor: pointer;
	transition: all 0.2s;
}

.btn-upload-file:hover {
	background: #334155;
}

.preset-wrap {
	padding-top: 0.5rem;
}

.preset-label {
	display: block;
	font-size: 0.7rem;
	font-weight: 700;
	color: #94a3b8;
	text-transform: uppercase;
	margin-bottom: 0.5rem;
	letter-spacing: 0.05em;
}

.preset-grid {
	display: grid;
	grid-template-columns: repeat(4, 1fr);
	gap: 0.5rem;
}

.preset-item {
	padding: 0.25rem;
	border-radius: 0.75rem;
	background: #020617;
	border: 1px solid #1e293b;
	cursor: pointer;
	transition: all 0.2s;
}

.preset-item:hover,
.preset-item.active {
	border-color: #10b981;
	box-shadow: 0 0 10px rgba(16, 185, 129, 0.3);
}

.preset-img {
	width: 100%;
	height: 48px;
	object-fit: cover;
	border-radius: 0.5rem;
}

.url-flex {
	display: flex;
	gap: 0.4rem;
}

.input-custom-url {
	flex: 1;
	padding: 0.5rem 0.75rem;
	border-radius: 0.6rem;
	background: #020617;
	border: 1px solid #1e293b;
	color: #ffffff;
	font-size: 0.75rem;
}

.input-custom-url:focus {
	outline: none;
	border-color: #10b981;
}

.btn-url-apply {
	padding: 0.5rem 0.85rem;
	border-radius: 0.6rem;
	background: #1e293b;
	border: 1px solid #334155;
	color: #ffffff;
	font-size: 0.75rem;
	font-weight: 700;
	cursor: pointer;
}

.btn-remove-avatar {
	width: 100%;
	padding: 0.6rem;
	border-radius: 0.75rem;
	background: rgba(239, 68, 68, 0.1);
	border: 1px solid rgba(239, 68, 68, 0.25);
	color: #f87171;
	font-size: 0.75rem;
	font-weight: 700;
	cursor: pointer;
}

/* Form Styles */
.form-group {
	display: flex;
	flex-direction: column;
	gap: 0.35rem;
}

.form-label {
	display: flex;
	align-items: center;
	justify-content: space-between;
	font-size: 0.8rem;
	font-weight: 700;
	color: #cbd5e1;
}

.badge-edit-privilege {
	font-size: 0.65rem;
	font-weight: 800;
	padding: 0.15rem 0.5rem;
	border-radius: 4px;
	background: rgba(16, 185, 129, 0.15);
	color: #34d399;
	border: 1px solid rgba(16, 185, 129, 0.3);
}

.input-with-icon {
	position: relative;
	display: flex;
	align-items: center;
}

.input-icon {
	position: absolute;
	left: 0.85rem;
	font-size: 0.9rem;
	pointer-events: none;
	opacity: 0.7;
}

.form-control {
	width: 100%;
	padding: 0.8rem 1rem 0.8rem 2.6rem;
	border-radius: 0.85rem;
	background: #020617;
	border: 1px solid #1e293b;
	color: #ffffff;
	font-size: 0.85rem;
	transition: all 0.2s;
}

.form-control:focus {
	outline: none;
	border-color: #10b981;
	box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.15);
}

.btn-toggle-eye {
	position: absolute;
	right: 0.85rem;
	background: none;
	border: none;
	font-size: 1rem;
	cursor: pointer;
	opacity: 0.8;
}

.field-hint {
	font-size: 0.7rem;
	color: #64748b;
}

.btn-submit-all {
	flex: 1;
	padding: 0.85rem 1.5rem;
	border-radius: 0.85rem;
	background: linear-gradient(135deg, #10b981 0%, #059669 100%);
	color: #0f172a;
	font-size: 0.85rem;
	font-weight: 800;
	border: none;
	cursor: pointer;
	display: flex;
	align-items: center;
	justify-content: center;
	gap: 0.5rem;
	box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
	transition: all 0.2s;
}

.btn-submit-all:hover:not(:disabled) {
	transform: translateY(-1px);
	box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
}

.btn-submit-all:disabled {
	opacity: 0.6;
	cursor: not-allowed;
}

.btn-reset {
	padding: 0.85rem 1.25rem;
	border-radius: 0.85rem;
	background: #1e293b;
	border: 1px solid #334155;
	color: #94a3b8;
	font-size: 0.85rem;
	font-weight: 700;
	cursor: pointer;
}

@media (max-width: 900px) {
	.profile-grid {
		grid-template-columns: 1fr;
	}
	.sidebar {
		position: fixed;
		left: -260px;
		top: 0;
		bottom: 0;
		transition: left 0.3s ease;
	}
	.sidebar.open {
		left: 0;
	}
	.mobile-menu {
		display: block;
	}
	.page-content {
		padding: 1rem;
	}
}
</style>
