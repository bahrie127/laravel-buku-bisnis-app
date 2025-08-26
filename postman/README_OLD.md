# Laravel Bookkeeping API - Postman Collection

This directory contains Postman collections and environments for testing the Laravel Bookkeeping System API.

## Files

- `Complete_Bookkeeping_API_v2.postman_collection.json` - **RECOMMENDED** Complete collection with all endpoints
- `Bookkeeping_API.postman_environment.json` - Environment variables for API testing
- `Complete_Bookkeeping_API.postman_collection.json` - Legacy collection (use v2 instead)
- `Transaction_API.postman_collection.json` - Transactions only collection
- `Transaction_API.postman_environment.json` - Legacy environment file

## Quick Start

### 1. Import Files to Postman

1. Open Postman
2. Import `Complete_Bookkeeping_API_v2.postman_collection.json`
3. Import `Bookkeeping_API.postman_environment.json`
4. Select the imported environment in Postman

### 2. Configuration

The environment includes these variables:
- `base_url`: API base URL (default: http://localhost:8000)
- `access_token`: Authentication token (auto-populated after login)
- `sample_account_id`: Account ID for testing (auto-populated)
- `sample_category_id`: Category ID for testing (auto-populated)
- `sample_transaction_id`: Transaction ID for testing (auto-populated)

1. Select environment "Transaction API Environment"
2. Update `base_url` sesuai server Laravel Anda (default: `http://localhost:8000`)

### 3. Authentication Flow

1. **Register** - Buat akun baru
2. **Login** - Token akan otomatis tersimpan di environment variable
3. Gunakan endpoint lainnya (token sudah otomatis ter-attach)

## 📚 Endpoint Categories

### 🔐 Authentication

-   Register
-   Login (auto-save token)
-   Get User Profile
-   Logout

### 💰 Transactions

-   Get All Transactions (dengan filtering & pagination)
-   Create Transaction
-   Get Transaction by ID
-   Update Transaction
-   Delete Transaction

### 🔄 Transfers

-   Create Transfer (antar akun)

### 📊 Statistics

-   Get Transaction Statistics
-   Get Monthly Statistics

### 📖 Reference

-   Get All Accounts
-   Get All Categories

## 🎯 Flutter Integration

Untuk integrasi dengan Flutter, lihat dokumentasi lengkap di:
`docs/FLUTTER_INTEGRATION.md`

Dokumentasi tersebut berisi:

-   ✅ Contoh kode Dart/Flutter
-   ✅ Model classes
-   ✅ Service classes
-   ✅ Error handling
-   ✅ Best practices

## 🔧 Features

### Filtering & Search

-   Filter by account, category, type
-   Date range filtering
-   Search in note/counterparty
-   Amount range filtering

### Pagination

-   Configurable items per page
-   Sort by date/amount/created_at
-   Ascending/descending order

### Security

-   Bearer token authentication
-   User data isolation
-   Input validation

### Error Handling

-   Standardized error responses
-   Validation error details
-   Proper HTTP status codes

## 💡 Tips untuk Development

1. **Testing Sequence**:

    - Buat akun → Login → Get accounts/categories → Buat transaksi

2. **Environment Variables**:

    - `base_url` - URL server Laravel
    - `access_token` - Otomatis ter-set setelah login
    - `sample_account_id` & `sample_category_id` - Untuk testing

3. **Common Filters**:

    ```
    ?type=expense&from_date=2025-08-01&to_date=2025-08-31
    ?account_id=1&per_page=20&sort_by=date&sort_order=desc
    ```

4. **Transfer vs Regular Transaction**:
    - Transfer: Gunakan endpoint `/transactions/transfer`
    - Regular: Gunakan endpoint `/transactions`

## 🐛 Troubleshooting

**Token Issues:**

-   Pastikan sudah login dan token tersimpan
-   Check environment variable `access_token`

**Validation Errors:**

-   Check required fields dan format data
-   Date format: YYYY-MM-DD
-   Amount: number (bukan string)

**404 Errors:**

-   Pastikan ID exists dan belongs to user
-   Check base_url di environment

## 📱 Ready for Flutter!

Collection ini sudah siap untuk digunakan sebagai referensi dalam development Flutter app. Semua endpoint sudah tested dan documented dengan contoh request/response yang lengkap.
