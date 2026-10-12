<template>
  <div class="min-h-screen bg-slate-950 text-slate-100 font-sans pb-16">
    <!-- Header OB Terpadu dengan Hamburger Menu Mobile -->
    <ObTopbar />

    <main class="max-w-5xl mx-auto px-4 py-6 space-y-6">
      <!-- Banner Ringkasan Profil OB -->
      <section class="bg-gradient-to-r from-teal-950/40 via-slate-900 to-slate-900 border border-teal-500/30 rounded-2xl p-5 md:p-6 shadow-xl relative overflow-hidden">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
          <div>
            <div class="flex items-center gap-2 mb-1.5">
              <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-teal-500/20 text-teal-300 border border-teal-500/30">
                PENCATATAN ALAT KEBERSIHAN
              </span>
              <span class="text-xs text-slate-400">Log Pengambilan Mesin & Perlengkapan Janitor</span>
            </div>
            <h2 class="text-lg md:text-xl font-bold text-white">Riwayat Penggunaan Alat Kebersihan Kantor</h2>
            <p class="text-xs text-slate-300 mt-1 max-w-2xl">
              Daftar mesin pembersih lantai, vacuum cleaner, troli janitor, pel microfiber, dan cairan sanitasi yang telah Anda ambil melalui pemindaian barcode fisik.
            </p>
          </div>

          <NuxtLink
            to="/ob/dashboard"
            class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-teal-500 to-emerald-600 hover:from-teal-600 hover:to-emerald-700 text-white text-xs font-bold shadow-lg shadow-teal-500/20 transition flex items-center justify-center gap-2 shrink-0"
          >
            <svg viewBox="0 0 24 24" class="w-4 h-4" fill="currentColor"><path d="M4 4h3l2-2h6l2 2h3a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2zm8 3a5 5 0 1 0 0 10 5 5 0 0 0 0-10zm0 2a3 3 0 1 1 0 6 3 3 0 0 1 0-6z"/></svg>
            <span>Scan Alat Baru</span>
          </NuxtLink>
        </div>
      </section>

      <!-- Statistik Aktivitas OB (2 KOLOM RESPONSIVE MOBILE) -->
      <section class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4">
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-4 sm:p-5 shadow-lg space-y-1">
          <span class="text-xs text-slate-400 block leading-normal">Total Alat Digunakan</span>
          <strong class="text-xl sm:text-2xl font-black text-white font-mono block leading-snug">{{ totalUnitsTaken }}</strong>
          <span class="text-xs text-teal-400/90 block leading-normal">Unit alat & perlengkapan</span>
        </div>

        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-4 sm:p-5 shadow-lg space-y-1">
          <span class="text-xs text-slate-400 block leading-normal">Total Transaksi Scan</span>
          <strong class="text-xl sm:text-2xl font-black text-emerald-400 font-mono block leading-snug">{{ pickups.length }}</strong>
          <span class="text-xs text-emerald-400/90 block leading-normal">Aktivitas penugasan</span>
        </div>

        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-4 sm:p-5 shadow-lg space-y-1">
          <span class="text-xs text-slate-400 block leading-normal">Pengambilan Selesai</span>
          <strong class="text-xl sm:text-2xl font-black text-cyan-400 font-mono block leading-snug">{{ completedPickupsCount }}</strong>
          <span class="text-xs text-cyan-400/90 block leading-normal">Tercatat ke database</span>
        </div>

        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-4 sm:p-5 shadow-lg space-y-1">
          <span class="text-xs text-slate-400 block leading-normal">Varian Alat Pernah Dipakai</span>
          <strong class="text-xl sm:text-2xl font-black text-indigo-400 font-mono block leading-snug">{{ uniqueToolCount }}</strong>
          <span class="text-xs text-indigo-400/90 block leading-normal">Jenis alat kebersihan</span>
        </div>
      </section>

      <!-- Filter & Pencarian -->
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-800 pb-3">
        <div class="flex items-center gap-2 overflow-x-auto pb-1 sm:pb-0">
          <button
            @click="statusFilter = 'all'"
            class="px-3.5 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 whitespace-nowrap leading-normal"
            :class="statusFilter === 'all' ? 'bg-teal-500 text-slate-950 font-black shadow-lg shadow-teal-500/20' : 'bg-slate-900 text-slate-400 hover:text-white border border-slate-800'"
          >
            <span>Semua ({{ pickups.length }})</span>
          </button>
          <button
            @click="statusFilter = 'done'"
            class="px-3.5 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 whitespace-nowrap leading-normal"
            :class="statusFilter === 'done' ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/20' : 'bg-slate-900 text-slate-400 hover:text-white border border-slate-800'"
          >
            <span>Selesai ({{ completedPickupsCount }})</span>
          </button>
        </div>

        <div class="relative">
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Cari alat, lokasi, kode CLN..."
            class="w-full sm:w-64 pl-8 pr-3 py-2 rounded-xl bg-slate-950 border border-slate-800 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-teal-500 leading-normal"
          />
          <span class="absolute left-2.5 top-2.5 text-slate-500 text-xs">🔍</span>
        </div>
      </div>

      <!-- Loading State -->
      <div v-if="isLoading" class="text-center py-16 text-slate-400 text-xs leading-relaxed">
        <div class="w-8 h-8 border-2 border-teal-500 border-t-transparent rounded-full animate-spin mx-auto mb-3"></div>
        Memuat riwayat pengambilan alat kebersihan {{ currentUser.name }}...
      </div>

      <!-- Empty State -->
      <div v-else-if="filteredPickups.length === 0" class="py-16 px-4 text-center text-slate-500 text-xs bg-slate-900/50 rounded-2xl border border-slate-800 leading-relaxed">
        <div class="text-4xl mb-2">🧹</div>
        <p class="font-bold text-slate-300 text-sm">Belum ada riwayat pengambilan alat kebersihan untuk akun ini.</p>
        <p class="text-xs text-slate-400 mt-1 leading-relaxed">Gunakan pemindai barcode pada dashboard OB untuk mencatat alat kebersihan yang akan digunakan.</p>
        <NuxtLink to="/ob/dashboard" class="inline-block mt-4 px-4 py-2.5 rounded-xl bg-teal-500 text-slate-950 font-bold text-xs hover:bg-teal-400 transition leading-normal">
          Buka Dashboard OB
        </NuxtLink>
      </div>

      <!-- DAFTAR KARTU MOBILE (sm:hidden) -->
      <div v-else class="space-y-3.5 sm:hidden">
        <div
          v-for="(entry, index) in filteredPickups"
          :key="entry.uniqueKey || index"
          class="bg-slate-900 border border-slate-800 rounded-2xl p-4 shadow-lg space-y-3"
        >
          <div class="flex items-start gap-3">
            <div class="w-11 h-11 rounded-xl bg-slate-950 border border-slate-800 p-1 shrink-0 flex items-center justify-center overflow-hidden">
              <img
                :src="entry.image || getToolImage(entry.code)"
                :alt="entry.tool_name"
                class="w-full h-full object-contain"
                loading="lazy"
                @error="handleImgError($event, '/images/cleaning/default-cleaning.svg')"
              />
            </div>
            <div class="flex-1 space-y-1">
              <div class="flex items-center justify-between gap-1 flex-wrap">
                <span class="px-2 py-0.5 rounded font-mono font-bold text-xs bg-slate-950 text-teal-400 border border-slate-800">
                  {{ entry.code || 'CLN' }}
                </span>
                <span class="text-xs text-slate-400 font-mono">{{ entry.formattedDate }}</span>
              </div>
              <h4 class="font-bold text-sm text-white leading-snug">{{ entry.tool_name }}</h4>
            </div>
          </div>

          <div class="space-y-1.5 text-xs text-slate-300 pt-2 border-t border-slate-800/80 leading-relaxed">
            <div class="flex items-center justify-between">
              <span class="text-slate-400">Jumlah Diambil:</span>
              <strong class="font-mono text-teal-300 font-bold">{{ entry.quantity }} {{ entry.unit || 'Unit' }}</strong>
            </div>
            <div class="flex items-start justify-between gap-2">
              <span class="text-slate-400 shrink-0">Lokasi / Tugas:</span>
              <span class="text-white font-medium text-right leading-relaxed">{{ entry.location }}</span>
            </div>
            <p v-if="entry.note" class="text-xs text-slate-400 italic pt-1 border-t border-slate-800/60 leading-relaxed">
              💬 {{ entry.note }}
            </p>
          </div>

          <div class="pt-1 flex items-center justify-end">
            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
              ✓ Selesai Dicatat
            </span>
          </div>
        </div>
      </div>

      <!-- TABEL DESKTOP (hidden sm:block) -->
      <div v-if="filteredPickups.length > 0" class="hidden sm:block bg-slate-900 rounded-2xl border border-slate-800 overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
          <table class="w-full text-left text-xs border-collapse">
            <thead>
              <tr class="border-b border-slate-800 bg-slate-950/50 text-slate-400 font-semibold">
                <th class="p-3.5 pl-4">No</th>
                <th class="p-3.5">Waktu / Tanggal</th>
                <th class="p-3.5">Nama Alat Kebersihan & Barcode</th>
                <th class="p-3.5">Jumlah</th>
                <th class="p-3.5">Lokasi & Keterangan Tugas</th>
                <th class="p-3.5 pr-4 text-right">Status</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-800/80">
              <tr v-for="(entry, index) in filteredPickups" :key="entry.uniqueKey || index" class="hover:bg-slate-800/40 transition">
                <td class="p-3.5 pl-4 text-slate-500 font-mono">{{ index + 1 }}</td>
                <td class="p-3.5 text-slate-300 font-mono whitespace-nowrap">
                  {{ entry.formattedDate }}
                </td>
                <td class="p-3.5">
                  <div class="flex items-center gap-2.5">
                    <div class="w-10 h-10 rounded-lg bg-slate-950 border border-slate-800 p-1 shrink-0 flex items-center justify-center overflow-hidden">
                      <img
                        :src="entry.image || getToolImage(entry.code)"
                        :alt="entry.tool_name"
                        class="w-full h-full object-contain"
                        loading="lazy"
                        @error="handleImgError($event, '/images/cleaning/default-cleaning.svg')"
                      />
                    </div>
                    <div>
                      <strong class="text-white block text-sm">{{ entry.tool_name }}</strong>
                      <span v-if="entry.code" class="text-xs text-teal-400 font-mono">
                        {{ entry.code }}
                      </span>
                    </div>
                  </div>
                </td>
                <td class="p-3.5">
                  <span class="px-2.5 py-1 rounded bg-teal-500/10 text-teal-300 border border-teal-500/20 font-bold font-mono">
                    {{ entry.quantity }} {{ entry.unit || 'Unit' }}
                  </span>
                </td>
                <td class="p-3.5 text-slate-300 max-w-[240px]">
                  <span class="block text-white font-medium">📍 {{ entry.location }}</span>
                  <span v-if="entry.note" class="text-xs text-slate-400 truncate block mt-0.5">
                    {{ entry.note }}
                  </span>
                </td>
                <td class="p-3.5 pr-4 text-right">
                  <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                    ✓ Selesai Dicatat
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'

useHead({
  title: 'Riwayat Penggunaan Alat Kebersihan - Portal DigO'
})

const router = useRouter()
const config = useRuntimeConfig()
const apiBase = config.public?.apiBase || 'http://127.0.0.1:8000/api'

const userCookie = useCookie('auth_user')
const tokenCookie = useCookie('auth_token')

const currentUser = ref({
  id: 4,
  name: 'Petugas OB',
  nip: 'ob01',
  role: 'ob'
})

const handleImgError = (e, fallback = '/images/cleaning/default-cleaning.svg') => {
  if (e && e.target) {
    e.target.onerror = null
    e.target.src = fallback
  }
}

const getToolImage = (code) => {
  if (!code) return '/images/cleaning/default-cleaning.svg'
  const clean = String(code).toLowerCase().replace(/[^a-z0-9-]/g, '')
  return `/images/cleaning/${clean}.svg`
}

const pickups = ref([])
const isLoading = ref(false)
const statusFilter = ref('all')
const searchQuery = ref('')

const totalUnitsTaken = computed(() => {
  return pickups.value.reduce((sum, item) => sum + Number(item.quantity || 1), 0)
})

const completedPickupsCount = computed(() => {
  return pickups.value.length
})

const uniqueToolCount = computed(() => {
  const set = new Set(pickups.value.map(p => p.tool_name).filter(Boolean))
  return set.size
})

const filteredPickups = computed(() => {
  const q = searchQuery.value.toLowerCase().trim()
  return pickups.value.filter(p => {
    const name = (p.tool_name || '').toLowerCase()
    const code = (p.code || '').toLowerCase()
    const loc = (p.location || '').toLowerCase()
    const note = (p.note || '').toLowerCase()
    const matchesQuery = !q || name.includes(q) || code.includes(q) || loc.includes(q) || note.includes(q)

    return matchesQuery
  })
})

const loadObHistory = async () => {
  isLoading.value = true

  const headers = {}
  if (tokenCookie.value) {
    headers['Authorization'] = `Bearer ${tokenCookie.value}`
  }

  const userId = currentUser.value.id || 4
  const list = []

  // 1. Ambil dari backend /cleaning-requests
  try {
    const res = await $fetch(`${apiBase}/cleaning-requests?user_id=${userId}`, { headers })
    if (Array.isArray(res)) {
      if (res.length === 0 && typeof window !== 'undefined') {
        localStorage.removeItem('ob_cleaning_pickups')
      }
      res.forEach(item => {
        const tool = item.cleaning_tool || item.cleaningTool
        list.push({
          uniqueKey: `srv-${item.id}`,
          tool_name: tool?.name || 'Alat Kebersihan Gedung',
          code: tool?.code || 'CLN-TOOL',
          quantity: item.quantity || 1,
          unit: tool?.unit || 'Unit',
          image: tool?.image || getToolImage(tool?.code),
          location: item.location || 'Ruangan Kantor',
          note: item.notes || item.service_type || 'Pembersihan Ruangan',
          formattedDate: item.requested_date || (item.created_at ? new Date(item.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' }) + ' WIB' : 'Hari ini'),
          status: 'done'
        })
      })
    }
  } catch (err) {
    try {
      const fallback = await $fetch(`${apiBase}/v1/manage/cleaning-requests?user_id=${userId}`)
      if (Array.isArray(fallback)) {
        fallback.forEach(item => {
          const tool = item.cleaning_tool || item.cleaningTool
          list.push({
            uniqueKey: `srv-${item.id}`,
            tool_name: tool?.name || 'Alat Kebersihan Gedung',
            code: tool?.code || 'CLN-TOOL',
            quantity: item.quantity || 1,
            unit: tool?.unit || 'Unit',
            image: tool?.image || getToolImage(tool?.code),
            location: item.location || 'Ruangan Kantor',
            note: item.notes || item.service_type || 'Pembersihan Ruangan',
            formattedDate: item.requested_date || (item.created_at ? new Date(item.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' }) + ' WIB' : 'Hari ini'),
            status: 'done'
          })
        })
      }
    } catch (e) {
      console.warn('Backend cleaning requests not accessible.')
    }
  }

  // 2. Gabungkan dengan riwayat lokal scanner alat kebersihan OB
  if (typeof window !== 'undefined') {
    const saved = localStorage.getItem('ob_cleaning_pickups')
    if (saved) {
      try {
        const parsed = JSON.parse(saved)
        if (Array.isArray(parsed)) {
          parsed.forEach((localItem, idx) => {
            list.unshift({
              uniqueKey: `loc-${localItem.id || idx}`,
              tool_name: localItem.tool_name || localItem.name,
              code: localItem.code || '',
              quantity: localItem.quantity || 1,
              unit: localItem.unit || 'Unit',
              image: localItem.image || getToolImage(localItem.code),
              location: localItem.location || 'Ruang Rapat Aula Lt. 2',
              note: localItem.note || 'Pengambilan via Scan Barcode Alat',
              formattedDate: localItem.date ? (localItem.time ? `${localItem.date}, ${localItem.time}` : localItem.date) : (localItem.time ? `Hari ini, ${localItem.time}` : 'Hari ini'),
              status: 'done'
            })
          })
        }
      } catch (e) {}
    }
  }

  // Deduplicate items
  const seen = new Set()
  const cleanList = []
  list.forEach(item => {
    const key = `${item.tool_name}-${item.location}-${item.quantity}`
    if (!seen.has(key)) {
      seen.add(key)
      cleanList.push(item)
    }
  })

  pickups.value = cleanList
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
  if ((!currentUser.value.nip || currentUser.value.name === 'Petugas OB') && typeof window !== 'undefined') {
    const s = localStorage.getItem('auth_user')
    if (s) {
      try {
        const u = JSON.parse(s)
        if (u && u.name) currentUser.value = u
      } catch (e) {}
    }
  }

  loadObHistory()
})
</script>
