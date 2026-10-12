<script setup>
import { ref } from 'vue'

useHead({
  title: 'Login - Portal Layanan Internal'
})

// Konfigurasi koneksi API
const config = useRuntimeConfig()
const nip = ref('')
const password = ref('')
const showPassword = ref(false)
const isLoading = ref(false)
const errorMessage = ref('')

const handleLogin = async () => {
  isLoading.value = true
  errorMessage.value = ''
  
  try {
    const response = await $fetch(`${config.public.apiBase}/login`, {
      method: 'POST',
      body: {
        nip: nip.value,
        password: password.value
      }
    })
    
    // Simpan token & data user ke cookie dan localStorage
    const token = useCookie('auth_token', { path: '/', maxAge: 60 * 60 * 24 * 7 })
    token.value = response.access_token

    const userCookie = useCookie('auth_user', { path: '/', maxAge: 60 * 60 * 24 * 7 })
    userCookie.value = response.user

    if (typeof window !== 'undefined') {
      localStorage.setItem('auth_token', response.access_token)
      localStorage.setItem('auth_user', JSON.stringify(response.user))
      // Bersihkan cache aktivitas lama agar tersinkronisasi murni dengan database
      localStorage.removeItem('user_atk_history')
      localStorage.removeItem('user_vehicle_history')
      localStorage.removeItem('user_room_history')
      localStorage.removeItem('ob_cleaning_pickups')
    }

    // Arahkan ke dashboard sesuai role pengguna
    const role = response.user ? response.user.role : 'user'
    if (role === 'admin') {
      navigateTo('/admin/dashboard')
    } else if (role === 'sopir') {
      navigateTo('/sopir/dashboard')
    } else if (role === 'ob') {
      navigateTo('/ob/dashboard')
    } else {
      navigateTo('/User/dashboard')
    }
    
  } catch (error) {
    if (error.response && error.response._data && error.response._data.message) {
      errorMessage.value = error.response._data.message
    } else if (error.response && error.response._data && error.response._data.errors) {
       // Menangkap pesan error dari validasi laravel
       const errors = error.response._data.errors;
       errorMessage.value = Object.values(errors).flat()[0];
    } else {
      errorMessage.value = 'Terjadi kesalahan saat menghubungi server.'
    }
  } finally {
    isLoading.value = false
  }
}
</script>

<template>
  <div class="min-h-screen bg-white flex flex-col md:flex-row font-sans">
    
    <!-- Left Section: Branding -->
    <div class="hidden md:flex md:w-[40%] bg-emerald-800 text-white flex-col justify-between p-12">
      <!-- Logo / Title Top -->
      <div class="flex items-center gap-3.5">
        <div class="w-12 h-12 bg-white rounded-2xl p-1.5 flex items-center justify-center shadow-lg shadow-black/15">
          <img src="/images/logo-simpel-icon.png" alt="Logo SIMPEL" class="w-full h-full object-contain" />
        </div>
        <div>
          <span class="text-2xl font-black tracking-wider text-white">SIMPEL</span>
          <span class="block text-[11px] text-emerald-200 font-medium">Sistem Informasi Manajemen Pelayanan</span>
        </div>
      </div>
      
      <!-- Main Branding Text -->
      <div class="my-auto max-w-md">
        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/10 backdrop-blur-sm border border-white/15 text-emerald-100 text-xs font-semibold mb-6">
          <span class="w-2 h-2 rounded-full bg-emerald-300 animate-pulse"></span>
          Aplikasi Manajemen Internal Terpadu
        </div>
        <h1 class="text-5xl font-bold leading-tight mb-6 tracking-tight">
          Layanan Cepat.<br/>
          Kerja Tepat.
        </h1>
        <p class="text-emerald-200 text-lg leading-relaxed">
          Kelola pengambilan alat tulis kantor, peminjaman ruangan, mobil operasional, hingga alat kebersihan dalam satu pintu dengan mudah dan cepat.
        </p>
      </div>
      
      <!-- Bottom Decorative / Info -->
      <div class="text-sm text-emerald-300">
        &copy; {{ new Date().getFullYear() }} SIMPEL - Sistem Informasi Manajemen Pelayanan.
      </div>
    </div>
    
    <!-- Right Section: Login Form -->
    <div class="w-full md:w-[60%] flex items-center justify-center p-8 bg-gray-50/50">
      <div class="w-full max-w-md bg-white rounded-2xl p-8 md:p-10 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100">
        
        <div class="mb-8">
          <div class="mb-6 flex items-center justify-between gap-3 flex-wrap">
            <img src="/images/logo-simpel.png" alt="SIMPEL - Sistem Informasi Manajemen Pelayanan" class="h-10 md:h-12 w-auto object-contain max-w-[200px]" />
            <RealtimeClock theme="light" />
          </div>
          <h2 class="text-2xl font-bold text-gray-900 mb-2">Selamat Datang!</h2>
          <p class="text-gray-500 text-sm">Masuk dengan NPP atau Username untuk melanjutkan ke Portal.</p>
        </div>

        <!-- Pesan Error -->
        <div v-if="errorMessage" class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 text-sm rounded">
          {{ errorMessage }}
        </div>

        <form @submit.prevent="handleLogin" class="space-y-5">
          <!-- NPP / Username Input -->
          <div>
            <div class="flex items-center justify-between mb-1.5">
              <label for="nip" class="block text-sm font-medium text-gray-700">NPP / Username</label>
              <span class="text-[11px] text-gray-400">Pegawai (NPP) • Pengemudi / CS (Username)</span>
            </div>
            <input 
              id="nip" 
              v-model="nip"
              type="text" 
              required
              class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-emerald-800 focus:border-emerald-800 outline-none transition-all text-sm"
              placeholder="Masukkan NPP atau Username Anda"
            />
          </div>
          
          <!-- Password Input -->
          <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Kata Sandi</label>
            <div class="relative">
              <input 
                id="password" 
                v-model="password"
                :type="showPassword ? 'text' : 'password'" 
                required
                class="w-full pl-4 pr-10 py-2.5 rounded-lg border border-gray-300 focus:ring-2 focus:ring-emerald-800 focus:border-emerald-800 outline-none transition-all text-sm"
                placeholder="Masukkan kata sandi"
              />
              <!-- Toggle Password Icon Button -->
              <button 
                type="button" 
                @click="showPassword = !showPassword"
                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none transition-colors"
                tabindex="-1"
                title="Tampilkan/Sembunyikan Kata Sandi"
              >
                <svg v-if="!showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858-5.908a10.025 10.025 0 014.122-.963c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21M3 3l18 18" />
                </svg>
              </button>
            </div>
          </div>

          <!-- Submit Button -->
          <div class="pt-2">
            <button 
              type="submit" 
              :disabled="isLoading"
              class="w-full bg-emerald-800 hover:bg-emerald-900 text-white font-medium py-2.5 px-4 rounded-lg transition-colors flex justify-center items-center shadow-md disabled:opacity-70 disabled:cursor-not-allowed"
            >
              <svg v-if="isLoading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              {{ isLoading ? 'Memproses...' : 'Masuk' }}
            </button>
          </div>
          
          <div class="text-right mt-4">
            <NuxtLink to="/forgot-password" class="text-sm text-gray-500 hover:text-emerald-800 transition-colors font-medium">Lupa Kata Sandi?</NuxtLink>
          </div>
        </form>
        
      </div>
    </div>
    
  </div>
</template>

<style scoped>
/* Sembunyikan icon mata bawaan browser (MS Edge/IE/Chrome) agar tidak tumpang tindih */
input::-ms-reveal,
input::-ms-clear {
  display: none;
}
</style>
