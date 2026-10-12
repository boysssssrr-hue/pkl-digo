<template>
  <div class="ruangan-page min-h-screen font-sans pb-16 relative" :class="[isDark ? 'theme-dark' : 'theme-light']">
    <!-- BACKGROUND RUANG RAPAT (TETAP NATURAL, TANPA LAPISAN GELAP/TERANG) -->
    <div class="ruangan-bg-backdrop" aria-hidden="true"></div>

    <!-- Topbar Terpadu -->
    <UserTopbar />

    <main class="ruangan-main max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-6 relative z-10">
      <!-- Notifikasi Alert -->
      <div
        v-if="alertMsg"
        class="alert-card p-4 rounded-xl border flex items-center justify-between transition shadow-lg"
        :class="alertMsg.type === 'error' ? 'alert-error' : 'alert-success'"
      >
        <div class="flex items-center gap-3">
          <span class="text-lg">{{ alertMsg.type === 'error' ? '⚠️' : '✅' }}</span>
          <p class="text-xs md:text-sm font-medium">{{ alertMsg.text }}</p>
        </div>
        <button @click="alertMsg = null" class="btn-close-alert text-xs">✕</button>
      </div>

      <!-- Banner Info Kedinasan -->
      <section class="banner-card rounded-2xl p-4 sm:p-6 shadow-xl relative overflow-hidden">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
          <div>
            <div class="flex items-center gap-2 mb-1.5">
              <span class="badge-procedure px-2.5 py-0.5 rounded-full text-[10px] font-bold">
                FASILITAS KANTOR
              </span>
              <span class="text-xs text-subtle">Peminjaman Ruang Rapat & Aula Pertemuan</span>
            </div>
            <h2 class="text-base sm:text-lg md:text-2xl font-black banner-title">Reservasi Ruang Rapat Kedinasan</h2>
            <p class="text-xs sm:text-sm banner-desc mt-1.5 max-w-3xl leading-relaxed">
              Gunakan fasilitas ruang rapat dinas untuk koordinasi internal, rapat pleno, atau seminar kedinasan. Pilih ruangan yang berstatus <b>Tersedia</b> dan isi agenda kegiatan rapat Anda.
            </p>
          </div>
          <div class="flex items-center gap-2.5 shrink-0">
            <button
              type="button"
              class="btn-scan-room-action"
              @click="startCamera"
            >
              <svg viewBox="0 0 24 24" class="w-4 h-4" fill="currentColor">
                <path d="M4 4h3l2-2h6l2 2h3a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2zm8 3a5 5 0 1 0 0 10 5 5 0 0 0 0-10zm0 2a3 3 0 1 1 0 6 3 3 0 0 1 0-6z"/>
              </svg>
              <span>Pindai QR Ruangan</span>
            </button>
          </div>
        </div>
      </section>

      <!-- Grid Daftar Ruangan -->
      <section>
        <div class="section-header-card flex items-center justify-between mb-4 p-3 rounded-xl">
          <h3 class="font-bold text-sm section-title">Daftar Ruang Rapat & Pertemuan ({{ rooms.length }})</h3>
          <button
            @click="fetchRooms"
            :disabled="isLoading"
            class="btn-refresh-rooms text-xs font-semibold px-3 py-1.5 rounded-lg transition flex items-center gap-1.5"
            title="Muat Ulang Fasilitas Ruangan"
          >
            <span :class="{ 'animate-spin': isLoading }">↻</span>
            <span>Refresh</span>
          </button>
        </div>

        <div v-if="isLoading" class="text-center py-12 text-subtle text-xs loading-box rounded-2xl p-6">
          <div class="w-8 h-8 border-2 border-emerald-500 border-t-transparent rounded-full animate-spin mx-auto mb-2"></div>
          Memuat fasilitas ruangan...
        </div>

        <div v-else-if="rooms.length === 0" class="empty-room-box text-center py-16 rounded-2xl p-6 shadow-lg">
          <span class="text-4xl block mb-2">🏢</span>
          <p class="font-bold empty-title text-sm">Belum ada data ruangan rapat yang terdaftar.</p>
          <p class="text-xs text-subtle mt-1">Data ruangan dapat ditambahkan oleh admin melalui Portal Admin.</p>
        </div>

        <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div
            v-for="room in rooms"
            :key="room.id"
            class="room-card rounded-2xl p-4 flex flex-col justify-between transition-all shadow-xl group"
            :class="isRoomAvailable(room) ? 'status-available' : 'status-booked'"
          >
            <div>
              <!-- Visual Image Banner Ruangan -->
              <div class="room-img-wrapper w-full h-40 sm:h-44 rounded-xl overflow-hidden mb-3 relative flex items-center justify-center">
                <img
                  :src="room.image || '/images/rooms/default-room.svg'"
                  :alt="room.name"
                  class="w-full h-full object-cover group-hover:scale-105 transition duration-300"
                  loading="lazy"
                  @error="handleImgError($event, '/images/rooms/default-room.svg')"
                />
                <span
                  class="absolute top-2.5 right-2.5 text-[10px] font-bold px-2.5 py-0.5 rounded-full border shadow-sm backdrop-blur-md"
                  :class="isRoomAvailable(room) ? 'badge-available' : 'badge-booked'"
                >
                  {{ isRoomAvailable(room) ? 'Tersedia' : (room.status || 'Sedang Dipakai') }}
                </span>
                <span v-if="room.code" class="absolute bottom-2.5 left-2.5 font-mono text-[10px] font-bold px-2 py-0.5 rounded badge-code">
                  {{ room.code }}
                </span>
              </div>

              <div class="flex items-center justify-between mb-2">
                <span class="text-[11px] text-subtle flex items-center gap-1 font-semibold">
                  <span>👥</span>
                  <span>Kapasitas: {{ room.capacity || 20 }} Orang</span>
                </span>
                <span class="text-[11px] text-subtle font-medium">
                  🏢 {{ room.floor || 'Lantai 1' }}
                </span>
              </div>

              <h4 class="room-name font-bold text-base transition">{{ room.name }}</h4>
              <p class="text-xs text-subtle mt-1 line-clamp-2">Fasilitas: {{ room.facility || room.facilities || 'Proyektor, AC, Sound System, Wi-Fi' }}</p>

              <!-- Lokasi Ruangan Box -->
              <div v-if="room.location || room.description" class="room-location-box mt-3 p-2.5 rounded-xl text-[11px] space-y-1">
                <div class="text-subtle truncate">📍 Lokasi: {{ room.location || room.description || 'Gedung Kantor Pusat' }}</div>
              </div>
            </div>

            <div class="room-footer mt-4 pt-3 border-t flex items-center gap-2">
              <button
                @click="openRoomModal(room)"
                :disabled="!isRoomAvailable(room)"
                class="flex-1 py-2.5 rounded-xl text-xs font-bold transition flex items-center justify-center gap-2 cursor-pointer"
                :class="!isRoomAvailable(room) ? 'btn-disabled' : 'btn-booking'"
              >
                <span>{{ !isRoomAvailable(room) ? 'Sedang Dipakai' : '📅 Ajukan Peminjaman' }}</span>
              </button>
              <button
                type="button"
                @click="openBarcodeModal(room)"
                class="btn-room-qr-action"
                title="Lihat & Cetak Label QR Code Resmi Ruangan"
              >
                <span class="text-sm">🏷️</span>
                <span class="hidden sm:inline text-xs font-semibold">QR Code</span>
              </button>
            </div>
          </div>
        </div>
      </section>
    </main>

    <!-- Modal Form Pinjam Ruangan -->
    <div v-if="selectedRoom" class="modal-backdrop fixed inset-0 z-50 flex items-center justify-center p-4 backdrop-blur-md" @click.self="selectedRoom = null">
      <div class="modal-box rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4">
        <div class="modal-header flex items-center justify-between pb-3 border-b">
          <div class="flex items-center gap-3">
            <div class="modal-room-thumb w-14 h-12 rounded-xl overflow-hidden p-0.5 flex items-center justify-center shrink-0">
              <img
                :src="selectedRoom.image || '/images/rooms/default-room.svg'"
                :alt="selectedRoom.name"
                class="w-full h-full object-cover rounded-lg"
                @error="handleImgError($event, '/images/rooms/default-room.svg')"
              />
            </div>
            <div>
              <h3 class="font-bold text-sm modal-title">{{ selectedRoom.name }}</h3>
              <p class="text-[11px] text-subtle">{{ selectedRoom.floor || selectedRoom.location || 'Gedung Utama' }} • Kapasitas: {{ selectedRoom.capacity }} Org</p>
            </div>
          </div>
          <button @click="selectedRoom = null" class="btn-close-modal w-7 h-7 rounded-lg flex items-center justify-center text-xs">✕</button>
        </div>

        <form @submit.prevent="submitBooking" class="space-y-3.5 text-xs">
          <div>
            <label class="block font-semibold form-label mb-1">Agenda / Kegiatan Rapat:</label>
            <input
              v-model="bookingForm.agenda"
              type="text"
              placeholder="Contoh: Rapat Koordinasi Evaluasi Program Kerja Semester I"
              required
              class="form-input w-full p-2.5 rounded-lg focus:outline-none focus:border-emerald-500"
            />
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
              <label class="block font-semibold form-label mb-1">Perkiraan Peserta:</label>
              <input
                v-model.number="bookingForm.attendees"
                type="number"
                min="1"
                :max="selectedRoom.capacity || 100"
                class="form-input w-full p-2.5 rounded-lg focus:outline-none focus:border-emerald-500 font-bold"
              />
            </div>
            <div>
              <label class="block font-semibold form-label mb-1">Waktu Mulai:</label>
              <input
                v-model="bookingForm.start_time"
                type="datetime-local"
                class="form-input w-full p-2.5 rounded-lg focus:outline-none focus:border-emerald-500 font-medium"
              />
            </div>
          </div>

          <div>
            <label class="block font-semibold form-label mb-1">Catatan Fasilitas Tambahan:</label>
            <textarea
              v-model="bookingForm.notes"
              rows="2"
              placeholder="Contoh: Memerlukan mic wireless tambahan dan koneksi proyektor HDMI..."
              class="form-input w-full p-2.5 rounded-lg focus:outline-none focus:border-emerald-500 leading-relaxed"
            ></textarea>
          </div>

          <div class="modal-footer pt-3 flex flex-col-reverse sm:flex-row justify-end gap-2 border-t">
            <button
              @click="selectedRoom = null"
              type="button"
              class="btn-modal-cancel w-full sm:w-auto px-4 py-2.5 rounded-lg font-semibold transition cursor-pointer"
            >
              Batal
            </button>
            <button
              type="submit"
              :disabled="isSubmitting"
              class="btn-modal-submit w-full sm:w-auto px-5 py-2.5 rounded-lg font-bold flex items-center justify-center gap-2 transition cursor-pointer"
            >
              <span v-if="isSubmitting" class="w-3.5 h-3.5 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
              <span>{{ isSubmitting ? 'Mengirim...' : 'Kirim Permohonan' }}</span>
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- ================================================================= -->
    <!-- MODAL LIHAT & CETAK QR CODE RUANGAN (QR CODE ONLY)               -->
    <!-- ================================================================= -->
    <div v-if="barcodeRoom" class="modal-backdrop fixed inset-0 z-50 flex items-center justify-center p-4 backdrop-blur-md" @click.self="barcodeRoom = null">
      <div class="modal-box rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4 barcode-modal-card">
        <div class="modal-header flex items-center justify-between pb-3 border-b">
          <div class="flex items-center gap-3">
            <span class="text-2xl">🏷️</span>
            <div>
              <h3 class="font-bold text-sm modal-title">Label QR Code Resmi Ruangan</h3>
              <p class="text-[11px] text-subtle">QR Code siap dicetak untuk pintu ruangan atau dipindai</p>
            </div>
          </div>
          <button @click="barcodeRoom = null" class="btn-close-modal w-7 h-7 rounded-lg flex items-center justify-center text-xs">✕</button>
        </div>

        <div class="barcode-preview-body">
          <!-- Printable Ticket Label -->
          <div class="barcode-ticket-card">
            <div class="ticket-header">
              <span class="ticket-brand">SIMPEL • BPJS KESEHATAN</span>
              <span class="ticket-category">{{ barcodeRoom.floor || 'Ruang Rapat' }}</span>
            </div>

            <div class="ticket-room-row">
              <img
                :src="barcodeRoom.image || '/images/rooms/default-room.svg'"
                :alt="barcodeRoom.name"
                class="ticket-thumb"
                @error="handleImgError($event, '/images/rooms/default-room.svg')"
              />
              <div class="ticket-room-info">
                <h4 class="ticket-name">{{ barcodeRoom.name }}</h4>
                <div class="ticket-meta-grid">
                  <span>Kode: <b>{{ barcodeRoom.code }}</b></span>
                  <span>Kapasitas: <b>{{ barcodeRoom.capacity }} Org</b></span>
                  <span>Lokasi: <b>{{ barcodeRoom.floor }}</b></span>
                </div>
              </div>
            </div>

            <!-- QR Code Section (Standar Satu Macam: QR Code Saja) -->
            <div class="ticket-qr-main-box">
              <img
                :src="`/images/barcodes/qr-${barcodeRoom.code}.svg`"
                :alt="`QR ${barcodeRoom.code}`"
                class="ticket-qr-large-img"
              />
              <div class="ticket-qr-badge">QR CODE RESMI RUANGAN</div>
              <span class="ticket-qr-hint">Pindai via Kamera Scanner / HP untuk Reservasi</span>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="barcode-modal-actions mt-4 flex flex-col gap-2">
            <button
              type="button"
              class="btn-print-barcode"
              @click="printBarcodeLabel(barcodeRoom)"
            >
              🖨️ Cetak / Print Stiker Label Pintu Ruangan
            </button>
            <a
              :href="`/images/barcodes/qr-${barcodeRoom.code}.svg`"
              :download="`QR-${barcodeRoom.code}.svg`"
              class="btn-download-barcode"
            >
              📥 Unduh QR Code (SVG)
            </a>
            <button
              v-if="isRoomAvailable(barcodeRoom)"
              type="button"
              class="btn-quick-reserve"
              @click="reserveFromBarcode(barcodeRoom)"
            >
              📅 Ajukan Peminjaman Ruangan Ini
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- ================================================================= -->
    <!-- MODAL SCANNER QR CODE KAMERA UNTUK RUANGAN                         -->
    <!-- ================================================================= -->
    <div v-if="isScanning" class="modal-backdrop fixed inset-0 z-50 flex items-center justify-center p-4 backdrop-blur-md" @click.self="stopCamera">
      <div class="camera-card rounded-2xl shadow-2xl overflow-hidden">
        <div class="camera-modal-header p-4 flex items-center justify-between border-b border-slate-700/50">
          <div class="flex items-center gap-3">
            <svg viewBox="0 0 24 24" class="w-5 h-5 text-emerald-400" fill="currentColor">
              <path d="M4 4h3l2-2h6l2 2h3a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2zm8 3a5 5 0 1 0 0 10 5 5 0 0 0 0-10zm0 2a3 3 0 1 1 0 6 3 3 0 0 1 0-6z"/>
            </svg>
            <div>
              <h3 class="font-bold text-sm text-white">Pemindai QR Code Ruangan</h3>
              <p class="text-[11px] text-slate-400">Arahkan kamera ke stiker QR Code pintu ruangan</p>
            </div>
          </div>
          <button class="btn-close-modal text-slate-300 hover:text-white" type="button" @click="stopCamera">✕</button>
        </div>

        <div class="camera-viewfinder-box">
          <!-- Container for HTML5-QRCode Reader -->
          <div id="room-qr-reader" class="html5-qr-container"></div>
          <video v-if="!usingHtml5Qrcode" ref="videoRef" class="camera-video-feed" playsinline muted></video>

          <!-- KOTAK BIDIK SCANNER PRESISI DI TENGAH SESUAI UKURAN KAMERA -->
          <div class="scanner-custom-overlay">
            <div class="scanner-target-box">
              <div class="corner top-left"></div>
              <div class="corner top-right"></div>
              <div class="corner bottom-left"></div>
              <div class="corner bottom-right"></div>
              <div class="scanner-laser-line"></div>
            </div>
            <div class="scanner-guide-text">
              <span>Arahkan QR Code Ruangan ke Kotak Tengah</span>
            </div>
          </div>
        </div>

        <div v-if="scanError" class="camera-error-banner p-3 bg-red-500/20 text-red-300 text-xs text-center">
          <p>{{ scanError }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted, nextTick } from 'vue'

useHead({
  title: 'Pelayanan Ruang Rapat - PKL-Digo'
})

// Sinkronisasi state tema dark/light terpadu
const isDark = useState('user_theme_dark', () => true)

const config = useRuntimeConfig()
const apiBase = config.public?.apiBase || 'http://127.0.0.1:8000/api'

const rooms = ref([])
const isLoading = ref(false)
const isSubmitting = ref(false)
const selectedRoom = ref(null)
const alertMsg = ref(null)

// Barcode / QR Code State
const barcodeRoom = ref(null)

// Camera Barcode/QR Scanner State
const isScanning = ref(false)
const usingHtml5Qrcode = ref(false)
const videoRef = ref(null)
const mediaStream = ref(null)
const scanError = ref('')
let animationFrameId = null
let barcodeDetector = null
let html5QrScannerInstance = null

const tokenCookie = useCookie('auth_token')
const userCookie = useCookie('auth_user')

const bookingForm = ref({
  agenda: '',
  attendees: 10,
  start_time: '',
  notes: ''
})

const isRoomAvailable = (room) => {
  if (!room) return false
  const status = (room.status || '').toLowerCase()
  return status === 'tersedia' || status === 'available' || status === ''
}

const handleImgError = (e, fallback = '/images/rooms/default-room.svg') => {
  if (e && e.target) {
    e.target.onerror = null
    e.target.src = fallback
  }
}

const fetchRooms = async () => {
  isLoading.value = true
  try {
    const res = await $fetch(`${apiBase}/rooms`)
    if (Array.isArray(res)) {
      rooms.value = res.map(r => ({
        ...r,
        status: r.status || 'Tersedia'
      }))
    } else {
      rooms.value = []
    }
  } catch (err) {
    console.warn('Gagal memuat data ruangan dari API:', err)
    rooms.value = []
  } finally {
    isLoading.value = false
  }
}

const openRoomModal = (room) => {
  selectedRoom.value = room
  const now = new Date()
  now.setMinutes(now.getMinutes() - now.getTimezoneOffset())
  const defaultTime = now.toISOString().slice(0, 16)

  bookingForm.value = {
    agenda: '',
    attendees: Math.min(room.capacity || 10, 15),
    start_time: defaultTime,
    notes: ''
  }
}

const submitBooking = async () => {
  if (!selectedRoom.value) return
  isSubmitting.value = true
  alertMsg.value = null

  const headers = {}
  if (tokenCookie.value) {
    headers['Authorization'] = `Bearer ${tokenCookie.value}`
  }

  const userObj = userCookie.value ? (typeof userCookie.value === 'string' ? JSON.parse(userCookie.value) : userCookie.value) : null

  const payload = {
    room_id: selectedRoom.value.id,
    user_id: userObj?.id || 1,
    agenda: bookingForm.value.agenda,
    attendees: bookingForm.value.attendees,
    start_time: bookingForm.value.start_time || new Date().toISOString(),
    notes: bookingForm.value.notes
  }

  try {
    const res = await $fetch(`${apiBase}/room-bookings`, {
      method: 'POST',
      headers,
      body: payload
    })

    if (typeof window !== 'undefined') {
      try {
        const localHist = JSON.parse(localStorage.getItem('user_room_history') || '[]')
        localHist.unshift({
          id: res?.booking?.id || res?.data?.id || Date.now(),
          room: selectedRoom.value,
          room_id: selectedRoom.value.id,
          agenda: bookingForm.value.agenda,
          start_time: bookingForm.value.start_time || new Date().toISOString(),
          status: 'pending',
          notes: bookingForm.value.notes,
          created_at: new Date().toISOString()
        })
        localStorage.setItem('user_room_history', JSON.stringify(localHist.slice(0, 50)))
      } catch (e) {}
    }

    alertMsg.value = {
      type: 'success',
      text: res?.message || `Permohonan peminjaman ruangan ${selectedRoom.value.name} berhasil diajukan dengan status "Menunggu Persetujuan".`
    }
    selectedRoom.value = null
  } catch (err) {
    try {
      const fallback = await $fetch(`${apiBase}/v1/manage/room-bookings`, {
        method: 'POST',
        headers,
        body: payload
      })
      alertMsg.value = {
        type: 'success',
        text: fallback?.message || `Permohonan peminjaman ruangan berhasil diajukan dengan status "Menunggu Persetujuan".`
      }
      selectedRoom.value = null
    } catch (e) {
      if (typeof window !== 'undefined') {
        const localHist = JSON.parse(localStorage.getItem('user_room_history') || '[]')
        localHist.unshift({
          id: Date.now(),
          room: selectedRoom.value,
          room_id: selectedRoom.value.id,
          agenda: bookingForm.value.agenda,
          start_time: bookingForm.value.start_time || new Date().toISOString(),
          status: 'pending',
          notes: bookingForm.value.notes,
          created_at: new Date().toISOString()
        })
        localStorage.setItem('user_room_history', JSON.stringify(localHist.slice(0, 50)))
      }
      alertMsg.value = {
        type: 'success',
        text: `Permohonan peminjaman ruangan ${selectedRoom.value.name} berhasil diajukan dengan status "Menunggu Persetujuan".`
      }
      selectedRoom.value = null
    }
  } finally {
    isSubmitting.value = false
  }
}

// QR Code Modal Functions
const openBarcodeModal = (room) => {
  barcodeRoom.value = room
}

const reserveFromBarcode = (room) => {
  barcodeRoom.value = null
  openRoomModal(room)
}

const printBarcodeLabel = (room) => {
  if (typeof window === 'undefined') return
  const printWindow = window.open('', '_blank', 'width=550,height=650')
  if (!printWindow) {
    alert('Pop-up terblokir oleh browser. Harap izinkan pop-up untuk mencetak QR Code.')
    return
  }
  const qrSvg = `/images/barcodes/qr-${room.code}.svg`

  printWindow.document.write(`
    <!DOCTYPE html>
    <html>
    <head>
      <title>Label QR Pintu Ruangan - ${room.code} - ${room.name}</title>
      <style>
        @page { size: auto; margin: 8mm; }
        body {
          font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif;
          background: #ffffff;
          color: #0f172a;
          display: flex;
          flex-direction: column;
          align-items: center;
          justify-content: center;
          padding: 24px;
          margin: 0;
        }
        .label-card {
          border: 2px dashed #0f172a;
          border-radius: 16px;
          padding: 24px;
          width: 320px;
          text-align: center;
          box-sizing: border-box;
        }
        .header-tag {
          font-size: 11px;
          font-weight: 800;
          letter-spacing: 2px;
          color: #059669;
          margin-bottom: 6px;
        }
        .item-name {
          font-size: 16px;
          font-weight: bold;
          margin: 0 0 16px;
          color: #0f172a;
          line-height: 1.3;
        }
        .qr-wrap {
          display: flex;
          align-items: center;
          justify-content: center;
          margin: 12px 0;
        }
        .qr-img {
          width: 170px;
          height: 170px;
        }
        .meta-text {
          background: #f8fafc;
          border-radius: 8px;
          padding: 10px;
          font-size: 12px;
          color: #475569;
          line-height: 1.6;
          margin-top: 10px;
        }
        .meta-text b { color: #0f172a; }
        .footer-note {
          font-size: 9px;
          color: #94a3b8;
          margin-top: 14px;
        }
      </style>
    </head>
    <body>
      <div class="label-card">
        <div class="header-tag">FASILITAS RUANG RAPAT BPJS KESEHATAN</div>
        <h3 class="item-name">${room.name}</h3>
        <div class="qr-wrap">
          <img src="${qrSvg}" class="qr-img" alt="QR Code" />
        </div>
        <div class="meta-text">
          <div>Kode Ruangan: <b>${room.code}</b></div>
          <div>Kapasitas: <b>${room.capacity || 20} Orang</b></div>
          <div>Lokasi: <b>${room.floor || 'Gedung Kantor'}</b></div>
          <div>Fasilitas: <b>${room.facilities || room.facility || 'Lengkap'}</b></div>
        </div>
        <div class="footer-note">Dicetak dari Sistem SIMPEL • Pindai untuk Reservasi Cepat via Kamera HP</div>
      </div>
      <script>
        window.onload = function() {
          window.print();
        };
      <\/script>
    </body>
    </html>
  `)
  printWindow.document.close()
}

// Camera Barcode Scanner Functions
const startCamera = async () => {
  scanError.value = ''
  isScanning.value = true

  try {
    const { Html5Qrcode, Html5QrcodeSupportedFormats } = await import('html5-qrcode')
    usingHtml5Qrcode.value = true
    await nextTick()

    setTimeout(async () => {
      try {
        if (html5QrScannerInstance) {
          try {
            if (html5QrScannerInstance.isScanning) {
              await html5QrScannerInstance.stop()
            }
            await html5QrScannerInstance.clear()
          } catch (e) {}
          html5QrScannerInstance = null
        }

        html5QrScannerInstance = new Html5Qrcode('room-qr-reader', {
          formatsToSupport: [
            Html5QrcodeSupportedFormats.QR_CODE,
            Html5QrcodeSupportedFormats.CODE_128,
            Html5QrcodeSupportedFormats.EAN_13,
            Html5QrcodeSupportedFormats.CODE_39
          ],
          verbose: false
        })

        let cameraConfig = { facingMode: 'environment' }
        try {
          const devices = await Html5Qrcode.getCameras()
          if (devices && devices.length > 0) {
            const backCam = devices.find(d => /back|rear|belakang/i.test(d.label))
            cameraConfig = backCam ? backCam.id : devices[0].id
          }
        } catch (camErr) {}

        const qrConfig = {
          fps: 20,
          disableFlip: true
        }

        await html5QrScannerInstance.start(
          cameraConfig,
          qrConfig,
          (decodedText) => {
            handleBarcodeScanned(decodedText)
          },
          () => {}
        )
      } catch (startErr) {
        console.warn('Html5Qrcode start failed, falling back to native camera:', startErr)
        usingHtml5Qrcode.value = false
        startNativeCameraFallback()
      }
    }, 180)
  } catch (e) {
    console.warn('html5-qrcode module load failed, using native camera:', e)
    usingHtml5Qrcode.value = false
    startNativeCameraFallback()
  }
}

const startNativeCameraFallback = async () => {
  try {
    const stream = await navigator.mediaDevices.getUserMedia({
      video: { facingMode: 'environment', width: { ideal: 1280 }, height: { ideal: 720 } }
    })
    mediaStream.value = stream
    if (videoRef.value) {
      videoRef.value.srcObject = stream
      await videoRef.value.play()
    }

    if (typeof window !== 'undefined' && 'BarcodeDetector' in window) {
      barcodeDetector = new window.BarcodeDetector({
        formats: ['qr_code']
      })
      startBarcodeDetection()
    }
  } catch (err) {
    console.warn('Gagal membuka kamera fallback:', err)
    scanError.value = 'Kamera tidak dapat diakses atau izin ditolak. Harap izinkan akses kamera di browser Anda.'
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
  if (mediaStream.value) {
    mediaStream.value.getTracks().forEach(track => track.stop())
    mediaStream.value = null
  }
  isScanning.value = false
  usingHtml5Qrcode.value = false
}

const startBarcodeDetection = () => {
  const detectFrame = async () => {
    if (!isScanning.value || !videoRef.value || !barcodeDetector) return
    try {
      if (videoRef.value.readyState === videoRef.value.HAVE_ENOUGH_DATA) {
        const barcodes = await barcodeDetector.detect(videoRef.value)
        if (barcodes.length > 0) {
          const rawValue = barcodes[0].rawValue
          handleBarcodeScanned(rawValue)
          return
        }
      }
    } catch (e) {
      console.warn('Kesalahan scanner:', e)
    }
    animationFrameId = requestAnimationFrame(detectFrame)
  }
  animationFrameId = requestAnimationFrame(detectFrame)
}

const handleBarcodeScanned = (decodedText) => {
  if (!decodedText) return
  const cleanCode = String(decodedText).trim()
  stopCamera()

  const found = rooms.value.find(r =>
    String(r.code || '').toLowerCase() === cleanCode.toLowerCase() ||
    String(r.name || '').toLowerCase() === cleanCode.toLowerCase() ||
    String(r.id) === cleanCode
  )

  if (found) {
    alertMsg.value = {
      type: 'success',
      text: `Ruangan "${found.name}" (${found.code}) berhasil dipindai!`
    }
    openRoomModal(found)
  } else {
    alertMsg.value = {
      type: 'error',
      text: `QR Code "${cleanCode}" berhasil dipindai, namun ruangan tidak ditemukan dalam daftar.`
    }
  }
}

onMounted(() => {
  fetchRooms()
  if (import.meta.client) {
    const savedTheme = localStorage.getItem('user_theme_mode')
    if (savedTheme) {
      isDark.value = savedTheme === 'dark'
    }
  }
})

onUnmounted(() => {
  stopCamera()
})
</script>

<style scoped>
/* ========================================================================== */
/* BACKGROUND RUANG RAPAT (TETAP NATURAL, TANPA LAPISAN GELAP/TERANG)        */
/* ========================================================================== */
.ruangan-page {
  background-color: transparent !important;
}

.ruangan-bg-backdrop {
  position: fixed;
  inset: 0;
  background-image: url('/images/room-bg.jpg');
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
  --modal-bg: rgba(11, 19, 32, 0.88);
  --modal-backdrop: rgba(0, 0, 0, 0.75);
  --divider: rgba(16, 185, 129, 0.3);
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
  --modal-bg: rgba(255, 255, 255, 0.94);
  --modal-backdrop: rgba(15, 23, 42, 0.45);
  --divider: rgba(226, 232, 240, 0.8);
}

/* COMMON GLASSMORPHISM BOX STYLES */
.banner-card,
.section-header-card,
.room-card,
.modal-box,
.loading-box,
.empty-room-box {
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

.room-card {
  overflow: hidden;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  height: 100%;
}

.theme-dark .room-card:hover {
  border-color: #34d399;
  box-shadow: 0 0 32px rgba(16, 185, 129, 0.6), inset 0 0 20px rgba(16, 185, 129, 0.15);
}

/* TEXT COLOR OVERRIDES */
.banner-title,
.section-title,
.room-name,
.modal-title,
.empty-title {
  color: var(--text-main);
}

.room-name {
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.text-subtle {
  color: var(--text-sub);
}

.banner-desc {
  color: var(--text-sub);
}

.badge-procedure {
  background-color: rgba(16, 185, 129, 0.15);
  color: #10b981;
  border: 1px solid rgba(16, 185, 129, 0.3);
}

.btn-refresh-rooms {
  background-color: var(--inner-bg);
  border: 1px solid var(--inner-border);
  color: var(--text-sub);
}

.btn-refresh-rooms:hover {
  color: var(--text-main);
  border-color: #10b981;
}

/* ROOM CARD SPECIFICS */
.room-img-wrapper {
  background-color: var(--inner-bg);
  border: 1px solid var(--inner-border);
}

.badge-available {
  background-color: rgba(16, 185, 129, 0.9);
  color: #ffffff;
  border-color: rgba(16, 185, 129, 0.5);
}

.badge-booked {
  background-color: rgba(245, 158, 11, 0.9);
  color: #ffffff;
  border-color: rgba(245, 158, 11, 0.5);
}

.badge-code {
  background-color: var(--inner-bg);
  color: var(--text-sub);
  border: 1px solid var(--inner-border);
}

.room-location-box {
  background-color: var(--inner-bg);
  border: 1px solid var(--inner-border);
}

.room-footer {
  border-color: var(--divider);
}

.btn-booking {
  background-color: #10b981;
  color: #ffffff;
  box-shadow: 0 4px 14px rgba(16, 185, 129, 0.3);
}

.btn-booking:hover {
  background-color: #059669;
}

.btn-disabled {
  background-color: var(--inner-bg);
  color: var(--text-sub);
  border: 1px solid var(--inner-border);
  cursor: not-allowed;
}

/* ALERTS */
.alert-card {
  backdrop-filter: blur(14px);
  -webkit-backdrop-filter: blur(14px);
}

.alert-success {
  background-color: var(--box-bg);
  border-color: rgba(16, 185, 129, 0.4);
  color: #10b981;
}

.alert-error {
  background-color: var(--box-bg);
  border-color: rgba(239, 68, 68, 0.4);
  color: #ef4444;
}

.btn-close-alert {
  color: var(--text-sub);
}

.btn-close-alert:hover {
  color: var(--text-main);
}

/* MODAL SPECIFICS */
.modal-backdrop {
  background-color: var(--modal-backdrop);
}

.modal-box {
  background-color: var(--modal-bg);
}

.modal-header,
.modal-footer {
  border-color: var(--divider);
}

.modal-room-thumb {
  background-color: var(--inner-bg);
  border: 1px solid var(--inner-border);
}

.btn-close-modal {
  background-color: var(--inner-bg);
  color: var(--text-sub);
  border: 1px solid var(--inner-border);
}

.btn-close-modal:hover {
  color: var(--text-main);
}

.form-label {
  color: var(--text-main);
}

.form-input {
  background-color: var(--input-bg);
  border: 1px solid var(--input-border);
  color: var(--text-main);
}

.btn-modal-cancel {
  background-color: var(--inner-bg);
  color: var(--text-sub);
  border: 1px solid var(--inner-border);
}

.btn-modal-cancel:hover {
  color: var(--text-main);
}

.btn-modal-submit {
  background-color: #10b981;
  color: #ffffff;
  box-shadow: 0 4px 14px rgba(16, 185, 129, 0.35);
}

.btn-modal-submit:hover {
  background-color: #059669;
}

/* ========================================================================== */
/* SCANNER & QR CODE BUTTONS                                                  */
/* ========================================================================== */
.btn-scan-room-action {
  display: flex;
  align-items: center;
  gap: 8px;
  background: linear-gradient(135deg, #10b981 0%, #059669 100%);
  color: #ffffff;
  font-size: 13px;
  font-weight: 700;
  padding: 10px 18px;
  border-radius: 12px;
  border: none;
  box-shadow: 0 4px 14px rgba(16, 185, 129, 0.4);
  cursor: pointer;
  transition: all 0.2s ease;
}

.btn-scan-room-action:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(16, 185, 129, 0.55);
}

.btn-room-qr-action {
  display: flex;
  align-items: center;
  gap: 6px;
  padding: 10px 14px;
  background: var(--inner-bg);
  color: var(--text-main);
  border: 1px solid var(--inner-border);
  border-radius: 12px;
  cursor: pointer;
  transition: all 0.15s ease;
}

.btn-room-qr-action:hover {
  border-color: #10b981;
  color: #10b981;
}

/* ========================================================================== */
/* MODAL TICKET QR CODE PREVIEW                                               */
/* ========================================================================== */
.barcode-ticket-card {
  background: #ffffff;
  color: #0f172a;
  border: 2px dashed #cbd5e1;
  border-radius: 16px;
  padding: 20px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
}

.ticket-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-bottom: 1px solid #e2e8f0;
  padding-bottom: 8px;
  margin-bottom: 12px;
}

.ticket-brand {
  font-size: 11px;
  font-weight: 800;
  color: #059669;
  letter-spacing: 1px;
}

.ticket-category {
  font-size: 10px;
  font-weight: 700;
  background: #f1f5f9;
  color: #475569;
  padding: 2px 8px;
  border-radius: 9999px;
}

.ticket-room-row {
  display: flex;
  align-items: center;
  gap: 12px;
  text-align: left;
  margin-bottom: 14px;
}

.ticket-thumb {
  width: 52px;
  height: 52px;
  border-radius: 10px;
  object-fit: cover;
  border: 1px solid #e2e8f0;
  background: #f8fafc;
}

.ticket-room-info {
  flex: 1;
}

.ticket-name {
  font-size: 14px;
  font-weight: 800;
  color: #0f172a;
  line-height: 1.3;
}

.ticket-meta-grid {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
  font-size: 11px;
  color: #64748b;
  margin-top: 4px;
}

.ticket-meta-grid b {
  color: #0f172a;
}

.ticket-qr-main-box {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  padding: 16px;
  margin-bottom: 4px;
}

.ticket-qr-large-img {
  width: 180px;
  height: 180px;
  display: block;
}

.ticket-qr-badge {
  margin-top: 8px;
  font-size: 11px;
  font-weight: 800;
  color: #059669;
  background: #ecfdf5;
  padding: 3px 12px;
  border-radius: 9999px;
  letter-spacing: 0.5px;
}

.ticket-qr-hint {
  font-size: 10px;
  color: #94a3b8;
  margin-top: 4px;
}

.btn-print-barcode {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  padding: 12px 18px;
  background: #10b981;
  color: #ffffff;
  border: none;
  border-radius: 12px;
  font-size: 13px;
  font-weight: 700;
  cursor: pointer;
  box-shadow: 0 4px 14px rgba(16, 185, 129, 0.35);
  transition: all 0.2s ease;
}

.btn-print-barcode:hover {
  background: #059669;
  transform: translateY(-1px);
}

.btn-download-barcode {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
  padding: 10px 16px;
  background: var(--inner-bg);
  color: var(--text-main);
  border: 1px solid var(--inner-border);
  border-radius: 12px;
  font-size: 12px;
  font-weight: 600;
  text-decoration: none;
  transition: all 0.15s ease;
}

.btn-download-barcode:hover {
  border-color: #10b981;
  color: #10b981;
}

.btn-quick-reserve {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
  padding: 10px 16px;
  background: rgba(16, 185, 129, 0.15);
  color: #10b981;
  border: 1px solid rgba(16, 185, 129, 0.35);
  border-radius: 12px;
  font-size: 12px;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.15s ease;
}

.btn-quick-reserve:hover {
  background: #10b981;
  color: #ffffff;
}

/* ========================================================================== */
/* CAMERA SCANNER LAYOUT: FULL & TERPUSAT 100%                                */
/* ========================================================================== */
.camera-card {
  max-width: 480px;
  width: 92vw;
  border-radius: 20px;
  overflow: hidden;
  background: #0f172a;
  box-shadow: 0 25px 60px rgba(0, 0, 0, 0.75);
}

.camera-viewfinder-box {
  position: relative;
  width: 100%;
  aspect-ratio: 4 / 3;
  background: #000000;
  overflow: hidden;
}

.html5-qr-container {
  position: absolute;
  inset: 0;
  width: 100%;
  height: 100%;
  background: #000000;
}

#room-qr-reader {
  position: absolute !important;
  inset: 0 !important;
  width: 100% !important;
  height: 100% !important;
  border: none !important;
  background: #000000 !important;
  padding: 0 !important;
  margin: 0 !important;
  overflow: hidden !important;
}

#room-qr-reader video,
.camera-video-feed {
  position: absolute !important;
  inset: 0 !important;
  width: 100% !important;
  height: 100% !important;
  min-width: 100% !important;
  min-height: 100% !important;
  object-fit: cover !important;
  object-position: center !important;
  display: block !important;
  transform: none !important;
  -webkit-transform: none !important;
}

#room-qr-reader__scan_region {
  position: absolute !important;
  inset: 0 !important;
  width: 100% !important;
  height: 100% !important;
  background: transparent !important;
  display: flex !important;
  align-items: center !important;
  justify-content: center !important;
  overflow: hidden !important;
}

#room-qr-reader__scan_region img,
#room-qr-reader__dashboard,
#room-qr-reader__header_message {
  display: none !important;
}

/* OVERLAY KOTAK BIDIK YANG PRESISI DI TENGAH SESUAI KAMERA */
.scanner-custom-overlay {
  position: absolute;
  inset: 0;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  pointer-events: none;
  z-index: 10;
}

.scanner-target-box {
  position: relative;
  width: 250px;
  height: 250px;
  max-width: 72%;
  max-height: 72%;
  border: 2px dashed rgba(16, 185, 129, 0.45);
  border-radius: 20px;
  box-shadow: 0 0 0 9999px rgba(0, 0, 0, 0.42);
}

.corner {
  position: absolute;
  width: 28px;
  height: 28px;
  border-color: #10b981;
  border-style: solid;
}

.top-left { top: -2px; left: -2px; border-width: 4px 0 0 4px; border-top-left-radius: 12px; }
.top-right { top: -2px; right: -2px; border-width: 4px 4px 0 0; border-top-right-radius: 12px; }
.bottom-left { bottom: -2px; left: -2px; border-width: 0 0 4px 4px; border-bottom-left-radius: 12px; }
.bottom-right { bottom: -2px; right: -2px; border-width: 0 4px 4px 0; border-bottom-right-radius: 12px; }

.scanner-laser-line {
  position: absolute;
  left: 6px;
  right: 6px;
  height: 2.5px;
  background: linear-gradient(90deg, transparent 0%, #10b981 50%, transparent 100%);
  box-shadow: 0 0 10px #10b981, 0 0 20px rgba(16, 185, 129, 0.6);
  animation: laserSweep 2s ease-in-out infinite alternate;
}

@keyframes laserSweep {
  0% { top: 12px; opacity: 0.85; }
  100% { top: calc(100% - 14px); opacity: 0.85; }
}

.scanner-guide-text {
  margin-top: 14px;
  background: rgba(15, 23, 42, 0.75);
  backdrop-filter: blur(6px);
  border: 1px solid rgba(255, 255, 255, 0.1);
  color: #e2e8f0;
  font-size: 11px;
  font-weight: 600;
  padding: 5px 14px;
  border-radius: 9999px;
  letter-spacing: 0.3px;
  text-shadow: 0 1px 2px rgba(0, 0, 0, 0.8);
}
</style>

<!-- GLOBAL UNSCOPED STYLE UNTUK ELEMEN YANG DIINJEKSIKAN DINAMIS OLEH HTML5-QRCODE -->
<style>
#room-qr-reader {
  position: absolute !important;
  inset: 0 !important;
  width: 100% !important;
  height: 100% !important;
  border: none !important;
  background: #000000 !important;
  padding: 0 !important;
  margin: 0 !important;
  overflow: hidden !important;
}

#room-qr-reader video {
  position: absolute !important;
  inset: 0 !important;
  width: 100% !important;
  height: 100% !important;
  min-width: 100% !important;
  min-height: 100% !important;
  object-fit: cover !important;
  object-position: center !important;
  display: block !important;
  transform: none !important;
  -webkit-transform: none !important;
}

#room-qr-reader__scan_region {
  position: absolute !important;
  inset: 0 !important;
  width: 100% !important;
  height: 100% !important;
  background: transparent !important;
  overflow: hidden !important;
}

#room-qr-reader__scan_region img,
#room-qr-reader__dashboard,
#room-qr-reader__header_message {
  display: none !important;
}
</style>