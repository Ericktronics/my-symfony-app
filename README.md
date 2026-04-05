# Symfony CRUD API

A RESTful API built with Symfony 7 and Doctrine ORM, applying SOLID principles.

---

## Requirements

- PHP 8.2+
- Composer
- MySQL 8+

---

## Setup

```bash
# Install dependencies
composer install

# Copy environment file and configure your database credentials
cp .env .env.local
```

Edit `.env.local`:
```env
DATABASE_URL="mysql://root:password@127.0.0.1:3306/symfony_app?serverVersion=8.0&charset=utf8mb4"
```

```bash
# Create the database
php bin/console doctrine:database:create

# Run migrations
php bin/console doctrine:migrations:migrate

# Seed sample data
php bin/console doctrine:fixtures:load

# Start the dev server
symfony server:start
```

---

## Project Structure

```
src/
├── Contract/        # Service interfaces (Dependency Inversion)
├── Controller/      # HTTP layer — routes and request handling
├── Dto/             # Data Transfer Objects with validation rules
├── Entity/          # Doctrine ORM entities (pure domain objects)
├── EventListener/   # Global exception handler
├── Exception/       # Custom exception classes
├── Repository/      # Database queries
├── Service/         # Business logic
└── Transformer/     # Entity → array serialization (Single Responsibility)
```

---

## API Endpoints

### Categories

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/categories` | Get all categories |
| GET | `/categories/{id}` | Get a single category |
| POST | `/categories` | Create a category |
| PATCH | `/categories/{id}` | Update a category |
| DELETE | `/categories/{id}` | Delete a category |

**POST /categories**
```json
{
  "name": "Peripherals"
}
```

---

### Products

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/products` | Get all products (supports filtering & pagination) |
| GET | `/products/{id}` | Get a single product |
| POST | `/products` | Create a product |
| PATCH | `/products/{id}` | Update a product |
| DELETE | `/products/{id}` | Delete a product |

**GET /products** — query params:

| Param | Type | Description |
|-------|------|-------------|
| `categoryId` | int | Filter by category |
| `search` | string | Search by name or description |
| `page` | int | Page number (default: 1) |
| `limit` | int | Items per page (default: 10) |

```bash
GET /products?categoryId=1&search=keyboard&page=1&limit=5
```

**POST /products**
```json
{
  "name": "Mechanical Keyboard",
  "description": "TKL RGB mechanical keyboard",
  "price": 99.99,
  "categoryId": 1
}
```

**PATCH /products/{id}** — all fields optional:
```json
{
  "price": 79.99
}
```

---

## Error Responses

**422 Validation Error**
```json
{
  "statusCode": 422,
  "message": "Validation failed",
  "errors": {
    "name": "Name is required",
    "price": "Price must be a positive number"
  }
}
```

**404 Not Found**
```json
{
  "statusCode": 404,
  "message": "Product with id '99' not found"
}
```

**409 Conflict**
```json
{
  "statusCode": 409,
  "message": "Cannot delete category 'Peripherals' because it has linked products."
}
```
