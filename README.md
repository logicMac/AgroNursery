# ANFPSQTS — Agro Nursery Farm Product Sales & Quality Tracking System

A complete MVC web application for a nursery in Polomolok, South Cotabato, Philippines.

## Stack
- PHP 8.x (plain, hand-rolled MVC)
- MySQL (InnoDB) via PDO
- Tailwind CSS, Lucide icons, Poppins font
- Apache + mod_rewrite

## Setup (XAMPP/WAMP)
1. Place or clone this folder under `www/Agro`.
2. Copy `.env.example` to `.env` and set DB credentials.
3. Import `database/schema.sql`, then `database/seeders.sql` in phpMyAdmin or MySQL CLI.
4. Enable `mod_rewrite` in Apache.
5. Visit `http://localhost/Agro/public`.

## Default Logins
- Owner: `owner@agronursery.ph` / `password`
- Staff: `staff@agronursery.ph` / `password`

## Folder Structure
- `/app/Controllers` — request orchestration & RBAC
- `/app/Models` — all database access (PDO prepared statements)
- `/app/Views` — display logic only
- `/app/Core` — Router, Database, Model/Controller, Auth, Session, Csrf, Validator
- `/config` — environment config (`.env`)
- `/database` — `schema.sql` and `seeders.sql`
- `/docs` — `sprint-log.md`
- `/public` — front controller `index.php` and assets
- `.htaccess` — blocks `/app`, `/config`, `/database`; rewrites to `public/index.php`

## Security
- `password_hash()` / `password_verify()`
- PDO prepared statements only
- CSRF tokens on state-changing forms
- `htmlspecialchars()` output escaping
- HttpOnly/Secure session cookies, session timeout, ID regeneration on login
- Centralized role/permission middleware
- Audit logging

## Modules
All 12 sprints implemented: Auth, Sales, Quality, Inventory, Analytics, Integrated Sales/Quality, Batch/Lot, Environment, Pricing, Traceability, Shrinkage, Forecasting.
