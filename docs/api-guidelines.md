# API Design & Usage Guidelines

This document defines the standards and best practices used in the **SalesPro Order & Inventory Management API**.  
The goal is to ensure consistency, maintainability, and clarity across all backend modules.

---

## 1️ General Principles

- All APIs follow **RESTful design conventions**
- API responses are **JSON-only**
- All requests must be **authenticated using Laravel Sanctum**
- Versioning is enforced under `/api/v1`
- Input validation is handled using **Form Request classes**
- Business logic is placed in **Service Classes**
- Database operations use **Eloquent ORM + Transactions where necessary**

---

## 2 Versioning Strategy

Base prefix: /api/v1/

New breaking changes must be introduced under: /api/v2/

This ensures:

- Backward compatibility
- Safe feature iteration
- Easier migration

## 3️ Authentication & Security

Authentication Method: Bearer Token (Laravel Sanctum)

Protected routes use: auth:sanctum

## 4️ Request & Validation Standards

All validation is handled using **Form Request Classes**.
Examples:

- `CreateOrderRequest`
- `UpdateOrderStatusRequest`
- `CreateProductRequest`

Benefits:

- Clean controllers
- Centralized validation rules
- Better testability

Validation failures return:

```json
{
  "success": false,
  "message": "Validation error",
  "errors": {
    "field_name": ["Error message"]
  }
}

5 Response Format (Standardized)
All API responses follow a consistent structure.
 Success Response
 {
  "success": true,
  "message": "Order created successfully",
  "data": {}
}

Error Response
{
  "success": false,
  "message": "Cannot update status of shipped or delivered orders"
}

Validation Error
{
  "success": false,
  "message": "Validation failed",
  "errors": {}
}

6 Pagination Standard
Pagination follows Laravel default with custom wrapper.

{
  "success": true,
  "data": {
    "current_page": 1,
    "per_page": 15,
    "total": 25,
    "data": []
  },
  "message": "Orders retrieved successfully"
}
