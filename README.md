# 📦 Transport Management System

A role-based shipment and logistics management platform built with Laravel, Filament, and MySQL.

This application streamlines the process of managing clients, shipments, carriers, and payments. Built with Laravel and Filament, it features a robust role-based access control system supporting Admins, Managers, Clients, and Accountants. This was my **third project**, focused on real-world transport workflow and user management using Laravel’s ecosystem and Filament Admin Panel.

---

## 🧾 Features Overview

### 🔐 User & Role Management
- Role-based access with four main roles: **Admin, Manager, Client, Accountant**
- Laravel Filament admin panel for managing users and permissions
- Registration & authentication using Laravel’s built-in features
- Role-specific dashboards and permissions

### 🧍 Client Management
- Admins and Managers can create and manage client profiles
- Clients can update their profiles and manage multiple addresses
- Each client has access to:
  - Their own shipment records (sent/received)
  - Dashboard showing basic shipment & payment statistics

### 📦 Shipment Management
- Clients, Admins, and Managers can create shipment requests
- Approval workflow: 
  - Initial status: `draft`
  - Approved by Admin: `approved`
- Shipment fields:
  - Sender & Receiver (User relations)
  - Address data
  - Weight, value, tracking number, isFlex flag
  - Carrier, attachments, shipment price
- Attachments are stored via Laravel Filesystem

### 🚚 Carrier Management
- Admins can manage carriers (transport handlers)
- Store carrier name, contact, and logo

### 💳 Payment Management
- Clients can view and pay invoices
- Accountants & Admins can manage, track, and verify payments
- Payment fields:
  - Client, amount, method, date
  - Attachment (e.g., proof of payment)

---

## 🛠️ Tech Stack

- **Framework:** Laravel 10+
- **Admin Panel:** FilamentPHP
- **Database:** MySQL
- **Storage:** Laravel Filesystem (local or cloud)
- **Authentication:** Laravel Auth with Filament integration

---

## 🧪 Installation & Setup

1. **Clone the Repository**
   ```bash
   git clone https://github.com/tahamhb12/ShipmentProject.git
