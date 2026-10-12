<template>
  <div>
    <!-- TOPBAR WRAPPER (DIBERI JARAK NYAMAN DARI ATAS DAN POJOK) -->
    <div class="driver-topbar-wrapper sticky top-0 z-40 pt-3 sm:pt-5 px-3.5 sm:px-6 pb-1.5 transition-all duration-200 pointer-events-none">
      <header
        class="bg-slate-900/95 backdrop-blur-md border border-slate-800 rounded-2xl shadow-xl max-w-6xl mx-auto pointer-events-auto transition-all duration-200"
      >
        <div class="px-3.5 sm:px-6 h-14 sm:h-16 flex items-center justify-between gap-2.5 sm:gap-4 overflow-hidden">
          
          <!-- SISI KIRI: HAMBURGER MOBILE & BRAND DRIVER -->
          <div class="flex items-center gap-2 sm:gap-3 shrink-0">
            <!-- Mobile Hamburger Toggle Button -->
            <button
              type="button"
              @click="mobileMenuOpen = !mobileMenuOpen"
              class="md:hidden w-9 h-9 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 border border-slate-700 flex items-center justify-center transition active:scale-95 shrink-0"
              aria-label="Toggle Menu Pengemudi"
            >
              <svg viewBox="0 0 24 24" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5">
                <path v-if="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                <path v-else stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>

            <!-- Brand Logo & Label Driver -->
            <NuxtLink to="/sopir/dashboard" class="flex items-center gap-2 sm:gap-2.5 group shrink-0">
              <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-white/10 p-1 flex items-center justify-center shadow border border-amber-500/30 group-hover:scale-105 transition-transform overflow-hidden shrink-0">
                <img src="/images/logo-simpel-icon.png" alt="Logo SIMPEL" class="w-full h-full object-contain" />
              </div>
              <div class="leading-tight">
                <div class="flex items-center gap-1.5">
                  <span class="font-brand font-black text-amber-400 text-sm sm:text-base tracking-wider">SIMPEL</span>
                  <span class="px-2 py-0.5 rounded text-[10px] font-black bg-amber-500/20 text-amber-300 border border-amber-500/30">
                    DRIVER
                  </span>
                </div>
                <p class="text-[10px] text-slate-400 hidden sm:block truncate max-w-[160px]">
                  {{ currentUser?.name || 'Pengemudi Operasional' }}
                </p>
              </div>
            </NuxtLink>
          </div>

          <!-- TENGAH: MENU NAVIGASI DESKTOP / TABLET (Tampil di md ke atas) -->
          <nav class="hidden md:flex items-center gap-1 bg-slate-950/80 p-1 rounded-xl border border-slate-800 text-xs shadow-inner shrink-0">
            <NuxtLink
              v-for="item in navMenuItems"
              :key="item.path"
              :to="item.path"
              class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg font-bold transition-all"
              :class="isActiveRoute(item.path)
                ? 'bg-amber-500 text-slate-950 shadow-md shadow-amber-500/25'
                : 'text-slate-400 hover:text-slate-200 hover:bg-slate-900'"
            >
              <span class="text-xs">{{ item.icon }}</span>
              <span>{{ item.title }}</span>
              <span
                v-if="item.badgeCount && item.badgeCount > 0"
                class="px-1.5 py-0.2 rounded-full text-[9px] font-mono font-black"
                :class="isActiveRoute(item.path) ? 'bg-slate-950 text-amber-400' : 'bg-amber-500/20 text-amber-300 border border-amber-500/30'"
              >
                {{ item.badgeCount }}
              </span>
            </NuxtLink>
          </nav>

          <!-- SISI KANAN: TOGGLE SUARA & LOGOUT -->
          <div class="flex items-center gap-2 sm:gap-2.5 shrink-0">
            <!-- Toggle Suara Notifikasi Pesanan -->
            <button
              type="button"
              @click="toggleSound"
              class="w-9 h-9 sm:w-auto sm:px-2.5 sm:py-1.5 rounded-xl border transition flex items-center justify-center gap-1.5 text-xs font-semibold shrink-0"
              :class="soundEnabled ? 'text-emerald-400 bg-emerald-500/10 border-emerald-500/30 hover:bg-emerald-500/20' : 'text-slate-400 bg-slate-800 border-slate-700 hover:bg-slate-700'"
              :title="soundEnabled ? 'Suara Notifikasi Aktif (Klik untuk Mute)' : 'Suara Notifikasi MUTE (Klik untuk Aktifkan)'"
            >
              <span>{{ soundEnabled ? '🔔' : '🔕' }}</span>
              <span class="hidden lg:inline text-[11px]">{{ soundEnabled ? 'Suara ON' : 'Mute' }}</span>
            </button>

            <!-- Tombol Keluar / Logout -->
            <button
              @click="handleLogout"
              type="button"
              title="Keluar dari Akun"
              class="w-9 h-9 sm:w-auto sm:px-3 sm:py-1.5 rounded-xl bg-red-500/10 hover:bg-red-500/20 text-red-400 border border-red-500/20 text-xs font-bold transition flex items-center justify-center gap-1.5 active:scale-95 shrink-0"
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

    <!-- MOBILE SLIDE-UP DRAWER (HAMBURGER MENU) -->
    <div
      v-if="mobileMenuOpen"
      class="fixed inset-0 z-50 md:hidden flex flex-col justify-end bg-black/75 backdrop-blur-sm"
      @click.self="mobileMenuOpen = false"
    >
      <div class="bg-slate-900 border-t border-slate-700 rounded-t-3xl p-5 shadow-2xl space-y-4 animate-in slide-in-from-bottom duration-200 max-h-[85vh] overflow-y-auto">
        <!-- Header Drawer -->
        <div class="flex items-center justify-between pb-3.5 border-b border-slate-800">
          <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-xl bg-amber-500/20 border border-amber-500/40 text-amber-300 flex items-center justify-center font-bold text-xl shrink-0">
              👨‍✈️
            </div>
            <div class="space-y-0.5">
              <strong class="block text-sm sm:text-base text-white font-bold leading-snug">
                {{ currentUser?.name || 'Pengemudi Operasional' }}
              </strong>
              <p class="text-xs text-slate-400 leading-normal">
                NPP: {{ currentUser?.nip || '-' }} • Driver Kantor
              </p>
            </div>
          </div>

          <button
            @click="mobileMenuOpen = false"
            class="w-9 h-9 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-400 hover:text-white flex items-center justify-center text-sm font-bold transition"
            aria-label="Tutup menu"
          >
            ✕
          </button>
        </div>

        <!-- Jam Realtime di Dalam Menu Drawer Mobile -->
        <div class="p-3 rounded-xl bg-slate-950 flex items-center justify-center border border-slate-800 shadow-inner">
          <RealtimeClock theme="dark" />
        </div>

        <!-- Daftar Menu Navigasi Driver -->
        <nav class="space-y-2 text-xs">
          <NuxtLink
            v-for="item in navMenuItems"
            :key="item.path"
            :to="item.path"
            @click="mobileMenuOpen = false"
            class="flex items-center gap-3.5 p-3.5 rounded-xl font-bold transition leading-normal"
            :class="isActiveRoute(item.path)
              ? 'bg-amber-500 text-slate-950 shadow-md shadow-amber-500/25'
              : 'text-slate-300 hover:bg-slate-800 bg-slate-950/50 border border-slate-800/80'"
          >
            <span class="text-xl">{{ item.icon }}</span>
            <span class="flex-1 text-sm font-bold tracking-wide">{{ item.title }}</span>
            <span
              v-if="item.badgeCount && item.badgeCount > 0"
              class="px-2.5 py-0.5 rounded-full text-xs font-mono font-bold"
              :class="isActiveRoute(item.path) ? 'bg-slate-950 text-amber-400' : 'bg-cyan-500/20 text-cyan-300 border border-cyan-500/40'"
            >
              {{ item.badgeCount }}
            </span>
            <span class="text-xs opacity-70">➔</span>
          </NuxtLink>
        </nav>

        <!-- Toggle Suara & Logout di Drawer -->
        <div class="pt-3.5 border-t border-slate-800 space-y-3">
          <button
            type="button"
            @click="toggleSound"
            class="w-full py-3 px-4 rounded-xl border transition flex items-center justify-between text-xs font-bold leading-normal"
            :class="soundEnabled ? 'bg-emerald-500/10 border-emerald-500/30 text-emerald-400' : 'bg-slate-800 border-slate-700 text-slate-400'"
          >
            <span class="flex items-center gap-2.5 text-xs sm:text-sm">
              <span class="text-base">{{ soundEnabled ? '🔔' : '🔕' }}</span>
              <span>Notifikasi Suara Pesanan Baru</span>
            </span>
            <span class="font-mono text-xs px-2.5 py-0.5 rounded bg-slate-950 border border-slate-800">{{ soundEnabled ? 'AKTIF' : 'MUTE' }}</span>
          </button>

          <button
            @click="handleLogout"
            type="button"
            class="w-full py-3 rounded-xl bg-red-500/10 text-red-400 hover:bg-red-500/20 font-bold text-xs sm:text-sm border border-red-500/20 flex items-center justify-center gap-2 transition active:scale-95 leading-normal"
          >
            <svg viewBox="0 0 24 24" class="w-4 h-4" fill="currentColor">
              <path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/>
            </svg>
            <span>Keluar dari Portal Pengemudi</span>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'

const route = useRoute()
const config = useRuntimeConfig()
const apiBase = config.public?.apiBase || 'http://127.0.0.1:8000/api'

// Auth Cookies
const userCookie = useCookie('auth_user')
const tokenCookie = useCookie('auth_token')
const roleCookie = useCookie('auth_role')
const soundCookie = useCookie('driver_sound_enabled', { default: () => true })

const currentUser = ref(null)
const mobileMenuOpen = ref(false)
const routineCount = ref(0)
const soundEnabled = ref(soundCookie.value !== false)

const toggleSound = () => {
  soundEnabled.value = !soundEnabled.value
  soundCookie.value = soundEnabled.value
}

const navMenuItems = computed(() => [
  {
    title: 'Dashboard Pengemudi',
    icon: '🚗',
    path: '/sopir/dashboard'
  },
  {
    title: 'Kalender Jadwal',
    icon: '📅',
    path: '/sopir/kalender',
    badgeCount: routineCount.value
  },
  {
    title: 'Riwayat Perjalanan',
    icon: '📜',
    path: '/sopir/riwayat'
  }
])

const isActiveRoute = (path) => {
  if (path === '/sopir/dashboard') {
    return route.path === '/sopir/dashboard'
  }
  return route.path.startsWith(path)
}

const fetchRoutineSummary = async () => {
  try {
    const myId = currentUser.value?.id
    const myName = currentUser.value?.name
    const q = new URLSearchParams()
    if (myId) q.append('driver_id', myId)
    if (myName) q.append('driver_name', myName)

    const headers = {}
    if (tokenCookie.value) {
      headers['Authorization'] = `Bearer ${tokenCookie.value}`
    }

    const res = await $fetch(`${apiBase}/driver/routine-bookings?${q.toString()}`, { headers })
    if (res?.status === 'success') {
      const routines = res.data?.length || 0
      const incidentals = res.incidentals?.length || 0
      routineCount.value = routines + incidentals
    }
  } catch (err) {
    // Non-blocking
  }
}

const handleLogout = async () => {
  try {
    if (tokenCookie.value) {
      await $fetch(`${apiBase}/logout`, {
        method: 'POST',
        headers: { Authorization: `Bearer ${tokenCookie.value}` }
      })
    }
  } catch (e) {
    // Continue cleanup even if server request fails
  }

  tokenCookie.value = null
  userCookie.value = null
  roleCookie.value = null
  navigateTo('/login')
}

onMounted(() => {
  if (userCookie.value) {
    try {
      currentUser.value = typeof userCookie.value === 'string' ? JSON.parse(userCookie.value) : userCookie.value
    } catch (e) {
      currentUser.value = null
    }
  }
  fetchRoutineSummary()
})
</script>

<style scoped>
/* Scoped driver topbar styles */
</style>
