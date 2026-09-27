<h1 align="center">💰 Ran's Cents</h1>

<p align="center">
  <strong>Aplikasi pencatatan keuangan pribadi yang simpel, cepat, dan aman.</strong><br/>
  Catat pemasukan, pengeluaran, hutang, tabungan, dan wishlist dalam satu tempat.
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-10.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 10"/>
  <img src="https://img.shields.io/badge/PHP-8.1+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.1+"/>
  <img src="https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL"/>
  <img src="https://img.shields.io/badge/License-MIT-green?style=for-the-badge" alt="MIT License"/>
</p>

---

## ✨ Fitur Utama

| Fitur | Deskripsi |
|-------|-----------|
| 📊 **Dashboard** | Ringkasan keuangan bulanan, grafik 6 bulan terakhir, dan pengeluaran per kategori |
| 💵 **Pemasukan** | Catat sumber pendapatan dengan kategori dan pengulangan otomatis |
| 💸 **Pengeluaran** | Lacak pengeluaran harian dengan metode pembayaran & kategori |
| 🤝 **Hutang & Piutang** | Kelola hutang/piutang, bayar sebagian, dan pantau jatuh tempo |
| 🎯 **Wishlist** | Daftar keinginan dengan prioritas, estimasi harga, dan tombol "beli" |
| 🏦 **Tabungan** | Alokasi tabungan dengan tujuan spesifik |
| 🔐 **Autentikasi OTP** | Register & reset password menggunakan kode OTP via email |

---

## 🖥️ Tampilan Dashboard

- Saldo bulan ini (pemasukan − pengeluaran)
- Total hutang yang belum dibayar & piutang yang belum diterima
- Grafik pemasukan vs pengeluaran 6 bulan terakhir
- Pie chart pengeluaran per kategori
- 5 transaksi terbaru

---

## 🛠️ Tech Stack

- **Framework:** Laravel 10
- **Bahasa:** PHP 8.1+
- **Database:** MySQL 8.0
- **Frontend:** Blade Template + Vanilla CSS
- **Auth:** Custom OTP (tanpa Laravel Breeze/Jetstream)
- **Email:** SMTP / Log driver (untuk development)

---

## 🚀 Instalasi Lokal

### Prasyarat
- PHP >= 8.1
- Composer
- MySQL
- Node.js & NPM (opsional, untuk asset)

### Langkah Instalasi

**1. Clone repository**
```bash
git clone https://github.com/username/rans-cents.git
cd rans-cents
```

**2. Install dependencies**
```bash
composer install
```

**3. Salin file environment**
```bash
cp .env.example .env
```

**4. Generate app key**
```bash
php artisan key:generate
```

**5. Konfigurasi database**

Edit file `.env` sesuai konfigurasi lokal kamu:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=money_manager
DB_USERNAME=root
DB_PASSWORD=
```

**6. Jalankan migrasi & seeder**
```bash
php artisan migrate
php artisan db:seed
```

**7. Jalankan server**
```bash
php artisan serve
```

Akses aplikasi di: `http://localhost:8000`

---

## 📧 Konfigurasi Email (OTP)

Aplikasi menggunakan email untuk mengirim kode OTP saat registrasi dan reset password.

**Untuk development** (tidak perlu email nyata), gunakan driver `log`:
```env
MAIL_MAILER=log
```
OTP akan tercatat di `storage/logs/laravel.log`.

**Untuk production**, gunakan SMTP:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
```

---

## 📁 Struktur Direktori

```
app/
├── Http/Controllers/
│   ├── Auth/
│   │   ├── LoginController.php      # Login & logout
│   │   ├── RegisterController.php   # Registrasi + kirim OTP
│   │   └── OtpController.php        # Verifikasi OTP & reset password
│   ├── DashboardController.php      # Ringkasan & statistik
│   ├── IncomeController.php         # CRUD Pemasukan
│   ├── ExpenseController.php        # CRUD Pengeluaran
│   ├── DebtController.php           # CRUD Hutang/Piutang + bayar
│   ├── WishlistController.php       # CRUD Wishlist + tandai beli
│   └── SavingController.php         # CRUD Tabungan
├── Models/
│   ├── User.php
│   ├── Income.php / Expense.php / Debt.php
│   ├── Wishlist.php / Saving.php
│   ├── Category.php
│   └── OtpCode.php
database/
├── migrations/                      # Semua skema tabel
└── seeders/
    └── CategorySeeder.php           # 19 kategori default
resources/views/
├── auth/                            # Login, register, OTP
├── dashboard.blade.php
├── incomes/ expenses/ debts/
├── wishlists/ savings/
└── layouts/                         # Layout utama & auth
```

---

## 🔑 Alur Autentikasi

```
Register → Kirim OTP ke Email → Verifikasi OTP → Masuk
                                                      ↓
                              Lupa Password → Kirim OTP → Reset Password
```

- OTP berlaku selama **10 menit**
- OTP hanya bisa digunakan **1 kali**
- Akun tidak bisa login sebelum email diverifikasi

---

## 🌐 Deploy ke Hosting Shared (InfinityFree / cPanel)

1. Upload semua file ke folder `public_html`, isi folder `public/` dipindah langsung ke `public_html/`
2. Edit `public_html/index.php`, sesuaikan path ke `bootstrap/app.php`
3. Buat database MySQL melalui control panel hosting
4. Isi `.env` dengan kredensial database hosting
5. Akses `https://your-domain.com/setup-database` untuk menjalankan migrasi otomatis

> ⚠️ **Penting:** Hapus atau amankan route `/setup-database` setelah database berhasil di-setup!

---

## 📜 Lisensi

Proyek ini dibuat untuk kebutuhan pribadi dan pembelajaran. Bebas digunakan dan dimodifikasi.

---

<p align="center">Dibuat dengan ❤️ menggunakan <a href="https://laravel.com">Laravel</a></p>
