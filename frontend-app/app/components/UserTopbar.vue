<template>
  <div>
    <!-- TOPBAR WRAPPER (MEMBERIKAN JARAK DARI ATAS AGAR TIDAK TERLALU KE ATAS) -->
    <div class="user-topbar-wrapper sticky top-0 z-40 pt-3 sm:pt-5 px-3.5 sm:px-6 pb-1.5 transition-all duration-200 pointer-events-none">
      <header
        class="backdrop-blur-md border rounded-2xl transition-all duration-200 shadow-lg max-w-7xl mx-auto pointer-events-auto"
        :class="isDark ? 'bg-slate-900/90 border-slate-800 text-slate-100 shadow-black/30' : 'bg-white/95 border-slate-200 text-slate-800 shadow-slate-300/40'"
      >
        <div class="px-4 sm:px-6 h-16 flex items-center justify-between gap-2">
          
          <!-- Left: Hamburger & Brand -->
          <div class="flex items-center gap-3">
            <!-- Mobile Hamburger Toggle -->
            <button
              type="button"
              @click="mobileMenuOpen = !mobileMenuOpen"
              class="md:hidden p-2.5 rounded-xl transition cursor-pointer"
              :class="isDark ? 'bg-slate-800 hover:bg-slate-700 text-slate-300 border border-slate-700/60' : 'bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200'"
              aria-label="Toggle Menu"
            >
              <svg viewBox="0 0 24 24" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2">
                <path v-if="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                <path v-else stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>

            <!-- Brand Logo -->
            <NuxtLink to="/User/dashboard" class="flex items-center gap-2.5 group">
              <div class="w-10 h-10 rounded-xl bg-white/90 dark:bg-slate-900/90 p-1 flex items-center justify-center shadow-md shadow-emerald-500/10 border border-emerald-500/25 group-hover:scale-105 transition-transform overflow-hidden">
                <img src="/images/logo-simpel-icon.png" alt="Logo SIMPEL" class="w-full h-full object-contain" />
              </div>
              <div>
                <div class="font-black text-sm md:text-base leading-tight tracking-tight flex items-center gap-1.5" :class="isDark ? 'text-white' : 'text-slate-900'">
                  <span class="font-brand font-black text-emerald-500 text-base md:text-lg tracking-wider">SIMPEL</span>
                  <span class="hidden sm:inline-block px-1.5 py-0.2 rounded text-[9px] font-bold bg-emerald-500/15 text-emerald-600 border border-emerald-500/30">USER</span>
                </div>
                <p class="text-[10px] hidden sm:block" :class="isDark ? 'text-slate-400' : 'text-slate-500'">Sistem Informasi Manajemen Pelayanan</p>
              </div>
            </NuxtLink>
          </div>

          <!-- Center: Quick Nav Menu (Desktop & Tablet) -->
          <nav
            class="hidden md:flex items-center gap-1 p-1.5 rounded-2xl border shadow-inner transition-colors duration-200"
            :class="isDark ? 'bg-slate-950/70 border-slate-800/80' : 'bg-slate-100/90 border-slate-200'"
          >
            <NuxtLink
              v-for="item in navMenuItems"
              :key="item.path"
              :to="item.path"
              class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold transition-all"
              :class="isActiveRoute(item.path)
                ? 'bg-emerald-500 text-white shadow-md shadow-emerald-500/25'
                : (isDark ? 'text-slate-400 hover:text-slate-200 hover:bg-slate-900' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-200/70')"
            >
              <span class="text-sm">{{ item.icon }}</span>
              <span>{{ item.title }}</span>
            </NuxtLink>
          </nav>

          <!-- Right: Profile Chip & Mode Gelap Terang & Logout -->
          <div class="flex items-center gap-2">
            <!-- Profile Chip -->
            <NuxtLink
              to="/User/profile"
              title="Buka Profil Saya"
              class="flex items-center gap-2.5 p-1 sm:pr-3 rounded-xl border transition group"
              :class="isDark ? 'bg-slate-800/80 hover:bg-slate-800 border-slate-700/60' : 'bg-slate-100 hover:bg-slate-200/80 border-slate-200 shadow-sm'"
            >
              <div class="w-8 h-8 rounded-lg bg-emerald-600/30 text-emerald-500 border border-emerald-500/40 flex items-center justify-center font-black text-xs group-hover:scale-105 transition-transform overflow-hidden shrink-0">
                <img v-if="currentUser?.avatar" :src="currentUser.avatar" :alt="currentUser.name" class="w-full h-full object-cover" @error="handleAvatarError" />
                <span v-else>{{ userAvatarLetter }}</span>
              </div>
              <div class="text-left hidden sm:block">
                <strong class="block text-xs leading-tight truncate max-w-[120px]" :class="isDark ? 'text-white' : 'text-slate-900'">{{ currentUser?.name || 'Pegawai' }}</strong>
                <small class="block text-[10px] truncate max-w-[120px]" :class="isDark ? 'text-slate-400' : 'text-slate-500'">Divisi {{ currentUser?.division?.name || 'Operasional' }}</small>
              </div>
            </NuxtLink>

            <!-- SIMBOL MODE GELAP / TERANG (DI ATAS DI SAMPING NAMA USER) -->
            <button
              @click="toggleTheme"
              type="button"
              :title="isDark ? 'Beralih ke Mode Terang' : 'Beralih ke Mode Gelap'"
              aria-label="Toggle Mode Gelap Terang"
              class="w-8 h-8 rounded-xl flex items-center justify-center border transition active:scale-95 shrink-0"
              :class="isDark ? 'bg-slate-800/80 hover:bg-slate-700 text-amber-400 border-slate-700/60' : 'bg-slate-100 hover:bg-slate-200 text-slate-700 border-slate-200 shadow-sm'"
            >
              <!-- Sun symbol when dark (click to become light) -->
              <svg v-if="isDark" viewBox="0 0 24 24" class="w-4 h-4" fill="currentColor">
                <path d="M12 7c-2.76 0-5 2.24-5 5s2.24 5 5 5 5-2.24 5-5-2.24-5-5-5zM2 13h2c.55 0 1-.45 1-1s-.45-1-1-1H2c-.55 0-1 .45-1 1s.45 1 1 1zm18 0h2c.55 0 1-.45 1-1s-.45-1-1-1h-2c-.55 0-1 .45-1 1s.45 1 1 1zM11 2v2c0 .55.45 1 1 1s1-.45 1-1V2c0-.55-.45-1-1-1s-1 .45-1 1zm0 18v2c0 .55.45 1 1 1s1-.45 1-1v-2c0-.55-.45-1-1-1s-1 .45-1 1zM5.99 4.58c-.39-.39-1.03-.39-1.41 0s-.39 1.03 0 1.41l1.06 1.06c.39.39 1.03.39 1.41 0s.39-1.03 0-1.41L5.99 4.58zm12.37 12.37c-.39-.39-1.03-.39-1.41 0s-.39 1.03 0 1.41l1.06 1.06c.39.39 1.03.39 1.41 0s.39-1.03 0-1.41l-1.06-1.06zm1.06-10.96c.39-.39.39-1.03 0-1.41s-1.03-.39-1.41 0l-1.06 1.06c-.39.39-.39 1.03 0 1.41s1.03.39 1.41 0l1.06-1.06zM7.05 18.36c.39-.39.39-1.03 0-1.41s-1.03-.39-1.41 0l-1.06 1.06c-.39.39-.39 1.03 0 1.41s1.03.39 1.41 0l1.06-1.06z"/>
              </svg>
              <!-- Moon symbol when light (click to become dark) -->
              <svg v-else viewBox="0 0 24 24" class="w-4 h-4" fill="currentColor">
                <path d="M12.3 2a10 10 0 0 0-1.9 19.8 9.5 9.5 0 0 0 8.8-5.3 1 1 0 0 0-1.2-1.4 7.5 7.5 0 0 1-6.7-10.8 1 1 0 0 0-.8-1.5z"/>
              </svg>
            </button>

            <!-- Logout Button -->
            <button
              @click="handleLogout"
              type="button"
              title="Keluar dari Akun"
              class="px-2.5 sm:px-3 py-1.5 rounded-xl bg-red-500/10 hover:bg-red-500/20 text-red-500 border border-red-500/20 text-xs font-bold transition flex items-center gap-1.5 active:scale-95"
            >
              <svg viewBox="0 0 24 24" class="w-4 h-4" fill="currentColor">
                <path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/>
              </svg>
              <span class="hidden sm:inline">Keluar</span>
            </button>
          </div>
        </div>
      </header>
    </div>

    <!-- MOBILE SLIDE-OUT DRAWER / POPUP MENU -->
    <div v-if="mobileMenuOpen" class="fixed inset-0 z-50 md:hidden flex flex-col justify-end bg-black/70 backdrop-blur-sm" @click.self="mobileMenuOpen = false">
      <div
        class="border-t rounded-t-3xl p-5 shadow-2xl space-y-4 animate-in slide-in-from-bottom duration-200"
        :class="isDark ? 'bg-slate-900 border-slate-700' : 'bg-white border-slate-200'"
      >
        <div class="flex items-center justify-between pb-3 border-b" :class="isDark ? 'border-slate-800' : 'border-slate-200'">
          <NuxtLink to="/User/profile" @click="mobileMenuOpen = false" class="flex items-center gap-2.5 hover:opacity-90 transition">
            <div class="w-9 h-9 rounded-xl bg-emerald-600/30 text-emerald-500 flex items-center justify-center font-bold text-xs overflow-hidden shrink-0 border border-emerald-500/40">
              <img v-if="currentUser?.avatar" :src="currentUser.avatar" :alt="currentUser.name" class="w-full h-full object-cover" @error="handleAvatarError" />
              <span v-else>{{ userAvatarLetter }}</span>
            </div>
            <div>
              <strong class="block text-xs flex items-center gap-1" :class="isDark ? 'text-white' : 'text-slate-900'">
                <span>{{ currentUser?.name || 'Pegawai' }}</span>
                <span class="text-[10px] text-emerald-500">⚙️</span>
              </strong>
              <small class="text-[10px]" :class="isDark ? 'text-slate-400' : 'text-slate-500'">NPP: {{ currentUser?.nip || '-' }} • {{ currentUser?.division?.name || 'Divisi' }}</small>
            </div>
          </NuxtLink>

          <div class="flex items-center gap-2">
            <!-- Mobile Theme Toggle Symbol -->
            <button
              @click="toggleTheme"
              type="button"
              :title="isDark ? 'Beralih ke Mode Terang' : 'Beralih ke Mode Gelap'"
              class="w-8 h-8 rounded-xl flex items-center justify-center border transition"
              :class="isDark ? 'bg-slate-800 text-amber-400 border-slate-700' : 'bg-slate-100 text-slate-700 border-slate-200 shadow-sm'"
            >
              <svg v-if="isDark" viewBox="0 0 24 24" class="w-4 h-4" fill="currentColor">
                <path d="M12 7c-2.76 0-5 2.24-5 5s2.24 5 5 5 5-2.24 5-5-2.24-5-5-5zM2 13h2c.55 0 1-.45 1-1s-.45-1-1-1H2c-.55 0-1 .45-1 1s.45 1 1 1zm18 0h2c.55 0 1-.45 1-1s-.45-1-1-1h-2c-.55 0-1 .45-1 1s.45 1 1 1zM11 2v2c0 .55.45 1 1 1s1-.45 1-1V2c0-.55-.45-1-1-1s-1 .45-1 1zm0 18v2c0 .55.45 1 1 1s1-.45 1-1v-2c0-.55-.45-1-1-1s-1 .45-1 1zM5.99 4.58c-.39-.39-1.03-.39-1.41 0s-.39 1.03 0 1.41l1.06 1.06c.39.39 1.03.39 1.41 0s.39-1.03 0-1.41L5.99 4.58zm12.37 12.37c-.39-.39-1.03-.39-1.41 0s-.39 1.03 0 1.41l1.06 1.06c.39.39 1.03.39 1.41 0s.39-1.03 0-1.41l-1.06-1.06zm1.06-10.96c.39-.39.39-1.03 0-1.41s-1.03-.39-1.41 0l-1.06 1.06c-.39.39-.39 1.03 0 1.41s1.03.39 1.41 0l1.06-1.06zM7.05 18.36c.39-.39.39-1.03 0-1.41s-1.03-.39-1.41 0l-1.06 1.06c-.39.39-.39 1.03 0 1.41s1.03.39 1.41 0l1.06-1.06z"/>
              </svg>
              <svg v-else viewBox="0 0 24 24" class="w-4 h-4" fill="currentColor">
                <path d="M12.3 2a10 10 0 0 0-1.9 19.8 9.5 9.5 0 0 0 8.8-5.3 1 1 0 0 0-1.2-1.4 7.5 7.5 0 0 1-6.7-10.8 1 1 0 0 0-.8-1.5z"/>
              </svg>
            </button>
            <button @click="mobileMenuOpen = false" class="w-8 h-8 rounded-lg flex items-center justify-center text-xs" :class="isDark ? 'bg-slate-800 text-slate-400' : 'bg-slate-100 text-slate-600'">✕</button>
          </div>
        </div>

        <!-- Live Clock in Mobile Menu -->
        <div class="my-2 p-2 rounded-xl flex items-center justify-center" :class="isDark ? 'bg-slate-950/60' : 'bg-slate-50'">
          <RealtimeClock :theme="isDark ? 'dark' : 'light'" />
        </div>

        <nav class="space-y-1 text-xs">
          <NuxtLink
            v-for="item in navMenuItems"
            :key="item.path"
            :to="item.path"
            @click="mobileMenuOpen = false"
            class="flex items-center gap-3 p-3 rounded-xl font-bold transition"
            :class="isActiveRoute(item.path)
              ? 'bg-emerald-500 text-white shadow-md shadow-emerald-500/25'
              : (isDark ? 'text-slate-300 hover:bg-slate-800' : 'text-slate-700 hover:bg-slate-100')"
          >
            <span class="text-base">{{ item.icon }}</span>
            <span class="flex-1">{{ item.title }}</span>
            <span class="text-[10px] opacity-70">→</span>
          </NuxtLink>
        </nav>

        <div class="pt-2 border-t" :class="isDark ? 'border-slate-800' : 'border-slate-200'">
          <button
            @click="handleLogout"
            type="button"
            class="w-full py-2.5 rounded-xl bg-red-500/10 text-red-500 font-bold text-xs border border-red-500/20 flex items-center justify-center gap-2"
          >
            <svg viewBox="0 0 24 24" class="w-4 h-4" fill="currentColor">
              <path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/>
            </svg>
            <span>Keluar dari Akun</span>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'

const route = useRoute()
const router = useRouter()
const mobileMenuOpen = ref(false)

// Global Shared Theme State
const isDark = useState('user_theme_dark', () => true)

const userCookie = useCookie('auth_user')
const tokenCookie = useCookie('auth_token')

const currentUser = ref({
  name: 'Pegawai',
  nip: '',
  avatar: null,
  division: null
})

// Menu Utama Terpadu untuk Semua Halaman User
const navMenuItems = [
  { title: 'Beranda', path: '/User/dashboard', icon: '🏠' },
  { title: 'Ambil ATK', path: '/User/ATK', icon: '✏️' },
  { title: 'Ruang Rapat', path: '/User/ruangan', icon: '🏢' },
  { title: 'Mobil Dinas', path: '/User/kendaraan', icon: '🚗' },
  { title: 'Riwayat Saya', path: '/User/riwayat', icon: '🕒' },
  { title: 'Profil Saya', path: '/User/profile', icon: '👤' },
]

const isActiveRoute = (path) => {
  if (!route?.path) return false
  const cur = route.path.toLowerCase()
  const target = path.toLowerCase()
  if (target === '/user/dashboard') {
    return cur === '/user/dashboard' || cur === '/user'
  }
  return cur.startsWith(target)
}

const userAvatarLetter = computed(() => {
  return (currentUser.value?.name || 'P').charAt(0).toUpperCase()
})

const handleAvatarError = () => {
  if (currentUser.value) {
    currentUser.value.avatar = null
  }
}

// Toggle Theme Dark / Light
const toggleTheme = () => {
  isDark.value = !isDark.value
  if (typeof window !== 'undefined') {
    localStorage.setItem('user_theme', isDark.value ? 'dark' : 'light')
  }
}

const handleLogout = () => {
  userCookie.value = null
  tokenCookie.value = null
  if (typeof window !== 'undefined') {
    localStorage.removeItem('auth_user')
    localStorage.removeItem('auth_token')
    localStorage.removeItem('user_vehicle_history')
    localStorage.removeItem('user_atk_history')
    localStorage.removeItem('user_room_history')
    // Bersihkan semua key history per user
    Object.keys(localStorage).forEach(k => {
      if (k.startsWith('user_vehicle_history_') || k.startsWith('user_atk_history_') || k.startsWith('user_room_history_')) {
        localStorage.removeItem(k)
      }
    })
  }
  router.push('/login')
}

const syncUserData = () => {
  if (typeof window !== 'undefined') {
    const raw = localStorage.getItem('auth_user')
    if (raw) {
      try {
        const u = JSON.parse(raw)
        if (u?.name) currentUser.value = { ...currentUser.value, ...u }
      } catch (e) {}
    }
  }
  if (!currentUser.value?.nip && userCookie.value) {
    try {
      const u = typeof userCookie.value === 'string' ? JSON.parse(userCookie.value) : userCookie.value
      if (u?.name) currentUser.value = { ...currentUser.value, ...u }
    } catch (e) {}
  }
}

onMounted(() => {
  syncUserData()
  if (typeof window !== 'undefined') {
    window.addEventListener('storage', syncUserData)
    const savedTheme = localStorage.getItem('user_theme')
    if (savedTheme) {
      isDark.value = savedTheme === 'dark'
    }
  }
})
</script>
