# 🗑️ Smart Waste Collection Management System
**Domain:** Smart City  
**Theme:** Industry-Oriented Web Application Development using Agile Methodology

---

## 📋 Project Overview

A comprehensive web-based system to manage smart city waste collection operations including:
- Bin monitoring & fill-level tracking
- Vehicle & fleet management
- Collection route planning
- Citizen complaint portal
- Analytics & reporting

---

## 🛠️ Tech Stack

| Layer | Technology |
|-------|-----------|
| Backend | PHP 8.x |
| Database | MySQL (via XAMPP) |
| Frontend | HTML5, CSS3, Bootstrap 5 |
| Charts | Chart.js |
| Icons | Bootstrap Icons |
| Server | Apache (XAMPP) |

---

## 📁 Project Structure

```
waste_management/
├── index.php           # Login page
├── logout.php          # Logout
├── profile.php         # User profile
├── includes/
│   ├── config.php      # DB connection & helpers
│   ├── header.php      # HTML head & styles
│   ├── sidebar.php     # Navigation sidebar
│   ├── topbar.php      # Top navigation bar
│   └── footer.php      # Scripts & footer
├── admin/
│   ├── dashboard.php   # Admin dashboard with charts
│   ├── bins.php        # CRUD: Waste bins
│   ├── vehicles.php    # CRUD: Vehicles/trucks
│   ├── routes.php      # CRUD: Collection routes
│   ├── collection_logs.php  # View all logs
│   ├── complaints.php  # Manage complaints
│   ├── users.php       # CRUD: Users
│   └── reports.php     # Analytics & reports
├── collector/
│   ├── dashboard.php   # Collector dashboard
│   ├── my_routes.php   # View assigned routes
│   ├── log_collection.php  # Log bin collections
│   └── complaints.php  # Handle complaints
├── resident/
│   ├── dashboard.php   # Resident dashboard
│   ├── track_bins.php  # Track bin fill levels
│   ├── complaints.php  # My complaints
│   └── new_complaint.php  # File new complaint
├── api/
│   └── mark_notification.php
└── db/
    └── database.sql    # Database schema + seed data
```

---

## 🚀 XAMPP Setup Instructions

### Step 1: Install XAMPP
Download from: https://www.apachefriends.org/

### Step 2: Copy Project Files
Copy the `waste_management` folder to:
```
C:\xampp\htdocs\waste_management\
```

### Step 3: Start Services
- Open XAMPP Control Panel
- Start **Apache** and **MySQL**

### Step 4: Create Database
1. Open browser → http://localhost/phpmyadmin
2. Click **New** → Create database named `waste_management_db`
3. Select the database → Click **Import**
4. Choose `db/database.sql` → Click **Go**

### Step 5: Open Application
Visit: http://localhost/waste_management/

---

## 🔐 Demo Login Accounts

| Role | Email | Password |
|------|-------|----------|
| 👑 Admin | admin@wastesmart.com | password |
| 🚛 Collector | raj@wastesmart.com | password |
| 🚛 Collector | priya@wastesmart.com | password |
| 🏠 Resident | amit@wastesmart.com | password |
| 🏠 Resident | sunita@wastesmart.com | password |

---

## 🗄️ Database Schema

### Tables:
1. **users** – Admin, Collectors, Residents
2. **waste_bins** – Smart bins with fill levels
3. **vehicles** – Garbage trucks & fleet
4. **collection_routes** – Routes with schedule
5. **route_bins** – Which bins are on which route
6. **collection_logs** – History of waste collections
7. **complaints** – Citizen complaints & tracking
8. **notifications** – System notifications
9. **waste_reports** – Monthly summaries

---

## ✨ Features by Module

### 👑 Admin Panel
- ✅ Real-time dashboard with KPIs & charts
- ✅ Full CRUD for Bins, Vehicles, Routes, Users
- ✅ Complaint management with assignment
- ✅ Monthly reports & analytics
- ✅ Collection log viewer
- ✅ Notification system

### 🚛 Collector Portal
- ✅ Dashboard with assigned route bins
- ✅ Log waste collections with weight
- ✅ View my route map with bin stops
- ✅ Update complaint resolution

### 🏠 Resident Portal
- ✅ Track bin fill levels in real-time
- ✅ File complaints (6 types)
- ✅ Track complaint status
- ✅ View collection schedule

---

## 📅 15-Day Agile Development Plan

| Sprint | Days | Tasks |
|--------|------|-------|
| Planning | 1-3 | Setup, SRS, Sprint Plan |
| Design | 4-5 | UI Mockups, DB Design |
| Sprint-1 Dev | 6-8 | Frontend + Backend + Auth |
| Testing | 9-11 | Unit, Peer Testing, Bug Fix |
| Review | 12-13 | Demo, Retrospective |
| Deploy | 14-15 | Final Deploy + Presentation |

---

## 📊 ER Diagram (Simplified)

```
users (1) ──────── (M) complaints
users (1) ──────── (M) collection_logs
users (1) ──────── (M) collection_routes
vehicles (1) ───── (M) collection_routes
waste_bins (M) ─── (M) collection_routes [via route_bins]
waste_bins (1) ─── (M) collection_logs
waste_bins (1) ─── (M) complaints
```

---

## 🏆 Agile Methodology Applied

- **SCRUM** framework with 2-week sprint
- **User Stories** defined for each role
- **Sprint Backlog** with task priorities
- **Daily Standups** (what did, what will do, blockers)
- **Sprint Review** with mentor demo
- **Retrospective** for continuous improvement
