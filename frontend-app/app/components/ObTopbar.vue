<template>
  <div>
    <!-- TOPBAR WRAPPER (DIBERI JARAK NYAMAN DARI ATAS DAN SISI) -->
    <div class="ob-topbar-wrapper sticky top-0 z-40 pt-3 sm:pt-5 px-3.5 sm:px-6 pb-1.5 transition-all duration-200 pointer-events-none">
      <header
        class="bg-slate-900/95 backdrop-blur-md border border-slate-800 rounded-2xl shadow-xl max-w-6xl mx-auto pointer-events-auto transition-all duration-200"
      >
        <div class="px-3.5 sm:px-6 h-14 sm:h-16 flex items-center justify-between gap-2.5 sm:gap-4 overflow-hidden">
          
          <!-- SISI KIRI: HAMBURGER MOBILE & BRAND OB -->
          <div class="flex items-center gap-2 sm:gap-3 shrink-0">
            <!-- Mobile Hamburger Toggle Button -->
            <button
              type="button"
              @click="mobileMenuOpen = !mobileMenuOpen"
              class="md:hidden w-9 h-9 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 border border-slate-700 flex items-center justify-center transition active:scale-95 shrink-0"
              aria-label="Toggle Menu OB"
            >
              <svg viewBox="0 0 24 24" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5">
                <path v-if="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                <path v-else stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>

            <!-- Brand Logo & Label OB -->
            <NuxtLink to="/ob/dashboard" class="flex items-center gap-2 sm:gap-2.5 group shrink-0">
              <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-white/10 p-1 flex items-center justify-center shadow border border-teal-500/30 group-hover:scale-105 transition-transform overflow-hidden shrink-0">
                <img src="/images/logo-simpel-icon.png" alt="Logo SIMPEL" class="w-full h-full object-contain" />
              </div>
              <div class="leading-tight">
                <div class="flex items-center gap-1.5">
                  <span class="font-brand font-black text-teal-400 text-sm sm:text-base tracking-wider">SIMPEL</span>
                  <span class="px-2 py-0.5 rounded text-[10px] font-black bg-teal-500/20 text-teal-300 border border-teal-500/30">
                    OB / JANITOR
                  </span>
                </div>
                <p class="text-[10px] text-slate-400 hidden sm:block truncate max-w-[160px]">
                  {{ currentUser?.name || 'Petugas Kebersihan' }}
                </p>
              </div>
            </NuxtLink>
          </div>

          <!-- TENGAH: MENU NAVIGASI DESKTOP / TABLET (Tampil di md ke atas) -->
          <nav class="hidden md:flex items-center gap-1 bg-slate-950/80 p-1 rounded-xl border border-slate-800 text-xs shadow-inner shrink-0">
            <NuxtLink
              to="/ob/dashboard"
              class="flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg font-bold transition-all"
              :class="isActiveRoute('/ob/dashboard')
                ? 'bg-teal-500 text-slate-950 shadow-md shadow-teal-500/25'
                : 'text-slate-400 hover:text-slate-200 hover:bg-slate-900'"
            >
              <span>📷</span>
              <span>Scan Alat</span>
            </NuxtLink>

            <NuxtLink
              to="/ob/riwayat"
              class="flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg font-bold transition-all"
              :class="isActiveRoute('/ob/riwayat')
                ? 'bg-teal-500 text-slate-950 shadow-md shadow-teal-500/25'
                : 'text-slate-400 hover:text-slate-200 hover:bg-slate-900'"
            >
              <span>📜</span>
              <span>Riwayat Penggunaan</span>
            </NuxtLink>
          </nav>

          <!-- SISI KANAN: TOMBOL LOGOUT -->
          <div class="flex items-center gap-2 sm:gap-2.5 shrink-0">
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
            <div class="w-11 h-11 rounded-xl bg-teal-500/20 border border-teal-500/40 text-teal-300 flex items-center justify-center font-bold text-xl shrink-0">
              🧹
            </div>
            <div class="space-y-0.5">
              <strong class="block text-sm sm:text-base text-white font-bold leading-snug">
                {{ currentUser?.name || 'Petugas Kebersihan' }}
              </strong>
              <p class="text-xs text-slate-400 leading-normal">
                NPP: {{ currentUser?.nip || '-' }} • Janitor & OB
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

        <!-- Daftar Menu Navigasi OB -->
        <nav class="space-y-2 text-xs">
          <NuxtLink
            to="/ob/dashboard"
            @click="mobileMenuOpen = false"
            class="flex items-center gap-3.5 p-3.5 rounded-xl font-bold transition leading-normal"
            :class="isActiveRoute('/ob/dashboard')
              ? 'bg-teal-500 text-slate-950 shadow-md shadow-teal-500/25'
              : 'text-slate-300 hover:bg-slate-800 bg-slate-950/50 border border-slate-800/80'"
          >
            <span class="text-xl">📷</span>
            <span class="flex-1 text-sm font-bold tracking-wide">Scan & Ambil Alat</span>
            <span class="text-xs opacity-70">➔</span>
          </NuxtLink>

          <NuxtLink
            to="/ob/riwayat"
            @click="mobileMenuOpen = false"
            class="flex items-center gap-3.5 p-3.5 rounded-xl font-bold transition leading-normal"
            :class="isActiveRoute('/ob/riwayat')
              ? 'bg-teal-500 text-slate-950 shadow-md shadow-teal-500/25'
              : 'text-slate-300 hover:bg-slate-800 bg-slate-950/50 border border-slate-800/80'"
          >
            <span class="text-xl">📜</span>
            <span class="flex-1 text-sm font-bold tracking-wide">Riwayat Penggunaan Alat</span>
            <span class="text-xs opacity-70">➔</span>
          </NuxtLink>
        </nav>

        <!-- Tombol Logout di Drawer -->
        <div class="pt-3.5 border-t border-slate-800">
          <button
            @click="handleLogout"
            type="button"
            class="w-full py-3 rounded-xl bg-red-500/10 text-red-400 hover:bg-red-500/20 font-bold text-xs sm:text-sm border border-red-500/20 flex items-center justify-center gap-2 transition active:scale-95 leading-normal"
          >
            <svg viewBox="0 0 24 24" class="w-4 h-4" fill="currentColor">
              <path d="M17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5zM4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4V5z"/>
            </svg>
            <span>Keluar dari Portal OB</span>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'

const route = useRoute()
const mobileMenuOpen = ref(false)
const currentUser = ref({ name: '', nip: '', role: 'ob' })

const isActiveRoute = (path) => {
  return route.path === path
}

const handleLogout = () => {
  if (confirm('Apakah Anda yakin ingin keluar dari portal OB?')) {
    const authUser = useCookie('auth_user')
    const authToken = useCookie('auth_token')
    authUser.value = null
    authToken.value = null
    navigateTo('/login')
  }
}

onMounted(() => {
  try {
    const userCookie = useCookie('auth_user')
    if (userCookie.value) {
      const u = typeof userCookie.value === 'string' ? JSON.parse(userCookie.value) : userCookie.value
      currentUser.value = {
        name: u.name || 'Petugas Kebersihan',
        nip: u.nip || u.username || '-',
        role: u.role || 'ob'
      }
    }
  } catch (e) {
    // fallback
  }
})
</script>

<style scoped>
</style>
