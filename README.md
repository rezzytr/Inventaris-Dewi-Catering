# 🍱 Sistem Manajemen Inventaris - Dewi Catering

Sistem Informasi Manajemen Inventaris dan Pengadaan Bahan Baku berbasis web yang dirancang khusus untuk mempermudah operasional usaha catering. Sistem ini membantu mengelola data produk/bahan baku, supplier, transaksi Purchase Order (PO), hingga pembaruan stok secara otomatis dan terintegrasi.

---

## ✨ Fitur Utama

- **📊 Dasbor Utama**: Menampilkan ringkasan data inventaris dan indikator performa utama secara *real-time*.
- **📦 Manajemen Produk & Stok**: Pengelolaan data bahan baku, harga beli, serta pemantauan kuantitas stok.
- **🏢 Manajemen Supplier**: Pendataan mitra pemasok bahan baku secara terpusat.
- **🛒 Modul Purchase Order (PO)**:
  - Pembuatan PO baru dengan perhitungan total harga otomatis.
  - Pencatatan detail pesanan ke dalam tabel relasional (`purchase_order_items`).
  - Pembaruan status PO (*Pending*, *Received*, *Cancelled*).
  - **Sinkronisasi Stok Otomatis**: Stok produk otomatis bertambah ketika status PO diubah menjadi *Received (Selesai)*.
- **📄 Laporan Pembelian**: Rekapitulasi riwayat transaksi pembelian bahan baku.
- **👥 Manajemen Pengguna**: Pengelolaan hak akses pengguna sistem (staf gudang, admin, dll.).

---

## 🛠️ Teknologi yang Digunakan

* **Kerangka Kerja Backend**: Laravel 10 / 12
* **Bahasa Pemrograman**: PHP 8.x
* **Basis Data**: MySQL
* **Tampilan (Frontend)**: Blade Templating, Bootstrap 5, FontAwesome Icons
* **Versi Kontrol**: Git & GitHub

---

## 🚀 Panduan Instalasi Lokal

Untuk menjalankan proyek ini di lingkungan lokal Anda, ikuti langkah-langkah berikut:

1. **Kloning Repositori**:
   ```bash
   git clone [https://github.com/username-anda/nama-repositori.git](https://github.com/username-anda/nama-repositori.git)
   cd nama-repositori