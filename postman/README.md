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

### 3. Usage Flow

**Start your Laravel application first:**
```bash
php artisan serve
```

**Then follow this testing sequence:**

1. **Authentication** 🔐
   - Register a new user
   - Login (token automatically saved)
   - Test "Get User Profile"

2. **Accounts** 💰
   - Create an account (ID automatically saved)
   - Test filtering and search
   - Update/view account details

3. **Categories** 📂
   - Create a parent category (ID automatically saved)
   - Create subcategories
   - Test filtering by type

4. **Transactions** 💸
   - Create income/expense transactions
   - Test transfer between accounts
   - Filter by date, account, category
   - Search transactions

5. **Statistics** 📊
   - Get overall statistics
   - Filter by date range, account, category
   - Test complex multi-filter queries

## Collection Features

### Auto-Variable Management
- Authentication tokens are automatically extracted and saved
- Resource IDs are automatically captured for subsequent requests
- No manual copying of IDs required

### Comprehensive Coverage
- **Authentication**: Register, login, profile, logout
- **Accounts**: CRUD operations with filtering and search
- **Categories**: CRUD operations with parent-child relationships
- **Transactions**: CRUD operations with advanced filtering
- **Transfer**: Account-to-account transfers
- **Statistics**: Comprehensive reporting with multiple filters

### Real-World Examples
All requests include realistic sample data that demonstrates:
- Proper validation requirements
- Business logic constraints
- Expected data formats
- Common use cases

## API Response Formats

### Successful Responses
```json
{
    "message": "Operation successful",
    "data": { ... }
}
```

### Error Responses
```json
{
    "message": "Error description",
    "errors": {
        "field": ["validation error"]
    }
}
```

### Authentication Response
```json
{
    "message": "Login successful",
    "token": "your-auth-token",
    "user": { ... }
}
```

## Testing Tips

### Sequential Testing
1. Always start with Authentication → Accounts → Categories → Transactions
2. Use the auto-populated IDs for dependent requests
3. Check response status codes and data structure

### Environment Switching
- Modify `base_url` for different environments (local, staging, production)
- Keep separate environments for different API versions

### Debugging
- Use Postman Console to see auto-extracted variables
- Check response headers for rate limiting information
- Verify token authentication in each protected request

## Common Issues

### 401 Unauthorized
- Ensure you've logged in and token is saved
- Check if token has expired (re-login if needed)
- Verify the Authorization header is properly set

### 404 Not Found
- Ensure Laravel app is running (`php artisan serve`)
- Check the `base_url` in environment variables
- Verify route definitions in `routes/api.php`

### 422 Validation Errors
- Check required fields in request body
- Verify data types and formats
- Ensure foreign key relationships exist

### 500 Server Error
- Check Laravel logs: `storage/logs/laravel.log`
- Verify database connection and migrations
- Ensure all dependencies are installed

## Development Integration

### For Flutter Development
This collection is perfect for:
- API contract validation
- Response format verification
- Error handling testing
- Performance benchmarking

### For API Documentation
The collection serves as:
- Living API documentation
- Integration examples
- Validation reference
- Testing baseline

## Advanced Usage

### Bulk Testing
Use Postman's Collection Runner to:
- Test entire API flows
- Validate regression scenarios
- Performance testing with iterations

### CI/CD Integration
Export collection and environment for:
- Automated API testing in pipelines
- Contract testing between services
- Deployment validation

### Custom Scripts
Extend the collection with:
- Advanced authentication flows
- Custom data validation
- Performance monitoring
- Error rate tracking

## Support

For issues with the API collection:
1. Check Laravel application logs
2. Verify database state and migrations
3. Test individual endpoints manually
4. Check network connectivity and firewall rules

The collection is maintained to match the latest API implementation and provides comprehensive coverage for all bookkeeping system functionality.
