# Transaction API Documentation for Flutter Integration

## Base URL

```
http://localhost:8000/api
```

## Authentication

All endpoints (except register and login) require Bearer token authentication:

```
Authorization: Bearer YOUR_ACCESS_TOKEN
```

## Endpoints Overview

### 1. Authentication Endpoints

#### Register

```http
POST /auth/register
```

**Body:**

```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

#### Login

```http
POST /auth/login
```

**Body:**

```json
{
    "email": "john@example.com",
    "password": "password123"
}
```

**Response:**

```json
{
    "message": "Login successful",
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com"
        },
        "token": "1|abc123def456..."
    }
}
```

### 2. Transaction CRUD Endpoints

#### Get All Transactions

```http
GET /transactions
```

**Query Parameters:**

-   `per_page` (int, max 100): Items per page (default: 15)
-   `sort_by` (string): date, amount, created_at (default: date)
-   `sort_order` (string): asc, desc (default: desc)
-   `account_id` (int): Filter by account
-   `category_id` (int): Filter by category
-   `type` (string): income, expense
-   `from_date` (date): YYYY-MM-DD format
-   `to_date` (date): YYYY-MM-DD format
-   `q` (string): Search in note or counterparty
-   `min_amount` (decimal): Minimum amount
-   `max_amount` (decimal): Maximum amount

**Response:**

```json
{
    "message": "Transactions retrieved successfully",
    "data": [
        {
            "id": 1,
            "account_id": 1,
            "category_id": 1,
            "type": "expense",
            "date": "2025-08-26",
            "amount": "100.50",
            "note": "Sample transaction",
            "counterparty": "Test Shop",
            "transfer_group_id": null,
            "created_at": "2025-08-26T10:00:00.000000Z",
            "updated_at": "2025-08-26T10:00:00.000000Z",
            "account": {
                "id": 1,
                "name": "Main Account",
                "type": "checking"
            },
            "category": {
                "id": 1,
                "name": "Food",
                "type": "expense"
            }
        }
    ],
    "pagination": {
        "current_page": 1,
        "last_page": 5,
        "per_page": 15,
        "total": 67,
        "from": 1,
        "to": 15
    }
}
```

#### Create Transaction

```http
POST /transactions
```

**Body:**

```json
{
    "account_id": 1,
    "category_id": 1,
    "type": "expense",
    "date": "2025-08-26",
    "amount": 100.5,
    "note": "Sample transaction",
    "counterparty": "Test Shop"
}
```

#### Get Single Transaction

```http
GET /transactions/{id}
```

#### Update Transaction

```http
PUT /transactions/{id}
```

**Body (partial update allowed):**

```json
{
    "amount": 150.75,
    "note": "Updated note"
}
```

#### Delete Transaction

```http
DELETE /transactions/{id}
```

### 3. Transfer Endpoints

#### Create Transfer

```http
POST /transactions/transfer
```

**Body:**

```json
{
    "from_account_id": 1,
    "to_account_id": 2,
    "amount": 500.0,
    "date": "2025-08-26",
    "note": "Transfer between accounts"
}
```

### 4. Statistics Endpoint

#### Get Transaction Statistics

```http
GET /transactions-statistics
```

**Query Parameters:**

-   `from_date` (date): YYYY-MM-DD format
-   `to_date` (date): YYYY-MM-DD format
-   `account_id` (int): Filter by account
-   `category_id` (int): Filter by category

**Response:**

```json
{
    "message": "Transaction statistics retrieved successfully",
    "data": {
        "total_income": 2500,
        "total_expense": 1800,
        "net_amount": 700,
        "transaction_count": 25,
        "income_count": 10,
        "expense_count": 15
    }
}
```

## Flutter Integration Examples

### 1. Authentication Service

```dart
class AuthService {
  static const String baseUrl = 'http://localhost:8000/api';

  Future<Map<String, dynamic>> login(String email, String password) async {
    final response = await http.post(
      Uri.parse('$baseUrl/auth/login'),
      headers: {'Content-Type': 'application/json'},
      body: jsonEncode({
        'email': email,
        'password': password,
      }),
    );

    if (response.statusCode == 200) {
      return jsonDecode(response.body);
    } else {
      throw Exception('Login failed');
    }
  }
}
```

### 2. Transaction Service

```dart
class TransactionService {
  static const String baseUrl = 'http://localhost:8000/api';

  Future<Map<String, dynamic>> getTransactions({
    int? page,
    int? perPage,
    String? sortBy,
    String? sortOrder,
    int? accountId,
    int? categoryId,
    String? type,
    String? fromDate,
    String? toDate,
    String? search,
  }) async {
    final queryParams = <String, String>{};

    if (page != null) queryParams['page'] = page.toString();
    if (perPage != null) queryParams['per_page'] = perPage.toString();
    if (sortBy != null) queryParams['sort_by'] = sortBy;
    if (sortOrder != null) queryParams['sort_order'] = sortOrder;
    if (accountId != null) queryParams['account_id'] = accountId.toString();
    if (categoryId != null) queryParams['category_id'] = categoryId.toString();
    if (type != null) queryParams['type'] = type;
    if (fromDate != null) queryParams['from_date'] = fromDate;
    if (toDate != null) queryParams['to_date'] = toDate;
    if (search != null) queryParams['q'] = search;

    final uri = Uri.parse('$baseUrl/transactions').replace(
      queryParameters: queryParams.isNotEmpty ? queryParams : null,
    );

    final response = await http.get(
      uri,
      headers: {
        'Authorization': 'Bearer $accessToken',
        'Accept': 'application/json',
      },
    );

    if (response.statusCode == 200) {
      return jsonDecode(response.body);
    } else {
      throw Exception('Failed to load transactions');
    }
  }

  Future<Map<String, dynamic>> createTransaction({
    required int accountId,
    required int categoryId,
    required String type,
    required String date,
    required double amount,
    String? note,
    String? counterparty,
  }) async {
    final response = await http.post(
      Uri.parse('$baseUrl/transactions'),
      headers: {
        'Authorization': 'Bearer $accessToken',
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: jsonEncode({
        'account_id': accountId,
        'category_id': categoryId,
        'type': type,
        'date': date,
        'amount': amount,
        'note': note,
        'counterparty': counterparty,
      }),
    );

    if (response.statusCode == 201) {
      return jsonDecode(response.body);
    } else {
      throw Exception('Failed to create transaction');
    }
  }

  Future<Map<String, dynamic>> createTransfer({
    required int fromAccountId,
    required int toAccountId,
    required double amount,
    required String date,
    String? note,
  }) async {
    final response = await http.post(
      Uri.parse('$baseUrl/transactions/transfer'),
      headers: {
        'Authorization': 'Bearer $accessToken',
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: jsonEncode({
        'from_account_id': fromAccountId,
        'to_account_id': toAccountId,
        'amount': amount,
        'date': date,
        'note': note,
      }),
    );

    if (response.statusCode == 201) {
      return jsonDecode(response.body);
    } else {
      throw Exception('Failed to create transfer');
    }
  }

  Future<Map<String, dynamic>> getStatistics({
    String? fromDate,
    String? toDate,
    int? accountId,
    int? categoryId,
  }) async {
    final queryParams = <String, String>{};

    if (fromDate != null) queryParams['from_date'] = fromDate;
    if (toDate != null) queryParams['to_date'] = toDate;
    if (accountId != null) queryParams['account_id'] = accountId.toString();
    if (categoryId != null) queryParams['category_id'] = categoryId.toString();

    final uri = Uri.parse('$baseUrl/transactions-statistics').replace(
      queryParameters: queryParams.isNotEmpty ? queryParams : null,
    );

    final response = await http.get(
      uri,
      headers: {
        'Authorization': 'Bearer $accessToken',
        'Accept': 'application/json',
      },
    );

    if (response.statusCode == 200) {
      return jsonDecode(response.body);
    } else {
      throw Exception('Failed to load statistics');
    }
  }
}
```

### 3. Transaction Model

```dart
class Transaction {
  final int id;
  final int accountId;
  final int categoryId;
  final String type;
  final DateTime date;
  final double amount;
  final String? note;
  final String? counterparty;
  final String? transferGroupId;
  final DateTime createdAt;
  final DateTime updatedAt;
  final Account account;
  final Category category;

  Transaction({
    required this.id,
    required this.accountId,
    required this.categoryId,
    required this.type,
    required this.date,
    required this.amount,
    this.note,
    this.counterparty,
    this.transferGroupId,
    required this.createdAt,
    required this.updatedAt,
    required this.account,
    required this.category,
  });

  factory Transaction.fromJson(Map<String, dynamic> json) {
    return Transaction(
      id: json['id'],
      accountId: json['account_id'],
      categoryId: json['category_id'],
      type: json['type'],
      date: DateTime.parse(json['date']),
      amount: double.parse(json['amount'].toString()),
      note: json['note'],
      counterparty: json['counterparty'],
      transferGroupId: json['transfer_group_id'],
      createdAt: DateTime.parse(json['created_at']),
      updatedAt: DateTime.parse(json['updated_at']),
      account: Account.fromJson(json['account']),
      category: Category.fromJson(json['category']),
    );
  }
}
```

## Error Handling

All endpoints return standardized error responses:

### Validation Error (422)

```json
{
    "message": "Validation error",
    "errors": {
        "amount": ["The amount field is required."],
        "date": ["The date field must be a valid date."]
    }
}
```

### Authentication Error (401)

```json
{
    "message": "Unauthenticated."
}
```

### Not Found Error (404)

```json
{
    "message": "Transaction not found"
}
```

### Server Error (500)

```json
{
    "message": "An error occurred while creating the transaction"
}
```

## Testing with Postman

1. Import the collection file: `Transaction_API.postman_collection.json`
2. Import the environment file: `Transaction_API.postman_environment.json`
3. Set the `base_url` in environment to your Laravel server URL
4. Run the "Login" request first to automatically set the access token
5. Use other endpoints with the token automatically applied

## Notes for Flutter Development

1. **Date Format**: Always use YYYY-MM-DD format for dates
2. **Amount**: Send as number (not string) in requests
3. **Token Storage**: Store the access token securely (use flutter_secure_storage)
4. **Error Handling**: Check status codes and handle errors appropriately
5. **Pagination**: Implement infinite scroll or pagination for transaction lists
6. **Offline Support**: Consider caching frequently used data
7. **Form Validation**: Implement client-side validation matching server rules
