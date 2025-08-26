# ☕ Finansial Kopi Flutter

Sistem manajemen keuangan modern khusus untuk bisnis kopi yang dibangun dengan **Laravel 12** dan **Filament v4**. **Finansial Kopi Flutter** menyediakan solusi komprehensif untuk mengelola keuangan bisnis kopi dengan interface admin yang intuitif, REST API yang robust, dan sistem testing yang lengkap.

![Laravel](https://img.shields.io/badge/Laravel-12.25-red?style=flat-square&logo=laravel)
![Filament](https://img.shields.io/badge/Filament-4.0.3-orange?style=flat-square)
![PHP](https://img.shields.io/badge/PHP-8.3.22-blue?style=flat-square&logo=php)
![Pest](https://img.shields.io/badge/Pest-3.8.4-green?style=flat-square)
![Tests](https://img.shields.io/badge/Tests-51%20passing-brightgreen?style=flat-square)
![License](https://img.shields.io/badge/License-MIT-green?style=flat-square)

## ✨ Fitur Unggulan

### ☕ Khusus Bisnis Kopi

-   **Tema Kopi Terintegrasi**: Interface dan branding yang disesuaikan untuk bisnis kopi
-   **Kategorisasi Khusus**: Template kategori yang relevan untuk coffee shop dan café
-   **Mata Uang Indonesia**: Format Rupiah dengan pemisah ribuan yang sesuai
-   **Multi-outlet Support**: Kelola beberapa lokasi coffee shop dalam satu sistem
-   **Laporan Bisnis Kopi**: Report yang disesuaikan dengan kebutuhan industri kopi

### 🏗️ Arsitektur Enterprise

-   **Laravel 12**: Framework Laravel terbaru dengan PHP 8.3.22
-   **Filament v4**: Admin panel modern dengan Server-Driven UI (SDUI)
-   **Laravel Sanctum**: Autentikasi API yang secure
-   **Multi-tenancy**: Isolasi data user yang lengkap
-   **Enterprise Testing**: 51 test yang passing dengan coverage komprehensif

### 📊 Dashboard & Analytics

-   **Overview Keuangan**: Card pemasukan, pengeluaran, profit bersih, dan total saldo
-   **Widget Saldo Akun**: Overview real-time dari semua saldo akun
-   **Chart Interaktif**: Visualisasi data keuangan
-   **Update Live**: Kalkulasi saldo real-time

### 💰 Manajemen Akun

-   **Multiple Tipe Akun**: Tunai, Bank, E-Wallet, dan tipe lainnya
-   **Auto-calculated Balances**: Update saldo real-time dengan riwayat transaksi
-   **Multi-user Support**: Isolasi data lengkap antar user
-   **Live Formatting**: Auto-formatting untuk nilai mata uang (Rupiah Indonesia)
-   **Business Rules**: Mencegah penghapusan akun saat ada transaksi
-   **Full REST API**: Operasi CRUD lengkap dengan validasi

### 🏷️ Manajemen Kategori

-   **Struktur Hierarki**: Hubungan kategori parent/child
-   **Tipe Pemasukan/Pengeluaran**: Kategori terpisah untuk berbagai tipe transaksi
-   **User Isolation**: Setiap user memiliki kategori sendiri
-   **Unique Constraints**: Mencegah duplikasi kategori per user dan tipe
-   **API Integration**: REST API lengkap dengan filtering dan pencarian

### 📝 Manajemen Transaksi

-   **Filter Tanggal**: Filter transaksi berdasarkan rentang tanggal
-   **Download Report PDF**: Laporan transaksi dalam format PDF dengan filter tanggal
-   **Dual Entry System**: Support transaksi pemasukan dan pengeluaran
-   **File Attachments**: Upload kwitansi dan dokumen pendukung
-   **Rich Metadata**: Catatan, deskripsi, dan kategorisasi
-   **Auto-formatting**: Format mata uang Rupiah Indonesia
-   **Real-time Updates**: Kalkulasi ulang saldo secara instant
-   **Audit Trail**: Tracking riwayat transaksi lengkap

### 🌐 Interface Bahasa Indonesia

-   **UI Bahasa Indonesia**: Seluruh interface menggunakan bahasa Indonesia
-   **Format Lokal**: Tanggal dan mata uang sesuai standar Indonesia
-   **Terminologi Bisnis**: Istilah yang familiar untuk pemilik bisnis Indonesia
-   **Navigation yang Intuitif**: Menu terorganisir secara logis dalam bahasa Indonesia

### 🔐 Keamanan & Autentikasi

-   **Laravel Sanctum**: Autentikasi API production-ready
-   **User Registration**: Onboarding user yang secure
-   **Data Isolation**: User hanya dapat mengakses data mereka sendiri
-   **API Protection**: Semua endpoint dilindungi dengan autentikasi
-   **CSRF Protection**: Perlindungan cross-site request forgery

### 🧪 Testing Production-Ready

-   **Pest PHP v3**: Framework testing modern dengan sintaks ekspresif
-   **51 Passing Tests**: Coverage test komprehensif (256 assertions)
-   **Database Isolation**: Database test terpisah dengan suffix `_test`
-   **Factory System**: Model factory lengkap untuk semua entitas
-   **API Testing**: Coverage endpoint lengkap dengan testing validasi
-   **Custom Helpers**: Helper autentikasi, manajemen waktu, dan assertion
-   **Safe Testing**: Perintah `test:fresh` untuk environment test terisolasi

### 🎨 UI/UX Modern

-   **Filament v4**: Interface admin terbaru dengan komponen modern
-   **Responsive Design**: Pendekatan mobile-first untuk semua device
-   **Navigasi Intuitif**: Item menu yang dikelompokkan secara logis
-   **Live Validation**: Validasi form dan formatting real-time
-   **Alpine.js**: Komponen frontend interaktif
-   **Tailwind CSS**: Styling modern utility-first
-   **Coffee Theme**: Tema visual yang konsisten dengan bisnis kopi

## 🚀 Quick Start

### Prerequisites

-   **PHP 8.3.22** atau lebih tinggi
-   **Composer 2.x**
-   **Node.js 18+** & NPM
-   **Database**: MySQL 8.0+, PostgreSQL 13+, atau SQLite 3.8+

### Setup 1 Menit

```bash
# Clone dan setup
git clone https://github.com/bahrie127/finansial-kopi-flutter.git
cd finansial-kopi-flutter

# Install dependencies
composer install && npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Database setup
php artisan migrate --seed

# Build assets dan start
npm run build && php artisan serve
```

**🎉 Siap!** Kunjungi `http://localhost:8000/admin` untuk mulai menggunakan aplikasi.

## 📋 Instalasi Detail

### Step 1: Clone Repository

```bash
git clone https://github.com/bahrie127/finansial-kopi-flutter.git
cd finansial-kopi-flutter
```

### Step 2: Install Dependencies

```bash
# Backend dependencies
composer install

# Frontend dependencies
npm install
```

### Step 3: Konfigurasi Environment

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Konfigurasi database di file .env
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=finansial_kopi_flutter
# DB_USERNAME=your_username
# DB_PASSWORD=your_password
```

### Step 4: Setup Database

```bash
# Run migrations dan seeders
php artisan migrate --seed

# Atau untuk instalasi fresh
php artisan migrate:fresh --seed
```

### Step 5: Build Assets

```bash
# Production build
npm run build

# Atau untuk development dengan hot reload
npm run dev
```

### Step 6: Start Aplikasi

```bash
# Start Laravel development server
php artisan serve

# Aplikasi akan tersedia di:
# http://localhost:8000/admin
```

### 🧪 Setup Testing (Opsional)

```bash
# Setup testing environment
cp .env.testing.example .env.testing

# Run tests untuk verifikasi setup
./vendor/bin/pest

# Safe testing dengan database terisolasi
php artisan test:fresh
./vendor/bin/pest
php artisan test:fresh --restore
```

## 🛠️ Tech Stack

### Backend

-   **Laravel 12.25.0** - Framework PHP modern
-   **PHP 8.3.22** - PHP terbaru dengan peningkatan performa
-   **Laravel Sanctum** - Autentikasi API
-   **MySQL/PostgreSQL/SQLite** - Support database

### Frontend

-   **Filament v4.0.3** - Framework admin panel modern
-   **Livewire v3.6.4** - Full-stack framework untuk Laravel
-   **Alpine.js** - Framework minimal untuk reactive behavior
-   **Tailwind CSS v4.0.0** - Utility-first CSS framework
-   **Vite** - Frontend tooling generasi terbaru

### Testing & Quality

-   **Pest PHP v3.8.4** - Framework testing modern
-   **Laravel Pint v1.24.0** - Code style fixer
-   **PHPUnit** - Foundation unit testing
-   **51 Tests** - Coverage test komprehensif (256 assertions)

### Development Tools

-   **Laravel Boost** - Tools percepatan development
-   **Laravel Prompts v0.3.6** - Command-line prompts yang indah
-   **Composer** - Manajemen dependency
-   **NPM/Node.js** - Manajemen package frontend

## 📁 Struktur Project

```
app/
├── Filament/Admin/
│   ├── Resources/          # Filament resources
│   │   ├── Accounts/      # Manajemen akun
│   │   ├── Categories/    # Manajemen kategori
│   │   └── Transactions/  # Manajemen transaksi
│   └── Widgets/           # Dashboard widgets
├── Http/
│   ├── Controllers/Api/   # API controllers
│   └── Requests/          # Form request validation
├── Models/                # Eloquent models
└── Console/Commands/      # Custom Artisan commands

database/
├── migrations/           # Database migrations
├── seeders/             # Database seeders
└── factories/           # Model factories

tests/
├── Feature/             # Feature tests
│   └── Api/            # API endpoint tests
├── Unit/               # Unit tests
└── Pest.php           # Test configuration & helpers

docs/
├── testing-setup.md    # Panduan testing environment
└── database-testing.md # Panduan database testing

public/
└── images/
    ├── logo-kopi-flutter.svg  # Logo aplikasi
    └── favicon-kopi.svg       # Favicon aplikasi
```

## 🔧 Konfigurasi

### Konfigurasi Timezone

Aplikasi dikonfigurasi untuk timezone Indonesia (WIB/Asia/Jakarta). Anda dapat memodifikasi di `config/app.php`:

```php
'timezone' => 'Asia/Jakarta',
```

### Setting Mata Uang

Saat ini dikonfigurasi untuk Rupiah Indonesia (IDR) dengan format pemisah ribuan titik. Modifikasi setting mata uang di konfigurasi tabel sesuai kebutuhan.

### Branding & Logo

Branding "Finansial Kopi Flutter" dikonfigurasi di:

-   **AdminPanelProvider**: Nama brand dan logo
-   **Tema Warna**: Palet warna coklat kopi (primary colors)
-   **Logo SVG**: Logo responsif dengan tema kopi
-   **Favicon**: Ikon kopi untuk browser tab

## 🎯 Target Pengguna

**Finansial Kopi Flutter** dirancang khusus untuk:

### ☕ Pemilik Coffee Shop

-   Warung kopi tradisional yang ingin modernisasi pencatatan keuangan
-   Coffee shop kecil hingga menengah
-   Kedai kopi specialty yang butuh tracking detail

### 🏪 Operator Café Chain

-   Jaringan café dengan multiple outlets
-   Franchise kopi yang butuh standarisasi pencatatan
-   Coffee shop modern dengan sistem terintegrasi

### 📱 Coffee Entrepreneurs

-   Startup bisnis kopi yang menggunakan teknologi Flutter
-   Coffee roaster yang perlu manajemen keuangan
-   Online coffee business yang butuh tracking komprehensif

### 💼 Konsultan Bisnis Kopi

-   Profesional yang mengelola keuangan multiple klien kopi
-   Akuntan yang spesialisasi industri F&B
-   Business advisor untuk sektor coffee industry

## 🌟 Keunggulan Kompetitif

### 🎨 Branding Khusus Kopi

-   Logo dan tema visual yang disesuaikan untuk industri kopi
-   Terminologi dan kategori yang familiar untuk pelaku bisnis kopi
-   Color scheme coklat kopi yang warm dan professional

### 🇮🇩 Lokalisasi Indonesia

-   Interface 100% bahasa Indonesia
-   Format mata uang Rupiah dengan pemisah ribuan
-   Timezone dan format tanggal Indonesia (WIB)

### 📊 Reports Bisnis Kopi

-   Template laporan yang disesuaikan dengan kebutuhan coffee shop
-   Filter tanggal untuk analisis periode tertentu
-   Export PDF dengan branding konsisten

### � Teknologi Modern

-   Built dengan Laravel 12 dan Filament v4 terbaru
-   Responsive design untuk akses mobile dan desktop
-   API-ready untuk integrasi dengan sistem POS atau aplikasi Flutter

## 🚀 Roadmap Pengembangan

### 🎯 Versi Mendatang

#### v2.0 - Coffee Business Features

-   📦 **Inventory Management**: Stock bahan baku kopi
-   👥 **Customer Management**: Database pelanggan dan loyalty program
-   📋 **Menu Management**: Katalog produk dengan harga
-   📈 **Sales Analytics**: Report penjualan dan profit margin

#### v2.1 - Multi-Outlet Support

-   🏪 **Branch Management**: Kelola multiple lokasi coffee shop
-   📊 **Consolidated Reports**: Laporan gabungan semua outlet
-   👨‍💼 **Role Management**: Akses berbeda untuk manager dan staff
-   🔄 **Data Sync**: Sinkronisasi data antar outlet

#### v2.2 - Integration Ready

-   🔌 **POS Integration**: Koneksi dengan sistem Point of Sale
-   📱 **Flutter App API**: API lengkap untuk aplikasi mobile Flutter
-   💳 **Payment Gateway**: Integrasi dengan payment processor
-   📧 **Email Reports**: Automatic email reporting

### 💡 Fitur Request

Punya ide fitur untuk bisnis kopi Anda? Buat issue di GitHub atau hubungi developer!

-   `q` - Search by account name
-   `is_active` - Filter by active status (1/0)

**Request Body (Create/Update):**

```json
{
    "name": "Kas Utama Coffee Shop",
    "type": "bank",
    "starting_balance": 5000000.0,
    "is_active": true
}
```

### 🏷️ Categories API

```http
GET    /api/categories            # List user categories
POST   /api/categories            # Create new category
GET    /api/categories/{id}       # Get specific category
PUT    /api/categories/{id}       # Update category
DELETE /api/categories/{id}       # Delete category
```

**Category Types:** `income`, `expense`

**Query Parameters:**

-   `q` - Search by category name
-   `type` - Filter by type (income/expense)
-   `parent_id` - Filter by parent category

**Request Body (Create/Update):**

```json
{
    "name": "Belanja Peralatan Kopi",
    "type": "expense",
    "parent_id": null
}
```

### 📝 Transactions API

```http
GET    /api/transactions         # List user transactions
POST   /api/transactions         # Create new transaction
GET    /api/transactions/{id}    # Get specific transaction
PUT    /api/transactions/{id}    # Update transaction
DELETE /api/transactions/{id}    # Delete transaction
```

**Transaction Types:** `income`, `expense`

**Query Parameters:**

-   `q` - Search by transaction note/description
-   `type` - Filter by type (income/expense)
-   `account_id` - Filter by account
-   `category_id` - Filter by category
-   `date_from` - Filter from date (Y-m-d format)
-   `date_to` - Filter to date (Y-m-d format)

**Request Body (Create/Update):**

```json
{
    "account_id": 1,
    "category_id": 2,
    "type": "expense",
    "amount": 125000.0,
    "note": "Pembelian biji kopi arabica",
    "transaction_date": "2024-01-15"
}
```

### ✅ API Features

-   🔒 **Secure Authentication**: Laravel Sanctum with bearer tokens
-   👤 **User Isolation**: Each user only accesses their own data
-   ✅ **Input Validation**: Comprehensive validation for all endpoints
-   🚨 **Error Handling**: Consistent JSON error responses
-   🛡️ **Business Rules**: Enforced (e.g., prevent deletion with dependencies)
-   🔍 **Filtering & Search**: Query parameters for data filtering
-   📊 **Structured Responses**: Consistent JSON structure across all endpoints
-   📅 **Date Range Filtering**: Filter transactions by date range for reports

### 📄 Response Format

**Success Response:**

```json
{
    "data": {
        "id": 1,
        "name": "Kas Utama Coffee Shop",
        "type": "bank",
        "starting_balance": "5000000.00",
        "current_balance": "6250000.00",
        "is_active": true,
        "created_at": "2024-01-15T10:30:00.000000Z",
        "updated_at": "2024-01-15T10:30:00.000000Z"
    }
}
```

**Error Response:**

```json
{
    "message": "Validation failed",
    "errors": {
        "name": ["The name field is required."],
        "type": ["The selected type is invalid."]
    }
}
```

## 🧪 Testing

**Finansial Kopi Flutter** features a **production-ready testing environment** with comprehensive coverage and modern testing practices.

### 📊 Test Coverage

```bash
✅ 51 Tests Passing (256 Assertions)
⚡ Duration: ~1.04s
🎯 100% API Endpoint Coverage
🔒 Security & Authentication Testing
📝 Validation Rule Coverage
🏗️ Database Constraint Testing
```

### 🚀 Quick Testing

```bash
# Run all tests
./vendor/bin/pest

# Run specific test types
./vendor/bin/pest tests/Unit/
./vendor/bin/pest tests/Feature/
./vendor/bin/pest tests/Feature/Api/

# Run with verbose output
./vendor/bin/pest --verbose

# Run specific test file
./vendor/bin/pest tests/Feature/Api/AccountsApiTest.php
```

### 🛡️ Safe Database Testing

Use the **isolated testing environment** to prevent data loss:

```bash
# Setup test database (creates separate DB with _test suffix)
php artisan test:fresh

# Run your tests safely
./vendor/bin/pest

# Restore original environment
php artisan test:fresh --restore
```

**Why Use Safe Testing?**

-   ✅ **Data Protection**: Your production data remains untouched
-   ✅ **Isolated Environment**: Tests run in completely separate database
-   ✅ **Repeatable**: Fresh database state for each test run
-   ✅ **Reversible**: Easy restoration to original environment

### 🧪 Test Categories

#### **Unit Tests** (8 tests)

-   Model relationships and business logic
-   Database constraints and validation
-   Factory system verification

#### **Feature Tests** (42 tests)

-   Complete API endpoint testing
-   Authentication and authorization
-   User data isolation
-   JSON structure validation

#### **API Tests** (25 tests)

-   **Accounts API**: CRUD operations, filtering, validation
-   **Categories API**: Hierarchical structure, user isolation
-   **Authentication**: Registration, login, token management

### 🔧 Testing Features

#### **Modern Testing Framework**

-   **Pest PHP v3.8.4**: Expressive and developer-friendly syntax
-   **Custom Helpers**: `actingAsApi()`, `freezeTime()`, `toBeValidationError()`
-   **Database Isolation**: SQLite in-memory for fast, isolated tests

#### **Comprehensive Test Scenarios**

-   ✅ **CRUD Operations**: Create, Read, Update, Delete for all resources
-   ✅ **Validation Testing**: All form validation rules covered
-   ✅ **Security Testing**: Unauthorized access prevention
-   ✅ **Business Rules**: Complex logic and constraints
-   ✅ **Data Types**: JSON structure and type validation
-   ✅ **Time Consistency**: Frozen timestamps for reliable testing

#### **Factory System**

Complete model factories for all entities:

```php
User::factory()->create()
Account::factory()->for($user)->create()
Category::factory()->for($user)->create()
Transaction::factory()->for($user)->create()
```

### 📚 Testing Documentation

For detailed testing setup and best practices:

-   📖 [Testing Setup Guide](docs/testing-setup.md)
-   📖 [Database Testing Guide](docs/database-testing.md)

### 🔍 Example Test

```php
test('Account API should create account with valid data', function () {
    $user = User::factory()->create();

    actingAsApi($user)
        ->postJson('/api/accounts', [
            'name' => 'Test Account',
            'type' => 'bank',
            'starting_balance' => 1000.00
        ])
        ->assertStatus(201)
        ->assertJsonStructure(['data' => ['id', 'name', 'type']])
        ->assertJson(['data' => ['name' => 'Test Account']]);
});
```

## 🚀 Usage Guide

### 👤 Getting Started

1. **Register Your Account**

    - Visit `http://localhost:8000/admin`
    - Click "Register" to create your account
    - Login with your credentials

2. **Setup Your Financial Structure**
    ```
    📊 Dashboard → View your financial overview
    💰 Accounts → Add your bank accounts, cash, e-wallets
    🏷️ Categories → Create income and expense categories
    📝 Transactions → Start recording your financial activities
    ```

### 💰 Account Management

**Adding Accounts:**

-   Navigate to **Accounts** → **New Account**
-   Choose account type: Cash, Bank, E-Wallet, Credit Card, Investment, Loan
-   Set starting balance and activation status
-   Accounts auto-calculate current balance based on transactions

**Account Types:**

-   💵 **Cash**: Uang tunai di kasir atau brankas
-   🏦 **Bank**: Rekening giro, tabungan, rekening bisnis
-   📱 **E-Wallet**: Dompet digital (GoPay, OVO, Dana, ShopeePay)
-   💳 **Credit Card**: Kartu kredit untuk pembelian bisnis
-   📈 **Investment**: Saham, obligasi, reksadana
-   💸 **Loan**: Pinjaman usaha, KUR, modal investasi

### 🏷️ Category System

**Creating Categories:**

-   **Income Categories**: Penjualan kopi, Layanan catering, Konsultasi barista, Hadiah
-   **Expense Categories**: Bahan baku kopi, Peralatan, Sewa tempat, Marketing
-   **Hierarchical Structure**: Create parent categories with sub-categories
-   **User-Specific**: Each user maintains their own category system

**Parent-Child Example:**

```
📂 Bahan Baku (Parent)
   ├── ☕ Biji Kopi
   ├── 🥛 Susu & Krimer
   └── 🍯 Sirup & Topping
```

### 📝 Transaction Workflow

1. **Create Transaction**

    - Select account and category
    - Enter amount (auto-formatted to IDR)
    - Add description and notes
    - Upload receipts/attachments
    - Filter by date range for reporting

2. **Real-time Updates**
    - Account balances update instantly
    - Dashboard reflects changes immediately
    - Transaction history maintained
    - PDF reports with date filtering

### 🔌 API Integration

**Authentication:**

```bash
# Register/Login via API
curl -X POST /api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"user@example.com","password":"password"}'

# Use returned token in subsequent requests
curl -X GET /api/accounts \
  -H "Authorization: Bearer {your-token}"
```

**Common API Usage:**

```bash
# Get all accounts
GET /api/accounts

# Create new account
POST /api/accounts
{
  "name": "Akun Tabungan Kopi",
  "type": "bank",
  "starting_balance": 5000000.00
}

# Filter categories by type
GET /api/categories?type=expense&q=bahan

# Get transactions with date filter
GET /api/transactions?date_from=2024-01-01&date_to=2024-01-31
```

### 🧪 Development & Testing

**Running Tests:**

```bash
# Full test suite
./vendor/bin/pest

# Test specific functionality
./vendor/bin/pest tests/Feature/Api/AccountsApiTest.php

# Safe database testing
php artisan test:fresh
./vendor/bin/pest
php artisan test:fresh --restore
```

**Development Commands:**

```bash
# Start development environment
npm run dev          # Frontend hot reload
php artisan serve    # Backend server

# Code quality
vendor/bin/pint      # Format code
php artisan test     # Run tests

# Database management
php artisan migrate:fresh --seed  # Fresh database with sample data
php artisan tinker              # Interactive PHP console
```

### 📊 Advanced Features

**Currency Formatting:**

-   Automatic Indonesian Rupiah (IDR) formatting
-   Thousand separators and decimal handling
-   Configurable in application settings

**Data Export:**

-   Transaction history export dengan filter tanggal
-   Account balance reports untuk analisis keuangan
-   Category-wise spending analysis untuk optimasi biaya

**Search & Filtering:**

-   Powerful search across all resources
-   Multi-criteria filtering untuk transaksi
-   Date range selections untuk laporan periodik

### 🛠️ Customization

**Timezone Configuration:**

```php
// config/app.php
'timezone' => 'Asia/Jakarta',  // WIB timezone
```

**Currency Settings:**

-   Modify currency display in table configurations
-   Adjust decimal places and formatting
-   Localization support for different regions

## 🤝 Contributing

We welcome contributions! **Finansial Kopi Flutter** is built to be extensible and maintainable. Here's how you can contribute:

### 🔧 Development Setup

```bash
# Fork and clone the repository
git clone https://github.com/your-username/finansial-kopi-flutter.git
cd finansial-kopi-flutter

# Install dependencies
composer install && npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Setup database
php artisan migrate:fresh --seed

# Start development servers
npm run dev        # Frontend with hot reload
php artisan serve  # Backend server
```

### 🧪 Testing Requirements

**All contributions must include tests:**

```bash
# Run tests before submitting
./vendor/bin/pest

# Ensure code style compliance
vendor/bin/pint

# Test with isolated database
php artisan test:fresh
./vendor/bin/pest
php artisan test:fresh --restore
```

### 📝 Contribution Guidelines

1. **Fork** the repository
2. **Create** a feature branch: `git checkout -b feature/amazing-feature`
3. **Write** tests for your changes
4. **Ensure** all tests pass: `./vendor/bin/pest`
5. **Format** code: `vendor/bin/pint`
6. **Commit** changes: `git commit -m 'Add amazing feature'`
7. **Push** to branch: `git push origin feature/amazing-feature`
8. **Submit** a Pull Request

### 🎯 Areas for Contribution

-   📊 **Dashboard Widgets**: New chart types, analytics
-   💰 **Account Types**: Additional account categories
-   📱 **Mobile Experience**: Mobile-specific improvements
-   🌍 **Internationalization**: Multi-language support
-   📈 **Reporting**: Advanced reporting features
-   🔗 **Integrations**: Bank API connections, import tools
-   🧪 **Testing**: Additional test coverage
-   📚 **Documentation**: Guides, tutorials, API docs

### 🔍 Code Standards

-   **PHP 8.3+** features encouraged
-   **Laravel 12** best practices
-   **Filament v4** conventions
-   **Pest PHP** for testing
-   **Laravel Pint** for code formatting

### 🐛 Bug Reports

**Before submitting a bug report:**

1. **Search** existing issues
2. **Test** with latest version
3. **Provide** detailed reproduction steps
4. **Include** environment details (PHP, Laravel, database versions)

**Bug Report Template:**

```
**Environment:**
- PHP Version: 8.3.22
- Laravel Version: 12.25.0
- Database: MySQL 8.0

**Steps to Reproduce:**
1. Navigate to...
2. Click on...
3. Enter...

**Expected Behavior:**
Should show...

**Actual Behavior:**
Shows error...

**Screenshots/Logs:**
[Attach relevant screenshots or error logs]
```

### 💡 Feature Requests

We love new ideas! **Feature requests should include:**

-   **Use Case**: Why is this feature needed?
-   **Proposed Solution**: How should it work?
-   **Alternatives Considered**: Other approaches evaluated
-   **Implementation Details**: Technical considerations

### 👥 Community

-   💬 **Discussions**: Use GitHub Discussions for questions
-   🐛 **Issues**: Report bugs and request features
-   📖 **Wiki**: Community-driven documentation
-   🎥 **YouTube**: [@codewithbahri](https://youtube.com/@codewithbahri) for tutorials

## ‍💻 Tentang Developer

**Bahri** - Full Stack Developer & Programming Instructor

Saya passionate dalam membangun aplikasi yang berguna dan sharing knowledge dengan komunitas developer. **Finansial Kopi Flutter** menunjukkan kemampuan Laravel dan Filament modern sambil menyelesaikan kebutuhan manajemen keuangan bisnis kopi dengan kualitas kode enterprise-grade.

### 🌐 Connect dengan Saya

-   **GitHub**: [github.com/bahrie127](https://github.com/bahrie127) - Open source projects
-   **LinkedIn**: [linkedin.com/in/bahrie](https://linkedin.com/in/bahrie) - Professional network
-   **YouTube**: [@codewithbahri](https://youtube.com/@codewithbahri) - Laravel tutorials & tips
-   **Instagram**: [@codewithbahri](https://instagram.com/codewithbahri) - Development journey
-   **WhatsApp Channel**: [Programming Tips & Tutorials](https://whatsapp.com/channel/0029Vb0ucRx7oQhVmCVypC1Y)
-   **WhatsApp**: [+62 856-4089-9224](https://wa.me/6285640899224) - Contact langsung

### 🎯 Tujuan Project

**Finansial Kopi Flutter** dibuat untuk mendemonstrasikan:

-   ✅ **Laravel 12 Modern** features dan best practices
-   ✅ **Filament v4** server-driven UI capabilities
-   ✅ **Enterprise-grade Testing** dengan Pest PHP
-   ✅ **Production-ready Architecture** dengan proper separation of concerns
-   ✅ **Comprehensive API Design** dengan Laravel Sanctum
-   ✅ **Real-world Application** yang menyelesaikan masalah bisnis nyata
-   ✅ **Indonesian Localization** untuk market lokal
-   ✅ **Industry-Specific Solutions** untuk bisnis kopi

### 💝 Support Project

Jika project ini membantu Anda atau bisnis Anda:

-   ⭐ **Star repository** di GitHub
-   🐛 **Report bugs** dan suggest improvements
-   💡 **Contribute features** melalui pull requests
-   📖 **Improve documentation** dan guides
-   🔗 **Share dengan others** yang mungkin terbantu
-   📢 **Follow content saya** untuk Laravel tutorials lebih banyak

### 📚 Learning Resources

**Free Laravel Content:**

-   🎥 **YouTube Channel**: [@codewithbahri](https://youtube.com/@codewithbahri)
-   📱 **WhatsApp Channel**: [Programming Tips](https://whatsapp.com/channel/0029Vb0ucRx7oQhVmCVypC1Y)
-   📝 **Articles & Tutorials**: Available di social media saya
-   💻 **Open Source Projects**: Check GitHub repositories saya

**Topics yang Dicover:**

-   Laravel 12 features dan updates
-   Filament v4 development
-   API development dengan Laravel Sanctum
-   Modern PHP 8.3 practices
-   Testing dengan Pest PHP
-   Database design dan optimization
-   Indonesian localization techniques
-   Coffee shop business solutions

### ☕ Khusus Komunitas Bisnis Kopi

-   🎓 **Workshop**: Laravel untuk coffee shop owners
-   📊 **Business Consulting**: Setup sistem keuangan coffee shop
-   🔧 **Custom Development**: Fitur khusus sesuai kebutuhan bisnis Anda
-   📱 **Flutter Integration**: Pengembangan mobile app terintegrasi

---

## 📄 License

Project ini adalah open-source software dengan lisensi [MIT license](https://opensource.org/licenses/MIT).

```
MIT License

Copyright (c) 2024 Bahri

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
```

---

**Made with ❤️ and ☕ by [Bahri](https://github.com/bahrie127)**

_Membangun masa depan web development, satu aplikasi Laravel untuk bisnis kopi pada satu waktu._ 🚀☕

### 🎉 Terima Kasih

Special thanks kepada:

-   ☕ **Coffee shop owners** yang memberikan insight bisnis
-   👥 **Laravel community** Indonesia
-   🛠️ **Filament team** untuk framework yang amazing
-   🧪 **Pest PHP team** untuk testing framework yang elegant
-   🇮🇩 **Indonesian developers** yang selalu support

Mari bersama-sama memajukan industri kopi Indonesia dengan teknologi! ☕🇮🇩🚀
