# Sima PKL CI4

REST API berbasis **CodeIgniter 4** untuk manajemen **PKL / Magang Sekolah**.  
Proyek ini dirancang sebagai backend dengan autentikasi JWT, migration, dan seeder bawaan.

---

## 🚀 Tech Stack

- PHP >= 8.0  
- CodeIgniter 4  
- MySQL  
- Composer  
- JWT Authentication  
- CI4 Migration & Seeder  

---

## 📦 Prerequisites

Pastikan environment Anda sudah terpasang:

- PHP >= 8.0  
- Composer  
- MySQL  
- Git  

Cek versi PHP:

```bash
php -v
```
## ⚙️ Installation
### 1. Clone Repository
```bash
git clone https://github.com/Hiujalan/sima-pkl-ci4.git
cd sima-pkl-ci4
```
### 2. Install Dependencies
```bash
composer install
```
### 3. Setup Environment File
Copy file .env.example menjadi .env:
```bash
cp .env.example .env
```
Lalu sesuaikan konfigurasi database di file .env

### 4. Generate App Key
```bash
php spark key:generate
```
### 5. Setup Database
```bash
CREATE DATABASE sima-pkl;
```

### 6. Jalankan Migration
```bash
php spark migrate
```
### 7. Jalankan Seeder
```bash
php spark db:seed RoleSeeder
php spark db:seed UserSeeder
```

## 🔐 Default Super Admin Account
Setelah proses seeding selesai, Anda bisa login menggunakan akun berikut:
- Email : admin@example.com
- Password : sayalupa@admin

## ▶️ Run Application
Jalankan server bawaan CodeIgniter:
```bash
php spark serve
```
Aplikasi akan berjalan di:
```bash
http://localhost:8080
```


