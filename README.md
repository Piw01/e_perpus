---

## 📸 Galeri Screenshots

| Halaman | Deskripsi |
|---------|-----------|
| ![Login](https://raw.githubusercontent.com/Piw01/e-perpus/main/screenshots/login.jpg) | **Halaman Login** - Interface login yang simpel dan aman |
| ![Dashboard](https://raw.githubusercontent.com/Piw01/e-perpus/main/screenshots/dashboard.jpg) | **Dashboard** - Statistik real-time & quick access |
| ![Data Buku](https://raw.githubusercontent.com/Piw01/e-perpus/main/screenshots/data-buku.jpg) | **Manajemen Buku** - CRUD lengkap dengan thumbnail |
| ![Tambah Buku](https://raw.githubusercontent.com/Piw01/e-perpus/main/screenshots/tambah-buku.jpg) | **Form Tambah Buku** - Input lengkap dengan file upload |
| ![Data Siswa](https://raw.githubusercontent.com/Piw01/e-perpus/main/screenshots/data-siswa.jpg) | **Manajemen Siswa** - Data anggota perpustakaan |
| ![Management Admin](https://raw.githubusercontent.com/Piw01/e-perpus/main/screenshots/management-admin.jpg) | **Management Admin** - Kelola user sistem (Super Admin only) |

---# 📚 E-PERPUS

**Sistem Informasi Perpustakaan Digital Berbasis Web**

[![License](https://img.shields.io/badge/License-MIT-blue.svg)](LICENSE)
[![PHP](https://img.shields.io/badge/PHP-8.2.12-purple.svg)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-10.4.32-orange.svg)](https://www.mysql.com/)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-blueviolet.svg)](https://getbootstrap.com/)
[![Status](https://img.shields.io/badge/Status-Active-brightgreen.svg)](#)

> Aplikasi web modern untuk mengelola data perpustakaan dengan fitur login multi-level dan CRUD lengkap. Dikembangkan sebagai tugas Ujian Tengah Semester mata kuliah **Pemrograman Web 1** di Sekolah Tinggi Teknologi Bandung.

## 🎨 Screenshot Galeri

### Dashboard
Tampilan dashboard dengan statistik real-time dan akses cepat ke fitur utama.
![Dashboard](https://raw.githubusercontent.com/yourusername/e-perpus/main/screenshots/11-dashboard.png)

### Fitur CRUD Lengkap

**Create - Tambah Data**
- Form responsif dengan validasi
- Upload file untuk sampul/foto
- Dropdown untuk relasi antar tabel

**Read - Tampil Data**
- Tabel dengan thumbnail
- Pagination untuk data besar
- Search dan filter functionality

**Update - Edit Data**
- Form pre-filled dengan data lama
- Validasi perubahan data
- Optional file update

**Delete - Hapus Data**
- Konfirmasi sebelum delete
- Pesan sukses/error
- Cleanup file otomatis

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
| M Raihan Samih | - | Backend Developer |
| M Syahril Ariandi | - | Frontend Developer |
| Yoni Muhammad Nizar | - | Database Engineer |

**Program Studi:** Teknik Informatika  
**Institusi:** Sekolah Tinggi Teknologi Bandung  
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
│                   DATABASE SCHEMA                 │
├──────────────────────────────────────────────────┤
│                                                   │
│  ┌─────────┐  ┌──────────┐  ┌──────────────┐   │
│  │  admin  │  │  siswa   │  │ kategorii    │   │
│  ├─────────┤  ├──────────┤  ├──────────────┤   │
│  │ id_admin│  │   nisn   │  │ id_kategori  │   │
│  │ username│  │   nama   │  │ nama_kategori   │   │
│  │password │  │   jk     │  └──────────────┘   │
│  │  level  │  │ tgl_lahir│                     │
│  └─────────┘  └──────────┘  ┌──────────────┐   │
│                              │  penulis     │   │
│  ┌──────────────────┐       ├──────────────┤   │
│  │     buku         │       │ id_penulis   │   │
│  ├──────────────────┤       │ nama_penulis │   │
│  │  id_buku (PK)    │       └──────────────┘   │
│  │  isbn            │                          │
│  │  judul           │       ┌──────────────┐   │
│  │  id_penulis (FK) │──────→│  penerbit    │   │
│  │  id_penerbit(FK) │──┐    ├──────────────┤   │
│  │  id_kategori(FK) │──┼───→│ id_penerbit  │   │
│  │  tahun_terbit    │  │    │ nama_penerbit   │   │
│  │  sinopsis        │  │    │ kota         │   │
│  │  jumlah          │  │    └──────────────┘   │
│  │  foto_sampul     │  └───→ kategori          │
│  └──────────────────┘                          │
│                                                   │
└──────────────────────────────────────────────────┘
```

### Tabel Utama

#### 📌 admin
```sql
CREATE TABLE admin (
  id_admin INT(11) PRIMARY KEY AUTO_INCREMENT,
  nama_lengkap VARCHAR(100) NOT NULL,
  username VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  level INT(1) NOT NULL DEFAULT 2
);
```

#### 📚 buku
```sql
CREATE TABLE buku (
  id_buku VARCHAR(5) PRIMARY KEY,
  isbn VARCHAR(30) NOT NULL UNIQUE,
  judul VARCHAR(255) NOT NULL,
  id_penulis INT(11) NOT NULL,
  id_penerbit INT(11) NOT NULL,
  id_kategori INT(11) NOT NULL,
  tahun_terbit VARCHAR(4) NOT NULL,
  sinopsis TEXT NOT NULL,
  jumlah INT(11) NOT NULL,
  foto_sampul VARCHAR(100) NOT NULL,
  FOREIGN KEY (id_penulis) REFERENCES penulis(id_penulis),
  FOREIGN KEY (id_penerbit) REFERENCES penerbit(id_penerbit),
  FOREIGN KEY (id_kategori) REFERENCES kategori(id_kategori)
);
```

#### 👨‍🎓 siswa
```sql
CREATE TABLE siswa (
  nisn VARCHAR(20) PRIMARY KEY,
  nama_siswa VARCHAR(100) NOT NULL,
  jenis_kelamin CHAR(1) NOT NULL,
  tempat_lahir VARCHAR(30) NOT NULL,
  tgl_lahir DATE NOT NULL,
  alamat VARCHAR(255) NOT NULL,
  no_hp VARCHAR(13) NOT NULL,
  foto_siswa VARCHAR(100)
);
```

---

## 📁 Struktur Folder Proyek

```
e_perpus/
├── 📄 README.md                          # Dokumentasi proyek
├── 📄 perpus_satu.sql                    # Database dump
│
├── 📂 public/                            # Web root directory
│   ├── 📄 index.php                      # Entry point aplikasi
│   │
│   └── 📂 assets/                        # Static files
│       ├── 📂 css/                       # Stylesheet
│       │   ├── style.css
│       │   └── bootstrap.min.css
│       ├── 📂 js/                        # JavaScript
│       │   ├── script.js
│       │   ├── bootstrap.min.js
│       │   └── validation.js
│       └── 📂 img/                       # Images
│           ├── logo.png
│           └── sampul_buku/
│
├── 📂 src/                               # Source code
│   ├── 📂 config/                        # Configuration
│   │   └── Database.php                  # PDO connection
│   │
│   ├── 📂 controllers/                   # Business logic
│   │   ├── AuthController.php            # Authentication
│   │   ├── BukuController.php            # Book management
│   │   ├── SiswaController.php           # Student management
│   │   ├── KategoriController.php        # Category management
│   │   ├── PenulisController.php         # Author management
│   │   └── PenerbitController.php        # Publisher management
│   │
│   ├── 📂 models/                        # Data access layer
│   │   ├── AdminModel.php
│   │   ├── BukuModel.php
│   │   ├── SiswaModel.php
│   │   ├── KategoriModel.php
│   │   ├── PenulisModel.php
│   │   └── PenerbitModel.php
│   │
│   └── 📂 views/                         # Presentation layer
│       ├── 📂 layouts/
│       │   ├── header.php
│       │   └── footer.php
│       ├── 📂 auth/
│       │   └── login.php
│       ├── 📂 dashboard/
│       │   └── index.php
│       ├── 📂 buku/
│       │   ├── index.php
│       │   ├── create.php
│       │   ├── edit.php
│       │   └── show.php
│       ├── 📂 siswa/
│       └── 📂 errors/
│           └── 404.php
│
└── 📄 .gitignore                         # Git ignore rules
```

---

## 🚀 Panduan Instalasi

### Prerequisites
- PHP 8.0+ dengan ekstensi PDO MySQL
- MySQL/MariaDB 5.7+
- Web server (Apache/Nginx)
- Git (opsional)

### Langkah Instalasi

#### 1️⃣ Clone Repository
```bash
git clone https://github.com/yourusername/e-perpus.git
cd e-perpus
```

#### 2️⃣ Setup Database
```bash
# Via phpMyAdmin atau command line
mysql -u root -p < perpus_satu.sql
```

Atau menggunakan phpMyAdmin:
- Buat database baru bernama `perpus_satu`
- Import file `perpus_satu.sql`

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
✅ Akses: Full (Management Admin + CRUD semua data)

### 👔 ADMIN
```
Username: min
Password: 123
```
✅ Akses: Data Master + Transaksi

### 🎫 OPERATOR
```
Username: min
Password: 123
```
✅ Akses: Read-Only + Transaksi

> ⚠️ **Catatan:** Pada production, gunakan password yang strong dan unique untuk setiap akun.

---

## 📖 Panduan Penggunaan

### 🔐 Login
1. Akses halaman login di `index.php?page=auth/login`
2. Masukkan username dan password
3. Klik tombol **LOGIN**
4. Jika berhasil, akan diarahkan ke dashboard

![Login Page](https://raw.githubusercontent.com/yourusername/e-perpus/main/screenshots/01-login.png)

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

![Tambah Buku](https://raw.githubusercontent.com/yourusername/e-perpus/main/screenshots/02-tambah-buku.png)

#### Edit Buku
1. Di tabel data buku, klik ikon **Edit** (✏️)
2. Ubah data yang diperlukan
3. Klik **Perbarui Buku**

![Edit Buku](https://raw.githubusercontent.com/yourusername/e-perpus/main/screenshots/03-edit-buku.png)

#### Hapus Buku
1. Di tabel data buku, klik ikon **Hapus** (🗑️)
2. Konfirmasi penghapusan
3. Data buku akan terhapus dari sistem

![Data Buku](https://raw.githubusercontent.com/yourusername/e-perpus/main/screenshots/04-data-buku.png)

### 👥 Manajemen Data Siswa
1. Dari dashboard, klik **Data Master → Data Siswa**
2. Klik **+ Tambah Siswa Baru** untuk menambah data baru
3. Isi form dengan informasi lengkap siswa
4. Klik **Simpan Data**

![Tambah Siswa](https://raw.githubusercontent.com/yourusername/e-perpus/main/screenshots/05-tambah-siswa.png)

![Data Siswa](https://raw.githubusercontent.com/yourusername/e-perpus/main/screenshots/06-data-siswa.png)

### 📚 Manajemen Data Master (Kategori, Penulis, Penerbit)

#### Kategori Buku
![Data Kategori](https://raw.githubusercontent.com/yourusername/e-perpus/main/screenshots/07-data-kategori.png)

#### Penulis
![Data Penulis](https://raw.githubusercontent.com/yourusername/e-perpus/main/screenshots/08-data-penulis.png)

#### Penerbit
![Data Penerbit](https://raw.githubusercontent.com/yourusername/e-perpus/main/screenshots/09-data-penerbit.png)

### 👨‍💼 Management Admin (Super Admin Only)
Hanya tersedia untuk level Super Admin - kelola akun admin sistem.

![Management Admin](https://raw.githubusercontent.com/yourusername/e-perpus/main/screenshots/10-management-admin.png)

### Logout
- Klik nama user di navbar → **Logout**
- Anda akan kembali ke halaman login

---

## 🔒 Fitur Keamanan

### 🛡️ Implementasi Keamanan

| Fitur | Deskripsi |
|-------|-----------|
| **Password Hashing** | bcrypt dengan cost factor 10 |
| **SQL Injection Prevention** | Prepared statements PDO |
| **Input Validation** | Server-side & client-side validation |
| **Input Sanitization** | `htmlspecialchars()`, `strip_tags()` |
| **Session Management** | Timeout & role-based access control |
| **CSRF Protection** | (Dapat ditambahkan dengan token) |
| **File Upload Security** | Type checking, size limit, storage outside web root |

### 🔑 Password Hashing

Saat pembuatan user baru:
```php
$password_hashed = password_hash($password, PASSWORD_DEFAULT);
```

Saat login:
```php
if (password_verify($password, $stored_hash)) {
    // Password correct
}
```

---

## 🎓 Konsep Teknis

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

## 🤝 Kontribusi

Proyek ini adalah tugas akademik. Untuk kontribusi atau saran:

1. Fork repository ini
2. Buat branch fitur (`git checkout -b feature/AmazingFeature`)
3. Commit perubahan (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

---

## 📝 Lisensi

Proyek ini menggunakan lisensi **MIT**. Lihat file [`LICENSE`](LICENSE) untuk detail lebih lanjut.

---

## 📞 Kontak & Support

- **Email:** lutfimahesa@email.com
- **GitHub:** [@lutfimahesa](https://github.com/lutfimahesa)
- **Institusi:** Sekolah Tinggi Teknologi Bandung

---

## 🙏 Ucapan Terima Kasih

Terima kasih kepada:
- **Dosen Pengampu:** Erick Andika, S.Kom., M.Kom
- **Bootstrap Team** - untuk framework yang luar biasa
- **PHP Community** - dokumentasi dan tutorial
- **Semua contributor** yang telah membantu

---

## 📚 Referensi & Resource

- [PHP Official Documentation](https://www.php.net/docs.php)
- [MySQL Documentation](https://dev.mysql.com/doc/)
- [Bootstrap 5 Documentation](https://getbootstrap.com/docs/5.0/)
- [PDO Tutorial](https://www.php.net/manual/en/book.pdo.php)
- [Web Security Best Practices](https://owasp.org/)

---

## 🔮 Fitur Masa Depan

Fitur yang berencana ditambahkan:

- [ ] Module Peminjaman & Pengembalian Buku
- [ ] Notification System (Email)
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

## 📊 Statistik Proyek

```
Total Files:        20+
Total Lines of Code: ~2,500+
Database Tables:    7
Controllers:        6
Models:             6
Views:              15+
CSS Rules:          500+
JavaScript Lines:   300+
```

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
