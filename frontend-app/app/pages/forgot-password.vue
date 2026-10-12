<script setup>
import { ref, reactive } from 'vue'

useHead({
  title: 'Lupa Kata Sandi - Portal SIMPEL'
})

const config = useRuntimeConfig()

// Stepper State: 1 = Input NIP, 2 = Input New Password, 3 = Success
const currentStep = ref(1)

// Form Data
const nip = ref('')
const newPassword = ref('')
const confirmPassword = ref('')
const showPassword = ref(false)
const showConfirmPassword = ref(false)

// Verified User Info from API
const verifiedUser = reactive({
  id: null,
  name: '',
  nip: '',
  division: '',
  role: ''
})

// UI State
const isLoading = ref(false)
const errorMessage = ref('')
const successMessage = ref('')

// Step 1: Verifikasi NIP / NPP Pegawai
const handleCheckNip = async () => {
  if (!nip.value.trim()) {
    errorMessage.value = 'Silakan masukkan NIP atau Username Anda.'
    return
  }

  isLoading.value = true
  errorMessage.value = ''

  try {
    const res = await $fetch(`${config.public.apiBase}/check-user-reset`, {
      method: 'POST',
      body: { nip: nip.value.trim() }
    })

    if (res && res.status === 'success') {
      verifiedUser.id = res.user_id
      verifiedUser.name = res.name
      verifiedUser.nip = res.nip
      verifiedUser.division = res.division
      verifiedUser.role = res.role
      currentStep.value = 2
    } else {
      errorMessage.value = res.message || 'Pengguna tidak ditemukan.'
    }
  } catch (error) {
    if (error.response && error.response._data && error.response._data.message) {
      errorMessage.value = error.response._data.message
    } else {
      errorMessage.value = 'NIP / Username tidak ditemukan dalam database kepegawaian.'
    }
  } finally {
    isLoading.value = false
  }
}

// Step 2: Set Password Baru
const handleResetPassword = async () => {
  errorMessage.value = ''

  if (!newPassword.value) {
    errorMessage.value = 'Kata sandi baru wajib diisi.'
    return
  }

  if (newPassword.value.length < 6) {
    errorMessage.value = 'Kata sandi minimal harus 6 karakter.'
    return
  }

  if (newPassword.value !== confirmPassword.value) {
    errorMessage.value = 'Konfirmasi kata sandi tidak cocok. Harap periksa kembali.'
    return
  }

  isLoading.value = true

  try {
    const res = await $fetch(`${config.public.apiBase}/reset-password`, {
      method: 'POST',
      body: {
        nip: verifiedUser.nip,
        password: newPassword.value,
        password_confirmation: confirmPassword.value
      }
    })

    if (res && res.status === 'success') {
      successMessage.value = res.message || 'Kata sandi Anda berhasil diperbarui.'
      currentStep.value = 3
    } else {
      errorMessage.value = res.message || 'Gagal memperbarui kata sandi.'
    }
  } catch (error) {
    if (error.response && error.response._data && error.response._data.message) {
      errorMessage.value = error.response._data.message
    } else if (error.response && error.response._data && error.response._data.errors) {
      const errs = error.response._data.errors
      errorMessage.value = Object.values(errs).flat()[0]
    } else {
      errorMessage.value = 'Terjadi kesalahan saat menyimpan kata sandi baru.'
    }
  } finally {
    isLoading.value = false
  }
}

// Reset ke step 1
const handleChangeAccount = () => {
  currentStep.value = 1
  newPassword.value = ''
  confirmPassword.value = ''
  errorMessage.value = ''
}
</script>

<template>
  <div class="min-h-screen bg-slate-50 flex flex-col md:flex-row font-sans">
    
    <!-- Left Section: Branding & Info -->
    <div class="hidden md:flex md:w-[40%] bg-emerald-800 text-white flex-col justify-between p-12 relative overflow-hidden">
      <!-- Background subtle decorative shapes -->
      <div class="absolute -top-24 -left-24 w-96 h-96 rounded-full bg-emerald-700/50 blur-3xl pointer-events-none"></div>
      <div class="absolute -bottom-24 -right-24 w-96 h-96 rounded-full bg-emerald-900/50 blur-3xl pointer-events-none"></div>

      <!-- Top Header / Logo -->
      <div class="relative z-10 flex items-center gap-3.5">
        <div class="w-12 h-12 bg-white rounded-2xl p-1.5 flex items-center justify-center shadow-lg shadow-black/15">
          <img src="/images/logo-simpel-icon.png" alt="Logo SIMPEL" class="w-full h-full object-contain" />
        </div>
        <div>
          <span class="text-2xl font-black tracking-wider text-white">SIMPEL</span>
          <span class="block text-[11px] text-emerald-200 font-medium">Sistem Informasi Manajemen Pelayanan</span>
        </div>
      </div>

      <!-- Center Copy -->
      <div class="relative z-10 my-auto max-w-md">
        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/10 backdrop-blur-sm border border-white/15 text-emerald-100 text-xs font-semibold mb-6">
          <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>
          Bantuan Akses Akun
        </div>
        <h1 class="text-4xl font-extrabold leading-tight mb-4 tracking-tight">
          Pemulihan & Atur Ulang Kata Sandi
        </h1>
        <p class="text-emerald-100/90 text-sm leading-relaxed mb-8">
          Jangan khawatir jika Anda lupa kata sandi. Cukup masukkan Nomor Induk Pegawai (NIP/NPP) Anda untuk memverifikasi akun dan membuat kata sandi baru.
        </p>

        <!-- Feature List -->
        <div class="space-y-4">
          <div class="flex items-start gap-3.5">
            <div class="w-8 h-8 rounded-lg bg-emerald-700/60 border border-emerald-600/40 flex items-center justify-center text-sm flex-shrink-0">
              🔍
            </div>
            <div>
              <h4 class="text-sm font-semibold text-white">Verifikasi NIP Instan</h4>
              <p class="text-xs text-emerald-200/80">Sistem memverifikasi akun secara otomatis dari database pegawai.</p>
            </div>
          </div>
          <div class="flex items-start gap-3.5">
            <div class="w-8 h-8 rounded-lg bg-emerald-700/60 border border-emerald-600/40 flex items-center justify-center text-sm flex-shrink-0">
              🔒
            </div>
            <div>
              <h4 class="text-sm font-semibold text-white">Keamanan Terenkripsi</h4>
              <p class="text-xs text-emerald-200/80">Kata sandi baru langsung dienkripsi dengan standar hash keamanan tinggi.</p>
            </div>
          </div>
          <div class="flex items-start gap-3.5">
            <div class="w-8 h-8 rounded-lg bg-emerald-700/60 border border-emerald-600/40 flex items-center justify-center text-sm flex-shrink-0">
              ⚡
            </div>
            <div>
              <h4 class="text-sm font-semibold text-white">Langsung Aktif</h4>
              <p class="text-xs text-emerald-200/80">Setelah reset berhasil, Anda dapat langsung login kembali ke dashboard.</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Bottom Footer Note -->
      <div class="relative z-10 text-xs text-emerald-200/80 flex items-center justify-between">
        <span>SIMPEL &copy; {{ new Date().getFullYear() }}</span>
        <span>Layanan Bantuan Internal</span>
      </div>
    </div>

    <!-- Right Section: Form Stepper -->
    <div class="w-full md:w-[60%] flex flex-col justify-center items-center p-6 md:p-14 relative bg-white">
      
      <!-- Back to Login Navigation -->
      <div class="w-full max-w-lg mb-6 flex justify-between items-center">
        <NuxtLink 
          to="/login" 
          class="inline-flex items-center gap-2 text-sm font-medium text-gray-500 hover:text-emerald-800 transition-colors"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
          </svg>
          Kembali ke Halaman Masuk
        </NuxtLink>

        <!-- Stepper Indicator -->
        <div class="flex items-center gap-1.5 text-xs font-semibold text-gray-400">
          <span :class="currentStep >= 1 ? 'text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full' : ''">Langkah {{ currentStep }} dari 3</span>
        </div>
      </div>

      <!-- Main Box Container -->
      <div class="w-full max-w-lg bg-white border border-slate-200/80 rounded-2xl shadow-xl shadow-slate-200/40 p-8 sm:p-10">

        <!-- STEP PROGRESS BAR -->
        <div class="flex items-center justify-between mb-8 relative">
          <div class="absolute left-0 top-1/2 -translate-y-1/2 h-1 bg-slate-100 w-full z-0 rounded-full"></div>
          <div 
            class="absolute left-0 top-1/2 -translate-y-1/2 h-1 bg-emerald-600 transition-all duration-500 z-0 rounded-full"
            :style="{ width: currentStep === 1 ? '0%' : currentStep === 2 ? '50%' : '100%' }"
          ></div>

          <!-- Step 1 Pin -->
          <div class="relative z-10 flex flex-col items-center gap-1.5">
            <div 
              class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-xs transition-colors duration-300 shadow-sm"
              :class="currentStep >= 1 ? 'bg-emerald-700 text-white' : 'bg-slate-200 text-slate-500'"
            >
              1
            </div>
            <span class="text-[11px] font-semibold text-gray-500">Cek NIP</span>
          </div>

          <!-- Step 2 Pin -->
          <div class="relative z-10 flex flex-col items-center gap-1.5">
            <div 
              class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-xs transition-colors duration-300 shadow-sm"
              :class="currentStep >= 2 ? 'bg-emerald-700 text-white' : 'bg-slate-200 text-slate-500'"
            >
              2
            </div>
            <span class="text-[11px] font-semibold text-gray-500">Kata Sandi Baru</span>
          </div>

          <!-- Step 3 Pin -->
          <div class="relative z-10 flex flex-col items-center gap-1.5">
            <div 
              class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-xs transition-colors duration-300 shadow-sm"
              :class="currentStep === 3 ? 'bg-emerald-700 text-white' : 'bg-slate-200 text-slate-500'"
            >
              ✓
            </div>
            <span class="text-[11px] font-semibold text-gray-500">Selesai</span>
          </div>
        </div>

        <!-- ALERT ERROR -->
        <div v-if="errorMessage" class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-sm flex items-start gap-3 animate-shake">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0 text-rose-500 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
          </svg>
          <span class="font-medium leading-relaxed">{{ errorMessage }}</span>
        </div>

        <!-- =================================================================== -->
        <!-- STEP 1: VERIFIKASI NIP / NPP                                        -->
        <!-- =================================================================== -->
        <div v-if="currentStep === 1">
          <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Verifikasi Akun Pegawai</h2>
            <p class="text-sm text-gray-500 mt-1.5 leading-relaxed">
              Masukkan Nomor Induk Pegawai (NIP/NPP) yang terdaftar pada akun Anda.
            </p>
          </div>

          <form @submit.prevent="handleCheckNip" class="space-y-5">
            <div>
              <label for="input-nip" class="block text-sm font-semibold text-gray-700 mb-1.5">
                NPP / NIP atau Username
              </label>
              <div class="relative">
                <input 
                  id="input-nip"
                  v-model="nip"
                  type="text" 
                  required
                  placeholder="Contoh: 12345 atau NIP Anda"
                  class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-emerald-700/20 focus:border-emerald-700 transition-all outline-none text-gray-900 placeholder:text-gray-400 text-sm font-medium"
                />
                <span class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                  </svg>
                </span>
              </div>
              <p class="text-[11.5px] text-gray-400 mt-1.5">
                Pastikan NIP sudah terdaftar di sistem SIMPEL.
              </p>
            </div>

            <button 
              type="submit" 
              :disabled="isLoading"
              class="w-full bg-emerald-800 hover:bg-emerald-900 text-white font-semibold py-3 px-4 rounded-xl transition-all flex justify-center items-center shadow-lg shadow-emerald-800/20 disabled:opacity-70 disabled:cursor-not-allowed text-sm"
            >
              <svg v-if="isLoading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              {{ isLoading ? 'Memeriksa Data...' : 'Verifikasi Akun Saya ➔' }}
            </button>
          </form>
        </div>

        <!-- =================================================================== -->
        <!-- STEP 2: ATUR KATA SANDI BARU                                        -->
        <!-- =================================================================== -->
        <div v-else-if="currentStep === 2">
          <!-- User Confirmation Banner -->
          <div class="mb-6 p-4 rounded-xl bg-emerald-50/70 border border-emerald-200/80 flex items-center justify-between">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-full bg-emerald-700 text-white font-bold flex items-center justify-center text-sm shadow-sm">
                {{ verifiedUser.name.charAt(0).toUpperCase() }}
              </div>
              <div>
                <h4 class="text-sm font-bold text-gray-900">{{ verifiedUser.name }}</h4>
                <p class="text-xs text-gray-500">NIP: {{ verifiedUser.nip }} • Divisi: {{ verifiedUser.division }}</p>
              </div>
            </div>
            <button 
              type="button" 
              @click="handleChangeAccount" 
              class="text-xs text-emerald-800 hover:underline font-semibold"
            >
              Ganti Akun
            </button>
          </div>

          <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Buat Kata Sandi Baru</h2>
            <p class="text-sm text-gray-500 mt-1 leading-relaxed">
              Silakan masukkan kata sandi baru untuk akun Anda (minimal 6 karakter).
            </p>
          </div>

          <form @submit.prevent="handleResetPassword" class="space-y-4">
            <!-- Kata Sandi Baru -->
            <div>
              <label for="input-new-pass" class="block text-sm font-semibold text-gray-700 mb-1.5">
                Kata Sandi Baru
              </label>
              <div class="relative">
                <input 
                  id="input-new-pass"
                  v-model="newPassword"
                  :type="showPassword ? 'text' : 'password'" 
                  required
                  placeholder="Minimal 6 karakter"
                  class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-emerald-700/20 focus:border-emerald-700 transition-all outline-none text-gray-900 placeholder:text-gray-400 text-sm font-medium pr-10"
                />
                <button 
                  type="button"
                  @click="showPassword = !showPassword"
                  class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors"
                >
                  <span v-if="!showPassword">👁️</span>
                  <span v-else>🙈</span>
                </button>
              </div>
            </div>

            <!-- Konfirmasi Kata Sandi Baru -->
            <div>
              <label for="input-confirm-pass" class="block text-sm font-semibold text-gray-700 mb-1.5">
                Konfirmasi Kata Sandi Baru
              </label>
              <div class="relative">
                <input 
                  id="input-confirm-pass"
                  v-model="confirmPassword"
                  :type="showConfirmPassword ? 'text' : 'password'" 
                  required
                  placeholder="Ulangi kata sandi baru"
                  class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-emerald-700/20 focus:border-emerald-700 transition-all outline-none text-gray-900 placeholder:text-gray-400 text-sm font-medium pr-10"
                />
                <button 
                  type="button"
                  @click="showConfirmPassword = !showConfirmPassword"
                  class="absolute right-3.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors"
                >
                  <span v-if="!showConfirmPassword">👁️</span>
                  <span v-else>🙈</span>
                </button>
              </div>
            </div>

            <!-- Submit Button -->
            <button 
              type="submit" 
              :disabled="isLoading"
              class="w-full mt-2 bg-emerald-800 hover:bg-emerald-900 text-white font-semibold py-3 px-4 rounded-xl transition-all flex justify-center items-center shadow-lg shadow-emerald-800/20 disabled:opacity-70 disabled:cursor-not-allowed text-sm"
            >
              <svg v-if="isLoading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              {{ isLoading ? 'Menyimpan Kata Sandi...' : 'Simpan Kata Sandi Baru' }}
            </button>
          </form>
        </div>

        <!-- =================================================================== -->
        <!-- STEP 3: SUKSES                                                      -->
        <!-- =================================================================== -->
        <div v-else-if="currentStep === 3" class="text-center py-4">
          <div class="w-16 h-16 bg-emerald-100 text-emerald-700 rounded-full flex items-center justify-center mx-auto mb-5 shadow-inner">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-9 w-9" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
            </svg>
          </div>

          <h2 class="text-2xl font-bold text-gray-900 tracking-tight mb-2">Kata Sandi Berhasil Diperbarui!</h2>
          <p class="text-sm text-gray-600 max-w-sm mx-auto leading-relaxed mb-6">
            Akun Anda dengan NIP <b class="text-gray-900">{{ verifiedUser.nip }}</b> sekarang sudah dapat diakses kembali menggunakan kata sandi yang baru.
          </p>

          <NuxtLink 
            to="/login"
            class="inline-flex w-full justify-center items-center gap-2 bg-emerald-800 hover:bg-emerald-900 text-white font-semibold py-3 px-6 rounded-xl transition-all shadow-lg shadow-emerald-800/20 text-sm"
          >
            Masuk Sekarang ➔
          </NuxtLink>
        </div>

      </div>

      <!-- Helpdesk Assistance Banner -->
      <div class="w-full max-w-lg mt-6 p-4 rounded-xl bg-slate-100/80 border border-slate-200/80 flex items-center justify-between text-xs text-gray-600">
        <div class="flex items-center gap-2.5">
          <span class="text-base">💬</span>
          <span>Masih mengalami kendala akses akun?</span>
        </div>
        <a 
          href="https://wa.me/6281234567890?text=Halo%20Admin%20SIMPEL,%20saya%20butuh%20bantuan%20reset%20kata%20sandi" 
          target="_blank"
          class="font-semibold text-emerald-800 hover:underline"
        >
          Hubungi Helpdesk
        </a>
      </div>

    </div>

  </div>
</template>

<style scoped>
/* Sembunyikan icon mata bawaan browser agar tidak tumpang tindih */
input::-ms-reveal,
input::-ms-clear {
  display: none;
}

@keyframes shake {
  0%, 100% { transform: translateX(0); }
  20%, 60% { transform: translateX(-4px); }
  40%, 80% { transform: translateX(4px); }
}

.animate-shake {
  animation: shake 0.4s ease-in-out;
}
</style>
