# Dapur Tyas by Techno Garage
Sistem POS Kasir & Smart Self-Order Pelanggan Berbasis Web Jaringan Lokal Wi-Fi.

## Fitur Utama
1. **Smart Self-Order Pelanggan:**
   - 1 Master QR Barcode untuk seluruh resto (Scan -> Pilih Nomor Meja -> Pilih Menu).
   - Katalog 260+ Menu Makanan & Minuman dengan filter kategori dan pencarian cepat.
   - Pilihan Dine In / Take Away & Metode Pembayaran (Kasir Tunai / QRIS E-Wallet).
   - Pertanyaan opsi struk fisik atau e-struk digital (Paperless).
   - Proteksi pembayaran tunai (menu & navigasi terkunci sebelum pembayaran lunas diverifikasi kasir).

2. **Panel Kasir & Admin (POS):**
   - Manajemen Antrean Pesanan Dapur & Kasir (Real-time).
   - Scan Tiket Barcode Pesanan Pelanggan.
   - Cetak Struk Thermal 58mm/80mm untuk pesanan yang meminta struk.
   - Manajemen Meja & Katalog Menu Restoran.
   - Laporan Penjualan & Omzet Harian.

## Persyaratan Sistem
- Web Server (Apache / Nginx) - misal: Laragon / XAMPP.
- PHP 7.4 / 8.0 / 8.1 / 8.2+
- MySQL / MariaDB

## Instalasi
1. Clone repositori ini ke folder root web server (contoh: `C:/laragon/www/Dapur-Tyas`).
2. Buat database baru bernama `dapur_tyas` di MySQL.
3. Import file `database.sql` ke database `dapur_tyas`.
4. Buka browser:
   - Panel Kasir/Staf: `http://localhost/Dapur-Tyas/pos.php` atau `http://localhost/Dapur-Tyas/login.php`
   - Self-Order Pelanggan: `http://localhost/Dapur-Tyas/pilih-meja.php`
   - Cetak QR Barcode: `http://localhost/Dapur-Tyas/barcode.php`
