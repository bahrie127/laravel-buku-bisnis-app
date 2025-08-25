# Buku Bisnis

A modern, enterprise-grade multi-user bookkeeping and financial management system built with **Laravel 12** and **Filament v4**. **Buku Bisnis** provides a comprehensive solution for managing personal or business finances with an intuitive admin interface, robust REST API, and comprehensive testing suite.

![Laravel](https://img.shields.io/badge/Laravel-12.25-red?style=flat-square&logo=laravel)
![Filament](https://img.shields.io/badge/Filament-4.0.3-orange?style=flat-square)
![PHP](https://img.shields.io/badge/PHP-8.3.22-blue?style=flat-square&logo=php)
![Pest](https://img.shields.io/badge/Pest-3.8.4-green?style=flat-square)
![Tests](https://img.shields.io/badge/Tests-51%20passing-brightgreen?style=flat-square)
![License](https://img.shields.io/badge/License-MIT-green?style=flat-square)

## ✨ Features

### 🏗️ Enterprise Architecture

-   **Laravel 12**: Latest Laravel framework with modern PHP 8.3.22
-   **Filament v4**: Modern admin panel with Server-Driven UI (SDUI)
-   **Laravel Sanctum**: Secure API authentication
-   **Multi-tenancy**: Complete user data isolation
-   **Enterprise Testing**: 51 passing tests with comprehensive coverage

### 📊 Dashboard & Analytics

-   **Financial Overview**: Income, expenses, net profit, and total balance cards with clickable navigation
-   **Account Balance Widget**: Real-time overview of all account balances
-   **Interactive Charts**: Visual representation of financial data
-   **Live Updates**: Real-time balance calculations

### 💰 Account Management

-   **Multiple Account Types**: Cash, Bank, E-Wallet, Credit Card, Investment, Loan
-   **Auto-calculated Balances**: Real-time balance updates with transaction history
-   **Multi-user Support**: Complete data isolation between users
-   **Live Formatting**: Auto-formatting for monetary values (Indonesian Rupiah)
-   **Business Rules**: Prevent account deletion when transactions exist
-   **Full REST API**: Complete CRUD operations with validation

### 🏷️ Category Management

-   **Hierarchical Structure**: Parent/child category relationships
-   **Income/Expense Types**: Separate categories for different transaction types
-   **User Isolation**: Each user has their own categories
-   **Unique Constraints**: Prevent duplicate categories per user and type
-   **API Integration**: Full REST API with filtering and search capabilities

### 📝 Transaction Management

-   **Dual Entry System**: Income and expense transaction support
-   **File Attachments**: Upload receipts and supporting documents
-   **Rich Metadata**: Notes, descriptions, and categorization
-   **Auto-formatting**: Indonesian Rupiah currency formatting
-   **Real-time Updates**: Instant balance recalculations
-   **Audit Trail**: Complete transaction history tracking

### 🔐 Security & Authentication

-   **Laravel Sanctum**: Production-ready API authentication
-   **User Registration**: Secure user onboarding
-   **Data Isolation**: Users can only access their own data
-   **API Protection**: All endpoints secured with authentication
-   **CSRF Protection**: Cross-site request forgery protection

### 🧪 Production-Ready Testing

-   **Pest PHP v3**: Modern testing framework with expressive syntax
-   **51 Passing Tests**: Comprehensive test coverage (256 assertions)
-   **Database Isolation**: Separate test database with `_test` suffix
-   **Factory System**: Complete model factories for all entities
-   **API Testing**: Full endpoint coverage with validation testing
-   **Custom Helpers**: Authentication, time management, and assertion helpers
-   **Safe Testing**: `test:fresh` command for isolated test environments

### 🎨 Modern UI/UX

-   **Filament v4**: Latest admin interface with modern components
-   **Responsive Design**: Mobile-first approach for all devices
-   **Intuitive Navigation**: Logically grouped menu items
-   **Live Validation**: Real-time form validation and formatting
-   **Alpine.js**: Interactive frontend components
-   **Tailwind CSS**: Modern, utility-first styling

## 🚀 Quick Start

### Prerequisites

-   **PHP 8.3.22** or higher
-   **Composer 2.x**
-   **Node.js 18+** & NPM
-   **Database**: MySQL 8.0+, PostgreSQL 13+, or SQLite 3.8+

### 1-Minute Setup

```bash
# Clone and setup
git clone https://github.com/bahrie127/laravel-bookkeeping-system.git
cd laravel-bookkeeping-system

# Install dependencies
composer install && npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Database setup
php artisan migrate --seed

# Build assets and start
npm run build && php artisan serve
```

**🎉 Ready!** Visit `http://localhost:8000/admin` to start using the application.

## 📋 Detailed Installation

### Step 1: Clone Repository

```bash
git clone https://github.com/bahrie127/laravel-bookkeeping-system.git
cd laravel-bookkeeping-system
```

### Step 2: Install Dependencies

```bash
# Backend dependencies
composer install

# Frontend dependencies
npm install
```

### Step 3: Environment Configuration

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure your database in .env file
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=bookkeeping
# DB_USERNAME=your_username
# DB_PASSWORD=your_password
```

### Step 4: Database Setup

```bash
# Run migrations and seeders
php artisan migrate --seed

# Or for fresh installation
php artisan migrate:fresh --seed
```

### Step 5: Build Assets

```bash
# Production build
npm run build

# Or for development with hot reload
npm run dev
```

### Step 6: Start Application

```bash
# Start Laravel development server
php artisan serve

# Application will be available at:
# http://localhost:8000/admin
```

### 🧪 Testing Setup (Optional)

```bash
# Setup testing environment
cp .env.testing.example .env.testing

# Run tests to verify setup
./vendor/bin/pest

# Safe testing with isolated database
php artisan test:fresh
./vendor/bin/pest
php artisan test:fresh --restore
```

## 🛠️ Tech Stack

### Backend

-   **Laravel 12.25.0** - Modern PHP framework
-   **PHP 8.3.22** - Latest PHP with performance improvements
-   **Laravel Sanctum** - API authentication
-   **MySQL/PostgreSQL/SQLite** - Database support

### Frontend

-   **Filament v4.0.3** - Modern admin panel framework
-   **Livewire v3.6.4** - Full-stack framework for Laravel
-   **Alpine.js** - Minimal framework for reactive behavior
-   **Tailwind CSS v4.0.0** - Utility-first CSS framework
-   **Vite** - Next generation frontend tooling

### Testing & Quality

-   **Pest PHP v3.8.4** - Modern testing framework
-   **Laravel Pint v1.24.0** - Code style fixer
-   **PHPUnit** - Unit testing foundation
-   **51 Tests** - Comprehensive test coverage (256 assertions)

### Development Tools

-   **Laravel Boost** - Development acceleration tools
-   **Laravel Prompts v0.3.6** - Beautiful command-line prompts
-   **Composer** - Dependency management
-   **NPM/Node.js** - Frontend package management

## 📁 Project Structure

```
app/
├── Filament/Admin/
│   ├── Resources/          # Filament resources
│   │   ├── Accounts/
│   │   ├── Categories/
│   │   └── Transactions/
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
├── testing-setup.md    # Testing environment guide
└── database-testing.md # Database testing guide
```

## 🔧 Configuration

### Timezone Configuration

The application is configured for Indonesian timezone (WIB/Asia/Jakarta). You can modify this in `config/app.php`:

```php
'timezone' => 'Asia/Jakarta',
```

### Currency Settings

Currently configured for Indonesian Rupiah (IDR). Modify currency settings in the table configurations as needed.

## 🔌 API Reference

The application provides a comprehensive **REST API** with Laravel Sanctum authentication for programmatic access to all features.

### 🔐 Authentication Endpoints

```http
POST   /api/auth/register    # User registration
POST   /api/auth/login       # User login (returns bearer token)
GET    /api/auth/me          # Get current authenticated user
POST   /api/auth/logout      # User logout (invalidate token)
```

**Authentication:** All API endpoints (except auth) require `Authorization: Bearer {token}` header.

### 💰 Accounts API

```http
GET    /api/accounts              # List user accounts
POST   /api/accounts              # Create new account
GET    /api/accounts/{id}         # Get specific account
PUT    /api/accounts/{id}         # Update account
DELETE /api/accounts/{id}         # Delete account
```

**Account Types:** `cash`, `bank`, `e_wallet`, `credit_card`, `investment`, `loan`

**Query Parameters:**

-   `q` - Search by account name
-   `is_active` - Filter by active status (1/0)

**Request Body (Create/Update):**

```json
{
    "name": "Main Bank Account",
    "type": "bank",
    "starting_balance": 1000000.0,
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
    "name": "Food & Dining",
    "type": "expense",
    "parent_id": null
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

### 📄 Response Format

**Success Response:**

```json
{
    "data": {
        "id": 1,
        "name": "Main Bank Account",
        "type": "bank",
        "starting_balance": "1000000.00",
        "current_balance": "1250000.00",
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

**Buku Bisnis** features a **production-ready testing environment** with comprehensive coverage and modern testing practices.

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

-   💵 **Cash**: Physical cash on hand
-   🏦 **Bank**: Checking, savings, business accounts
-   📱 **E-Wallet**: Digital wallets (GoPay, OVO, Dana)
-   💳 **Credit Card**: Credit card accounts
-   📈 **Investment**: Stocks, bonds, mutual funds
-   💸 **Loan**: Mortgages, personal loans

### 🏷️ Category System

**Creating Categories:**

-   **Income Categories**: Salary, Business, Investments, Gifts
-   **Expense Categories**: Food, Transportation, Housing, Entertainment
-   **Hierarchical Structure**: Create parent categories with sub-categories
-   **User-Specific**: Each user maintains their own category system

**Parent-Child Example:**

```
📂 Transportation (Parent)
   ├── 🚗 Car Maintenance
   ├── ⛽ Fuel
   └── 🚌 Public Transport
```

### 📝 Transaction Workflow

1. **Create Transaction**

    - Select account and category
    - Enter amount (auto-formatted to IDR)
    - Add description and notes
    - Upload receipts/attachments

2. **Real-time Updates**
    - Account balances update instantly
    - Dashboard reflects changes immediately
    - Transaction history maintained

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
  "name": "Savings Account",
  "type": "bank",
  "starting_balance": 5000000.00
}

# Filter categories by type
GET /api/categories?type=expense&q=food
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

-   Transaction history export
-   Account balance reports
-   Category-wise spending analysis

**Search & Filtering:**

-   Powerful search across all resources
-   Multi-criteria filtering
-   Date range selections

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

We welcome contributions! **Buku Bisnis** is built to be extensible and maintainable. Here's how you can contribute:

### 🔧 Development Setup

```bash
# Fork and clone the repository
git clone https://github.com/your-username/laravel-bookkeeping-system.git
cd laravel-bookkeeping-system

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

## ‍💻 About the Developer

**Bahri** - Full Stack Developer & Programming Instructor

I'm passionate about building useful applications and sharing knowledge with the developer community. **Buku Bisnis** showcases modern Laravel and Filament capabilities while solving real-world financial management needs with enterprise-grade code quality.

### 🌐 Connect with Me

-   **GitHub**: [github.com/bahrie127](https://github.com/bahrie127) - Open source projects
-   **LinkedIn**: [linkedin.com/in/bahrie](https://linkedin.com/in/bahrie) - Professional network
-   **YouTube**: [@codewithbahri](https://youtube.com/@codewithbahri) - Laravel tutorials & tips
-   **Instagram**: [@codewithbahri](https://instagram.com/codewithbahri) - Development journey
-   **WhatsApp Channel**: [Programming Tips & Tutorials](https://whatsapp.com/channel/0029Vb0ucRx7oQhVmCVypC1Y)
-   **WhatsApp**: [+62 856-4089-9224](https://wa.me/6285640899224) - Direct contact

### 🎯 Project Goals

**Buku Bisnis** was created to demonstrate:

-   ✅ **Modern Laravel 12** features and best practices
-   ✅ **Filament v4** server-driven UI capabilities
-   ✅ **Enterprise-grade Testing** with Pest PHP
-   ✅ **Production-ready Architecture** with proper separation of concerns
-   ✅ **Comprehensive API Design** with Laravel Sanctum
-   ✅ **Real-world Application** solving actual business problems

### 💝 Support the Project

If this project helps you or your business:

-   ⭐ **Star the repository** on GitHub
-   🐛 **Report bugs** and suggest improvements
-   💡 **Contribute features** through pull requests
-   📖 **Improve documentation** and guides
-   🔗 **Share with others** who might benefit
-   📢 **Follow my content** for more Laravel tutorials

### 📚 Learning Resources

**Free Laravel Content:**

-   🎥 **YouTube Channel**: [@codewithbahri](https://youtube.com/@codewithbahri)
-   📱 **WhatsApp Channel**: [Programming Tips](https://whatsapp.com/channel/0029Vb0ucRx7oQhVmCVypC1Y)
-   📝 **Articles & Tutorials**: Available on my social media
-   💻 **Open Source Projects**: Check my GitHub repositories

**Topics Covered:**

-   Laravel 12 features and updates
-   Filament v4 development
-   API development with Laravel Sanctum
-   Modern PHP 8.3 practices
-   Testing with Pest PHP
-   Database design and optimization

---

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

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

**Made with ❤️ by [Bahri](https://github.com/bahrie127)**

_Building the future of web development, one Laravel application at a time._ 🚀
