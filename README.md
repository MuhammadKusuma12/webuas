[README.md](https://github.com/user-attachments/files/29243224/README.md)
# Toko Ikan Koi : Platform Pengelolaan dan Penjualan Ikan Koi

Aplikasi web untuk membantu pengelolaan penjualan ikan koi, pakan, obat, vitamin, dan perlengkapan kolam secara digital. Sistem ini dirancang untuk memudahkan proses pengelolaan produk, pencatatan transaksi, pemantauan stok, serta pengelolaan pengguna dalam satu platform terintegrasi.

Dibangun sebagai Proyek Akhir mata kuliah Pemrograman Web II, Fakultas Teknik, Program Studi Teknologi Informasi, Universitas Lambung Mangkurat.

---

## Tim Pengembang

| Nama                 | NIM           | Peran (Role)       | GitHub            |
| -------------------- | ------------- | ------------------ | ----------------- |
| Arinda Dita Pratiwi  | 2410817120018 | Frontend Developer | @arinn-lab        |
| Muhammad Kusuma      | 2410817310019 | Database Engineer  | @MuhammadKusuma12 |
| Oktavia Laila Qodari | 2410817120001 | Backend Developer  | @oktalaila        |

---

## Repository Project

Repository proyek dapat diakses melalui:

https://github.com/MuhammadKusuma12/webuas

---

## Tech Stack

| Komponen           | Teknologi              |
| ------------------ | ---------------------- |
| Bahasa Pemrograman | PHP, JavaScript        |
| Backend Framework  | Laravel 13             |
| Frontend Framework | Vue 3                  |
| Styling            | Tailwind CSS           |
| Database           | MySQL                  |
| Authentication     | Laravel Authentication |
| Version Control    | Git & GitHub           |

---

## Fitur Utama

* **Sistem Otentikasi Multi-Role**
  Login dan registrasi dengan hak akses berbeda untuk Admin, Pegawai, dan Pembeli.

* **Katalog Produk**
  Menampilkan berbagai produk yang dijual, mulai dari ikan koi, pakan, obat, vitamin, hingga aksesoris kolam.

* **Manajemen Produk (CRUD)**
  Admin dapat menambah, melihat, mengubah, dan menghapus data produk beserta informasi stok dan harga.

* **Transaksi Penjualan**
  Mendukung transaksi penjualan secara online maupun offline dengan sistem invoice otomatis.

* **Dashboard Monitoring**
  Menampilkan informasi ringkas mengenai produk, transaksi, dan kondisi stok secara real-time.

---

## Cara Instalasi

### Prasyarat

Pastikan perangkat telah terinstall:

* PHP 8.3 atau lebih baru
* Composer
* Node.js
* NPM
* MySQL

### Langkah Instalasi

1. Clone repository:

```bash
git clone https://github.com/MuhammadKusuma12/webuas.git
cd webuas
```

2. Install dependency backend:

```bash
composer install
```

3. Install dependency frontend:

```bash
npm install
```

4. Salin file environment:

```bash
cp .env.example .env
```

5. Generate application key:

```bash
php artisan key:generate
```

6. Konfigurasi database pada file `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=toko_ikan_koi
DB_USERNAME=root
DB_PASSWORD=
```

7. Jalankan migrasi database dan seeder:

```bash
php artisan migrate --seed
```

8. Jalankan build frontend:

```bash
npm run build
```

9. Jalankan server Laravel:

```bash
php artisan serve
```

Aplikasi dapat diakses melalui browser pada:

```text
http://127.0.0.1:8000
```

---

## Akun Default & Hak Akses

Untuk mempermudah proses pengujian aplikasi, sistem menyediakan akun bawaan berikut:

| Role    | Username | Password |
| ------- | -------- | -------- |
| Admin   | admin    | password |
| Pegawai | pegawai1 | password |

---

## Struktur Database

### Tabel `users`

Menyimpan data pengguna sistem.

| Kolom     | Tipe    | Keterangan              |
| --------- | ------- | ----------------------- |
| id        | BIGINT  | Primary Key             |
| username  | VARCHAR | Username login          |
| nama      | VARCHAR | Nama pengguna           |
| password  | VARCHAR | Password terenkripsi    |
| role      | ENUM    | admin, pegawai, pembeli |
| is_active | BOOLEAN | Status akun             |

---

### Tabel `kode_items`

Menyimpan kategori dan kode produk.

| Kolom       | Tipe    | Keterangan         |
| ----------- | ------- | ------------------ |
| id          | BIGINT  | Primary Key        |
| kode_prefix | VARCHAR | Prefix kode produk |
| nama_kode   | VARCHAR | Nama kategori      |

Contoh kategori:

* Ikan Koi
* Pakan
* Aksesoris
* Obat dan Vitamin

---

### Tabel `items`

Menyimpan seluruh data produk.

| Kolom        | Tipe    | Keterangan         |
| ------------ | ------- | ------------------ |
| id           | BIGINT  | Primary Key        |
| kode_item    | VARCHAR | Kode produk        |
| nama_item    | VARCHAR | Nama produk        |
| harga_beli   | DECIMAL | Harga beli         |
| harga_jual   | DECIMAL | Harga jual         |
| stok         | INTEGER | Jumlah stok        |
| stok_minimum | INTEGER | Batas minimum stok |
| foto         | VARCHAR | Foto produk        |
| deskripsi    | TEXT    | Deskripsi produk   |

---

### Tabel `transaksi`

Menyimpan data transaksi penjualan.

| Kolom             | Tipe     | Keterangan          |
| ----------------- | -------- | ------------------- |
| id                | BIGINT   | Primary Key         |
| nomor_invoice     | VARCHAR  | Nomor invoice       |
| user_id           | BIGINT   | Relasi pengguna     |
| sumber            | VARCHAR  | Online atau Offline |
| status            | ENUM     | Status transaksi    |
| metode_bayar      | VARCHAR  | Metode pembayaran   |
| total_harga       | DECIMAL  | Total transaksi     |
| tanggal_transaksi | DATETIME | Tanggal transaksi   |

---

### Tabel `detail_transaksi`

Menyimpan rincian produk yang dibeli dalam setiap transaksi.

| Kolom        | Tipe    | Keterangan       |
| ------------ | ------- | ---------------- |
| transaksi_id | BIGINT  | Relasi transaksi |
| item_id      | BIGINT  | Relasi produk    |
| nama_item    | VARCHAR | Nama produk      |
| qty          | INTEGER | Jumlah beli      |
| harga        | DECIMAL | Harga satuan     |
| subtotal     | DECIMAL | Total per produk |

---

### Tabel `mutasi_stok`

Menyimpan riwayat perubahan stok produk.

| Kolom         | Tipe    | Keterangan                                            |
| ------------- | ------- | ----------------------------------------------------- |
| item_id       | BIGINT  | Relasi produk                                         |
| user_id       | BIGINT  | Pengguna yang melakukan perubahan                     |
| jenis_mutasi  | ENUM    | masuk, keluar, penyesuaian                            |
| alasan_mutasi | ENUM    | pembelian, penjualan, mati, rusak, retur, dan lainnya |
| jumlah        | INTEGER | Jumlah perubahan stok                                 |
| stok_sebelum  | INTEGER | Stok sebelum perubahan                                |
| stok_sesudah  | INTEGER | Stok setelah perubahan                                |

---

## Hak Akses Pengguna

### Admin

* Mengakses dashboard utama.
* Mengelola seluruh data produk.
* Mengelola kategori produk.
* Mengelola transaksi penjualan.
* Mengelola mutasi stok.
* Mengelola data pengguna.
* Memantau aktivitas sistem.

### Pegawai

* Mengakses dashboard.
* Melihat katalog produk.
* Melakukan transaksi penjualan.
* Melihat data stok produk.
* Melihat riwayat transaksi.

### Pembeli

* Registrasi akun.
* Login ke sistem.
* Melihat katalog produk.
* Melakukan transaksi pembelian.
* Melihat riwayat transaksi pribadi.

---

## Lisensi

Proyek aplikasi web ini dikembangkan untuk keperluan akademik sebagai tugas Proyek Akhir mata kuliah Pemrograman Web II, Program Studi Teknologi Informasi, Fakultas Teknik, Universitas Lambung Mangkurat tahun 2026.
