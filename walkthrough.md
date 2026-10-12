# Walkthrough - Implementasi Profil Pengguna & Profil Administrator (SIMPEL)

Sistem profil aplikasi SIMPEL telah selesai dibuat dan disesuaikan secara menyeluruh dengan pembagian hak akses:
1. **Pengguna Biasa (Pegawai)**: Hanya dapat mengubah/mengunggah **Foto Profil**, sedangkan Nama, NIP, dan Password bersifat **Paten (Terkunci / Read-Only)**.
2. **Administrator**: Memiliki hak istimewa penuh dan **dapat mengubah semuanya** (Foto Profil, Nama Lengkap, NIP/Username, dan Kata Sandi).

---

## 1. Perubahan Backend & API Database

1. **Endpoint Pembaruan Profil Lengkap (`POST /api/profile/update`)**:
   - Berkas: [ApiController.php](file:///c:/xampp/htdocs/pkl-digo/backend-api/app/Http/Controllers/ApiController.php)
   - Method: `updateProfile(Request $request)`
   - Menerima pembaruan serentak:
     - `name`: Nama lengkap pengguna / administrator.
     - `nip`: NIP / username login (dengan validasi keunikan).
     - `password`: Kata sandi baru (otomatis di-hash dan disinkronkan ke kolom `visible_password`).
     - `image` / `avatar`: Berkas foto profil baru atau URL avatar.
   - Mengembalikan data pengguna terbaru beserta relasi divisi.

2. **Rute API (`routes/api.php`)**:
   - Mendaftarkan rute `POST /api/profile/update` dan `POST /api/profile/photo`.

---

## 2. Halaman Profil Administrator (`/admin/profil`)

1. **Berkas Halaman**:
   - [admin/profil.vue](file:///c:/xampp/htdocs/pkl-digo/frontend-app/app/pages/admin/profil.vue)
   - Alias/Redirect: [admin/profile.vue](file:///c:/xampp/htdocs/pkl-digo/frontend-app/app/pages/admin/profile.vue)

2. **Fitur Pengaturan Administrator (Bisa Mengubah Semuanya)**:
   - **Foto Profil Admin**:
     - Pratinjau foto profil dengan tombol kamera mengambang.
     - Unggah file gambar lokal (JPG, PNG, WebP hingga 10MB).
     - Pilihan cepat 4 avatar representatif siap pakai.
     - Input URL gambar eksternal & tombol hapus foto.
   - **Nama Lengkap Administrator**:
     - Kolom input teks dapat diedit langsung (`v-model="form.name"`).
     - Badge hijau: `BISA DIUBAH`.
   - **NIP / Username Administrator**:
     - Kolom input NIP dapat diedit langsung (`v-model="form.nip"`).
     - Badge hijau: `BISA DIUBAH`.
   - **Kata Sandi (Password)**:
     - Kolom input password dengan tombol intip/sembunyikan (`👁️ / 🙈`).
     - Admin dapat langsung mengganti password baru kapan saja.
   - **Aksi & Sinkronisasi**:
     - Tombol **Simpan Semua Perubahan** memperbarui database, cookie sesi, dan `localStorage`.
     - Seluruh navigasi Admin (sidebar akun di semua halaman admin) langsung tersambung ke halaman `/admin/profil`.

---

## 3. Penyesuaian di Halaman Profil User (`/User/profile`)

1. **Berkas Halaman**: [User/profile.vue](file:///c:/xampp/htdocs/pkl-digo/frontend-app/app/pages/User/profile.vue)
2. **Dukungan Role Dinamis**:
   - Jika yang sedang login adalah **Pegawai Biasa (`role !== 'admin'`)**:
     - Nama, NIP, dan Password berstatus **PATEN / HANYA-BACA 🔒**.
     - Hanya Foto Profil yang dapat diubah.
   - Jika yang sedang login adalah **Administrator (`role === 'admin'`)**:
     - Muncul banner *"Hak Istimewa Administrator"*.
     - Form Nama, NIP, dan Password terbuka dan dapat diedit langsung (`Bisa Diedit ✏️`).
     - Dilengkapi tombol *"Simpan Semua Data (Nama, NIP, Password)"* dan tautan ke Panel Admin.

---

## 4. Hasil Verifikasi & Uji Coba

- **Uji Coba Script Endpoint API**:
  - Pengujian update Nama, NIP, Password, dan Avatar Admin pada database `users` berjalan 100% sukses (Status HTTP 200).
- **Verifikasi Build Nuxt 4**:
  - Perintah `npm.cmd run build` pada folder `frontend-app` sukses tanpa error (`✨ Build complete!`, bundle size ~3 MB).
