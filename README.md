# Farhana Web — Exclusive Moslem Wear

Farhana Web adalah platform e-commerce minimalis kelas premium yang dirancang khusus untuk butik busana muslimah eksklusif. Aplikasi ini dibangun di atas kerangka kerja **Laravel** dengan perpaduan teknologi interaktif modern seperti **Alpine.js**, **Tailwind CSS**, dan **Swiper.js** untuk menghadirkan pengalaman berbelanja yang mewah, cepat, dan responsif.

---

## 🎨 Fitur Utama Aplikasi

### 1. 🛍️ Pengalaman Belanja Storefront (B2C)
- **Katalog Produk Dinamis**:
  - Filter kategori real-time (Standard, Kids, Khiban, Defect).
  - Sistem pencarian produk instan terintegrasi.
- **Visual & Galeri Premium**:
  - Banner dinamis beranda dengan dukungan gambar desktop, gambar ramah mobile, atau video latar belakang autoplays.
  - **Image-to-Color Gallery Mapping**: Memilih warna varian tertentu akan langsung menggeser galeri foto produk ke gambar yang sesuai dengan warna tersebut.
  - Slider interaktif dengan fitur swipe ramah sentuhan (Swiper.js).
- **Sistem Pembelian Cerdas**:
  - **Dynamic Price Calculation**: Harga produk utama otomatis bertambah jika varian ukuran/warna memiliki harga tambahan (*additional price*).
  - **Dynamic Stock Badge**: Tampilan status stok yang akurat (*Tersedia*, *Stok Menipis*, *Habis Terjual*) yang berubah secara real-time mengikuti kombinasi warna & ukuran terpilih.
  - Pilihan **Size Guide Template** instan berbentuk modal pop-up berdasarkan kategori tipe produk (Abaya, Khimar, Kids, Khiban, General).
- **Countdown & Pre-Order**:
  - Penanda badge khusus untuk barang *Pre-Order* atau *Limited Edition*.
  - Hitung mundur (*countdown timer*) otomatis di halaman detail produk menuju tanggal rilis resmi.

### 2. ⚠️ Kategori Produk Defect & Harga Coret
- **Katalog Defect Terpusat**: Produk defect (memiliki cacat minor/major) dikelompokkan secara khusus tanpa mengotori katalog utama.
- **Badge Kelas Defect**: Penanda kelas defect (`Minor` atau `Major`) di atas gambar katalog dan halaman detail.
- **Fitur Harga Coret (Strikethrough Price)**: Menampilkan harga asli sebelum diskon dengan garis coret (`line-through`) bersandingan dengan harga jual aktif secara estetis guna meningkatkan konversi pembelian.

### 3. 💳 Pembayaran & Logistik Terintegrasi
- **Keranjang Belanja (Cart)**: Tambah, ubah jumlah barang, dan hapus item belanjaan dengan pembaruan harga otomatis.
- **API Lokasi JNE**: Fitur pencarian lokasi tujuan pengiriman otomatis terintegrasi dengan database kecamatan di Indonesia.
- **Gerbang Pembayaran (Midtrans)**: Proses checkout terintegrasi langsung dengan Midtrans Payment Gateway untuk pembayaran aman via e-wallet, virtual account, maupun transfer bank.
- **Airwaybill & JNE Integration**: Pembuatan resi AWB otomatis ke sistem JNE saat transaksi sukses, lengkap dengan modul **Pelacakan Resi (JNE Tracking Service)** langsung di dasbor profil pelanggan.

### 4. 👔 Dasbor Admin (Back-Office)
Dasbor lengkap bagi administrator untuk mengelola seluruh ekosistem toko:
- **Kategori**: Tambah dan edit kategori beserta klasifikasi tipenya (Standard, Kids, Khiban, Defect).
- **Produk**: Manajemen produk lengkap (nama, deskripsi, harga dasar, tag kustom, status preorder/limited, template size guide, pembuatan varian tak terbatas, unggah banyak foto sekaligus, dan pemetaan warna pada setiap foto).
- **Slider Banner**: Kelola banner beranda (unggah video mp4, gambar desktop, gambar mobile, deskripsi, dan judul).
- **Size Guide Templates**: Kelola master data panduan ukuran dan gambar diagramnya.
- **Order Management**: Kelola pesanan masuk, pantau status pembayaran, update nomor resi pengiriman, hingga fitur **Ekspor Laporan ke Excel**.

---

## 📂 Arsitektur Database (Model & Hubungan)

Proyek ini memiliki 12 model utama Eloquent:
1. **User**: Autentikasi pengguna, hak akses (`admin` / `customer`).
2. **UserAddress**: Menyimpan beberapa alamat pengiriman pelanggan.
3. **Category**: Pengelompokan produk dengan tipe khusus (Standard, Kids, Khiban, Defect).
4. **Product**: Data utama produk termasuk harga dasar, status rilis, dan tag.
5. **ProductImage**: File foto produk dengan relasi opsional ke warna tertentu (untuk fitur pemetaan warna).
6. **ProductVariant**: Kombinasi warna, ukuran, stok, dan harga tambahan per varian.
7. **SizeGuideTemplate**: Template panduan ukuran berdasarkan tipe kategori.
8. **Slider**: Konten slider promosi beranda.
9. **Order**: Transaksi utama, status pembayaran (pending/success/expire), info logistik (kurir, tarif, alamat, resi JNE).
10. **OrderItem**: Detail produk dan varian yang dibeli dalam satu transaksi.
11. **JneDestination**: Data kodifikasi tujuan pengiriman JNE.
12. **Tag**: Tag kustom produk.

---

## 🛠️ Persyaratan Sistem

- **PHP**: `^8.1`
- **Composer**
- **Node.js & NPM**
- **MySQL / MariaDB**

---

## 🚀 Panduan Instalasi Lokal

### 1. Kloning Repositori
```bash
git clone https://github.com/your-username/farhana-web.git
cd farhana-web
```

### 2. Pasang Dependency
```bash
composer install
npm install
```

### 3. Konfigurasi Lingkungan (`.env`)
Salin file `.env.example` menjadi `.env`:
```bash
cp .env.example .env
```
Buat kunci aplikasi baru:
```bash
php artisan key:generate
```

Sesuaikan detail koneksi database di file `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=farhana_db
DB_USERNAME=root
DB_PASSWORD=
```

Konfigurasikan juga kredensial Midtrans & JNE jika ingin mengaktifkan modul pembayaran & ekspedisi:
```env
MIDTRANS_MERCHANT_ID=your_merchant_id
MIDTRANS_CLIENT_KEY=your_client_key
MIDTRANS_SERVER_KEY=your_server_key

JNE_API_URL=https://api.jne.co.id
JNE_USERNAME=your_username
JNE_API_KEY=your_api_key
```

### 4. Jalankan Migrasi & Seeders
Jalankan migrasi tabel database beserta data awal (seeder):
```bash
php artisan migrate --seed
```
Hubungkan folder penyimpanan file storage agar dapat diakses publik:
```bash
php artisan storage:link
```

### 5. Jalankan Aplikasi
Jalankan compiler aset Vite di satu terminal:
```bash
npm run dev
```
Jalankan server lokal Laravel di terminal lain:
```bash
php artisan serve
```
Akses aplikasi melalui peramban di alamat: [http://127.0.0.1:8000](http://127.0.0.1:8000)

---

## 🧪 Simulasi Transaksi (Developer Mode)
Untuk keperluan pengembangan tanpa harus melakukan pembayaran Midtrans sungguhan, gunakan rute simulasi transaksi berikut untuk mengubah pesanan menjadi sukses dan mengeluarkan nomor resi JNE otomatis:
```
http://127.0.0.1:8000/dev/simulate-payment/{NOMOR_ORDER}
```
*Catatan: Pastikan untuk menonaktifkan rute simulasi ini sebelum naik ke server produksi.*
