# JANJI
Saya Muhammad Syafiq A dengan NIM 2500254 mengerjakan TP 7 dalam mata kuliah Desain dan Pemrograman Berorientasi Objek untuk keberkahanNya maka saya tidak melakukan kecurangan seperti yang telah dispesifikasikan. Aamiin

## Struktur File
```
│
├── class/
│   ├── Kategori.php       <-- Model CRUD untuk tabel kategori
│   ├── Makanan.php        <-- Model CRUD untuk tabel makanan (relasi)
│   └── Restoran.php       <-- Model CRUD untuk tabel restoran
│
├── config/
│   └── db.php             <-- Konfigurasi dan koneksi PDO ke database
│
├── database/
│   └── db_kuliner.sql     <-- File ekspor database MySQL untuk dikumpulkan
│
├── view/
│   ├── footer.php         <-- Bagian bawah halaman HTML (modular)
│   ├── header.php         <-- Bagian atas HTML dan menu navigasi (modular)
│   ├── kategori.php       <-- Antarmuka CRUD dan tabel Kategori
│   ├── makanan.php        <-- Antarmuka CRUD dan tabel Menu Makanan
│   ├── restoran.php       <-- Antarmuka CRUD dan tabel Tempat Makan
│   └── style.css          <-- File styling agar tampilan web rapi
│
├── index.php              <-- Titik awal (router) yang mengarahkan ke view/makanan.php
```

## Deskripsi Tema Website

Website ini dikembangkan dengan tema "Sistem Rekomendasi Makanan Sehat di Sekitar Kampus UPI". Ide ini dilatarbelakangi oleh tingginya kebutuhan mahasiswa (khususnya mahasiswa UPI di area Gegerkalong dan Setiabudhi) terhadap akses informasi mengenai pilihan makanan yang tidak hanya terjangkau secara harga, tetapi juga memiliki nilai gizi yang baik dan sehat.
Aplikasi berbasis web ini berfungsi sebagai katalog dinamis yang memungkinkan penggunanya untuk melakukan manajemen data (CRUD) terhadap tiga elemen utama:
- Kategori Makanan
- Tempat Makan / Restoran (Kantin kampus atau warung makan di sekitar UPI).
- Menu Makanan yang merelasikan makanan dengan kategori dan tempat makan penyedianya, lengkap dengan informasi kalori dan harga.

Dengan adanya sistem ini, pendataan kuliner sehat di sekitar kampus menjadi lebih terstruktur, mudah dibaca, dan dapat terus diperbarui.

## Struktur dan Relasi Database
Sistem ini menggunakan Relational Database Management System (RDBMS) MySQL dengan nama database db_kuliner. Skema database dirancang dengan menjunjung tinggi normalisasi data, terbukti dari pemisahan entitas menjadi 3 tabel berbeda di mana salah satu tabel bertindak sebagai jembatan relasi.

Berikut adalah rincian masing-masing entitas:

- Tabel kategori (Entitas Master 1)
  - Menyimpan referensi pengelompokan jenis makanan.
  - id (INT, Primary Key, Auto Increment): Identifier unik kategori.
  - nama_kategori (VARCHAR): Nama kategori masakan.
  - deskripsi (TEXT): Penjelasan singkat mengenai kategori tersebut.

- Tabel restoran (Entitas Master 2)
  - Menyimpan data lokasi fisik tempat makanan dijual.
  - id (INT, Primary Key, Auto Increment): Identifier unik restoran.
  - nama_restoran (VARCHAR): Nama kantin atau rumah makan.
  - alamat (VARCHAR): Lokasi spesifik di sekitar kampus.
  - jam_operasional (VARCHAR): Waktu buka dan tutup.

- Tabel makanan (Entitas Relasional / Transaksional)
Merupakan entitas utama yang memiliki relasi Foreign Key ke dua entitas master di atas.
  - id (INT, Primary Key, Auto Increment).
  - nama_makanan (VARCHAR): Nama spesifik hidangan.
  - kalori (INT) & harga (DECIMAL): Informasi gizi dan harga.
  - kategori_id (INT): Foreign Key yang mereferensikan id pada tabel kategori.
  - restoran_id (INT): Foreign Key yang mereferensikan id pada tabel restoran.
  - Constraint: Menggunakan ON DELETE CASCADE sehingga jika sebuah restoran atau kategori dihapus, maka menu makanan yang terikat di dalamnya akan otomatis ikut terhapus demi menjaga integritas data.

 ### Penjelasan Alur Kode (Code Flow) dan Implementasi OOP
Arsitektur kode pada aplikasi ini dibangun menggunakan konsep Object-Oriented Programming (OOP) dan dipisahkan secara modular agar kode lebih bersih, mudah dibaca, dan mudah dikelola (mengadaptasi pola dasar MVC).

- A. Konfigurasi (config/db.php)
Di dalam folder config, terdapat file inisiasi koneksi ke server MySQL. Koneksi dibungkus dalam sebuah class Database yang mengembalikan objek koneksi PDO. Blok try-catch digunakan untuk menangkap PDOException apabila terjadi kegagalan koneksi ke server database, sehingga pesan error dapat ditangani dengan baik.

- B. Class Model (class/)
  - Folder ini menampung representasi dari setiap tabel di database dalam bentuk Class (Enkapsulasi).
  - Terdapat tiga file class: Kategori.php, Restoran.php, dan Makanan.php.
  - Saat objek dari class ini diinstansiasi (dibuat), ia akan menerima injection koneksi database melalui Constructor (__construct($db)).
  - Setiap class dilengkapi dengan metode untuk melakukan operasi CRUD secara mandiri:
    - create(): Mengeksekusi query INSERT.
    - read(): Mengeksekusi query SELECT (Pada class Makanan, metode ini juga melakukan JOIN tabel untuk mengambil nama kategori dan restoran berdasarkan ID relasinya).
    - update(): Mengeksekusi query UPDATE berdasarkan ID.
    - delete(): Mengeksekusi query DELETE berdasarkan ID.

C. Tampilan dan Logika Proses (view/)
  - Folder view/ berisi antarmuka pengguna berbasis HTML/CSS yang dikombinasikan dengan logika pemrosesan PHP murni.
  - Modularitas UI: Elemen visual yang berulang seperti bagian atas web dan navigasi dipisah ke header.php, sedangkan bagian penutup dipisah ke footer.php.
  - Penerimaan Data (POST): Setiap halaman (kategori.php, restoran.php, makanan.php) mendeteksi keberadaan request ber-metode POST. Jika pengguna menekan tombol Tambah, Update, atau Hapus, PHP akan memanggil metode CRUD yang bersesuaian dari objek class, lalu melakukan redirect untuk menghindari resubmission form (mencegah data ganda saat halaman di-refresh).
  - Alur Update Data Dinamis (Tanpa JavaScript): Saat tombol "Update" pada tabel diklik, sistem melempar parameter ID ke URL melalui metode GET (contoh: makanan.php?edit=3). File PHP akan menangkap URL tersebut, mencari data terkait di memori, dan secara otomatis mengubah form "Tambah" menjadi form "Update" yang kolomnya sudah terisi data lama. Hal ini membuktikan bahwa fungsionalitas UI modern dapat dicapai secara murni menggunakan logika PHP Native.

D. Router Utama (index.php)
  - Terletak di root directory. File ini berfungsi sebagai entry point. Ketika pengguna membuka alamat proyek di browser, file ini akan merutekan (menggunakan header("Location: ...")) pengguna secara otomatis ke halaman view/makanan.php sebagai beranda utamanya.

## Penjelasan Desain Tampilan Program

Untuk desain tampilan, Web Rekomendasi Makanan Sehat Sekitar UPI dibuat dalam bentuk website dengan beberapa komponen antarmuka yang memiliki fungsi masing-masing.

- **Navigasi** digunakan untuk berpindah dari satu halaman pengelolaan ke halaman lainnya, yaitu Menu Makanan, Kelola Kategori, dan Tempat Makan.
- **Form input** bersifat dinamis, digunakan untuk memasukkan data baru maupun mengubah (update) data yang sudah tersedia secara otomatis tanpa berpindah halaman.
- **Tabel data** digunakan untuk menampilkan daftar entitas (makanan, kategori, restoran) yang tersimpan di dalam database, lengkap dengan tombol aksi pada setiap barisnya.
- **Tombol aksi** dibedakan berdasarkan warna untuk meminimalisir kesalahan pengguna (hijau untuk menyimpan data, oranye untuk mengubah data, dan merah untuk menghapus data).

Tampilan website juga menggunakan CSS murni (Native) untuk mengatur warna, ukuran, tata letak bergaya *card layout*, efek interaktif (*hover* pada tabel), serta jenis font (Poppins) agar antarmuka terlihat modern dan rapi.

Dengan adanya pembagian komponen tersebut, pengguna dapat mengelola data rekomendasi kuliner sehat melalui tampilan yang lebih terstruktur, minimalis, dan sangat mudah digunakan.

## Dokumentasi

[Dokumentasi Di GDrive](https://drive.google.com/file/d/1aFXrRTy-p1ZDBgyXk8Qx1_OT5Caj4HEa/view?usp=drivesdk)
