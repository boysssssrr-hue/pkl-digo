<template>
  <div class="min-h-screen bg-slate-950 text-slate-100 font-sans pb-16">
    <!-- TOPBAR PENGEMUDI TERPADU (TIDAK TERLALU KE ATAS) -->
    <DriverTopbar />

    <main class="max-w-6xl mx-auto px-3 sm:px-6 py-4 space-y-6">
      <!-- Banner Ringkasan Profil Sopir -->
      <section class="bg-gradient-to-r from-amber-950/40 via-slate-900 to-slate-900 border border-amber-500/30 rounded-2xl p-5 md:p-6 shadow-xl relative overflow-hidden">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
          <div>
            <div class="flex items-center gap-2 mb-1.5">
              <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/20 text-amber-400 border border-amber-500/30">
                LOG PERJALANAN RESMI
              </span>
              <span class="text-xs text-slate-400">Pencatatan Keberangkatan & Kepulangan Pengemudi</span>
            </div>
            <h2 class="text-lg md:text-xl font-bold text-white">Riwayat Perjalanan & Pengantaran Pegawai</h2>
            <p class="text-xs text-slate-300 mt-1 max-w-2xl">
              Halaman ini menampilkan seluruh riwayat perjalanan kedinasan yang telah Anda tangani menggunakan mobil dinas operasional kantor.
            </p>
          </div>

          <NuxtLink
            to="/sopir/dashboard"
            class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 text-white text-xs font-bold shadow-lg shadow-orange-500/20 transition flex items-center justify-center gap-2 shrink-0"
          >
            <svg viewBox="0 0 24 24" class="w-4 h-4" fill="currentColor"><path d="M4 4h3l2-2h6l2 2h3a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2zm8 3a5 5 0 1 0 0 10 5 5 0 0 0 0-10zm0 2a3 3 0 1 1 0 6 3 3 0 0 1 0-6z"/></svg>
            <span>Scan Mobil Sekarang</span>
          </NuxtLink>
        </div>
      </section>

      <!-- Statistik Sopir -->
      <section class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4">
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-4 sm:p-5 shadow-lg space-y-1">
          <span class="text-xs text-slate-400 block leading-normal">Total Tugas Pengemudi</span>
          <strong class="text-xl sm:text-2xl font-black text-white font-mono block leading-snug">{{ trips.length }}</strong>
          <span class="text-xs text-amber-400/90 block leading-normal">Semua riwayat tugas</span>
        </div>

        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-4 sm:p-5 shadow-lg space-y-1">
          <span class="text-xs text-slate-400 block leading-normal">Perjalanan Selesai</span>
          <strong class="text-xl sm:text-2xl font-black text-emerald-400 font-mono block leading-snug">{{ completedCount }}</strong>
          <span class="text-xs text-emerald-400/90 block leading-normal">Sampai pool kantor</span>
        </div>

        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-4 sm:p-5 shadow-lg space-y-1">
          <span class="text-xs text-slate-400 block leading-normal">Sedang Berlangsung</span>
          <strong class="text-xl sm:text-2xl font-black text-cyan-400 font-mono block leading-snug">{{ activeCount }}</strong>
          <span class="text-xs text-cyan-400/90 block leading-normal">Perjalanan aktif</span>
        </div>

        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-4 sm:p-5 shadow-lg space-y-1">
          <span class="text-xs text-slate-400 block leading-normal">Armada Berbeda</span>
          <strong class="text-xl sm:text-2xl font-black text-indigo-400 font-mono block leading-snug">{{ uniqueArmadaCount }}</strong>
          <span class="text-xs text-indigo-400/90 block leading-normal">Mobil pernah dibawa</span>
        </div>
      </section>

      <!-- Filter & Pencarian Riwayat -->
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-800 pb-3">
        <div class="flex items-center gap-2 overflow-x-auto pb-1 sm:pb-0">
          <button
            @click="statusFilter = 'all'"
            class="px-3.5 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 whitespace-nowrap leading-normal"
            :class="statusFilter === 'all' ? 'bg-amber-500 text-slate-950 font-black shadow-lg shadow-amber-500/20' : 'bg-slate-900 text-slate-400 hover:text-white border border-slate-800'"
          >
            <span>Semua ({{ trips.length }})</span>
          </button>
          <button
            @click="statusFilter = 'completed'"
            class="px-3.5 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 whitespace-nowrap leading-normal"
            :class="statusFilter === 'completed' ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/20' : 'bg-slate-900 text-slate-400 hover:text-white border border-slate-800'"
          >
            <span>Selesai ({{ completedCount }})</span>
          </button>
          <button
            @click="statusFilter = 'in_progress'"
            class="px-3.5 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 whitespace-nowrap leading-normal"
            :class="statusFilter === 'in_progress' ? 'bg-cyan-600 text-white shadow-lg shadow-cyan-600/20' : 'bg-slate-900 text-slate-400 hover:text-white border border-slate-800'"
          >
            <span>Berjalan / Aktif ({{ activeCount }})</span>
          </button>
        </div>

        <div class="relative">
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Cari armada, tujuan, pegawai..."
            class="w-full sm:w-64 pl-8 pr-3 py-2 rounded-lg bg-slate-900 border border-slate-800 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-amber-500 leading-normal"
          />
          <span class="absolute left-2.5 top-2.5 text-slate-500 text-xs">🔍</span>
        </div>
      </div>

      <!-- Loading State -->
      <div v-if="isLoading" class="text-center py-16 text-slate-400 text-xs leading-relaxed">
        <div class="w-8 h-8 border-2 border-amber-500 border-t-transparent rounded-full animate-spin mx-auto mb-3"></div>
        Memuat riwayat perjalanan pengemudi {{ currentUser.name }}...
      </div>

      <!-- Empty State -->
      <div v-else-if="filteredTrips.length === 0" class="py-16 px-4 text-center text-slate-500 text-xs bg-slate-900/50 rounded-2xl border border-slate-800 leading-relaxed">
        <div class="text-4xl mb-2">🚗</div>
        <p class="font-bold text-slate-300 text-sm">Belum ada catatan riwayat perjalanan untuk pengemudi ini.</p>
        <p class="text-xs text-slate-400 mt-1 leading-relaxed">Lakukan scan keberangkatan pada mobil dinas melalui tombol di dashboard pengemudi.</p>
        <NuxtLink to="/sopir/dashboard" class="inline-block mt-4 px-4 py-2.5 rounded-xl bg-amber-500 text-slate-950 font-bold text-xs hover:bg-amber-400 transition leading-normal">
          Buka Dashboard Pengemudi
        </NuxtLink>
      </div>

      <!-- Daftar Riwayat Perjalanan Sopir -->
      <div v-else class="space-y-3.5">
        <div
          v-for="trip in filteredTrips"
          :key="trip.id"
          class="bg-slate-900 border rounded-2xl p-4 sm:p-5 shadow-lg transition hover:border-slate-700 flex flex-col md:flex-row md:items-center justify-between gap-4"
          :class="trip.status === 'in_progress' ? 'border-cyan-500/40 bg-cyan-950/10' : (trip.status === 'completed' ? 'border-slate-800' : 'border-amber-500/30')"
        >
          <div class="flex items-start gap-3.5 flex-1">
            <div
              class="w-11 h-11 sm:w-12 sm:h-12 rounded-xl flex items-center justify-center text-xl shrink-0 border"
              :class="trip.status === 'completed' ? 'bg-emerald-500/10 border-emerald-500/20 text-emerald-400' : (trip.status === 'in_progress' ? 'bg-cyan-500/10 border-cyan-500/20 text-cyan-400' : 'bg-amber-500/10 border-amber-500/20 text-amber-400')"
            >
              🚗
            </div>

            <div class="flex-1">
              <div class="flex items-center gap-2 mb-1.5 flex-wrap">
                <span class="font-mono text-xs font-bold px-2.5 py-0.5 rounded-lg bg-slate-800 text-amber-300 border border-slate-700">
                  {{ trip.vehicle?.license_plate || 'MOBIL DINAS' }}
                </span>
                <span class="text-xs font-bold text-slate-200">
                  {{ trip.vehicle?.name || 'Mobil Dinas Operasional' }}
                </span>
              </div>

              <!-- Rincian Informasi Tidak Dempet & Rapih -->
              <div class="space-y-2 text-xs text-slate-300 mt-2.5 leading-relaxed">
                <div class="flex flex-col sm:flex-row sm:items-start gap-0.5 sm:gap-2">
                  <span class="text-slate-400 font-medium shrink-0 sm:w-28">📍 Destinasi:</span>
                  <strong class="text-white text-xs sm:text-sm font-bold leading-relaxed flex-1">{{ trip.destination }}</strong>
                </div>
                <div class="flex flex-col sm:flex-row sm:items-start gap-0.5 sm:gap-2">
                  <span class="text-slate-400 font-medium shrink-0 sm:w-28">👥 Penumpang:</span>
                  <span class="text-slate-200 leading-relaxed flex-1">
                    {{ trip.passengers || (trip.user?.name ? `${trip.user.name} & Tim` : 'Pegawai Kantor') }}
                    <span v-if="trip.user?.division?.name" class="text-slate-400 text-xs font-mono ml-1">({{ trip.user.division.name }})</span>
                  </span>
                </div>
                <div class="flex flex-col sm:flex-row sm:items-start gap-0.5 sm:gap-2 font-mono">
                  <span class="text-slate-400 font-medium shrink-0 sm:w-28">🕒 Jadwal:</span>
                  <div class="flex-1 leading-relaxed text-slate-300">
                    <span>Berangkat: {{ formatTripDate(trip.start_date || trip.created_at) }}</span>
                    <span v-if="trip.end_date" class="text-emerald-400 block sm:inline sm:ml-2">
                      ✓ Kembali: {{ formatTripDate(trip.end_date) }}
                    </span>
                  </div>
                </div>
              </div>

            </div>
          </div>

          <div class="flex items-center justify-between md:flex-col md:items-end gap-2 shrink-0 border-t md:border-t-0 pt-3 md:pt-0 border-slate-800">
            <span
              class="px-3 py-1 rounded-full text-xs font-bold border leading-normal"
              :class="trip.status === 'completed' ? 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30' : (trip.status === 'in_progress' ? 'bg-cyan-500/20 text-cyan-300 border-cyan-500/30' : 'bg-amber-500/20 text-amber-300 border-amber-500/30')"
            >
              {{ trip.status === 'completed' ? '✓ Perjalanan Selesai' : (trip.status === 'in_progress' ? '🔄 Sedang Diantar' : '⏳ Menunggu Berangkat') }}
            </span>
            <span class="text-xs text-slate-400 font-mono">
              Driver: {{ trip.driver?.name || trip.driver_name || currentUser.name }}
            </span>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'

useHead({
  title: 'Riwayat Tugas Pengemudi - Portal DigO'
})

const router = useRouter()
const config = useRuntimeConfig()
const apiBase = config.public?.apiBase || 'http://127.0.0.1:8000/api'

const userCookie = useCookie('auth_user')
const tokenCookie = useCookie('auth_token')

const currentUser = ref({
  id: 3,
  name: 'Pengemudi',
  nip: 'ahmad',
  role: 'sopir'
})

const trips = ref([])
const isLoading = ref(false)
const statusFilter = ref('all')
const searchQuery = ref('')

const formatTripDate = (dateStr) => {
  if (!dateStr) return '-'
  try {
    const d = new Date(dateStr)
    return d.toLocaleDateString('id-ID', {
      day: 'numeric',
      month: 'short',
      year: 'numeric',
      hour: '2-digit',
      minute: '2-digit'
    }) + ' WIB'
  } catch (e) {
    return dateStr
  }
}

const completedCount = computed(() => {
  return trips.value.filter(t => t.status === 'completed').length
})

const activeCount = computed(() => {
  return trips.value.filter(t => t.status === 'in_progress').length
})

const uniqueArmadaCount = computed(() => {
  const set = new Set(trips.value.map(t => t.vehicle?.license_plate || t.vehicle_id).filter(Boolean))
  return set.size
})

const filteredTrips = computed(() => {
  const q = searchQuery.value.toLowerCase().trim()
  return trips.value.filter(t => {
    const car = (t.vehicle?.name || '').toLowerCase()
    const plate = (t.vehicle?.license_plate || '').toLowerCase()
    const dest = (t.destination || '').toLowerCase()
    const pass = (t.passengers || '').toLowerCase()
    const user = (t.user?.name || '').toLowerCase()
    const matchesQuery = !q || car.includes(q) || plate.includes(q) || dest.includes(q) || pass.includes(q) || user.includes(q)

    let matchesStatus = true
    if (statusFilter.value === 'completed') {
      matchesStatus = t.status === 'completed'
    } else if (statusFilter.value === 'in_progress') {
      matchesStatus = t.status === 'in_progress'
    }

    return matchesQuery && matchesStatus
  })
})

const loadDriverHistory = async () => {
  isLoading.value = true

  const headers = {}
  if (tokenCookie.value) {
    headers['Authorization'] = `Bearer ${tokenCookie.value}`
  }

  const driverId = currentUser.value.id || ''
  const driverName = currentUser.value.name || ''

  try {
    const url = `${apiBase}/driver/history?driver_id=${driverId}&driver_name=${encodeURIComponent(driverName)}`
    const res = await $fetch(url, { headers })
    if (res && res.data && Array.isArray(res.data)) {
      trips.value = res.data
    } else if (Array.isArray(res)) {
      trips.value = res
    }
  } catch (err) {
    try {
      const fallbackUrl = `${apiBase}/v1/manage/driver/history?driver_id=${driverId}&driver_name=${encodeURIComponent(driverName)}`
      const fallback = await $fetch(fallbackUrl)
      if (fallback && fallback.data) trips.value = fallback.data
    } catch (e) {
      console.warn('Driver history endpoint not accessible.')
    }
  }


  isLoading.value = false
}

const handleLogout = () => {
  userCookie.value = null
  tokenCookie.value = null
  if (typeof window !== 'undefined') {
    localStorage.removeItem('auth_user')
    localStorage.removeItem('auth_token')
  }
  router.push('/')
}

onMounted(() => {
  if (userCookie.value) {
    try {
      const u = typeof userCookie.value === 'string' ? JSON.parse(userCookie.value) : userCookie.value
      if (u && u.name) currentUser.value = u
    } catch (e) {}
  }
  if ((!currentUser.value.nip || currentUser.value.name === 'Pengemudi' || currentUser.value.name === 'Sopir') && typeof window !== 'undefined') {
    const s = localStorage.getItem('auth_user')
    if (s) {
      try {
        const u = JSON.parse(s)
        if (u && u.name) currentUser.value = u
      } catch (e) {}
    }
  }

  loadDriverHistory()
})
</script>

<style scoped>
/* Scoped driver riwayat styles */
</style>
