# Web Rent Apartment

Web Rent Apartment adalah aplikasi web untuk mencari, menyewa, dan memanajemen apartemen secara online. Proyek ini dibuat untuk memudahkan pengguna menemukan apartemen sesuai kebutuhan, melihat detail properti, serta melakukan pemesanan secara digital.

## Fitur Utama

- Pencarian apartemen berdasarkan lokasi, harga, dan fasilitas
- Detail setiap apartemen: foto, deskripsi, harga, fasilitas
- Sistem pemesanan dan penyewaan online
- Manajemen akun pengguna (penyewa & pemilik apartemen)
- Ulasan dan rating apartemen
- Notifikasi penyewaan

## User Guide

Panduan pengguna lengkap tersedia dalam format PDF:

[Lihat User Guide (PDF)](./userguide.pdf)

Atau baca ringkasan di bawah ini.

## Instalasi

### 1. Clone repositori

```bash
git clone https://github.com/VyRoux/web-rent-apartment.git
cd web-rent-apartment
```

### 2. Setup Database

1. Buka **phpMyAdmin** melalui browser (misal: `http://localhost/phpmyadmin`).
2. **Buat database baru** dengan nama `rent_apartment`.
3. **Import/ekspor** struktur dan data dengan file SQL terbaru:
   - Pilih database `rent_apartment` yang sudah dibuat.
   - Klik menu **Import** dan pilih file: `rent_apartmen_update2.sql` (file ini sudah disediakan di repo).
   - Klik **Go** untuk mulai proses import.
   - Pastikan tidak ada error saat import.
4. Pastikan konfigurasi koneksi database pada aplikasi sudah sesuai (`config.php` biasanya berisi pengaturan nama database, user, password, host, dll).

### 3. Konfigurasi file `config.php`

Pastikan pengaturan path lokal sudah tepat di file `config.php`.  
Contoh pengaturan `BASE_URL`:

```php
define('BASE_URL', 'http://localhost/web-rent-apartment/');
```

### 4. Instal dependensi

Jika menggunakan npm/composer, jalankan perintah berikut (sesuaikan kebutuhan):

```bash
npm install
# atau
composer install
```

### 5. Jalankan aplikasi

- Letakkan folder proyek pada `htdocs` (mis: `C:\xampp\htdocs\web-rent-apartment`)
- Akses via browser: `http://localhost/web-rent-apartment/`

## Penggunaan

- Login/daftar akun untuk mulai menggunakan fitur aplikasi.
- Cari apartemen, lihat detail, dan lakukan pemesanan sesuai kebutuhan.

## Kontribusi

Kontribusi sangat terbuka!  
Jika ingin menambah fitur, menemukan bug, atau memperbaiki kode:

1. Fork repositori
2. Buat branch baru untuk perubahan (`git checkout -b fitur-baru`)
3. Commit perubahan lalu kirim pull request

## Lisensi

Lisensi proyek (tentukan lisensi, misal MIT/Apache).

---

**Catatan:**
- Selalu gunakan file database terbaru: **rent_apartmen_update2.sql**.
- Pastikan pengaturan `BASE_URL` dan koneksi database sudah benar untuk mencegah error 404 atau gagal koneksi database.

---

**Hubungi**  
Untuk pertanyaan atau kerjasama, silakan buka Issue atau kontak langsung melalui email yang tertera di profil GitHub.
