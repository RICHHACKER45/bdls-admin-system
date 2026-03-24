# A Web-Based Service Request Queuing and Notification System for Local Communities Using SMS Technology

This repository contains the **Admin Dashboard Module** and **Core Queue Management System** developed for a barangay e-governance capstone project. This system features an automated database initializer and advanced security hardening.

## 🚀 Key Features
* **Automated Database Initializer:** Zero-manual-import setup. The system automatically creates the database and tables upon first run.
* **Real-Time Audit Logging:** Every administrative action (Login, Logout, Walk-in, Status Update, Password Change) is timestamped and recorded for accountability.
* **AJAX-Powered UI:** Seamless data fetching and UI updates using Fetch API (Asynchronous JavaScript) for a "Single Page Application" feel.
* **Secure Authentication:** Session-based security with password hashing using `PASSWORD_BCRYPT`.
* **Dynamic Analytics:** Real-time visual data representation of service statuses using Chart.js.

## 🔒 Security & Validation Strategy
* **Directory Listing Protection:** Blocked unauthorized folder viewing via `.htaccess`.
* **Clean URLs:** Extensionless routing (e.g., `/login` instead of `/login.php`) to obscure technology stack.
* **SQL Injection Prevention:** 100% usage of **PDO Prepared Statements**.
* **Backend Isolation:** Restricted web access to `app/config/` and `database/` folders.
* **Silent Fallbacks:** Blank `index.php` files in backend directories to prevent information leakage.

## 📂 Project Structure
```text
/
├── app/
│   ├── api/                 # Backend PHP endpoints (AJAX & Log triggers)
│   └── config/              # DB Connection & Auto-Setup Logic
├── database/
│   └── init.sql             # SQL Schema & Seed Data (Jose, Maricar, etc.)
├── public/
│   ├── assets/              # CSS/JS (main.js handles UI refreshes)
│   ├── index.php            # Main Dashboard (Clean URL)
│   ├── login.php            # Entry Point
│   └── logout.php           # Secure Session Destroyer
├── .htaccess                # Global Security & Redirects
└── README.md
```

## 🛠️ Setup & Installation

### 1. Simple Database Setup
1. Open **XAMPP** and start **Apache** and **MySQL**.
2. Open `app/config/database.php` and set your MySQL credentials (default is `root` with no password).
3. **That's it!** You don't need to import SQL manually. The system will detect if `bdls_db` is missing and will execute `database/init.sql` automatically upon your first visit to the site.

### 2. Accessing the System
1. Place the folder in your `htdocs` directory.
2. Open your browser and go to: `http://localhost/your-folder-name/`
3. The system will automatically redirect you to the Login page.

### 3. Default Admin Accounts
* **Usernames:** `jose`, `maricar`, `beatriz`, `annaleah`
* **Password:** `admin123`