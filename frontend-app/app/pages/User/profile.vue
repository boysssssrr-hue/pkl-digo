<template>
  <div class="profile-page min-h-screen font-sans pb-16 relative selection:bg-emerald-500/30 selection:text-emerald-200" :class="[isDark ? 'theme-dark' : 'theme-light']">
    <!-- BACKGROUND MEJA KERJA EKSEKUTIF (TETAP NATURAL, TANPA LAPISAN GELAP/TERANG) -->
    <div class="profile-bg-backdrop" aria-hidden="true"></div>

    <!-- Topbar Navigasi SIMPEL -->
    <UserTopbar />

    <main class="profile-main max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-6 sm:space-y-8 relative z-10">
      <!-- Breadcrumb & Page Title -->
      <div class="header-card p-4 sm:p-6 rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-xl">
        <div>
          <div class="flex items-center gap-2 text-xs text-subtle mb-1 font-medium">
            <NuxtLink to="/User/dashboard" class="hover:text-emerald-400 transition">Beranda</NuxtLink>
            <span>/</span>
            <span class="text-emerald-400">Profil Saya</span>
          </div>
          <h1 class="text-xl sm:text-2xl md:text-3xl font-black banner-title tracking-tight flex items-center gap-2.5">
            <span>Profil Pengguna</span>
            <span class="px-2 py-0.5 rounded-full text-xs font-bold badge-procedure">SIMPEL</span>
          </h1>
          <p class="text-xs sm:text-sm text-subtle mt-1">
            Informasi identitas akun pegawai dan pengelolaan foto profil resmi.
          </p>
        </div>

        <div class="flex items-center gap-2">
          <NuxtLink
            to="/User/dashboard"
            class="btn-back px-4 py-2 sm:px-4 sm:py-2.5 rounded-xl text-xs font-bold transition flex items-center gap-2 shadow-sm"
          >
            <span>←</span>
            <span>Kembali ke Beranda</span>
          </NuxtLink>
        </div>
      </div>

      <!-- Toast Notification -->
      <transition enter-active-class="transition duration-300 ease-out" enter-from-class="transform -translate-y-4 opacity-0" enter-to-class="transform translate-y-0 opacity-100" leave-active-class="transition duration-200 ease-in" leave-from-class="opacity-100" leave-to-class="opacity-0">
        <div v-if="toast.show" class="toast-card fixed top-20 right-4 z-50 max-w-md px-4 py-3.5 rounded-2xl shadow-2xl flex items-center gap-3 border backdrop-blur-md" :class="toast.type === 'success' ? 'toast-success' : 'toast-error'">
          <span class="text-xl">{{ toast.type === 'success' ? '✅' : '⚠️' }}</span>
          <div class="flex-1 text-xs">
            <strong class="block font-bold text-sm">{{ toast.title }}</strong>
            <span>{{ toast.message }}</span>
          </div>
          <button @click="toast.show = false" class="btn-close-toast text-base">✕</button>
        </div>
      </transition>

      <!-- Main Profile Grid -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8">
        
        <!-- LEFT COLUMN: Foto Profile (DAPAT DIUBAH-UBAH) -->
        <div class="lg:col-span-5 space-y-6">
          <div class="avatar-card rounded-3xl p-6 shadow-xl relative overflow-hidden backdrop-blur-sm">
            <div class="flex items-center justify-between mb-5">
              <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                <h2 class="text-base font-bold banner-title tracking-tight">Foto Profil</h2>
              </div>
              <span class="badge-procedure px-2.5 py-1 rounded-full text-[11px] font-bold flex items-center gap-1">
                <span>✏️</span> Dapat Diubah
              </span>
            </div>

            <!-- Avatar Preview & Camera Button -->
            <div class="flex flex-col items-center text-center">
              <div class="relative group">
                <div class="avatar-frame w-36 h-36 sm:w-40 sm:h-40 rounded-3xl overflow-hidden border-4 shadow-2xl flex items-center justify-center transition-all duration-300 relative">
                  <img
                    v-if="previewAvatar"
                    :src="previewAvatar"
                    :alt="currentUser.name"
                    class="w-full h-full object-cover"
                    @error="handleAvatarError"
                  />
                  <div
                    v-else
                    class="w-full h-full bg-gradient-to-br from-emerald-600 to-teal-700 flex items-center justify-center text-white text-5xl font-black shadow-inner"
                  >
                    {{ userAvatarLetter }}
                  </div>

                  <!-- Overlay on hover -->
                  <div
                    @click="triggerFileInput"
                    class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center text-white cursor-pointer backdrop-blur-[2px]"
                    title="Klik untuk memilih foto baru"
                  >
                    <svg viewBox="0 0 24 24" class="w-8 h-8 mb-1 text-emerald-400" fill="currentColor">
                      <path d="M4 4h3l2-2h6l2 2h3a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2zm8 3a5 5 0 1 0 0 10 5 5 0 0 0 0-10zm0 2a3 3 0 1 1 0 6 3 3 0 0 1 0-6z"/>
                    </svg>
                    <span class="text-xs font-bold">Pilih Foto</span>
                  </div>
                </div>

                <!-- Floating Camera Action Button -->
                <button
                  type="button"
                  @click="triggerFileInput"
                  class="absolute -bottom-2 -right-2 w-11 h-11 rounded-2xl bg-gradient-to-tr from-emerald-500 to-teal-500 hover:from-emerald-400 hover:to-teal-400 text-slate-950 font-black shadow-lg shadow-emerald-500/30 flex items-center justify-center transition active:scale-95 border-2 border-emerald-500"
                  title="Unggah Foto dari Perangkat"
                >
                  <svg viewBox="0 0 24 24" class="w-5 h-5 text-slate-950" fill="currentColor">
                    <path d="M4 4h3l2-2h6l2 2h3a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2zm8 3a5 5 0 1 0 0 10 5 5 0 0 0 0-10zm0 2a3 3 0 1 1 0 6 3 3 0 0 1 0-6z"/>
                  </svg>
                </button>
              </div>

              <!-- Hidden File Input -->
              <input
                ref="fileInputRef"
                type="file"
                accept="image/png,image/jpeg,image/jpg,image/webp,image/svg+xml"
                class="hidden"
                @change="onFileSelected"
              />

              <div class="mt-5">
                <h3 class="text-lg font-black banner-title leading-snug">{{ currentUser.name || 'Pegawai' }}</h3>
                <p class="text-xs text-subtle font-mono mt-0.5">NIP: {{ currentUser.nip || '-' }}</p>
                <div class="flex items-center justify-center gap-1.5 mt-2">
                  <span class="badge-procedure px-2.5 py-0.5 rounded-full text-[10px] font-bold">
                    {{ currentUser.division?.name || 'Divisi Pegawai' }}
                  </span>
                  <span class="badge-role px-2 py-0.5 rounded-full text-[10px] font-bold border">
                    {{ currentUser.role === 'admin' ? 'Administrator' : 'Pegawai Aktif' }}
                  </span>
                </div>
              </div>
            </div>

            <!-- Upload Controls & Form -->
            <div class="card-divider mt-6 pt-5 border-t space-y-4">
              <!-- Upload Button trigger -->
              <div>
                <button
                  type="button"
                  @click="triggerFileInput"
                  class="btn-upload w-full py-2.5 px-4 rounded-xl text-xs font-bold transition flex items-center justify-center gap-2"
                >
                  <svg viewBox="0 0 24 24" class="w-4 h-4 text-emerald-400" fill="currentColor">
                    <path d="M19.35 10.04C18.67 6.59 15.64 4 12 4 9.11 4 6.6 5.64 5.35 8.04 2.34 8.36 0 10.91 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96zM14 13v4h-4v-4H7l5-5 5 5h-3z"/>
                  </svg>
                  <span>Pilih Berkas Foto (JPG, PNG, WebP)</span>
                </button>
                <p class="text-[10px] text-subtle text-center mt-1.5">
                  Format gambar: PNG, JPG, WEBP • Ukuran maksimal 10MB
                </p>
              </div>

              <!-- Preset Avatar Selection -->
              <div class="pt-2">
                <label class="block text-[11px] font-bold text-subtle uppercase tracking-wider mb-2">
                  Atau Pilih Avatar Favorit:
                </label>
                <div class="grid grid-cols-4 gap-2">
                  <button
                    v-for="(av, idx) in presetAvatars"
                    :key="idx"
                    type="button"
                    @click="selectPresetAvatar(av)"
                    class="btn-preset-avatar p-1 rounded-xl border transition-all duration-200 hover:scale-105 flex flex-col items-center"
                    :class="previewAvatar === av.url ? 'border-emerald-500 bg-emerald-500/15 shadow-md shadow-emerald-500/20' : ''"
                    :title="av.name"
                  >
                    <img :src="av.url" :alt="av.name" class="w-11 h-11 rounded-lg object-cover" />
                    <span class="text-[9px] font-medium text-subtle mt-1 truncate w-full text-center">{{ av.name }}</span>
                  </button>
                </div>
              </div>

              <!-- Custom URL Input Option -->
              <div class="pt-2">
                <label class="block text-[11px] font-bold text-subtle uppercase tracking-wider mb-1.5">
                  Atau Masukkan Tautan / URL Foto:
                </label>
                <div class="flex items-center gap-2">
                  <input
                    v-model="customUrlInput"
                    type="url"
                    placeholder="https://example.com/foto.jpg"
                    class="form-input flex-1 rounded-xl px-3 py-2 text-xs focus:outline-none focus:border-emerald-500 transition"
                  />
                  <button
                    type="button"
                    @click="applyCustomUrl"
                    class="btn-apply-url px-3 py-2 rounded-xl text-xs font-bold transition"
                  >
                    Pakai
                  </button>
                </div>
              </div>

              <!-- Action Buttons: Save & Reset -->
              <div class="card-divider pt-3 border-t flex items-center gap-3">
                <button
                  type="button"
                  @click="saveProfilePhoto"
                  :disabled="isSaving || !hasPhotoChanged"
                  class="flex-1 py-3 px-4 rounded-xl font-bold text-xs flex items-center justify-center gap-2 transition-all shadow-lg"
                  :class="hasPhotoChanged
                    ? 'btn-save-active text-slate-950 shadow-emerald-500/25 active:scale-[0.98]'
                    : 'btn-save-disabled cursor-not-allowed'"
                >
                  <span v-if="isSaving" class="animate-spin text-sm">⏳</span>
                  <span v-else class="text-sm">💾</span>
                  <span>{{ isSaving ? 'Menyimpan Foto...' : 'Simpan Foto Profil' }}</span>
                </button>

                <button
                  v-if="previewAvatar"
                  type="button"
                  @click="removeAvatar"
                  :disabled="isSaving"
                  class="px-3 py-3 rounded-xl bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/20 text-xs font-bold transition flex items-center gap-1.5"
                  title="Hapus foto profil dan gunakan inisial"
                >
                  <span>🗑️</span>
                  <span class="hidden sm:inline">Hapus</span>
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- RIGHT COLUMN: INFORMASI DATA PATEN (Nama, NIP, Password, Divisi, Role) -->
        <div class="lg:col-span-7 space-y-6">
          <div class="credentials-card rounded-3xl p-6 sm:p-7 shadow-xl relative backdrop-blur-sm space-y-6">
            
            <!-- Section Header -->
            <div class="card-divider flex items-center justify-between pb-4 border-b">
              <div>
                <div class="flex items-center gap-2">
                  <span class="text-lg">{{ isAdmin ? '👑' : '🔒' }}</span>
                  <h2 class="text-lg font-bold banner-title tracking-tight">Kredensial &amp; Data Kepegawaian</h2>
                </div>
                <p class="text-xs text-subtle mt-0.5">
                  <span v-if="isAdmin">Sebagai Administrator, Anda memiliki wewenang penuh untuk merubah seluruh data.</span>
                  <span v-else>Seluruh bagian data di bawah ini berstatus paten dan dikelola oleh Administrator SIMPEL.</span>
                </p>
              </div>
              <span v-if="isAdmin" class="badge-procedure px-2.5 py-1 rounded-full text-[11px] font-black flex items-center gap-1">
                <span>👑</span> AKSES ADMIN
              </span>
              <span v-else class="px-2.5 py-1 rounded-full text-[11px] font-black bg-amber-500/15 text-amber-300 border border-amber-500/30 flex items-center gap-1">
                <span>🔒</span> DATA PATEN
              </span>
            </div>

            <!-- Notice Banner: Admin vs Normal User -->
            <div v-if="isAdmin" class="notice-admin p-4 rounded-2xl flex items-start justify-between gap-3">
              <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-xl bg-emerald-500/20 border border-emerald-500/40 text-emerald-400 flex items-center justify-center text-base shrink-0 mt-0.5">
                  👑
                </div>
                <div class="text-xs space-y-1">
                  <strong class="font-bold text-emerald-400 block text-xs sm:text-sm">Hak Istimewa Administrator</strong>
                  <p class="text-subtle leading-relaxed">
                    Anda login sebagai <strong>Administrator</strong>. Anda dapat merubah <strong>semua data</strong>: Foto Profil, Nama Lengkap, NIP, dan Kata Sandi.
                  </p>
                </div>
              </div>
              <NuxtLink to="/admin/profil" class="shrink-0 px-3 py-1.5 rounded-xl bg-emerald-500/20 hover:bg-emerald-500/30 text-emerald-400 border border-emerald-500/40 text-xs font-bold transition flex items-center gap-1">
                <span>Panel Admin</span>
                <span>→</span>
              </NuxtLink>
            </div>

            <div v-else class="notice-user p-4 rounded-2xl flex items-start gap-3.5">
              <div class="w-8 h-8 rounded-xl bg-amber-500/20 border border-amber-500/30 text-amber-400 flex items-center justify-center text-base shrink-0 mt-0.5">
                🛡️
              </div>
              <div class="text-xs space-y-1">
                <strong class="font-bold text-amber-400 block text-xs sm:text-sm">Kebijakan Keamanan Akun SIMPEL</strong>
                <p class="text-subtle leading-relaxed">
                  Demi kepatuhan data dan akuntabilitas sistem pelayanan, informasi <strong>Nama</strong>, <strong>NIP</strong>, dan <strong>Password</strong> bersifat <em>paten</em> (tidak dapat diedit langsung oleh pegawai). Apabila terdapat kekeliruan data, silakan berkoordinasi dengan Administrator Layanan.
                </p>
              </div>
            </div>

            <!-- Form Fields (Editable for Admin, Locked/Paten for Non-Admin) -->
            <div class="space-y-4">
              
              <!-- 1. NAMA LENGKAP -->
              <div class="space-y-1.5">
                <div class="flex items-center justify-between">
                  <label class="text-xs font-bold banner-title flex items-center gap-1.5">
                    <span>Nama Lengkap Pegawai</span>
                    <span v-if="isAdmin" class="text-[10px] text-emerald-400 font-semibold">(Bisa Diubah ✏️)</span>
                    <span v-else class="text-[10px] text-amber-400 font-normal flex items-center gap-0.5">(Paten 🔒)</span>
                  </label>
                  <span v-if="isAdmin" class="badge-procedure text-[10px] font-bold px-2 py-0.5 rounded">
                    Bisa Diedit
                  </span>
                  <span v-else class="badge-role text-[10px] font-bold px-2 py-0.5 rounded border">
                    Hanya-Baca
                  </span>
                </div>
                <div class="relative">
                  <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-subtle">
                    <svg viewBox="0 0 24 24" class="w-4 h-4" fill="currentColor">
                      <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                    </svg>
                  </div>
                  <!-- Admin Input -->
                  <input
                    v-if="isAdmin"
                    v-model="adminEditForm.name"
                    type="text"
                    required
                    placeholder="Masukkan nama lengkap"
                    class="form-input w-full pl-10 pr-4 py-3 rounded-xl text-xs sm:text-sm font-semibold focus:outline-none focus:border-emerald-500 shadow-inner"
                  />
                  <!-- Non-Admin Paten Input -->
                  <input
                    v-else
                    type="text"
                    :value="currentUser.name || 'Pegawai SIMPEL'"
                    readonly
                    disabled
                    class="input-locked w-full pl-10 pr-10 py-3 rounded-xl text-xs sm:text-sm font-medium cursor-not-allowed select-none shadow-inner"
                    title="Nama pengguna bersifat paten dan tidak dapat diedit"
                  />
                  <div v-if="!isAdmin" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-subtle" title="Terkunci secara sistem">
                    🔒
                  </div>
                </div>
                <p class="text-[11px] text-subtle pl-1">
                  Nama resmi yang terdaftar pada SK dan basis data kepegawaian.
                </p>
              </div>

              <!-- 2. NOMOR INDUK PEGAWAI (NIP) -->
              <div class="space-y-1.5">
                <div class="flex items-center justify-between">
                  <label class="text-xs font-bold banner-title flex items-center gap-1.5">
                    <span>Nomor Induk Pegawai (NIP) / Username Login</span>
                    <span v-if="isAdmin" class="text-[10px] text-emerald-400 font-semibold">(Bisa Diubah ✏️)</span>
                    <span v-else class="text-[10px] text-amber-400 font-normal flex items-center gap-0.5">(Paten 🔒)</span>
                  </label>
                  <span v-if="isAdmin" class="badge-procedure text-[10px] font-bold px-2 py-0.5 rounded">
                    Bisa Diedit
                  </span>
                  <span v-else class="badge-role text-[10px] font-bold px-2 py-0.5 rounded border">
                    Hanya-Baca
                  </span>
                </div>
                <div class="relative">
                  <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-subtle">
                    <svg viewBox="0 0 24 24" class="w-4 h-4" fill="currentColor">
                      <path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm2 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
                    </svg>
                  </div>
                  <!-- Admin Input -->
                  <input
                    v-if="isAdmin"
                    v-model="adminEditForm.nip"
                    type="text"
                    required
                    placeholder="Masukkan NIP"
                    class="form-input w-full pl-10 pr-4 py-3 rounded-xl text-emerald-400 font-mono text-xs sm:text-sm font-bold focus:outline-none focus:border-emerald-500 shadow-inner"
                  />
                  <!-- Non-Admin Paten Input -->
                  <input
                    v-else
                    type="text"
                    :value="currentUser.nip || '-'"
                    readonly
                    disabled
                    class="input-locked w-full pl-10 pr-10 py-3 rounded-xl text-emerald-400 font-mono text-xs sm:text-sm font-bold cursor-not-allowed select-none shadow-inner"
                    title="NIP bersifat paten dan tidak dapat diedit"
                  />
                  <div v-if="!isAdmin" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-subtle" title="Terkunci secara sistem">
                    🔒
                  </div>
                </div>
                <p class="text-[11px] text-subtle pl-1">
                  Digunakan sebagai nomor identitas unik saat login ke portal SIMPEL.
                </p>
              </div>

              <!-- 3. KATA SANDI (PASSWORD) -->
              <div class="space-y-1.5">
                <div class="flex items-center justify-between">
                  <label class="text-xs font-bold banner-title flex items-center gap-1.5">
                    <span>Kata Sandi (Password Akun)</span>
                    <span v-if="isAdmin" class="text-[10px] text-emerald-400 font-semibold">(Bisa Diubah ✏️)</span>
                    <span v-else class="text-[10px] text-amber-400 font-normal flex items-center gap-0.5">(Paten 🔒)</span>
                  </label>
                  <span v-if="isAdmin" class="badge-procedure text-[10px] font-bold px-2 py-0.5 rounded">
                    Bisa Diedit
                  </span>
                  <span v-else class="badge-role text-[10px] font-bold px-2 py-0.5 rounded border">
                    Hanya-Baca
                  </span>
                </div>
                <div class="relative">
                  <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-subtle">
                    <svg viewBox="0 0 24 24" class="w-4 h-4" fill="currentColor">
                      <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/>
                    </svg>
                  </div>
                  <!-- Admin Input -->
                  <input
                    v-if="isAdmin"
                    v-model="adminEditForm.password"
                    :type="showPassword ? 'text' : 'password'"
                    required
                    minlength="4"
                    placeholder="Masukkan kata sandi baru"
                    class="form-input w-full pl-10 pr-24 py-3 rounded-xl font-mono text-xs sm:text-sm font-semibold focus:outline-none focus:border-emerald-500 shadow-inner"
                  />
                  <!-- Non-Admin Paten Input -->
                  <input
                    v-else
                    :type="showPassword ? 'text' : 'password'"
                    :value="currentUser.visible_password || '12345678'"
                    readonly
                    disabled
                    class="input-locked w-full pl-10 pr-24 py-3 rounded-xl font-mono text-xs sm:text-sm font-semibold cursor-not-allowed select-none shadow-inner"
                    title="Password bersifat paten dan dikelola oleh Administrator"
                  />
                  <!-- Toggle Visibility & Lock Icon -->
                  <div class="absolute inset-y-0 right-0 pr-3 flex items-center gap-2">
                    <button
                      type="button"
                      @click="showPassword = !showPassword"
                      class="btn-toggle-pwd px-2 py-1 rounded text-[11px] font-bold transition flex items-center gap-1"
                      title="Lihat / Sembunyikan Password"
                    >
                      <span>{{ showPassword ? '🙈 Tutup' : '👁️ Lihat' }}</span>
                    </button>
                    <span v-if="!isAdmin" class="text-subtle" title="Terkunci secara sistem">🔒</span>
                  </div>
                </div>
                <p class="text-[11px] text-subtle pl-1">
                  <span v-if="isAdmin">Ketikkan kata sandi baru untuk langsung memperbarui akun Admin ini.</span>
                  <span v-else>Kata sandi resmi akun. Hubungi Administrator jika membutuhkan reset kata sandi darurat.</span>
                </p>
              </div>

              <!-- Admin Full Update Button -->
              <div v-if="isAdmin" class="pt-2">
                <button
                  type="button"
                  @click="saveAdminFullProfile"
                  :disabled="isSavingAdmin"
                  class="btn-save-active w-full py-3 px-4 rounded-xl text-slate-950 font-black text-xs flex items-center justify-center gap-2 shadow-lg shadow-emerald-500/20 active:scale-[0.98] transition"
                >
                  <span v-if="isSavingAdmin" class="animate-spin text-sm">⏳</span>
                  <span v-else class="text-sm">💾</span>
                  <span>{{ isSavingAdmin ? 'Menyimpan Seluruh Perubahan...' : 'Simpan Semua Data (Nama, NIP, Password)' }}</span>
                </button>
              </div>

              <!-- 4. INFORMASI TAMBAHAN (DIVISI & PERAN) -->
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                <div class="detail-box p-4 rounded-2xl space-y-1">
                  <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-subtle uppercase tracking-wider">Unit / Divisi</span>
                    <span class="text-xs">{{ isAdmin ? '🏢' : '🔒' }}</span>
                  </div>
                  <div class="text-sm font-bold banner-title">
                    {{ currentUser.division?.name || (isAdmin ? 'Pusat Administrator' : 'Divisi Kepesertaan') }}
                  </div>
                  <p class="text-[10px] text-subtle">{{ isAdmin ? 'Wewenang sistem penuh' : 'Kuota ATK disesuaikan per divisi' }}</p>
                </div>

                <div class="detail-box p-4 rounded-2xl space-y-1">
                  <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold text-subtle uppercase tracking-wider">Hak Akses (Role)</span>
                    <span class="text-xs">{{ isAdmin ? '👑' : '🔒' }}</span>
                  </div>
                  <div class="text-sm font-bold text-emerald-400 uppercase">
                    {{ currentUser.role || 'User' }}
                  </div>
                  <p class="text-[10px] text-subtle">{{ isAdmin ? 'Bebas mengubah seluruh konfigurasi' : 'Akses peminjaman operasional' }}</p>
                </div>
              </div>

            </div>

            <!-- Footer Note -->
            <div class="card-divider pt-4 border-t flex items-center justify-between text-xs text-subtle">
              <span>SIMPEL v2.0 • Data Tersinkronisasi Otomatis</span>
              <span class="flex items-center gap-1.5 text-emerald-400">
                <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                Server Terhubung
              </span>
            </div>

          </div>
        </div>

      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue'

useHead({
  title: 'Profil Pengguna - Portal DigO'
})

// Sinkronisasi status tema dark/light terpadu
const isDark = useState('user_theme_dark', () => true)

const config = useRuntimeConfig()
const apiBase = config.public?.apiBase || 'http://127.0.0.1:8000/api'

// Cookies
const userCookie = useCookie('auth_user')
const tokenCookie = useCookie('auth_token')

// User Data State
const currentUser = ref({
  id: null,
  name: '',
  nip: '',
  visible_password: '',
  avatar: null,
  division_id: null,
  division: null,
  role: 'user',
  status: 'active'
})

// Admin Privilege State & Form
const isAdmin = computed(() => currentUser.value?.role === 'admin')
const adminEditForm = reactive({
  name: '',
  nip: '',
  password: ''
})
const isSavingAdmin = ref(false)
const originNip = ref('')

// Photo Management State
const previewAvatar = ref(null)
const selectedFile = ref(null)
const customUrlInput = ref('')
const isSaving = ref(false)
const showPassword = ref(false)
const fileInputRef = ref(null)

// Toast Alert State
const toast = ref({
  show: false,
  title: '',
  message: '',
  type: 'success'
})

const showToast = (title, message, type = 'success') => {
  toast.value = { show: true, title, message, type }
  setTimeout(() => {
    toast.value.show = false
  }, 4000)
}

// Initials Letter for Avatar fallback
const userAvatarLetter = computed(() => {
  return (currentUser.value?.name || 'P').charAt(0).toUpperCase()
})

// Check if photo has changed compared to saved avatar
const hasPhotoChanged = computed(() => {
  return selectedFile.value !== null || previewAvatar.value !== currentUser.value.avatar
})

// Preset Modern Avatars
const presetAvatars = [
  { name: 'Pegawai Pria 1', url: 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=150&auto=format&fit=crop&q=80' },
  { name: 'Pegawai Wanita 1', url: 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=150&auto=format&fit=crop&q=80' },
  { name: 'Pegawai Pria 2', url: 'https://images.unsplash.com/photo-1570295999919-56ceb5ecca61?w=150&auto=format&fit=crop&q=80' },
  { name: 'Pegawai Wanita 2', url: 'https://images.unsplash.com/photo-1580489944761-15a19d654956?w=150&auto=format&fit=crop&q=80' }
]

// Trigger Native File Input
const triggerFileInput = () => {
  if (fileInputRef.value) {
    fileInputRef.value.click()
  }
}

// Handle File Selection
const onFileSelected = (event) => {
  const file = event.target.files?.[0]
  if (!file) return

  // Validate File Size (max 10MB)
  if (file.size > 10 * 1024 * 1024) {
    showToast('Ukuran File Terlalu Besar', 'Maksimal ukuran foto adalah 10MB.', 'error')
    return
  }

  // Validate File Type
  const allowed = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp', 'image/svg+xml']
  if (!allowed.includes(file.type)) {
    showToast('Format Tidak Didukung', 'Silakan pilih gambar dengan format JPG, PNG, atau WebP.', 'error')
    return
  }

  selectedFile.value = file

  // Create Preview via FileReader
  const reader = new FileReader()
  reader.onload = (e) => {
    previewAvatar.value = e.target.result
    showToast('Foto Dipilih', 'Foto siap disimpan. Klik tombol "Simpan Foto Profil" di bawah.', 'success')
  }
  reader.readAsDataURL(file)
}

// Handle Preset Selection
const selectPresetAvatar = (preset) => {
  selectedFile.value = null
  previewAvatar.value = preset.url
  showToast('Avatar Dipilih', `Avatar ${preset.name} dipilih. Jangan lupa simpan perubahan.`, 'success')
}

// Apply Custom URL
const applyCustomUrl = () => {
  if (!customUrlInput.value.trim()) {
    showToast('URL Kosong', 'Silakan ketikkan URL foto yang valid.', 'error')
    return
  }
  selectedFile.value = null
  previewAvatar.value = customUrlInput.value.trim()
  customUrlInput.value = ''
  showToast('Tautan Diterapkan', 'Foto siap disimpan.', 'success')
}

// Remove Avatar (reset to null)
const removeAvatar = () => {
  selectedFile.value = null
  previewAvatar.value = null
  showToast('Foto Dihapus', 'Foto profil direset. Klik "Simpan Foto Profil" untuk konfirmasi perubahan ke database.', 'success')
}

// Error Fallback for Avatar Image
const handleAvatarError = (e) => {
  console.warn('Gagal memuat gambar avatar:', e)
  previewAvatar.value = null
}

// Save Profile Photo to Backend Database & Sync Storage
const saveProfilePhoto = async () => {
  if (!currentUser.value.id && !currentUser.value.nip) {
    showToast('Sesi Habis', 'Silakan login ulang untuk memperbarui profil.', 'error')
    return
  }

  isSaving.value = true

  try {
    let responseData = null

    // Skenario 1: Jika mengunggah berkas gambar langsung dari perangkat
    if (selectedFile.value) {
      const formData = new FormData()
      formData.append('image', selectedFile.value)
      formData.append('category', 'avatars')
      if (currentUser.value.id) formData.append('user_id', currentUser.value.id)
      if (currentUser.value.nip) formData.append('nip', currentUser.value.nip)

      const res = await fetch(`${apiBase}/profile/photo`, {
        method: 'POST',
        headers: {
          ...(tokenCookie.value ? { 'Authorization': `Bearer ${tokenCookie.value}` } : {}),
          'Accept': 'application/json'
        },
        body: formData
      })

      if (!res.ok) {
        const err = await res.json().catch(() => ({}))
        throw new Error(err.message || 'Gagal mengunggah foto profil')
      }

      responseData = await res.json()
    } else {
      // Skenario 2: Menggunakan tautan URL atau preset atau null
      const res = await fetch(`${apiBase}/profile/photo`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          ...(tokenCookie.value ? { 'Authorization': `Bearer ${tokenCookie.value}` } : {})
        },
        body: JSON.stringify({
          user_id: currentUser.value.id,
          nip: currentUser.value.nip,
          avatar: previewAvatar.value || ''
        })
      })

      if (!res.ok) {
        const err = await res.json().catch(() => ({}))
        throw new Error(err.message || 'Gagal memperbarui foto profil')
      }

      responseData = await res.json()
    }

    // Update state profil saat ini
    const updatedUser = responseData.user || {
      ...currentUser.value,
      avatar: responseData.avatar || previewAvatar.value
    }

    currentUser.value = updatedUser
    previewAvatar.value = updatedUser.avatar
    selectedFile.value = null

    // Sinkronisasi ke Cookie & LocalStorage agar Topbar dan semua halaman langsung terupdate
    userCookie.value = JSON.stringify(updatedUser)
    if (typeof window !== 'undefined') {
      localStorage.setItem('auth_user', JSON.stringify(updatedUser))
      // Trigger storage event for reactive sync across tabs/components
      window.dispatchEvent(new Event('storage'))
    }

    showToast('Berhasil Disimpan!', 'Foto profil Anda berhasil diperbarui di database SIMPEL.', 'success')
  } catch (err) {
    console.error('Error saving profile photo:', err)
    showToast('Gagal Menyimpan', err.message || 'Terjadi kesalahan saat menyimpan foto.', 'error')
  } finally {
    isSaving.value = false
  }
}

// Save Full Admin Profile (Nama, NIP, Password)
const saveAdminFullProfile = async () => {
  if (!adminEditForm.name.trim() || !adminEditForm.nip.trim()) {
    showToast('Validasi Gagal', 'Nama dan NIP wajib diisi.', 'error')
    return
  }

  isSavingAdmin.value = true

  try {
    const res = await fetch(`${apiBase}/profile/update`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        ...(tokenCookie.value ? { 'Authorization': `Bearer ${tokenCookie.value}` } : {})
      },
      body: JSON.stringify({
        user_id: currentUser.value.id,
        nip_origin: originNip.value || currentUser.value.nip,
        name: adminEditForm.name.trim(),
        nip: adminEditForm.nip.trim(),
        password: adminEditForm.password,
        avatar: previewAvatar.value || currentUser.value.avatar || ''
      })
    })

    if (!res.ok) {
      const err = await res.json().catch(() => ({}))
      throw new Error(err.message || 'Gagal menyimpan perubahan profil admin.')
    }

    const resData = await res.json()
    const updated = resData.user || {
      ...currentUser.value,
      name: adminEditForm.name.trim(),
      nip: adminEditForm.nip.trim(),
      visible_password: adminEditForm.password
    }

    currentUser.value = updated
    originNip.value = updated.nip

    // Sync to cookie and localStorage
    userCookie.value = JSON.stringify(updated)
    if (typeof window !== 'undefined') {
      localStorage.setItem('auth_user', JSON.stringify(updated))
      window.dispatchEvent(new Event('storage'))
    }

    showToast('Berhasil Diperbarui!', 'Semua data kredensial Admin (Nama, NIP, Password) berhasil disimpan.', 'success')
  } catch (err) {
    console.error('Error updating admin profile:', err)
    showToast('Gagal Menyimpan', err.message || 'Terjadi kesalahan saat menyimpan data.', 'error')
  } finally {
    isSavingAdmin.value = false
  }
}

const syncAdminEditForm = () => {
  if (currentUser.value?.name) adminEditForm.name = currentUser.value.name
  if (currentUser.value?.nip) {
    adminEditForm.nip = currentUser.value.nip
    if (!originNip.value) originNip.value = currentUser.value.nip
  }
  if (currentUser.value?.visible_password) adminEditForm.password = currentUser.value.visible_password
}

// Load Current User Data on Mount
onMounted(async () => {
  if (import.meta.client) {
    const savedTheme = localStorage.getItem('user_theme_mode')
    if (savedTheme) {
      isDark.value = savedTheme === 'dark'
    }
  }

  // 1. Ambil dari localStorage
  if (typeof window !== 'undefined') {
    const raw = localStorage.getItem('auth_user')
    if (raw) {
      try {
        const u = JSON.parse(raw)
        if (u && (u.name || u.nip)) {
          currentUser.value = { ...currentUser.value, ...u }
          previewAvatar.value = u.avatar || null
          syncAdminEditForm()
        }
      } catch (e) {
        console.warn('Failed parsing local auth_user', e)
      }
    }
  }

  // 2. Ambil dari Cookie jika belum ada
  if (!currentUser.value.nip && userCookie.value) {
    try {
      const u = typeof userCookie.value === 'string' ? JSON.parse(userCookie.value) : userCookie.value
      if (u && (u.name || u.nip)) {
        currentUser.value = { ...currentUser.value, ...u }
        previewAvatar.value = u.avatar || null
        syncAdminEditForm()
      }
    } catch (e) {
      console.warn('Failed parsing cookie auth_user', e)
    }
  }

  // 3. Tarik data teranyar dari server API /me atau /users
  try {
    const token = tokenCookie.value
    if (token) {
      const res = await fetch(`${apiBase}/me`, {
        headers: {
          'Authorization': `Bearer ${token}`,
          'Accept': 'application/json'
        }
      })
      if (res.ok) {
        const freshUser = await res.json()
        if (freshUser?.nip) {
          currentUser.value = { ...currentUser.value, ...freshUser }
          previewAvatar.value = freshUser.avatar || null
          syncAdminEditForm()
          // Simpan sinkronisasi terbaru
          userCookie.value = JSON.stringify(currentUser.value)
          if (typeof window !== 'undefined') {
            localStorage.setItem('auth_user', JSON.stringify(currentUser.value))
          }
        }
      }
    }
  } catch (e) {
    console.warn('Could not refresh from /api/me:', e)
  }
})
</script>

<style scoped>
/* ========================================================================== */
/* BACKGROUND RUANG KERJA EKSEKUTIF (TETAP NATURAL, TANPA LAPISAN GELAP/TERANG) */
/* ========================================================================== */
.profile-page {
  background-color: transparent !important;
}

.profile-bg-backdrop {
  position: fixed;
  inset: 0;
  background-image: url('/images/profile-bg.jpg');
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
  --locked-bg: rgba(0, 0, 0, 0.6);
  --locked-border: rgba(16, 185, 129, 0.3);
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
  --locked-bg: rgba(241, 245, 249, 0.7);
  --locked-border: rgba(203, 213, 225, 0.8);
}

/* COMMON GLASSMORPHISM BOX STYLES */
.header-card,
.avatar-card,
.credentials-card {
  background-color: var(--box-bg);
  border: 1px solid var(--box-border);
  box-shadow: var(--box-shadow);
  backdrop-filter: blur(16px);
  -webkit-backdrop-filter: blur(16px);
  color: var(--text-body);
  transition: all 0.25s ease;
  min-width: 0;
  box-sizing: border-box;
  overflow: hidden;
}

/* TEXT COLOR OVERRIDES */
.banner-title {
  color: var(--text-main);
}

.text-subtle {
  color: var(--text-sub);
}

.card-divider {
  border-color: var(--divider);
}

.badge-procedure {
  background-color: rgba(16, 185, 129, 0.15);
  color: #10b981;
  border: 1px solid rgba(16, 185, 129, 0.3);
}

.badge-role {
  background-color: var(--inner-bg);
  color: var(--text-sub);
  border-color: var(--inner-border);
}

.avatar-frame {
  background-color: var(--inner-bg);
  border-color: var(--inner-border);
}

.btn-back,
.btn-upload,
.btn-apply-url,
.btn-toggle-pwd {
  background-color: var(--inner-bg);
  border: 1px solid var(--inner-border);
  color: var(--text-sub);
}

.btn-back:hover,
.btn-upload:hover,
.btn-apply-url:hover,
.btn-toggle-pwd:hover {
  color: var(--text-main);
  border-color: #10b981;
}

.btn-preset-avatar {
  background-color: var(--inner-bg);
  border-color: var(--inner-border);
}

.form-input {
  background-color: var(--input-bg);
  border: 1px solid var(--input-border);
  color: var(--text-main);
}

.input-locked {
  background-color: var(--locked-bg);
  border: 1px solid var(--locked-border);
  color: var(--text-main);
}

.btn-save-active {
  background: linear-gradient(135deg, #10b981 0%, #0d9488 100%);
  color: #ffffff;
}

.btn-save-active:hover {
  background: linear-gradient(135deg, #059669 0%, #0f766e 100%);
}

.btn-save-disabled {
  background-color: var(--inner-bg);
  color: var(--text-sub);
  border: 1px solid var(--inner-border);
}

.notice-admin {
  background-color: rgba(16, 185, 129, 0.12);
  border: 1px solid rgba(16, 185, 129, 0.35);
}

.notice-user {
  background-color: rgba(245, 158, 11, 0.12);
  border: 1px solid rgba(245, 158, 11, 0.35);
}

.detail-box {
  background-color: var(--inner-bg);
  border: 1px solid var(--inner-border);
}

/* TOAST */
.toast-card {
  backdrop-filter: blur(14px);
  -webkit-backdrop-filter: blur(14px);
}

.toast-success {
  background-color: var(--box-bg);
  border-color: rgba(16, 185, 129, 0.5);
  color: #10b981;
}

.toast-error {
  background-color: var(--box-bg);
  border-color: rgba(239, 68, 68, 0.5);
  color: #ef4444;
}

.btn-close-toast {
  color: var(--text-sub);
}

.btn-close-toast:hover {
  color: var(--text-main);
}

input:disabled {
  -webkit-text-fill-color: currentColor;
}
</style>
