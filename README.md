# Sistem Akuntansi: Buku Besar, Laba Rugi, dan Neraca

## Deskripsi
Sistem ini adalah aplikasi akuntansi berbasis web yang dibangun dengan Laravel 11 dan Filament PHP v3, yang memungkinkan pengguna untuk:
- Mengelola data akun dan tipe akun
- Mencatat transaksi dalam jurnal umum
- Menghasilkan laporan buku besar, laporan laba rugi, dan neraca

## Tech Stack
**Backend:**
- Laravel 11 (PHP Framework)
- Filament PHP v3 (Admin Panel)

**Frontend:**
- Tailwind CSS
- Alpine.js
- Livewire

**Database:**
- MySQL/MariaDB


## Struktur Database
Sistem ini menggunakan struktur database dengan entitas berikut:

### 1. User
- `id`: Identifikasi unik pengguna
- `username`: Nama pengguna untuk login
- `email`: Alamat email pengguna
- `password`: Kata sandi terenkripsi

### 2. Tipe Akun
- `id`: Identifikasi unik tipe akun
- `kode_tipe`: Kode klasifikasi tipe akun
- `nama_tipe`: Nama kategori akun

### 3. Akun
- `id`: Identifikasi unik akun
- `tipe_akun_id`: Relasi ke tabel Tipe Akun
- `kode_akun`: Kode akun
- `nama_akun`: Nama akun
- `pos_saldo`: Posisi saldo (Debit/Kredit)
- `pos_laporan`: Posisi laporan (Neraca/Laba Rugi)
- `saldo_awal`: Saldo awal akun

### 4. Jurnal Umum
- `id`: Identifikasi unik jurnal
- `tanggal`: Tanggal transaksi
- `keterangan`: Deskripsi transaksi
- `bukti_transfer`: Referensi bukti transaksi

### 5. Jurnal Umum Detail
- `id`: Identifikasi unik detail jurnal
- `jurnal_umum_id`: Relasi ke tabel Jurnal Umum
- `akun_id`: Relasi ke tabel Akun
- `tipe`: Jenis entri (Debit/Kredit)
- `nominal`: Jumlah transaksi

## Fitur Utama
1. **Manajemen Akun**
   - CRUD Tipe Akun dan Akun dengan Filament
   - Setel saldo awal akun
   - Validasi kode akun unik

2. **Pencatatan Transaksi**
   - Form input transaksi dengan validasi entri ganda
   - Pencarian akun otomatis
   - Histori transaksi

3. **Laporan Keuangan**
   - Buku Besar dengan filter periode
   - Laporan Laba Rugi dengan periodisasi
   - Neraca dengan tampilan balance check
   - Ekspor laporan (PDF/Excel)

## Instalasi
1. Clone repository ini:
   ```bash
   git clone [repository-url]
   ```
2. Install dependencies:
   ```bash
   composer install
   npm install
   ```
3. Buat file `.env` dan konfigurasi database:
   ```bash
   cp .env.example .env
   ```
4. Generate key aplikasi:
   ```bash
   php artisan key:generate
   ```
5. Jalankan migrasi dan seeder:
   ```bash
   php artisan migrate --seed
   ```
6. Jalankan development server:
   ```bash
   php artisan serve
   ```

## Konfigurasi Filament
- Panel admin dapat diakses di `/dashboard`
- Konfigurasi tambahan dapat dilakukan di:
  - `config/filament.php`
  - `app/Providers/Filament/AdminPanelProvider.php`
