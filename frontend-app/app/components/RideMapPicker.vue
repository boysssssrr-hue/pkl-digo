<template>
  <div class="ride-map-picker rounded-2xl border border-emerald-500/30 bg-slate-900/95 shadow-2xl p-3 sm:p-5 md:p-6 text-slate-100 space-y-3.5 sm:space-y-4 relative">
    <!-- Header Rute & GPS Real-Time -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-800">
      <div class="flex items-center gap-2.5">
        <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-gradient-to-tr from-emerald-500 to-teal-400 flex items-center justify-center text-white text-base sm:text-lg shadow-lg shadow-emerald-500/20 shrink-0">
          🗺️
        </div>
        <div>
          <div class="flex items-center gap-2">
            <h4 class="font-bold text-sm md:text-base text-white">Peta Rute & Titik Kunjungan Dinas</h4>
            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
              PENCARIAN SPESIFIK & AUTOCOMPLETE
            </span>
          </div>
          <p class="text-[11px] text-slate-400">
            Penjemputan awal diatur otomatis di <strong>BPJS Kesehatan Cabang Jakarta Utara</strong>. Ketik nama instansi/faskes untuk rekomendasi akurat.
          </p>
        </div>
      </div>

      <!-- Quick Action: Reset ke BPJS Jakut & Deteksi GPS -->
      <div class="flex items-center gap-2 self-start sm:self-auto flex-wrap">
        <button
          type="button"
          @click="resetToBpjsJakartaUtara"
          class="px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 text-xs font-semibold flex items-center gap-1.5 transition active:scale-95 cursor-pointer shadow-sm"
          title="Kembalikan titik jemput ke Kantor Cabang BPJS Kesehatan Jakarta Utara"
        >
          <span>🏢</span>
          <span>Titik Awal BPJS Jakut</span>
        </button>

        <button
          type="button"
          @click="getCurrentLocation(true)"
          :disabled="locating"
          class="px-3 py-1.5 rounded-xl bg-emerald-500/15 hover:bg-emerald-500/25 text-emerald-300 border border-emerald-500/40 text-xs font-bold flex items-center gap-1.5 transition active:scale-95 cursor-pointer disabled:opacity-50 shadow-sm"
        >
          <span>{{ locating ? '⏳' : '🎯' }}</span>
          <span>{{ locating ? 'Mendeteksi...' : 'GPS Saya' }}</span>
        </button>
      </div>
    </div>

    <!-- Pilihan Titik Aktif (Jemput & Multi Tujuan) -->
    <div class="space-y-3">
      <!-- 1. TITIK JEMPUT (PICKUP) -->
      <div
        @click="activeTarget = 'pickup'"
        class="p-3.5 rounded-xl border transition-all relative location-search-wrapper"
        :class="activeTarget === 'pickup' ? 'bg-emerald-950/40 border-emerald-400 shadow-md shadow-emerald-500/10 ring-1 ring-emerald-400' : 'bg-slate-950/60 border-slate-800 hover:border-slate-700'"
      >
        <div class="flex items-center justify-between mb-1.5">
          <div class="flex items-center gap-2">
            <span class="w-3 h-3 rounded-full bg-emerald-500 ring-4 ring-emerald-500/20"></span>
            <span class="text-xs font-bold text-emerald-400 uppercase tracking-wide">Titik Penjemputan Awal</span>
            <span class="text-[10px] font-semibold text-emerald-300 bg-emerald-500/10 px-2 py-0.5 rounded border border-emerald-500/20">
              🏢 Kantor Cabang Utama
            </span>
          </div>

          <div class="flex items-center gap-2">
            <span v-if="pickup.coords" class="text-[10px] text-emerald-400 font-mono font-semibold flex items-center gap-1">
              <span>✓</span>
              <span>Terpasang</span>
            </span>
            <span v-if="activeTarget === 'pickup'" class="text-[10px] font-bold px-2 py-0.5 rounded bg-emerald-500 text-white animate-pulse">
              Aktif
            </span>
          </div>
        </div>

        <!-- Input & Search Autocomplete Container -->
        <div class="relative" @click.stop>
          <div class="flex gap-2 items-center">
            <div class="relative flex-1">
              <input
                v-model="pickup.name"
                @focus="onInputFocus('pickup')"
                @input="onInputQuery(pickup.name, 'pickup')"
                @keydown.enter.prevent="handleEnterKey(pickup.name, 'pickup')"
                @keydown.esc="closeDropdown"
                type="text"
                placeholder="Ketik lokasi jemput (contoh: BPJS Kesehatan Cabang Jakarta Utara)..."
                class="w-full bg-slate-900 border border-slate-700 rounded-lg pl-3 pr-8 py-2 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500"
              />
              <!-- Indicator loading / Clear -->
              <div class="absolute right-2.5 top-1/2 -translate-y-1/2 flex items-center">
                <span v-if="searchLoading && activeDropdown === 'pickup'" class="animate-spin text-xs text-emerald-400">
                  ⌛
                </span>
                <button
                  v-else-if="pickup.name"
                  type="button"
                  @click="clearInput('pickup')"
                  class="text-slate-500 hover:text-slate-300 text-xs px-1"
                  title="Hapus teks"
                >
                  ✕
                </button>
              </div>
            </div>

            <button
              type="button"
              @click.stop="triggerSearch(pickup.name, 'pickup')"
              class="px-3 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-semibold shrink-0 transition flex items-center gap-1.5 shadow-sm cursor-pointer"
              title="Cari Rekomendasi Lokasi"
            >
              <span>🔍</span>
              <span class="hidden sm:inline">Cari</span>
            </button>
          </div>

          <!-- Alamat Lengkap & Quick Button Reset to BPJS Jakut -->
          <div class="flex items-center justify-between mt-1 text-[11px] text-slate-400">
            <span class="truncate">
              📍 <span class="text-slate-300 font-medium">Jl. Raya Plumpang Semper No. 6, Koja, Jakarta Utara</span>
            </span>
            <button
              v-if="pickup.name !== DEFAULT_PICKUP.name"
              type="button"
              @click="resetToBpjsJakartaUtara"
              class="text-emerald-400 hover:text-emerald-300 font-semibold underline text-[10px] shrink-0 ml-2"
            >
              Set ke BPJS Jakut
            </button>
          </div>

          <!-- REKOMENDASI LOKASI SPESIFIK (DROPDOWN AUTOCOMPLETE) -->
          <div
            v-if="activeDropdown === 'pickup'"
            class="absolute top-full left-0 right-0 mt-1.5 z-50 bg-slate-900 border border-emerald-500/40 rounded-xl shadow-2xl overflow-hidden backdrop-blur-xl animate-fade-in"
          >
            <!-- Loading State -->
            <div v-if="searchLoading" class="p-3.5 flex items-center justify-center gap-2.5 text-xs text-slate-300">
              <span class="animate-spin text-base">⌛</span>
              <span>Mencari rekomendasi lokasi spesifik...</span>
            </div>

            <!-- List Suggestions -->
            <div v-else-if="suggestions.length > 0" class="max-h-72 overflow-y-auto divide-y divide-slate-800/80">
              <div class="px-3 py-2 bg-slate-950 flex items-center justify-between text-[11px] text-slate-400 font-semibold border-b border-slate-800">
                <span class="flex items-center gap-1.5 text-emerald-400">
                  <span>📍</span>
                  <span>Pilih Lokasi Spesifik ({{ suggestions.length }} Ditemukan)</span>
                </span>
                <button type="button" @click.stop="closeDropdown" class="hover:text-white transition cursor-pointer">
                  ✕ Tutup
                </button>
              </div>

              <button
                v-for="(item, sIdx) in suggestions"
                :key="item.id || sIdx"
                type="button"
                @click.stop="selectSuggestion(item, 'pickup')"
                class="w-full text-left p-3 hover:bg-emerald-950/40 flex items-start gap-3 transition group cursor-pointer border-b border-slate-800/40 last:border-b-0"
              >
                <div class="w-8 h-8 rounded-lg bg-emerald-500/15 border border-emerald-500/30 flex items-center justify-center text-sm shrink-0 mt-0.5 group-hover:scale-110 group-hover:bg-emerald-500/25 transition">
                  {{ item.icon }}
                </div>
                <div class="flex-1 min-w-0">
                  <div class="font-bold text-xs text-white group-hover:text-emerald-300 transition flex items-center justify-between gap-2">
                    <span class="truncate">{{ item.name }}</span>
                    <span class="text-[10px] font-semibold text-emerald-400 shrink-0 opacity-0 group-hover:opacity-100 transition">
                      Pilih ➔
                    </span>
                  </div>
                  <p class="text-[11px] text-slate-400 line-clamp-1 mt-0.5 leading-snug group-hover:text-slate-300 transition">
                    {{ item.subtitle }}
                  </p>
                  <span v-if="item.distanceFromBase" class="inline-block mt-1 text-[10px] font-mono text-cyan-400 bg-cyan-950/60 px-1.5 py-0.2 rounded border border-cyan-500/30">
                    📏 ~{{ item.distanceFromBase }} km dari BPJS Jakut
                  </span>
                </div>
              </button>
            </div>

            <!-- Empty / Not Found State -->
            <div v-else-if="searchFailed" class="p-4 text-center space-y-2">
              <div class="text-xl">🔍</div>
              <div class="text-xs font-semibold text-slate-300">
                Lokasi "<span class="text-emerald-400">{{ lastSearchedQuery }}</span>" tidak ditemukan
              </div>
              <p class="text-[11px] text-slate-400 max-w-xs mx-auto">
                Coba ketik nama gedung, instansi, atau nama jalan lebih umum, atau klik langsung pada peta di bawah.
              </p>
              <div class="pt-1 flex items-center justify-center gap-2">
                <button
                  type="button"
                  @click.stop="closeDropdown"
                  class="px-3 py-1 rounded-lg bg-slate-800 hover:bg-slate-700 text-xs text-slate-200 transition cursor-pointer"
                >
                  Tutup
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- 2. DAFTAR LOKASI TUJUAN (BISA > 1) -->
      <div class="space-y-2.5">
        <div class="flex items-center justify-between pt-1">
          <span class="text-xs font-bold text-slate-300 flex items-center gap-1.5">
            <span>📍</span>
            <span>Rute Tujuan (Dapat lebih dari 1 lokasi)</span>
          </span>
          <span class="text-[11px] text-slate-400">
            {{ destinations.length }} Titik Tujuan
          </span>
        </div>

        <div
          v-for="(dest, index) in destinations"
          :key="dest.id"
          @click="activeTarget = `dest-${index}`"
          class="p-3.5 rounded-xl border transition-all relative location-search-wrapper"
          :class="activeTarget === `dest-${index}` ? 'bg-rose-950/40 border-rose-400 shadow-md shadow-rose-500/10 ring-1 ring-rose-400' : 'bg-slate-950/60 border-slate-800 hover:border-slate-700'"
        >
          <div class="flex items-center justify-between mb-1.5">
            <div class="flex items-center gap-2">
              <span class="w-5 h-5 rounded-full bg-rose-500 text-white text-[10px] font-bold flex items-center justify-center shrink-0">
                #{{ index + 1 }}
              </span>
              <span class="text-xs font-bold text-rose-300">
                {{ index === 0 ? 'Tujuan Pertama' : `Tujuan Ke-${index + 1}` }}
              </span>
            </div>

            <div class="flex items-center gap-2">
              <span v-if="dest.coords" class="text-[10px] text-rose-400 font-mono font-semibold flex items-center gap-1">
                <span>✓</span>
                <span>Terpasang</span>
              </span>
              <span v-if="activeTarget === `dest-${index}`" class="text-[10px] font-bold px-2 py-0.5 rounded bg-rose-500 text-white animate-pulse">
                Aktif
              </span>
              <!-- Tombol Hapus jika > 1 tujuan -->
              <button
                v-if="destinations.length > 1"
                type="button"
                @click.stop="removeDestination(index)"
                class="w-6 h-6 rounded-lg bg-rose-500/20 hover:bg-rose-500/30 text-rose-300 flex items-center justify-center text-xs transition cursor-pointer"
                title="Hapus lokasi ini"
              >
                ✕
              </button>
            </div>
          </div>

          <!-- Input & Search Autocomplete Container for Destination -->
          <div class="relative" @click.stop>
            <div class="flex gap-2 items-center">
              <div class="relative flex-1">
                <input
                  v-model="dest.name"
                  @focus="onInputFocus(`dest-${index}`)"
                  @input="onInputQuery(dest.name, `dest-${index}`)"
                  @blur="onInputBlur(`dest-${index}`)"
                  @keydown.enter.prevent="handleEnterKey(dest.name, `dest-${index}`)"
                  @keydown.esc="closeDropdown"
                  type="text"
                  :placeholder="index === 0 ? 'Ketik tujuan (contoh: RSUD Koja, Walikota Jakut, Dinkes DKI)...' : `Ketik lokasi tujuan ke-${index + 1}...`"
                  class="w-full bg-slate-900 border border-slate-700 rounded-lg pl-3 pr-8 py-2 text-xs text-white placeholder-slate-500 focus:outline-none focus:border-rose-500"
                />
                <!-- Indicator loading / Clear -->
                <div class="absolute right-2.5 top-1/2 -translate-y-1/2 flex items-center">
                  <span v-if="searchLoading && activeDropdown === `dest-${index}`" class="animate-spin text-xs text-rose-400">
                    ⌛
                  </span>
                  <button
                    v-else-if="dest.name"
                    type="button"
                    @click="clearInput(`dest-${index}`)"
                    class="text-slate-500 hover:text-slate-300 text-xs px-1"
                    title="Hapus teks"
                  >
                    ✕
                  </button>
                </div>
              </div>

              <button
                type="button"
                @click.stop="triggerSearch(dest.name, `dest-${index}`)"
                class="px-3 py-2 rounded-lg bg-rose-600 hover:bg-rose-500 text-white text-xs font-semibold shrink-0 transition flex items-center gap-1.5 shadow-sm cursor-pointer"
                title="Cari Rekomendasi Lokasi"
              >
                <span>🔍</span>
                <span class="hidden sm:inline">Cari</span>
              </button>
            </div>

            <!-- REKOMENDASI LOKASI YANG SERING DIKUNJUNGI (OTOMATIS PER USER) -->
            <div v-if="userFrequentDestinations.length > 0" class="mt-2 pt-2 border-t border-slate-800/80 animate-fade-in">
              <div class="flex items-center justify-between mb-1.5">
                <span class="text-[9.5px] sm:text-[10px] text-amber-300 font-bold flex items-center gap-1.5">
                  <span class="text-xs">⭐</span>
                  <span>Sering Anda Kunjungi (Rekomendasi Personal):</span>
                </span>
                <span class="text-[8.5px] sm:text-[9px] text-amber-400 font-mono font-bold bg-amber-500/10 px-2 py-0.5 rounded-full border border-amber-500/25">
                  {{ userFrequentDestinations.length }} Lokasi Favorit
                </span>
              </div>
              <div class="flex items-center gap-1.5 overflow-x-auto pb-1 sm:flex-wrap no-scrollbar">
                <button
                  type="button"
                  v-for="chip in userFrequentDestinations"
                  :key="chip.name"
                  @click.stop="selectPresetDestination(chip, index)"
                  class="px-2 py-1 rounded-lg bg-amber-500/15 hover:bg-amber-500/25 hover:border-amber-400 border border-amber-500/40 text-[10px] text-amber-200 hover:text-white transition flex items-center gap-1.5 shrink-0 cursor-pointer shadow-sm active:scale-95 group"
                  :title="`${chip.name} - Dikunjungi ${chip.count}x (${chip.subtitle || 'Tujuan Kedinasan'})`"
                >
                  <span class="text-xs group-hover:scale-110 transition">{{ chip.icon || '📍' }}</span>
                  <span class="font-bold whitespace-nowrap">{{ chip.name }}</span>
                  <span class="px-1.5 py-0.2 rounded-full bg-amber-500/30 text-amber-300 text-[8.5px] sm:text-[9px] font-black border border-amber-500/40">
                    {{ chip.count }}x
                  </span>
                </button>
              </div>
            </div>

            <!-- CHIP PILIHAN CEPAT INSTANSI & RUMAH SAKIT POPULER (JAKARTA) -->
            <div class="mt-2 pt-2 border-t border-slate-800/80">
              <span class="text-[9.5px] sm:text-[10px] text-slate-400 block mb-1.5 font-semibold">
                ⚡ Rekomendasi Instansi / Faskes Terdekat dari BPJS Jakut:
              </span>
              <div class="flex items-center gap-1.5 overflow-x-auto pb-1 sm:flex-wrap no-scrollbar">
                <button
                  type="button"
                  v-for="chip in popularJakartaDestinations"
                  :key="chip.name"
                  @click.stop="selectPresetDestination(chip, index)"
                  class="px-2 py-1 rounded-md bg-slate-800/90 hover:bg-rose-950/50 hover:border-rose-500/50 border border-slate-700 text-[10px] text-slate-300 hover:text-rose-200 transition flex items-center gap-1 shrink-0 cursor-pointer"
                  :title="chip.subtitle"
                >
                  <span>{{ chip.icon }}</span>
                  <span class="font-medium whitespace-nowrap">{{ chip.name }}</span>
                  <span class="text-[9px] text-slate-400 font-mono">({{ chip.distance }})</span>
                </button>
              </div>
            </div>

            <!-- REKOMENDASI LOKASI SPESIFIK (DROPDOWN AUTOCOMPLETE) FOR DESTINATION -->
            <div
              v-if="activeDropdown === `dest-${index}`"
              class="absolute top-full left-0 right-0 mt-1.5 z-50 bg-slate-900 border border-rose-500/40 rounded-xl shadow-2xl overflow-hidden backdrop-blur-xl animate-fade-in"
            >
              <!-- Loading State -->
              <div v-if="searchLoading" class="p-3.5 flex items-center justify-center gap-2.5 text-xs text-slate-300">
                <span class="animate-spin text-base">⌛</span>
                <span>Mencari rekomendasi lokasi spesifik di Jakarta & sekitarnya...</span>
              </div>

              <!-- List Suggestions -->
              <div v-else-if="suggestions.length > 0" class="max-h-72 overflow-y-auto divide-y divide-slate-800/80">
                <div class="px-3 py-2 bg-slate-950 flex items-center justify-between text-[11px] text-slate-400 font-semibold border-b border-slate-800">
                  <span class="flex items-center gap-1.5 text-rose-400">
                    <span>📍</span>
                    <span>Pilih Lokasi Spesifik ({{ suggestions.length }} Ditemukan)</span>
                  </span>
                  <button type="button" @click.stop="closeDropdown" class="hover:text-white transition cursor-pointer">
                    ✕ Tutup
                  </button>
                </div>

                <button
                  v-for="(item, sIdx) in suggestions"
                  :key="item.id || sIdx"
                  type="button"
                  @click.stop="selectSuggestion(item, `dest-${index}`)"
                  class="w-full text-left p-3 hover:bg-rose-950/40 flex items-start gap-3 transition group cursor-pointer border-b border-slate-800/40 last:border-b-0"
                >
                  <div class="w-8 h-8 rounded-lg bg-rose-500/15 border border-rose-500/30 flex items-center justify-center text-sm shrink-0 mt-0.5 group-hover:scale-110 group-hover:bg-rose-500/25 transition">
                    {{ item.icon }}
                  </div>
                  <div class="flex-1 min-w-0">
                    <div class="font-bold text-xs text-white group-hover:text-rose-300 transition flex items-center justify-between gap-2">
                      <span class="truncate">{{ item.name }}</span>
                      <span class="text-[10px] font-semibold text-rose-400 shrink-0 opacity-0 group-hover:opacity-100 transition">
                        Pilih ➔
                      </span>
                    </div>
                    <p class="text-[11px] text-slate-400 line-clamp-1 mt-0.5 leading-snug group-hover:text-slate-300 transition">
                      {{ item.subtitle }}
                    </p>
                    <span v-if="item.distanceFromBase" class="inline-block mt-1 text-[10px] font-mono text-cyan-400 bg-cyan-950/60 px-1.5 py-0.2 rounded border border-cyan-500/30">
                      📏 ~{{ item.distanceFromBase }} km dari BPJS Jakut
                    </span>
                  </div>
                </button>
              </div>

              <!-- Empty / Not Found State -->
              <div v-else-if="searchFailed" class="p-4 text-center space-y-2">
                <div class="text-xl">🔍</div>
                <div class="text-xs font-semibold text-slate-300">
                  Lokasi "<span class="text-rose-400">{{ lastSearchedQuery }}</span>" tidak ditemukan
                </div>
                <p class="text-[11px] text-slate-400 max-w-xs mx-auto">
                  Coba ketik nama gedung, instansi, atau nama jalan lebih spesifik, atau klik salah satu rekomendasi cepat di atas.
                </p>
                <div class="pt-1 flex items-center justify-center gap-2">
                  <button
                    type="button"
                    @click.stop="closeDropdown"
                    class="px-3 py-1 rounded-lg bg-slate-800 hover:bg-slate-700 text-xs text-slate-200 transition cursor-pointer"
                  >
                    Tutup
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Tombol Tambah Lokasi Tujuan Berikutnya (+) -->
        <button
          type="button"
          @click="addDestination"
          class="w-full py-2.5 px-3 rounded-xl border border-dashed border-slate-700 hover:border-emerald-500/60 bg-slate-950/40 hover:bg-slate-900 text-slate-300 hover:text-emerald-400 text-xs font-bold flex items-center justify-center gap-2 transition cursor-pointer"
        >
          <span class="text-base font-bold">+</span>
          <span>Tambah Lokasi Tujuan Berikutnya (Multi-Stop)</span>
        </button>
      </div>
    </div>

    <!-- Container Peta Leaflet (Adaptif: h-48 di HP, h-64 di Tablet, h-80/h-96 di Laptop) -->
    <div class="relative w-full h-48 sm:h-64 md:h-80 lg:h-96 rounded-xl overflow-hidden border border-slate-800 shadow-inner bg-slate-950">
      <div ref="mapContainer" class="w-full h-full z-10"></div>

      <!-- Info Mode Klik yang sedang aktif -->
      <div class="absolute top-2 left-2 z-20 bg-slate-900/90 backdrop-blur-md px-2 py-0.5 sm:px-2.5 sm:py-1 rounded-lg border border-slate-800 text-[9.5px] sm:text-[11px] text-slate-200 shadow-lg flex items-center gap-1.5">
        <span class="w-2 h-2 rounded-full animate-ping" :class="activeTarget === 'pickup' ? 'bg-emerald-400' : 'bg-rose-400'"></span>
        <span>
          Target Klik:
          <strong :class="activeTarget === 'pickup' ? 'text-emerald-400' : 'text-rose-400'">
            {{ activeTarget === 'pickup' ? 'Penjemputan 🟢' : getTargetLabel(activeTarget) }}
          </strong>
        </span>
      </div>

      <!-- Loading Rute -->
      <div v-if="calculatingRoute" class="absolute inset-0 z-30 bg-slate-950/60 backdrop-blur-sm flex items-center justify-center gap-2 text-xs font-semibold text-white">
        <span class="animate-spin text-lg">⏳</span> Menghubungkan rute...
      </div>
    </div>

    <!-- Ringkasan Rute, Jarak, & Tombol Google Maps Driver -->
    <div v-if="routeInfo" class="p-3 sm:p-4 rounded-xl bg-gradient-to-r from-emerald-950/50 via-slate-900 to-cyan-950/50 border border-emerald-500/30 flex flex-col md:flex-row md:items-center justify-between gap-3 sm:gap-4 animate-fade-in">
      <div class="grid grid-cols-3 divide-x divide-slate-800 text-center sm:flex sm:items-center sm:text-left sm:divide-x-0 gap-2 sm:gap-5 w-full md:w-auto">
        <div class="pr-1 sm:pr-0">
          <span class="text-[8.5px] sm:text-[10px] text-slate-400 uppercase tracking-wider block font-bold">Total Jarak</span>
          <span class="text-sm sm:text-xl font-black text-emerald-300 font-mono">{{ routeInfo.distanceKm }} KM</span>
        </div>
        <div class="hidden sm:block w-px h-7 sm:h-9 bg-slate-800"></div>
        <div class="px-1 sm:px-0">
          <span class="text-[8.5px] sm:text-[10px] text-slate-400 uppercase tracking-wider block font-bold">Estimasi</span>
          <span class="text-sm sm:text-xl font-black text-cyan-300 font-mono">~{{ routeInfo.durationMinutes }} Mnt</span>
        </div>
        <div class="hidden sm:block w-px h-7 sm:h-9 bg-slate-800"></div>
        <div class="pl-1 sm:pl-0">
          <span class="text-[8.5px] sm:text-[10px] text-slate-400 uppercase tracking-wider block font-bold">Singgah</span>
          <span class="text-xs sm:text-sm font-bold text-amber-300">{{ destinations.length }} Titik</span>
        </div>
      </div>

      <!-- Navigasi Google Maps untuk Driver / User -->
      <a
        v-if="googleMapsUrl"
        :href="googleMapsUrl"
        target="_blank"
        rel="noopener noreferrer"
        class="w-full md:w-auto px-3 sm:px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-[11px] sm:text-xs font-bold border border-blue-400/30 flex items-center justify-center gap-1.5 transition active:scale-95 shadow-md shadow-blue-600/25"
      >
        <span>🧭 Buka di Google Maps</span>
        <span>↗</span>
      </a>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount, computed } from 'vue';

// KOORDINAT RESMI & DEFAULT PENJEMPUTAN: BPJS KESEHATAN CABANG JAKARTA UTARA
const DEFAULT_PICKUP = {
  name: 'BPJS Kesehatan Cabang Jakarta Utara',
  subtitle: 'Jl. Raya Plumpang Semper No. 6, Tugu Selatan, Kec. Koja, Jakarta Utara, DKI Jakarta 14260',
  coords: {
    lat: -6.1297591,
    lng: 106.9073865
  }
};

// PRESET POPULER INSTANSI & FASKES JAKARTA UTARA / DKI (PILIHAN 1-KLIK)
const popularJakartaDestinations = [
  {
    name: 'RSUD Koja',
    subtitle: 'Jl. Deli No. 4, Koja, Jakarta Utara',
    coords: { lat: -6.1089322, lng: 106.8996682 },
    icon: '🏥',
    distance: '~2.5 km'
  },
  {
    name: 'Kantor Walikota Jakarta Utara',
    subtitle: 'Jl. Laksamana Yos Sudarso No. 27-29, Jakarta Utara',
    coords: { lat: -6.1201816, lng: 106.8918439 },
    icon: '🏛️',
    distance: '~2.7 km'
  },
  {
    name: 'RSUD Tanjung Priok',
    subtitle: 'Jl. Bugis No. 1, Tanjung Priok, Jakarta Utara',
    coords: { lat: -6.115837, lng: 106.88933 },
    icon: '🏥',
    distance: '~2.8 km'
  },
  {
    name: 'RSUD Cilincing',
    subtitle: 'Jl. Madya Kebantenan No. 4, Cilincing, Jakarta Utara',
    coords: { lat: -6.112966, lng: 106.92607 },
    icon: '🏥',
    distance: '~3.2 km'
  },
  {
    name: 'Dinas Kesehatan Prov. DKI Jakarta',
    subtitle: 'Jl. Kesehatan No. 10, Petojo Selatan, Gambir, Jakarta Pusat',
    coords: { lat: -6.1547607, lng: 106.8368718 },
    icon: '🏛️',
    distance: '~10.5 km'
  },
  {
    name: 'BPJS Kesehatan Kantor Pusat',
    subtitle: 'Jl. Letjen Suprapto Kav. 20 No. 14, Cempaka Putih, Jakarta Pusat',
    coords: { lat: -6.1764, lng: 106.8718 },
    icon: '🏢',
    distance: '~7.8 km'
  },
  {
    name: 'Balai Kota DKI Jakarta',
    subtitle: 'Jl. Medan Merdeka Selatan No. 8-9, Gambir, Jakarta Pusat',
    coords: { lat: -6.1812627, lng: 106.8286123 },
    icon: '🏛️',
    distance: '~11.2 km'
  }
];

const props = defineProps({
  userId: {
    type: [Number, String],
    default: null
  },
  userName: {
    type: String,
    default: ''
  },
  initialPickup: {
    type: String,
    default: 'BPJS Kesehatan Cabang Jakarta Utara'
  },
  initialDestinations: {
    type: Array,
    default: () => ['']
  }
});

const config = useRuntimeConfig();
const apiBase = config.public?.apiBase || 'http://127.0.0.1:8000/api';

// State Rekomendasi Lokasi Sering Dikunjungi per User
const userFrequentDestinations = ref([]);
const loadingFrequent = ref(false);

const emit = defineEmits(['update:route']);

const mapContainer = ref(null);
let map = null;
let pickupMarker = null;
let destMarkers = [];
let routeLine = null;

// Target aktif untuk klik peta: 'dest-0' secara default karena pickup sudah otomatis terpasang
const activeTarget = ref('dest-0');
const locating = ref(false);
const calculatingRoute = ref(false);

// State Titik Jemput (Selalu Default ke BPJS Kesehatan Cabang Jakarta Utara)
const pickup = ref({
  name: props.initialPickup || DEFAULT_PICKUP.name,
  coords: { ...DEFAULT_PICKUP.coords }
});

// State Multi-Tujuan
const destinations = ref([
  { id: 1, name: (props.initialDestinations && props.initialDestinations[0]) || '', coords: null }
]);

const routeInfo = ref(null);

// Autocomplete State
const activeDropdown = ref(null); // 'pickup' | 'dest-0' | 'dest-1' | null
const searchLoading = ref(false);
const suggestions = ref([]);
const searchFailed = ref(false);
const lastSearchedQuery = ref('');
let debounceTimer = null;

// Hitung jarak garis lurus dari titik penjemputan (KM)
const getDistanceToPickup = (lat, lng) => {
  const baseLat = pickup.value.coords?.lat || DEFAULT_PICKUP.coords.lat;
  const baseLng = pickup.value.coords?.lng || DEFAULT_PICKUP.coords.lng;
  const R = 6371;
  const dLat = (lat - baseLat) * (Math.PI / 180);
  const dLon = (lng - baseLng) * (Math.PI / 180);
  const a =
    Math.sin(dLat / 2) * Math.sin(dLat / 2) +
    Math.cos(baseLat * (Math.PI / 180)) * Math.cos(lat * (Math.PI / 180)) *
    Math.sin(dLon / 2) * Math.sin(dLon / 2);
  const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
  return (R * c).toFixed(1);
};

// Helper format subtitle dari OpenStreetMap / Photon
const formatPhotonSubtitle = (p) => {
  const parts = [
    p.street || p.housenumber ? `${p.street || ''} ${p.housenumber || ''}`.trim() : null,
    p.district || p.suburb,
    p.city,
    p.state
  ].filter(Boolean);
  return parts.length > 0 ? parts.join(', ') : 'Jakarta & Sekitarnya';
};

const formatNominatimSubtitle = (item) => {
  if (item.address) {
    const road = item.address.road || item.address.street;
    const area = item.address.suburb || item.address.village || item.address.neighbourhood;
    const city = item.address.city || item.address.town || item.address.county;
    const state = item.address.state;
    const parts = [road, area, city, state].filter(Boolean);
    if (parts.length > 0) return parts.join(', ');
  }
  const parts = item.display_name.split(',');
  return parts.slice(1, 4).map(s => s.trim()).filter(Boolean).join(', ');
};

// Helper icon kategori tempat
const getLocationIcon = (name = '', type = '') => {
  const n = name.toLowerCase();
  const t = type.toLowerCase();

  if (t.includes('hospital') || t.includes('clinic') || n.includes('rsud') || n.includes('rumah sakit') || n.includes('puskesmas') || n.includes('klinik') || n.includes('kesehatan') || n.includes('dokter')) return '🏥';
  if (t.includes('government') || t.includes('townhall') || n.includes('dinas') || n.includes('kantor') || n.includes('walikota') || n.includes('bupati') || n.includes('gubernur') || n.includes('kementerian') || n.includes('balai kota') || n.includes('bpjs')) return '🏛️';
  if (t.includes('school') || t.includes('university') || t.includes('college') || n.includes('kampus') || n.includes('universitas') || n.includes('sekolah') || n.includes('politeknik')) return '🎓';
  if (t.includes('mosque') || t.includes('place_of_worship') || n.includes('masjid') || n.includes('musholla')) return '🕌';
  if (t.includes('station') || t.includes('terminal') || n.includes('stasiun') || n.includes('terminal') || n.includes('pelabuhan') || n.includes('tanjung priok') || n.includes('bandara')) return '🚉';
  if (t.includes('mall') || t.includes('supermarket') || n.includes('mall') || n.includes('plaza') || n.includes('pasar') || n.includes('kelapa gading')) return '🏬';
  if (t.includes('hotel') || n.includes('hotel')) return '🏨';
  if (t.includes('park') || n.includes('taman') || n.includes('monas') || n.includes('ancol')) return '🌳';
  return '📍';
};

// Mesin Pencarian Spesifik: Mencari Segala Lokasi di Indonesia dengan Prioritas Terdekat
const searchSpecificLocations = async (query) => {
  const trimmed = query ? query.trim() : '';
  if (!trimmed || trimmed.length < 2) {
    suggestions.value = [];
    searchLoading.value = false;
    searchFailed.value = false;
    return;
  }

  searchLoading.value = true;
  searchFailed.value = false;
  lastSearchedQuery.value = trimmed;

  const baseLat = pickup.value.coords?.lat || DEFAULT_PICKUP.coords.lat;
  const baseLng = pickup.value.coords?.lng || DEFAULT_PICKUP.coords.lng;

  try {
    const list = [];
    const seen = new Set();

    // ENGINE 1: Komoot Photon dengan proximity bias lat/lon (Sangat cepat & akurat)
    try {
      const photonUrl = `https://photon.komoot.io/api/?q=${encodeURIComponent(trimmed)}&lat=${baseLat}&lon=${baseLng}&limit=8`;
      const photonRes = await fetch(photonUrl);
      const photonData = await photonRes.json();

      if (photonData?.features && photonData.features.length > 0) {
        for (const f of photonData.features) {
          const p = f.properties;
          const [lng, lat] = f.geometry.coordinates;
          const placeName = p.name || `${p.street || ''} ${p.housenumber || ''}`.trim() || 'Lokasi';
          const key = (placeName + '_' + lat.toFixed(3) + '_' + lng.toFixed(3)).toLowerCase();
          if (!seen.has(key)) {
            seen.add(key);
            list.push({
              id: p.osm_id || Math.random(),
              name: placeName,
              subtitle: formatPhotonSubtitle(p),
              lat,
              lng,
              icon: getLocationIcon(placeName, p.osm_value || ''),
              distanceFromBase: getDistanceToPickup(lat, lng)
            });
          }
        }
      }
    } catch (pErr) {
      console.warn('Photon search warning:', pErr);
    }

    // ENGINE 2 (FALLBACK / AUGMENT): OpenStreetMap Nominatim di seluruh Indonesia
    if (list.length < 4) {
      try {
        const nomUrl = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(trimmed)}&countrycodes=id&limit=6&addressdetails=1`;
        const nomRes = await fetch(nomUrl, {
          headers: { 'Accept-Language': 'id', 'User-Agent': 'PKL-DIGO-Ride-App/1.0' }
        });
        const nomData = await nomRes.json();

        if (Array.isArray(nomData) && nomData.length > 0) {
          for (const it of nomData) {
            const primaryName = it.name || it.display_name.split(',')[0];
            const lat = parseFloat(it.lat);
            const lng = parseFloat(it.lon);
            const key = (primaryName + '_' + lat.toFixed(3) + '_' + lng.toFixed(3)).toLowerCase();
            if (!seen.has(key)) {
              seen.add(key);
              list.push({
                id: it.place_id,
                name: primaryName,
                subtitle: formatNominatimSubtitle(it),
                lat,
                lng,
                icon: getLocationIcon(primaryName, it.type || ''),
                distanceFromBase: getDistanceToPickup(lat, lng)
              });
            }
          }
        }
      } catch (nErr) {
        console.warn('Nominatim search warning:', nErr);
      }
    }

    if (list.length > 0) {
      suggestions.value = list;
      searchFailed.value = false;
    } else {
      suggestions.value = [];
      searchFailed.value = true;
    }
  } catch (err) {
    console.error('Error saat mencari lokasi:', err);
    suggestions.value = [];
    searchFailed.value = true;
  } finally {
    searchLoading.value = false;
  }
};

const onInputFocus = (target) => {
  activeTarget.value = target;
};

const onInputQuery = (query, target) => {
  activeTarget.value = target;
  activeDropdown.value = target;

  if (debounceTimer) clearTimeout(debounceTimer);

  // Selalu sinkronkan perubahan teks secara real-time ke form parent
  emitRouteUpdate();

  if (!query || query.trim().length < 2) {
    suggestions.value = [];
    searchLoading.value = false;
    searchFailed.value = false;
    return;
  }

  searchLoading.value = true;
  debounceTimer = setTimeout(() => {
    searchSpecificLocations(query);
  }, 300);
};

// Auto-resolve koordinat jika pengguna mengetik nama tapi tidak mengklik dropdown
const onInputBlur = (target) => {
  setTimeout(async () => {
    if (target.startsWith('dest-')) {
      const idx = parseInt(target.split('-')[1], 10);
      const dest = destinations.value[idx];
      if (dest && dest.name && dest.name.trim().length >= 3 && !dest.coords) {
        try {
          const photonUrl = `https://photon.komoot.io/api/?q=${encodeURIComponent(dest.name.trim())}&lat=${DEFAULT_PICKUP.coords.lat}&lon=${DEFAULT_PICKUP.coords.lng}&limit=1`;
          const res = await fetch(photonUrl);
          const data = await res.json();
          if (data?.features && data.features[0]) {
            const [lng, lat] = data.features[0].geometry.coordinates;
            setDestinationPoint(idx, lat, lng);
            calculateMultiStopRoute();
            emitRouteUpdate();
          }
        } catch (e) {}
      }
    }
  }, 300);
};

const triggerSearch = (query, target) => {
  activeTarget.value = target;
  activeDropdown.value = target;
  if (debounceTimer) clearTimeout(debounceTimer);
  searchSpecificLocations(query || '');
};

const handleEnterKey = (query, target) => {
  if (suggestions.value.length > 0 && activeDropdown.value === target) {
    selectSuggestion(suggestions.value[0], target);
  } else {
    triggerSearch(query, target);
  }
};

// Memilih rekomendasi dropdown
const selectSuggestion = (item, target) => {
  if (target === 'pickup') {
    pickup.value.name = item.name;
    setPickupPoint(item.lat, item.lng);
    if (map) {
      map.flyTo([item.lat, item.lng], 15, { duration: 0.8 });
    }
    if (destinations.value.length > 0 && !destinations.value[0].coords) {
      activeTarget.value = 'dest-0';
    }
  } else {
    const parts = target.split('-');
    const idx = parts.length === 2 ? parseInt(parts[1], 10) : 0;
    if (destinations.value[idx]) {
      destinations.value[idx].name = item.name;
      setDestinationPoint(idx, item.lat, item.lng);
      if (map) {
        map.flyTo([item.lat, item.lng], 15, { duration: 0.8 });
      }
    }
  }

  activeDropdown.value = null;
  suggestions.value = [];
  searchFailed.value = false;
  emitRouteUpdate();
};

// Memilih Preset Populer / Rekomendasi Sering Dikunjungi (1-Klik)
const selectPresetDestination = (preset, index) => {
  if (!destinations.value[index]) return;
  destinations.value[index].name = preset.name;
  if (preset.coords && preset.coords.lat && preset.coords.lng) {
    setDestinationPoint(index, preset.coords.lat, preset.coords.lng);
    if (map) {
      map.flyTo([preset.coords.lat, preset.coords.lng], 15, { duration: 0.8 });
    }
    activeDropdown.value = null;
    emitRouteUpdate();
  } else {
    // Jika koordinat belum terpasang otomatis, cari secara spesifik
    triggerSearch(preset.name, `dest-${index}`);
  }
};

// Ambil Lokasi yang Sering Dikunjungi User (Personal Rekomendasi Otomatis)
const fetchFrequentDestinations = async () => {
  let uId = props.userId;
  if (!uId && typeof window !== 'undefined') {
    try {
      const raw = localStorage.getItem('auth_user');
      if (raw) {
        const u = JSON.parse(raw);
        if (u?.id) uId = u.id;
      }
    } catch (e) {}
  }

  if (!uId) uId = 82; // Fallback

  try {
    loadingFrequent.value = true;
    const res = await $fetch(`${apiBase}/users/${uId}/frequent-destinations`).catch(() => null);
    if (res?.data && Array.isArray(res.data) && res.data.length > 0) {
      userFrequentDestinations.value = res.data;
    } else {
      userFrequentDestinations.value = [];
    }
  } catch (err) {
    console.warn('Gagal memuat rekomendasi lokasi sering dikunjungi:', err);
  } finally {
    loadingFrequent.value = false;
  }
};

watch(() => props.userId, () => {
  fetchFrequentDestinations();
});

// Reset Penjemputan ke Default: BPJS Kesehatan Cabang Jakarta Utara
const resetToBpjsJakartaUtara = () => {
  pickup.value.name = DEFAULT_PICKUP.name;
  pickup.value.coords = { ...DEFAULT_PICKUP.coords };
  setPickupPoint(DEFAULT_PICKUP.coords.lat, DEFAULT_PICKUP.coords.lng);
  if (map) {
    map.flyTo([DEFAULT_PICKUP.coords.lat, DEFAULT_PICKUP.coords.lng], 15, { duration: 0.8 });
  }
  emitRouteUpdate();
};

const closeDropdown = () => {
  activeDropdown.value = null;
};

const clearInput = (target) => {
  if (target === 'pickup') {
    pickup.value.name = '';
    pickup.value.coords = null;
    if (pickupMarker && map) {
      map.removeLayer(pickupMarker);
      pickupMarker = null;
    }
  } else {
    const parts = target.split('-');
    const idx = parts.length === 2 ? parseInt(parts[1], 10) : 0;
    if (destinations.value[idx]) {
      destinations.value[idx].name = '';
      destinations.value[idx].coords = null;
      refreshDestMarkers();
    }
  }
  activeDropdown.value = null;
  suggestions.value = [];
  calculateMultiStopRoute();
  emitRouteUpdate();
};

// Click outside handler
const handleGlobalClick = (e) => {
  if (!e.target.closest || !e.target.closest('.location-search-wrapper')) {
    activeDropdown.value = null;
  }
};

// Google Maps URL yang 100% sinkron memuat titik jemput dan seluruh perhentian tujuan
const googleMapsUrl = computed(() => {
  const validDests = destinations.value.filter(d => d.name && d.name.trim().length > 0);
  if (validDests.length === 0) return null;

  const pickupName = (pickup.value.name && pickup.value.name.trim()) || DEFAULT_PICKUP.name;
  
  // Format standard multi-stop URL: /dir/pickup/dest1/dest2/...
  const allStops = [pickupName, ...validDests.map(d => d.name.trim())];
  return `https://www.google.com/maps/dir/${allStops.map(s => encodeURIComponent(s)).join('/')}`;
});

const getTargetLabel = (target) => {
  if (target === 'pickup') return 'Titik Penjemputan 🟢';
  const parts = target.split('-');
  if (parts.length === 2) {
    const idx = parseInt(parts[1], 10);
    return `Tujuan #${idx + 1} 🔴`;
  }
  return 'Lokasi Tujuan 🔴';
};

const addDestination = () => {
  const newIdx = destinations.value.length;
  destinations.value.push({
    id: Date.now(),
    name: '',
    coords: null
  });
  activeTarget.value = `dest-${newIdx}`;
  emitRouteUpdate();
};

const removeDestination = (index) => {
  if (destinations.value.length <= 1) return;
  destinations.value.splice(index, 1);

  refreshDestMarkers();
  calculateMultiStopRoute();
  emitRouteUpdate();

  activeTarget.value = 'dest-0';
};

// Emit data rute terpadu ke parent component (kendaraan.vue)
const emitRouteUpdate = () => {
  const validDests = destinations.value.filter(d => d.name && d.name.trim().length > 0);
  const destWithCoords = validDests.filter(d => d.coords);
  const finalDestWithCoords = destWithCoords.length > 0 ? destWithCoords[destWithCoords.length - 1] : null;

  const validDestNames = validDests.map(d => d.name.trim());

  emit('update:route', {
    pickup_location: pickup.value.name || DEFAULT_PICKUP.name,
    pickup_lat: pickup.value.coords?.lat || DEFAULT_PICKUP.coords.lat,
    pickup_lng: pickup.value.coords?.lng || DEFAULT_PICKUP.coords.lng,
    destinations: validDestNames.length > 0 ? validDestNames : ['Perjalanan Dinas'],
    destination_str: validDestNames.join(' ➔ '),
    destination_lat: finalDestWithCoords?.coords?.lat || null,
    destination_lng: finalDestWithCoords?.coords?.lng || null,
    distance_km: routeInfo.value?.distanceKm || null,
    estimated_duration_minutes: routeInfo.value?.durationMinutes || null,
    google_maps_url: googleMapsUrl.value
  });
};

const ensureLeaflet = () => {
  return new Promise((resolve) => {
    if (typeof window !== 'undefined' && window.L) {
      return resolve(window.L);
    }
    const check = setInterval(() => {
      if (typeof window !== 'undefined' && window.L) {
        clearInterval(check);
        resolve(window.L);
      }
    }, 100);
    setTimeout(() => {
      clearInterval(check);
      resolve(window.L || null);
    }, 6000);
  });
};

onMounted(async () => {
  fetchFrequentDestinations();

  if (typeof window !== 'undefined') {
    window.addEventListener('click', handleGlobalClick);
  }

  const L = await ensureLeaflet();
  if (!L || !mapContainer.value) return;

  // Inisialisasi Map tepat di Jakarta Utara (BPJS Kesehatan Cabang Jakarta Utara)
  map = L.map(mapContainer.value, {
    zoomControl: true,
    attributionControl: false
  }).setView([DEFAULT_PICKUP.coords.lat, DEFAULT_PICKUP.coords.lng], 14);

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
  }).addTo(map);

  // Pasang Titik Jemput Awal BPJS Kesehatan Cabang Jakarta Utara
  setPickupPoint(DEFAULT_PICKUP.coords.lat, DEFAULT_PICKUP.coords.lng);

  // Click di Map untuk set titik sesuai activeTarget
  map.on('click', async (e) => {
    const { lat, lng } = e.latlng;
    if (activeTarget.value === 'pickup') {
      setPickupPoint(lat, lng);
      const addr = await reverseGeocode(lat, lng);
      if (addr) pickup.value.name = addr;
      activeTarget.value = 'dest-0';
    } else {
      const parts = activeTarget.value.split('-');
      const idx = parts.length === 2 ? parseInt(parts[1], 10) : 0;
      setDestinationPoint(idx, lat, lng);
      const addr = await reverseGeocode(lat, lng);
      if (addr && destinations.value[idx]) {
        destinations.value[idx].name = addr;
      }
    }
    emitRouteUpdate();
  });

  // Emit update awal ke form
  emitRouteUpdate();
});

onBeforeUnmount(() => {
  if (typeof window !== 'undefined') {
    window.removeEventListener('click', handleGlobalClick);
  }
  if (debounceTimer) clearTimeout(debounceTimer);
});

// Icon Pin Jemput Hijau
const createPickupIcon = (L) => {
  return L.divIcon({
    className: 'custom-map-pin',
    html: `<div style="background-color:#10b981; width:32px; height:32px; border-radius:50%; border:3px solid #ffffff; box-shadow:0 0 14px rgba(16,185,129,0.9); display:flex; align-items:center; justify-content:center; color:white; font-size:14px; font-weight:bold;">🟢</div>`,
    iconSize: [32, 32],
    iconAnchor: [16, 16]
  });
};

// Icon Pin Tujuan Merah dengan Nomor Urut
const createDestIcon = (L, number) => {
  return L.divIcon({
    className: 'custom-map-pin',
    html: `<div style="background-color:#ef4444; width:30px; height:30px; border-radius:50%; border:3px solid #ffffff; box-shadow:0 0 12px rgba(239,68,68,0.8); display:flex; align-items:center; justify-content:center; color:white; font-size:11px; font-weight:900;">${number}</div>`,
    iconSize: [30, 30],
    iconAnchor: [15, 30]
  });
};

const setPickupPoint = (lat, lng) => {
  if (!map || !window.L) return;
  const L = window.L;
  pickup.value.coords = { lat, lng };

  if (pickupMarker) {
    pickupMarker.setLatLng([lat, lng]);
  } else {
    pickupMarker = L.marker([lat, lng], {
      icon: createPickupIcon(L),
      draggable: true
    }).addTo(map);

    pickupMarker.on('dragend', async (e) => {
      const pos = e.target.getLatLng();
      pickup.value.coords = { lat: pos.lat, lng: pos.lng };
      const addr = await reverseGeocode(pos.lat, pos.lng);
      if (addr) pickup.value.name = addr;
      calculateMultiStopRoute();
      emitRouteUpdate();
    });
  }

  calculateMultiStopRoute();
};

const setDestinationPoint = (index, lat, lng) => {
  if (!map || !window.L || !destinations.value[index]) return;
  const L = window.L;
  destinations.value[index].coords = { lat, lng };

  refreshDestMarkers();
  calculateMultiStopRoute();
};

const refreshDestMarkers = () => {
  if (!map || !window.L) return;
  const L = window.L;

  destMarkers.forEach(m => map.removeLayer(m));
  destMarkers = [];

  destinations.value.forEach((dest, idx) => {
    if (dest.coords) {
      const marker = L.marker([dest.coords.lat, dest.coords.lng], {
        icon: createDestIcon(L, idx + 1),
        draggable: true
      }).addTo(map);

      marker.on('dragend', async (e) => {
        const pos = e.target.getLatLng();
        dest.coords = { lat: pos.lat, lng: pos.lng };
        const addr = await reverseGeocode(pos.lat, pos.lng);
        if (addr) dest.name = addr;
        calculateMultiStopRoute();
        emitRouteUpdate();
      });

      destMarkers.push(marker);
    }
  });
};

// Hitung Rute Menghubungkan Titik Jemput -> Tujuan 1 -> Tujuan 2 -> dst via OSRM
const calculateMultiStopRoute = async () => {
  if (!pickup.value.coords || !map || !window.L) return;

  const validDests = destinations.value.filter(d => d.coords);
  if (validDests.length === 0) return;

  calculatingRoute.value = true;
  try {
    const coordsList = [
      `${pickup.value.coords.lng},${pickup.value.coords.lat}`,
      ...validDests.map(d => `${d.coords.lng},${d.coords.lat}`)
    ];

    const url = `https://router.project-osrm.org/route/v1/driving/${coordsList.join(';')}?overview=full&geometries=geojson`;
    const res = await fetch(url);
    const data = await res.json();

    if (data.routes && data.routes.length > 0) {
      const route = data.routes[0];
      const distKm = (route.distance / 1000).toFixed(1);
      const durationMins = Math.round(route.duration / 60);

      routeInfo.value = {
        distanceKm: parseFloat(distKm),
        durationMinutes: durationMins
      };

      const L = window.L;
      if (routeLine) {
        map.removeLayer(routeLine);
      }

      const latLngs = route.geometry.coordinates.map(coord => [coord[1], coord[0]]);

      routeLine = L.polyline(latLngs, {
        color: '#10b981',
        weight: 6,
        opacity: 0.9,
        lineCap: 'round',
        lineJoin: 'round'
      }).addTo(map);

      const allPoints = [
        [pickup.value.coords.lat, pickup.value.coords.lng],
        ...validDests.map(d => [d.coords.lat, d.coords.lng])
      ];
      map.fitBounds(L.latLngBounds(allPoints), { padding: [40, 40] });

      emitRouteUpdate();
    }
  } catch (err) {
    console.warn('Gagal menghitung rute:', err);
  } finally {
    calculatingRoute.value = false;
  }
};

// Ambil Posisi Real GPS Pengguna jika pengguna klik tombol GPS
const getCurrentLocation = (isUserClick = false) => {
  if (typeof window === 'undefined' || !navigator.geolocation) {
    if (isUserClick) alert('Browser Anda tidak mendukung deteksi lokasi GPS.');
    return;
  }

  locating.value = true;
  navigator.geolocation.getCurrentPosition(
    async (pos) => {
      locating.value = false;
      const lat = pos.coords.latitude;
      const lng = pos.coords.longitude;

      setPickupPoint(lat, lng);
      if (map) {
        map.setView([lat, lng], 15);
      }

      const addr = await reverseGeocode(lat, lng);
      pickup.value.name = addr || 'Lokasi Saya Saat Ini (GPS Real)';
      emitRouteUpdate();
    },
    (err) => {
      locating.value = false;
      if (isUserClick) {
        alert('Gagal mendeteksi lokasi GPS. Titik jemput tetap menggunakan BPJS Kesehatan Cabang Jakarta Utara.');
      }
    },
    { enableHighAccuracy: true, timeout: 10000, maximumAge: 30000 }
  );
};

// Reverse Geocode (Koordinat ke Alamat)
const reverseGeocode = async (lat, lng) => {
  try {
    const url = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`;
    const res = await fetch(url, {
      headers: { 'Accept-Language': 'id', 'User-Agent': 'PKL-DIGO-Ride-App/1.0' }
    });
    const data = await res.json();
    if (data && data.display_name) {
      const parts = data.display_name.split(',');
      return parts.slice(0, 3).join(',').trim();
    }
  } catch (e) {}
  return null;
};
</script>

<style scoped>
:deep(.leaflet-popup-content-wrapper) {
  background-color: #0f172a;
  color: #f8fafc;
  border: 1px solid #334155;
  border-radius: 12px;
}
</style>
