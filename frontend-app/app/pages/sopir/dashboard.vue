<template>
  <div class="min-h-screen bg-slate-950 text-slate-100 font-sans pb-16">
    <!-- TOPBAR PENGEMUDI TERPADU (TIDAK TERLALU KE ATAS) -->
    <DriverTopbar />

    <main class="max-w-6xl mx-auto px-3 sm:px-6 py-4 space-y-6">
      <!-- Banner Pop-up Pesanan Baru Masuk Real-Time (Animasi Berkedip) -->
      <div
        v-if="incomingAlert"
        class="p-4 rounded-xl border border-amber-500/50 bg-gradient-to-r from-amber-950/90 via-slate-900 to-amber-950/90 text-white shadow-2xl shadow-amber-500/20 flex items-center justify-between gap-3 animate-pulse"
      >
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-full bg-amber-500/20 border border-amber-400 text-amber-300 flex items-center justify-center text-xl shrink-0">
            🔔
          </div>
          <div>
            <div class="flex items-center gap-2">
              <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-wider bg-amber-500 text-slate-950">Pesanan Baru Masuk!</span>
              <span class="text-xs text-slate-300 font-mono">{{ incomingAlert.time }}</span>
            </div>
            <p class="text-sm font-bold text-white mt-0.5">
              {{ incomingAlert.borrower }} • Ke: <span class="text-amber-300">{{ incomingAlert.destination }}</span>
            </p>
          </div>
        </div>
        <div class="flex items-center gap-2 shrink-0">
          <button @click="incomingAlert = null" class="text-slate-400 hover:text-white px-2 py-1 text-sm font-bold">✕</button>
        </div>
      </div>

      <!-- Notifikasi Alert -->
      <div v-if="alertMsg" class="p-4 rounded-xl border flex items-center justify-between transition" :class="alertMsg.type === 'error' ? 'bg-red-500/10 border-red-500/30 text-red-300' : 'bg-emerald-500/10 border-emerald-500/30 text-emerald-300'">
        <div class="flex items-center gap-3">
          <span class="text-lg">{{ alertMsg.type === 'error' ? '⚠️' : '✅' }}</span>
          <p class="text-xs md:text-sm font-medium">{{ alertMsg.text }}</p>
        </div>
        <button @click="alertMsg = null" class="text-slate-400 hover:text-white text-xs">✕</button>
      </div>

      <!-- ============================================================= -->
      <!-- 1. TEMPAT SCAN & STATUS PERJALANAN AKTIF PENGEMUDI             -->
      <!-- ============================================================= -->
      <section class="bg-gradient-to-br from-slate-900 via-slate-900 to-slate-800 rounded-2xl border border-slate-800 p-5 md:p-6 shadow-xl relative overflow-hidden">
        <div class="absolute -right-12 -bottom-12 w-48 h-48 bg-amber-500/5 rounded-full blur-3xl pointer-events-none"></div>

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-slate-800">
          <div>
            <div class="flex items-center gap-2 mb-1">
              <span class="w-2.5 h-2.5 rounded-full" :class="activeTrip ? (activeTrip.status_key === 'departed' ? 'bg-amber-400 animate-ping' : 'bg-cyan-400 animate-pulse') : 'bg-emerald-400'"></span>
              <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Status Pengemudi</span>
            </div>
            <h2 class="text-lg md:text-xl font-black text-white">
              {{ activeTrip ? (activeTrip.status_key === 'departed' ? '🛫 SEDANG MENGANTARKAN PEGAWAI' : '🔄 DALAM PERJALANAN PULANG') : '🅿️ STANDBY DI POOL KANTOR' }}
            </h2>
          </div>

          <div v-if="activeTrip" class="flex items-center gap-2">
            <span class="px-3 py-1 rounded-lg text-xs font-mono font-bold bg-slate-800 text-amber-300 border border-slate-700">
              {{ activeTrip.license_plate }}
            </span>
            <span class="px-3 py-1 rounded-lg text-xs font-semibold bg-amber-500/20 text-amber-300 border border-amber-500/40">
              {{ activeTrip.name }}
            </span>
          </div>
        </div>

        <!-- Detail Perjalanan Aktif (Jika Sedang Berjalan) -->
        <div v-if="activeTrip" class="grid grid-cols-1 sm:grid-cols-3 gap-3 my-4">
          <div class="bg-slate-950/60 p-4 rounded-xl border border-slate-800/80 space-y-1.5">
            <span class="text-xs text-slate-400 block font-medium">📍 Lokasi Tujuan:</span>
            <strong class="text-sm text-amber-300 block leading-relaxed">{{ activeTrip.current_destination || '-' }}</strong>
          </div>
          <div class="bg-slate-950/60 p-4 rounded-xl border border-slate-800/80 space-y-1.5">
            <span class="text-xs text-slate-400 block font-medium">👥 Pegawai yang Diantar:</span>
            <strong class="text-sm text-slate-200 block leading-relaxed">{{ activeTrip.current_passengers || '-' }}</strong>
            <small class="text-xs text-slate-400 block mt-0.5">Divisi: {{ activeTrip.current_division || 'Umum' }}</small>
          </div>
          <div class="bg-slate-950/60 p-4 rounded-xl border border-slate-800/80 space-y-1.5">
            <span class="text-xs text-slate-400 block font-medium">⏱️ Waktu Berangkat:</span>
            <strong class="text-sm text-slate-200 block leading-relaxed">{{ formatDateTime(activeTrip.trip_started_at) }}</strong>
          </div>
        </div>

        <!-- TEMPAT SCAN: DENGAN LOGO KAMERA KECIL & RAPI -->
        <div class="mt-4 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3.5 p-4 sm:p-5 rounded-xl bg-slate-950/80 border border-slate-800">
          <div class="flex items-center gap-3.5">
            <div class="w-10 h-10 rounded-xl bg-amber-500/10 border border-amber-500/30 text-amber-400 flex items-center justify-center shrink-0">
              <!-- LOGO KAMERA KECIL -->
              <svg viewBox="0 0 24 24" class="w-4 h-4 text-amber-400" fill="currentColor">
                <path d="M4 4h3l2-2h6l2 2h3a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2zm8 3a5 5 0 1 0 0 10 5 5 0 0 0 0-10zm0 2a3 3 0 1 1 0 6 3 3 0 0 1 0-6z"/>
              </svg>
            </div>
            <div class="space-y-0.5">
              <span class="text-sm font-bold text-white block leading-snug">Tempat Scan Barcode Mobil</span>
              <p class="text-xs text-slate-400 leading-relaxed">Scan barcode mobil dinas saat berangkat atau selesai</p>
            </div>
          </div>

          <button
            @click="openUnifiedScanModal"
            class="px-4 py-2.5 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 text-white rounded-xl font-bold text-xs sm:text-sm shadow-md transition-all active:scale-95 flex items-center justify-center gap-2 border border-amber-400/30 shrink-0"
          >
            <!-- LOGO KAMERA KECIL PADA TOMBOL -->
            <svg viewBox="0 0 24 24" class="w-4 h-4 text-white" fill="currentColor">
              <path d="M4 4h3l2-2h6l2 2h3a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2zm8 3a5 5 0 1 0 0 10 5 5 0 0 0 0-10zm0 2a3 3 0 1 1 0 6 3 3 0 0 1 0-6z"/>
            </svg>
            <span>Buka Kamera Scan</span>
          </button>
        </div>
      </section>

      <!-- ============================================================= -->
      <!-- 2. NOTIFIKASI PEMBERITAHUAN TUGAS PERJALANAN DINAS            -->
      <!-- ============================================================= -->
      <section class="bg-slate-900 rounded-2xl border border-slate-800 p-4 sm:p-6 shadow-xl space-y-4">
        <div class="flex items-center justify-between flex-wrap gap-2.5 pb-3 border-b border-slate-800/80">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-orange-500/20 text-orange-400 flex items-center justify-center font-bold text-lg shrink-0">
              🔔
            </div>
            <div>
              <h3 class="font-bold text-sm sm:text-base text-white flex items-center gap-2 flex-wrap leading-snug">
                <span>Notifikasi Tugas Perjalanan Dinas</span>
                <span v-if="pendingBookings.length > 0" class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                  {{ pendingBookings.length }} Siap Berangkat
                </span>
              </h3>
              <p class="text-xs text-slate-400 leading-relaxed mt-1">Peminjaman mobil telah disetujui. Konfirmasi untuk scan barcode mobil saat berangkat.</p>
            </div>
          </div>

          <button @click="fetchBookings" class="text-xs text-amber-400 hover:text-amber-300 flex items-center gap-1 font-semibold py-1.5 px-3 rounded-lg bg-amber-500/10 border border-amber-500/20">
            <span>🔄 Cek Booking</span>
          </button>
        </div>

        <!-- Daftar Kartu Permohonan Booking -->
        <div v-if="pendingBookings.length === 0" class="p-8 rounded-xl bg-slate-950/60 border border-slate-800 text-center text-xs text-slate-500 leading-relaxed">
          ✨ Tidak ada jadwal perjalanan baru yang menunggu keberangkatan.
        </div>

        <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div
            v-for="b in pendingBookings"
            :key="b.id"
            class="p-4 sm:p-5 rounded-2xl border border-emerald-500/40 bg-emerald-950/20 flex flex-col justify-between transition hover:border-emerald-500 shadow-md space-y-4"
          >
            <div class="space-y-3">
              <div class="flex items-center justify-between flex-wrap gap-2">
                <span v-if="b.is_routine || b.booking_type === 'routine'" class="px-2.5 py-1 rounded-lg text-xs font-bold bg-cyan-500/20 text-cyan-300 border border-cyan-500/30">
                  🔄 Rutin Terjadwal
                </span>
                <span v-else class="px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                  🚗 Sekali Jalan • Siap Berangkat
                </span>
                <span class="text-xs text-slate-400 font-mono">{{ formatDateTime(b.start_date || b.created_at) }}</span>
              </div>

              <div class="flex items-center justify-between flex-wrap gap-2 pt-1">
                <h4 class="font-bold text-sm sm:text-base text-white flex items-center gap-1.5 leading-snug">
                  <span>🚗</span>
                  <span>{{ b.vehicle?.name ? `${b.vehicle.name} (${b.vehicle.license_plate})` : 'Mobil Pool / Ditentukan Saat Berangkat' }}</span>
                </h4>
                <span v-if="b.driver_name" class="px-2.5 py-0.5 rounded-md text-xs font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30">
                  👨‍✈️ Ditugaskan: {{ b.driver_name }} {{ b.driver_id === currentUser?.id ? '(Anda)' : '' }}
                </span>
                <span v-else class="px-2.5 py-0.5 rounded-md text-xs font-bold bg-slate-800 text-slate-300 border border-slate-700">
                  👨‍✈️ Terbuka untuk Pool
                </span>
              </div>

              <!-- Detail Penugasan Rapi & Berjarak Nyaman (Tidak Dempet di Mobile) -->
              <div class="bg-slate-950/80 p-3.5 sm:p-4 rounded-xl border border-slate-800/90 space-y-3 text-xs">
                <div v-if="b.routine_schedule" class="text-cyan-300 font-semibold bg-cyan-950/30 px-3 py-2 rounded-lg border border-cyan-500/20 text-xs leading-relaxed">
                  🗓️ Pola Rutin: {{ b.routine_schedule }}
                </div>

                <div v-if="b.pickup_location" class="flex flex-col sm:flex-row sm:items-start gap-1 sm:gap-2">
                  <span class="text-slate-400 shrink-0 text-xs font-medium sm:w-28">🟢 Titik Jemput:</span>
                  <span class="font-bold text-emerald-300 leading-relaxed flex-1">{{ b.pickup_location }}</span>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-start gap-1 sm:gap-2">
                  <span class="text-slate-400 shrink-0 text-xs font-medium sm:w-28">📍 Rute Tujuan:</span>
                  <div class="flex-1">
                    <div v-if="parseDestinations(b.destination).length > 1" class="space-y-1.5 mt-0.5">
                      <div
                        v-for="(destItem, dIdx) in parseDestinations(b.destination)"
                        :key="dIdx"
                        class="flex items-center gap-2 text-xs text-white"
                      >
                        <span class="w-4 h-4 rounded-full bg-rose-500/20 border border-rose-500/40 text-[10px] font-bold text-rose-300 flex items-center justify-center shrink-0">
                          {{ dIdx + 1 }}
                        </span>
                        <span class="font-bold leading-relaxed">{{ destItem }}</span>
                      </div>
                    </div>
                    <span v-else class="font-bold text-amber-300 leading-relaxed">{{ b.destination }}</span>
                  </div>
                </div>

                <div v-if="b.distance_km" class="flex flex-col sm:flex-row sm:items-start gap-1 sm:gap-2 text-cyan-300 text-xs">
                  <span class="text-slate-400 shrink-0 font-medium sm:w-28">📏 Jarak Rute:</span>
                  <div class="flex-1 leading-relaxed">
                    <strong class="font-bold">{{ b.distance_km }} KM</strong>
                    <span v-if="b.estimated_duration_minutes" class="text-slate-400 font-normal ml-1">
                      (Est. ~{{ b.estimated_duration_minutes }} menit)
                    </span>
                  </div>
                </div>


                <div class="flex flex-col sm:flex-row sm:items-start gap-1 sm:gap-2 text-slate-200 text-xs">
                  <span class="text-slate-400 shrink-0 font-medium sm:w-28">👤 Pemesan:</span>
                  <span class="leading-relaxed flex-1">
                    <strong class="text-white">{{ b.borrower_name || b.requester_name || (b.user?.name !== 'admin' ? b.user?.name : (b.passengers || 'Pegawai')) }}</strong>
                    <span class="text-slate-400 ml-1">({{ b.borrower_division || b.requester_division || b.user?.division?.name || 'Operasional' }})</span>
                  </span>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-start gap-1 sm:gap-2 text-slate-300 text-xs">
                  <span class="text-slate-400 shrink-0 font-medium sm:w-28">👥 Penumpang:</span>
                  <span class="leading-relaxed flex-1">{{ b.passengers || ((b.borrower_name || b.requester_name || b.user?.name) + ' & Rekan') }} ({{ b.passengers_count || 1 }} orang)</span>
                </div>

              </div>
            </div>

            <div class="mt-3 pt-2 space-y-2.5">
              <div class="flex items-center gap-2">
                <!-- Tombol Buka Navigasi Google Maps Langsung (Turn-by-turn) -->
                <a
                  v-if="getGoogleMapsLink(b)"
                  :href="getGoogleMapsLink(b)"
                  target="_blank"
                  rel="noopener"
                  class="flex-1 py-2.5 px-3 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold text-xs shadow-md shadow-blue-600/20 transition flex items-center justify-center gap-1.5 active:scale-[0.98] leading-normal"
                >
                  <span>🧭 Buka di Google Maps</span>
                  <span>↗</span>
                </a>

                <!-- Tombol Ambil Orderan (Khusus jika belum diambil oleh driver ini) -->
                <button
                  v-if="!b.driver_id || b.driver_id !== currentUser?.id"
                  @click="acceptOrder(b)"
                  class="flex-1 py-2.5 px-3 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-md shadow-emerald-600/20 transition flex items-center justify-center gap-1.5 active:scale-[0.98] leading-normal"
                >
                  <span>🚖 Ambil Orderan</span>
                </button>
              </div>

              <!-- Tombol Utama: Scan Barcode Mobil & Berangkat dengan Logo Kamera Kecil -->
              <button
                @click="confirmAndScanBooking(b)"
                class="w-full py-3 px-4 rounded-xl bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 text-white font-bold text-xs sm:text-sm shadow-lg shadow-orange-500/20 transition flex items-center justify-center gap-2 active:scale-[0.99] leading-normal"
              >
                <!-- Logo kamera kecil -->
                <svg viewBox="0 0 24 24" class="w-4 h-4 text-white" fill="currentColor">
                  <path d="M4 4h3l2-2h6l2 2h3a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2zm8 3a5 5 0 1 0 0 10 5 5 0 0 0 0-10zm0 2a3 3 0 1 1 0 6 3 3 0 0 1 0-6z"/>
                </svg>
                <span>Scan Barcode Mobil & Mulai Perjalanan</span>
              </button>
            </div>
          </div>
        </div>
      </section>
    </main>

    <!-- ============================================================= -->
    <!-- MODAL KAMERA SCANNER: MURNI KAMERA & SCAN INSTAN              -->
    <!-- TIDAK ADA KONFIRMASI MANUAL & TIDAK ADA DROPDOWN PEMILIHAN    -->
    <!-- ============================================================= -->
    <div v-if="showModal" class="fixed inset-0 z-50 bg-black/85 backdrop-blur-md flex items-center justify-center p-4" @click.self="closeModal">
      <div class="bg-slate-900 border border-slate-700 w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[92vh]">
        
        <!-- Header Modal -->
        <div class="p-4 bg-slate-800/90 border-b border-slate-700 flex items-center justify-between">
          <div class="flex items-center gap-2">
            <span class="w-8 h-8 rounded-lg bg-amber-500/20 text-amber-400 flex items-center justify-center text-sm font-bold">📷</span>
            <div>
              <h3 class="font-bold text-sm text-white">Scanner Barcode Mobil</h3>
              <p class="text-[11px] text-slate-400">Arahkan kamera ke barcode mobil — terbaca langsung otomatis</p>
            </div>
          </div>
          <button @click="closeModal" class="w-8 h-8 rounded-lg bg-slate-700 hover:bg-slate-600 text-slate-300 text-xs flex items-center justify-center">✕</button>
        </div>

        <!-- Body Modal -->
        <div class="p-5 overflow-y-auto space-y-4 text-xs">
          
          <!-- ════════════════════════════════════════════════════════════ -->
          <!-- OPSI PERUBAHAN STATUS DI ATAS KAMERA                        -->
          <!-- ════════════════════════════════════════════════════════════ -->
          <div>
            <label class="block font-bold text-slate-300 text-xs mb-2">Pilih Status Sebelum Scan:</label>
            <div class="grid grid-cols-3 gap-2 bg-slate-950 p-1.5 rounded-xl border border-slate-800">
              <!-- Opsi 1: Berangkat -->
              <button
                type="button"
                @click="modalAction = 'depart'"
                class="py-2.5 px-2 rounded-lg font-bold text-xs transition flex flex-col items-center gap-1"
                :class="modalAction === 'depart' ? 'bg-amber-500 text-white shadow-md shadow-amber-500/30' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-900'"
              >
                <span class="text-sm">🛫</span>
                <span>Berangkat</span>
              </button>

              <!-- Opsi 2: Kembali ke Kantor -->
              <button
                type="button"
                @click="modalAction = 'return'"
                class="py-2.5 px-2 rounded-lg font-bold text-xs transition flex flex-col items-center gap-1"
                :class="modalAction === 'return' ? 'bg-cyan-600 text-white shadow-md shadow-cyan-600/30' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-900'"
              >
                <span class="text-sm">🏢</span>
                <span>Kembali ke Kantor</span>
              </button>

              <!-- Opsi 3: Selesai -->
              <button
                type="button"
                @click="modalAction = 'arrive'"
                class="py-2.5 px-2 rounded-lg font-bold text-xs transition flex flex-col items-center gap-1"
                :class="modalAction === 'arrive' ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/30' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-900'"
              >
                <span class="text-sm">🅿️</span>
                <span>Selesai (Pool)</span>
              </button>
            </div>
          </div>

          <!-- Banner Notifikasi Terkait Booking (Jika Driver Mengkonfirmasi Booking Pegawai) -->
          <div v-if="targetBooking" class="p-4 rounded-xl bg-orange-950/40 border border-orange-500/50 text-orange-200 space-y-2.5 shadow-lg">
            <div class="flex items-center justify-between flex-wrap gap-2">
              <span class="font-bold text-xs text-orange-300 flex items-center gap-1.5">
                <span>🎯</span>
                <span>Mengonfirmasi Booking Pegawai:</span>
              </span>
              <span v-if="targetBooking.is_routine || targetBooking.booking_type === 'routine'" class="px-2 py-0.5 rounded text-xs font-bold bg-cyan-500/20 text-cyan-300 border border-cyan-500/30">
                🔄 Rutin Terjadwal
              </span>
            </div>

            <div class="text-xs space-y-2 text-slate-200 pt-1 border-t border-orange-500/20 leading-relaxed">
              <div class="flex flex-col sm:flex-row sm:items-start gap-0.5 sm:gap-2">
                <span class="text-slate-400 shrink-0 sm:w-24">👤 Pemesan:</span>
                <span class="flex-1 font-semibold text-white">
                  {{ targetBooking.user?.name || targetBooking.borrower_name || targetBooking.requester_name }}
                  <span class="text-slate-400 font-normal ml-1">({{ targetBooking.user?.division?.name || targetBooking.borrower_division || targetBooking.requester_division || 'Operasional' }})</span>
                </span>
              </div>
              <div class="flex flex-col sm:flex-row sm:items-start gap-0.5 sm:gap-2">
                <span class="text-slate-400 shrink-0 sm:w-24">📍 Tujuan:</span>
                <span class="flex-1 font-bold text-amber-300">{{ targetBooking.destination }}</span>
              </div>
              <div class="flex flex-col sm:flex-row sm:items-start gap-0.5 sm:gap-2">
                <span class="text-slate-400 shrink-0 sm:w-24">👥 Penumpang:</span>
                <span class="flex-1 text-slate-300">{{ targetBooking.passengers_count || 1 }} orang ({{ targetBooking.passengers }})</span>
              </div>
            </div>

            <p class="text-xs text-orange-300/90 pt-1.5 border-t border-orange-500/20 leading-relaxed">
              Langsung scan barcode mobil <b>{{ targetBooking.vehicle?.name }} ({{ targetBooking.vehicle?.license_plate }})</b> di bawah ini.
            </p>
          </div>

          <!-- ════════════════════════════════════════════════════════════ -->
          <!-- KAMERA SCANNER VIEWFINDER (BERADA TEPAT DI BAWAH OPSI)       -->
          <!-- ════════════════════════════════════════════════════════════ -->
          <div class="relative w-full h-64 bg-slate-950 rounded-xl overflow-hidden border border-slate-700 shadow-inner flex items-center justify-center">
            <!-- Reader container untuk html5-qrcode -->
            <div id="sopir-qr-reader" class="w-full h-full"></div>

            <!-- Fallback video jika html5-qrcode gagal -->
            <video v-if="!usingHtml5Qrcode" ref="videoRef" class="w-full h-full object-cover" playsinline autoplay></video>
            
            <!-- Laser Viewfinder Overlay -->
            <div class="absolute inset-x-8 inset-y-6 border-2 border-dashed rounded-xl pointer-events-none flex items-center justify-center" :class="modalAction === 'depart' ? 'border-amber-400' : (modalAction === 'return' ? 'border-cyan-400' : 'border-emerald-400')">
              <div class="w-full h-0.5 animate-pulse" :class="modalAction === 'depart' ? 'bg-amber-400' : (modalAction === 'return' ? 'bg-cyan-400' : 'bg-emerald-400')"></div>
            </div>

            <!-- Petunjuk Kamera -->
            <div class="absolute bottom-2.5 bg-black/85 px-3.5 py-1.5 rounded-full text-[11px] font-semibold border border-white/10 text-center max-w-[90%]" :class="modalAction === 'depart' ? 'text-amber-300' : (modalAction === 'return' ? 'text-cyan-300' : 'text-emerald-300')">
              {{ isSubmitting ? 'Memproses barcode...' : (modalAction === 'depart' ? 'Arahkan ke barcode mobil untuk BERANGKAT' : (modalAction === 'return' ? 'Arahkan ke barcode mobil untuk KEMBALI KE KANTOR' : 'Arahkan ke barcode mobil untuk SELESAI')) }}
            </div>

            <!-- Loading Spinner saat scan berhasil -->
            <div v-if="isSubmitting" class="absolute inset-0 bg-black/70 flex flex-col items-center justify-center gap-2 z-20">
              <div class="w-8 h-8 border-3 border-amber-400 border-t-transparent rounded-full animate-spin"></div>
              <span class="text-xs font-bold text-white">Sinkronisasi status mobil...</span>
            </div>

            <!-- Action buttons overlay: Switch Camera & Toggle Camera -->
            <div class="absolute top-2.5 right-2.5 flex items-center gap-1.5 z-10">
              <button
                v-if="availableCameras.length > 1"
                type="button"
                @click="switchCamera"
                class="px-2.5 py-1 rounded-md bg-black/80 hover:bg-black text-[10px] font-semibold border border-white/20 text-white transition flex items-center gap-1"
                title="Ganti Kamera Depan/Belakang"
              >
                <span>🔄</span>
                <span>Kamera</span>
              </button>
              <button
                type="button"
                @click="isCameraActive ? stopCamera() : startCamera()"
                class="px-2.5 py-1 rounded-md bg-black/80 hover:bg-black text-[10px] font-semibold border border-white/20 text-white transition"
              >
                {{ isCameraActive ? 'Matikan' : 'Nyalakan' }}
              </button>
            </div>
          </div>

          <!-- OPSI CADANGAN 1-KLIK: JIKA BARCODE FISIK KOTOR / SUSAH DISCAN -->
          <div class="bg-slate-950/70 p-3 rounded-xl border border-slate-800 space-y-2">
            <div class="flex items-center justify-between">
              <span class="text-[11px] font-bold text-slate-300">💡 Barcode fisik kotor/rusak? Klik plat mobil yang ditugaskan:</span>
              <span class="text-[10px] text-amber-400/90 font-mono">1-Klik Eksekusi</span>
            </div>
            <div class="flex flex-wrap gap-1.5">
              <button
                v-for="car in vehicles"
                :key="car.id"
                type="button"
                @click="handleScannedCode(car.license_plate)"
                :disabled="isSubmitting"
                class="px-2.5 py-1 rounded-lg text-[10px] font-bold font-mono border transition flex items-center gap-1"
                :class="selectedVehicleId === car.id ? 'bg-amber-500 text-slate-950 border-amber-400' : 'bg-slate-900 hover:bg-slate-800 text-slate-300 border-slate-700 hover:border-slate-500'"
              >
                <span>🚗</span>
                <span>{{ car.license_plate }}</span>
              </button>
            </div>
          </div>

          <!-- STATUS & PETUNJUK SCAN TANPA FORMULIR -->
          <div class="p-3.5 rounded-xl border flex items-center gap-3 transition-all"
            :class="modalAction === 'depart' ? 'bg-amber-950/30 border-amber-500/30 text-amber-200' : (modalAction === 'return' ? 'bg-cyan-950/30 border-cyan-500/30 text-cyan-200' : 'bg-emerald-950/30 border-emerald-500/30 text-emerald-200')"
          >
            <div class="text-2xl">
              {{ modalAction === 'depart' ? '🛫' : (modalAction === 'return' ? '🏢' : '🅿️') }}
            </div>
            <div class="flex-1">
              <strong class="block text-xs font-bold text-white uppercase tracking-wide">
                Mode Scan: {{ modalAction === 'depart' ? 'Keberangkatan (Jalan)' : (modalAction === 'return' ? 'Kembali ke Kantor' : 'Selesai (Sampai Pool)') }}
              </strong>
              <p class="text-[11px] opacity-90">
                Arahkan kamera ke barcode mobil — status akan <b>langsung terupdate otomatis</b> tanpa perlu tombol konfirmasi apapun.
              </p>
            </div>
          </div>
        </div>

        <!-- Footer Modal: Hanya tombol tutup -->
        <div class="p-4 bg-slate-800/90 border-t border-slate-700 flex items-center justify-between">
          <div class="text-[11px] text-slate-400 flex items-center gap-1.5">
            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
            <span>Kamera aktif & siap mendeteksi barcode</span>
          </div>
          <button @click="closeModal" type="button" class="px-4 py-2 rounded-lg bg-slate-700 hover:bg-slate-600 text-slate-200 text-xs font-semibold transition" :disabled="isSubmitting">
            Tutup Scanner
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, nextTick, onMounted, onUnmounted } from 'vue'
useHead({
  title: 'Portal Pengemudi Operasional - PKL-Digo'
})

const config = useRuntimeConfig()
const apiBase = config.public?.apiBase || 'http://127.0.0.1:8000/api'

// User State
const userCookie = useCookie('auth_user')
const tokenCookie = useCookie('auth_token')
const currentUser = ref(null)

// Vehicles & Trip State
const vehicles = ref([])
const activeTrip = ref(null)
const alertMsg = ref(null)
const isSubmitting = ref(false)

// Booking & Notification State
const pendingBookings = ref([])
const rawActiveBookings = ref([])
const targetBooking = ref(null)
const activeBookingId = ref(null)
const incomingAlert = ref(null)
let incomingAlertTimeout = null
const knownBookingIds = ref(new Set())
let isFirstBookingLoad = true

// Routine Calendar State
const dashboardRoutines = ref([])
const myRoutineCount = ref(0)

const fetchRoutineSummary = async () => {
  const myId = currentUser.value?.id
  const myName = currentUser.value?.name
  const q = new URLSearchParams()
  if (myId) q.append('driver_id', myId)
  if (myName) q.append('driver_name', myName)

  try {
    const res = await $fetch(`${apiBase}/driver/routine-bookings?${q.toString()}`)
    if (res?.status === 'success') {
      const allList = [...(res.data || []), ...(res.incidentals || [])]
      dashboardRoutines.value = allList.slice(0, 4)
      myRoutineCount.value = (res.summary?.my_routines || 0) + (res.incidentals?.length || 0)
    }
  } catch (e) {
    console.warn('Gagal memuat ringkasan rutin di dashboard:', e)
  }
}

// Modal State
const showModal = ref(false)
const modalAction = ref('depart') // 'depart', 'return', 'arrive'
const selectedVehicleId = ref('')
const lastScannedVehicle = ref(null)
let isScanningThrottled = false

// Camera Scanner State (HTML5-QRCode & Native Fallback)
const isCameraActive = ref(false)
const usingHtml5Qrcode = ref(true)
const availableCameras = ref([])
const currentCameraIndex = ref(0)
const videoRef = ref(null)
let html5QrScannerInstance = null
let mediaStream = null
let animationFrameId = null
let barcodeDetector = null
let pollTimer = null

// Daftar seluruh armada yang sedang jalan (untuk monitor antar-sopir)
const activeTripsList = computed(() => {
  return vehicles.value.filter(v => v.status_key === 'departed' || v.status_key === 'returning')
})

const fetchVehicles = async () => {
  try {
    const res = await $fetch(`${apiBase}/vehicles`)
    vehicles.value = res || []

    // Cari trip aktif sopir saat ini
    const myId = currentUser.value?.id
    const myName = currentUser.value?.name
    const myTrip = vehicles.value.find(v => 
      (v.current_driver_id === myId || (myName && v.current_driver_name === myName)) &&
      (v.status_key === 'departed' || v.status_key === 'returning')
    )
    activeTrip.value = myTrip || null
  } catch (err) {
    console.warn('Gagal memuat kendaraan:', err)
  }
}

// Deteksi pesanan baru & bunyikan audio notifikasi
const handleNewBookingsDetection = (newItems) => {
  if (!Array.isArray(newItems)) return

  if (isFirstBookingLoad) {
    newItems.forEach(b => knownBookingIds.value.add(b.id))
    isFirstBookingLoad = false
    return
  }

  // Cari booking baru yang belum pernah dicatat
  const newlyArrived = newItems.filter(b => !knownBookingIds.value.has(b.id))
  if (newlyArrived.length > 0) {
    const newest = newlyArrived[0]
    const borrower = newest.borrower_name || newest.requester_name || (newest.user?.name !== 'admin' ? newest.user?.name : (newest.passengers || 'Pegawai'))
    const destination = newest.destination || 'Tujuan Kantor'

    // Tampilkan banner pop-up di layar driver
    if (incomingAlertTimeout) clearTimeout(incomingAlertTimeout)
    incomingAlert.value = {
      borrower,
      destination,
      time: new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) + ' WIB'
    }
    incomingAlertTimeout = setTimeout(() => {
      incomingAlert.value = null
    }, 15000)

    newlyArrived.forEach(b => knownBookingIds.value.add(b.id))
  }
}

// Helper parse destinasi multi-stop
const parseDestinations = (destStr) => {
  if (!destStr) return []
  const raw = destStr.includes('➔')
    ? destStr.split('➔')
    : (destStr.includes('->') ? destStr.split('->') : [destStr])
  return raw.map(s => s.trim()).filter(Boolean)
}

// Buka Google Maps langsung dengan rute penjemputan dan seluruh perhentian tujuan
const getGoogleMapsLink = (b) => {
  if (!b) return null

  // 1. Jika ada google_maps_url valid dan bukan versi %2C yang bermasalah
  if (b.google_maps_url && b.google_maps_url.includes('google.com/maps') && !b.google_maps_url.includes('%2C')) {
    return b.google_maps_url
  }

  // 2. Bangun rute multi-stop path: /dir/pickup/stop1/stop2/...
  const pickup = b.pickup_location || 'BPJS Kesehatan Cabang Jakarta Utara'
  const rawDest = b.destination || ''
  const stops = parseDestinations(rawDest)

  if (stops.length > 0) {
    const allPoints = [pickup, ...stops]
    return `https://www.google.com/maps/dir/${allPoints.map(p => encodeURIComponent(p)).join('/')}`
  }

  if (b.destination_lat && b.destination_lng) {
    const origin = (b.pickup_lat && b.pickup_lng)
      ? `${b.pickup_lat},${b.pickup_lng}`
      : encodeURIComponent(pickup)
    return `https://www.google.com/maps/dir/${origin}/${b.destination_lat},${b.destination_lng}`
  }

  return b.google_maps_nav_url || null
}

// Driver klaim / ambil orderan langsung
const acceptOrder = async (booking) => {
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
        text: `Berhasil mengambil orderan! Anda ditugaskan mengantar ${booking.borrower_name || booking.requester_name || 'pegawai'}. Silakan buka navigasi Google Maps atau langsung scan mobil.`
      }
      await fetchBookings()
    }
  } catch (err) {
    alertMsg.value = {
      type: 'error',
      text: err?.data?.message || 'Gagal mengambil orderan'
    }
  }
}

const fetchBookings = async () => {
  const headers = {}
  if (tokenCookie.value) {
    headers['Authorization'] = `Bearer ${tokenCookie.value}`
  }

  // Kirim driver_id dan driver_name pengemudi yang login agar backend menyaring notifikasi tugas sesuai nama yang diajukan admin
  const myId = currentUser.value?.id
  const myName = currentUser.value?.name
  const queryParams = new URLSearchParams()
  if (myId) queryParams.append('driver_id', myId)
  if (myName) queryParams.append('driver_name', myName)
  const qs = queryParams.toString() ? `?${queryParams.toString()}` : ''

  try {
    const res = await $fetch(`${apiBase}/driver/bookings${qs}`, { headers })
    if (res?.status === 'success') {
      const newItems = res.pending || []
      handleNewBookingsDetection(newItems)
      pendingBookings.value = newItems
      rawActiveBookings.value = res.active || []
    }
  } catch (err) {
    try {
      const fallback = await $fetch(`${apiBase}/v1/manage/driver/bookings${qs}`, { headers })
      if (fallback?.status === 'success') {
        const newItems = fallback.pending || []
        handleNewBookingsDetection(newItems)
        pendingBookings.value = newItems
        rawActiveBookings.value = fallback.active || []
      }
    } catch (innerErr) {
      console.warn('Gagal memuat notifikasi booking:', innerErr)
    }
  }
}

// Buka scanner saat driver klik tombol konfirmasi pada permohonan booking pegawai
const confirmAndScanBooking = (booking) => {
  targetBooking.value = booking
  activeBookingId.value = booking.id
  selectedVehicleId.value = booking.vehicle_id
  modalAction.value = 'depart'
  alertMsg.value = null

  showModal.value = true
  setTimeout(() => {
    startCamera()
  }, 200)
}

// Buka scanner terpadu dengan kamera aktif
const openUnifiedScanModal = () => {
  alertMsg.value = null
  lastScannedVehicle.value = null
  targetBooking.value = null
  activeBookingId.value = null
  
  // Tentukan opsi default otomatis berdasarkan status perjalanan saat ini
  if (activeTrip.value) {
    selectedVehicleId.value = activeTrip.value.id
    lastScannedVehicle.value = activeTrip.value
    if (activeTrip.value.status_key === 'departed') {
      modalAction.value = 'return'
    } else {
      modalAction.value = 'arrive'
    }
  } else {
    modalAction.value = 'depart'
    selectedVehicleId.value = vehicles.value[0]?.id || ''
  }

  showModal.value = true
  setTimeout(() => {
    startCamera()
  }, 200)
}

const closeModal = () => {
  stopCamera()
  showModal.value = false
  isScanningThrottled = false
  targetBooking.value = null
  activeBookingId.value = null
}

// ═══════════════════════════════════════════════════════════════════
// CAMERA SCANNER: HTML5-QRCODE ENGINE DENGAN MULTI-FORMAT SUPPORT
// Mendukung QR Code, Code 128, Code 39, EAN 13 di semua browser & HP
// ═══════════════════════════════════════════════════════════════════
const startCamera = async () => {
  isCameraActive.value = true

  try {
    const { Html5Qrcode, Html5QrcodeSupportedFormats } = await import('html5-qrcode')
    usingHtml5Qrcode.value = true
    await nextTick()

    // Bersihkan scanner lama jika masih ada
    if (html5QrScannerInstance) {
      try {
        if (html5QrScannerInstance.isScanning) {
          await html5QrScannerInstance.stop()
        }
        await html5QrScannerInstance.clear()
      } catch (e) {}
      html5QrScannerInstance = null
    }

    html5QrScannerInstance = new Html5Qrcode('sopir-qr-reader', {
      formatsToSupport: [
        Html5QrcodeSupportedFormats.QR_CODE,
        Html5QrcodeSupportedFormats.CODE_128,
        Html5QrcodeSupportedFormats.CODE_39,
        Html5QrcodeSupportedFormats.EAN_13,
        Html5QrcodeSupportedFormats.UPC_A
      ],
      verbose: false
    })

    let cameraIdOrConfig = { facingMode: 'environment' }
    try {
      const devices = await Html5Qrcode.getCameras()
      if (devices && devices.length > 0) {
        availableCameras.value = devices
        const backCam = devices.find(d => /back|rear|belakang|environment/i.test(d.label))
        cameraIdOrConfig = backCam ? backCam.id : devices[0].id
      }
    } catch (camErr) {
      console.warn('Gagal membaca kamera fisik:', camErr)
    }

    const qrConfig = {
      fps: 25,
      qrbox: { width: 250, height: 250 },
      aspectRatio: 1.0,
      disableFlip: true
    }

    await html5QrScannerInstance.start(
      cameraIdOrConfig,
      qrConfig,
      (decodedText) => {
        handleScannedCode(decodedText)
      },
      () => {} // callback per-frame
    )
  } catch (err) {
    console.warn('Html5Qrcode gagal dijalankan, beralih ke native camera stream:', err)
    usingHtml5Qrcode.value = false
    startNativeCameraFallback()
  }
}

// Ganti Kamera (Depan / Belakang)
const switchCamera = async () => {
  if (availableCameras.value.length < 2 || !html5QrScannerInstance) return
  currentCameraIndex.value = (currentCameraIndex.value + 1) % availableCameras.value.length
  const nextCam = availableCameras.value[currentCameraIndex.value]

  try {
    if (html5QrScannerInstance.isScanning) {
      await html5QrScannerInstance.stop()
    }
    await html5QrScannerInstance.start(
      nextCam.id,
      { fps: 25, qrbox: { width: 250, height: 250 }, aspectRatio: 1.0, disableFlip: true },
      (decodedText) => handleScannedCode(decodedText),
      () => {}
    )
  } catch (e) {
    console.warn('Gagal beralih kamera:', e)
  }
}

const stopCamera = async () => {
  if (html5QrScannerInstance) {
    try {
      if (html5QrScannerInstance.isScanning) {
        await html5QrScannerInstance.stop()
      }
      await html5QrScannerInstance.clear()
    } catch (e) {}
    html5QrScannerInstance = null
  }

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

// Fallback jika html5-qrcode tidak tersedia
const startNativeCameraFallback = async () => {
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
      barcodeDetector = new window.BarcodeDetector({
        formats: ['qr_code', 'code_128', 'code_39', 'ean_13', 'upc_a']
      })
      startBarcodeDetectLoop()
    }
  } catch (err) {
    console.warn('Kamera fallback gagal dibuka:', err)
  }
}

const startBarcodeDetectLoop = () => {
  const detectFrame = async () => {
    if (!isCameraActive.value || !videoRef.value || !barcodeDetector || isScanningThrottled) {
      if (isCameraActive.value) {
        animationFrameId = requestAnimationFrame(detectFrame)
      }
      return
    }
    try {
      if (videoRef.value.readyState === videoRef.value.HAVE_ENOUGH_DATA) {
        const barcodes = await barcodeDetector.detect(videoRef.value)
        if (barcodes.length > 0 && !isScanningThrottled) {
          handleScannedCode(barcodes[0].rawValue)
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
    osc.frequency.setValueAtTime(880, ctx.currentTime)
    gain.gain.setValueAtTime(0.2, ctx.currentTime)
    osc.connect(gain)
    gain.connect(ctx.destination)
    osc.start()
    osc.stop(ctx.currentTime + 0.18)
  } catch(e) {}
}

// ═══════════════════════════════════════════════════════════════════
// PARSER & MATCHER KODE BARCODE MOBIL CERDAS
// Mendukung plat dengan/tanpa spasi, format JSON, ID mobil, nama mobil
// ═══════════════════════════════════════════════════════════════════
const matchVehicleFromCode = (code) => {
  if (!code || !vehicles.value || vehicles.value.length === 0) return null

  const raw = String(code).trim()
  
  // 1. Coba parse JSON jika format QR adalah JSON string
  if (raw.startsWith('{') && raw.endsWith('}')) {
    try {
      const parsed = JSON.parse(raw)
      const targetId = parsed.id || parsed.vehicle_id
      if (targetId) {
        const found = vehicles.value.find(v => String(v.id) === String(targetId))
        if (found) return found
      }
      const targetPlate = parsed.license_plate || parsed.plate || parsed.plat
      if (targetPlate) {
        return matchVehicleFromCode(targetPlate)
      }
    } catch (e) {}
  }

  // 2. Normalisasi teks: Hapus ekstensi .svg, prefix qr-, strip, dll
  let clean = raw.toUpperCase()
    .replace(/^QR-/, '')
    .replace(/\.SVG$/, '')
    .trim()

  const alphaNumClean = clean.replace(/[^A-Z0-9]/gi, '')

  // 3. Cocokkan plat nomor (misal: "B 1046 UIE" vs "B1046UIE" vs "B-1046-UIE")
  let found = vehicles.value.find(v => {
    const vPlate = (v.license_plate || '').toUpperCase().trim()
    const vPlateAlphaNum = vPlate.replace(/[^A-Z0-9]/gi, '')

    return vPlate === clean || 
           vPlateAlphaNum === alphaNumClean ||
           clean.includes(vPlate) ||
           alphaNumClean.includes(vPlateAlphaNum)
  })
  if (found) return found

  // 4. Cocokkan berdasarkan ID mobil jika kode berupa angka (contoh: "1", "2")
  if (/^\d+$/.test(clean)) {
    found = vehicles.value.find(v => String(v.id) === clean)
    if (found) return found
  }

  // 5. Cocokkan nama kendaraan
  found = vehicles.value.find(v => {
    const vName = (v.name || '').toUpperCase()
    return clean.includes(vName) || vName.includes(clean)
  })

  return found || null
}

// Handler saat Barcode berhasil terdeteksi kamera atau diklik
const handleScannedCode = async (code) => {
  if (isScanningThrottled || isSubmitting.value) return
  isScanningThrottled = true

  playBeep()

  const matched = matchVehicleFromCode(code)

  if (matched) {
    selectedVehicleId.value = matched.id
    lastScannedVehicle.value = matched
    // Langsung eksekusi otomatis tanpa perlu konfirmasi manual
    await executeTripAction(matched)
  } else {
    alertMsg.value = { type: 'error', text: `Barcode "${code}" tidak terdaftar dalam plat armada mobil dinas.` }
    setTimeout(() => {
      isScanningThrottled = false
    }, 1500)
  }
}

// Eksekusi aksi trip sopir secara instan
const executeTripAction = async (car) => {
  isSubmitting.value = true
  alertMsg.value = null

  const headers = {}
  if (tokenCookie.value) {
    headers['Authorization'] = `Bearer ${tokenCookie.value}`
  }

  const action = modalAction.value
  const endpoint = `${apiBase}/driver/trip/${action}`
  const payload = {
    vehicle_id: car.id,
    license_plate: car.license_plate
  }

  if (action === 'depart' && activeBookingId.value) {
    payload.booking_id = activeBookingId.value
  }

  const actionLabels = {
    depart: 'BERANGKAT (Jalan Mengantar Pegawai)',
    return: 'KEMBALI KE KANTOR (Perjalanan Pulang)',
    arrive: 'SELESAI (Parkir di Pool Kantor)'
  }

  try {
    const res = await $fetch(endpoint, {
      method: 'POST',
      headers,
      body: payload
    })

    const actionText = actionLabels[action] || 'Berhasil'
    alertMsg.value = { 
      type: 'success', 
      text: `✅ Sukses! ${car.name} (${car.license_plate}) berhasil diperbarui: ${actionText}. Realtime dashboard monitor terupdate!` 
    }
    closeModal()
    await fetchVehicles()
    await fetchBookings()
  } catch (err) {
    try {
      const fallbackEndpoint = `${apiBase}/v1/manage/driver/trip/${action}`
      const fallbackRes = await $fetch(fallbackEndpoint, {
        method: 'POST',
        headers,
        body: payload
      })
      const actionText = actionLabels[action] || 'Berhasil'
      alertMsg.value = { 
        type: 'success', 
        text: `✅ Sukses! ${car.name} (${car.license_plate}) berhasil diperbarui: ${actionText}. Realtime dashboard monitor terupdate!` 
      }
      closeModal()
      await fetchVehicles()
      await fetchBookings()
    } catch (innerErr) {
      const msg = innerErr?.data?.message || err?.data?.message || 'Gagal memperbarui status perjalanan.'
      alertMsg.value = { type: 'error', text: msg }
    }
  } finally {
    isSubmitting.value = false
    setTimeout(() => {
      isScanningThrottled = false
    }, 2000)
  }
}

const handleLogout = () => {
  userCookie.value = null
  tokenCookie.value = null
  if (typeof window !== 'undefined') {
    localStorage.removeItem('auth_user')
    localStorage.removeItem('auth_token')
  }
  navigateTo('/login')
}

const formatDateTime = (str) => {
  if (!str) return '-'
  try {
    const d = new Date(str)
    return d.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) + ' WIB'
  } catch {
    return str
  }
}

onMounted(async () => {
  if (typeof window !== 'undefined') {
    const raw = localStorage.getItem('auth_user')
    if (raw) {
      try { currentUser.value = JSON.parse(raw) } catch(e) {}
    }
  }
  if (!currentUser.value && userCookie.value) {
    currentUser.value = userCookie.value
  }

  await fetchVehicles()
  await fetchBookings()
  await fetchRoutineSummary()
  pollTimer = setInterval(async () => {
    await fetchVehicles()
    await fetchBookings()
    await fetchRoutineSummary()
  }, 4000)
})

onUnmounted(() => {
  if (pollTimer) clearInterval(pollTimer)
  stopCamera()
})
</script>

<style scoped>
:deep(#sopir-qr-reader) {
  width: 100% !important;
  height: 100% !important;
  border: none !important;
  background: transparent !important;
}

:deep(#sopir-qr-reader video) {
  width: 100% !important;
  height: 100% !important;
  object-fit: cover !important;
  border-radius: 12px;
}

:deep(#sopir-qr-reader img) {
  display: none !important;
}

:deep(#sopir-qr-reader div) {
  border: none !important;
}
</style>
