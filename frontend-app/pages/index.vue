<template>
  <div class="min-h-screen flex items-center justify-center bg-slate-100">
    <div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-md border border-slate-200">
      
      <!-- Judul -->
      <div class="text-center mb-8">
        <h1 class="text-3xl font-extrabold text-slate-800">Sistem Barang</h1>
        <p class="text-sm text-slate-500 mt-1">Silakan masuk untuk melanjutkan pengambilan</p>
      </div>

      <!-- Form Login -->
      <form @submit.prevent="handleLogin" class="space-y-5">
        <div>
          <label class="block text-slate-700 text-sm font-semibold mb-2">Username</label>
          <input 
            v-model="form.username" 
            type="text" 
            placeholder="Masukkan username..."
            class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm" 
            required 
          />
        </div>

        <div>
          <label class="block text-slate-700 text-sm font-semibold mb-2">Password</label>
          <input 
            v-model="form.password" 
            type="password" 
            placeholder="••••••••"
            class="w-full px-4 py-3 rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm" 
            required 
          />
        </div>

        <button 
          type="submit" 
          class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg transition duration-200 shadow-md">
          Masuk Sekarang
        </button>
      </form>

      <!-- Informasi Demo Akun -->
      <div class="mt-8 p-4 bg-blue-50 rounded-xl border border-blue-100 text-xs text-blue-800 space-y-1">
        <p class="font-bold mb-1">Akun Simulasi:</p>
        <p>• Admin: <span class="font-mono bg-blue-100 px-1 py-0.5 rounded">admin</span> / <span class="font-mono bg-blue-100 px-1 py-0.5 rounded">123</span></p>
        <p>• User: <span class="font-mono bg-blue-100 px-1 py-0.5 rounded">budi</span> / <span class="font-mono bg-blue-100 px-1 py-0.5 rounded">123</span></p>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const form = ref({
  username: '',
  password: ''
})

const handleLogin = () => {
  // Demo logika sementara sebelum konek ke Laravel backend
  if (form.value.username === 'admin' && form.value.password === '123') {
    alert('Login Berhasil sebagai Admin!')
    // Nanti diarahkan ke /admin/items atau dashboard admin
  } else if (form.value.username === 'budi' && form.value.password === '123') {
    alert('Login Berhasil sebagai User!')
    // Nanti diarahkan ke /user/dashboard
  } else {
    alert('Username atau Password salah!')
  }
}
</script>