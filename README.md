# Sistem Reservasi Hotel Berbasis Web

Aplikasi web untuk manajemen hotel modern yang dikembangkan menggunakan **Laravel 10** dan **Tailwind CSS**.  
Aplikasi ini menyediakan sistem **reservasi kamar**, **pengelolaan pengguna**, dan **manajemen fasilitas** dengan antarmuka yang bersih, responsif, dan mudah digunakan.

---

## 🚀 Fitur Utama

### 🧾 Admin

-   Melihat dan mengelola daftar reservasi
-   Mengubah status reservasi (pending, confirmed, check-in, completed, cancelled)
-   Melihat detail tamu dan pemesanan

### 🛠️ Superadmin

-   Dashboard statistik hotel (jumlah kamar, total reservasi, total pendapatan)
-   CRUD data kamar (Tambah, Edit, Hapus, Detail)
-   CRUD data fasilitas kamar
-   Manajemen pengguna (ubah role: admin, receptionist, user)
-   Kelola semua data reservasi

---

## 🧩 Teknologi yang Digunakan

| Teknologi            | Deskripsi                           |
| -------------------- | ----------------------------------- | --- |
| **Laravel 10**       | Framework backend utama             |
| **Tailwind CSS**     | Framework CSS utility-first         |
| **Alpine.js**        | Interaktivitas ringan pada frontend |
| **MySQL**            | Database utama                      |
| **Blade Components** | Komponen UI reusable                |     |
| **Eloquent ORM**     | Manajemen relasi data antar tabel   |

---

## ⚙️ Cara Instalasi & Setup

### 1️⃣ Clone Repository

```bash
git clone https://github.com/fauziahmadzaki/sistem-hotel.git
cd sistem-hotel
```

### 2️⃣ Install Dependencies

```bash
composer install
npm install
```

### 3️⃣ Buat File .env

```bash
cp .env.example .env
```

Lalu sesuaikan konfigurasi database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hotelio_db
DB_USERNAME=root
DB_PASSWORD=
```

### 4️⃣ Generate App Key

```bash
php artisan key:generate
```

###5️⃣ Jalankan Migrasi & Seeder

```bash
php artisan migrate --seed
```

### 7️⃣ Jalankan Server

```bash
php artisan serve
npm run dev
```

Buka di browser:
👉 http://localhost:8000

---
