# Enkrix Inventory Management System

A production-grade Inventory & Asset Management System built for church operations — tracking physical assets (instruments, furniture, AV equipment, media devices, books, welfare items) with stock control, assignments, and audit trails.

## Tech Stack

- **Backend:** PHP 8.2 (OOP, MVC architecture)
- **Frontend:** HTML + Tailwind CSS + Vanilla JavaScript
- **Database:** MySQL 8.0
- **Web Server:** Apache (inside container)
- **Deployment:** Docker + docker-compose

## Features

- Inventory item management (add/edit/delete, category, quantity, condition, location, cost, image)
- Category management
- Stock tracking (available vs. assigned, low-stock alerts, stock movement history)
- Item assignment to departments or individuals, with return tracking
- Full activity/audit log (user, timestamp, action type)
- Reporting: current stock, low stock, assignments, asset valuation, CSV export
- Role-based access control: **Admin**, **Inventory Manager**, **Viewer**

## Project Structure

```
app/
  controllers/   # Request handlers
  models/        # Database models
  views/         # HTML/Tailwind templates
  middleware/    # Auth middleware
  config/        # DB and app config
  routes/        # Route definitions
database/        # SQL schema and migrations
docker/          # Apache and PHP config for the container
public/          # Web root
storage/         # App storage (uploads, logs, etc.)
```

## Getting Started

### Prerequisites

- Docker and Docker Compose

### Setup

1. Copy the example environment file and adjust values as needed:
   ```
   cp .env.example .env
   ```
2. Start the stack:
   ```
   docker-compose up -d
   ```
3. The app will be available at the URL/port configured by `APP_URL` (default `http://localhost:8087`).
4. phpMyAdmin is available at `http://localhost:8086`.

### Services

| Service    | Purpose            | Port (host) |
|------------|---------------------|--------------|
| app        | PHP + Apache app    | 8087         |
| db         | MySQL 8.0 database  | 3309         |
| phpmyadmin | Database admin UI   | 8086         |

## Configuration (`.env`)

| Variable       | Description                          |
|----------------|---------------------------------------|
| `APP_ENV`      | Application environment (e.g. `production`) |
| `APP_URL`      | Public URL of the app                 |
| `APP_KEY`      | Application secret key                |
| `DB_HOST`      | Database host (`db` inside Docker)    |
| `DB_PORT`      | Database port                         |
| `DB_NAME`      | Database name                         |
| `DB_USER`      | Database user                         |
| `DB_PASS`      | Database user password                |
| `DB_ROOT_PASS` | Database root password                |

## Default Admin Login

After the first run, log in with:

- **Email:** `admin@enkrix.local`
- **Password:** `Admin@123`

> ⚠️ Change the default admin password immediately after first login, and rotate `DB_PASS`/`DB_ROOT_PASS` before deploying to a shared or production environment. Do not commit `.env` with real credentials to version control.

## User Roles

- **Admin** — full system control, manage users, view audit logs
- **Inventory Manager** — manage items and stock, assign/recover items
- **Viewer** — read-only access to reports
