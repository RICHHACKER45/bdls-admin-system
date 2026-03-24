# A Web-Based Service Request Queuing and Notification System for Local Communities Using SMS Technology

This repository contains the **Admin Dashboard Module** and **Core Queue Management System** developed for the barangay e-governance capstone project. It is built with Vanilla PHP, modern JavaScript (Fetch API), and a secure MySQL database architecture.

## Features
* **Secure Authentication:** Session-based login with Bcrypt password hashing.
* **Real-Time Queue Monitoring:** Dynamic service request tracking (Pending, Processing, Released) using asynchronous JavaScript (AJAX).
* **Walk-in Request Generator:** Integrated transaction-based queuing for physically present residents.
* **Resident Masterlist:** Dynamic directory with client-side alphabet sorting and age-bracket filtering.
* **E-Logbook & System Audit Logs:** Automated tracking of released documents and admin actions for accountability.
* **Dashboard Analytics:** Real-time visual data representation using Chart.js.

## Database Structure
The system utilizes a relational database (`bdls_db`) with strict foreign key constraints to ensure data integrity.
* **`users`**: Stores both `admin` accounts (with hashed passwords) and `resident` demographic data.
* **`document_types`**: Lookup table for available barangay documents (Clearance, Indigency, etc.).
* **`service_requests`**: The core transactional table linking users, documents, and queue statuses.
* **`audit_logs`**: Tracks timestamped administrative actions.

## Validation Strategy & Security
* **SQL Injection Prevention:** 100% usage of PDO Prepared Statements for all database queries.
* **Cross-Site Scripting (XSS) Protection:** Output sanitization using `htmlspecialchars()`.
* **Authentication:** Password verification using PHP's native `password_verify` and `PASSWORD_BCRYPT`. Route protection via strict session checks.
* **Data Integrity:** Use of PDO Transactions (`beginTransaction` & `commit`) for multi-table inserts (e.g., Walk-in module).

## Project Structure
```text
/
├── app/
│   ├── api/                 # Backend PHP endpoints (AJAX handlers)
│   └── config/              # Secure database connection (PDO)
├── public/
│   ├── assets/
│   │   ├── css/             # Stylesheets (dashboard.css)
│   │   └── js/              # Client-side logic (main.js)
│   ├── index.php            # Main secured dashboard application
│   ├── login.php            # Authentication gateway
│   └── logout.php           # Session destroyer
└── README.md
```

## Setup & Installation

### 1. Database Configuration
1. Open **phpMyAdmin** and create a new database named `bdls_db`.
2. Import the provided SQL schema to generate the tables and dummy data.
3. Open `app/config/database.php` and update the credentials:
   ```php
   $host = 'localhost';
   $dbname = 'bdls_db';
   $username = 'root';
   $password = ''; // Leave blank if default XAMPP
   ```

### 2. Running the System Locally
1. Clone this repository inside your local server directory (e.g., `htdocs` for XAMPP):
   ```bash
   git clone [https://github.com/yourusername/your-repo-name.git](https://github.com/yourusername/your-repo-name.git)
   ```
2. Open your web browser and navigate to the login gateway:
   ```text
   http://localhost/your-folder-name/public/login.php
   ```

### 3. Default Admin Accounts
Use any of the following accounts to access the dashboard:
* **Usernames:** `jose`, `maricar`, `beatriz`, `annaleah`
* **Default Password:** `admin123`

*(Note: It is highly recommended to use the "Change Password" feature immediately upon first login).*