# 📚 E-PERPUS

**Sistem Informasi Perpustakaan Digital Berbasis Web**

[![PHP](https://img.shields.io/badge/PHP-8.2.12-purple.svg)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-10.4.32-orange.svg)](https://www.mysql.com/)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-blueviolet.svg)](https://getbootstrap.com/)
[![Status](https://img.shields.io/badge/Status-Active-brightgreen.svg)](#)

> Aplikasi web modern untuk mengelola data perpustakaan dengan fitur login multi-level dan CRUD lengkap. Dikembangkan sebagai tugas Ujian Tengah Semester mata kuliah **Pemrograman Web 1** di Sekolah Tinggi Teknologi Bandung.

---

## 🎯 Tentang Proyek

**E-PERPUS** adalah solusi manajemen perpustakaan yang dirancang untuk mempermudah administrasi dan pengelolaan data buku serta anggota perpustakaan. Aplikasi ini menerapkan arsitektur **MVC (Model-View-Controller)** dengan implementasi keamanan standar industri.

### ✨ Fitur Utama

- 🔐 **Sistem Login Multi-Level** dengan role-based access control
  - Super Admin (Full Access)
  - Admin (Data Master + Transaksi)
  - Operator (Read-Only + Transaksi)

- 📖 **Manajemen Data Buku** lengkap dengan:
  - Upload foto sampul
  - ISBN management
  - Kategori, penulis, penerbit

- 👥 **Manajemen Data Siswa/Anggota** dengan informasi lengkap

- 📊 **Dashboard Interaktif** dengan statistik real-time

- ✅ **CRUD Operations** (Create, Read, Update, Delete)

- 📱 **Responsive Design** menggunakan Bootstrap 5

- 🛡️ **Keamanan Tingkat Lanjut**:
  - Password hashing dengan bcrypt
  - Prepared statements (anti SQL Injection)
  - Input sanitization
  - Session management

---

## 👨‍💻 Tim Pengembang

| Nama | NPM | Role |
|------|-----|------|
| Lutfi Mahesa Abdul Kholiq | 233552011147 | Lead Developer |
| M Raihan Samih | 23552011122 | Frontend Developer |
| M Syahril Ariandi | 23552011124 | Database Developer |
| Yoni Muhammad Nizar | 23552011142 | Database Engineer |

**Program Studi:** Teknik Informatika  
**Institusi:** Universitas Teknologi Bandung  
**Tahun Akademik:** 2024/2025

---

## 🛠️ Teknologi yang Digunakan

### Frontend
- **HTML5** - Markup structure
- **CSS3** - Styling dan layout
- **Bootstrap 5** - Responsive framework
- **JavaScript (Vanilla)** - Interaksi dan validasi form
- **Font Awesome** - Icon library

### Backend
- **PHP 8.2.12** - Server-side scripting
- **PDO (PHP Data Objects)** - Database abstraction layer
- **Prepared Statements** - Query security

### Database
- **MySQL 10.4.32** (MariaDB) - RDBMS
- **Relasi Many-to-One** antar tabel

### Development Tools
- **XAMPP** - Local development server
- **phpMyAdmin** - Database management
- **Git & GitHub** - Version control

---

## 📋 Struktur Database

### Entity Relationship Diagram (ERD)

```
┌──────────────────────────────────────────────────┐
│                   DATABASE SCHEMA                │
├──────────────────────────────────────────────────┤
│                                                  │
│  ┌─────────┐  ┌──────────┐  ┌──────────────┐     │
│  │  admin  │  │  siswa   │  │ kategorii    │     │
│  ├─────────┤  ├──────────┤  ├──────────────┤     │
│  │ id_admin│  │   nisn   │  │ id_kategori  │     │
│  │ username│  │   nama   │  │ nama_kategori│     │
│  │password │  │   jk     │  └──────────────┘     │
│  │  level  │  │ tgl_lahir│                       │
│  └─────────┘  └──────────┘  ┌──────────────┐     │
│                             │   penulis    │     │
│  ┌──────────────────┐       ├──────────────┤     │
│  │     buku         │       │ id_penulis   │     │
│  ├──────────────────┤       │ nama_penulis │     │
│  │  id_buku (PK)    │       └──────────────┘     │
│  │  isbn            │                            │
│  │  judul           │       ┌──────────────┐     │
│  │  id_penulis (FK) │──────→│  penerbit    │     │
│  │  id_penerbit(FK) │──┐    ├──────────────┤     │
│  │  id_kategori(FK) │──┼───→│ id_penerbit  │     │
│  │  tahun_terbit    │  │    │ nama_penerbit│     │
│  │  sinopsis        │  │    │ kota         │     │
│  │  jumlah          │  │    └──────────────┘     │
│  │  foto_sampul     │  └───→ kategori            │
│  └──────────────────┘                            │
│                                                  │
└──────────────────────────────────────────────────┘
```

---

---

## 🚀 Panduan Instalasi

### Prerequisites
- PHP 8.0+ dengan ekstensi PDO MySQL
- MySQL/MariaDB 5.7+
- Web server (Apache/Nginx)
- Git (opsional)


#### 3️⃣ Konfigurasi Koneksi Database

Edit file `src/config/Database.php`:
```php
private const DB_HOST = 'localhost';
private const DB_NAME = 'perpus_satu';
private const DB_USER = 'root';              // Username MySQL Anda
private const DB_PASS = '';                 // Password MySQL Anda
```

#### 4️⃣ Setup Folder Assets
```bash
# Pastikan folder ini writable
mkdir -p public/assets/img/sampul_buku
chmod 755 public/assets/img/sampul_buku
```

#### 5️⃣ Jalankan Aplikasi

**Dengan XAMPP:**
- Copy folder `e_perpus` ke `htdocs`
- Akses: `http://localhost/e-perpus/public/index.php`

**Dengan PHP Built-in Server:**
```bash
php -S localhost:8000 -t public/
# Akses: http://localhost:8000
```

---

## 🔑 Akun Login

Gunakan akun berikut untuk testing:

### 🔐 SUPER ADMIN
```
Username: min
Password: 123
```
 Akses: Full (Management Admin + CRUD semua data)

### 👔 ADMIN
```
Username: min
Password: 123
```
 Akses: Data Master + Transaksi

### 🎫 OPERATOR
```
Username: min
Password: 123
```
 Akses: Read-Only + Transaksi

> ⚠️ **Catatan:** Pada production, gunakan password yang strong dan unique untuk setiap akun.

---

## 📖 Panduan Penggunaan

### 🔐 Login
1. Akses halaman login di `index.php?page=auth/login`
2. Masukkan username dan password
3. Klik tombol **LOGIN**
4. Jika berhasil, akan diarahkan ke dashboard

### Manajemen Data Buku

#### Tambah Buku
1. Dari dashboard, klik **Data Master → Data Buku**
2. Klik tombol **+ Tambah Buku**
3. Isi form dengan lengkap:
   - ISBN
   - Judul Buku
   - Penulis (dropdown)
   - Penerbit (dropdown)
   - Kategori (dropdown)
   - Tahun Terbit
   - Jumlah Stok
   - Sampul Buku (upload JPG/PNG max 5MB)
   - Sinopsis
4. Klik **Simpan Buku**

#### Edit Buku
1. Di tabel data buku, klik ikon **Edit** (✏️)
2. Ubah data yang diperlukan
3. Klik **Perbarui Buku**

#### Hapus Buku
1. Di tabel data buku, klik ikon **Hapus** (🗑️)
2. Konfirmasi penghapusan
3. Data buku akan terhapus dari sistem

### Manajemen Data Siswa
Prosesnya sama dengan manajemen buku, meliputi create, read, update, delete.

### Logout
- Klik nama user di navbar → **Logout**
- Anda akan kembali ke halaman login

---


## Konsep Teknis

### Arsitektur MVC

```
┌─────────────┐
│    View     │  → Menampilkan data ke user
│  (HTML/CSS) │
└─────────────┘
       ↑
       │ Update
       ↓
┌─────────────┐
│ Controller  │  → Logika bisnis & request handling
│   (PHP)     │
└─────────────┘
       ↑
       │ Query/Update
       ↓
┌─────────────┐
│   Model     │  → Akses database & data processing
│  (MySQL)    │
└─────────────┘
```

### Role-Based Access Control (RBAC)

```php
// Level 1 - Super Admin
if ($_SESSION['level'] == 1) {
    // Akses semua fitur
}

// Level 2 - Admin
if ($_SESSION['level'] == 2) {
    // CRUD data master
}

// Level 3 - Operator
if ($_SESSION['level'] == 3) {
    // Read-only data
}
```

### Routing System

```
URL: index.php?page=buku/index
           ↓
   Parse: buku / index
           ↓
Controller: BukuController
Method: index()
           ↓
Render view: buku/index.php
```

---

---

## 📞 Kontak & Support

- **Email:** amfibloods@email.com
- **GitHub:** [@Piw01](https://github.com/Piw01)
- **Institusi:** Universitas Teknologi Bandung

---

## 🙏 Ucapan Terima Kasih

Terima kasih kepada:
- **Dosen Pengampu:** Erick Andika, S.Kom., M.Kom
- **Bootstrap Team** - untuk framework yang luar biasa
- **Semua Anggota** yang telah membantu

---


## 🔮 Fitur Masa Depan

Fitur yang berencana ditambahkan:

- [ ] Module Peminjaman & Pengembalian Buku
- [ ] Advanced Search & Filter
- [ ] Data Export (Excel/PDF)
- [ ] API REST untuk mobile app
- [ ] Dark Mode
- [ ] Two-Factor Authentication (2FA)
- [ ] Real-time Dashboard Analytics
- [ ] Backup & Recovery System

---

## 🐛 Troubleshooting

### Error: "Koneksi database gagal"
**Solusi:**
- Pastikan MySQL server running
- Cek konfigurasi di `src/config/Database.php`
- Pastikan database `perpus_satu` sudah dibuat

### Error: "File upload gagal"
**Solusi:**
- Pastikan folder `public/assets/img/sampul_buku/` writable
- Cek ukuran file (max 5MB)
- Format file harus JPG, PNG, atau GIF

### Error: "Session tidak terbuat"
**Solusi:**
- Pastikan `session_start()` dipanggil di awal
- Cek settings session di `php.ini`

---


---

## ⭐ Jika Proyek Ini Membantu

Jangan lupa beri **star** ⭐ di repository ini!

```
   ⭐⭐⭐⭐⭐
  E-PERPUS
   ⭐⭐⭐⭐⭐
```

---

**Happy Coding! 🚀**

*Last Updated: December 2025*  
*Version: 1.0.0*
