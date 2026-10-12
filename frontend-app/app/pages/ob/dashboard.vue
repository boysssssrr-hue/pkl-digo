<template>
  <div class="min-h-screen bg-slate-950 text-slate-100 font-sans pb-16">
    <!-- Header OB Terpadu dengan Hamburger Menu Mobile -->
    <ObTopbar />

    <main class="max-w-6xl mx-auto px-3.5 sm:px-6 py-4 sm:py-6 space-y-6">
      <!-- Notification Alert -->
      <div v-if="alertMsg" class="p-4 rounded-xl border flex items-center justify-between transition" :class="alertMsg.type === 'error' ? 'bg-red-500/10 border-red-500/30 text-red-300' : 'bg-teal-500/10 border-teal-500/30 text-teal-300'">
        <div class="flex items-center gap-3">
          <span class="text-lg">{{ alertMsg.type === 'error' ? '⚠️' : '✅' }}</span>
          <p class="text-xs md:text-sm font-medium">{{ alertMsg.text }}</p>
        </div>
        <button @click="alertMsg = null" class="text-slate-400 hover:text-white text-xs">✕</button>
      </div>

      <!-- REDIRECT ALERT BANNER -->
      <div v-if="redirectMessage" class="p-4 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-bold text-xs md:text-sm flex items-center gap-3 shadow-xl animate-pulse">
        <span class="text-lg">✓</span>
        <span>{{ redirectMessage }}</span>
      </div>

      <!-- HERO BANNER SCANNER ALAT KEBERSIHAN -->
      <section class="bg-gradient-to-r from-teal-950/70 via-slate-900 to-slate-900 border border-teal-500/30 rounded-2xl p-5 md:p-6 shadow-xl relative overflow-hidden">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-5">
          <div class="max-w-2xl">
            <div class="flex items-center gap-2 mb-1.5 flex-wrap">
              <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-teal-500/20 text-teal-300 border border-teal-500/30">
                ✦ SISTEM SCAN ALAT KEBERSIHAN OB
              </span>
              <span class="text-xs text-slate-400 flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                Kamera Barcode / QR Ready
              </span>
            </div>
            <h2 class="text-xl md:text-2xl font-black text-white leading-snug">Scan Barcode Alat Kebersihan yang Digunakan</h2>
            <p class="text-xs sm:text-sm text-slate-300 mt-1.5 leading-relaxed">
              Pindai barcode fisik pada mesin polisher, vacuum cleaner, mop set, cairan desinfektan, atau troli kebersihan sebelum digunakan. Data pengambilan otomatis tercatat ke buku riwayat dinas Anda.
            </p>
          </div>

          <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5 shrink-0">
            <button
              type="button"
              @click="isCameraActive ? stopCamera() : startCamera()"
              class="w-full sm:w-auto px-5 py-3 rounded-xl font-bold text-xs sm:text-sm flex items-center justify-center gap-2 transition shadow-lg active:scale-95 leading-normal"
              :class="isCameraActive ? 'bg-red-500/20 text-red-400 border border-red-500/40 hover:bg-red-500/30' : 'bg-gradient-to-r from-teal-500 to-emerald-600 text-white shadow-teal-500/25 hover:from-teal-600 hover:to-emerald-700'"
            >
              <svg viewBox="0 0 24 24" class="w-4 h-4" fill="currentColor">
                <path d="M4 4h3l2-2h6l2 2h3a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2zm8 3a5 5 0 1 0 0 10 5 5 0 0 0 0-10zm0 2a3 3 0 1 1 0 6 3 3 0 0 1 0-6z"/>
              </svg>
              <span>{{ isCameraActive ? 'Matikan Kamera' : '📷 Buka Kamera Scanner' }}</span>
            </button>

            <NuxtLink
              to="/ob/riwayat"
              class="w-full sm:w-auto px-4 py-3 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs sm:text-sm font-semibold border border-slate-700 transition flex items-center justify-center gap-2 leading-normal"
            >
              <span>📜 Riwayat ({{ pickups.length }})</span>
            </NuxtLink>
          </div>
        </div>

        <!-- Viewfinder Kamera Scanner Realtime -->
        <div v-if="isCameraActive" class="mt-5 relative w-full h-64 md:h-72 bg-black rounded-xl overflow-hidden border border-teal-500/40 flex items-center justify-center shadow-inner">
          <video ref="videoRef" class="w-full h-full object-cover" playsinline autoplay></video>
          
          <!-- Laser Animation Frame -->
          <div class="absolute inset-x-8 sm:inset-x-12 inset-y-6 sm:inset-y-8 border-2 border-dashed border-teal-400 rounded-xl pointer-events-none flex items-center justify-center">
            <div class="w-full h-0.5 bg-gradient-to-r from-transparent via-teal-400 to-transparent animate-bounce"></div>
          </div>
          <div class="absolute bottom-3 bg-black/85 px-3.5 py-1.5 rounded-full text-xs text-teal-300 font-medium border border-teal-500/30 flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-teal-400 animate-pulse"></span>
            <span>Arahkan kamera ke barcode alat kebersihan</span>
          </div>
        </div>

        <!-- Input Barcode Manual & Quick Barcode Simulation -->
        <div class="mt-5 pt-4 border-t border-slate-800/80 flex flex-col md:flex-row md:items-center justify-between gap-3">
          <div class="flex items-center gap-2 flex-1 max-w-md">
            <div class="relative flex-1">
              <input
                v-model="manualBarcodeInput"
                type="text"
                placeholder="Ketik kode barcode manual (misal: CLN-001)..."
                class="w-full pl-8 pr-3 py-2 rounded-lg bg-slate-950 border border-slate-700 text-xs text-white focus:outline-none focus:border-teal-500 font-mono leading-normal"
                @keyup.enter="handleManualBarcodeSubmit"
              />
              <span class="absolute left-2.5 top-2.5 text-slate-500 text-xs">🏷️</span>
            </div>
            <button
              type="button"
              @click="handleManualBarcodeSubmit"
              class="px-4 py-2 rounded-lg bg-teal-600 hover:bg-teal-500 text-white font-bold text-xs transition shrink-0 leading-normal"
            >
              Pindai
            </button>
          </div>

          <!-- Quick Test Chips Barcode Alat Kebersihan -->
          <div class="flex items-center gap-1.5 overflow-x-auto pb-1 md:pb-0">
            <span class="text-xs text-slate-500 whitespace-nowrap">Simulasi:</span>
            <button
              v-for="tool in cleaningTools.slice(0, 5)"
              :key="tool.id"
              @click="handleBarcodeScanned(tool.code)"
              class="px-2.5 py-1.5 rounded-lg bg-slate-950 hover:bg-slate-800 text-xs text-slate-300 border border-slate-800 hover:border-teal-500/40 transition flex items-center gap-1 whitespace-nowrap font-mono"
            >
              <span class="text-teal-400 font-bold">{{ tool.code }}</span>
              <span class="text-slate-400">({{ tool.name.split(' ')[0] }})</span>
            </button>
          </div>
        </div>
      </section>

      <!-- STATISTIK RINGKASAN ALAT KEBERSIHAN (2 KOLOM RESPONSIVE MOBILE) -->
      <section class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-4 sm:p-5 shadow-lg space-y-1">
          <div class="flex items-center justify-between">
            <span class="text-xs text-slate-400 font-medium">Total Alat</span>
            <span class="w-8 h-8 rounded-lg bg-teal-500/10 border border-teal-500/20 text-teal-400 flex items-center justify-center text-sm">🧹</span>
          </div>
          <strong class="text-lg sm:text-xl font-bold text-white block">{{ cleaningTools.length }} Jenis</strong>
          <span class="text-xs text-teal-400/90 block leading-normal">{{ totalAvailableUnits }} unit di gudang</span>
        </div>

        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-4 sm:p-5 shadow-lg space-y-1">
          <div class="flex items-center justify-between">
            <span class="text-xs text-slate-400 font-medium">Diambil Hari Ini</span>
            <span class="w-8 h-8 rounded-lg bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center text-sm">✓</span>
          </div>
          <strong class="text-lg sm:text-xl font-bold text-white block">{{ totalTakenToday }} Alat</strong>
          <span class="text-xs text-emerald-400/90 block leading-normal">{{ pickups.length }} tugas tercatat</span>
        </div>

        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-4 sm:p-5 shadow-lg space-y-1">
          <div class="flex items-center justify-between">
            <span class="text-xs text-slate-400 font-medium">Sedang Beroperasi</span>
            <span class="w-8 h-8 rounded-lg bg-amber-500/10 border border-amber-500/20 text-amber-400 flex items-center justify-center text-sm">🔄</span>
          </div>
          <strong class="text-lg sm:text-xl font-bold text-amber-400 block">{{ inUseToolsCount }} Alat</strong>
          <span class="text-xs text-slate-400 block leading-normal">Dipakai di ruangan</span>
        </div>

        <div class="bg-slate-900 border border-slate-800 rounded-2xl p-4 sm:p-5 shadow-lg space-y-1">
          <div class="flex items-center justify-between">
            <span class="text-xs text-slate-400 font-medium">Riwayat OB</span>
            <span class="w-8 h-8 rounded-lg bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 flex items-center justify-center text-sm">📜</span>
          </div>
          <strong class="text-lg sm:text-xl font-bold text-white block">Tersinkron</strong>
          <NuxtLink to="/ob/riwayat" class="text-xs text-cyan-400 hover:underline block font-semibold leading-normal">
            Buka Riwayat ➜
          </NuxtLink>
        </div>
      </section>

      <!-- KATALOG ALAT KEBERSIHAN: KARTU RESPONSIVE DI MOBILE & TABEL DI DESKTOP -->
      <section class="bg-slate-900 rounded-2xl border border-slate-800 p-4 sm:p-6 shadow-xl space-y-4">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-3.5 pb-3 border-b border-slate-800">
          <div>
            <h3 class="font-bold text-sm sm:text-base text-white">Inventaris Alat Kebersihan & Janitor</h3>
            <p class="text-xs text-slate-400 leading-relaxed mt-0.5">Pilih alat kebersihan untuk scan barcode atau ambil langsung sesuai area tugas gedung.</p>
          </div>

          <div class="flex flex-col sm:flex-row items-center gap-2">
            <!-- Search Box -->
            <div class="relative w-full sm:w-64">
              <input
                v-model="searchQuery"
                type="text"
                placeholder="Cari alat, kode (CLN)..."
                class="w-full pl-8 pr-3 py-2 rounded-xl bg-slate-950 border border-slate-700 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-teal-500 leading-normal"
              />
              <span class="absolute left-2.5 top-2.5 text-slate-500 text-xs">🔍</span>
            </div>

            <!-- Category Filter Pills -->
            <select
              v-model="selectedCategory"
              class="w-full sm:w-auto px-3.5 py-2 rounded-xl bg-slate-950 border border-slate-700 text-xs text-slate-300 focus:outline-none focus:border-teal-500 leading-normal"
            >
              <option v-for="cat in categories" :key="cat" :value="cat">{{ cat }}</option>
            </select>
          </div>
        </div>

        <!-- TAMPILAN MOBILE: KARTU ALAT KEBERSIHAN (sm:hidden) -->
        <div class="sm:hidden space-y-3">
          <div
            v-if="filteredCleaningTools.length === 0"
            class="p-8 text-center text-slate-500 text-xs bg-slate-950/60 rounded-xl border border-slate-800 leading-relaxed"
          >
            {{ searchQuery ? `Tidak ada alat kebersihan yang cocok dengan pencarian "${searchQuery}".` : 'Belum ada data alat kebersihan yang terdaftar dalam sistem.' }}
          </div>

          <div
            v-for="(tool, index) in filteredCleaningTools"
            :key="tool.id"
            class="p-4 rounded-xl border border-slate-800 bg-slate-950/70 space-y-3 shadow-md"
          >
            <div class="flex items-start gap-3">
              <div class="w-14 h-14 rounded-xl bg-slate-900 border border-slate-800 p-1 shrink-0 flex items-center justify-center overflow-hidden">
                <img
                  :src="tool.image || '/images/cleaning/default-cleaning.svg'"
                  :alt="tool.name"
                  class="w-full h-full object-contain"
                  loading="lazy"
                  @error="handleImgError($event, '/images/cleaning/default-cleaning.svg')"
                />
              </div>

              <div class="flex-1 space-y-1">
                <div class="flex items-center gap-1.5 flex-wrap">
                  <span class="px-2 py-0.5 rounded font-mono font-bold text-xs bg-slate-900 text-teal-400 border border-slate-700">
                    {{ tool.code }}
                  </span>
                  <span class="px-2 py-0.5 rounded text-xs font-bold bg-slate-800 text-slate-300 border border-slate-700">
                    {{ tool.category }}
                  </span>
                </div>
                <h4 class="font-bold text-sm text-white leading-snug">{{ tool.name }}</h4>
                <p class="text-xs text-slate-400 leading-relaxed">{{ tool.description || 'Peralatan operasional kebersihan gedung' }}</p>
              </div>
            </div>

            <div class="flex items-center justify-between pt-2 border-t border-slate-800/80 text-xs">
              <div class="space-y-0.5">
                <span class="text-slate-400 block">📍 {{ tool.location || 'Gudang Janitor' }}</span>
                <span
                  class="font-mono font-bold block"
                  :class="tool.quantity > 0 ? 'text-emerald-400' : 'text-amber-400'"
                >
                  Stok: {{ tool.quantity }} {{ tool.unit || 'Unit' }} ({{ tool.status }})
                </span>
              </div>

              <button
                type="button"
                @click="openPickupModal(tool)"
                class="px-3.5 py-2 rounded-xl bg-teal-600 hover:bg-teal-500 text-white font-bold text-xs transition shadow-md shadow-teal-600/20 flex items-center gap-1.5 active:scale-95 disabled:opacity-35 disabled:cursor-not-allowed leading-normal"
                :disabled="tool.quantity <= 0 && tool.status === 'Perbaikan'"
              >
                <span>🧹</span>
                <span>Ambil</span>
              </button>
            </div>
          </div>
        </div>

        <!-- TAMPILAN DESKTOP: TABEL ALAT KEBERSIHAN (hidden sm:block) -->
        <div class="hidden sm:block overflow-x-auto">
          <table class="w-full text-left text-xs border-collapse">
            <thead>
              <tr class="border-b border-slate-800 bg-slate-950/60 text-slate-400 font-semibold">
                <th class="p-3 pl-4">#</th>
                <th class="p-3">Kode & Barcode</th>
                <th class="p-3">Nama Alat Kebersihan & Spesifikasi</th>
                <th class="p-3">Kategori</th>
                <th class="p-3">Lokasi Penyimpanan</th>
                <th class="p-3">Stok Alat</th>
                <th class="p-3 pr-4 text-center">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-800/80">
              <tr
                v-for="(tool, index) in filteredCleaningTools"
                :key="tool.id"
                class="hover:bg-slate-800/40 transition"
              >
                <td class="p-3 pl-4 text-slate-500 font-mono">{{ index + 1 }}</td>
                <td class="p-3">
                  <span class="px-2 py-0.5 rounded font-mono font-bold text-xs bg-slate-950 text-teal-400 border border-slate-800">
                    {{ tool.code }}
                  </span>
                </td>
                <td class="p-3">
                  <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-xl bg-slate-950 border border-slate-800 p-1 shrink-0 flex items-center justify-center overflow-hidden">
                      <img
                        :src="tool.image || '/images/cleaning/default-cleaning.svg'"
                        :alt="tool.name"
                        class="w-full h-full object-contain"
                        loading="lazy"
                        @error="handleImgError($event, '/images/cleaning/default-cleaning.svg')"
                      />
                    </div>
                    <div>
                      <strong class="text-white block text-sm">{{ tool.name }}</strong>
                      <span class="text-xs text-slate-500 leading-normal">{{ tool.description || 'Peralatan operasional kebersihan gedung' }}</span>
                    </div>
                  </div>
                </td>
                <td class="p-3">
                  <span class="px-2 py-0.5 rounded text-xs font-bold bg-slate-800 text-slate-300 border border-slate-700">
                    {{ tool.category }}
                  </span>
                </td>
                <td class="p-3 text-slate-400">
                  📍 {{ tool.location || 'Gudang Janitor' }}
                </td>
                <td class="p-3">
                  <div class="flex items-center gap-2">
                    <span
                      class="text-xs font-bold font-mono"
                      :class="tool.quantity > 0 ? 'text-emerald-400' : 'text-amber-400'"
                    >
                      {{ tool.quantity }} {{ tool.unit || 'Unit' }}
                    </span>
                    <span
                      class="px-2 py-0.5 rounded text-xs font-bold uppercase"
                      :class="tool.status === 'Tersedia' ? 'bg-emerald-500/10 text-emerald-300' : (tool.status === 'Sedang Digunakan' ? 'bg-amber-500/10 text-amber-300' : 'bg-red-500/10 text-red-400')"
                    >
                      {{ tool.status }}
                    </span>
                  </div>
                </td>
                <td class="p-3 pr-4 text-center">
                  <button
                    type="button"
                    @click="openPickupModal(tool)"
                    class="w-8 h-8 rounded-lg bg-teal-600 hover:bg-teal-500 text-white font-bold text-xs transition shadow-md shadow-teal-600/20 inline-flex items-center justify-center mx-auto hover:-translate-y-0.5 disabled:opacity-35 disabled:cursor-not-allowed"
                    :disabled="tool.quantity <= 0 && tool.status === 'Perbaikan'"
                    :title="tool.quantity > 0 ? `Ambil Alat: ${tool.name}` : 'Alat Sedang Tidak Tersedia'"
                    :aria-label="tool.quantity > 0 ? 'Ambil Alat' : 'Tidak Tersedia'"
                  >
                    <span class="text-sm">🧹</span>
                  </button>
                </td>
              </tr>

              <tr v-if="filteredCleaningTools.length === 0">
                <td colspan="7" class="p-8 text-center text-slate-500 text-xs">
                  {{ searchQuery ? `Tidak ada alat kebersihan yang cocok dengan pencarian "${searchQuery}".` : 'Belum ada data alat kebersihan yang terdaftar dalam sistem.' }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>

      <!-- RIWAYAT PENGAMBILAN TERAKHIR ALAT KEBERSIHAN OLEH OB -->
      <section class="bg-slate-900 rounded-2xl border border-slate-800 p-5 shadow-xl">
        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-800">
          <div>
            <h3 class="font-bold text-sm md:text-base text-white">Riwayat Penggunaan Alat Kebersihan Hari Ini</h3>
            <p class="text-xs text-slate-400">Pencatatan alat yang sedang atau telah Anda gunakan untuk pembersihan ruangan.</p>
          </div>
          <NuxtLink
            to="/ob/riwayat"
            class="px-3 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-teal-400 hover:text-teal-300 text-xs font-semibold border border-slate-700 transition flex items-center gap-1"
          >
            <span>Buka Riwayat Lengkap</span>
            <span>➜</span>
          </NuxtLink>
        </div>

        <div v-if="pickups.length === 0" class="py-10 text-center text-slate-500 text-xs">
          <div class="text-3xl mb-2">🧹</div>
          <p class="font-semibold text-slate-400">Belum ada alat kebersihan yang diambil hari ini.</p>
          <p class="text-[11px] text-slate-600 mt-0.5">Pindai barcode atau klik tombol Ambil Alat pada tabel di atas.</p>
        </div>

        <div v-else class="divide-y divide-slate-800">
          <div
            v-for="item in pickups.slice(0, 5)"
            :key="item.id"
            class="py-3 flex flex-col sm:flex-row sm:items-center justify-between gap-3"
          >
            <div class="flex items-center gap-3">
              <div class="w-9 h-9 rounded-lg bg-teal-500/10 border border-teal-500/20 text-teal-400 flex items-center justify-center font-bold text-sm shrink-0">
                🧹
              </div>
              <div>
                <strong class="text-sm text-white block">{{ item.tool_name || item.product_name }}</strong>
                <div class="flex items-center gap-2 text-[11px] text-slate-400 mt-0.5">
                  <span class="font-mono text-teal-400 font-bold">{{ item.code || item.barcode }}</span>
                  <span>•</span>
                  <span class="text-teal-400 font-semibold font-mono">{{ item.quantity }} {{ item.unit || 'Unit' }}</span>
                  <span>•</span>
                  <span class="text-slate-300">📍 {{ item.location || item.note }}</span>
                </div>
              </div>
            </div>

            <div class="flex items-center justify-between sm:flex-col sm:items-end gap-1 shrink-0">
              <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                ✓ Selesai Dicatat
              </span>
              <span class="text-[10px] text-slate-500 font-mono">{{ item.time || 'Hari ini' }}</span>
            </div>
          </div>
        </div>
      </section>
    </main>

    <!-- ============================================================= -->
    <!-- MODAL FORM PENGAMBILAN ALAT KEBERSIHAN (SETELAH SCAN / KLIK)  -->
    <!-- ============================================================= -->
    <div v-if="selectedTool" class="fixed inset-0 z-50 bg-black/80 backdrop-blur-sm flex items-center justify-center p-4" @click.self="closePickupModal">
      <div class="bg-slate-900 border border-slate-700 w-full max-w-md rounded-2xl shadow-2xl overflow-hidden">
        <div class="p-4 bg-slate-800/90 border-b border-slate-700 flex items-center justify-between">
          <div class="flex items-center gap-2">
            <span class="text-xl">🧹</span>
            <h3 class="font-bold text-sm text-white">Ambil Alat Kebersihan (Petugas OB)</h3>
          </div>
          <button @click="closePickupModal" class="text-slate-400 hover:text-white text-sm">✕</button>
        </div>

        <form @submit.prevent="submitPickup" class="p-5 space-y-4 text-xs">
          <!-- Detail Alat Kebersihan Scanned -->
          <div class="bg-slate-950 p-3.5 rounded-xl border border-slate-800 flex items-center gap-3">
            <div class="w-14 h-14 rounded-xl bg-slate-900 border border-slate-800 p-1 flex items-center justify-center shrink-0 overflow-hidden">
              <img
                :src="selectedTool.image || '/images/cleaning/default-cleaning.svg'"
                :alt="selectedTool.name"
                class="w-full h-full object-contain"
                @error="handleImgError($event, '/images/cleaning/default-cleaning.svg')"
              />
            </div>
            <div>
              <strong class="text-sm text-white block">{{ selectedTool.name }}</strong>
              <div class="flex items-center gap-2 text-[11px] text-slate-400 mt-0.5">
                <span class="font-mono text-teal-400 font-semibold">{{ selectedTool.code }}</span>
                <span>•</span>
                <span>Stok: <b class="text-white">{{ selectedTool.quantity }} {{ selectedTool.unit }}</b></span>
                <span>•</span>
                <span>📍 {{ selectedTool.location }}</span>
              </div>
            </div>
          </div>

          <!-- Input Jumlah -->
          <div>
            <label class="block font-semibold text-slate-300 mb-1">Jumlah Alat yang Digunakan ({{ selectedTool.unit }}):</label>
            <div class="flex items-center gap-2">
              <button
                type="button"
                @click="pickupQty = Math.max(1, pickupQty - 1)"
                class="w-10 h-10 rounded-lg bg-slate-800 text-slate-200 font-bold hover:bg-slate-700 text-base transition"
              >-</button>
              <input
                v-model.number="pickupQty"
                type="number"
                min="1"
                :max="Math.max(1, selectedTool.quantity)"
                class="flex-1 p-2 text-center font-bold text-base rounded-lg bg-slate-950 border border-slate-700 text-white focus:outline-none focus:border-teal-500"
                required
              />
              <button
                type="button"
                @click="pickupQty = Math.min(Math.max(1, selectedTool.quantity), pickupQty + 1)"
                class="w-10 h-10 rounded-lg bg-slate-800 text-slate-200 font-bold hover:bg-slate-700 text-base transition"
              >+</button>
            </div>
          </div>

          <!-- Quick Room / Location Presets untuk OB -->
          <div>
            <label class="block font-semibold text-slate-300 mb-1">Pilih Cepat Lokasi / Ruangan Pembersihan:</label>
            <div class="flex flex-wrap gap-1.5">
              <button
                v-for="chip in roomPresets"
                :key="chip"
                type="button"
                @click="pickupLocation = chip"
                class="px-2.5 py-1 rounded-lg text-[11px] border transition"
                :class="pickupLocation === chip ? 'bg-teal-500/20 text-teal-300 border-teal-500/40 font-semibold' : 'bg-slate-950 text-slate-400 border-slate-800 hover:text-slate-200'"
              >
                {{ chip }}
              </button>
            </div>
          </div>

          <!-- Input Lokasi / Ruangan Pembersihan -->
          <div>
            <label class="block font-semibold text-slate-300 mb-1">Lokasi Pembersihan:</label>
            <input
              v-model="pickupLocation"
              type="text"
              placeholder="Contoh: Ruang Rapat Aula Lt. 2 / Toilet Lt. 1"
              class="w-full p-2.5 rounded-lg bg-slate-950 border border-slate-700 text-white focus:outline-none focus:border-teal-500 text-xs"
              required
            />
          </div>

          <!-- Input Keterangan Tambahan -->
          <div>
            <label class="block font-semibold text-slate-300 mb-1">Keterangan Tugas:</label>
            <input
              v-model="pickupNotes"
              type="text"
              placeholder="Contoh: Pembersihan rutin & pel marmer sebelum rapat pimpinan"
              class="w-full p-2.5 rounded-lg bg-slate-950 border border-slate-700 text-white focus:outline-none focus:border-teal-500 text-xs"
            />
          </div>

          <!-- Tombol Aksi Submit -->
          <div class="pt-3 flex justify-end gap-2 border-t border-slate-800">
            <button @click="closePickupModal" type="button" class="px-4 py-2 rounded-lg bg-slate-800 text-slate-300 text-xs font-semibold hover:bg-slate-700 transition" :disabled="isSubmitting">
              Batal
            </button>
            <button
              type="submit"
              class="px-5 py-2 rounded-lg bg-gradient-to-r from-teal-500 to-emerald-600 hover:from-teal-600 text-white text-xs font-bold shadow-lg shadow-teal-500/25 transition flex items-center gap-2"
              :disabled="isSubmitting || pickupQty < 1"
            >
              <span v-if="isSubmitting" class="w-3.5 h-3.5 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
              <span>{{ isSubmitting ? 'Mencatat...' : 'Konfirmasi Ambil Alat' }}</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'

useHead({
  title: 'Portal Office Boy (OB) - Alat Kebersihan & Janitor'
})

const router = useRouter()
const config = useRuntimeConfig()
const apiBase = config.public?.apiBase || 'http://127.0.0.1:8000/api'

// User State
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

// Cleaning Tools State (Bukan ATK, tapi Alat Kebersihan)
const cleaningTools = ref([])

const pickups = ref([])
const searchQuery = ref('')
const selectedCategory = ref('Semua')
const alertMsg = ref(null)
const redirectMessage = ref('')
const isSubmitting = ref(false)
const manualBarcodeInput = ref('')

// Modal State
const selectedTool = ref(null)
const pickupQty = ref(1)
const pickupLocation = ref('')
const pickupNotes = ref('')

const roomPresets = [
  'Ruang Rapat Aula Lt. 2',
  'Lobby Utama & Resepsionis',
  'Toilet & Janitor Area Lt. 1',
  'Toilet & Janitor Area Lt. 2',
  'Pantry & Koridor Karyawan',
  'Ruang Pimpinan & Sekretariat',
  'Area Luar & Halaman Parkir'
]

// Camera Scanner State
const isCameraActive = ref(false)
const videoRef = ref(null)
let mediaStream = null
let animationFrameId = null
let barcodeDetector = null

const categories = ['Semua', 'Mesin Pembersih', 'Mesin Cuci Tekanan', 'Trolley & Keranjang', 'Peralatan Manual', 'Bahan Kimia & Sanitasi']

const filteredCleaningTools = computed(() => {
  const q = searchQuery.value.toLowerCase().trim()
  return cleaningTools.value.filter(t => {
    const matchesQuery = !q ||
      t.name.toLowerCase().includes(q) ||
      t.code.toLowerCase().includes(q) ||
      t.category.toLowerCase().includes(q) ||
      (t.location && t.location.toLowerCase().includes(q))

    const matchesCat = selectedCategory.value === 'Semua' || t.category === selectedCategory.value
    return matchesQuery && matchesCat
  })
})

const totalAvailableUnits = computed(() => {
  return cleaningTools.value.reduce((sum, t) => sum + Number(t.quantity || 0), 0)
})

const totalTakenToday = computed(() => {
  return pickups.value.reduce((sum, p) => sum + Number(p.quantity || 1), 0)
})

const inUseToolsCount = computed(() => {
  return cleaningTools.value.filter(t => t.status === 'Sedang Digunakan').length
})

const fetchCleaningTools = async () => {
  try {
    const res = await $fetch(`${apiBase}/cleaning-tools`)
    const loaded = res.data || res
    if (Array.isArray(loaded)) {
      cleaningTools.value = loaded.map(t => ({
        ...t,
        image: t.image || '/images/cleaning/default-cleaning.svg'
      }))
    } else {
      cleaningTools.value = []
    }
  } catch (err) {
    console.warn('Gagal memuat alat kebersihan dari API:', err)
    cleaningTools.value = []
  }
}

const fetchRecentPickups = async () => {
  const userId = currentUser.value?.id || 4
  try {
    const res = await $fetch(`${apiBase}/cleaning-requests?user_id=${userId}`)
    if (Array.isArray(res)) {
      if (res.length === 0 && typeof window !== 'undefined') {
        localStorage.removeItem('ob_cleaning_pickups')
        pickups.value = []
        return
      }
    }
  } catch (e) {}

  if (typeof window !== 'undefined') {
    const saved = localStorage.getItem('ob_cleaning_pickups')
    if (saved) {
      try {
        const parsed = JSON.parse(saved)
        if (Array.isArray(parsed)) pickups.value = parsed
      } catch (e) {}
    }
  }
}

const openPickupModal = (tool) => {
  stopCamera()
  selectedTool.value = tool
  pickupQty.value = 1
  pickupLocation.value = roomPresets[0]
  pickupNotes.value = `Pembersihan rutin area ${roomPresets[0]}`
}

const closePickupModal = () => {
  selectedTool.value = null
  pickupQty.value = 1
  pickupLocation.value = ''
  pickupNotes.value = ''
}

// Camera Scanner Handlers
const startCamera = async () => {
  isCameraActive.value = true
  alertMsg.value = null
  try {
    const stream = await navigator.mediaDevices.getUserMedia({
      video: { facingMode: 'environment', width: { ideal: 1280 } }
    })
    mediaStream = stream
    if (videoRef.value) {
      videoRef.value.srcObject = stream
      await videoRef.value.play()
    }

    if (typeof window !== 'undefined' && 'BarcodeDetector' in window) {
      try {
        barcodeDetector = new window.BarcodeDetector({
          formats: ['qr_code', 'code_128', 'ean_13', 'code_39', 'upc_a']
        })
        startBarcodeLoop()
      } catch (e) {
        console.warn('BarcodeDetector error:', e)
      }
    }
  } catch (err) {
    alertMsg.value = {
      type: 'error',
      text: 'Tidak dapat mengakses kamera. Silakan pilih langsung alat kebersihan pada tabel atau gunakan tombol barcode.'
    }
    isCameraActive.value = false
  }
}

const stopCamera = () => {
  if (animationFrameId) {
    cancelAnimationFrame(animationFrameId)
    animationFrameId = null
  }
  if (mediaStream) {
    mediaStream.getTracks().forEach(t => t.stop())
    mediaStream = null
  }
  isCameraActive.value = false
}

const startBarcodeLoop = () => {
  const detectFrame = async () => {
    if (!isCameraActive.value || !videoRef.value || !barcodeDetector) return
    try {
      if (videoRef.value.readyState === videoRef.value.HAVE_ENOUGH_DATA) {
        const barcodes = await barcodeDetector.detect(videoRef.value)
        if (barcodes.length > 0) {
          handleBarcodeScanned(barcodes[0].rawValue)
          return
        }
      }
    } catch (e) {}
    animationFrameId = requestAnimationFrame(detectFrame)
  }
  animationFrameId = requestAnimationFrame(detectFrame)
}

const playBeep = () => {
  try {
    const AudioCtx = window.AudioContext || window.webkitAudioContext
    if (!AudioCtx) return
    const ctx = new AudioCtx()
    const osc = ctx.createOscillator()
    const gain = ctx.createGain()
    osc.frequency.setValueAtTime(950, ctx.currentTime)
    gain.gain.setValueAtTime(0.2, ctx.currentTime)
    osc.connect(gain)
    gain.connect(ctx.destination)
    osc.start()
    osc.stop(ctx.currentTime + 0.15)
  } catch (e) {}
}

const handleBarcodeScanned = (code) => {
  playBeep()
  stopCamera()
  const clean = code.trim().toLowerCase()
  const matched = cleaningTools.value.find(t =>
    t.code.toLowerCase() === clean ||
    t.name.toLowerCase().includes(clean)
  )

  if (matched) {
    openPickupModal(matched)
    alertMsg.value = { type: 'success', text: `Barcode alat terdeteksi: ${matched.name} (${matched.code})` }
  } else {
    alertMsg.value = { type: 'error', text: `Barcode alat "${code}" tidak ditemukan pada daftar alat kebersihan.` }
  }
}

const handleManualBarcodeSubmit = () => {
  if (!manualBarcodeInput.value.trim()) return
  handleBarcodeScanned(manualBarcodeInput.value)
  manualBarcodeInput.value = ''
}

// Submit Pickup to Backend & Redirect to Riwayat (PERSIS SEPERTI PADA ATK)
const submitPickup = async () => {
  if (!selectedTool.value) return
  isSubmitting.value = true
  alertMsg.value = null
  redirectMessage.value = ''

  const headers = {}
  if (tokenCookie.value) {
    headers['Authorization'] = `Bearer ${tokenCookie.value}`
  }

  const userId = currentUser.value.id || 4

  const payload = {
    user_id: userId,
    cleaning_tool_id: selectedTool.value.id,
    location: pickupLocation.value || roomPresets[0],
    service_type: 'Pembersihan & Sanitasi Gedung',
    notes: pickupNotes.value || `Pengambilan alat ${selectedTool.value.name}`,
    quantity: pickupQty.value
  }

  const newEntry = {
    id: Date.now(),
    tool_name: selectedTool.value.name,
    code: selectedTool.value.code,
    category: selectedTool.value.category,
    quantity: pickupQty.value,
    unit: selectedTool.value.unit || 'Unit',
    location: pickupLocation.value || roomPresets[0],
    note: pickupNotes.value || 'Pembersihan Ruangan',
    time: new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) + ' WIB',
    date: new Date().toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' })
  }

  try {
    await $fetch(`${apiBase}/cleaning-requests`, {
      method: 'POST',
      headers,
      body: payload
    })
  } catch (err) {
    try {
      await $fetch(`${apiBase}/v1/manage/cleaning-requests`, {
        method: 'POST',
        headers,
        body: payload
      })
    } catch (innerErr) {
      console.warn('Backend sync failed, storing locally:', innerErr)
    }
  }

  // 1. Kurangi stok lokal alat
  if (selectedTool.value.quantity >= pickupQty.value) {
    selectedTool.value.quantity -= pickupQty.value
  }
  if (selectedTool.value.quantity === 0) {
    selectedTool.value.status = 'Sedang Digunakan'
  }

  // 2. Simpan ke riwayat lokal OB
  pickups.value.unshift(newEntry)
  if (typeof window !== 'undefined') {
    localStorage.setItem('ob_cleaning_pickups', JSON.stringify(pickups.value.slice(0, 50)))
  }

  // 3. Tampilkan Banner Konfirmasi & Otomatis Alihkan ke Riwayat (PERSIS ALUR ATK)
  redirectMessage.value = `Pengambilan alat "${selectedTool.value.name}" (${pickupQty.value} ${selectedTool.value.unit}) berhasil dicatat! Mengalihkan ke Halaman Riwayat...`

  setTimeout(() => {
    selectedTool.value = null
    isSubmitting.value = false
    router.push('/ob/riwayat')
  }, 800)
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

  fetchCleaningTools()
  fetchRecentPickups()
})

onUnmounted(() => {
  stopCamera()
})
</script>
