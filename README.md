# 📚 City Public Library — Modern Library Management System

[![Live Demo](https://img.shields.io/badge/Live_Demo-Vercel-black?style=for-the-badge&logo=vercel)](https://library-management-system-nsxt.vercel.app)
[![PHP Version](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)
[![Database](https://img.shields.io/badge/Database-MySQL%20%7C%20TiDB_Cloud-00618A?style=for-the-badge&logo=mysql&logoColor=white)](https://tidbcloud.com/)
[![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)](LICENSE)
[![Theme](https://img.shields.io/badge/Theme-Light%20%2F%20Dark-blueviolet?style=for-the-badge)](#-light--dark-theme-mode)

> **A feature-rich, modern, and responsive full-stack Library Management System built with PHP, MySQL/TiDB Cloud, and modern Glassmorphism UI. Fully deployed on Vercel with 24/7 cloud database connectivity.**

---

## 🌐 Live Website & Demo

🔗 **Live URL:** [https://library-management-system-nsxt.vercel.app](https://library-management-system-nsxt.vercel.app)

---

## 🔑 Demo Login Credentials

You can test all system portals using the pre-configured accounts below:

| Role | Username | Password | Key Responsibilities |
| :--- | :--- | :--- | :--- |
| **👑 Super Admin** | `superadmin` | `admin123` | System Settings, Admin Accounts, DB Backup/Restore, Audit Logs, Fine Config |
| **🛡️ Library Admin** | `admin` | `admin123` | Books, Categories, Members, Fine Approvals, Overdue Management, Reports |
| **💼 Library Staff** | `staff` | `staff123` | Check Out (Issue), Check In (Return), Member Info Lookup, Shelf Management |
| **📖 Member** | `member1` | `member123` | Book Search, Reservations, Due Dates, Fine Payments via Telebirr/Mobile Banking |

> *New users can also register freely on the [Registration Page](https://library-management-system-nsxt.vercel.app/register.php).*

---

## ✨ Key Features

### 1. 👥 Multi-Role Role-Based Access Control (RBAC)
- **5 Distinct Portals**: Superadmin, Admin, Staff, Member, and Public Guest.
- Protected session routing and unauthorized access blocking.

### 2. 🌗 Light & Dark Theme Mode
- Built-in theme switcher with smooth animated transitions.
- Persistent user theme choice stored in browser `localStorage`.

### 3. 👤 Dynamic Profile & Avatar System
- Profile photo upload supporting JPG, PNG, and WebP formats.
- Live avatar image and stylized initials rendered seamlessly across all navbars and topbars.

### 4. 📚 Comprehensive Book & Shelf Management
- Full CRUD catalog with ISBN lookup, category tagging, cover image uploads, and shelf location mapping.
- Live inventory tracking for available vs. issued copies.

### 5. 🔄 Automated Borrowing, Returns & Fine Engine
- Instant checkout and check-in workflows.
- Automatic fine calculation based on grace periods, daily rates, and maximum fine caps.

### 6. 💳 Telebirr & Mobile Banking Receipt Upload
- Members can pay fines via **Telebirr**, **CBE Mobile Banking**, or **Bank Slip Deposit**.
- Direct screenshot/receipt upload with transaction reference numbers.
- Admin review dashboard to verify, approve, or reject payment proofs with printable official fine receipts.

### 7. 🔒 Secure Authentication & Real SMTP Password Reset
- Secure password hashing using PHP `password_hash()` (Bcrypt).
- Password reset links delivered directly to user emails via PHPMailer with customizable SMTP settings.

### 8. 🛡️ Superadmin Tools & Native Database Backup
- One-click native database backup dumper (pure SQL export).
- Instant database restore and backup file downloads.
- Real-time audit log tracking every user action with IP logging.

---

## 🛠️ Tech Stack

- **Frontend**: HTML5, CSS3 Custom Properties (Variables), JavaScript (ES6+), Inter Font, FontAwesome 6
- **Backend**: PHP 8.2+ (PDO with Parameterized Prepared Statements)
- **Database**: MySQL 8.0+ / TiDB Cloud Serverless (Distributed MySQL-compatible Cloud Database)
- **Email Delivery**: PHPMailer with SMTP Support
- **Hosting & Deployment**: Vercel Serverless Functions (`vercel-php`)

---

## 📂 Project Directory Structure

```
Library_Management_System/
├── admin/                  # Library Admin Management Portal
│   ├── books/              # Book Catalog CRUD
│   ├── categories/         # Category CRUD
│   ├── fines/              # Fine Collection & Receipt Review
│   ├── members/            # Member Management
│   ├── inventory/          # Inventory Reports
│   ├── issue/              # Book Issue/Checkout
│   ├── returns/            # Book Returns
│   └── dashboard.php       # Admin Analytics Dashboard
├── api/                    # Vercel Serverless Gateway Router
│   └── index.php
├── assets/                 # Static Styles, Scripts & Theme Tokens
│   ├── css/
│   │   ├── auth.css
│   │   └── style.css       # Light/Dark Theme CSS Variables
│   └── js/
│       └── main.js         # Theme Switcher & Utility Scripts
├── config/                 # Environment & Database Configuration
│   ├── db.php              # Dynamic PDO Database Connection (Local + Cloud)
│   └── mail.php            # SMTP Mailer Settings
├── db/                     # Database Schema & Seed Data
│   └── library_db.sql      # Full MySQL Database Dump
├── guest/                  # Public Guest Browsing Pages
│   ├── catalog.php
│   ├── book_details.php
│   ├── library_info.php
│   └── index.php
├── includes/               # Shared Components & Helper Functions
│   ├── PHPMailer/          # SMTP Mail Library
│   ├── functions.php       # Global Helper Functions & Avatar Renderer
│   ├── header.php
│   ├── sidebar.php
│   ├── mailer.php
│   └── footer.php
├── member/                 # Member Portal (Borrows, Fines, Reservations)
├── staff/                  # Staff Portal (Issue, Return, Shelves, Member Lookup)
├── superadmin/             # Superadmin Portal (Settings, Admins, Backup, Logs)
├── uploads/                # User Profile Photos & Fine Receipts
├── vercel.json             # Vercel Serverless Deployment Configuration
├── index.php               # Root Application Entrypoint
├── login.php               # Authentication Gateway
├── register.php            # Member Registration
├── forgot_password.php     # Password Recovery
└── reset_password.php      # Secure Password Reset
```

---

## 💻 Local Installation (WAMP / XAMPP)

1. **Clone the Repository:**
   ```bash
   git clone https://github.com/Yimencodehub/Library-Management-System.git
   ```

2. **Move to Web Server Root:**
   - For **WAMP**: Copy to `C:\wamp64\www\Library_Management_System\`
   - For **XAMPP**: Copy to `C:\xampp\htdocs\Library_Management_System\`

3. **Import Database:**
   - Open **phpMyAdmin** (`http://localhost/phpmyadmin/`).
   - Create a database named `library_db`.
   - Import the file [`db/library_db.sql`](db/library_db.sql).

4. **Launch Application:**
   - Open your browser and navigate to:
     ```
     http://localhost/Library_Management_System/
     ```

---

## ☁️ Cloud Deployment (Vercel + TiDB Cloud)

1. **Database:** Create a free Serverless MySQL cluster on [TiDB Cloud](https://tidbcloud.com/) and run [`db/library_db.sql`](db/library_db.sql).
2. **Vercel Setup:** Import this repository into [Vercel](https://vercel.com/).
3. **Environment Variables:** In Vercel Project Settings ➔ Environment Variables, configure:
   - `DB_HOST` = `your-tidb-cluster-host`
   - `DB_PORT` = `4000`
   - `DB_USER` = `your-cluster-username`
   - `DB_PASS` = `your-cluster-password`
   - `DB_NAME` = `library_db`

---

## 👨‍💻 Author & Maintainer

- **Developer:** [Yimen Anmaw](https://github.com/Yimencodehub)
- **GitHub:** [@Yimencodehub](https://github.com/Yimencodehub)
- **Project Repository:** [Library-Management-System](https://github.com/Yimencodehub/Library-Management-System)

---

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.