<template>
  <div class="riwayat-page min-h-screen font-sans pb-16 relative" :class="[isDark ? 'theme-dark' : 'theme-light']">
    <!-- BACKGROUND ARSIP & RIWAYAT (TETAP NATURAL, TANPA LAPISAN GELAP/TERANG) -->
    <div class="riwayat-bg-backdrop" aria-hidden="true"></div>

    <!-- Topbar Navigasi Terpadu -->
    <UserTopbar />

    <main class="riwayat-main max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-6 relative z-10">
      <!-- Header / Banner Profil User -->
      <section class="banner-card rounded-2xl p-4 sm:p-6 shadow-xl relative overflow-hidden">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-5">
          <div class="flex items-center gap-4">
            <div class="user-avatar-badge w-14 h-14 rounded-2xl flex items-center justify-center text-white text-xl font-black shadow-lg shrink-0">
              {{ userAvatarLetter }}
            </div>
            <div>
              <div class="flex items-center gap-2 mb-1">
                <span class="badge-procedure px-2.5 py-0.5 rounded-full text-[10px] font-bold">
                  PEGAWAI AKTIF
                </span>
                <span class="text-xs text-subtle font-mono">NIP: {{ currentUser.nip || '-' }}</span>
              </div>
              <h1 class="text-xl md:text-2xl font-black banner-title">{{ currentUser.name || 'Pegawai' }}</h1>
              <p class="text-xs banner-desc mt-0.5">
                Divisi: <span class="text-emerald-400 font-semibold">{{ currentUser.division?.name || userDivisionName }}</span>
              </p>
            </div>
          </div>

          <div class="flex items-center gap-2">
            <button
              @click="loadAllHistory"
              class="btn-refresh px-3.5 py-2 sm:px-4 sm:py-2.5 rounded-xl text-xs font-semibold transition flex items-center gap-2 shadow-sm"
              :disabled="isLoading"
              title="Segarkan Data Riwayat"
            >
              <span :class="{ 'animate-spin': isLoading }">🔄</span>
              <span>Segarkan Data</span>
            </button>
          </div>
        </div>
      </section>

      <!-- Ringkasan Statistik Riwayat Personal -->
      <section class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        <div class="stat-card rounded-2xl p-3.5 sm:p-4 flex flex-col sm:flex-row items-start sm:items-center gap-2.5 sm:gap-3 shadow-lg">
          <div class="stat-icon-wrapper w-9 h-9 sm:w-11 sm:h-11 rounded-xl text-emerald-400 flex items-center justify-center text-lg sm:text-xl shrink-0">
            📦
          </div>
          <div>
            <span class="text-[10px] sm:text-[11px] text-subtle font-medium block">Total ATK</span>
            <strong class="text-base sm:text-lg font-bold stat-value">{{ totalAtkUnits }} unit</strong>
            <span class="text-[9px] sm:text-[10px] text-emerald-400 block">{{ atkList.length }} pengajuan</span>
          </div>
        </div>

        <div class="stat-card rounded-2xl p-3.5 sm:p-4 flex flex-col sm:flex-row items-start sm:items-center gap-2.5 sm:gap-3 shadow-lg">
          <div class="stat-icon-wrapper w-9 h-9 sm:w-11 sm:h-11 rounded-xl text-cyan-400 flex items-center justify-center text-lg sm:text-xl shrink-0">
            🚗
          </div>
          <div>
            <span class="text-[10px] sm:text-[11px] text-subtle font-medium block">Mobil Dinas</span>
            <strong class="text-base sm:text-lg font-bold stat-value">{{ vehicleList.length }} trip</strong>
            <span class="text-[9px] sm:text-[10px] text-cyan-400 block">{{ completedTripsCount }} terlaksana</span>
          </div>
        </div>

        <div class="stat-card rounded-2xl p-3.5 sm:p-4 flex flex-col sm:flex-row items-start sm:items-center gap-2.5 sm:gap-3 shadow-lg">
          <div class="stat-icon-wrapper w-9 h-9 sm:w-11 sm:h-11 rounded-xl text-amber-400 flex items-center justify-center text-lg sm:text-xl shrink-0">
            ⏳
          </div>
          <div>
            <span class="text-[10px] sm:text-[11px] text-subtle font-medium block">Diproses</span>
            <strong class="text-base sm:text-lg font-bold stat-value">{{ pendingTotalCount }} aktivitas</strong>
            <span class="text-[9px] sm:text-[10px] text-amber-400 block">Menunggu verifikasi</span>
          </div>
        </div>

        <div class="stat-card rounded-2xl p-3.5 sm:p-4 flex flex-col sm:flex-row items-start sm:items-center gap-2.5 sm:gap-3 shadow-lg">
          <div class="stat-icon-wrapper w-9 h-9 sm:w-11 sm:h-11 rounded-xl text-indigo-400 flex items-center justify-center text-lg sm:text-xl shrink-0">
            📊
          </div>
          <div>
            <span class="text-[10px] sm:text-[11px] text-subtle font-medium block">Sisa Kuota</span>
            <strong class="text-base sm:text-lg font-bold stat-value">{{ totalRemainingLimit }} unit</strong>
            <span class="text-[9px] sm:text-[10px] text-subtle block">Semester aktif</span>
          </div>
        </div>
      </section>

      <!-- Tab Navigasi Kategori Riwayat -->
      <div class="section-filter-card p-3 sm:p-4 rounded-2xl flex flex-col xl:flex-row xl:items-center justify-between gap-4">
        <div class="flex items-center gap-2 overflow-x-auto pb-2 xl:pb-0">
          <button
            @click="activeTab = 'all'"
            class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2 whitespace-nowrap"
            :class="activeTab === 'all' ? 'tab-btn-active' : 'tab-btn-inactive'"
          >
            <span>📋 Semua Riwayat</span>
            <span class="px-1.5 py-0.2 rounded text-[10px]" :class="activeTab === 'all' ? 'bg-emerald-700 text-white' : 'tab-badge'">
              {{ combinedHistory.length }}
            </span>
          </button>

          <button
            @click="activeTab = 'atk'"
            class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2 whitespace-nowrap"
            :class="activeTab === 'atk' ? 'tab-btn-active' : 'tab-btn-inactive'"
          >
            <span>📦 Pengambilan ATK</span>
            <span class="px-1.5 py-0.2 rounded text-[10px]" :class="activeTab === 'atk' ? 'bg-emerald-700 text-white' : 'tab-badge'">
              {{ atkList.length }}
            </span>
          </button>

          <button
            @click="activeTab = 'ruangan'"
            class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2 whitespace-nowrap"
            :class="activeTab === 'ruangan' ? 'tab-btn-active' : 'tab-btn-inactive'"
          >
            <span>🏛️ Peminjaman Ruangan</span>
            <span class="px-1.5 py-0.2 rounded text-[10px]" :class="activeTab === 'ruangan' ? 'bg-emerald-700 text-white' : 'tab-badge'">
              {{ roomList.length }}
            </span>
          </button>

          <button
            @click="activeTab = 'mobil'"
            class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2 whitespace-nowrap"
            :class="activeTab === 'mobil' ? 'tab-btn-active' : 'tab-btn-inactive'"
          >
            <span>🚗 Peminjaman Mobil</span>
            <span class="px-1.5 py-0.2 rounded text-[10px]" :class="activeTab === 'mobil' ? 'bg-emerald-700 text-white' : 'tab-badge'">
              {{ vehicleList.length }}
            </span>
          </button>

          <button
            @click="activeTab = 'kuota'"
            class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2 whitespace-nowrap"
            :class="activeTab === 'kuota' ? 'tab-btn-active' : 'tab-btn-inactive'"
          >
            <span>📊 Batas Kuota Divisi</span>
          </button>
        </div>

        <!-- Filter Pencarian, Tanggal & Status -->
        <div v-if="activeTab !== 'kuota'" class="flex flex-wrap items-center gap-2">
          <div class="relative">
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Cari barang / mobil / tujuan..."
              class="form-input w-44 md:w-56 pl-8 pr-3 py-1.5 rounded-lg text-xs focus:outline-none focus:border-emerald-500"
            />
            <span class="absolute left-2.5 top-2 text-subtle text-xs">🔍</span>
          </div>

          <!-- Filter Tanggal -->
          <div class="flex items-center gap-1">
            <div class="relative">
              <input
                v-model="dateFilter"
                type="date"
                title="Filter Tanggal Riwayat"
                class="form-input px-2 py-1.5 rounded-lg text-xs font-mono focus:outline-none focus:border-emerald-500"
              />
            </div>
            <button
              v-if="dateFilter"
              @click="dateFilter = ''"
              type="button"
              class="px-2 py-1.5 rounded-lg bg-red-500/20 text-red-400 hover:bg-red-500/30 text-xs font-bold transition"
              title="Reset Filter Tanggal"
            >
              ✕
            </button>
          </div>

          <select
            v-model="statusFilter"
            class="form-select px-2.5 py-1.5 rounded-lg text-xs focus:outline-none focus:border-emerald-500"
          >
            <option value="all">Semua Status</option>
            <option value="selesai">Selesai</option>
            <option value="proses">Diproses / Berjalan</option>
          </select>
        </div>
      </div>

      <!-- Loading State -->
      <div v-if="isLoading" class="loading-box text-center py-16 text-subtle text-xs rounded-2xl p-6">
        <div class="w-8 h-8 border-2 border-emerald-500 border-t-transparent rounded-full animate-spin mx-auto mb-3"></div>
        Memuat riwayat personal akun {{ currentUser.name }}...
      </div>

      <!-- TAB 1: SEMUA RIWAYAT -->
      <div v-else-if="activeTab === 'all'" class="space-y-3">
        <div v-if="filteredCombinedHistory.length === 0" class="empty-box py-16 text-center text-subtle text-xs rounded-2xl p-6">
          <div class="text-3xl mb-2">📋</div>
          <p class="font-semibold banner-title">Belum ada riwayat aktivitas yang tercatat untuk akun ini.</p>
          <p class="text-[11px] text-subtle mt-1">Gunakan menu ATK atau Kendaraan untuk mengajukan peminjaman pertama Anda.</p>
        </div>

        <div v-else class="space-y-3">
          <div
            v-for="item in filteredCombinedHistory"
            :key="item.uniqueKey"
            class="history-card rounded-2xl p-4 md:p-5 transition shadow-lg flex flex-col md:flex-row md:items-center justify-between gap-4 group"
          >
            <div class="flex items-start gap-3.5">
              <div
                class="item-type-icon w-10 h-10 rounded-xl flex items-center justify-center text-lg shrink-0 border"
                :class="item.type === 'atk' ? 'text-emerald-400' : (item.type === 'ruangan' ? 'text-purple-400' : 'text-cyan-400')"
              >
                {{ item.type === 'atk' ? '📦' : (item.type === 'ruangan' ? '🏛️' : '🚗') }}
              </div>
              <div>
                <div class="flex items-center gap-2 mb-1">
                  <span
                    class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider badge-category"
                    :class="item.type === 'atk' ? 'badge-cat-atk' : (item.type === 'ruangan' ? 'badge-cat-ruangan' : 'badge-cat-mobil')"
                  >
                    {{ item.type === 'atk' ? 'Alat Tulis Kantor' : (item.type === 'ruangan' ? 'Peminjaman Ruangan' : 'Peminjaman Mobil') }}
                  </span>
                  <span class="text-xs text-subtle font-mono">{{ item.formattedDate }}</span>
                </div>
                <h3 class="font-bold text-sm banner-title">{{ item.title }}</h3>
                <p class="text-xs text-subtle mt-0.5">{{ item.subtitle }}</p>
                <p v-if="item.note" class="text-[11px] text-subtle mt-1 italic">Catatan: {{ item.note }}</p>
              </div>
            </div>

            <div class="card-footer-item flex items-center justify-between md:flex-col md:items-end gap-2 shrink-0 border-t md:border-t-0 pt-2 md:pt-0">
              <span
                class="px-2.5 py-1 rounded-full text-[11px] font-bold border"
                :class="item.statusBadgeClass"
              >
                {{ item.statusText }}
              </span>
              <span class="text-[11px] text-subtle font-medium">{{ item.metaInfo }}</span>
            </div>
          </div>
        </div>
      </div>

      <!-- TAB 2: PENGAMBILAN ATK -->
      <div v-else-if="activeTab === 'atk'" class="space-y-4">
        <div v-if="filteredAtkList.length === 0" class="empty-box py-16 text-center text-subtle text-xs rounded-2xl p-6">
          <div class="text-3xl mb-2">📦</div>
          <p class="font-semibold banner-title">Belum ada riwayat pengambilan ATK.</p>
          <NuxtLink to="/User/ATK" class="btn-action inline-block mt-3 px-4 py-2 rounded-xl text-white font-bold text-xs transition">
            Ambil ATK Sekarang
          </NuxtLink>
        </div>

        <div v-else class="table-card rounded-2xl overflow-hidden shadow-xl">
          <!-- DESKTOP TABLE -->
          <div class="hidden sm:block overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
              <thead>
                <tr class="table-head-row text-subtle font-semibold border-b">
                  <th class="p-3.5 pl-4">No</th>
                  <th class="p-3.5">Tanggal</th>
                  <th class="p-3.5">Nama Barang</th>
                  <th class="p-3.5">Jumlah</th>
                  <th class="p-3.5">Keperluan</th>
                  <th class="p-3.5 pr-4 text-right">Status</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-inherit">
                <tr v-for="(entry, index) in filteredAtkList" :key="entry.id || index" class="table-body-row transition">
                  <td class="p-3.5 pl-4 text-subtle font-mono">{{ index + 1 }}</td>
                  <td class="p-3.5 banner-title font-mono whitespace-nowrap">{{ entry.date || entry.created_at || '-' }}</td>
                  <td class="p-3.5">
                    <div v-if="entry.items && entry.items.length > 1" class="space-y-1.5">
                      <div class="flex items-center gap-1.5">
                        <span class="px-1.5 py-0.5 rounded bg-emerald-500/20 text-emerald-400 font-bold text-[10px] font-mono border border-emerald-500/30">
                          📦 Paket {{ entry.items.length }} Jenis Barang
                        </span>
                      </div>
                      <div class="space-y-1 pl-1">
                        <div
                          v-for="(subItem, sIdx) in entry.items"
                          :key="sIdx"
                          class="flex items-center gap-1.5 text-[11px] banner-title"
                        >
                          <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 shrink-0"></span>
                          <span class="font-semibold">{{ subItem.product?.name || subItem.item || 'Barang' }}</span>
                          <span class="text-emerald-400 font-mono font-bold">({{ subItem.quantity }} {{ subItem.product?.unit || 'unit' }})</span>
                        </div>
                      </div>
                    </div>
                    <div v-else>
                      <strong class="banner-title block">{{ entry.item || entry.items?.[0]?.product?.name || 'Alat Tulis' }}</strong>
                    </div>
                  </td>
                  <td class="p-3.5">
                    <span class="px-2 py-0.5 rounded badge-quantity font-bold font-mono">
                      {{ getAtkEntryTotalUnits(entry) }} {{ getAtkEntryUnit(entry) }}
                    </span>
                    <span v-if="entry.items && entry.items.length > 1" class="block text-[10px] text-subtle mt-0.5 font-sans">
                      {{ entry.items.length }} jenis barang
                    </span>
                  </td>
                  <td class="p-3.5 text-subtle max-w-[240px] truncate">
                    {{ entry.note || entry.request_note || 'Keperluan Kantor' }}
                  </td>
                  <td class="p-3.5 pr-4 text-right">
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border badge-status-done">
                      Selesai
                    </span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- MOBILE CARD LIST (SM:HIDDEN) -->
          <div class="sm:hidden divide-y divide-slate-800/40">
            <div v-for="(entry, index) in filteredAtkList" :key="entry.id || index" class="p-4 space-y-2.5">
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                  <span class="w-5 h-5 rounded-md bg-emerald-500/20 text-emerald-400 font-bold text-[10px] flex items-center justify-center font-mono">
                    {{ index + 1 }}
                  </span>
                  <span class="font-mono text-xs text-subtle">{{ entry.date || entry.created_at || '-' }}</span>
                </div>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold border badge-status-done">
                  Selesai
                </span>
              </div>

              <!-- Item info -->
              <div v-if="entry.items && entry.items.length > 1" class="space-y-1.5 p-2.5 rounded-xl bg-slate-950/40 border border-slate-800/60">
                <span class="px-1.5 py-0.5 rounded bg-emerald-500/20 text-emerald-400 font-bold text-[10px] font-mono border border-emerald-500/30 inline-block">
                  📦 Paket {{ entry.items.length }} Jenis Barang
                </span>
                <div class="space-y-1 pl-1 pt-1">
                  <div v-for="(subItem, sIdx) in entry.items" :key="sIdx" class="flex items-center justify-between text-xs">
                    <span class="font-medium text-slate-200">{{ subItem.product?.name || subItem.item || 'Barang' }}</span>
                    <span class="text-emerald-400 font-mono font-bold">{{ subItem.quantity }} {{ subItem.product?.unit || 'unit' }}</span>
                  </div>
                </div>
              </div>
              <div v-else class="flex items-center justify-between">
                <strong class="text-xs font-bold text-white">{{ entry.item || entry.items?.[0]?.product?.name || 'Alat Tulis' }}</strong>
                <span class="px-2 py-0.5 rounded badge-quantity font-bold font-mono text-xs">
                  {{ getAtkEntryTotalUnits(entry) }} {{ getAtkEntryUnit(entry) }}
                </span>
              </div>

              <!-- Note / purpose -->
              <div v-if="entry.note || entry.request_note" class="text-[11px] text-slate-400 flex items-start gap-1.5">
                <span class="text-slate-500 shrink-0">Keperluan:</span>
                <span class="italic text-slate-300">{{ entry.note || entry.request_note }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- TAB 2.5: PEMINJAMAN RUANGAN -->
      <div v-else-if="activeTab === 'ruangan'" class="space-y-4">
        <div v-if="filteredRoomList.length === 0" class="empty-box py-16 text-center text-subtle text-xs rounded-2xl p-6">
          <div class="text-3xl mb-2">🏛️</div>
          <p class="font-semibold banner-title">Belum ada riwayat peminjaman ruangan.</p>
          <NuxtLink to="/User/ruangan" class="btn-action inline-block mt-3 px-4 py-2 rounded-xl text-white font-bold text-xs transition">
            Ajukan Peminjaman Ruangan
          </NuxtLink>
        </div>

        <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div
            v-for="r in filteredRoomList"
            :key="r.id"
            class="vehicle-card rounded-2xl p-5 flex flex-col justify-between shadow-lg transition"
            :class="r.status === 'approved' ? 'card-veh-active' : (r.status === 'pending' ? 'card-veh-pending' : 'card-veh-done')"
          >
            <div>
              <div class="flex items-center justify-between mb-3">
                <span class="badge-plate font-mono text-xs font-bold px-2.5 py-1 rounded-lg border">
                  {{ r.room?.code || 'RUANGAN' }}
                </span>
                <span
                  class="text-[11px] font-bold px-2.5 py-0.5 rounded-full border"
                  :class="getRoomBadgeClass(r.status)"
                >
                  {{ getRoomStatusText(r.status) }}
                </span>
              </div>

              <h3 class="font-bold text-base banner-title mb-1">{{ r.room?.name || 'Ruang Rapat Utama' }}</h3>
              
              <div class="space-y-1.5 text-xs text-subtle mt-3">
                <div class="flex items-center gap-2">
                  <span class="text-subtle">📌 Agenda:</span>
                  <strong class="banner-title">{{ r.agenda }}</strong>
                </div>
                <div class="flex items-center gap-2">
                  <span class="text-subtle">🏢 Lokasi:</span>
                  <span class="text-cyan-400 font-semibold">{{ r.room?.floor || 'Gedung Utama' }} (Kapasitas: {{ r.room?.capacity || 10 }} Orang)</span>
                </div>
                <div class="flex items-center gap-2">
                  <span class="text-subtle">🕒 Waktu:</span>
                  <span class="text-subtle font-mono">{{ formatBookingDate(r.start_time || r.created_at) }}</span>
                </div>
              </div>
            </div>

            <div v-if="r.notes" class="card-footer-note mt-4 pt-3 border-t text-[11px] text-subtle">
              💬 <span class="italic">{{ r.notes }}</span>
            </div>
          </div>
        </div>
      </div>

      <!-- TAB 3: PEMINJAMAN MOBIL -->
      <div v-else-if="activeTab === 'mobil'" class="space-y-4">
        <div v-if="filteredVehicleList.length === 0" class="empty-box py-16 text-center text-subtle text-xs rounded-2xl p-6">
          <div class="text-3xl mb-2">🚗</div>
          <p class="font-semibold banner-title">Belum ada permohonan peminjaman mobil dinas.</p>
          <NuxtLink to="/User/kendaraan" class="btn-action inline-block mt-3 px-4 py-2 rounded-xl text-white font-bold text-xs transition">
            Booking Mobil Sekarang
          </NuxtLink>
        </div>

        <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div
            v-for="b in filteredVehicleList"
            :key="b.id"
            class="vehicle-card rounded-2xl p-5 flex flex-col justify-between shadow-lg transition"
            :class="b.status === 'in_progress' ? 'card-veh-active' : (b.status === 'pending' ? 'card-veh-pending' : 'card-veh-done')"
          >
            <div>
              <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-1.5 flex-wrap">
                  <span class="badge-plate font-mono text-xs font-bold px-2.5 py-1 rounded-lg border">
                    {{ b.vehicle?.license_plate || 'ALOKASI POOL' }}
                  </span>
                  <span
                    v-if="b.is_routine || b.booking_type === 'routine'"
                    class="text-[10px] font-bold px-2 py-0.5 rounded-full border bg-cyan-500/15 text-cyan-300 border-cyan-500/30"
                  >
                    🔄 Kunjungan Rutin
                  </span>
                </div>
                <span
                  class="text-[11px] font-bold px-2.5 py-0.5 rounded-full border"
                  :class="getVehicleBadgeClass(b.status)"
                >
                  {{ getVehicleStatusText(b.status) }}
                </span>
              </div>

              <h3 class="font-bold text-base banner-title mb-1">{{ b.vehicle?.name || 'Mobil Dinas Operasional' }}</h3>
              
              <div class="space-y-1.5 text-xs text-subtle mt-3">
                <div class="flex items-start gap-2">
                  <span class="text-subtle shrink-0">📍 Rute:</span>
                  <strong class="banner-title leading-relaxed">{{ b.destination }}</strong>
                </div>
                <div v-if="b.routine_schedule" class="p-2 rounded-lg bg-cyan-500/10 border border-cyan-500/20 text-[11px] my-1">
                  <span class="text-cyan-400 font-bold block mb-0.5">🗓️ Pola Jadwal Rutin:</span>
                  <span class="text-cyan-300 font-medium leading-relaxed">{{ b.routine_schedule }}</span>
                </div>
                <div v-if="b.purpose" class="flex items-start gap-2">
                  <span class="text-subtle shrink-0">🎯 Keperluan:</span>
                  <span class="text-emerald-400 font-semibold leading-relaxed">{{ b.purpose }}</span>
                </div>
                <div class="flex items-center gap-2">
                  <span class="text-subtle">👨‍✈️ Pengemudi:</span>
                  <span class="text-amber-400 font-semibold">{{ b.driver?.name || b.driver_name || 'Menunggu Penugasan' }}</span>
                </div>
                <div v-if="b.borrower_name || b.requester_name" class="flex items-center gap-2">
                  <span class="text-subtle">👤 Pemesan:</span>
                  <span class="font-semibold text-emerald-400">{{ b.borrower_name || b.requester_name }}</span>
                </div>
                <div class="flex items-center gap-2">
                  <span class="text-subtle">👥 Penumpang:</span>
                  <span class="banner-title">{{ b.passengers || `${b.passengers_count || 1} Orang` }}</span>
                </div>
                <div class="flex items-center gap-2">
                  <span class="text-subtle">🕒 Jadwal:</span>
                  <span class="text-subtle font-mono">{{ formatVehicleSchedule(b) }}</span>
                </div>
              </div>
            </div>

            <div v-if="b.notes" class="card-footer-note mt-4 pt-3 border-t text-[11px] text-subtle">
              💬 <span class="italic">{{ b.notes }}</span>
            </div>
          </div>
        </div>
      </div>

      <!-- TAB 4: KUOTA DIVISI (LIMIT 6 BULAN) -->
      <div v-else-if="activeTab === 'kuota'" class="space-y-4">
        <div class="kuota-card rounded-2xl p-5 shadow-xl">
          <div class="flex flex-col md:flex-row md:items-center justify-between gap-2 mb-4 pb-3 border-b card-divider">
            <div>
              <h2 class="font-bold text-sm md:text-base banner-title">Alokasi & Batas Kuota Divisi (Per 6 Bulan)</h2>
              <p class="text-xs text-subtle">
                Alokasi kuota otomatis diatur untuk Divisi <b>{{ currentUser.division?.name || userDivisionName }}</b>.
              </p>
            </div>
            <span class="badge-procedure px-2.5 py-1 rounded-lg text-xs font-bold w-fit">
              Semester Aktif 2026
            </span>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div
              v-for="rule in limitRows"
              :key="rule.item"
              class="kuota-item-box rounded-xl p-4 flex flex-col justify-between"
            >
              <div>
                <div class="flex items-start justify-between gap-2 mb-2">
                  <div>
                    <h3 class="font-bold text-xs banner-title">{{ rule.item }}</h3>
                    <span class="text-[11px] text-subtle">
                      Telah diambil: <b class="banner-title">{{ rule.taken }}</b> dari {{ rule.limit }} {{ rule.unit }}
                    </span>
                  </div>
                  <span class="text-sm font-black font-mono" :class="rule.remaining > 0 ? 'text-emerald-400' : 'text-red-400'">
                    {{ rule.remaining }} {{ rule.unit }}
                  </span>
                </div>

                <!-- Progress bar -->
                <div class="w-full h-2 rounded-full progress-track overflow-hidden mt-3">
                  <div
                    class="h-full rounded-full transition-all"
                    :class="rule.remaining === 0 ? 'bg-red-500' : (rule.taken / rule.limit > 0.7 ? 'bg-amber-500' : 'bg-emerald-500')"
                    :style="{ width: `${Math.min(100, (rule.taken / rule.limit) * 100)}%` }"
                  ></div>
                </div>
              </div>

              <div class="mt-3 pt-2 border-t card-divider flex items-center justify-between text-[10px]">
                <span v-if="rule.remaining > 0" class="text-emerald-400">
                  ✓ Sisa batas aman untuk semester ini
                </span>
                <span v-else class="text-red-400 font-bold">
                  ⚠️ Kuota semester telah habis terpakai
                </span>
                <span class="text-subtle">{{ Math.round((rule.taken / rule.limit) * 100) }}% Terpakai</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'

useHead({
  title: 'Riwayat Aktivitas Pegawai - Portal DigO'
})

// Sinkronisasi status tema dark/light terpadu
const isDark = useState('user_theme_dark', () => true)

const config = useRuntimeConfig()
const apiBase = config.public?.apiBase || 'http://127.0.0.1:8000/api'

// User State
const userCookie = useCookie('auth_user')
const tokenCookie = useCookie('auth_token')

const currentUser = ref({
  id: null,
  name: 'Pegawai SIMPEL',
  nip: '',
  division: { name: 'Divisi Umum' }
})

const userDivisionName = computed(() => {
  return currentUser.value.division?.name || 'Divisi Kepesertaan'
})

const userAvatarLetter = computed(() => {
  return (currentUser.value.name || 'P').charAt(0).toUpperCase()
})

// State Riwayat
const atkList = ref([])
const vehicleList = ref([])
const roomList = ref([])
const divisionRules = ref([])
const isLoading = ref(false)

// Tab & Filter State
const activeTab = ref('all')
const searchQuery = ref('')
const statusFilter = ref('all')
const dateFilter = ref('')

// Status & Badge Helper Functions
const getVehicleStatusText = (status) => {
  const map = {
    pending: 'Menunggu Persetujuan',
    approved: 'Disetujui Admin (Siap Berangkat)',
    in_progress: 'Sedang Berjalan',
    completed: 'Perjalanan Selesai',
    rejected: 'Ditolak Admin'
  }
  return map[status] || status || 'Menunggu Persetujuan'
}

const getVehicleBadgeClass = (status) => {
  if (status === 'completed' || status === 'approved') return 'badge-status-done'
  if (status === 'in_progress') return 'badge-status-cyan'
  if (status === 'rejected') return 'badge-status-reject'
  return 'badge-status-wait'
}

const getRoomStatusText = (status) => {
  const map = {
    pending: 'Menunggu Persetujuan',
    approved: 'Disetujui Admin',
    completed: 'Selesai',
    rejected: 'Ditolak Admin'
  }
  return map[status] || status || 'Menunggu Persetujuan'
}

const getRoomBadgeClass = (status) => {
  if (status === 'completed' || status === 'approved') return 'badge-status-done'
  if (status === 'rejected') return 'badge-status-reject'
  return 'badge-status-wait'
}

// ATK Multi-item Helpers
const getAtkEntryTotalUnits = (entry) => {
  if (entry.items && Array.isArray(entry.items) && entry.items.length > 0) {
    return entry.items.reduce((sum, it) => sum + (Number(it.quantity) || 0), 0)
  }
  return Number(entry.quantity) || 1
}

const getAtkEntryUnit = (entry) => {
  if (entry.items && Array.isArray(entry.items) && entry.items.length > 1) {
    return 'Unit'
  }
  return entry.unit || entry.items?.[0]?.product?.unit || 'Pcs'
}

// Statistics Computations
const totalAtkUnits = computed(() => {
  return atkList.value.reduce((acc, curr) => {
    return acc + getAtkEntryTotalUnits(curr)
  }, 0)
})

const completedTripsCount = computed(() => {
  return vehicleList.value.filter(v => v.status === 'completed').length
})

const pendingTotalCount = computed(() => {
  const vehPending = vehicleList.value.filter(v => v.status === 'pending' || v.status === 'in_progress').length
  const roomPending = roomList.value.filter(r => r.status === 'pending').length
  return vehPending + roomPending
})

const totalRemainingLimit = computed(() => {
  return limitRows.value.reduce((acc, r) => acc + Math.max(0, r.remaining), 0)
})

// Division Limit Rows with Mock fallback if empty
const limitRows = computed(() => {
  const defaultRules = [
    { item: 'Kertas HVS A4 80gr', limit: 10, unit: 'Rim' },
    { item: 'Pulpen Standard Hitam', limit: 25, unit: 'Pcs' },
    { item: 'Map Snellhecter Plastik', limit: 30, unit: 'Pcs' },
    { item: 'Spidol Whiteboard Hitam', limit: 12, unit: 'Pcs' },
    { item: 'Buku Agenda Kerja Dinas', limit: 8, unit: 'Buku' },
    { item: 'Tinta Printer Epson L-Series', limit: 4, unit: 'Botol' }
  ]

  const activeRules = divisionRules.value.length > 0 ? divisionRules.value : defaultRules

  return activeRules.map(r => {
    const keyword = r.item.toLowerCase().split(' ')[0]
    const taken = atkList.value.reduce((sum, a) => {
      if (a.items && Array.isArray(a.items) && a.items.length > 0) {
        const itemMatches = a.items
          .filter(it => (it.product?.name || '').toLowerCase().includes(keyword))
          .reduce((sub, it) => sub + (Number(it.quantity) || 0), 0)
        return sum + itemMatches
      }
      const directMatches = (a.item || '').toLowerCase().includes(keyword)
      return sum + (directMatches ? (Number(a.quantity) || 1) : 0)
    }, 0)

    const remaining = Math.max(0, r.limit - taken)
    return {
      ...r,
      taken,
      remaining
    }
  })
})

// Tab Filtered Lists
const filteredAtkList = computed(() => {
  const q = searchQuery.value.toLowerCase().trim()
  const dFilter = dateFilter.value
  return atkList.value.filter(item => {
    const mainName = item.item || item.items?.[0]?.product?.name || ''
    const allItemNames = item.items ? item.items.map(i => i.product?.name || '').join(' ') : ''
    const nameMatch = (mainName + ' ' + allItemNames).toLowerCase().includes(q)
    const noteMatch = (item.note || item.request_note || '').toLowerCase().includes(q)
    const dateMatch = (item.date || item.created_at || '').includes(q)
    const matchesQuery = !q || nameMatch || noteMatch || dateMatch

    let matchesStatus = true
    if (statusFilter.value === 'selesai') {
      matchesStatus = true
    } else if (statusFilter.value === 'proses') {
      matchesStatus = false
    }

    let matchesDate = true
    if (dFilter) {
      const itemDate = (item.date || item.created_at || '').slice(0, 10)
      matchesDate = itemDate === dFilter
    }

    return matchesQuery && matchesStatus && matchesDate
  })
})

const filteredVehicleList = computed(() => {
  const q = searchQuery.value.toLowerCase().trim()
  const dFilter = dateFilter.value
  return vehicleList.value.filter(item => {
    const carMatch = (item.vehicle?.name || '').toLowerCase().includes(q)
    const plateMatch = (item.vehicle?.license_plate || '').toLowerCase().includes(q)
    const destMatch = (item.destination || '').toLowerCase().includes(q)
    const noteMatch = (item.notes || '').toLowerCase().includes(q)
    const matchesQuery = !q || carMatch || plateMatch || destMatch || noteMatch

    let matchesStatus = true
    if (statusFilter.value === 'selesai') {
      matchesStatus = item.status === 'completed'
    } else if (statusFilter.value === 'proses') {
      matchesStatus = item.status === 'pending' || item.status === 'in_progress'
    }

    let matchesDate = true
    if (dFilter) {
      const itemDate = (item.start_date || item.created_at || '').slice(0, 10)
      matchesDate = itemDate === dFilter
    }

    return matchesQuery && matchesStatus && matchesDate
  })
})

const filteredRoomList = computed(() => {
  const q = searchQuery.value.toLowerCase().trim()
  const dFilter = dateFilter.value
  return roomList.value.filter(item => {
    const roomName = (item.room?.name || '').toLowerCase()
    const roomCode = (item.room?.code || '').toLowerCase()
    const agenda = (item.agenda || '').toLowerCase()
    const notes = (item.notes || '').toLowerCase()
    const matchesQuery = !q || roomName.includes(q) || roomCode.includes(q) || agenda.includes(q) || notes.includes(q)

    let matchesStatus = true
    if (statusFilter.value === 'selesai') {
      matchesStatus = item.status === 'completed'
    } else if (statusFilter.value === 'proses') {
      matchesStatus = item.status === 'pending' || item.status === 'approved'
    }

    let matchesDate = true
    if (dFilter) {
      const itemDate = (item.start_time || item.created_at || item.date || '').slice(0, 10)
      matchesDate = itemDate === dFilter
    }

    return matchesQuery && matchesStatus && matchesDate
  })
})

// Format Helpers
const formatBookingDate = (dateStr) => {
  if (!dateStr) return '-'
  try {
    const d = new Date(dateStr)
    return d.toLocaleDateString('id-ID', {
      day: 'numeric',
      month: 'short',
      year: 'numeric',
      hour: '2-digit',
      minute: '2-digit'
    })
  } catch (e) {
    return dateStr
  }
}

const formatVehicleSchedule = (b) => {
  if (!b) return '-'
  if (b.is_routine && b.routine_schedule) return b.routine_schedule
  if (!b.start_date) return formatBookingDate(b.created_at)

  try {
    const start = new Date(b.start_date)
    const startDatePart = start.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' })
    const startTimePart = start.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }).replace('.', ':')

    if (b.end_date) {
      const end = new Date(b.end_date)
      const isSameDay = start.toDateString() === end.toDateString()
      const endTimePart = end.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }).replace('.', ':')

      if (isSameDay) {
        return `${startDatePart}, ${startTimePart} - ${endTimePart} WIB (Estimasi Pulang)`
      } else {
        const endDatePart = end.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' })
        return `${startDatePart} ${startTimePart} s/d ${endDatePart} ${endTimePart} WIB (Estimasi Pulang)`
      }
    }
    return `${startDatePart}, ${startTimePart} WIB`
  } catch (e) {
    return formatBookingDate(b.start_date)
  }
}

// Combined All History Stream
const combinedHistory = computed(() => {
  const combined = []

  // Add ATK Requests
  atkList.value.forEach((a, idx) => {
    const hasMultiple = a.items && Array.isArray(a.items) && a.items.length > 1
    const totalUnits = getAtkEntryTotalUnits(a)

    let title = ''
    let subtitle = ''
    let metaInfo = ''

    if (hasMultiple) {
      const names = a.items.map(it => `${it.product?.name || 'Barang'} (${it.quantity} ${it.product?.unit || 'unit'})`).join(', ')
      title = `Pengambilan ${a.items.length} Macam ATK (Paket)`
      subtitle = `Rincian: ${names}`
      metaInfo = `${totalUnits} Total Unit (${a.items.length} Barang)`
    } else {
      const itemName = a.item || a.items?.[0]?.product?.name || 'Alat Tulis Kantor'
      const qty = a.quantity || a.items?.[0]?.quantity || 1
      const unit = a.unit || a.items?.[0]?.product?.unit || 'Pcs'
      title = `${itemName} (${qty} ${unit})`
      subtitle = `Keperluan: ${a.note || a.request_note || 'Operasional Kerja Rutin'}`
      metaInfo = `${qty} ${unit}`
    }

    combined.push({
      uniqueKey: `atk-${a.id || idx}`,
      type: 'atk',
      title,
      subtitle,
      note: a.notes || (hasMultiple ? (a.note || a.request_note || '') : ''),
      formattedDate: formatBookingDate(a.date || a.created_at),
      rawDate: a.created_at ? new Date(a.created_at).getTime() : Date.now() - idx * 100000,
      status: 'selesai',
      statusText: 'Pengambilan Selesai',
      statusBadgeClass: 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30',
      metaInfo
    })
  })

  // Add Room Bookings
  roomList.value.forEach((r, idx) => {
    const isDone = r.status === 'completed'
    const isApproved = r.status === 'approved'
    const isRejected = r.status === 'rejected'

    let statusText = 'Menunggu Persetujuan'
    let statusBadgeClass = 'bg-amber-500/20 text-amber-300 border-amber-500/30'
    if (isDone) {
      statusText = 'Selesai'
      statusBadgeClass = 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30'
    } else if (isApproved) {
      statusText = 'Disetujui Admin'
      statusBadgeClass = 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30'
    } else if (isRejected) {
      statusText = 'Ditolak Admin'
      statusBadgeClass = 'bg-rose-500/20 text-rose-300 border-rose-500/30'
    }

    combined.push({
      uniqueKey: `room-${r.id || idx}`,
      type: 'ruangan',
      title: `${r.room?.name || 'Ruang Rapat'} (${r.room?.code || '-'})`,
      subtitle: `Agenda: ${r.agenda} • Waktu: ${formatBookingDate(r.start_time || r.created_at)}`,
      note: r.notes || '',
      formattedDate: formatBookingDate(r.start_time || r.created_at),
      rawDate: r.created_at ? new Date(r.created_at).getTime() : Date.now() - idx * 100000,
      status: isDone ? 'selesai' : 'proses',
      statusText,
      statusBadgeClass,
      metaInfo: r.room?.floor || 'Gedung Utama'
    })
  })

  // Add Vehicle Bookings
  vehicleList.value.forEach((v, idx) => {
    const isDone = v.status === 'completed'
    const isRunning = v.status === 'in_progress'
    const isApproved = v.status === 'approved'
    const isRejected = v.status === 'rejected'

    let statusText = 'Menunggu Persetujuan'
    let statusBadgeClass = 'bg-amber-500/20 text-amber-300 border-amber-500/30'
    if (isDone) {
      statusText = 'Perjalanan Selesai'
      statusBadgeClass = 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30'
    } else if (isRunning) {
      statusText = 'Sedang Berjalan'
      statusBadgeClass = 'bg-cyan-500/20 text-cyan-300 border-cyan-500/30'
    } else if (isApproved) {
      statusText = 'Disetujui Admin (Siap Berangkat)'
      statusBadgeClass = 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30'
    } else if (isRejected) {
      statusText = 'Ditolak Admin'
      statusBadgeClass = 'bg-rose-500/20 text-rose-300 border-rose-500/30'
    }

    combined.push({
      uniqueKey: `veh-${v.id || idx}`,
      type: 'mobil',
      title: `${v.vehicle?.name || 'Mobil Dinas'} (${v.vehicle?.license_plate || '-'})`,
      subtitle: `Tujuan: ${v.destination} • Pengemudi: ${v.driver?.name || v.driver_name || 'Menunggu Pengemudi'}`,
      note: v.notes || '',
      formattedDate: formatVehicleSchedule(v),
      rawDate: v.created_at ? new Date(v.created_at).getTime() : Date.now() - idx * 100000,
      status: isDone ? 'selesai' : 'proses',
      statusText,
      statusBadgeClass,
      metaInfo: v.passengers || `${v.passengers_count || 1} Orang`
    })
  })

  // Sort latest first
  combined.sort((a, b) => b.rawDate - a.rawDate)
  return combined
})

const filteredCombinedHistory = computed(() => {
  const q = searchQuery.value.toLowerCase().trim()
  const dFilter = dateFilter.value
  return combinedHistory.value.filter(item => {
    const titleMatch = item.title.toLowerCase().includes(q)
    const subMatch = item.subtitle.toLowerCase().includes(q)
    const noteMatch = (item.note || '').toLowerCase().includes(q)
    const matchesQuery = !q || titleMatch || subMatch || noteMatch

    let matchesStatus = true
    if (statusFilter.value === 'selesai') {
      matchesStatus = item.status === 'selesai'
    } else if (statusFilter.value === 'proses') {
      matchesStatus = item.status === 'proses'
    }

    let matchesDate = true
    if (dFilter) {
      const isoFromRaw = item.rawDate ? new Date(item.rawDate).toISOString().slice(0, 10) : ''
      matchesDate = isoFromRaw === dFilter || (item.formattedDate || '').includes(dFilter)
    }

    return matchesQuery && matchesStatus && matchesDate
  })
})

// Load History from Backend and localStorage
const loadAllHistory = async () => {
  isLoading.value = true

  const headers = {}
  if (tokenCookie.value) {
    headers['Authorization'] = `Bearer ${tokenCookie.value}`
  }

  // Pastikan identitas user aktif ter-resolve dengan benar
  let userId = currentUser.value?.id
  let userName = currentUser.value?.name
  let userNip = currentUser.value?.nip

  if ((!userId || !userName) && typeof window !== 'undefined') {
    const raw = localStorage.getItem('auth_user')
    if (raw) {
      try {
        const u = JSON.parse(raw)
        if (u?.id) {
          userId = u.id
          userName = u.name
          userNip = u.nip
          currentUser.value = { ...currentUser.value, ...u }
        }
      } catch (e) {}
    }
  }

  if ((!userId || !userName) && tokenCookie.value) {
    try {
      const me = await $fetch(`${apiBase}/auth/me`, { headers })
      if (me?.user?.id) {
        userId = me.user.id
        userName = me.user.name
        userNip = me.user.nip
        currentUser.value = { ...currentUser.value, ...me.user }
      }
    } catch (e) {}
  }

  // Jika tetap tidak ditemukan, fallback ke ID 82
  const effectiveUserId = userId || 82
  const effectiveUserName = userName || 'Dian Puspa Pratiwi'

  // Helper Isolasi: Pastikan data mobil benar-benar milik user aktif
  const isMyVehicleBooking = (v) => {
    if (!v) return false
    // 1. Cocokkan ID jika ada
    if (userId && v.user_id && String(v.user_id) === String(userId)) return true
    // 2. Cocokkan Nama Pemohon
    if (effectiveUserName && v.borrower_name && v.borrower_name.toLowerCase().trim() === effectiveUserName.toLowerCase().trim()) return true
    if (effectiveUserName && v.requester_name && v.requester_name.toLowerCase().trim() === effectiveUserName.toLowerCase().trim()) return true
    // 3. Cocokkan NIP jika ada
    if (userNip && userNip !== '-' && userNip !== '12345' && (v.borrower_nip === userNip || v.requester_nip === userNip)) return true
    return false
  }

  // 1. Fetch ATK History from backend
  try {
    const res = await $fetch(`${apiBase}/users/${effectiveUserId}/history`, { headers })
    if (Array.isArray(res)) {
      atkList.value = res
    }
  } catch (err) {
    try {
      const fallback = await $fetch(`${apiBase}/v1/manage/users/${effectiveUserId}/history`)
      if (Array.isArray(fallback)) {
        atkList.value = fallback
      }
    } catch (e) {
      console.warn('Backend history not accessible, using local history.')
    }
  }

  // Combine with localStorage ATK history if present (per-user storage)
  if (typeof window !== 'undefined') {
    const userAtkKey = `user_atk_history_${effectiveUserId}`
    const localStr = localStorage.getItem(userAtkKey)
    if (localStr) {
      try {
        const localItems = JSON.parse(localStr)
        if (Array.isArray(localItems) && localItems.length > 0) {
          const existingNotes = new Set(atkList.value.map(a => (a.note || a.request_note || '') + (a.item || '')))
          localItems.forEach(loc => {
            const key = (loc.note || '') + (loc.item || '')
            if (!existingNotes.has(key)) {
              atkList.value.unshift(loc)
            }
          })
        }
      } catch (e) {}
    }
    // Hapus legacy global cache agar data akun lain tidak bocor
    localStorage.removeItem('user_atk_history')
  }

  // 2. Fetch Vehicle Bookings from backend
  try {
    const resVeh = await $fetch(`${apiBase}/users/${effectiveUserId}/vehicle-bookings`, { headers })
    const vehArr = (resVeh && resVeh.data && Array.isArray(resVeh.data)) ? resVeh.data : (Array.isArray(resVeh) ? resVeh : [])
    vehicleList.value = vehArr.filter(isMyVehicleBooking)
  } catch (err) {
    try {
      const fallbackVeh = await $fetch(`${apiBase}/v1/manage/users/${effectiveUserId}/vehicle-bookings`)
      const fallbackArr = (fallbackVeh && fallbackVeh.data && Array.isArray(fallbackVeh.data)) ? fallbackVeh.data : (Array.isArray(fallbackVeh) ? fallbackVeh : [])
      vehicleList.value = fallbackArr.filter(isMyVehicleBooking)
    } catch (e) {
      console.warn('Backend vehicle bookings not accessible.')
    }
  }

  // Combine with localStorage vehicle history if present (terisolasi per-user)
  if (typeof window !== 'undefined') {
    const userVehKey = `user_vehicle_history_${effectiveUserId}`
    const localVehStr = localStorage.getItem(userVehKey)
    if (localVehStr) {
      try {
        const localVehs = JSON.parse(localVehStr)
        if (Array.isArray(localVehs) && localVehs.length > 0) {
          const existingIds = new Set(vehicleList.value.map(v => v.id))
          localVehs.filter(isMyVehicleBooking).forEach(loc => {
            if (!existingIds.has(loc.id)) {
              vehicleList.value.unshift(loc)
            }
          })
        }
      } catch (e) {}
    }
    // Hapus legacy global cache agar data akun lain tidak bocor
    localStorage.removeItem('user_vehicle_history')
  }

  // 3. Fetch Room Bookings from backend
  try {
    const resRoom = await $fetch(`${apiBase}/users/${effectiveUserId}/room-bookings`, { headers })
    if (resRoom && resRoom.data && Array.isArray(resRoom.data)) {
      roomList.value = resRoom.data
    } else if (Array.isArray(resRoom)) {
      roomList.value = resRoom
    }
  } catch (err) {
    try {
      const fallbackRoom = await $fetch(`${apiBase}/v1/manage/users/${effectiveUserId}/room-bookings`)
      if (fallbackRoom && fallbackRoom.data) roomList.value = fallbackRoom.data
    } catch (e) {
      console.warn('Backend room bookings not accessible.')
    }
  }

  // Combine with localStorage room history if present (per-user storage)
  if (typeof window !== 'undefined') {
    const userRoomKey = `user_room_history_${effectiveUserId}`
    const localRoomStr = localStorage.getItem(userRoomKey)
    if (localRoomStr) {
      try {
        const localRooms = JSON.parse(localRoomStr)
        if (Array.isArray(localRooms) && localRooms.length > 0) {
          const existingIds = new Set(roomList.value.map(r => r.id))
          localRooms.forEach(loc => {
            if (!existingIds.has(loc.id)) {
              roomList.value.unshift(loc)
            }
          })
        }
      } catch (e) {}
    }
    // Hapus legacy global cache agar data akun lain tidak bocor
    localStorage.removeItem('user_room_history')
  }

  // 4. Fetch Division Limits from backend
  try {
    const resLimits = await $fetch(`${apiBase}/users/${effectiveUserId}/limits`, { headers })
    if (Array.isArray(resLimits) && resLimits.length > 0) {
      divisionRules.value = resLimits.map(l => ({
        item: l.product?.name || 'Item ATK',
        limit: l.semester_limit || 10,
        unit: l.product?.unit || 'Pcs'
      }))
    }
  } catch (err) {}

  isLoading.value = false
}

onMounted(async () => {
  if (import.meta.client) {
    const savedTheme = localStorage.getItem('user_theme_mode')
    if (savedTheme) {
      isDark.value = savedTheme === 'dark'
    }
  }

  // Read authenticated user info
  if (userCookie.value) {
    try {
      const u = typeof userCookie.value === 'string' ? JSON.parse(userCookie.value) : userCookie.value
      if (u && u.name) currentUser.value = u
    } catch (e) {}
  }
  if ((!currentUser.value.nip || currentUser.value.name === 'Pegawai') && typeof window !== 'undefined') {
    const s = localStorage.getItem('auth_user')
    if (s) {
      try {
        const u = JSON.parse(s)
        if (u && u.name) currentUser.value = u
      } catch (e) {}
    }
  }

  await loadAllHistory()
})
</script>

<style scoped>
/* ========================================================================== */
/* BACKGROUND ARSIP RIWAYAT (TETAP NATURAL, TANPA LAPISAN GELAP/TERANG)      */
/* ========================================================================== */
.riwayat-page {
  background-color: transparent !important;
}

.riwayat-bg-backdrop {
  position: fixed;
  inset: 0;
  background-image: url('/images/riwayat-bg.jpg');
  background-size: cover;
  background-position: center center;
  background-repeat: no-repeat;
  pointer-events: none;
  z-index: 0;
  opacity: 1;
}

/* ========================================================================== */
/* THEME DARK: KOTAK TRANSPARAN & GARIS HIJAU NYALA SEPERTI LAMPU LED        */
/* ========================================================================== */
.theme-dark {
  --box-bg: rgba(11, 19, 32, 0.48);
  --box-border: #10b981;
  --box-shadow: 0 0 22px rgba(16, 185, 129, 0.38), inset 0 0 16px rgba(16, 185, 129, 0.08);
  --inner-bg: rgba(0, 0, 0, 0.45);
  --inner-border: rgba(16, 185, 129, 0.45);
  --text-main: #ffffff;
  --text-sub: #cbd5e1;
  --text-body: #f8fafc;
  --input-bg: rgba(5, 10, 20, 0.75);
  --input-border: rgba(16, 185, 129, 0.55);
  --divider: rgba(16, 185, 129, 0.3);
  --table-head-bg: rgba(0, 0, 0, 0.5);
  --table-hover: rgba(16, 185, 129, 0.12);
}

/* ========================================================================== */
/* THEME LIGHT: KOTAK TRANSPARAN & GARIS WARNA BIASA                          */
/* ========================================================================== */
.theme-light {
  --box-bg: rgba(255, 255, 255, 0.65);
  --box-border: rgba(203, 213, 225, 0.9);
  --box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
  --inner-bg: rgba(255, 255, 255, 0.55);
  --inner-border: rgba(203, 213, 225, 0.8);
  --text-main: #0f172a;
  --text-sub: #475569;
  --text-body: #1e293b;
  --input-bg: rgba(255, 255, 255, 0.85);
  --input-border: #cbd5e1;
  --divider: rgba(226, 232, 240, 0.8);
  --table-head-bg: rgba(241, 245, 249, 0.75);
  --table-hover: rgba(241, 245, 249, 0.9);
}

/* COMMON GLASSMORPHISM BOX STYLES */
.banner-card,
.stat-card,
.section-filter-card,
.history-card,
.table-card,
.vehicle-card,
.kuota-card,
.loading-box,
.empty-box {
  background-color: var(--box-bg);
  border: 1px solid var(--box-border);
  box-shadow: var(--box-shadow);
  backdrop-filter: blur(16px);
  -webkit-backdrop-filter: blur(16px);
  color: var(--text-body);
  transition: all 0.25s ease;
  min-width: 0;
  box-sizing: border-box;
}

.table-card {
  overflow: hidden;
}

.history-card {
  min-width: 0;
  box-sizing: border-box;
}

.theme-dark .history-card:hover,
.theme-dark .vehicle-card:hover {
  border-color: #34d399;
  box-shadow: 0 0 32px rgba(16, 185, 129, 0.6), inset 0 0 20px rgba(16, 185, 129, 0.15);
}

/* TEXT COLOR OVERRIDES */
.banner-title,
.stat-value {
  color: var(--text-main);
}

.text-subtle {
  color: var(--text-sub);
}

.banner-desc {
  color: var(--text-sub);
}

.user-avatar-badge {
  background: linear-gradient(135deg, #10b981 0%, #0d9488 100%);
  border: 1px solid rgba(16, 185, 129, 0.5);
}

.badge-procedure {
  background-color: rgba(16, 185, 129, 0.15);
  color: #10b981;
  border: 1px solid rgba(16, 185, 129, 0.3);
}

.btn-refresh {
  background-color: var(--inner-bg);
  border: 1px solid var(--inner-border);
  color: var(--text-sub);
}

.btn-refresh:hover {
  color: var(--text-main);
  border-color: #10b981;
}

.stat-icon-wrapper {
  background-color: var(--inner-bg);
  border: 1px solid var(--inner-border);
}

/* TABS */
.tab-btn-active {
  background-color: #10b981;
  color: #ffffff;
  box-shadow: 0 4px 14px rgba(16, 185, 129, 0.3);
}

.tab-btn-inactive {
  background-color: var(--inner-bg);
  border: 1px solid var(--inner-border);
  color: var(--text-sub);
}

.tab-btn-inactive:hover {
  color: var(--text-main);
  border-color: #10b981;
}

.tab-badge {
  background-color: var(--inner-bg);
  color: var(--text-sub);
}

/* INPUTS */
.form-input,
.form-select {
  background-color: var(--input-bg);
  border: 1px solid var(--input-border);
  color: var(--text-main);
}

.theme-dark .form-input,
.theme-dark .form-select,
.theme-dark input[type="date"],
.theme-dark input[type="time"] {
  color-scheme: dark;
}

.theme-light .form-input,
.theme-light .form-select,
.theme-light input[type="date"],
.theme-light input[type="time"] {
  color-scheme: light;
}

/* Kalender & jam picker terlihat jelas/putih di mode gelap */
.theme-dark input[type="date"]::-webkit-calendar-picker-indicator,
.theme-dark input[type="time"]::-webkit-calendar-picker-indicator {
  cursor: pointer;
  filter: invert(1) brightness(1.2);
  opacity: 0.95;
  border-radius: 4px;
  padding: 2px 3px;
  transition: all 0.2s ease;
}

.theme-dark input[type="date"]::-webkit-calendar-picker-indicator:hover,
.theme-dark input[type="time"]::-webkit-calendar-picker-indicator:hover {
  opacity: 1;
  background-color: rgba(16, 185, 129, 0.25);
  transform: scale(1.1);
}

/* HISTORY CARDS */
.item-type-icon {
  background-color: var(--inner-bg);
  border: 1px solid var(--inner-border);
}

.badge-category {
  border: 1px solid transparent;
}

.badge-cat-atk {
  background-color: rgba(16, 185, 129, 0.15);
  color: #10b981;
  border-color: rgba(16, 185, 129, 0.3);
}

.badge-cat-mobil {
  background-color: rgba(6, 182, 212, 0.15);
  color: #06b6d4;
  border-color: rgba(6, 182, 212, 0.3);
}

.badge-cat-ruangan {
  background-color: rgba(168, 85, 247, 0.15);
  color: #c084fc;
  border-color: rgba(168, 85, 247, 0.3);
}

.card-footer-item,
.card-footer-note,
.card-divider {
  border-color: var(--divider);
}

/* TABLE STYLING */
.table-head-row {
  background-color: var(--table-head-bg);
  border-color: var(--divider);
}

.table-body-row {
  border-color: var(--divider);
}

.table-body-row:hover {
  background-color: var(--table-hover);
}

.badge-quantity {
  background-color: rgba(16, 185, 129, 0.15);
  color: #10b981;
  border: 1px solid rgba(16, 185, 129, 0.3);
}

.badge-status-done {
  background-color: rgba(16, 185, 129, 0.9);
  color: #ffffff;
  border-color: rgba(16, 185, 129, 0.5);
}

.badge-status-wait {
  background-color: rgba(245, 158, 11, 0.9);
  color: #ffffff;
  border-color: rgba(245, 158, 11, 0.5);
}

.badge-status-cyan {
  background-color: rgba(6, 182, 212, 0.9);
  color: #ffffff;
  border-color: rgba(6, 182, 212, 0.5);
}

.badge-status-reject {
  background-color: rgba(239, 68, 68, 0.9);
  color: #ffffff;
  border-color: rgba(239, 68, 68, 0.5);
}

.badge-plate {
  background-color: var(--inner-bg);
  color: var(--text-sub);
  border-color: var(--inner-border);
}

.btn-action {
  background-color: #10b981;
  color: #ffffff;
  box-shadow: 0 4px 14px rgba(16, 185, 129, 0.3);
}

.btn-action:hover {
  background-color: #059669;
}

/* KUOTA SPECIFIC */
.kuota-item-box {
  background-color: var(--inner-bg);
  border: 1px solid var(--inner-border);
}

.progress-track {
  background-color: var(--divider);
}
</style>
