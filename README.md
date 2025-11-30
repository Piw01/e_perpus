🚀 E-Perpus: Aplikasi WEB Sistem Informasi Perpustakaan


<p align="center"> <a href="#"> <img src="https://img.shields.io/badge/Status-Development-yellow.svg" alt="Status Pengembangan"> </a> <a href="#"> <img src="https://img.shields.io/badge/Lisensi-MIT-blue.svg" alt="Lisensi"> </a> <a href="#"> <img src="https://img.shields.io/badge/Dibuat%20dengan-HTML%20%26%20PHP-red.svg" alt="Teknologi"> </a> </p>


Aplikasi WEB E-Perpus adalah sebuah solusi sistem informasi yang dirancang untuk mengelola operasional perpustakaan secara digital, mulai dari katalogisasi buku, manajemen anggota, hingga transaksi peminjaman dan pengembalian.


✨ Fitur Utama
Manajemen Buku: Tambah, edit, dan hapus data buku beserta detailnya.

Manajemen Anggota: Pendataan anggota perpustakaan dengan hak akses yang jelas.

Transaksi: Pencatatan peminjaman dan pengembalian buku yang efisien.

Sistem Hierarki Pengguna: Membedakan hak akses untuk Super Admin, Admin, dan Operator.


💻 Teknologi yang Digunakan
Projek ini dikembangkan menggunakan tumpukan teknologi standar untuk aplikasi web:

Frontend: HTML, CSS, JavaScript (Jika ada framework, sebutkan di sini, cth: Bootstrap).

Backend: PHP (Sebutkan framework jika menggunakan, cth: Laravel/CodeIgniter).

Database: MySQL/MariaDB.


🛠️ Instalasi Projek
Ikuti langkah-langkah sederhana ini untuk menjalankan E-Perpus di lingkungan lokal Anda.

Prasyarat
Pastikan Anda telah menginstal web server lokal seperti XAMPP, WAMP, atau Laragon.

Langkah-langkah


Clone Repositori:

Bash

git clone [Link Repositori Anda Di sini]
Pindahkan Folder: Pindahkan folder projek (e_perpus) ke direktori web server Anda (misalnya: htdocs pada XAMPP).

Setup Database:

Nyalakan layanan Apache dan MySQL di XAMPP/WAMP/Laragon Anda.

Buka phpMyAdmin di browser Anda.

Buat database baru (misalnya, beri nama e_perpus).

Import file SQL yang tersedia di: e_perpus/perpus_satu.sql

Akses Aplikasi: Buka browser Anda dan akses projek melalui:

http://localhost/e_perpus



🔑 Informasi Login Default
Aplikasi ini menggunakan sistem hierarki dengan kredensial default untuk mempermudah pengujian awal.

Peran	Username	Password	Hak Akses
Super Admin	min	123	Semua kontrol, termasuk manajemen user.
Admin	min	123	Kontrol penuh operasional perpustakaan.
Operator	min	123	Fokus pada transaksi peminjaman/pengembalian.

CATATAN: Setelah instalasi, sangat disarankan untuk segera mengganti password default ini!


👥 Tim Pengembang
Projek E-Perpus ini dikembangkan dengan kolaborasi oleh:

Lutfi Mahesa Abdul kholiq

M Raihan Samih

M Syahril Ariandi

Yoni Muhammad Nizar


🤝 Kontribusi
Kami sangat menyambut kontribusi dari komunitas! Jika Anda memiliki saran atau ingin melaporkan bug, silakan buka Issue atau kirimkan Pull Request.

Fork repositori ini.

Buat branch baru: git checkout -b fitur-baru

Lakukan perubahan dan commit perubahan Anda: git commit -m 'Tambahkan fitur baru'

Push ke branch: git push origin fitur-baru

Ajukan Pull Request baru.
