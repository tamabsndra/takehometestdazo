# Dazo Store Management System

<div align="center">

![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-13-316192?style=for-the-badge&logo=postgresql&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/Tailwind-3.4-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)
![Alpine.js](https://img.shields.io/badge/Alpine.js-3.13-8BC0D0?style=for-the-badge&logo=alpine.js&logoColor=white)

**Modern Multi-Level Store Management System with JWT Authentication & Role-Based Access Control**

[Features](#-features) • [Installation](#-installation) • [API Documentation](#-api-documentation)

</div>

---

## 🎯 Overview

Dazo adalah sistem manajemen toko modern yang mendukung hierarki multi-level (Pusat, Cabang, Retail) dengan authentication JWT dan role-based access control

### Key Highlights
- ✅ **Backend Complete** - 36 RESTful API endpoints
- ✅ **JWT Authentication** - Secure token-based auth dengan 1 jam expiry
- ✅ **Role-Based Access** - 3 levels: Super Admin, Admin, Kasir
- ✅ **Beautiful UI** - Modern glassmorphism design dengan Tailwind CSS
- ✅ **Auto-Generated Users** - Setiap toko otomatis membuat admin & kasir
- ✅ **No Stock Management** - Simplified POS tanpa inventory tracking
- ✅ **Comprehensive Reports** - Sales analytics dan top products

---

## ✨ Features

### 🔐 Authentication & Authorization
- Session-based web authentication
- JWT tokens untuk API access
- Password hashing dengan bcrypt
- Role middleware (Super Admin, Admin, Cashier)

### 🏪 Store Management (Super Admin)
- CRUD operations untuk stores
- 3-level hierarchy: Pusat → Cabang → Retail  
- Auto-create admin & cashier saat toko dibuat
- Store statistics dan analytics

### 👥 User Management
- Super Admin: Manage semua users
- Admin: Manage cashiers di toko sendiri
- Profile management untuk semua roles
- Email-based authentication

### 📦 Product Management (Admin)
- CRUD products per store
- Search by name, SKU, description
- Price range filtering
- No stock tracking (unlimited quantity)

### 💰 Sales & POS
- Simple cart-based checkout
- Multiple payment methods (cash, card, transfer)
- Transaction history dengan filters
- Sales reports & analytics
- Top selling products

---

## 🚀 Installation

### Prerequisites
- PHP 8.2 or higher
- Composer 2.7+
- Node.js 18+ & NPM
- PostgreSQL 13+

### Step 1: Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install
```

### Step 2: Run Migrations & Seeders

```bash
# Fresh migration dengan sample data
php artisan migrate:fresh --seed
```

This creates:
- 6 stores (1 Pusat, 2 Cabang, 3 Retail)
- 13 users (1 Super Admin, 12 store users)
- 90 products (15 per store)
- 44 sample sales

### Step 3: Build Frontend Assets

```bash
# Production build
npm run build

# Development with hot reload
npm run dev
```

### Step 4: Start Server

```bash
php artisan serve
```

Application runs at: **http://127.0.0.1:8000**

---

## 🔑 Default Credentials

| Role | Email | Password |
|------|-------|----------|
| **Super Admin** | admin@dazo.com | password |
| **Admin** | admin.dazopusatjogja@dazo.com | password |
| **Kasir** | kasir.dazopusatjogja@dazo.com | password |

---

## 📡 API Documentation

**Base URL:** `http://127.0.0.1:8000/api`

### Authentication

```bash
# Login
curl -X POST http://127.0.0.1:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@dazo.com","password":"password"}'

# Use token
curl -H "Authorization: Bearer {token}" \
  http://127.0.0.1:8000/api/me
```

### Endpoints (36 Total)

- **Auth:** `/api/login`, `/api/logout`, `/api/refresh`, `/api/me`
- **Stores:** Full CRUD (Super Admin only)
- **Users:** Full CRUD with role-based access
- **Products:** Full CRUD with search & filters
- **Sales:** Create transactions, view history, reports

See [walkthrough.md](walkthrough.md) for complete API documentation.

---

## 🗂️ Database Schema

7 tables: `stores`, `users`, `products`, `sales`, `sale_items`, `cache`, `jobs`

- Hierarchical store structure
- JWT-ready user authentication
- Auto-calculate sale totals
- Foreign key constraints

---

## 🎨 Tech Stack

| Layer | Technology |
|-------|------------|
| Backend | Laravel 11 |
| Database | PostgreSQL |
| Auth (API) | JWT |
| Frontend | Blade + Tailwind CSS |
| JavaScript | Alpine.js |
| Build | Vite |

---

## 🧪 Testing

### Run All Tests

```bash
php artisan test
```

**Test Coverage:**
- ✅ 30 tests
- ✅ 99 assertions
- ✅ Authentication & Authorization
- ✅ Store Management (CRUD)
- ✅ Product Management (CRUD)
- ✅ Sales & POS System

### Test Suites

| Suite | Tests | Coverage |
|-------|-------|----------|
| **Authentication** | 7 | Login, logout, tokens, permissions |
| **Store Management** | 6 | CRUD, auto-user creation, validation |
| **Product Management** | 7 | CRUD, search, role permissions |
| **Sales** | 8 | POS checkout, reports, calculations |

**All tests passing!** ✅

<div align="center">

Made with Laravel • Powered by Tailwind CSS • Secured with JWT

</div>
