# Cafe/Restaurant POS System

A simple, fast, non-technical-friendly Point of Sale system for restaurants and cafes.
Built with **Laravel + Blade + Tailwind CSS + jQuery/JS + MySQL** — no extra frontend
frameworks, kept deliberately lean so it's easy to read, extend, and hand off.

## Features

- **Authentication** — simple, secure login (admin / cashier roles)
- **Dashboard** — today's sales, orders, occupied tables, top sellers at a glance
- **Products** — add products with photo + category, create a category inline
  from the product form (AJAX, no page reload), quick "out of stock" toggle
- **Table System** — tap-to-bill table tiles (green = available, red = occupied),
  instant table search, items saved to a table can be billed later without
  re-entering anything
- **Orders / Billing (POS)** — fast tap-to-add product grid, live cart totals,
  works for tables AND manual/takeaway orders from the same screen, checkout
  modal with change calculation, long printable receipt
- **Customers** — simple CRUD + live search/autocomplete inside the POS screen
- **Sales Reports** — today/yesterday/week/month summaries, sales trend chart,
  top products, payment-method breakdown
- **Settings** — business name, logo, address, currency symbol, default tax %,
  receipt footer

Every form validates in real time in the browser (correct field types,
required fields, number-only inputs, etc.) **and** again on the server, so the
data is always safe even if JavaScript is bypassed.

## Tech Stack

- Laravel 10 (PHP 8.1+)
- Blade templates
- Tailwind CSS (compiled via Vite)
- jQuery + vanilla JS (modular files under `resources/js/modules`)
- MySQL

## Project Structure

```
app/
  Http/
    Controllers/       Thin controllers — just orchestrate request → service → view
    Requests/           Form Request validation classes (server-side rules)
  Models/                Eloquent models + relationships
  Services/              Business logic (OrderService, ProductService, ReportService, SettingService)
database/
  migrations/            One migration per table, clearly named
  seeders/                Demo admin user, categories, products, tables, settings
resources/
  views/                 Blade views, grouped by feature (products, orders, tables, ...)
  js/
    app.js                Single Vite entry point
    modules/               One JS file per feature — validation, POS cart logic, etc.
  css/app.css             Tailwind + a small set of reusable component classes
routes/web.php            All routes, grouped and commented
```

This structure keeps each layer single-purpose so new features (e.g. inventory,
kitchen display, multi-branch) can be added without touching unrelated code.

## Setup Instructions

> This code was generated without internet access to Packagist, so the
> `vendor/` folder (PHP dependencies) is **not** included. Follow these steps
> on your machine to get it running — it takes about 5 minutes.

### 1. Requirements
- PHP >= 8.1 with common extensions (mbstring, pdo_mysql, gd, fileinfo)
- Composer
- Node.js >= 18 + npm
- MySQL >= 5.7 (or MariaDB)

### 2. Install dependencies
```bash
composer install
npm install
```

### 3. Configure environment
A `.env` file is already included with sensible local defaults. Update the
database credentials if needed:
```
DB_DATABASE=pos_system
DB_USERNAME=root
DB_PASSWORD=
```
Then generate the app key:
```bash
php artisan key:generate
```

### 4. Create the database
Create a MySQL database named `pos_system` (or whatever you set in `.env`),
then run migrations + demo data:
```bash
php artisan migrate --seed
```

### 5. Link storage (for product photos & logo uploads)
```bash
php artisan storage:link
```

### 6. Build frontend assets
```bash
npm run build
# or, while developing:
npm run dev
```

### 7. Serve the app
```bash
php artisan serve
```
Visit **http://localhost:8000**

### Demo Login
| Role    | Email                 | Password |
|---------|-----------------------|----------|
| Admin   | admin@cafepos.test    | password |
| Cashier | cashier@cafepos.test  | password |

## How the Table System Works

1. Go to **Tables** — every table is a tile (🟢 available / 🔴 occupied), or
   use the search box to jump straight to one.
2. Tap a table to open the billing screen for it.
3. Add items by tapping the product grid — quantities, notes, discount and
   tax all update live.
4. Hit **Save** to hold the order on that table (no payment yet) — the table
   turns red and shows the item count. Staff can come back anytime and the
   cart reloads exactly as it was left.
5. When the guest is ready to pay, reopen the table and hit **Checkout** to
   take payment and print the receipt.

For quick counter sales, open **Billing** directly from the sidebar (no table
needed) and check out immediately.

## Notes for Future Enhancement

- `OrderService`, `ProductService`, `ReportService` and `SettingService`
  contain all business logic — controllers stay thin, so new rules (loyalty
  points, multiple branches, ingredient-level inventory, etc.) belong there.
- All AJAX endpoints return JSON consistently (`{success, message, data}`)
  so new frontend behavior can hook in without backend changes.
