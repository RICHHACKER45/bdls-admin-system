# Case Problem #1: Video Presentation Outline

---

## 🎤 Speaker 1: Act 1 - Introduction & Live Demo (The "User Experience")
*Goal: Ipakita agad na working at "polished" ang system.*

**1. Introduction & Tech Stack**
* Banggitin ang Project Title.
* **Stack:** Vanilla PHP 8.x, MySQL (MariaDB), Modern JS (Fetch API), Chart.js, at CSS for UI.
* **Goal:** Makabuo ng secured, automated queuing system na may real-time monitoring at Audit Logs.

**2. Live Demo Flow**
* **The Gateway:** Ipakita ang Login page. Ipaliwanag ang Security (Bcrypt hashing).
* **The Action:** Mag-login at mag-generate ng **Walk-in Request**.
* **Real-time Validation:** Ipakita na nag-update ang "New Requests" counter at Table nang hindi nagre-reload ang page (AJAX).
* **System Audit Logs:** Ipakita agad ang Logs tab para patunayan na na-record ang action mo "just now."

---

## 🎤 Speaker 2: Act 2 - Database Architecture & Auto-Setup (The "Brain")
*Goal: Ipakita ang relational integrity at ang automation ng system.*

**3. Database Design First**
* Buksan ang **Designer Tab** sa phpMyAdmin.
* Ipaliwanag ang tables: `users`, `document_types`, `service_requests`, at `audit_logs`.
* **Foreign Keys:** Ipaliwanag kung paano naka-link ang `service_requests` sa `users` (residents) at `document_types`.
* Ipaliwanag bakit mahalaga ang `ON DELETE CASCADE` para sa data integrity.

**4. Database Connection & Auto-Setup**
* Buksan ang `app/config/database.php`.
* **The Innovation:** Ipaliwanag ang "Auto-Setup" logic. Kung wala pang database, ang PHP mismo ang gagawa nito gamit ang `database/init.sql`.
* Ipakita ang code block kung saan tinitignan ng PDO kung existing na ang `bdls_db`.

---

## 🎤 Speaker 3: Act 3 - Folder Structure & Security (The "Skeleton")
*Goal: Ipakita ang "Clean Code" at ang pag-protect sa system.*

**5. Project Structure**
* Ipakita ang folder layout sa VSCode.
* Ipaliwanag ang **Separation of Concerns**:
    * `public/`: Ang tanging folder na "exposed" sa web.
    * `app/`: Dito nakatago ang logic, models, at config.
    * `database/`: Dito nakatago ang schema.

**6. Security Hardening (.htaccess)**
* Ipakita ang **`.htaccess`** files.
* **Directory Protection:** Ipaliwanag na naka-block ang folder viewing (`Options -Indexes`).
* **Clean URLs:** Ipakita kung paano naging `/login` lang ang URL imbes na `/login.php` para itago ang technology stack.
* Ipakita ang blank `index.php` files bilang silent guardians sa backend folders.

---

## 🎤 Speaker 4: Act 4 - The Engine, UI Features & Vision (The "How" & "Next")
*Goal: Ipakita ang advanced JS logic at ang kinabukasan ng project.*

**7. Main Entry Point & Read Helpers (APIs)**
* Buksan ang `public/index.php`. Ipaliwanag ang flow: load dependencies -> check session -> render dashboard.
* Ipakita ang mga API files (e.g., `get_stats.php`) na nagbabalato ng JSON data para sa charts at tables.

**8. Controllers & The Logic Engine**
* Buksan ang `app/api/update_status.php`.
* Ipaliwanag ang **Audit Log integration**: Tuwing mag-u-update ng status, tinatawag ang `logAction()` function.
* Ipakita sa `main.js` ang `async/await` Fetch calls na kumakausap sa backend.

**9. UI/UX Features**
* Ipakita ang **Logout Confirmation Modal** at **Change Password modal**.
* Ipakita ang **Chart.js** integration para sa visual analytics ng barangay requests.

**10. The Vision (Final Statement)**
* Ipaliwanag na ang Case Problem #1 na ito ang foundation para sa kabuuan ng Capstone niyo.
* **Next Phase:** Ang pag-integrate ng **SMS Gateway API** (Semaphore) para sa automated notifications pagka-click ng "Release" button.