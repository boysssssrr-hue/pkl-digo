<script setup>
// Page meta for custom titles or layouts
useHead({
  title: 'Portal Layanan Internal',
  meta: [
    { name: 'description', content: 'Portal Layanan Internal untuk ATK, Ruangan, Mobil, dan Cleaning Service' }
  ]
})
</script>

<template>
  <div class="min-h-screen bg-gray-50 flex flex-col font-sans">
    
    <!-- Navbar -->
    <header class="bg-white shadow-sm sticky top-0 z-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
          <div class="flex items-center gap-3">
            <NuxtLink to="/" class="flex items-center">
              <img src="/images/logo-simpel.png" alt="SIMPEL - Sistem Informasi Manajemen Pelayanan" class="h-12 md:h-14 w-auto object-contain" />
            </NuxtLink>
          </div>
          <div class="flex items-center gap-3">
            <RealtimeClock theme="light" class="hidden sm:inline-flex" />
            <NuxtLink to="/login" class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-2.5 rounded-full font-semibold transition-all duration-300 shadow-lg shadow-teal-500/30 transform hover:-translate-y-0.5">
              Masuk
            </NuxtLink>
          </div>
        </div>
      </div>
    </header>

    <!-- Hero Section -->
    <section class="relative bg-teal-700 overflow-hidden">
      <!-- Image Background with Transparency (Overlay) -->
      <div class="absolute inset-0 z-0">
        <img src="/dashboard-bg.png" alt="Background BPJS" class="w-full h-full object-cover object-center opacity-30 mix-blend-overlay" />
      </div>

      <!-- Decorative Background Elements (Optional, to keep it dynamic) -->
      <div class="absolute inset-0 z-0 pointer-events-none">
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-teal-500 rounded-full mix-blend-multiply filter blur-3xl opacity-60 animate-blob"></div>
        <div class="absolute top-24 -left-24 w-72 h-72 bg-emerald-500 rounded-full mix-blend-multiply filter blur-3xl opacity-60 animate-blob animation-delay-2000"></div>
      </div>
      
      <!-- Hero Content -->
      <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-32 flex flex-col items-center text-center">
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-teal-800/50 backdrop-blur-md text-teal-100 mb-8 border border-teal-600/50">
          <span class="relative flex h-3 w-3">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
          </span>
          <span class="text-sm font-medium">Sistem Berjalan Normal</span>
        </div>
        
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold text-white tracking-tight mb-6">
          Selamat Datang di <br/>
          <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-300 to-teal-100">Portal Layanan Internal</span>
        </h1>
        
        <p class="mt-4 text-lg md:text-xl text-teal-100 max-w-2xl mx-auto font-light leading-relaxed mb-10">
          Sistem terpadu untuk mempermudah operasional kantor. Ajukan kebutuhan alat tulis, ruangan, kendaraan, dan kebersihan dalam satu pintu dengan mudah dan cepat.
        </p>

        <a href="#services" class="inline-flex items-center justify-center gap-2 bg-white text-teal-700 px-8 py-3.5 rounded-full font-bold text-lg hover:bg-gray-50 transition-all duration-300 shadow-xl hover:shadow-2xl transform hover:-translate-y-1">
          Lihat Layanan
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
          </svg>
        </a>
      </div>
      
      <!-- Bottom curve separator -->
      <div class="absolute bottom-0 w-full overflow-hidden leading-none rotate-180">
        <svg class="relative block w-[calc(100%+1.3px)] h-[50px]" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
          <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z" class="fill-gray-50"></path>
        </svg>
      </div>
    </section>

    <!-- Services Section -->
    <main id="services" class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24 w-full">
      <div class="text-center mb-16">
        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Layanan Utama Kami</h2>
        <div class="h-1.5 w-24 bg-teal-600 rounded-full mx-auto mb-6"></div>
        <p class="text-gray-600 max-w-2xl mx-auto text-lg">Pilih layanan yang Anda butuhkan di bawah ini untuk memulai permohonan baru.</p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
        
        <!-- Service Card 1 -->
        <div class="group bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 border border-gray-100 overflow-hidden transform hover:-translate-y-2">
          <div class="h-2 bg-teal-500 w-0 group-hover:w-full transition-all duration-500"></div>
          <div class="p-8 flex flex-col items-center text-center">
            <div class="w-16 h-16 bg-teal-50 text-teal-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
              </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-teal-700 transition-colors">Pengambilan ATK</h3>
            <p class="text-gray-500 text-sm mb-6">Ajukan permintaan Alat Tulis Kantor dengan mudah untuk kebutuhan tim.</p>
            <div class="mt-auto">
              <span class="text-teal-600 font-semibold text-sm inline-flex items-center gap-1 group-hover:gap-2 transition-all">
                Pelajari Lebih Lanjut
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
              </span>
            </div>
          </div>
        </div>

        <!-- Service Card 2 -->
        <div class="group bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 border border-gray-100 overflow-hidden transform hover:-translate-y-2">
          <div class="h-2 bg-emerald-500 w-0 group-hover:w-full transition-all duration-500"></div>
          <div class="p-8 flex flex-col items-center text-center">
            <div class="w-16 h-16 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
              </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-emerald-700 transition-colors">Peminjaman Ruangan</h3>
            <p class="text-gray-500 text-sm mb-6">Pesan ruangan rapat dan pastikan jadwal diskusi Anda berjalan lancar.</p>
            <div class="mt-auto">
              <span class="text-emerald-600 font-semibold text-sm inline-flex items-center gap-1 group-hover:gap-2 transition-all">
                Pelajari Lebih Lanjut
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7-7m7-7H3" />
                </svg>
              </span>
            </div>
          </div>
        </div>

        <!-- Service Card 3 -->
        <div class="group bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 border border-gray-100 overflow-hidden transform hover:-translate-y-2">
          <div class="h-2 bg-blue-500 w-0 group-hover:w-full transition-all duration-500"></div>
          <div class="p-8 flex flex-col items-center text-center">
            <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
              </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-blue-700 transition-colors">Peminjaman Mobil</h3>
            <p class="text-gray-500 text-sm mb-6">Akses kendaraan operasional untuk mendukung mobilitas kerja Anda.</p>
            <div class="mt-auto">
              <span class="text-blue-600 font-semibold text-sm inline-flex items-center gap-1 group-hover:gap-2 transition-all">
                Pelajari Lebih Lanjut
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7-7m7-7H3" />
                </svg>
              </span>
            </div>
          </div>
        </div>

        <!-- Service Card 4 -->
        <div class="group bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 border border-gray-100 overflow-hidden transform hover:-translate-y-2">
          <div class="h-2 bg-indigo-500 w-0 group-hover:w-full transition-all duration-500"></div>
          <div class="p-8 flex flex-col items-center text-center">
            <div class="w-16 h-16 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
              </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-indigo-700 transition-colors">Alat Cleaning Service</h3>
            <p class="text-gray-500 text-sm mb-6">Ajukan perlengkapan kebersihan untuk menjaga kenyamanan kantor.</p>
            <div class="mt-auto">
              <span class="text-indigo-600 font-semibold text-sm inline-flex items-center gap-1 group-hover:gap-2 transition-all">
                Pelajari Lebih Lanjut
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7-7m7-7H3" />
                </svg>
              </span>
            </div>
          </div>
        </div>

      </div>
    </main>

    <!-- Info Section (Optional) -->
    <section class="bg-white border-t border-gray-100 py-16">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-teal-50 rounded-3xl p-8 md:p-12 flex flex-col md:flex-row items-center justify-between gap-8 border border-teal-100">
          <div class="md:w-2/3">
            <h2 class="text-2xl md:text-3xl font-bold text-teal-900 mb-4">Butuh bantuan menggunakan aplikasi?</h2>
            <p class="text-teal-700 text-lg">Tim IT Support kami siap membantu Anda 24/7 jika mengalami kendala dalam penggunaan portal layanan ini.</p>
          </div>
          <div class="md:w-1/3 flex justify-end">
            <button class="bg-teal-700 hover:bg-teal-800 text-white px-8 py-3 rounded-full font-bold transition-colors w-full md:w-auto shadow-md">
              Hubungi IT Support
            </button>
          </div>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 py-8 border-t border-gray-800">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-4">
        <div class="flex items-center gap-3">
          <img src="/images/logo-simpel-white.png" alt="Logo SIMPEL" class="h-10 w-auto object-contain" />
        </div>
        <p class="text-gray-400 text-sm text-center">
          &copy; {{ new Date().getFullYear() }} SIMPEL - Sistem Informasi Manajemen Pelayanan. All rights reserved.
        </p>
      </div>
    </footer>
  </div>
</template>

<style>
/* Custom animations for background blobs */
@keyframes blob {
  0% { transform: translate(0px, 0px) scale(1); }
  33% { transform: translate(30px, -50px) scale(1.1); }
  66% { transform: translate(-20px, 20px) scale(0.9); }
  100% { transform: translate(0px, 0px) scale(1); }
}
.animate-blob {
  animation: blob 7s infinite;
}
.animation-delay-2000 {
  animation-delay: 2s;
}
.animation-delay-4000 {
  animation-delay: 4s;
}

html {
  scroll-behavior: smooth;
}
</style>