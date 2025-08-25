# Testing dengan Database Terpisah

Panduan untuk menjalankan `migrate:fresh --seed` pada database testing terpisah tanpa menghilangkan data production.

## 📋 Overview

Setup ini memungkinkan Anda untuk:

-   Menjalankan migrasi dan seeder pada database terpisah (`bookkeeping-db_test`)
-   Menjaga data asli di database utama (`bookkeeping-db`) tetap aman
-   Mudah beralih antara environment production dan testing

## 🚀 Cara Penggunaan

### Opsi 1: Menggunakan Artisan Command (Recommended)

```bash
# Jalankan migrate:fresh --seed pada database testing
php artisan test:fresh

# Restore kembali ke environment asli
php artisan test:fresh --restore
```

### Opsi 2: Menggunakan Shell Script

```bash
# Jalankan migrate:fresh --seed pada database testing
./test-fresh.sh

# Restore kembali ke environment asli
./restore-env.sh
```

## 📁 File yang Dibuat

1. **`.env.testing_mysql`** - Environment configuration untuk testing
2. **`test-fresh.sh`** - Shell script untuk testing
3. **`restore-env.sh`** - Shell script untuk restore environment
4. **`app/Console/Commands/TestFreshCommand.php`** - Artisan command

## 🗄️ Database Setup

-   **Database Asli**: `bookkeeping-db` (tetap aman, tidak berubah)
-   **Database Testing**: `bookkeeping-db_test` (akan dibuat otomatis)
-   **Kredensial**: Sama dengan database asli (root/pwdpwd8)

## ⚠️ Peringatan

1. **Database testing akan di-reset setiap kali menjalankan command**
2. **Jangan lupa restore environment setelah testing**
3. **File `.env.backup` akan dibuat otomatis untuk backup**

## 🔄 Workflow Testing

```bash
# 1. Jalankan testing dengan data fresh
php artisan test:fresh

# 2. Test aplikasi Anda dengan data bersih
# ... lakukan testing ...

# 3. Restore kembali ke environment asli
php artisan test:fresh --restore
```

## ✅ Verifikasi

Setelah menjalankan `php artisan test:fresh`, Anda dapat verify dengan:

```bash
# Check database yang sedang digunakan
php artisan tinker
>>> config('database.connections.mysql.database')
=> "bookkeeping-db_test"

# Check data di database
>>> \App\Models\User::count()
>>> \App\Models\Account::count()
>>> \App\Models\Category::count()
```

## 🛠️ Troubleshooting

Jika ada error:

1. Pastikan MySQL service berjalan
2. Pastikan kredensial database benar
3. Pastikan database user memiliki permission untuk create database
4. Jika ada masalah, restore environment: `php artisan test:fresh --restore`
