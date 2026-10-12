<template>
  <div class="kendaraan-page min-h-screen font-sans pb-16 relative" :class="[isDark ? 'theme-dark' : 'theme-light']">
    <!-- BACKGROUND GARASI MOBIL (TETAP NATURAL, TANPA LAPISAN GELAP/TERANG) -->
    <div class="kendaraan-bg-backdrop" aria-hidden="true"></div>

    <!-- Topbar Terpadu -->
    <UserTopbar />

    <main class="kendaraan-main max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-6 relative z-10">
      <!-- Notifikasi Alert -->
      <div
        v-if="alertMsg"
        class="alert-card p-4 rounded-xl border flex items-center justify-between transition shadow-lg animate-fade-in"
        :class="alertMsg.type === 'error' ? 'alert-error' : 'alert-success'"
      >
        <div class="flex items-center gap-3">
          <span class="text-xl">{{ alertMsg.type === 'error' ? '⚠️' : '✅' }}</span>
          <div>
            <h4 class="text-xs md:text-sm font-bold">{{ alertMsg.title || (alertMsg.type === 'error' ? 'Terjadi Kesalahan' : 'Berhasil') }}</h4>
            <p class="text-xs font-medium opacity-90">{{ alertMsg.text }}</p>
          </div>
        </div>
        <button @click="alertMsg = null" class="btn-close-alert text-xs px-2 py-1 rounded">✕</button>
      </div>

      <!-- Banner Prosedur Pelayanan Peminjaman Mobil -->
      <section class="banner-card rounded-2xl p-4 sm:p-6 shadow-xl relative overflow-hidden">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
          <div>
            <div class="flex items-center gap-2 mb-1.5">
              <span class="badge-procedure px-2.5 py-0.5 rounded-full text-[10px] font-bold">
                LAYANAN KEDINASAN TERPADU
              </span>
              <span class="text-xs text-subtle">Alokasi Sentral Pool Pengemudi</span>
            </div>
            <h2 class="text-base sm:text-lg md:text-2xl font-black banner-title">Formulir Permohonan Mobil Dinas Operasional</h2>
            <p class="text-xs sm:text-sm banner-desc mt-1.5 max-w-3xl leading-relaxed">
              Silakan lengkapi formulir di bawah ini. Anda dapat memilih antara permohonan <b>Kunjungan Sekali Jalan (Insidental)</b> atau <b>Kunjungan Rutin Terjadwal yang Sudah Pasti</b>. Armada dan sopir dialokasikan langsung oleh pool kendaraan dinas.
            </p>
          </div>
          <div class="shrink-0 flex items-center gap-2">
            <NuxtLink
              to="/User/riwayat"
              class="btn-view-history px-3.5 py-2 sm:px-4 sm:py-2.5 rounded-xl text-xs font-semibold flex items-center gap-2 transition shadow-sm"
            >
              <span>🕒</span>
              <span>Riwayat Pengajuan</span>
            </NuxtLink>
          </div>
        </div>
      </section>

      <!-- FORMULIR PENGAJUAN PINJAM MOBIL -->
      <section class="form-container-card rounded-2xl p-4 sm:p-6 lg:p-8 shadow-2xl relative">
        <!-- HEADER FORM -->
        <div class="border-b pb-3 mb-4 form-divider flex flex-col sm:flex-row sm:items-center justify-between gap-2">
          <div>
            <h3 class="text-sm sm:text-base font-bold banner-title flex items-center gap-1.5">
              <span>🚗</span>
              <span>Peminjaman Mobil Operasional Dinas</span>
            </h3>
            <p class="text-[10px] sm:text-xs text-subtle mt-0.5">Identitas pemohon terisi otomatis sesuai akun yang sedang aktif.</p>
          </div>
          <span
            class="px-2.5 py-0.5 sm:px-3 sm:py-1 rounded-full text-[10px] sm:text-[11px] font-bold self-start sm:self-auto border"
            :class="bookingType === 'routine' ? 'bg-cyan-500/15 text-cyan-400 border-cyan-500/30' : 'bg-emerald-500/15 text-emerald-400 border-emerald-500/30'"
          >
            {{ bookingType === 'routine' ? '🔄 Mode: Kunjungan Rutin Terjadwal' : '🚗 Mode: Sekali Jalan (Insidental)' }}
          </span>
        </div>

        <!-- PILIHAN TIPE KUNJUNGAN: 2 MENU (SEKALI JALAN & RUTIN TERJADWAL) -->
        <div class="booking-type-selector grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-3 mb-3.5 sm:mb-6">
          <!-- Opsi 1: Sekali Jalan (Insidental) -->
          <button
            type="button"
            @click="setBookingType('incidental')"
            class="type-btn p-2.5 sm:p-4 rounded-xl border text-left flex items-start gap-2.5 sm:gap-3 transition-all cursor-pointer"
            :class="bookingType === 'incidental' ? 'type-btn-active' : 'type-btn-inactive'"
          >
            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-sm sm:text-xl shrink-0">
              🚗
            </div>
            <div class="flex-1 min-w-0">
              <div class="flex items-center justify-between">
                <h4 class="font-bold text-xs sm:text-sm">Kunjungan Sekali Jalan</h4>
                <span v-if="bookingType === 'incidental'" class="badge-active-dot w-2 h-2 rounded-full bg-emerald-400"></span>
              </div>
              <p class="text-[10px] sm:text-[11px] text-subtle mt-0.5 leading-snug">
                Perjalanan dinas sewaktu-waktu / hari tertentu. Rute jemput & tujuan terkoneksi ke peta.
              </p>
            </div>
          </button>

          <!-- Opsi 2: Kunjungan Rutin Terjadwal (Sudah Pasti) -->
          <button
            type="button"
            @click="setBookingType('routine')"
            class="type-btn p-2.5 sm:p-4 rounded-xl border text-left flex items-start gap-2.5 sm:gap-3 transition-all cursor-pointer"
            :class="bookingType === 'routine' ? 'type-btn-active-cyan' : 'type-btn-inactive'"
          >
            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl bg-cyan-500/10 border border-cyan-500/20 flex items-center justify-center text-sm sm:text-xl shrink-0">
              🔄
            </div>
            <div class="flex-1 min-w-0">
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-1.5">
                  <h4 class="font-bold text-xs sm:text-sm">Kunjungan Rutin Terjadwal</h4>
                </div>
                <span class="px-1.5 py-0.2 rounded text-[8.5px] sm:text-[9px] font-bold bg-cyan-500/20 text-cyan-300 border border-cyan-500/30">
                  JADWAL PASTI
                </span>
              </div>
              <p class="text-[10px] sm:text-[11px] text-subtle mt-0.5 leading-snug">
                Agenda dinas berkala (mingguan atau bulanan) dengan rute dan armada standby tetap.
              </p>
            </div>
          </button>
        </div>

        <form @submit.prevent="submitCarBooking" class="space-y-3.5 sm:space-y-5 text-xs">
          <!-- BAGIAN 1: IDENTITAS PEMOHON (RINGKAS & DAPAT DIPILIH DARI LIST USER) -->
          <div class="relative" ref="borrowerDropdownRef">
            <div class="form-section-box p-2.5 sm:p-3.5 rounded-xl flex flex-col sm:flex-row sm:items-center justify-between gap-2 sm:gap-3 bg-slate-950/40">
              <div class="flex items-center gap-2.5">
                <div class="w-7 h-7 sm:w-9 sm:h-9 rounded-lg sm:rounded-xl bg-emerald-500/15 border border-emerald-500/30 text-emerald-400 flex items-center justify-center font-bold text-xs sm:text-base shrink-0">
                  👤
                </div>
                <div>
                  <span class="text-[8.5px] sm:text-[10px] text-subtle block uppercase tracking-wider font-semibold">Pegawai Pemohon</span>
                  <div class="flex items-center gap-1.5 sm:gap-2 flex-wrap">
                    <strong class="text-xs sm:text-sm text-white font-bold">{{ bookingForm.borrower_name || currentUser.name || 'Pegawai Kantor' }}</strong>
                    <span class="text-[10px] sm:text-[11px] text-slate-400 font-medium">({{ bookingForm.borrower_division || currentUserDivision }})</span>
                  </div>
                </div>
              </div>
              <div class="flex items-center gap-2 self-start sm:self-auto">
                <button
                  type="button"
                  @click="isBorrowerDropdownOpen = !isBorrowerDropdownOpen"
                  class="px-2.5 py-1 rounded-lg text-[10px] sm:text-[11px] font-semibold border border-emerald-500/40 bg-emerald-500/10 text-emerald-300 hover:bg-emerald-500/20 transition flex items-center gap-1 cursor-pointer"
                >
                  <span>👥</span>
                  <span>{{ isBorrowerCustom ? 'Ganti Pemohon' : 'Pilih Atas Nama Rekan' }}</span>
                </button>
                <button
                  v-if="isBorrowerCustom"
                  type="button"
                  @click="resetBorrowerToSelf"
                  class="px-2 py-1 rounded-lg text-[10px] text-subtle hover:text-white transition flex items-center gap-1 cursor-pointer"
                  title="Kembalikan ke akun saya"
                >
                  <span>↺</span>
                  <span>Saya</span>
                </button>
                <span v-else class="text-[8.5px] sm:text-[10px] text-emerald-400 font-semibold px-2 py-0.5 rounded-full bg-emerald-500/10 border border-emerald-500/20 flex items-center gap-1">
                  <span>🔒</span> Akun Aktif
                </span>
              </div>
            </div>

            <!-- DROPDOWN PILIH PEMOHON LAIN -->
            <div
              v-if="isBorrowerDropdownOpen"
              class="passenger-dropdown-popover absolute z-40 left-0 right-0 mt-1.5 p-2.5 rounded-xl shadow-2xl border backdrop-blur-xl animate-fade-in"
            >
              <div class="relative mb-2">
                <input
                  v-model="borrowerSearchQuery"
                  type="text"
                  placeholder="🔍 Cari nama pegawai pemohon..."
                  class="form-input w-full p-2 pl-2.5 pr-8 rounded-lg text-xs focus:outline-none focus:border-emerald-500"
                />
                <button
                  v-if="borrowerSearchQuery"
                  type="button"
                  @click="borrowerSearchQuery = ''"
                  class="absolute right-2.5 top-1/2 -translate-y-1/2 text-subtle hover:text-white text-xs"
                >
                  ✕
                </button>
              </div>
              <div class="max-h-48 overflow-y-auto space-y-1 py-1 custom-scrollbar">
                <div
                  v-for="user in filteredBorrowerUsers"
                  :key="user.id"
                  @click="selectBorrower(user)"
                  class="passenger-option-item p-2 rounded-lg flex items-center justify-between gap-2.5 transition cursor-pointer hover:bg-emerald-500/15"
                >
                  <div class="flex items-center gap-2.5 min-w-0">
                    <div class="w-6 h-6 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 flex items-center justify-center font-bold text-[9px] shrink-0">
                      {{ getInitials(user.name) }}
                    </div>
                    <div class="min-w-0">
                      <strong class="text-xs block banner-title truncate">{{ user.name }}</strong>
                      <span class="text-[9.5px] text-subtle block truncate">{{ user.division_name }}</span>
                    </div>
                  </div>
                  <span class="text-[10px] text-emerald-400 font-semibold shrink-0">Pilih</span>
                </div>
              </div>
            </div>
          </div>

          <!-- BAGIAN KHUSUS: PENGATURAN KUNJUNGAN RUTIN (HANYA MUNCUL JIKA MODE RUTIN) -->
          <div v-if="bookingType === 'routine'" class="form-section-box p-3 sm:p-4 rounded-xl space-y-3 sm:space-y-4 border-cyan-500/40 bg-cyan-950/10 animate-fade-in">
            <div class="flex items-center justify-between pb-2 border-b form-divider">
              <div>
                <span class="font-bold text-xs text-cyan-400 flex items-center gap-1.5">
                  <span>🔄</span>
                  <span>Pengaturan Jadwal Kunjungan Rutin Pasti</span>
                </span>
                <p class="text-[10px] sm:text-[11px] text-subtle mt-0.5">
                  Tentukan pola frekuensi dan hari pelaksanaan rutin agar pool mobil dapat menyiapkan armada standby.
                </p>
              </div>
              <span class="px-2 py-0.5 sm:px-2.5 sm:py-1 rounded-lg text-[9px] sm:text-[10px] font-bold bg-cyan-500/20 text-cyan-300 border border-cyan-500/30">
                Jadwal Pasti
              </span>
            </div>

            <!-- Detail Pola Pengulangan & Hari -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4 pt-1">
              <!-- Pilihan Frekuensi -->
              <div>
                <label class="block font-semibold form-label mb-1.5 text-[10.5px] sm:text-xs">Pola Frekuensi Rutin:</label>
                <div class="grid grid-cols-2 gap-1.5 sm:gap-2">
                  <button
                    type="button"
                    v-for="freq in frequencyOptions"
                    :key="freq.value"
                    @click="routineForm.frequency = freq.value"
                    class="p-1.5 sm:p-2 rounded-lg border text-center font-semibold text-[10.5px] sm:text-[11px] transition"
                    :class="routineForm.frequency === freq.value ? 'bg-cyan-500 text-white border-cyan-400 shadow-md' : 'bg-black/20 border-slate-700 text-subtle hover:text-white'"
                  >
                    {{ freq.label }}
                  </button>
                </div>
              </div>

              <!-- Jam Berangkat & Jam Pulang Rutin -->
              <div class="grid grid-cols-2 gap-2">
                <div>
                  <label class="block font-semibold form-label mb-1 text-[10.5px] sm:text-xs">Jam Berangkat (WIB):</label>
                  <input
                    v-model="routineForm.departureTime"
                    type="time"
                    required
                    class="form-input w-full p-2 rounded-lg focus:outline-none focus:border-cyan-400 font-mono font-bold text-center text-xs"
                  />
                  <small class="text-[8.5px] sm:text-[9.5px] text-subtle mt-0.5 block truncate">Waktu berangkat.</small>
                </div>
                <div>
                  <label class="block font-semibold form-label mb-1 text-[10.5px] sm:text-xs">Estimasi Pulang (WIB):</label>
                  <input
                    v-model="routineForm.returnTime"
                    type="time"
                    required
                    class="form-input w-full p-2 rounded-lg focus:outline-none focus:border-cyan-400 font-mono font-bold text-center text-xs text-amber-300"
                  />
                  <small class="text-[8.5px] sm:text-[9.5px] text-subtle mt-0.5 block truncate">Perkiraan tiba kantor.</small>
                </div>
              </div>
            </div>

            <!-- Pilihan Hari Pelaksanaan (Untuk Mingguan / Dwi-Mingguan) -->
            <div v-if="routineForm.frequency === 'weekly' || routineForm.frequency === 'biweekly'">
              <label class="block font-semibold form-label mb-1.5 flex items-center justify-between text-[10.5px] sm:text-xs">
                <span>Pilih Hari Pelaksanaan Pasti:</span>
                <span class="text-cyan-400 font-bold text-[9.5px] sm:text-[10px]">
                  {{ routineForm.selectedDays.length > 0 ? routineForm.selectedDays.join(', ') : 'Pilih minimal 1 hari' }}
                </span>
              </label>
              <div class="flex flex-wrap gap-1.5 sm:gap-2">
                <button
                  v-for="day in weekDays"
                  :key="day"
                  @click="toggleRoutineDay(day)"
                  class="px-2.5 py-1 rounded-lg text-[10.5px] sm:text-xs font-bold border transition flex items-center gap-1 cursor-pointer"
                  :class="routineForm.selectedDays.includes(day) ? 'bg-cyan-500 text-white border-cyan-400 shadow-md' : 'bg-black/20 border-slate-700 text-subtle hover:text-white'"
                >
                  <span v-if="routineForm.selectedDays.includes(day)">✓</span>
                  <span>{{ day }}</span>
                </button>
              </div>
            </div>

            <!-- Periode Berlaku Jadwal Rutin -->
            <div class="space-y-1.5 sm:space-y-2">
              <div class="flex items-center justify-between">
                <label class="block font-semibold form-label text-[10.5px] sm:text-xs">Periode Berlaku Jadwal Rutin:</label>
                <!-- Quick Duration Presets for Routine -->
                <div class="flex items-center gap-1">
                  <button
                    type="button"
                    @click="setRoutineDuration(1)"
                    class="px-1.5 py-0.5 rounded text-[9px] sm:text-[10px] border border-cyan-500/30 text-cyan-300 hover:bg-cyan-500/20 transition cursor-pointer"
                  >
                    +1 Bulan
                  </button>
                  <button
                    type="button"
                    @click="setRoutineDuration(3)"
                    class="px-1.5 py-0.5 rounded text-[9px] sm:text-[10px] border border-cyan-500/30 text-cyan-300 hover:bg-cyan-500/20 transition cursor-pointer"
                  >
                    +3 Bulan
                  </button>
                  <button
                    type="button"
                    @click="setRoutineDuration(6)"
                    class="px-1.5 py-0.5 rounded text-[9px] sm:text-[10px] border border-cyan-500/30 text-cyan-300 hover:bg-cyan-500/20 transition cursor-pointer"
                  >
                    +6 Bulan
                  </button>
                </div>
              </div>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-2.5 sm:gap-4">
                <div>
                  <label class="block text-[10px] sm:text-[11px] text-subtle mb-1">Mulai Berlaku Tanggal:</label>
                  <input
                    v-model="routineForm.startDate"
                    type="date"
                    required
                    class="form-input w-full p-2 rounded-lg font-mono focus:outline-none focus:border-cyan-400 font-semibold text-xs"
                  />
                  <small class="text-[9px] sm:text-[10px] text-subtle mt-0.5 block">{{ formatIndonesianDate(routineForm.startDate) }}</small>
                </div>
                <div>
                  <label class="block text-[10px] sm:text-[11px] text-subtle mb-1">Berlaku Sampai Tanggal:</label>
                  <input
                    v-model="routineForm.endDate"
                    :min="routineForm.startDate"
                    type="date"
                    required
                    class="form-input w-full p-2 rounded-lg font-mono focus:outline-none focus:border-cyan-400 font-semibold text-xs"
                  />
                  <small class="text-[9px] sm:text-[10px] text-subtle mt-0.5 block">{{ formatIndonesianDate(routineForm.endDate) }}</small>
                </div>
              </div>
            </div>

            <!-- LIVE PREVIEW JADWAL RUTIN -->
            <div class="p-2.5 sm:p-3 rounded-xl bg-cyan-950/30 border border-cyan-500/30 flex items-start gap-2">
              <span class="text-base sm:text-lg">🗓️</span>
              <div class="text-xs">
                <span class="font-bold text-cyan-300 block text-[10.5px] sm:text-xs">Ringkasan Jadwal Rutin Pasti:</span>
                <p class="banner-title font-medium mt-0.5 leading-relaxed text-[10.5px] sm:text-xs">{{ routineScheduleSummary }}</p>
              </div>
            </div>
          </div>

          <!-- BAGIAN 2: PETA RUTE & TITIK TUJUAN MULTI-STOP (TERPADU) -->
          <div>
            <ClientOnly>
              <RideMapPicker
                :initialPickup="bookingForm.pickup_location"
                :initialDestinations="bookingForm.destinations"
                :userId="isBorrowerCustom && selectedBorrowerId ? selectedBorrowerId : (currentUser.id || 82)"
                :userName="bookingForm.borrower_name || currentUser.name"
                @update:route="onMapRouteUpdate"
              />
            </ClientOnly>
          </div>

          <!-- BAGIAN 3: JADWAL & WAKTU KEBERANGKATAN (DENGAN FILTER TANGGAL PRAKTIS) -->
          <div class="form-section-box p-3 sm:p-4 rounded-xl space-y-3 sm:space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-2 border-b form-divider gap-2">
              <div>
                <label class="font-bold text-xs section-heading flex items-center gap-1.5">
                  <span>📅</span>
                  <span>Jadwal & Waktu Keberangkatan</span>
                </label>
                <p class="text-[10px] sm:text-[11px] text-subtle mt-0.5">
                  Gunakan filter tanggal praktis (Hari Ini, Besok, Lusa, atau Kalender) serta tentukan jam berangkat.
                </p>
              </div>

              <!-- Toggle Rentang Hari (Jika Perjalanan Dinas Multi Hari) -->
              <div v-if="bookingType === 'incidental'" class="flex items-center gap-2">
                <button
                  type="button"
                  @click="isMultiDayTrip = !isMultiDayTrip"
                  class="px-2.5 py-1 rounded-lg text-[10px] font-bold border transition flex items-center gap-1.5 cursor-pointer"
                  :class="isMultiDayTrip ? 'bg-emerald-500 text-white border-emerald-400' : 'bg-black/20 border-slate-700 text-subtle hover:text-white'"
                >
                  <span>{{ isMultiDayTrip ? '✓ Pulang Hari Berbeda (Multi-Hari)' : '+ Pulang Hari Berbeda?' }}</span>
                </button>
              </div>
            </div>

            <!-- JIKA MODE INSIDENTAL: FILTER TANGGAL & WAKTU LENGKAP -->
            <div v-if="bookingType === 'incidental'" class="space-y-3">
              <!-- Filter Cepat Tanggal Berangkat (Pills) -->
              <div>
                <label class="block font-semibold form-label mb-1 flex items-center justify-between text-[11px]">
                  <span>Filter Cepat Tanggal Berangkat:</span>
                  <span class="text-emerald-400 font-bold text-[10px]">{{ formatIndonesianDate(tripDate) }}</span>
                </label>
                <div class="grid grid-cols-2 sm:flex sm:flex-wrap items-center gap-1.5 sm:gap-2">
                  <button
                    type="button"
                    @click="setQuickDate('today')"
                    class="px-2.5 py-1.5 rounded-lg text-xs font-bold border transition flex items-center justify-center gap-1.5 cursor-pointer text-center"
                    :class="quickDateFilter === 'today' ? 'bg-emerald-500 text-white border-emerald-400 shadow-md' : 'bg-black/20 border-slate-700 text-subtle hover:text-white'"
                  >
                    <span>⚡</span>
                    <span>Hari Ini</span>
                  </button>
                  <button
                    type="button"
                    @click="setQuickDate('tomorrow')"
                    class="px-2.5 py-1.5 rounded-lg text-xs font-bold border transition flex items-center justify-center gap-1.5 cursor-pointer text-center"
                    :class="quickDateFilter === 'tomorrow' ? 'bg-emerald-500 text-white border-emerald-400 shadow-md' : 'bg-black/20 border-slate-700 text-subtle hover:text-white'"
                  >
                    <span>🌅</span>
                    <span>Besok</span>
                  </button>
                  <button
                    type="button"
                    @click="setQuickDate('lusa')"
                    class="px-2.5 py-1.5 rounded-lg text-xs font-bold border transition flex items-center justify-center gap-1.5 cursor-pointer text-center"
                    :class="quickDateFilter === 'lusa' ? 'bg-emerald-500 text-white border-emerald-400 shadow-md' : 'bg-black/20 border-slate-700 text-subtle hover:text-white'"
                  >
                    <span>📆</span>
                    <span>Lusa</span>
                  </button>
                  <button
                    type="button"
                    @click="quickDateFilter = 'custom'"
                    class="px-2.5 py-1.5 rounded-lg text-xs font-bold border transition flex items-center justify-center gap-1.5 cursor-pointer text-center"
                    :class="quickDateFilter === 'custom' ? 'bg-emerald-500 text-white border-emerald-400 shadow-md' : 'bg-black/20 border-slate-700 text-subtle hover:text-white'"
                  >
                    <span>📅</span>
                    <span>Pilih Kalender</span>
                  </button>
                </div>
              </div>

              <!-- Input Tanggal & Jam Grid -->
              <div class="grid grid-cols-1 gap-3" :class="isMultiDayTrip ? 'sm:grid-cols-2 md:grid-cols-4' : 'sm:grid-cols-3'">
                <!-- Tanggal Berangkat -->
                <div>
                  <label class="block font-semibold form-label mb-1 text-[11px]">
                    {{ isMultiDayTrip ? 'Tanggal Mulai Berangkat:' : 'Tanggal Keberangkatan:' }}
                  </label>
                  <input
                    v-model="tripDate"
                    @change="onDateInputChange"
                    type="date"
                    required
                    class="form-input w-full p-2 rounded-lg focus:outline-none focus:border-emerald-500 font-mono font-bold text-xs"
                  />
                  <small class="text-[9px] sm:text-[10px] text-emerald-400 mt-0.5 block font-medium">{{ formatIndonesianDate(tripDate) }}</small>
                </div>

                <!-- Tanggal Selesai / Kepulangan (Jika Multi-Hari) -->
                <div v-if="isMultiDayTrip">
                  <label class="block font-semibold form-label mb-1 text-[11px]">
                    Tanggal Selesai / Pulang:
                  </label>
                  <input
                    v-model="tripEndDate"
                    :min="tripDate"
                    type="date"
                    required
                    class="form-input w-full p-2 rounded-lg focus:outline-none focus:border-emerald-500 font-mono font-bold text-xs"
                  />
                  <small class="text-[9px] sm:text-[10px] text-emerald-400 mt-0.5 block font-medium">{{ formatIndonesianDate(tripEndDate) }}</small>
                </div>

                <!-- Jam Keberangkatan -->
                <div>
                  <label class="block font-semibold form-label mb-1 text-[11px] flex items-center justify-between">
                    <span>Jam Berangkat:</span>
                    <span class="text-emerald-400 font-mono font-bold text-xs">{{ tripTime }} WIB</span>
                  </label>
                  <input
                    v-model="tripTime"
                    type="time"
                    required
                    class="form-input w-full p-2 rounded-lg focus:outline-none focus:border-emerald-500 font-mono font-bold text-center text-xs"
                  />
                  <!-- Shortcut Waktu / Jam -->
                  <div class="flex items-center gap-1 mt-1 overflow-x-auto">
                    <button
                      v-for="qt in quickTimes"
                      :key="qt.value"
                      type="button"
                      @click="tripTime = qt.value"
                      class="px-1.5 py-0.5 rounded text-[8.5px] border transition shrink-0 cursor-pointer"
                      :class="tripTime === qt.value ? 'bg-emerald-500 text-white border-emerald-400 font-bold' : 'bg-black/10 border-slate-700 text-subtle hover:text-white'"
                    >
                      {{ qt.label }}
                    </button>
                  </div>
                </div>

                <!-- Jam Estimasi Pulang -->
                <div>
                  <label class="block font-semibold form-label mb-1 text-[11px] flex items-center justify-between">
                    <span class="text-amber-400">Estimasi Jam Pulang:</span>
                    <span class="text-amber-400 font-mono font-bold text-xs">{{ tripReturnTime }} WIB</span>
                  </label>
                  <input
                    v-model="tripReturnTime"
                    type="time"
                    required
                    class="form-input w-full p-2 rounded-lg focus:outline-none focus:border-amber-400 font-mono font-bold text-center text-xs text-amber-300 border-amber-500/40"
                  />
                  <!-- Shortcut Waktu Jam Pulang -->
                  <div class="flex items-center gap-1 mt-1 overflow-x-auto">
                    <button
                      v-for="qt in quickReturnTimes"
                      :key="qt.value"
                      type="button"
                      @click="tripReturnTime = qt.value"
                      class="px-1.5 py-0.5 rounded text-[8.5px] border transition shrink-0 cursor-pointer"
                      :class="tripReturnTime === qt.value ? 'bg-amber-500 text-black border-amber-400 font-bold' : 'bg-black/10 border-slate-700 text-subtle hover:text-white'"
                    >
                      {{ qt.label }}
                    </button>
                  </div>
                </div>
              </div>

              <!-- Ringkasan Filter Jadwal -->
              <div class="p-2.5 sm:p-3 rounded-xl bg-emerald-950/20 border border-emerald-500/30 flex items-center gap-2">
                <span class="text-base sm:text-lg">🗓️</span>
                <div class="text-[11px]">
                  <span class="font-bold text-emerald-400">Jadwal Keberangkatan & Estimasi Pulang:</span>
                  <p class="banner-title font-medium mt-0.5">
                    <span class="font-bold text-emerald-300">{{ formatIndonesianDate(tripDate) }}</span>
                    <span v-if="isMultiDayTrip"> s/d <span class="font-bold text-emerald-300">{{ formatIndonesianDate(tripEndDate) }}</span></span>
                    <span> • Berangkat: <b>{{ tripTime }} WIB</b></span>
                    <span> ➔ Estimasi Pulang: <b class="text-amber-300">{{ tripReturnTime }} WIB</b></span>
                  </p>
                </div>
              </div>
            </div>

            <!-- JIKA MODE RUTIN: TAMPILAN JAM KEBERANGKATAN RUTIN -->
            <div v-else class="flex flex-col sm:flex-row sm:items-center justify-between bg-black/10 p-2.5 sm:p-3 rounded-xl border border-cyan-500/20 gap-2">
              <div class="flex items-center gap-2">
                <span class="text-lg sm:text-xl">🕒</span>
                <div>
                  <span class="text-[10px] sm:text-[11px] font-semibold text-cyan-300 block">Jadwal Jam Keberangkatan & Pulang Rutin:</span>
                  <strong class="text-xs sm:text-sm font-mono banner-title">
                    {{ routineForm.departureTime }} WIB ➔ Estimasi Pulang: {{ routineForm.returnTime || '17:00' }} WIB
                  </strong>
                </div>
              </div>
              <span class="text-[9px] sm:text-[10px] text-subtle sm:text-right">Disesuaikan otomatis dengan siklus jadwal rutin di atas.</span>
            </div>
          </div>

          <!-- BAGIAN 4: JUMLAH ORANG & DAFTAR REKAN YANG IKUT (DENGAN DROPDOWN NAMA LIST USER) -->
          <div class="form-section-box p-3 sm:p-4 rounded-xl space-y-3">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-2 border-b form-divider">
              <div>
                <label class="font-bold text-xs section-heading flex items-center gap-1.5">
                  <span>👥</span>
                  <span>Rombongan & Rekan yang Ikut Pergi</span>
                </label>
                <p class="text-[10px] sm:text-[11px] text-subtle mt-0.5">
                  Pilih nama rekan pegawai yang ikut pergi dari dropdown daftar user di bawah, atau tambahkan tamu luar.
                </p>
              </div>

              <!-- Stepper Kapasitas Kursi / Jumlah Orang -->
              <div class="flex items-center gap-2 bg-black/20 px-3 py-1.5 rounded-xl border border-slate-700/60 self-start sm:self-auto">
                <span class="text-[10px] text-subtle font-semibold">Total Penumpang:</span>
                <div class="flex items-center gap-1.5">
                  <button
                    type="button"
                    @click="stepPassengers(-1)"
                    :disabled="bookingForm.passengers_count <= 1"
                    class="btn-stepper w-6 h-6 rounded-md font-bold text-xs transition flex items-center justify-center disabled:opacity-40 cursor-pointer"
                  >
                    -
                  </button>
                  <span class="text-emerald-400 font-bold font-mono text-xs px-1 min-w-[20px] text-center">
                    {{ bookingForm.passengers_count }}
                  </span>
                  <button
                    type="button"
                    @click="stepPassengers(1)"
                    :disabled="bookingForm.passengers_count >= 20"
                    class="btn-stepper w-6 h-6 rounded-md font-bold text-xs transition flex items-center justify-center disabled:opacity-40 cursor-pointer"
                  >
                    +
                  </button>
                </div>
                <span class="text-[10px] text-subtle">Orang</span>
              </div>
            </div>

            <!-- DROPDOWN SELECTOR NAMA LIST USER -->
            <div class="space-y-2 relative" ref="passengerDropdownRef">
              <label class="block font-semibold form-label text-[11px] flex items-center justify-between">
                <span>Pilih Rekan Pegawai yang Ikut (Dropdown):</span>
                <span class="text-emerald-400 font-bold text-[10px]" v-if="selectedPassengersList.length > 0">
                  {{ selectedPassengersList.length }} Rekan Terpilih
                </span>
              </label>

              <!-- Dropdown Trigger Button -->
              <div class="relative">
                <button
                  type="button"
                  @click="isPassengerDropdownOpen = !isPassengerDropdownOpen"
                  class="dropdown-trigger-btn w-full p-2.5 rounded-xl border flex items-center justify-between text-left transition cursor-pointer"
                  :class="isPassengerDropdownOpen ? 'border-emerald-500 ring-2 ring-emerald-500/20' : ''"
                >
                  <div class="flex items-center gap-2 min-w-0">
                    <span class="text-base shrink-0">👥</span>
                    <span v-if="selectedPassengersList.length === 0" class="text-subtle text-xs truncate">
                      Klik di sini untuk memilih nama rekan dari list user kantor...
                    </span>
                    <span v-else class="text-xs font-semibold text-emerald-300 truncate">
                      {{ selectedPassengersList.map(p => p.name).join(', ') }}
                    </span>
                  </div>
                  <div class="flex items-center gap-2 shrink-0">
                    <span
                      v-if="selectedPassengersList.length > 0"
                      class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30"
                    >
                      +{{ selectedPassengersList.length }} Orang
                    </span>
                    <span class="text-xs text-subtle transition-transform duration-200" :class="isPassengerDropdownOpen ? 'rotate-180' : ''">
                      ▼
                    </span>
                  </div>
                </button>

                <!-- DROPDOWN POPOVER MENU -->
                <div
                  v-if="isPassengerDropdownOpen"
                  class="passenger-dropdown-popover absolute z-40 left-0 right-0 mt-1.5 p-2.5 rounded-xl shadow-2xl border backdrop-blur-xl animate-fade-in"
                >
                  <!-- Search input -->
                  <div class="relative mb-2">
                    <input
                      v-model="passengerSearchQuery"
                      type="text"
                      placeholder="🔍 Cari nama pegawai atau divisi..."
                      class="form-input w-full p-2 pl-2.5 pr-8 rounded-lg text-xs focus:outline-none focus:border-emerald-500"
                    />
                    <button
                      v-if="passengerSearchQuery"
                      type="button"
                      @click="passengerSearchQuery = ''"
                      class="absolute right-2.5 top-1/2 -translate-y-1/2 text-subtle hover:text-white text-xs"
                    >
                      ✕
                    </button>
                  </div>

                  <!-- Quick counter / hint -->
                  <div class="flex items-center justify-between px-1 pb-1.5 text-[10px] text-subtle border-b form-divider">
                    <span>Menampilkan {{ filteredPassengerUsers.length }} pegawai</span>
                    <span class="text-emerald-400 font-medium">Klik nama untuk memilih / batal</span>
                  </div>

                  <!-- Scrollable User List -->
                  <div class="max-h-56 overflow-y-auto space-y-1 py-1.5 custom-scrollbar">
                    <div
                      v-for="user in filteredPassengerUsers"
                      :key="user.id"
                      @click="togglePassenger(user)"
                      class="passenger-option-item p-2 rounded-lg flex items-center justify-between gap-2.5 transition cursor-pointer"
                      :class="isPassengerSelected(user.name) ? 'passenger-option-selected' : 'passenger-option-default'"
                    >
                      <div class="flex items-center gap-2.5 min-w-0">
                        <div
                          class="w-7 h-7 rounded-full flex items-center justify-center font-bold text-[10px] shrink-0 border"
                          :class="isPassengerSelected(user.name) ? 'bg-emerald-500 text-white border-emerald-400' : 'bg-slate-800 text-slate-300 border-slate-700'"
                        >
                          {{ getInitials(user.name) }}
                        </div>
                        <div class="min-w-0">
                          <strong class="text-xs block truncate" :class="isPassengerSelected(user.name) ? 'text-emerald-300 font-bold' : 'banner-title'">
                            {{ user.name }}
                          </strong>
                          <span class="text-[10px] text-subtle block truncate">
                            {{ user.division_name }} <span v-if="user.nip && user.nip !== 'user'">• NIP: {{ user.nip }}</span>
                          </span>
                        </div>
                      </div>

                      <div class="shrink-0 flex items-center">
                        <span
                          v-if="isPassengerSelected(user.name)"
                          class="w-5 h-5 rounded-full bg-emerald-500 text-white flex items-center justify-center text-[10px] font-bold"
                        >
                          ✓
                        </span>
                        <span
                          v-else
                          class="w-5 h-5 rounded-full border border-slate-700 flex items-center justify-center text-[10px] text-subtle opacity-40 hover:opacity-100"
                        >
                          +
                        </span>
                      </div>
                    </div>

                    <div v-if="filteredPassengerUsers.length === 0" class="text-center py-4 text-xs text-subtle">
                      Tidak ditemukan pegawai dengan kata kunci "<b>{{ passengerSearchQuery }}</b>"
                    </div>
                  </div>

                  <!-- Dropdown Footer -->
                  <div class="pt-2 border-t form-divider flex items-center justify-between gap-2 text-[11px]">
                    <button
                      type="button"
                      @click="showCustomPassengerInput = !showCustomPassengerInput"
                      class="text-emerald-400 hover:text-emerald-300 font-medium flex items-center gap-1 cursor-pointer"
                    >
                      <span>➕</span>
                      <span>{{ showCustomPassengerInput ? 'Tutup Input Manual' : 'Tambah Tamu / Non-Pegawai' }}</span>
                    </button>
                    <button
                      type="button"
                      @click="isPassengerDropdownOpen = false"
                      class="px-3 py-1 rounded-lg bg-emerald-500/20 text-emerald-300 hover:bg-emerald-500/30 font-semibold cursor-pointer text-xs"
                    >
                      Selesai
                    </button>
                  </div>

                  <!-- Input Tambah Rekan Manual / Tamu -->
                  <div v-if="showCustomPassengerInput" class="mt-2 pt-2 border-t form-divider flex items-center gap-1.5">
                    <input
                      v-model="customPassengerName"
                      @keydown.enter.prevent="addCustomPassenger"
                      type="text"
                      placeholder="Ketik nama rekan / tamu dinas..."
                      class="form-input flex-1 p-1.5 rounded-lg text-xs focus:outline-none focus:border-emerald-500"
                    />
                    <button
                      type="button"
                      @click="addCustomPassenger"
                      class="px-3 py-1.5 rounded-lg bg-emerald-500 text-white font-bold text-xs shrink-0 cursor-pointer hover:bg-emerald-600 transition"
                    >
                      Tambah
                    </button>
                  </div>
                </div>
              </div>

              <!-- CHIPS / BADGES REKAN YANG SUDAH DIPILIH -->
              <div v-if="selectedPassengersList.length > 0" class="space-y-1.5 pt-1">
                <span class="text-[10px] text-subtle block font-semibold">Daftar Rekan yang Terpilih:</span>
                <div class="flex flex-wrap gap-1.5">
                  <div
                    v-for="(p, idx) in selectedPassengersList"
                    :key="idx"
                    class="passenger-chip inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs transition"
                  >
                    <span>👤</span>
                    <strong class="font-semibold">{{ p.name }}</strong>
                    <span v-if="p.division_name" class="text-[9.5px] opacity-75">({{ p.division_name }})</span>
                    <button
                      type="button"
                      @click="removePassenger(idx)"
                      class="chip-remove-btn w-4 h-4 rounded-full flex items-center justify-center font-bold text-[10px] ml-0.5 transition cursor-pointer"
                      title="Hapus dari daftar"
                    >
                      ✕
                    </button>
                  </div>
                </div>
              </div>

              <!-- INPUT RINGKASAN REKAN (EDITABLE & TERSINKRON OTOMATIS) -->
              <div class="pt-1">
                <label class="block text-[10px] text-subtle mb-0.5 flex items-center justify-between">
                  <span>Rincian Teks Penumpang (Tersinkronisasi Otomatis):</span>
                  <span class="text-[9px] text-slate-500">Dapat disesuaikan bebas</span>
                </label>
                <input
                  v-model="bookingForm.passengers"
                  type="text"
                  placeholder="Contoh: Pak Budi (Keuangan), Bu Maya (Umum)"
                  class="form-input w-full p-2 rounded-lg text-xs focus:outline-none focus:border-emerald-500 font-medium"
                />
              </div>
            </div>
          </div>

          <!-- BAGIAN 4: TUJUAN / KEPERLUAN UNTUK APA KE LOKASI TERSEBUT -->
          <div class="form-section-box p-3 sm:p-4 rounded-xl space-y-1.5">
            <label class="block font-semibold form-label mb-0.5 text-[11px] flex items-center gap-1.5">
              <span>🎯</span>
              <span>Tujuan / Keperluan Kedinasan:</span>
              <span class="text-red-400 font-bold">*</span>
            </label>
            <textarea
              v-model="bookingForm.purpose"
              rows="2"
              required
              :placeholder="bookingType === 'routine' ? 'Contoh: Kegiatan rutin mingguan supervisi obat dan perbekalan faskes...' : 'Contoh: Menghadiri rapat koordinasi teknis dinas kesehatan, monitoring berkala puskesmas...'"
              class="form-input w-full p-2 rounded-lg text-xs focus:outline-none focus:border-emerald-500 leading-relaxed"
            ></textarea>
            <small class="text-[9px] sm:text-[10px] text-subtle block">Jelaskan agenda kedinasan yang melatarbelakangi perjalanan.</small>
          </div>

          <!-- BAGIAN 5: CATATAN TAMBAHAN (OPSIONAL) -->
          <div class="form-section-box p-3 sm:p-4 rounded-xl space-y-1.5">
            <label class="block font-semibold form-label mb-0.5 text-[11px] flex items-center gap-1.5">
              <span>💬</span>
              <span>Catatan Tambahan untuk Pengemudi (Opsional):</span>
            </label>
            <input
              v-model="bookingForm.notes"
              type="text"
              placeholder="Contoh: Membawa dokumen logistik agak banyak di bagasi..."
              class="form-input w-full p-2 rounded-lg text-xs focus:outline-none focus:border-emerald-500"
            />
          </div>

          <!-- TOMBOL SUBMIT & AKSI -->
          <div class="form-actions-box pt-3.5 sm:pt-4 flex flex-col sm:flex-row items-center justify-between gap-2.5 sm:gap-3 border-t form-divider">
            <button
              type="button"
              @click="resetForm"
              class="btn-reset-form w-full sm:w-auto px-3.5 py-2 rounded-xl text-xs font-semibold transition flex items-center justify-center gap-1.5 cursor-pointer order-2 sm:order-1"
            >
              <span>↻</span>
              <span>Bersihkan Form</span>
            </button>

            <button
              type="submit"
              :disabled="isSubmitting"
              class="btn-submit-booking w-full sm:w-auto px-4 sm:px-6 py-2.5 rounded-xl text-xs sm:text-sm font-bold flex items-center justify-center gap-2 shadow-lg transition cursor-pointer order-1 sm:order-2"
              :class="bookingType === 'routine' ? 'btn-submit-routine' : ''"
            >
              <span v-if="isSubmitting" class="w-3.5 h-3.5 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
              <span v-else class="text-sm">{{ bookingType === 'routine' ? '🔄' : '🚀' }}</span>
              <span>
                {{ isSubmitting ? 'Mengirim Permohonan...' : (bookingType === 'routine' ? 'Daftarkan Kunjungan Rutin' : 'Kirim Permohonan Peminjaman') }}
              </span>
            </button>
          </div>
        </form>
      </section>

      <!-- MODAL SUKSES PENGAJUAN -->
      <div v-if="showSuccessModal" class="modal-backdrop fixed inset-0 z-50 flex items-center justify-center p-4 backdrop-blur-md">
        <div class="modal-box rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4 text-center animate-scale-up">
          <div
            class="w-16 h-16 rounded-full flex items-center justify-center text-3xl mx-auto mb-2 border"
            :class="lastSubmittedData?.is_routine ? 'bg-cyan-500/20 text-cyan-400 border-cyan-500/30' : 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30'"
          >
            {{ lastSubmittedData?.is_routine ? '🔄' : '✅' }}
          </div>
          <h3 class="font-bold text-base banner-title">
            {{ lastSubmittedData?.is_routine ? 'Booking Kunjungan Rutin Terdaftar!' : 'Permohonan Berhasil Dikirim!' }}
          </h3>
          <p class="text-xs text-subtle leading-relaxed">
            <span v-if="lastSubmittedData?.is_routine">
              Jadwal kunjungan rutin Anda telah dicatat dalam sistem dengan status <b>"Menunggu Persetujuan Admin"</b>. Admin & pool kendaraan akan menjadwalkan armada secara berkala.
            </span>
            <span v-else>
              Permohonan peminjaman mobil dinas Anda telah tercatat dengan status <b>"Menunggu Persetujuan Admin"</b>. Setelah disetujui, unit armada dan sopir akan ditugaskan.
            </span>
          </p>

          <div class="p-3.5 rounded-xl bg-black/20 border border-emerald-500/20 text-left space-y-2 text-xs">
            <div v-if="lastSubmittedData?.is_routine" class="pb-1.5 border-b border-white/10">
              <span class="text-cyan-400 font-bold block text-[11px]">🗓️ Jadwal Rutin Pasti:</span>
              <strong class="text-white text-xs leading-snug">{{ lastSubmittedData?.routine_schedule }}</strong>
            </div>
            <div v-else-if="lastSubmittedData?.formatted_schedule" class="pb-1.5 border-b border-white/10">
              <span class="text-emerald-400 font-bold block text-[11px]">🗓️ Jadwal Keberangkatan:</span>
              <strong class="text-white text-xs leading-snug">{{ lastSubmittedData?.formatted_schedule }}</strong>
            </div>
            <div class="flex items-start gap-2">
              <span class="text-subtle shrink-0">📍 Rute:</span>
              <span class="font-bold banner-title line-clamp-2">{{ lastSubmittedData?.destination || '-' }}</span>
            </div>
            <div class="flex items-center gap-2">
              <span class="text-subtle">👥 Penumpang:</span>
              <span class="font-semibold text-emerald-400">{{ lastSubmittedData?.passengers_count || 1 }} Orang</span>
            </div>
            <div class="flex items-center gap-2">
              <span class="text-subtle">🎯 Keperluan:</span>
              <span class="text-subtle truncate">{{ lastSubmittedData?.purpose || '-' }}</span>
            </div>
          </div>

          <div class="pt-2 flex flex-col sm:flex-row gap-2.5 justify-center">
            <button
              @click="showSuccessModal = false"
              type="button"
              class="btn-modal-cancel px-4 py-2.5 rounded-xl font-semibold text-xs transition"
            >
              Buat Pengajuan Baru
            </button>
            <NuxtLink
              to="/User/riwayat"
              class="btn-submit-booking px-5 py-2.5 rounded-xl font-bold text-xs flex items-center justify-center gap-1.5 shadow-lg"
            >
              <span>🕒</span>
              <span>Cek di Riwayat Saya</span>
            </NuxtLink>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'

useHead({
  title: 'Pelayanan Peminjaman Mobil Dinas - PKL-Digo'
})

// Global Shared Theme State (synced with UserTopbar)
const isDark = useState('user_theme_dark', () => true)

const config = useRuntimeConfig()
const apiBase = config.public?.apiBase || 'http://127.0.0.1:8000/api'

const tokenCookie = useCookie('auth_token')
const userCookie = useCookie('auth_user')

// State Tipe Permohonan: 'incidental' (Sekali Jalan) atau 'routine' (Kunjungan Rutin Terjadwal)
const bookingType = ref('incidental')

const setBookingType = (type) => {
  bookingType.value = type
  if (type === 'routine' && routineForm.value.selectedDays.length === 0) {
    routineForm.value.selectedDays = ['Senin']
  }
}

// State identitas pengguna
const currentUser = ref({
  id: null,
  name: '',
  nip: '',
  division: null,
  division_name: ''
})

const currentUserDivision = computed(() => {
  if (currentUser.value?.division?.name) return currentUser.value.division.name
  if (currentUser.value?.division_name) return currentUser.value.division_name
  if (typeof currentUser.value?.division === 'string') return currentUser.value.division
  return 'Divisi Umum / Operasional'
})

// Alert & Form State
const alertMsg = ref(null)
const isSubmitting = ref(false)
const showSuccessModal = ref(false)
const lastSubmittedData = ref(null)

const isMultiDayTrip = ref(false)
const tripDate = ref(new Date().toISOString().slice(0, 10))
const tripEndDate = ref(new Date().toISOString().slice(0, 10))
const tripTime = ref('08:30')
const tripReturnTime = ref('17:00')
const quickDateFilter = ref('today') // 'today', 'tomorrow', 'lusa', 'custom'

const quickTimes = [
  { label: '08:00 (Pagi)', value: '08:00' },
  { label: '09:30 (Pagi)', value: '09:30' },
  { label: '13:00 (Siang)', value: '13:00' },
  { label: '15:30 (Sore)', value: '15:30' }
]

const quickReturnTimes = [
  { label: '12:00 (Siang)', value: '12:00' },
  { label: '14:00 (Siang)', value: '14:00' },
  { label: '16:00 (Sore)', value: '16:00' },
  { label: '17:00 (Sore)', value: '17:00' },
  { label: '18:30 (Malam)', value: '18:30' }
]

const setQuickDate = (type) => {
  quickDateFilter.value = type
  const d = new Date()
  if (type === 'tomorrow') {
    d.setDate(d.getDate() + 1)
  } else if (type === 'lusa') {
    d.setDate(d.getDate() + 2)
  }
  const iso = d.toISOString().slice(0, 10)
  tripDate.value = iso
  if (!isMultiDayTrip.value) {
    tripEndDate.value = iso
  }
}

const onDateInputChange = () => {
  const today = new Date().toISOString().slice(0, 10)
  const tom = new Date(Date.now() + 86400000).toISOString().slice(0, 10)
  const lus = new Date(Date.now() + 172800000).toISOString().slice(0, 10)

  if (tripDate.value === today) {
    quickDateFilter.value = 'today'
  } else if (tripDate.value === tom) {
    quickDateFilter.value = 'tomorrow'
  } else if (tripDate.value === lus) {
    quickDateFilter.value = 'lusa'
  } else {
    quickDateFilter.value = 'custom'
  }

  if (tripEndDate.value < tripDate.value) {
    tripEndDate.value = tripDate.value
  }
}

const formatIndonesianDate = (dateStr) => {
  if (!dateStr) return '-'
  try {
    const parts = dateStr.split('-')
    if (parts.length === 3) {
      const d = new Date(Number(parts[0]), Number(parts[1]) - 1, Number(parts[2]))
      return d.toLocaleDateString('id-ID', {
        weekday: 'long',
        day: 'numeric',
        month: 'long',
        year: 'numeric'
      })
    }
    return dateStr
  } catch (e) {
    return dateStr
  }
}

const setRoutineDuration = (months) => {
  const base = routineForm.value.startDate ? new Date(routineForm.value.startDate) : new Date()
  const target = new Date(base)
  target.setMonth(target.getMonth() + months)
  routineForm.value.endDate = target.toISOString().slice(0, 10)
}

const getDefaultEndDate = () => {
  const d = new Date()
  d.setMonth(d.getMonth() + 3)
  return d.toISOString().slice(0, 10)
}

// Form Utama
const bookingForm = ref({
  borrower_name: '',
  borrower_division: '',
  destinations: [''],
  destination_str: '',
  pickup_location: 'BPJS Kesehatan Cabang Jakarta Utara',
  pickup_lat: -6.1297591,
  pickup_lng: 106.9073865,
  destination_lat: null,
  destination_lng: null,
  distance_km: null,
  estimated_duration_minutes: null,
  google_maps_url: '',
  passengers_count: 2,
  passengers: '',
  purpose: '',
  notes: ''
})

const onMapRouteUpdate = (routeData) => {
  bookingForm.value.pickup_location = routeData.pickup_location || 'BPJS Kesehatan Cabang Jakarta Utara'
  bookingForm.value.pickup_lat = routeData.pickup_lat || -6.1297591
  bookingForm.value.pickup_lng = routeData.pickup_lng || 106.9073865
  bookingForm.value.destinations = routeData.destinations || ['Perjalanan Dinas']
  bookingForm.value.destination_str = routeData.destination_str || ''
  bookingForm.value.destination_lat = routeData.destination_lat
  bookingForm.value.destination_lng = routeData.destination_lng
  bookingForm.value.distance_km = routeData.distance_km
  bookingForm.value.estimated_duration_minutes = routeData.estimated_duration_minutes
  bookingForm.value.google_maps_url = routeData.google_maps_url
}

// Form Khusus Kunjungan Rutin
const routineForm = ref({
  frequency: 'weekly', // weekly, biweekly, monthly, daily
  selectedDays: ['Senin'],
  departureTime: '08:30',
  returnTime: '17:00',
  startDate: new Date().toISOString().slice(0, 10),
  endDate: getDefaultEndDate()
})

const weekDays = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']

const frequencyOptions = [
  { value: 'weekly', label: 'Mingguan (Tiap Pekan)' },
  { value: 'biweekly', label: 'Dwi-Mingguan (2 Pekan)' },
  { value: 'monthly', label: 'Bulanan (Tiap Bulan)' },
  { value: 'daily', label: 'Harian (Senin - Jumat)' }
]

// Toggle hari untuk jadwal rutin
const toggleRoutineDay = (day) => {
  const idx = routineForm.value.selectedDays.indexOf(day)
  if (idx > -1) {
    if (routineForm.value.selectedDays.length > 1) {
      routineForm.value.selectedDays.splice(idx, 1)
    }
  } else {
    routineForm.value.selectedDays.push(day)
  }
}

// Live Summary Jadwal Rutin
const routineScheduleSummary = computed(() => {
  if (bookingType.value !== 'routine') return ''

  const freqLabel = frequencyOptions.find(f => f.value === routineForm.value.frequency)?.label || 'Rutin'
  const timeStr = routineForm.value.departureTime || '08:30'
  const returnStr = routineForm.value.returnTime || '17:00'
  const startStr = routineForm.value.startDate || '-'
  const endStr = routineForm.value.endDate || '-'

  let daysStr = ''
  if (routineForm.value.frequency === 'daily') {
    daysStr = 'Setiap Hari Kerja (Senin - Jumat)'
  } else if (routineForm.value.frequency === 'monthly') {
    daysStr = 'Setiap Awal Bulan'
  } else {
    daysStr = `Setiap Hari ${routineForm.value.selectedDays.join(' & ')}`
  }

  return `Pola ${freqLabel}: ${daysStr}, pukul ${timeStr} s/d ${returnStr} WIB (Estimasi Pulang). Berlaku dari ${startStr} s/d ${endStr}.`
})

// Tambah baris destinasi baru (+)
const addDestination = () => {
  bookingForm.value.destinations.push('')
}

// Hapus baris destinasi tertentu
const removeDestination = (index) => {
  if (bookingForm.value.destinations.length > 1) {
    bookingForm.value.destinations.splice(index, 1)
  }
}

// =========================================================================
// DAFTAR PEGAWAI / USERS (BPJS KESEHATAN CABANG JAKARTA UTARA)
// =========================================================================
const defaultEmployeesList = [
  { id: 1, name: 'Yayak Nugroho', nip: '02560', division_name: 'Kepala Cabang' },
  { id: 2, name: 'Tenny Romandia', nip: '02614', division_name: 'Kepesertaan' },
  { id: 3, name: 'Hibatus Zuriyah', nip: '06833', division_name: 'Kepesertaan' },
  { id: 4, name: 'Rinawati', nip: '06807', division_name: 'Kepesertaan' },
  { id: 5, name: 'Prima Mentari', nip: '09545', division_name: 'Kepesertaan' },
  { id: 6, name: 'Fajar Tri P. Herlambang', nip: '08711', division_name: 'Kepesertaan' },
  { id: 7, name: 'Muhammad Ikhsan', nip: '05086', division_name: 'Kepesertaan' },
  { id: 8, name: 'Denis Filya Siburian', nip: '07830', division_name: 'Kepesertaan' },
  { id: 9, name: 'Renie Nur Aziza', nip: '220307', division_name: 'Kepesertaan' },
  { id: 10, name: 'Adnila Gunawan Putri', nip: '220357', division_name: 'Kepesertaan' },
  { id: 11, name: 'Naufal Akbar Putrasyahreza', nip: '230469', division_name: 'Kepesertaan' },
  { id: 12, name: 'Rizky Roylando Manurung', nip: '240186', division_name: 'Kepesertaan' },
  { id: 13, name: 'Maria Olivia Regina', nip: '250063', division_name: 'Kepesertaan' },
  { id: 14, name: 'Jonathan Aditya Ramadhan', nip: '250447', division_name: 'Kepesertaan' },
  { id: 15, name: 'Dwi Puji Astuti', nip: '250704', division_name: 'Kepesertaan' },
  { id: 16, name: 'Intan Ayunita Nurhanifah', nip: '260031', division_name: 'Kepesertaan' },
  { id: 17, name: 'Reghina Putri Nabillah', nip: '260118', division_name: 'Kepesertaan' },
  { id: 18, name: 'Mawar Siswanto', nip: '260399', division_name: 'Kepesertaan' },
  { id: 19, name: 'Farelia Faza Anggana', nip: '260400', division_name: 'Kepesertaan' },
  { id: 20, name: 'Vina Oktavia', nip: '260504', division_name: 'Kepesertaan' },
  { id: 21, name: 'Ari Wahyu Kirani', nip: '260510', division_name: 'Kepesertaan' },
  { id: 22, name: 'Andi Shalsabila Putri', nip: '260596', division_name: 'Kepesertaan' },
  { id: 23, name: 'Ayu Triana Situmorang', nip: '260816', division_name: 'Kepesertaan' },
  { id: 24, name: 'Zahra Aqilah', nip: '260759', division_name: 'Kepesertaan' },
  { id: 25, name: 'Dian Ashari', nip: '260684', division_name: 'Kepesertaan' },
  { id: 26, name: 'Naomy Nada Nisrina Ridhana Chaniago', nip: '260685', division_name: 'Kepesertaan' },
  { id: 27, name: 'Windy Riqul Aini', nip: '260761', division_name: 'Kepesertaan' },
  { id: 28, name: 'Yefi Kurniawan', nip: '07650', division_name: 'Mutu Layanan Fasilitas Kesehatan' },
  { id: 29, name: 'Damar Lia Susanti', nip: '04546', division_name: 'Mutu Layanan Fasilitas Kesehatan' },
  { id: 30, name: 'Retno Jiwo Utami', nip: '06855', division_name: 'Mutu Layanan Fasilitas Kesehatan' },
  { id: 31, name: 'Nur Qurotul Ayuni', nip: '07899', division_name: 'Mutu Layanan Fasilitas Kesehatan' },
  { id: 32, name: 'Muhammad Zamzami', nip: '08011', division_name: 'Mutu Layanan Fasilitas Kesehatan' },
  { id: 33, name: 'Tri Dame Uly Manullang', nip: '220031', division_name: 'Mutu Layanan Fasilitas Kesehatan' },
  { id: 34, name: 'Nur Setyawan', nip: '240183', division_name: 'Mutu Layanan Fasilitas Kesehatan' },
  { id: 35, name: 'Siti Nur Ainun Nadiyah', nip: '250703', division_name: 'Mutu Layanan Fasilitas Kesehatan' },
  { id: 36, name: 'Dewi Tri Arisanti', nip: '250701', division_name: 'Mutu Layanan Fasilitas Kesehatan' },
  { id: 37, name: 'Jessica Virginia Yola', nip: '260626', division_name: 'Mutu Layanan Fasilitas Kesehatan' },
  { id: 38, name: 'Yohana Dwita Lydia', nip: '220547', division_name: 'Mutu Layanan Kepesertaan' },
  { id: 39, name: 'Melani Tania Wijaya', nip: '230101', division_name: 'Mutu Layanan Kepesertaan' },
  { id: 40, name: 'Yulia Indah Rahmawati', nip: '230470', division_name: 'Mutu Layanan Kepesertaan' },
  { id: 41, name: 'Nopembri Marifin Nababan', nip: '250078', division_name: 'Mutu Layanan Kepesertaan' },
  { id: 42, name: 'Neyla Raihan Khoirunissa', nip: '250071', division_name: 'Mutu Layanan Kepesertaan' },
  { id: 43, name: 'Ayu Kusuma Wardhani', nip: '03703', division_name: 'Penjaminan Manfaat dan Utilisasi' },
  { id: 44, name: 'Nurul Fithri', nip: '07532', division_name: 'Penjaminan Manfaat dan Utilisasi' },
  { id: 45, name: 'Yoshika Kurniasari', nip: '07853', division_name: 'Penjaminan Manfaat dan Utilisasi' },
  { id: 46, name: 'Yunita', nip: '04455', division_name: 'Penjaminan Manfaat dan Utilisasi' },
  { id: 47, name: 'Dinda Indrawati', nip: '06062', division_name: 'Penjaminan Manfaat dan Utilisasi' },
  { id: 48, name: 'Putri Wulandari Cahyaningtyas', nip: '06851', division_name: 'Penjaminan Manfaat dan Utilisasi' },
  { id: 49, name: 'Erny Prian Kusuma', nip: '06867', division_name: 'Penjaminan Manfaat dan Utilisasi' },
  { id: 50, name: 'Asri Wijayati M', nip: '06990', division_name: 'Penjaminan Manfaat dan Utilisasi' },
  { id: 51, name: 'Rizka Yunita', nip: '07589', division_name: 'Penjaminan Manfaat dan Utilisasi' },
  { id: 52, name: 'Amin Khilmi Fadhilah', nip: '07671', division_name: 'Penjaminan Manfaat dan Utilisasi' },
  { id: 53, name: 'Rieza Gathari Gatot Sardjito', nip: '09652', division_name: 'Penjaminan Manfaat dan Utilisasi' },
  { id: 54, name: 'Dewan Prasetyo', nip: '05064', division_name: 'Penjaminan Manfaat dan Utilisasi' },
  { id: 55, name: 'Shiko Ariesona', nip: '05087', division_name: 'Penjaminan Manfaat dan Utilisasi' },
  { id: 56, name: 'Pratista Nurmalasari', nip: '05248', division_name: 'Penjaminan Manfaat dan Utilisasi' },
  { id: 57, name: 'Dimas Arryo Radytyo', nip: '06068', division_name: 'Penjaminan Manfaat dan Utilisasi' },
  { id: 58, name: 'Nurfitri', nip: '07585', division_name: 'Penjaminan Manfaat dan Utilisasi' },
  { id: 59, name: 'Hafidhotul Marfuah', nip: '07587', division_name: 'Penjaminan Manfaat dan Utilisasi' },
  { id: 60, name: 'Sigit Purnomo', nip: '07881', division_name: 'Penjaminan Manfaat dan Utilisasi' },
  { id: 61, name: 'Erzan Dhanalvin', nip: '02485', division_name: 'Perencanaan, Keuangan dan Pemeriksaan' },
  { id: 62, name: 'Ervina Kurniati', nip: '04630', division_name: 'Perencanaan, Keuangan dan Pemeriksaan' },
  { id: 63, name: 'Ghiki Lestari', nip: '06792', division_name: 'Perencanaan, Keuangan dan Pemeriksaan' },
  { id: 64, name: 'Arief Febriansyah', nip: '08054', division_name: 'Perencanaan, Keuangan dan Pemeriksaan' },
  { id: 65, name: 'Indah Sari Hastuti', nip: '06790', division_name: 'Perencanaan, Keuangan dan Pemeriksaan' },
  { id: 66, name: 'Delia Lestari', nip: '10344', division_name: 'Perencanaan, Keuangan dan Pemeriksaan' },
  { id: 67, name: 'Yuliana Sinta Anggraeni', nip: '220306', division_name: 'Perencanaan, Keuangan dan Pemeriksaan' },
  { id: 68, name: 'Putri Faraz Oktavi Yanti', nip: '250076', division_name: 'Perencanaan, Keuangan dan Pemeriksaan' },
  { id: 69, name: 'Sarah Abigail Yuranian', nip: '251206', division_name: 'Perencanaan, Keuangan dan Pemeriksaan' },
  { id: 70, name: 'Joice Elma Miranda Gulo', nip: '260398', division_name: 'Perencanaan, Keuangan dan Pemeriksaan' },
  { id: 71, name: 'Mohamad Irham Syahbana', nip: '06065', division_name: 'SDM, Umum, dan Komunikasi' },
  { id: 72, name: 'Giana Carlanadzova', nip: '09784', division_name: 'SDM, Umum, dan Komunikasi' },
  { id: 73, name: 'Sandy Kurniawan', nip: '06797', division_name: 'SDM, Umum, dan Komunikasi' },
  { id: 74, name: 'Dian Puspa Pratiwi', nip: '08815', division_name: 'SDM, Umum, dan Komunikasi' },
  { id: 75, name: 'Daniska Alisia Azzahra', nip: '250700', division_name: 'SDM, Umum, dan Komunikasi' },
  { id: 76, name: 'Ibnu Sina Al-Khotami', nip: '250702', division_name: 'SDM, Umum, dan Komunikasi' }
]

// State Dropdown Pemilihan Rekan / Penumpang
const usersList = ref([...defaultEmployeesList])
const isPassengerDropdownOpen = ref(false)
const passengerSearchQuery = ref('')
const selectedPassengersList = ref([])
const showCustomPassengerInput = ref(false)
const customPassengerName = ref('')
const passengerDropdownRef = ref(null)

// State Dropdown Pemohon
const isBorrowerDropdownOpen = ref(false)
const borrowerSearchQuery = ref('')
const isBorrowerCustom = ref(false)
const selectedBorrowerId = ref(null)
const borrowerDropdownRef = ref(null)

// Helper: Inisial Nama untuk Avatar
const getInitials = (name) => {
  if (!name) return '?'
  const parts = name.trim().split(/\s+/)
  if (parts.length === 1) return parts[0].slice(0, 2).toUpperCase()
  return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase()
}

// Cek apakah seorang rekan sudah ada di list terpilih
const isPassengerSelected = (name) => {
  if (!name) return false
  return selectedPassengersList.value.some(p => p.name.toLowerCase() === name.toLowerCase())
}

// Filtered list penumpang (mengecualikan nama pemohon)
const filteredPassengerUsers = computed(() => {
  const q = passengerSearchQuery.value.toLowerCase().trim()
  const borrowerName = (bookingForm.value.borrower_name || currentUser.value.name || '').toLowerCase()
  return usersList.value.filter(u => {
    if (u.name.toLowerCase() === borrowerName) return false
    if (!q) return true
    const div = (u.division_name || '').toLowerCase()
    const nip = (u.nip || '').toLowerCase()
    return u.name.toLowerCase().includes(q) || div.includes(q) || nip.includes(q)
  })
})

// Filtered list pemohon
const filteredBorrowerUsers = computed(() => {
  const q = borrowerSearchQuery.value.toLowerCase().trim()
  return usersList.value.filter(u => {
    if (!q) return true
    const div = (u.division_name || '').toLowerCase()
    const nip = (u.nip || '').toLowerCase()
    return u.name.toLowerCase().includes(q) || div.includes(q) || nip.includes(q)
  })
})

// Susun teks nama penumpang dan sinkronkan jumlah total orang
const syncPassengersSummary = () => {
  const borrowerName = bookingForm.value.borrower_name || currentUser.value.name || 'Dian Puspa Pratiwi'
  
  if (selectedPassengersList.value.length === 0) {
    bookingForm.value.passengers = `${borrowerName} & Tim`
    bookingForm.value.passengers_count = 1
  } else {
    const rekanList = selectedPassengersList.value.map(p => {
      return p.division_name ? `${p.name} (${p.division_name})` : p.name
    }).join(', ')
    bookingForm.value.passengers = `${borrowerName} (Pemohon) & ${rekanList}`
    bookingForm.value.passengers_count = 1 + selectedPassengersList.value.length
  }
}

// Toggle pilih/batal rekan dari dropdown
const togglePassenger = (user) => {
  const idx = selectedPassengersList.value.findIndex(p => p.name.toLowerCase() === user.name.toLowerCase())
  if (idx >= 0) {
    selectedPassengersList.value.splice(idx, 1)
  } else {
    selectedPassengersList.value.push({
      id: user.id,
      name: user.name,
      nip: user.nip,
      division_name: user.division_name || 'Operasional'
    })
  }
  syncPassengersSummary()
}

// Hapus rekan dari chips / badges
const removePassenger = (index) => {
  selectedPassengersList.value.splice(index, 1)
  syncPassengersSummary()
}

// Tambah rekan tamu luar / non-pegawai secara manual
const addCustomPassenger = () => {
  const name = customPassengerName.value.trim()
  if (!name) return
  if (!isPassengerSelected(name)) {
    selectedPassengersList.value.push({
      id: `custom_${Date.now()}`,
      name: name,
      nip: '-',
      division_name: 'Tamu / Rekan Kerja',
      isCustom: true
    })
    syncPassengersSummary()
  }
  customPassengerName.value = ''
  showCustomPassengerInput.value = false
}

// Pilih pemohon dari dropdown
const selectBorrower = (user) => {
  bookingForm.value.borrower_name = user.name
  bookingForm.value.borrower_division = user.division_name || 'Operasional Kantor'
  bookingForm.value.borrower_nip = user.nip || '-'
  selectedBorrowerId.value = user.id
  isBorrowerCustom.value = true
  isBorrowerDropdownOpen.value = false
  
  // Jika pemohon baru sempat ada di daftar rekan, hapus agar tidak ganda
  const existIdx = selectedPassengersList.value.findIndex(p => p.name.toLowerCase() === user.name.toLowerCase())
  if (existIdx >= 0) {
    selectedPassengersList.value.splice(existIdx, 1)
  }
  syncPassengersSummary()
}

// Reset pemohon ke akun aktif sendiri
const resetBorrowerToSelf = () => {
  const isReal = currentUser.value.name && currentUser.value.name.toLowerCase() !== 'admin'
  bookingForm.value.borrower_name = isReal ? currentUser.value.name : 'Dian Puspa Pratiwi'
  bookingForm.value.borrower_division = isReal ? currentUserDivision.value : 'SDM, Umum, dan Komunikasi'
  bookingForm.value.borrower_nip = currentUser.value.nip || '08815'
  selectedBorrowerId.value = currentUser.value?.id || null
  isBorrowerCustom.value = false
  isBorrowerDropdownOpen.value = false
  syncPassengersSummary()
}

// Ambil daftar users fresh dari backend API
const fetchUsers = async () => {
  try {
    const res = await $fetch(`${apiBase}/users`).catch(() => null)
    if (Array.isArray(res) && res.length > 0) {
      const valid = res.filter(u => u.name && u.name.toLowerCase() !== 'admin').map(u => ({
        id: u.id,
        name: u.name,
        nip: u.nip,
        division_name: u.division?.name || u.division_name || 'Operasional'
      }))
      if (valid.length > 0) {
        usersList.value = valid
      }
    }
  } catch (e) {
    console.warn('Menggunakan fallback daftar pengguna BPJS:', e)
  }
}

// Stepper jumlah orang yang pergi
const stepPassengers = (delta) => {
  const current = Number(bookingForm.value.passengers_count) || 1
  const updated = current + delta
  if (updated >= 1 && updated <= 20) {
    bookingForm.value.passengers_count = updated
  }
}

// Reset form
const resetForm = () => {
  const isReal = currentUser.value.name && currentUser.value.name.toLowerCase() !== 'admin'
  const initName = isReal ? currentUser.value.name : 'Dian Puspa Pratiwi'
  const initDiv = isReal ? currentUserDivision.value : 'SDM, Umum, dan Komunikasi'

  selectedPassengersList.value = []
  isBorrowerCustom.value = false
  selectedBorrowerId.value = null
  isPassengerDropdownOpen.value = false
  isBorrowerDropdownOpen.value = false

  bookingForm.value = {
    borrower_name: initName,
    borrower_division: initDiv,
    destinations: [''],
    destination_str: '',
    pickup_location: 'BPJS Kesehatan Cabang Jakarta Utara',
    pickup_lat: -6.1297591,
    pickup_lng: 106.9073865,
    destination_lat: null,
    destination_lng: null,
    distance_km: null,
    estimated_duration_minutes: null,
    google_maps_url: '',
    passengers_count: 1,
    passengers: `${initName} & Tim`,
    purpose: '',
    notes: ''
  }
  tripDate.value = new Date().toISOString().slice(0, 10)
  tripEndDate.value = new Date().toISOString().slice(0, 10)
  tripTime.value = '08:30'
  tripReturnTime.value = '17:00'
  quickDateFilter.value = 'today'
  isMultiDayTrip.value = false
  routineForm.value = {
    frequency: 'weekly',
    selectedDays: ['Senin'],
    departureTime: '08:30',
    returnTime: '17:00',
    startDate: new Date().toISOString().slice(0, 10),
    endDate: getDefaultEndDate()
  }
}

// Sinkronisasi data user aktif
const syncUser = async () => {
  if (typeof window !== 'undefined') {
    const raw = localStorage.getItem('auth_user')
    if (raw) {
      try {
        const u = JSON.parse(raw)
        if (u?.name) {
          currentUser.value = { ...currentUser.value, ...u }
        }
      } catch (e) {}
    }
  }

  if ((!currentUser.value.name || !currentUser.value.division) && userCookie.value) {
    try {
      const u = typeof userCookie.value === 'string' ? JSON.parse(userCookie.value) : userCookie.value
      if (u?.name) {
        currentUser.value = { ...currentUser.value, ...u }
      }
    } catch (e) {}
  }

  // Coba fetch profil fresh dari backend jika division belum lengkap
  if (!currentUser.value.division && tokenCookie.value) {
    try {
      const res = await $fetch(`${apiBase}/auth/me`, {
        headers: { Authorization: `Bearer ${tokenCookie.value}` }
      })
      if (res?.user) {
        currentUser.value = { ...currentUser.value, ...res.user }
        if (typeof window !== 'undefined') {
          localStorage.setItem('auth_user', JSON.stringify(currentUser.value))
        }
      }
    } catch (e) {}
  }

  const isReal = currentUser.value.name && currentUser.value.name.toLowerCase() !== 'admin'
  const safeName = isReal ? currentUser.value.name : 'Dian Puspa Pratiwi'
  const safeDiv = isReal ? currentUserDivision.value : 'SDM, Umum, dan Komunikasi'

  if (!bookingForm.value.borrower_name || bookingForm.value.borrower_name.toLowerCase() === 'admin') {
    bookingForm.value.borrower_name = safeName
  }
  if (!bookingForm.value.borrower_division || bookingForm.value.borrower_division.toLowerCase() === 'admin') {
    bookingForm.value.borrower_division = safeDiv
  }

  // Jika nama rekan kosong atau mengandung kata admin, defaultkan nama peminjam
  if (!bookingForm.value.passengers || bookingForm.value.passengers.toLowerCase().includes('admin')) {
    bookingForm.value.passengers = `${safeName} & Tim`
  }
}

// Kirim Permohonan Booking Mobil
const submitCarBooking = async () => {
  // Validasi destinasi tidak boleh kosong
  const cleanedDestinations = bookingForm.value.destinations.map(d => d.trim()).filter(Boolean)
  if (cleanedDestinations.length === 0) {
    alertMsg.value = {
      type: 'error',
      title: 'Destinasi Belum Diisi',
      text: 'Harap masukkan minimal satu lokasi tujuan kedinasan.'
    }
    return
  }

  if (!bookingForm.value.purpose || !bookingForm.value.purpose.trim()) {
    alertMsg.value = {
      type: 'error',
      title: 'Tujuan Belum Diisi',
      text: 'Harap jelaskan tujuan / keperluan kedinasan ke lokasi tersebut.'
    }
    return
  }

  // Validasi khusus jadwal rutin & jam pulang
  const isRoutine = bookingType.value === 'routine'
  if (isRoutine) {
    if (routineForm.value.frequency === 'weekly' || routineForm.value.frequency === 'biweekly') {
      if (!routineForm.value.selectedDays || routineForm.value.selectedDays.length === 0) {
        alertMsg.value = {
          type: 'error',
          title: 'Hari Pelaksanaan Belum Dipilih',
          text: 'Harap pilih minimal satu hari pelaksanaan pasti untuk kunjungan rutin.'
        }
        return
      }
    }
    if (!routineForm.value.returnTime) {
      alertMsg.value = {
        type: 'error',
        title: 'Perkiraan Jam Pulang Belum Diisi',
        text: 'Harap tentukan estimasi jam pulang untuk kunjungan rutin.'
      }
      return
    }
    if (routineForm.value.returnTime <= routineForm.value.departureTime) {
      alertMsg.value = {
        type: 'error',
        title: 'Jam Pulang Rutin Tidak Sesuai',
        text: 'Perkiraan jam pulang rutin harus lebih lambat dari jam keberangkatan rutin.'
      }
      return
    }
  } else {
    // Mode Sekali Jalan (Insidental): Wajib isi Perkiraan Jam Pulang
    if (!tripReturnTime.value) {
      alertMsg.value = {
        type: 'error',
        title: 'Perkiraan Jam Pulang Belum Diisi',
        text: 'Harap tentukan perkiraan jam pulang dari perjalanan dinas (wajib diisi).'
      }
      return
    }
    if (!isMultiDayTrip.value && tripReturnTime.value <= tripTime.value) {
      alertMsg.value = {
        type: 'error',
        title: 'Jam Pulang Tidak Sesuai',
        text: 'Perkiraan jam pulang harus lebih lambat dari jam keberangkatan pada hari yang sama.'
      }
      return
    }
  }

  isSubmitting.value = true
  alertMsg.value = null

  const headers = {}
  if (tokenCookie.value) {
    headers['Authorization'] = `Bearer ${tokenCookie.value}`
  }

  const destinationSummary = cleanedDestinations.join(' ➔ ')

  // Siapkan waktu start_date dan end_date untuk incidental dan rutin
  const returnHour = tripReturnTime.value || '17:00'
  let finalStartDate = `${tripDate.value}T${tripTime.value}:00`
  let finalEndDate = isMultiDayTrip.value ? `${tripEndDate.value}T${returnHour}:00` : `${tripDate.value}T${returnHour}:00`

  if (isRoutine && routineForm.value.startDate && routineForm.value.departureTime) {
    const routineReturnHour = routineForm.value.returnTime || '17:00'
    finalStartDate = `${routineForm.value.startDate}T${routineForm.value.departureTime}:00`
    finalEndDate = `${routineForm.value.endDate}T${routineReturnHour}:00`
  }

  const rawBorrower = (bookingForm.value.borrower_name || '').trim()
  const isRealBorrower = rawBorrower && rawBorrower.toLowerCase() !== 'admin'
  const isRealCurrent = currentUser.value?.name && currentUser.value.name.toLowerCase() !== 'admin'
  
  const cleanBorrowerName = isRealBorrower 
    ? rawBorrower 
    : (isRealCurrent ? currentUser.value.name : 'Dian Puspa Pratiwi')

  const cleanBorrowerDivision = (bookingForm.value.borrower_division || '').trim() || (isRealCurrent ? currentUserDivision.value : 'SDM, Umum, dan Komunikasi')

  const resolvedUserId = (isBorrowerCustom.value && selectedBorrowerId.value)
    ? selectedBorrowerId.value
    : ((currentUser.value?.id && isRealCurrent) ? currentUser.value.id : 82)

  const pickupLoc = bookingForm.value.pickup_location || 'BPJS Kesehatan Cabang Jakarta Utara'
  let finalGoogleMapsUrl = bookingForm.value.google_maps_url
  if (!finalGoogleMapsUrl || !finalGoogleMapsUrl.includes('google.com/maps')) {
    const allStops = [pickupLoc, ...cleanedDestinations]
    finalGoogleMapsUrl = `https://www.google.com/maps/dir/${allStops.map(s => encodeURIComponent(s)).join('/')}`
  }

  const payload = {
    user_id: resolvedUserId,
    borrower_name: cleanBorrowerName,
    borrower_division: cleanBorrowerDivision,
    borrower_nip: currentUser.value?.nip && currentUser.value.nip !== '12345' ? currentUser.value.nip : '08815',
    destinations: cleanedDestinations,
    destination: destinationSummary,
    pickup_location: pickupLoc,
    pickup_lat: bookingForm.value.pickup_lat || -6.1297591,
    pickup_lng: bookingForm.value.pickup_lng || 106.9073865,
    destination_lat: bookingForm.value.destination_lat,
    destination_lng: bookingForm.value.destination_lng,
    distance_km: bookingForm.value.distance_km,
    estimated_duration_minutes: bookingForm.value.estimated_duration_minutes,
    google_maps_url: finalGoogleMapsUrl,
    trip_mode: isRoutine ? 'routine' : 'incidental',
    purpose: bookingForm.value.purpose.trim(),
    passengers: bookingForm.value.passengers || `${cleanBorrowerName} & Tim`,
    passengers_count: bookingForm.value.passengers_count || 1,
    start_date: finalStartDate,
    end_date: finalEndDate,
    booking_type: isRoutine ? 'routine' : 'incidental',
    is_routine: isRoutine,
    routine_schedule: isRoutine ? routineScheduleSummary.value : null,
    recurring_frequency: isRoutine ? routineForm.value.frequency : null,
    recurring_days: isRoutine ? routineForm.value.selectedDays.join(', ') : null,
    notes: bookingForm.value.notes ? bookingForm.value.notes.trim() : (isRoutine ? `Jadwal Rutin: ${routineScheduleSummary.value}` : (isMultiDayTrip.value ? `Perjalanan Dinas Multi-Hari: ${formatIndonesianDate(tripDate.value)} s/d ${formatIndonesianDate(tripEndDate.value)} (Jam Pulang: ${returnHour} WIB)` : `Keperluan: ${bookingForm.value.purpose.trim()} (Estimasi Pulang: ${returnHour} WIB)`))
  }

  const scheduleFormatted = isRoutine
    ? routineScheduleSummary.value
    : (isMultiDayTrip.value
        ? `${formatIndonesianDate(tripDate.value)} Jam ${tripTime.value} WIB s/d ${formatIndonesianDate(tripEndDate.value)} Jam ${returnHour} WIB (Estimasi Pulang)`
        : `${formatIndonesianDate(tripDate.value)} • ${tripTime.value} s/d ${returnHour} WIB (Estimasi Pulang)`)

  try {
    const res = await $fetch(`${apiBase}/vehicle-bookings`, {
      method: 'POST',
      headers,
      body: payload
    })

    // Simpan ke riwayat lokal terisolasi per user
    if (typeof window !== 'undefined') {
      try {
        const userStorageKey = `user_vehicle_history_${resolvedUserId}`
        const localHist = JSON.parse(localStorage.getItem(userStorageKey) || '[]')
        localHist.unshift({
          id: res?.booking?.id || Date.now(),
          user_id: resolvedUserId,
          borrower_name: payload.borrower_name,
          borrower_division: payload.borrower_division,
          borrower_nip: payload.borrower_nip,
          vehicle: null,
          vehicle_id: null,
          destination: destinationSummary,
          purpose: bookingForm.value.purpose.trim(),
          passengers: payload.passengers,
          passengers_count: payload.passengers_count,
          start_date: payload.start_date,
          end_date: payload.end_date,
          booking_type: payload.booking_type,
          is_routine: payload.is_routine,
          routine_schedule: payload.routine_schedule,
          status: 'pending',
          notes: payload.notes,
          created_at: new Date().toISOString()
        })
        localStorage.setItem(userStorageKey, JSON.stringify(localHist.slice(0, 50)))
        localStorage.removeItem('user_vehicle_history')
      } catch (e) {}
    }

    lastSubmittedData.value = {
      destination: destinationSummary,
      passengers_count: payload.passengers_count,
      purpose: payload.purpose,
      is_routine: isRoutine,
      routine_schedule: payload.routine_schedule,
      formatted_schedule: scheduleFormatted
    }
    showSuccessModal.value = true
    resetForm()
  } catch (err) {
    try {
      const fallback = await $fetch(`${apiBase}/v1/manage/vehicle-bookings`, {
        method: 'POST',
        headers,
        body: payload
      })

      if (typeof window !== 'undefined') {
        try {
          const userStorageKey = `user_vehicle_history_${resolvedUserId}`
          const localHist = JSON.parse(localStorage.getItem(userStorageKey) || '[]')
          localHist.unshift({
            id: fallback?.booking?.id || Date.now(),
            user_id: resolvedUserId,
            borrower_name: payload.borrower_name,
            borrower_division: payload.borrower_division,
            borrower_nip: payload.borrower_nip,
            vehicle: null,
            vehicle_id: null,
            destination: destinationSummary,
            purpose: bookingForm.value.purpose.trim(),
            passengers: payload.passengers,
            passengers_count: payload.passengers_count,
            start_date: payload.start_date,
            end_date: payload.end_date,
            booking_type: payload.booking_type,
            is_routine: payload.is_routine,
            routine_schedule: payload.routine_schedule,
            status: 'pending',
            notes: payload.notes,
            created_at: new Date().toISOString()
          })
          localStorage.setItem(userStorageKey, JSON.stringify(localHist.slice(0, 50)))
          localStorage.removeItem('user_vehicle_history')
        } catch (e) {}
      }

      lastSubmittedData.value = {
        destination: destinationSummary,
        passengers_count: payload.passengers_count,
        purpose: payload.purpose,
        is_routine: isRoutine,
        routine_schedule: payload.routine_schedule,
        formatted_schedule: scheduleFormatted
      }
      showSuccessModal.value = true
      resetForm()
    } catch (innerErr) {
      const msg = innerErr?.data?.message || err?.data?.message || 'Gagal mengirim permohonan peminjaman mobil.'
      alertMsg.value = {
        type: 'error',
        title: 'Pengiriman Gagal',
        text: msg
      }
    }
  } finally {
    isSubmitting.value = false
  }
}

onMounted(() => {
  if (typeof window !== 'undefined') {
    const savedTheme = localStorage.getItem('user_theme')
    if (savedTheme) {
      isDark.value = savedTheme === 'dark'
    }

    // Listener click outside & escape key untuk menutup dropdown
    window.addEventListener('click', (e) => {
      if (passengerDropdownRef.value && !passengerDropdownRef.value.contains(e.target)) {
        isPassengerDropdownOpen.value = false
      }
      if (borrowerDropdownRef.value && !borrowerDropdownRef.value.contains(e.target)) {
        isBorrowerDropdownOpen.value = false
      }
    })

    window.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') {
        isPassengerDropdownOpen.value = false
        isBorrowerDropdownOpen.value = false
      }
    })
  }
  syncUser()
  fetchUsers()
})
</script>

<style scoped>
/* ========================================================================== */
/* BACKGROUND GARASI MOBIL (TETAP NATURAL TANPA LAPISAN GELAP/TERANG)        */
/* ========================================================================== */
.kendaraan-page {
  position: relative;
  background-color: transparent;
  overflow-x: hidden;
  transition: color 0.25s ease;
}

.kendaraan-bg-backdrop {
  position: fixed;
  inset: 0;
  background-image: url('/images/garage-bg.jpg');
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
  --box-bg: rgba(11, 19, 32, 0.58);
  --box-border: #10b981;
  --box-shadow: 0 0 24px rgba(16, 185, 129, 0.4), inset 0 0 16px rgba(16, 185, 129, 0.08);
  --inner-bg: rgba(0, 0, 0, 0.48);
  --inner-border: rgba(16, 185, 129, 0.45);
  --text-main: #ffffff;
  --text-sub: #cbd5e1;
  --text-body: #f8fafc;
  --input-bg: rgba(5, 12, 24, 0.75);
  --input-border: rgba(16, 185, 129, 0.55);
  --modal-bg: rgba(11, 19, 32, 0.92);
  --modal-backdrop: rgba(0, 0, 0, 0.78);
  --divider: rgba(16, 185, 129, 0.3);
}

/* ========================================================================== */
/* THEME LIGHT: KOTAK TRANSPARAN & GARIS WARNA ELEGAN                        */
/* ========================================================================== */
.theme-light {
  --box-bg: rgba(255, 255, 255, 0.78);
  --box-border: rgba(203, 213, 225, 0.95);
  --box-shadow: 0 10px 30px rgba(0, 0, 0, 0.09);
  --inner-bg: rgba(255, 255, 255, 0.65);
  --inner-border: rgba(203, 213, 225, 0.85);
  --text-main: #0f172a;
  --text-sub: #475569;
  --text-body: #1e293b;
  --input-bg: rgba(255, 255, 255, 0.9);
  --input-border: #cbd5e1;
  --modal-bg: rgba(255, 255, 255, 0.96);
  --modal-backdrop: rgba(15, 23, 42, 0.5);
  --divider: rgba(226, 232, 240, 0.85);
}

/* GLASSMORPHISM BOXES */
.banner-card,
.form-container-card,
.modal-box {
  background-color: var(--box-bg);
  border: 1px solid var(--box-border);
  box-shadow: var(--box-shadow);
  backdrop-filter: blur(18px);
  -webkit-backdrop-filter: blur(18px);
  color: var(--text-body);
  transition: all 0.25s ease;
  min-width: 0;
  box-sizing: border-box;
  overflow: hidden;
}

.form-section-box {
  background-color: var(--inner-bg);
  border: 1px solid var(--inner-border);
  min-width: 0;
  box-sizing: border-box;
}

/* TYPE SELECTOR BUTTONS */
.type-btn {
  background-color: var(--inner-bg);
  border-color: var(--inner-border);
  color: var(--text-body);
}

.type-btn-active {
  border-color: #10b981;
  background-color: rgba(16, 185, 129, 0.12);
  box-shadow: 0 0 16px rgba(16, 185, 129, 0.35);
}

.type-btn-active-cyan {
  border-color: #06b6d4;
  background-color: rgba(6, 182, 212, 0.15);
  box-shadow: 0 0 16px rgba(6, 182, 212, 0.4);
}

.type-btn-active-amber {
  border-color: #f59e0b;
  background-color: rgba(245, 158, 11, 0.15);
  box-shadow: 0 0 16px rgba(245, 158, 11, 0.4);
}

.type-btn-inactive {
  opacity: 0.75;
}

.type-btn-inactive:hover {
  opacity: 1;
  border-color: var(--divider);
}



/* TEXT COLOR OVERRIDES */
.banner-title,
.section-heading,
.form-label,
.modal-title {
  color: var(--text-main);
}

.text-subtle {
  color: var(--text-sub);
}

.banner-desc {
  color: var(--text-sub);
}

.form-divider {
  border-color: var(--divider);
}

/* BADGES */
.badge-procedure {
  background-color: rgba(16, 185, 129, 0.15);
  color: #10b981;
  border: 1px solid rgba(16, 185, 129, 0.35);
}

.badge-count {
  background-color: var(--inner-bg);
  color: #10b981;
  border: 1px solid var(--inner-border);
}

.waypoint-badge {
  background-color: var(--input-bg);
  border: 1px solid var(--input-border);
  color: #10b981;
}

/* INPUTS */
.form-input {
  background-color: var(--input-bg);
  border: 1px solid var(--input-border);
  color: var(--text-main);
}

.theme-dark .form-input,
.theme-dark input[type="date"],
.theme-dark input[type="time"],
.theme-dark input[type="datetime-local"] {
  color-scheme: dark;
}

.theme-light .form-input,
.theme-light input[type="date"],
.theme-light input[type="time"],
.theme-light input[type="datetime-local"] {
  color-scheme: light;
}

/* Kalender & jam picker terlihat jelas/putih di mode gelap */
.theme-dark input[type="date"]::-webkit-calendar-picker-indicator,
.theme-dark input[type="time"]::-webkit-calendar-picker-indicator,
.theme-dark input[type="datetime-local"]::-webkit-calendar-picker-indicator {
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

.form-input:focus {
  border-color: #10b981;
  box-shadow: 0 0 10px rgba(16, 185, 129, 0.35);
}

.form-input-readonly {
  opacity: 0.85;
  background-color: rgba(0, 0, 0, 0.2);
}

/* BUTTONS */
.btn-view-history {
  background-color: var(--inner-bg);
  border: 1px solid var(--inner-border);
  color: var(--text-main);
}

.btn-view-history:hover {
  border-color: #10b981;
  color: #10b981;
}

.btn-add-dest {
  background-color: rgba(16, 185, 129, 0.15);
  border: 1px dashed #10b981;
  color: #10b981;
}

.btn-add-dest:hover {
  background-color: #10b981;
  color: #ffffff;
  border-style: solid;
  box-shadow: 0 4px 14px rgba(16, 185, 129, 0.35);
}

.btn-remove-dest {
  background-color: rgba(239, 68, 68, 0.15);
  border: 1px solid rgba(239, 68, 68, 0.4);
  color: #ef4444;
}

.btn-remove-dest:hover {
  background-color: #ef4444;
  color: #ffffff;
}

.btn-stepper {
  background-color: var(--input-bg);
  border: 1px solid var(--input-border);
  color: var(--text-main);
}

.btn-stepper:hover:not(:disabled) {
  border-color: #10b981;
  color: #10b981;
}

.btn-reset-form {
  background-color: var(--inner-bg);
  border: 1px solid var(--inner-border);
  color: var(--text-sub);
}

.btn-reset-form:hover {
  color: var(--text-main);
  border-color: #cbd5e1;
}

.btn-submit-booking {
  background: linear-gradient(135deg, #10b981 0%, #059669 100%);
  color: #ffffff;
  box-shadow: 0 4px 16px rgba(16, 185, 129, 0.4);
}

.btn-submit-routine {
  background: linear-gradient(135deg, #06b6d4 0%, #0284c7 100%);
  box-shadow: 0 4px 16px rgba(6, 182, 212, 0.4);
}

.btn-submit-booking:hover:not(:disabled) {
  transform: translateY(-1px);
}

.btn-modal-cancel {
  background-color: var(--inner-bg);
  border: 1px solid var(--inner-border);
  color: var(--text-sub);
}

.btn-modal-cancel:hover {
  color: var(--text-main);
}

/* ALERTS */
.alert-card {
  backdrop-filter: blur(14px);
  -webkit-backdrop-filter: blur(14px);
}

.alert-success {
  background-color: var(--box-bg);
  border-color: rgba(16, 185, 129, 0.5);
  color: #10b981;
}

.alert-error {
  background-color: var(--box-bg);
  border-color: rgba(239, 68, 68, 0.5);
  color: #ef4444;
}

.btn-close-alert {
  color: var(--text-sub);
}

.btn-close-alert:hover {
  color: var(--text-main);
}

/* MODAL */
.modal-backdrop {
  background-color: var(--modal-backdrop);
}

.modal-box {
  background-color: var(--modal-bg);
}

/* ANIMATIONS */
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(-6px); }
  to { opacity: 1; transform: translateY(0); }
}

@keyframes scaleUp {
  from { opacity: 0; transform: scale(0.95); }
  to { opacity: 1; transform: scale(1); }
}

.animate-fade-in {
  animation: fadeIn 0.25s ease-out;
}

.animate-scale-up {
  animation: scaleUp 0.25s ease-out;
}

/* ========================================================================== */
/* PASSENGER & BORROWER DROPDOWN POPOVER & CHIPS (RESPONSIF & PRESISI)       */
/* ========================================================================== */
.dropdown-trigger-btn {
  background-color: var(--input-bg);
  border-color: var(--input-border);
  color: var(--text-main);
}

.dropdown-trigger-btn:hover {
  border-color: #10b981;
}

.passenger-dropdown-popover {
  background-color: var(--modal-bg);
  border-color: var(--box-border);
  box-shadow: 0 16px 40px rgba(0, 0, 0, 0.45), 0 0 12px rgba(16, 185, 129, 0.15);
}

.passenger-option-item {
  color: var(--text-main);
}

.passenger-option-default:hover {
  background-color: rgba(16, 185, 129, 0.12);
}

.passenger-option-selected {
  background-color: rgba(16, 185, 129, 0.18);
  border: 1px solid rgba(16, 185, 129, 0.45);
}

.passenger-chip {
  background-color: rgba(16, 185, 129, 0.15);
  border: 1px solid rgba(16, 185, 129, 0.35);
  color: #10b981;
}

.chip-remove-btn {
  background-color: rgba(16, 185, 129, 0.25);
  color: #10b981;
}

.chip-remove-btn:hover {
  background-color: #ef4444;
  color: #ffffff;
}

.custom-scrollbar::-webkit-scrollbar {
  width: 5px;
}

.custom-scrollbar::-webkit-scrollbar-track {
  background: rgba(0, 0, 0, 0.1);
  border-radius: 4px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
  background: rgba(16, 185, 129, 0.35);
  border-radius: 4px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
  background: rgba(16, 185, 129, 0.6);
}
</style>