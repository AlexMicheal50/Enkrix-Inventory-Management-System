You are a senior full-stack software engineer and product thinker.

Your task is to design and build a PROFESSIONAL, SECURE, and SCALABLE Inventory Management System specifically for church operations.

⚠️ IMPORTANT:
This is NOT a general church management system.
This is STRICTLY an INVENTORY & ASSET MANAGEMENT SYSTEM.
Every feature must revolve around tracking, managing, auditing, and reporting physical assets.

---

## 🧠 CORE OBJECTIVE

Build a production-grade Inventory Management System that enables churches to:

* Track all physical assets
* Monitor stock levels in real time
* Assign and recover items
* Maintain accountability and audit trails
* Generate reports for decision-making

Assets include (but are not limited to):

* Musical instruments
* Chairs, tables, furniture
* Audio/visual equipment
* Media devices (cameras, laptops, projectors)
* Books and materials
* Welfare/store items

---

## ⚙️ TECH STACK (STRICT)

Backend: PHP (OOP, PHP 8+)
Frontend: HTML + Tailwind CSS + Vanilla JavaScript
Database: MySQL
Architecture: MVC (Model-View-Controller)
Deployment: Docker + docker-compose (MANDATORY)
Web Server: Apache or Nginx (inside container)
Authentication: Secure RBAC system

---

## 🐳 DOCKER DEPLOYMENT (MANDATORY)

The system MUST run fully via docker-compose.

Services:

* app → PHP + Apache/Nginx
* db → MySQL
* phpmyadmin → Database UI

Requirements:

* docker-compose.yml must orchestrate all services
* Use .env for environment variables
* Persistent volume for MySQL
* App code mounted via volume
* Internal networking (DB_HOST=db)

Expose:

* App → http://localhost:8000 (or 80)
* phpMyAdmin → http://localhost:8080

Deliverables:

* Dockerfile
* docker-compose.yml
* .env.example

---

## 🏗️ ARCHITECTURE (STRICT MVC)

/app
/controllers
/models
/views
/middleware
/config
/public
/routes
/storage

* Clean routing (no messy query strings)
* Reusable components
* Separation of concerns enforced

---

## 🔐 SECURITY

* password_hash() for passwords
* PDO prepared statements
* CSRF protection
* Input validation & sanitization
* Secure sessions
* Role-based authorization
* Full activity logging

---

## 👥 USER ROLES

Admin:

* Full system control
* Manage users
* View audit logs

Inventory Manager:

* Manage items & stock
* Assign/recover items

Viewer:

* Read-only access
* View reports only

---

## 📦 CORE INVENTORY FEATURES (PRIORITY)

1. Inventory Item Management

   * Add / Edit / Delete items
   * Fields:

     * Item Name
     * Category
     * Quantity
     * Unit (optional)
     * Condition (New, Good, Damaged)
     * Location (Store, Hall, Studio, etc.)
     * Purchase Date
     * Cost/Value
     * Image

2. Category Management

   * Create and manage categories
   * Examples:

     * Audio
     * Furniture
     * Media
     * Welfare

3. Stock Management

   * Available vs Assigned stock
   * Automatic stock updates
   * Low stock alerts
   * Stock movement tracking

4. Item Assignment System

   * Assign items to:

     * Departments (Choir, Media, Ushering)
     * Individuals
   * Track:

     * Quantity assigned
     * Assignment date
     * Return status
   * Prevent over-assignment

5. Inventory Audit Trail

   * Log EVERY action:

     * Create, update, delete
     * Assign, return
   * Include:

     * User
     * Timestamp
     * Action type

6. Reporting (Inventory-Focused)

   * Current stock report
   * Low stock report
   * Assignment report
   * Asset valuation report
   * Export to CSV

---

## 🎨 UI/UX (CLEAN & PRACTICAL)

* Tailwind CSS modern layout

* Sidebar navigation:

  * Dashboard
  * Inventory
  * Categories
  * Assignments
  * Reports
  * Users

* Dashboard shows:

  * Total items
  * Low stock alerts
  * Recently added items
  * Recent activity

* Use:

  * Tables for inventory
  * Modals for forms
  * Toast notifications

---

## 🧩 DATABASE TABLES

* users
* roles
* categories
* inventory_items
* assignments
* activity_logs

Must include:

* Foreign keys
* Indexing
* Normalization

---

## ⚡ PERFORMANCE

* Pagination for inventory lists
* Optimized queries
* Avoid N+1 issues

---

## 🚀 DEPLOYMENT

* Fully dockerized

* Simple startup:
  docker-compose up -d

* Accessible via browser

* phpMyAdmin included

---

## 🧪 BONUS (INVENTORY-ENHANCING ONLY)

* Barcode/QR code for items
* Item scanning support
* Email alerts for low stock
* Backup/restore database

---

## 📌 OUTPUT

* Full folder structure
* Docker setup files
* SQL schema
* Sample MVC code:

  * Auth
  * Inventory CRUD
  * Assignment logic

---

## 🎯 FINAL RULE

If a feature does NOT directly improve inventory tracking, stock control, or asset accountability → DO NOT include it.

This is a focused, real-world Inventory Management System, not a generic church platform.
