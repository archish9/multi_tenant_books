# Multi-Tenant Book Management System

This Laravel application provides a multi-tenant book management system with a Filament admin panel for each tenant.

## Features

- Multi-tenant architecture with database separation per tenant
- Filament admin panel for tenant-specific book management
- API endpoints for book operations
- Token-based authentication for API access
- Domain-specific functionality

## Setup Instructions

### 1. Clone the repository and install dependencies

```bash
git clone <repository-url>
cd multi_tenant_books
composer install
```

### 2. Configure environment variables

Copy `.env.example` to `.env` and set the database credentials:

```bash
cp .env.example .env
php artisan key:generate
```

### 3. Run migrations for the central database

```bash
php artisan migrate
```

### 4. Create tenants

Use the custom command to create tenants:

```bash
php artisan tenant:create "Tenant Name" "tenant1.domain.com" "admin@tenant1.com" --admin-email=admin@tenant1.com --admin-password=password
```


### 5. Access the admin panel

The admin panel will be available at:
- Central admin: `http://admin.domain.com/admin`
- Tenant specific admin: `http://tenant1.domain.com/admin`

### 6. API Usage

Authentication:

```bash
# Get API token
curl -X POST http://tenant1.domain.com/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@tenant1.com", "password":"password", "device_name":"api-test"}'
```

Using the API:

```bash
# Get all books
curl -X GET http://tenant1.domain.com/api/books \
  -H "Authorization: Bearer YOUR_TOKEN"

# Create a book
curl -X POST http://tenant1.domain.com/api/books \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"title":"Book Title", "author":"Author Name"}'

# Get a specific book
curl -X GET http://tenant1.domain.com/api/books/1 \
  -H "Authorization: Bearer YOUR_TOKEN"

# Update a book
curl -X PUT http://tenant1.domain.com/api/books/1 \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"title":"Updated Title"}'

# Delete a book
curl -X DELETE http://tenant1.domain.com/api/books/1 \
  -H "Authorization: Bearer YOUR_TOKEN"
```

## Architecture

This application uses:
- Laravel 9.x
- Tenancy for Laravel (stancl/tenancy)
- Filament Admin Panel
- Laravel Sanctum for API authentication
- Spatie Media Library for image handling

The multi-tenant setup separates databases per tenant, ensuring complete data isolation.