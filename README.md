# Rancang Bangun Aplikasi Diagnosis Masalah Stunting pada Balita Menggunakan Metode Forward Chaining Berbasis Website

![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)

Aplikasi ini adalah sebuah sistem pakar berbasis website yang dirancang untuk melakukan skrining atau diagnosis awal terhadap masalah stunting pada balita. Sistem ini menggunakan metode inferensi **Forward Chaining** untuk menarik kesimpulan berdasarkan gejala-gejala yang diinput oleh pengguna.

Tujuan utama dari aplikasi ini adalah sebagai alat bantu edukasi dan deteksi dini bagi orang tua, bukan sebagai pengganti diagnosis medis profesional.

---

## Tampilan Aplikasi (Screenshot)

*(Disarankan untuk menambahkan screenshot aplikasi Anda di sini untuk memberikan gambaran visual. Contohnya halaman dashboard user atau admin.)*

![Contoh Tampilan Dashboard](link-ke-screenshot-anda.png)

---

## Fitur Utama

Aplikasi ini memiliki dua hak akses utama dengan fitur yang berbeda untuk masing-masing peran.

### Fitur untuk Pengguna Biasa (Orang Tua)
* **👤 Registrasi & Login:** Pengguna dapat membuat akun pribadi dan login secara aman ke dalam sistem.
* **👶 Manajemen Data Anak:** Mendaftarkan dan melihat data anak balita yang akan didiagnosis.
* **🩺 Proses Diagnosis:** Menjawab serangkaian pertanyaan (gejala) untuk memulai proses diagnosis.
* **💡 Hasil Diagnosis:** Menerima hasil kesimpulan dari sistem pakar (misal: Berisiko Stunting, Normal, dll).
* **📜 Riwayat Diagnosis:** Melihat semua riwayat diagnosis yang pernah dilakukan untuk setiap anak, memungkinkan pelacakan dari waktu ke waktu.

### Fitur untuk Administrator
* **⚙️ Dashboard Admin:** Pusat kontrol untuk melihat statistik kunci sistem seperti jumlah pengguna, total diagnosis, dan aktivitas terbaru.
* **👥 Manajemen Pengguna:** Melihat daftar semua pengguna dan membuat akun baru dengan role 'user' atau 'admin'.
* **🧠 Manajemen Basis Pengetahuan:**
    * **Kelola Pertanyaan:** Kontrol penuh (Tambah, Lihat, Hapus) atas daftar pertanyaan/gejala yang menjadi dasar diagnosis.
    * **Kelola Aturan:** Kontrol penuh (Tambah, Lihat, Hapus) atas aturan logika `IF-THEN` yang merupakan otak dari metode Forward Chaining.
* **📋 Laporan & Ekspor Data:**
    * Melihat laporan lengkap dari seluruh riwayat diagnosis yang ada di sistem.
    * Mengekspor semua data laporan ke dalam format file **.CSV** yang kompatibel dengan Microsoft Excel.

---

## Teknologi yang Digunakan
* **Backend:** PHP 8.2 (Native, tanpa framework)
* **Database:** MySQL / MariaDB
* **Frontend:** HTML, CSS
* **Metode Sistem Pakar:** Forward Chaining

---

## Instalasi & Konfigurasi

Berikut adalah langkah-langkah untuk menjalankan proyek ini di lingkungan lokal Anda.

### Prasyarat
* Pastikan Anda sudah menginstal **XAMPP** (atau sejenisnya seperti WAMP/MAMP) yang menyertakan Apache, MySQL, dan PHP.

### Langkah-langkah Instalasi
1.  **Clone atau Unduh Repository**
    ```bash
    git clone [https://github.com/username/nama-repository.git](https://github.com/username/nama-repository.git)
    ```
    Atau unduh file ZIP dan ekstrak.

2.  **Pindahkan ke Folder `htdocs`**
    * Pindahkan seluruh folder proyek ke dalam direktori web server Anda. Untuk XAMPP, lokasinya adalah `C:\xampp\htdocs\`.

3.  **Setup Database**
    * Jalankan Apache dan MySQL dari XAMPP Control Panel.
    * Buka browser dan akses `http://localhost/phpmyadmin`.
    * Buat database baru dengan nama `db_stunting`.
    * Pilih database `db_stunting` tersebut, lalu klik tab **SQL**.
    * Salin dan jalankan query SQL yang ada di file `database_setup.sql` (atau dari panduan yang diberikan) untuk membuat semua tabel dan mengisi data awal (aturan & pertanyaan).

4.  **Konfigurasi Koneksi (Opsional)**
    * Konfigurasi koneksi database ada di file `config/database.php`.
    * Pengaturan default sudah disesuaikan untuk XAMPP standar (`host: 'localhost'`, `user: 'root'`, `password: ''`). Anda tidak perlu mengubahnya jika menggunakan XAMPP standar.

5.  **Jalankan Aplikasi**
    * Buka browser dan akses URL: `http://localhost/nama-folder-proyek/`
    * Anda akan diarahkan ke halaman login atau bisa memulai dari halaman registrasi.

6.  **Membuat Akun Admin Pertama**
    * Daftarkan sebuah akun baru melalui halaman `register.php`.
    * Buka `phpMyAdmin` -> database `db_stunting` -> tabel `users`.
    * Cari user yang baru Anda daftarkan, klik **Edit**.
    * Ubah nilai pada kolom `role` dari `user` menjadi `admin`, lalu klik **Go**.
    * Sekarang Anda bisa login dengan akun tersebut untuk mengakses panel admin.

---

## Struktur Folder Proyek
/
├── admin/              # Halaman dan logika khusus untuk Admin
│   ├── auth_admin.php
│   ├── dashboard_admin.php
│   ├── kelola_pengguna.php
│   ├── kelola_aturan.php
│   └── laporan_diagnosis.php
│   └── export.php
├── assets/             # File CSS, gambar, dll.
│   └── style.css
├── config/             # File konfigurasi
│   └── database.php
├── dashboard.php       # Halaman dashboard untuk user biasa
├── diagnosis.php       # Halaman proses diagnosis
├── hasil.php           # Halaman hasil diagnosis
├── login.php           # Halaman login
├── register.php        # Halaman registrasi
├── riwayat.php         # Halaman riwayat diagnosis user
└── README.md           # File ini

## Lisensi
Proyek ini dilisensikan di bawah Lisensi MIT.
