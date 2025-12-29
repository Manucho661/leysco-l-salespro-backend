# L-SalesPro API Backend

**Project:** L-SalesPro – Sales Automation Portal  
**Framework:** Laravel 10  
**Author:** Emmanuel Wanyonyi  
**Confidential:** LEYSCO-LARAVEL-2025-06  

---

## Table of Contents
1. [Overview](#overview)  
2. [Requirements](#requirements)  
3. [Installation](#installation)  
4. [Configuration](#configuration)  
5. [Database Setup](#database-setup)  
6. [API Endpoints](#api-endpoints)  
7. [Testing](#testing)  
8. [Seed Data](#seed-data)  
9. [Notes](#notes)

---

## Overview

L-SalesPro is a backend API for a sales automation system. It provides endpoints for:

- Authentication & Authorization
- Inventory Management
- Product Management
- Sales Order Management
- Customer Management
- Warehouse Management
- Notifications
- Dashboard Analytics

This project implements a RESTful API using Laravel 10, with token-based authentication via **Laravel Sanctum**.

---

## Requirements

- PHP >= 8.1  
- Composer  
- MySQL or PostgreSQL  
- Redis (for caching)  
- Node.js & NPM (optional for frontend tools)  
- Postman (for API testing)  

---

## Installation

1. Clone the repository:

```bash
git clone https://github.com/yourusername/leysco-l-salespro-backend.git
cd leysco-l-salespro-backend

2. Install dependencies:
composer install

3. Copy the example environment file:
cp .env.example .env

4. Generate application key:
php artisan key:generate

5. Run migrations:
php artisan migrate

6. php artisan db:seed
php artisan db:seed

7. Start the local server:
php artisan serve

## Configuration

Update .env file with:
APP_NAME=L-SalesPro
APP_ENV=local
APP_KEY=base64:...
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=leysco
DB_USERNAME=root
DB_PASSWORD=

CACHE_DRIVER=redis
QUEUE_CONNECTION=database

# Database Setup
users – user accounts and roles

products – products catalog

categories – product categories

customers – customer details

orders & order_items – orders and their items

warehouses – warehouses for stock tracking

inventory – stock per warehouse

stock_reservations – reserved stock for orders

notifications – user notifications

activity_logs – API activity tracking

# API Endpoints
Authentication
Endpoint	                    Method	    Description
/api/v1/auth/login	            POST	    Login
/api/v1/auth/logout	            POST	    Logout
/api/v1/auth/user	            GET	        Current user profile
/api/v1/auth/password/forgot	POST	    Password reset request
/api/v1/auth/password/reset	    POST	    Reset password

Orders
Endpoint	                    Method	    Description
/api/v1/orders	                GET	        List orders with optional filters
/api/v1/orders	                POST	    Create a new order
/api/v1/orders/{id}	            GET	        Show order details
/api/v1/orders/{id}/status	    PUT	        Update order status
/api/v1/orders/{id}/invoice	    GET	        Generate invoice
/api/v1/orders/calculate-total	POST	    Preview order totals

Products
Endpoint	                    Method	    Description
/api/v1/products	            GET	        List products
/api/v1/products/{id}	        GET	        Product details
/api/v1/products	            POST	    Create      product (Admin only)
/api/v1/products/{id}	        PUT	        Update product
/api/v1/products/{id}	        DELETE	    Soft delete product

# Other tables follow the similar structure

Seed Data

Seeders use JSON files stored in database/data/:

users.json

products.json

customers.json

warehouses.json

orders.json

To seed the database:
php artisan db:s