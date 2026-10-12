<template>
  <div class="min-h-screen bg-slate-950 text-slate-100 font-sans pb-12">
    <!-- TOPBAR PENGEMUDI TERPADU (TIDAK TERLALU KE ATAS) -->
    <DriverTopbar />

    <main class="max-w-6xl mx-auto px-4 py-6 space-y-5">
      <!-- Notifikasi Alert -->
      <div
        v-if="alertMsg"
        class="p-3.5 rounded-xl border flex items-center justify-between text-xs transition"
        :class="alertMsg.type === 'error' ? 'bg-red-500/10 border-red-500/30 text-red-300' : 'bg-emerald-500/10 border-emerald-500/30 text-emerald-300'"
      >
        <span>{{ alertMsg.text }}</span>
        <button @click="alertMsg = null" class="text-slate-400 hover:text-white font-bold ml-2">✕</button>
      </div>

      <!-- KARTU UTAMA: KALENDER JADWAL -->
      <section class="bg-slate-900 border border-slate-800 rounded-2xl p-4 sm:p-6 shadow-xl space-y-4">
        
        <!-- TOOLBAR KONTROL ATAS -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-800">
          
          <!-- Navigasi Bulan & Tahun -->
          <div class="flex items-center justify-between sm:justify-start gap-2">
            <div class="flex items-center bg-slate-950 border border-slate-800 rounded-xl p-0.5 sm:p-1 flex-1 sm:flex-initial justify-between">
              <button
                @click="changeMonth(-1)"
                class="w-8 h-8 rounded-lg hover:bg-slate-800 flex items-center justify-center text-slate-300 hover:text-white font-bold text-sm transition"
                title="Bulan sebelumnya"
              >
                ‹
              </button>
              <span class="px-2 sm:px-3 text-xs sm:text-base font-bold text-white min-w-[120px] sm:min-w-[150px] text-center font-mono">
                {{ monthNames[currentMonth] }} {{ currentYear }}
              </span>
              <button
                @click="changeMonth(1)"
                class="w-8 h-8 rounded-lg hover:bg-slate-800 flex items-center justify-center text-slate-300 hover:text-white font-bold text-sm transition"
                title="Bulan berikutnya"
              >
                ›
              </button>
            </div>

            <button
              @click="goToToday"
              class="px-2.5 sm:px-3 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white text-xs font-semibold border border-slate-700 transition shrink-0"
            >
              Hari Ini
            </button>
          </div>

          <!-- Filter & Tampilan -->
          <div class="flex items-center justify-between sm:justify-end gap-1.5 sm:gap-2">
            <!-- Filter Kepemilikan -->
            <div class="flex items-center bg-slate-950 p-1 rounded-xl border border-slate-800 text-[11px] sm:text-xs flex-wrap gap-0.5">
              <button
                @click="driverFilter = 'all'"
                class="px-2 sm:px-3 py-1 rounded-lg font-semibold transition"
                :class="driverFilter === 'all' ? 'bg-slate-800 text-white font-bold shadow' : 'text-slate-400 hover:text-slate-200'"
              >
                Semua
              </button>
              <button
                @click="driverFilter = 'mine'"
                class="px-2 sm:px-3 py-1 rounded-lg font-semibold transition flex items-center gap-1"
                :class="driverFilter === 'mine' ? 'bg-emerald-500/20 text-emerald-400 font-bold border border-emerald-500/30' : 'text-slate-400 hover:text-slate-200'"
              >
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                <span>Tugas Saya</span>
              </button>
              <button
                @click="driverFilter = 'others'"
                class="px-2 sm:px-3 py-1 rounded-lg font-semibold transition flex items-center gap-1"
                :class="driverFilter === 'others' ? 'bg-blue-500/20 text-blue-400 font-bold border border-blue-500/30' : 'text-slate-400 hover:text-slate-200'"
              >
                <span class="w-1.5 h-1.5 rounded-full bg-blue-400"></span>
                <span>Driver Lain</span>
              </button>
              <button
                @click="driverFilter = 'pool'"
                class="px-2 sm:px-3 py-1 rounded-lg font-semibold transition flex items-center gap-1"
                :class="driverFilter === 'pool' ? 'bg-amber-500/20 text-amber-400 font-bold border border-amber-500/30' : 'text-slate-400 hover:text-slate-200'"
              >
                <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                <span>Pool</span>
              </button>
            </div>

            <!-- View Mode Switch -->
            <div class="flex items-center bg-slate-950 p-1 rounded-xl border border-slate-800 text-xs">
              <button
                @click="viewMode = 'calendar'"
                class="p-1 sm:px-2.5 sm:py-1 rounded-lg font-bold transition flex items-center gap-1"
                :class="viewMode === 'calendar' ? 'bg-slate-800 text-white shadow' : 'text-slate-400 hover:text-slate-200'"
                title="Tampilan Kalender"
              >
                <span>📅</span>
                <span class="hidden sm:inline text-xs">Kalender</span>
              </button>
              <button
                @click="viewMode = 'agenda'"
                class="p-1 sm:px-2.5 sm:py-1 rounded-lg font-bold transition flex items-center gap-1"
                :class="viewMode === 'agenda' ? 'bg-slate-800 text-white shadow' : 'text-slate-400 hover:text-slate-200'"
                title="Tampilan Daftar"
              >
                <span>📋</span>
                <span class="hidden sm:inline text-xs">Daftar</span>
              </button>
            </div>
          </div>
        </div>

        <!-- ============================================================= -->
        <!-- VIEW 1: KALENDER GRID BULANAN (BERSIH & TERTATA)              -->
        <!-- ============================================================= -->
        <div v-if="viewMode === 'calendar'" class="space-y-3 sm:space-y-4">
          <!-- Header Hari dalam Sepekan -->
          <div class="grid grid-cols-7 gap-1 sm:gap-2 text-center text-[10px] sm:text-xs font-bold uppercase tracking-wider text-slate-400 py-1">
            <span v-for="d in ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min']" :key="d">{{ d }}</span>
          </div>

          <!-- Kotak Tanggal -->
          <div class="grid grid-cols-7 gap-1 sm:gap-2">
            <div
              v-for="(day, index) in calendarDays"
              :key="index"
              @click="selectDate(day)"
              class="h-16 sm:min-h-[105px] p-1 sm:p-2.5 rounded-xl border transition flex flex-col justify-between cursor-pointer select-none relative group"
              :class="getDayCellClass(day)"
            >
              <!-- Baris Atas: Nomor Tanggal & Badges -->
              <div class="flex items-center justify-between">
                <span
                  class="text-[11px] sm:text-sm font-bold font-mono inline-flex items-center justify-center rounded-lg px-1"
                  :class="[
                    day.isToday ? 'w-5 h-5 sm:w-6 sm:h-6 rounded-full bg-cyan-500 text-slate-950 font-black' :
                    (day.hasMyEvents ? 'text-emerald-300 font-black' :
                    (day.hasOtherEvents ? 'text-blue-300 font-bold' :
                    (day.isCurrentMonth ? 'text-slate-200' : 'text-slate-600')))
                  ]"
                >
                  {{ day.dayNumber }}
                </span>

                <!-- Counter Desktop & Status Selesai Mini -->
                <div v-if="day.events.length > 0" class="hidden sm:flex items-center gap-1">
                  <span
                    v-if="day.allCompleted"
                    class="px-1 py-0.2 rounded text-[8px] font-bold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30"
                    title="Semua trip tanggal ini selesai"
                  >
                    ✓ Selesai
                  </span>
                  <span
                    class="px-1.5 py-0.5 rounded-md text-[10px] font-mono font-bold"
                    :class="day.hasMyEvents ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' : (day.hasOtherEvents ? 'bg-blue-500/20 text-blue-300 border border-blue-500/30' : 'bg-slate-800 text-slate-300')"
                  >
                    {{ day.events.length }}
                  </span>
                </div>
              </div>

              <!-- List Mini Event (Rapi & Tidak Berantakan) -->
              <div class="my-0.5 sm:my-1 flex-1 overflow-hidden flex flex-col justify-center">
                <!-- Mobile: Dot Indikator Bersih (Tidak Pernah Overflow!) -->
                <div class="sm:hidden flex items-center justify-center gap-1 flex-wrap">
                  <span
                    v-for="(evt, eIdx) in day.events.slice(0, 3)"
                    :key="eIdx"
                    class="w-1.5 h-1.5 rounded-full shrink-0"
                    :class="getEventDotClass(evt)"
                  ></span>
                  <span v-if="day.events.length > 3" class="text-[8px] text-slate-400 font-mono">+</span>
                </div>

                <!-- Desktop: Chip Rapi -->
                <div class="hidden sm:block space-y-1">
                  <div
                    v-for="(evt, eIdx) in day.events.slice(0, 2)"
                    :key="eIdx"
                    class="px-1.5 py-0.5 rounded text-[10px] truncate leading-tight font-medium flex items-center gap-1 border"
                    :class="getEventBadgeClass(evt)"
                  >
                    <span class="w-1.5 h-1.5 rounded-full shrink-0" :class="getEventDotClass(evt)"></span>
                    <span class="font-mono font-bold shrink-0 text-[9px]">{{ evt.timeStr }}</span>
                    <span class="truncate">
                      <template v-if="evt.isOtherDriver">
                        <span class="text-blue-200 font-semibold">{{ evt.booking.driver?.name || evt.booking.driver_name || 'Driver' }}:</span>
                      </template>
                      {{ evt.booking.destination }}
                    </span>
                    <span v-if="evt.isCompleted" class="text-[8px] font-bold text-emerald-400 shrink-0 ml-auto">✓</span>
                  </div>

                  <div v-if="day.events.length > 2" class="text-[9px] text-slate-400 font-bold pl-1">
                    +{{ day.events.length - 2 }} lainnya
                  </div>
                </div>
              </div>

              <!-- Status Ringkas Bawah -->
              <div class="hidden sm:block text-[9px] truncate">
                <span v-if="day.hasMyEvents && day.hasOtherEvents" class="text-emerald-400 font-semibold">● Anda &amp; Driver Lain</span>
                <span v-else-if="day.hasMyEvents" class="text-emerald-400 font-semibold">● Tugas Anda</span>
                <span v-else-if="day.hasOtherEvents" class="text-blue-400 font-semibold">● Driver Lain</span>
                <span v-else-if="day.hasPoolEvents" class="text-amber-400 font-semibold">● Pool</span>
                <span v-else>&nbsp;</span>
              </div>
            </div>
          </div>

          <!-- Petunjuk Warna (Legend) -->
          <div class="flex items-center justify-center sm:justify-start gap-2.5 sm:gap-4 text-[11px] sm:text-xs text-slate-400 pt-2 border-t border-slate-800/60 flex-wrap">
            <span class="flex items-center gap-1.5">
              <span class="w-2.5 h-2.5 rounded bg-emerald-500/30 border border-emerald-400"></span>
              <span class="text-emerald-300 font-medium">Tugas Anda</span>
            </span>
            <span class="flex items-center gap-1.5">
              <span class="w-2.5 h-2.5 rounded bg-blue-500/30 border border-blue-400"></span>
              <span class="text-blue-300 font-medium">Driver Lain</span>
            </span>
            <span class="flex items-center gap-1.5">
              <span class="w-2.5 h-2.5 rounded bg-amber-500/30 border border-amber-400"></span>
              <span class="text-amber-300 font-medium">Pool</span>
            </span>
            <span class="flex items-center gap-1.5">
              <span class="px-1.5 py-0.2 rounded text-[9px] font-bold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">✓ Selesai</span>
              <span>Trip Selesai</span>
            </span>
            <span class="flex items-center gap-1.5">
              <span class="w-2.5 h-2.5 rounded-full bg-cyan-400"></span>
              <span>Hari Ini</span>
            </span>
            <span class="flex items-center gap-1.5">
              <span class="w-2.5 h-2.5 rounded border-2 border-amber-400"></span>
              <span>Dipilih</span>
            </span>
          </div>

          <!-- ============================================================= -->
          <!-- AGENDA HARI TERPILIH (TIMELINE RAPI DI BAWAH KALENDER)        -->
          <!-- ============================================================= -->
          <div v-if="selectedDay" class="pt-4 border-t border-slate-800 space-y-3">
            <div class="flex items-center justify-between flex-wrap gap-2">
              <div class="flex items-center gap-2">
                <span class="text-xs font-bold text-amber-400">📅 Agenda:</span>
                <strong class="text-xs sm:text-base text-white font-mono">{{ formatIndonesianFullDate(selectedDay.date) }}</strong>
                <span v-if="isDateToday(selectedDay.date)" class="px-1.5 py-0.2 rounded-full text-[9px] font-black bg-cyan-500 text-slate-950">
                  HARI INI
                </span>
              </div>
              <span class="text-xs text-slate-400 font-semibold">
                {{ selectedDay.events.length }} Jadwal
              </span>
            </div>

            <!-- List Kunjungan pada Tanggal Terpilih -->
            <div v-if="selectedDay.events.length === 0" class="p-5 rounded-xl bg-slate-950/40 border border-slate-800 text-center space-y-2">
              <p class="text-xs text-slate-400">Tidak ada jadwal perjalanan dinas pada tanggal ini.</p>
              <button
                @click="goToToday"
                class="px-3 py-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-xs text-slate-300 hover:text-white transition"
              >
                Kembali ke Hari Ini
              </button>
            </div>

            <div v-else class="space-y-3">
              <div
                v-for="(evt, idx) in selectedDay.events"
                :key="idx"
                class="p-4 rounded-xl border bg-slate-950/70 transition flex flex-col md:flex-row md:items-center justify-between gap-3.5 hover:border-slate-700"
                :class="[
                  evt.isMine ? 'border-emerald-500/40 bg-emerald-950/15' :
                  (evt.isOtherDriver ? 'border-blue-500/35 bg-blue-950/20' : 'border-slate-800'),
                  evt.isCompleted ? 'ring-1 ring-emerald-500/20' : ''
                ]"
              >
                <!-- Waktu & Rincian Jadwal -->
                <div class="flex items-start gap-3.5 flex-1">
                  <div class="px-3 py-2 rounded-xl bg-slate-900 border border-slate-800 text-center shrink-0 font-mono shadow">
                    <span class="block text-xs sm:text-sm font-black text-amber-300">{{ evt.timeStr }}</span>
                    <span class="block text-[10px] text-slate-400 mt-0.5">WIB</span>
                  </div>

                  <div class="space-y-1.5 flex-1">
                    <div class="flex items-center gap-2 flex-wrap">
                      <span
                        class="px-2.5 py-0.5 rounded text-xs font-bold"
                        :class="(evt.booking.is_routine || evt.booking.booking_type === 'routine') ? 'bg-cyan-500/20 text-cyan-300 border border-cyan-500/30' : 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30'"
                      >
                        {{ (evt.booking.is_routine || evt.booking.booking_type === 'routine') ? '🔄 Rutin' : '🚗 Sekali Jalan' }}
                      </span>

                      <!-- Badge Driver: Menampilkan Anda / Nama Driver Lain / Pool -->
                      <span
                        class="px-2.5 py-0.5 rounded text-xs font-bold"
                        :class="[
                          evt.isMine ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' :
                          (evt.isOtherDriver ? 'bg-blue-500/20 text-blue-300 border border-blue-500/30 font-semibold' :
                          'bg-amber-500/20 text-amber-300 border border-amber-500/30')
                        ]"
                      >
                        {{ evt.isMine ? '👨‍✈️ Anda (Tugas Saya)' : (evt.booking.driver?.name || evt.booking.driver_name ? `👨‍✈️ Driver: ${evt.booking.driver?.name || evt.booking.driver_name}` : '🏢 Pool (Belum Ada Sopir)') }}
                      </span>

                      <!-- Badge Status Perjalanan -->
                      <span
                        class="px-2.5 py-0.5 rounded text-xs font-bold border"
                        :class="[
                          evt.booking.status === 'completed' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/40' :
                          (evt.booking.status === 'in_progress' ? 'bg-cyan-500/20 text-cyan-300 border border-cyan-500/40' :
                          'bg-slate-800 text-slate-300 border-slate-700')
                        ]"
                      >
                        {{ evt.booking.status === 'completed' ? '✓ Selesai' : (evt.booking.status === 'in_progress' ? '🔄 Sedang Jalan' : '⏳ Terjadwal') }}
                      </span>

                      <span class="text-xs text-cyan-400 font-mono font-bold">
                        {{ evt.booking.vehicle?.license_plate || 'Armada Pool' }}
                      </span>
                    </div>

                    <h4 class="font-bold text-xs sm:text-sm text-white leading-relaxed">
                      📍 {{ evt.booking.destination }}
                    </h4>

                    <p class="text-xs text-slate-300 leading-relaxed">
                      Pemesan: <span class="text-white font-semibold">{{ evt.booking.borrower_name || evt.booking.requester_name }}</span>
                      <span class="text-slate-400"> ({{ evt.booking.borrower_division || evt.booking.requester_division || 'Umum' }})</span>
                      <span v-if="evt.booking.passengers_count" class="text-slate-400 ml-1">
                        • {{ evt.booking.passengers_count }} penumpang
                      </span>
                    </p>

                    <!-- Note Selesai di Setiap Kartu Trip yang Sudah Selesai -->
                    <div
                      v-if="evt.booking.status === 'completed'"
                      class="mt-2 p-2.5 rounded-lg bg-emerald-950/50 border border-emerald-500/30 text-emerald-300 text-xs flex items-center justify-between flex-wrap gap-2"
                    >
                      <div class="flex items-center gap-1.5 font-medium">
                        <span class="w-4 h-4 rounded-full bg-emerald-500/20 border border-emerald-500/40 flex items-center justify-center text-[10px] font-bold text-emerald-400">✓</span>
                        <span class="text-emerald-200">Trip perjalanan dinas ini telah selesai dilaksanakan</span>
                      </div>
                      <span v-if="evt.booking.end_date" class="text-emerald-400/90 font-mono text-[11px] font-semibold">
                        Kembali: {{ formatIndonesianTime(evt.booking.end_date) }} WIB
                      </span>
                    </div>
                  </div>
                </div>

                <!-- Aksi Rapi -->
                <div class="flex items-center gap-2 shrink-0 self-end md:self-center w-full md:w-auto pt-2.5 md:pt-0 border-t md:border-t-0 border-slate-800/80">
                  <a
                    v-if="getGoogleMapsLink(evt.booking)"
                    :href="getGoogleMapsLink(evt.booking)"
                    target="_blank"
                    rel="noopener"
                    class="flex-1 md:flex-initial px-3.5 py-2 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs transition flex items-center justify-center gap-1.5 shadow leading-normal"
                  >
                    <span>🧭</span>
                    <span>Maps</span>
                  </a>

                  <button
                    v-if="!evt.booking.driver_id && !evt.booking.driver_name"
                    @click="acceptRoutineOrder(evt.booking)"
                    class="flex-1 md:flex-initial px-3.5 py-2 rounded-xl bg-amber-600 hover:bg-amber-500 text-white font-bold text-xs transition shadow text-center leading-normal"
                  >
                    Ambil Tugas
                  </button>

                  <button
                    @click="openDetailModal(evt.booking)"
                    class="flex-1 md:flex-initial px-3.5 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white font-semibold text-xs border border-slate-700 transition text-center leading-normal"
                  >
                    Rincian
                  </button>
                </div>
              </div>

              <!-- Note Selesai Rangkuman di Bagian Bawah Kalender / Agenda -->
              <div
                v-if="selectedDayHasCompleted"
                class="mt-3.5 p-3.5 rounded-xl bg-emerald-950/30 border border-emerald-500/30 text-emerald-200 text-xs flex items-center justify-between flex-wrap gap-2 shadow-sm"
              >
                <div class="flex items-center gap-2">
                  <span class="text-base">🏁</span>
                  <div>
                    <strong class="text-white block sm:inline">Catatan Selesai:</strong>
                    <span class="text-emerald-300 ml-0 sm:ml-1">
                      {{ selectedDayCompletedCount }} dari {{ selectedDay.events.length }} perjalanan pada tanggal ini telah selesai dilaksanakan.
                    </span>
                  </div>
                </div>
                <span class="px-2.5 py-1 rounded-lg bg-emerald-500/20 text-emerald-300 font-bold font-mono text-[11px] border border-emerald-500/30">
                  Status: Selesai ✓
                </span>
              </div>
            </div>
          </div>
        </div>

        <!-- ============================================================= -->
        <!-- VIEW 2: DAFTAR AGENDA MENDATANG (LIST RAPI)                   -->
        <!-- ============================================================= -->
        <div v-else class="space-y-3">
          <div v-if="upcomingAgendaList.length === 0" class="p-8 rounded-xl bg-slate-950/40 border border-slate-800 text-center text-xs text-slate-400 leading-relaxed">
            Tidak ada agenda kunjungan terjadwal yang tercatat di database untuk 30 hari ke depan.
          </div>

          <div v-else class="space-y-3">
            <div
              v-for="(item, aIdx) in upcomingAgendaList"
              :key="aIdx"
              class="p-4 sm:p-5 rounded-xl border bg-slate-950/60 flex flex-col md:flex-row md:items-center justify-between gap-3.5 transition hover:border-slate-700"
              :class="[
                item.isMine ? 'border-emerald-500/40 bg-emerald-950/15' :
                (item.isOtherDriver ? 'border-blue-500/35 bg-blue-950/20' : 'border-slate-800'),
                item.isCompleted ? 'ring-1 ring-emerald-500/20' : ''
              ]"
            >
              <!-- Info Tanggal & Waktu -->
              <div class="flex items-start sm:items-center gap-3.5">
                <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-xl bg-slate-900 border border-slate-800 flex flex-col items-center justify-center font-mono shrink-0 shadow">
                  <span class="text-[10px] text-cyan-400 font-bold uppercase tracking-wider">{{ getShortDayName(item.date) }}</span>
                  <span class="text-base sm:text-lg font-black text-white leading-none mt-0.5">{{ item.date.getDate() }}</span>
                </div>

                <div class="space-y-1.5 flex-1">
                  <div class="flex items-center gap-2 flex-wrap">
                    <span class="text-xs sm:text-sm font-bold text-white">{{ formatIndonesianDate(item.date) }}</span>
                    <span v-if="isDateToday(item.date)" class="px-2 py-0.5 rounded text-[10px] font-black bg-cyan-500 text-slate-950">HARI INI</span>
                    <span class="text-xs text-amber-300 font-mono font-bold">{{ item.timeStr }} WIB</span>

                    <!-- Badge Driver -->
                    <span
                      class="px-2 py-0.5 rounded text-[11px] font-bold"
                      :class="item.isMine ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : (item.isOtherDriver ? 'bg-blue-500/20 text-blue-300 border border-blue-500/30' : 'bg-amber-500/20 text-amber-300 border border-amber-500/30')"
                    >
                      {{ item.isMine ? '👨‍✈️ Anda' : (item.booking.driver?.name || item.booking.driver_name ? `👨‍✈️ ${item.booking.driver?.name || item.booking.driver_name}` : '🏢 Pool') }}
                    </span>

                    <!-- Badge Selesai -->
                    <span
                      v-if="item.booking.status === 'completed'"
                      class="px-2 py-0.5 rounded text-[11px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/40"
                    >
                      ✓ Selesai
                    </span>
                  </div>

                  <h4 class="font-bold text-xs sm:text-sm text-slate-100 leading-relaxed">
                    📍 {{ item.booking.destination }}
                  </h4>

                  <p class="text-xs text-slate-400 leading-relaxed">
                    Pemesan: <span class="text-slate-200">{{ item.booking.borrower_name || item.booking.requester_name }}</span>
                    ({{ item.booking.borrower_division || item.booking.requester_division || 'Umum' }}) • Mobil: <span class="text-cyan-400 font-mono">{{ item.booking.vehicle?.license_plate || 'Pool' }}</span>
                  </p>

                  <div
                    v-if="item.booking.status === 'completed'"
                    class="text-[11px] text-emerald-300/90 font-medium flex items-center gap-1.5 pt-0.5"
                  >
                    <span>✓</span>
                    <span>Telah selesai dilaksanakan</span>
                    <span v-if="item.booking.end_date" class="font-mono text-slate-400">
                      (Kembali: {{ formatIndonesianTime(item.booking.end_date) }} WIB)
                    </span>
                  </div>
                </div>
              </div>

              <!-- Aksi -->
              <div class="flex items-center gap-2 shrink-0 self-end md:self-center w-full md:w-auto pt-2.5 md:pt-0 border-t md:border-t-0 border-slate-800/80">
                <a
                  v-if="getGoogleMapsLink(item.booking)"
                  :href="getGoogleMapsLink(item.booking)"
                  target="_blank"
                  rel="noopener"
                  class="flex-1 md:flex-initial px-3.5 py-2 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs transition flex items-center justify-center gap-1.5 leading-normal"
                >
                  🧭 Maps
                </a>

                <button
                  @click="openDetailModal(item.booking)"
                  class="flex-1 md:flex-initial px-3.5 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold border border-slate-700 transition leading-normal text-center"
                >
                  Rincian
                </button>
              </div>
            </div>
          </div>
        </div>
      </section>
    </main>

    <!-- MODAL DETAIL LENGKAP JADWAL -->
    <div
      v-if="showDetailModal && activeModalBooking"
      class="fixed inset-0 z-50 bg-black/80 backdrop-blur-sm flex items-center justify-center p-4"
      @click.self="showDetailModal = false"
    >
      <div class="bg-slate-900 border border-slate-800 w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[85vh]">
        <!-- Header Modal -->
        <div class="p-4 bg-slate-800/90 border-b border-slate-700 flex items-center justify-between">
          <h3 class="font-bold text-sm sm:text-base text-white flex items-center gap-2 leading-snug">
            <span>🗓️</span>
            <span>Rincian Jadwal Perjalanan</span>
          </h3>
          <button @click="showDetailModal = false" class="w-8 h-8 rounded-lg bg-slate-700 hover:bg-slate-600 text-slate-300 hover:text-white text-xs flex items-center justify-center transition">✕</button>
        </div>

        <!-- Isi Modal Rapi & Bernapas Bebas (Tidak Dempet) -->
        <div class="p-5 overflow-y-auto space-y-3 text-xs text-slate-300">
          <div class="p-3.5 rounded-xl bg-slate-950 border border-slate-800 space-y-1">
            <span class="text-xs text-slate-400 block uppercase font-bold tracking-wider">Pola Jadwal:</span>
            <strong class="text-white text-xs sm:text-sm block leading-relaxed">{{ activeModalBooking.routine_schedule || 'Kunjungan Terjadwal' }}</strong>
          </div>

          <div class="p-3.5 rounded-xl bg-slate-950 border border-slate-800 space-y-2.5">
            <div v-if="activeModalBooking.pickup_location" class="space-y-0.5">
              <span class="text-slate-400 text-xs block">Titik Jemput:</span>
              <strong class="text-emerald-300 text-xs sm:text-sm block leading-relaxed">{{ activeModalBooking.pickup_location }}</strong>
            </div>
            <div class="space-y-0.5">
              <span class="text-slate-400 text-xs block">Lokasi Tujuan:</span>
              <strong class="text-amber-300 text-xs sm:text-sm block leading-relaxed">{{ activeModalBooking.destination }}</strong>
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div class="p-3.5 rounded-xl bg-slate-950 border border-slate-800 space-y-1">
              <span class="text-slate-400 text-xs block">Pemesan:</span>
              <strong class="text-white text-xs sm:text-sm block leading-relaxed">{{ activeModalBooking.borrower_name || activeModalBooking.requester_name }}</strong>
              <small class="block text-slate-400 leading-normal">{{ activeModalBooking.borrower_division || 'Operasional' }}</small>
            </div>
            <div class="p-3.5 rounded-xl bg-slate-950 border border-slate-800 space-y-1">
              <span class="text-slate-400 text-xs block">Mobil & Plat:</span>
              <strong class="text-white text-xs sm:text-sm block leading-relaxed">{{ activeModalBooking.vehicle?.name || 'Mobil Pool' }}</strong>
              <small class="block text-cyan-400 font-mono leading-normal">{{ activeModalBooking.vehicle?.license_plate || '-' }}</small>
            </div>
          </div>

          <!-- Info Driver / Pengemudi yang Ditugaskan -->
          <div class="p-3.5 rounded-xl bg-slate-950 border border-slate-800 space-y-1">
            <span class="text-slate-400 text-xs block">Pengemudi / Driver:</span>
            <strong
              class="text-xs sm:text-sm block leading-relaxed"
              :class="isModalBookingMine ? 'text-emerald-400' : (activeModalBooking.driver_name || activeModalBooking.driver?.name ? 'text-blue-400' : 'text-amber-400')"
            >
              {{ activeModalBooking.driver?.name || activeModalBooking.driver_name || '🏢 Armada Pool (Belum Ditugaskan)' }}
            </strong>
            <small class="block text-slate-400 leading-normal">
              {{ isModalBookingMine ? '👨‍✈️ Ditugaskan ke Anda' : (activeModalBooking.driver_name || activeModalBooking.driver?.name ? '👨‍✈️ Driver Lain' : 'Terbuka untuk diambil seluruh pengemudi') }}
            </small>
          </div>

          <!-- Note Selesai di Modal Jika Trip Telah Selesai -->
          <div v-if="activeModalBooking.status === 'completed'" class="p-3.5 rounded-xl bg-emerald-950/40 border border-emerald-500/30 text-emerald-300 space-y-1">
            <span class="text-emerald-400 text-xs font-bold block flex items-center gap-1.5">
              <span>✅</span>
              <span>Status: Perjalanan Telah Selesai</span>
            </span>
            <p v-if="activeModalBooking.end_date" class="text-xs text-slate-300">
              Mobil telah kembali ke kantor pada: <strong class="text-emerald-300 font-mono">{{ formatIndonesianFullDate(activeModalBooking.end_date) }} {{ formatIndonesianTime(activeModalBooking.end_date) }} WIB</strong>
            </p>
          </div>
        </div>

        <!-- Footer Modal -->
        <div class="p-4 bg-slate-800/80 border-t border-slate-700 flex items-center justify-between gap-3">
          <a
            v-if="getGoogleMapsLink(activeModalBooking)"
            :href="getGoogleMapsLink(activeModalBooking)"
            target="_blank"
            rel="noopener"
            class="px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs transition flex items-center gap-1.5 leading-normal"
          >
            <span>🧭 Buka di Google Maps</span>
          </a>

          <button
            @click="showDetailModal = false"
            class="px-4 py-2.5 rounded-xl bg-slate-700 hover:bg-slate-600 text-white font-bold text-xs transition ml-auto leading-normal"
          >
            Tutup
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'

useHead({
  title: 'Kalender Jadwal Supir - SIMPEL PKL-Digo'
})

const config = useRuntimeConfig()
const apiBase = config.public?.apiBase || 'http://127.0.0.1:8000/api'

// User State
const userCookie = useCookie('auth_user')
const tokenCookie = useCookie('auth_token')
const currentUser = ref(null)

// Data State
const routineBookings = ref([])
const incidentalBookings = ref([])
const summaryStats = ref({
  total_routines: 0,
  my_routines: 0,
  pool_routines: 0
})
const isLoading = ref(false)
const alertMsg = ref(null)

// Filter & View State
const viewMode = ref('calendar') // 'calendar' | 'agenda'
const driverFilter = ref('all') // 'all' | 'mine' | 'pool'
const selectedDate = ref(null)

// Modal State
const showDetailModal = ref(false)
const activeModalBooking = ref(null)

// Kalender State
const today = new Date()
const currentYear = ref(today.getFullYear())
const currentMonth = ref(today.getMonth()) // 0 - 11

const monthNames = [
  'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
  'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
]

const dayNameMap = {
  0: 'Minggu',
  1: 'Senin',
  2: 'Selasa',
  3: 'Rabu',
  4: 'Kamis',
  5: 'Jumat',
  6: 'Sabtu'
}

const getShortDayName = (date) => {
  const shorts = ['MIN', 'SEN', 'SEL', 'RAB', 'KAM', 'JUM', 'SAB']
  return shorts[date.getDay()] || ''
}

const formatIndonesianDate = (d) => {
  if (!d) return '-'
  const dateObj = typeof d === 'string' ? new Date(d) : d
  return dateObj.toLocaleDateString('id-ID', {
    day: 'numeric',
    month: 'short',
    year: 'numeric'
  })
}

const formatIndonesianFullDate = (d) => {
  if (!d) return '-'
  const dateObj = typeof d === 'string' ? new Date(d) : d
  return dateObj.toLocaleDateString('id-ID', {
    weekday: 'long',
    day: 'numeric',
    month: 'long',
    year: 'numeric'
  })
}

const isDateToday = (d) => {
  const now = new Date()
  return (
    d.getDate() === now.getDate() &&
    d.getMonth() === now.getMonth() &&
    d.getFullYear() === now.getFullYear()
  )
}

const changeMonth = (delta) => {
  let newMonth = currentMonth.value + delta
  if (newMonth < 0) {
    currentMonth.value = 11
    currentYear.value--
  } else if (newMonth > 11) {
    currentMonth.value = 0
    currentYear.value++
  } else {
    currentMonth.value = newMonth
  }
}

const goToToday = () => {
  const now = new Date()
  currentYear.value = now.getFullYear()
  currentMonth.value = now.getMonth()
  selectedDate.value = new Date(now.getFullYear(), now.getMonth(), now.getDate())
}

// Ambil Data Murni dari Database
const fetchCalendarData = async () => {
  isLoading.value = true
  const headers = {}
  if (tokenCookie.value) {
    headers['Authorization'] = `Bearer ${tokenCookie.value}`
  }

  const myId = currentUser.value?.id
  const myName = currentUser.value?.name
  const q = new URLSearchParams()
  if (myId) q.append('driver_id', myId)
  if (myName) q.append('driver_name', myName)

  try {
    const res = await $fetch(`${apiBase}/driver/routine-bookings?${q.toString()}`, { headers })
    if (res?.status === 'success') {
      routineBookings.value = res.data || []
      incidentalBookings.value = res.incidentals || []
      if (res.summary) {
        summaryStats.value = {
          total_routines: (res.summary.total_routines || 0) + (res.incidentals?.length || 0),
          my_routines: (res.summary.my_routines || 0) + (res.incidentals?.length || 0),
          pool_routines: res.summary.pool_routines || 0
        }
      }
    }
  } catch (err) {
    console.warn('Gagal memuat jadwal kalender:', err)
  } finally {
    isLoading.value = false
  }
}

// Filtered Bookings Berdasarkan Driver
const filteredRoutineBookings = computed(() => {
  let list = [...(routineBookings.value || []), ...(incidentalBookings.value || [])]

  // Hilangkan duplikasi jika ada id booking yang beririsan
  const map = new Map()
  list.forEach(item => {
    if (item?.id) {
      map.set(item.id, item)
    }
  })
  list = Array.from(map.values())

  const myId = currentUser.value?.id
  const myName = currentUser.value?.name?.toLowerCase()

  if (driverFilter.value === 'mine') {
    list = list.filter(b => {
      const matchId = myId && Number(b.driver_id) === Number(myId)
      const matchName = myName && b.driver_name && b.driver_name.toLowerCase().includes(myName)
      return matchId || matchName
    })
  } else if (driverFilter.value === 'others') {
    list = list.filter(b => {
      const matchMine = (myId && Number(b.driver_id) === Number(myId)) ||
                        (myName && b.driver_name && b.driver_name.toLowerCase().includes(myName))
      const isPool = !b.driver_id && (!b.driver_name || b.driver_name.trim() === '')
      return !matchMine && !isPool
    })
  } else if (driverFilter.value === 'pool') {
    list = list.filter(b => !b.driver_id && (!b.driver_name || b.driver_name.trim() === ''))
  }

  return list
})

const formatIndonesianTime = (d) => {
  if (!d) return '-'
  try {
    const dateObj = typeof d === 'string' ? new Date(d) : d
    const h = String(dateObj.getHours()).padStart(2, '0')
    const m = String(dateObj.getMinutes()).padStart(2, '0')
    return `${h}:${m}`
  } catch (e) {
    return '-'
  }
}

const extractBookingTime = (b) => {
  if (b.start_date) {
    try {
      const d = new Date(b.start_date)
      const h = String(d.getHours()).padStart(2, '0')
      const m = String(d.getMinutes()).padStart(2, '0')
      if (h !== '00' || m !== '00') return `${h}:${m}`
    } catch (e) {}
  }
  if (b.routine_schedule) {
    const match = b.routine_schedule.match(/(\d{1,2}[:.]\d{2})/i)
    if (match) return match[1].replace('.', ':')
  }
  return '08:30'
}

const isBookingActiveOnDate = (booking, date) => {
  const dStart = booking.start_date ? new Date(booking.start_date) : null
  const dEnd = booking.end_date ? new Date(booking.end_date) : null

  if (dStart) {
    const startZero = new Date(dStart.getFullYear(), dStart.getMonth(), dStart.getDate())
    if (date < startZero) return false
  }
  if (dEnd) {
    const endMidnight = new Date(dEnd.getFullYear(), dEnd.getMonth(), dEnd.getDate(), 23, 59, 59)
    if (date > endMidnight) return false
  }

  if (!booking.is_routine && booking.booking_type !== 'routine') {
    if (!dStart) return false
    return (
      date.getDate() === dStart.getDate() &&
      date.getMonth() === dStart.getMonth() &&
      date.getFullYear() === dStart.getFullYear()
    )
  }

  const freq = (booking.recurring_frequency || 'weekly').toLowerCase()
  const daysStr = (booking.recurring_days || booking.routine_schedule || '').toLowerCase()
  const dayNameIndo = dayNameMap[date.getDay()]?.toLowerCase() || ''

  if (freq === 'daily' || daysStr.includes('harian') || daysStr.includes('senin - jumat')) {
    return date.getDay() >= 1 && date.getDay() <= 5
  }

  if (freq === 'weekly' || freq === 'biweekly') {
    const matchesDay = daysStr.includes(dayNameIndo)
    if (!matchesDay) return false

    if (freq === 'biweekly' && dStart) {
      const diffMs = date.getTime() - dStart.getTime()
      const diffWeeks = Math.floor(diffMs / (7 * 24 * 60 * 60 * 1000))
      if (diffWeeks % 2 !== 0) return false
    }
    return true
  }

  if (freq === 'monthly') {
    const targetDay = dStart ? dStart.getDate() : 1
    return date.getDate() === targetDay
  }

  return daysStr.includes(dayNameIndo)
}

const calendarDays = computed(() => {
  const year = currentYear.value
  const month = currentMonth.value

  const firstDayOfMonth = new Date(year, month, 1)
  const lastDayOfMonth = new Date(year, month + 1, 0)

  let startDayOfWeek = firstDayOfMonth.getDay()
  let prevMonthDaysNeeded = startDayOfWeek === 0 ? 6 : startDayOfWeek - 1

  const days = []

  const prevMonthLastDate = new Date(year, month, 0).getDate()
  for (let i = prevMonthDaysNeeded - 1; i >= 0; i--) {
    const d = new Date(year, month - 1, prevMonthLastDate - i)
    days.push(buildDayObject(d, false))
  }

  for (let d = 1; d <= lastDayOfMonth.getDate(); d++) {
    const curDate = new Date(year, month, d)
    days.push(buildDayObject(curDate, true))
  }

  const remainingDays = 42 - days.length
  for (let i = 1; i <= remainingDays; i++) {
    const nextDate = new Date(year, month + 1, i)
    days.push(buildDayObject(nextDate, false))
  }

  return days
})

const buildDayObject = (date, isCurrentMonth) => {
  const now = new Date()
  const isToday = (
    date.getDate() === now.getDate() &&
    date.getMonth() === now.getMonth() &&
    date.getFullYear() === now.getFullYear()
  )

  const myId = currentUser.value?.id
  const myName = currentUser.value?.name?.toLowerCase()

  const events = []
  filteredRoutineBookings.value.forEach(b => {
    if (isBookingActiveOnDate(b, date)) {
      const isMine = (myId && Number(b.driver_id) === Number(myId)) ||
                     (myName && b.driver_name && b.driver_name.toLowerCase().includes(myName))
      const isPool = !b.driver_id && (!b.driver_name || b.driver_name.trim() === '')
      const isOtherDriver = !isMine && !isPool
      const isCompleted = b.status === 'completed'
      events.push({
        booking: b,
        timeStr: extractBookingTime(b),
        isMine,
        isPool,
        isOtherDriver,
        isCompleted
      })
    }
  })

  events.sort((a, b) => a.timeStr.localeCompare(b.timeStr))

  return {
    date,
    dayNumber: date.getDate(),
    isCurrentMonth,
    isToday,
    events,
    hasMyEvents: events.some(e => e.isMine),
    hasOtherEvents: events.some(e => e.isOtherDriver),
    hasPoolEvents: events.some(e => e.isPool),
    hasCompletedEvents: events.some(e => e.isCompleted),
    allCompleted: events.length > 0 && events.every(e => e.isCompleted)
  }
}

const getDayCellClass = (day) => {
  const classes = []

  // Status bulan
  if (!day.isCurrentMonth) {
    classes.push('bg-slate-950/20 border-slate-900/60 text-slate-600 opacity-40')
  } else {
    // Highlight warna tanggal berdasarkan jenis aktivitas:
    if (day.hasMyEvents && day.hasOtherEvents) {
      // Ada tugas saya dan driver lain
      classes.push('bg-gradient-to-br from-emerald-950/30 to-blue-950/30 border-emerald-500/50 hover:border-emerald-400 shadow-sm shadow-emerald-500/5')
    } else if (day.hasMyEvents) {
      // Highlight Hijau Emerald untuk Tugas Anda
      classes.push('bg-emerald-950/25 border-emerald-500/50 hover:border-emerald-400 shadow-sm shadow-emerald-500/5')
    } else if (day.hasOtherEvents) {
      // Highlight Biru untuk Aktivitas Driver Lain
      classes.push('bg-blue-950/20 border-blue-500/40 hover:border-blue-400 shadow-sm shadow-blue-500/5')
    } else if (day.hasPoolEvents) {
      // Highlight Kuning Amber untuk Tugas Pool
      classes.push('bg-amber-950/20 border-amber-500/40 hover:border-amber-400 shadow-sm shadow-amber-500/5')
    } else {
      // Hari biasa tanpa jadwal
      classes.push('bg-slate-950/60 border-slate-800 hover:border-slate-700')
    }
  }

  // Highlight Hari Ini
  if (day.isToday) {
    classes.push('ring-1 ring-cyan-500/70 border-cyan-500/80')
  }

  // Highlight Tanggal Terpilih (Ketika tanggal di klik)
  if (isSelectedDate(day)) {
    classes.push('ring-2 ring-amber-400 border-amber-400 bg-slate-900/90 shadow-lg shadow-amber-500/10 z-10')
  }

  return classes.join(' ')
}

const selectDate = (day) => {
  selectedDate.value = day.date
}

const isSelectedDate = (day) => {
  if (!selectedDate.value) return false
  return (
    day.date.getDate() === selectedDate.value.getDate() &&
    day.date.getMonth() === selectedDate.value.getMonth() &&
    day.date.getFullYear() === selectedDate.value.getFullYear()
  )
}

const selectedDay = computed(() => {
  const target = selectedDate.value || new Date()
  return buildDayObject(target, target.getMonth() === currentMonth.value)
})

const selectedDayHasCompleted = computed(() => {
  return selectedDay.value?.events?.some(e => e.booking?.status === 'completed') || false
})

const selectedDayCompletedCount = computed(() => {
  return selectedDay.value?.events?.filter(e => e.booking?.status === 'completed')?.length || 0
})

const isModalBookingMine = computed(() => {
  if (!activeModalBooking.value) return false
  const myId = currentUser.value?.id
  const myName = currentUser.value?.name?.toLowerCase()
  const b = activeModalBooking.value
  return (myId && Number(b.driver_id) === Number(myId)) ||
         (myName && b.driver_name && b.driver_name.toLowerCase().includes(myName))
})

const upcomingAgendaList = computed(() => {
  const list = []
  const start = new Date()
  start.setHours(0, 0, 0, 0)

  for (let offset = 0; offset < 30; offset++) {
    const curDate = new Date(start)
    curDate.setDate(curDate.getDate() + offset)

    const dayObj = buildDayObject(curDate, true)
    dayObj.events.forEach(evt => {
      list.push({
        date: curDate,
        booking: evt.booking,
        timeStr: evt.timeStr,
        isMine: evt.isMine,
        isPool: evt.isPool,
        isOtherDriver: evt.isOtherDriver,
        isCompleted: evt.isCompleted
      })
    })
  }

  return list
})

const getEventBadgeClass = (evt) => {
  if (evt.isCompleted) {
    return 'bg-emerald-500/15 text-emerald-300 border-emerald-500/30 line-through opacity-85'
  }
  if (evt.isMine) {
    return 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30'
  }
  if (evt.isOtherDriver) {
    return 'bg-blue-500/20 text-blue-300 border-blue-500/30'
  }
  if (evt.isPool) {
    return 'bg-amber-500/20 text-amber-300 border-amber-500/30'
  }
  return 'bg-slate-800 text-slate-300 border-slate-700'
}

const getEventDotClass = (evt) => {
  if (evt.isCompleted) return 'bg-emerald-400'
  if (evt.isMine) return 'bg-emerald-400'
  if (evt.isOtherDriver) return 'bg-blue-400'
  if (evt.isPool) return 'bg-amber-400'
  return 'bg-cyan-400'
}

const getGoogleMapsLink = (b) => {
  if (!b) return null
  if (b.google_maps_url && b.google_maps_url.includes('google.com/maps') && !b.google_maps_url.includes('%2C')) {
    return b.google_maps_url
  }
  return b.google_maps_nav_url || null
}

const openDetailModal = (booking) => {
  activeModalBooking.value = booking
  showDetailModal.value = true
}

const acceptRoutineOrder = async (booking) => {
  const headers = {}
  if (tokenCookie.value) {
    headers['Authorization'] = `Bearer ${tokenCookie.value}`
  }

  try {
    const res = await $fetch(`${apiBase}/driver/bookings/${booking.id}/accept`, {
      method: 'POST',
      headers,
      body: {
        driver_id: currentUser.value?.id,
        driver_name: currentUser.value?.name || 'Pengemudi Operasional'
      }
    })

    if (res?.status === 'success') {
      alertMsg.value = {
        type: 'success',
        text: `Tugas berhasil diambil! Ditugaskan ke ${currentUser.value?.name || 'Anda'}.`
      }
      await fetchCalendarData()
    }
  } catch (err) {
    alertMsg.value = {
      type: 'error',
      text: err?.data?.message || 'Gagal mengambil penugasan'
    }
  }
}

onMounted(() => {
  if (userCookie.value) {
    try {
      currentUser.value = typeof userCookie.value === 'string' ? JSON.parse(userCookie.value) : userCookie.value
    } catch (e) {
      currentUser.value = null
    }
  }
  selectedDate.value = new Date()
  fetchCalendarData()
})
</script>

<style scoped>
/* Scoped driver calendar styles */
</style>
