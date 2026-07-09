# 📦 Inventory Management System (IMS)

**Inventory Management System (IMS)** adalah aplikasi berbasis web yang dikembangkan untuk membantu proses pengelolaan inventaris secara lebih terstruktur dan efisien. Aplikasi ini memungkinkan pengguna mengelola data kategori dan barang, mencatat transaksi peminjaman serta pengembalian, memantau ketersediaan stok secara real-time, dan melihat ringkasan informasi melalui dashboard yang interaktif. Dengan proses yang terdigitalisasi, pencatatan inventaris menjadi lebih rapi, meminimalkan duplikasi data, serta memudahkan pemantauan dan pelaporan.

Aplikasi ini dibangun menggunakan **Laravel 12**, **MySQL 8**, **Tailwind CSS**, **Laravel Breeze** untuk autentikasi dan Role-Based Access Control (RBAC), serta **Chart.js** untuk menyajikan visualisasi data.

---

## 🔑 Akun Login Pengujian (Testing Accounts)

Terdapat 3 (tiga) role dengan hak akses berbeda yang sudah otomatis terbuat di database untuk memudahkan pengujian:

| Role | Email | Password | Kegunaan & Hak Akses |
| :--- | :--- | :--- | :--- |
| **Admin** | `admin@inventory.test` | `password` | **Akses Penuh:** Manajemen user, kelola kategori, kelola produk, transaksi peminjaman, return barang, dan lihat dashboard laporan. |
| **Staff** | `staff@inventory.test` | `password` | **Operator:** Kelola kategori, kelola produk, membuat transaksi peminjaman baru, dan memproses pengembalian barang. |
| **Manager** | `manager@inventory.test` | `password` | **Supervisor:** Hanya bisa melihat dashboard laporan dan daftar barang/kategori (akses *Read-Only*). |

---

## 🚀 Panduan Instalasi & Menjalankan Project

Pilih salah satu dari 2 cara menjalankan aplikasi di bawah ini sesuai dengan preferensi lingkungan kerja Anda:

### METODE 1: Menggunakan Docker (Sangat Direkomendasikan)
*Metode ini sangat mudah karena Anda tidak perlu menginstal PHP, Composer, atau MySQL secara lokal di komputer Anda.*

#### Prasyarat:
- Pastikan aplikasi [Docker Desktop](https://www.docker.com/products/docker-desktop/) sudah terinstal dan sedang berjalan di komputer Anda.

#### Langkah-langkah:
1. **Salin berkas konfigurasi (`.env`)**
   Buka terminal di folder project ini dan jalankan perintah:
   ```bash
   cp .env.example .env
   ```
   *(Konfigurasi database default di dalam file `.env` sudah otomatis cocok dengan container Docker MySQL).*

2. **Nyalakan container Docker**
   Lakukan build dan jalankan service di background:
   ```bash
   docker-compose up -d --build
   ```

3. **Install dependensi PHP (Composer)**
   Jalankan perintah ini untuk mengunduh package Laravel di dalam container:
   ```bash
   docker-compose exec app composer install
   ```

4. **Buat Application Key**
   ```bash
   docker-compose exec app php artisan key:generate
   ```

5. **Jalankan Migrasi Database & Seeding Data**
   Perintah ini akan membuat tabel database sekaligus mengisi data awal pengujian (kategori, produk, dan user akun):
   ```bash
   docker-compose exec app php artisan migrate --seed
   ```

6. **Buat Link Storage (untuk foto produk)**
   ```bash
   docker-compose exec app php artisan storage:link
   ```

7. **Compile Asset Frontend (Tailwind & JS)**
   Jalankan perintah npm di komputer lokal Anda:
   ```bash
   npm install
   npm run build
   ```

8. **Buka Aplikasi di Browser 🎉**
   Aplikasi Anda siap diakses melalui URL berikut:
   - **Aplikasi Web**: [http://localhost:8000](http://localhost:8000)
   - **phpMyAdmin (Manajemen Database)**: [http://localhost:8081](http://localhost:8081) (Username: `root` \| Password: `root`)

*Untuk menghentikan container Docker jika sudah selesai:*
```bash
docker-compose down
```

---

### METODE 2: Menggunakan Server PHP Lokal (Tanpa Docker)

#### Prasyarat:
- PHP versi 8.3 atau lebih baru.
- MySQL Server versi 8.0 atau lebih baru.
- [Composer](https://getcomposer.org/) terinstal.
- [Node.js & npm](https://nodejs.org/) terinstal.

#### Langkah-langkah:
1. **Buat Database Baru**
   Masuk ke database MySQL lokal Anda (misal via phpMyAdmin atau CLI) lalu jalankan perintah:
   ```sql
   CREATE DATABASE inventory_management;
   ```

2. **Salin berkas `.env` dan sesuaikan koneksi database**
   ```bash
   cp .env.example .env
   ```
   Buka file `.env` yang baru dibuat dengan text editor, lalu ubah bagian database menjadi:
   ```env
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=inventory_management
   DB_USERNAME=username_mysql_anda
   DB_PASSWORD=password_mysql_anda
   ```

3. **Install dependensi PHP**
   ```bash
   composer install
   ```

4. **Buat Application Key**
   ```bash
   php artisan key:generate
   ```

5. **Jalankan Migrasi & Seeding**
   ```bash
   php artisan migrate --seed
   ```

6. **Buat Link Storage**
   ```bash
   php artisan storage:link
   ```

7. **Install & Jalankan Node compiler**
   ```bash
   npm install
   ```
   Kompilasi asset untuk siap digunakan:
   ```bash
   npm run build
   ```

8. **Nyalakan Server Lokal**
   Buka 2 tab terminal terpisah untuk menjalankan server backend dan development server frontend secara bersamaan:

   - **Terminal 1 (Server PHP):**
     ```bash
     php artisan serve --port=8000
     ```
   - **Terminal 2 (Server Vite untuk Hot-Reload):**
     ```bash
     npm run dev
     ```

9. **Buka Aplikasi di Browser 🎉**
   Aplikasi Anda siap diakses melalui URL: [http://localhost:8000](http://localhost:8000)

---

## 📊 Dokumentasi API (Postman Collection & Environment)

Untuk memudahkan pengujian dan pemahaman integrasi endpoint backend, berkas koleksi dan environment Postman telah disediakan secara lengkap di root direktori:

- **Collection**: [postman_collection.json](./postman_collection.json) (terdapat 6 folder kategori module lengkap dengan auto-headers, pre-request scripts CSRF, form-data, query parameters, dan test assertions).
- **Environment**: [postman_environment.json](./postman_environment.json) (berisi variabel `base_url`, kredensial uji coba, dan ID referensi untuk pengujian CRUD lokal).

### Cara Import & Uji di Postman:
1. Buka aplikasi **Postman**.
2. Klik tombol **Import** di sisi kiri atas, lalu pilih kedua file: `postman_collection.json` dan `postman_environment.json`.
3. Pada pojok kanan atas Postman, aktifkan environment **`Inventory Management System - Local Dev`**.
4. Lakukan request **1. Auth -> Sanctum CSRF Cookie** terlebih dahulu untuk menginisialisasi cookie keamanan Laravel Sanctum.
5. Lakukan request **1. Auth -> Login as Admin / Staff / Manager** agar session cookie dan state login tersimpan otomatis.
6. **Otomatisasi Penuh**: Anda dapat langsung menguji endpoint lainnya di folder *Profile*, *Dashboard*, *Categories*, *Products*, dan *Borrowings*. Token CSRF (`X-XSRF-TOKEN`) akan disisipkan secara otomatis oleh script pada setiap request modifikasi data!

