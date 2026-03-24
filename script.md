# 🎬 Presentation Script: Basic Barangay Admin-Dashboard

**Project:** A Web-Based Service Request Queuing and Notification System for Local Communities

---

## 🎤 Speaker 1: The Intro & Live Demo

**Goal:** Ipakita na "smooth" at "working" ang system sa mata ng user.

* **Greeting:** "Good day everyone! Kami ang Team [Team Name], at ngayon ay i-pe-present namin ang aming project: *A Basic Admin-Dashboard for our local barangay*."
* **Tech Stack:** "Ang system na ito ay binuo gamit ang **Vanilla PHP 8.x** para sa backend, **MySQL** para sa database, at **Modern JavaScript (Fetch API)** para sa real-time updates."
* **Demo (Login):** "**(Action: Ipakita ang Login Page)** Mapapansin niyo na secured ang ating login. Gumagamit kami ng **Bcrypt hashing** para sa passwords."
* **Demo (Walk-in):** "**(Action: Pumunta sa Walk-in Tab, mag-fill up ng names, i-click ang Generate)** Dito sa Walk-in module, pag-click ko ng Generate, lalabas agad ang Queue Number. Mapapansin niyo na hindi nag-reload ang page dahil sa aming **AJAX workaround**."
* **Audit Logs:** "At para sa accountability, bawat click ay recorded. **(Action: Pumunta sa Audit Logs)** Ayan, makikita niyo na naka-record na 'just now' na nag-generate ako ng request."

---

## 🎤 Speaker 2: Database Architecture & Auto-Setup

**Goal:** Ipaliwanag ang "puso" ng system at ang automation logic.

* **Database Design:** "Punta naman tayo sa likod ng system—ang aming Database. **(Action: Buksan ang Designer tab sa phpMyAdmin)** Mapapansin niyo ang relational structure natin sa `bdls_db`."
* **Relationships:** "Gumamit kami ng **Foreign Keys** at `ON DELETE CASCADE` para masiguro ang database consistency at iwas sa 'garbage data'."
* **The Innovation (Auto-Setup):** "Ang highlight ng aming setup ay ang **Auto-Initialization**. **(Action: Buksan ang app/config/database.php)** Imbes na mag-manual import ng SQL, ang system na mismo ang gagawa ng database at tables sa unang run gamit ang aming `init.sql` script."

---

## 🎤 Speaker 3: Project Structure & Security Hardening

**Goal:** Ipakita ang "Skeleton" ng system at kung paano ito pinu-protektahan.

* **Folders:** "Dito naman sa VSCode, makikita niyo ang aming **Project Structure**. **(Ac ***eparation of Concerns*. Ang sensitive logic, models, at configs ay nakatago sa `app` folder, malayo sa public access."
* **Security Layers (.htaccess):** "**(Action: Buksan ang .htaccess sa root)** Nag-implement kami ng security hardening:
  1. **Directory Protection:** Binlock namin ang folder viewing para hindi makita ang files.
  2. **Clean URLs:** Tinanggal namin ang `.php` extensions sa URL para mas maging malinis at secured ang routing.
  3. **Silent Guardians:** Mayroon din kaming blank `index.php` files sa backend folders as fallback security."

---

## 🎤 Speaker 4: Backend Logic & Conclusion

**Goal:** I-explain ang "Engine" at ang susunod na plano sa project.

* **The Engine (Fetch API):** "Para sa logic, gumamit kami ng **Async/Await** at **Fetch API**. Ang JavaScript na mismo ang kumakausap sa aming PHP endpoints sa background para sa real-time updates."
* **Audit Logger Logic:** "Bawat update ay dumadaan sa aming `logAction()` helper function sa backend. Ito ang nagtatanim ng record sa database tuwing may admin action para sa audit trail."
* **Closing:** "Ang basic admin-dashboard na ito ang magsisilbing foundation para sa mga susunod pang features ng system, gaya ng automated SMS notifications. Maraming salamat!"
