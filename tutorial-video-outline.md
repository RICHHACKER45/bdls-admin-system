# Case Problem #1: Video Presentation Outline

## Act 1: The "Hook" and System Demo (2-3 mins)
*Goal: Show the working product first before showing the code.*
1. **The Gateway:** Open the browser to `login.php`. Explain that the system is secured and requires authentication. Log in using your assigned admin account.
2. **The Dashboard:** Highlight the dynamic greeting ("Hello, Admin [Name]!") and the real-time statistic numbers on the cards.
3. **The Walk-in Process:** - Navigate to the "Walk-in Request" tab.
   - Type a dummy name, select a document, and click Generate.
   - Emphasize that the page *did not reload*, but the "New Requests" counter went up, and the new queue number appeared in the table.
4. **Action & Analytics:** - Click "Process" and "Release" on a pending request. 
   - Point out how the Chart.js Pie Chart updates in real-time.
   - Navigate to the "System Audit Logs" tab to prove that the exact action was securely recorded under your specific admin name.

## Act 2: The "Behind the Scenes" Architecture (3-4 mins)
*Goal: Prove your knowledge of the backend and database design.*
1. **The Database (phpMyAdmin):** Show the relational structure (`users`, `service_requests`, `document_types`, `audit_logs`). Briefly explain how Foreign Keys keep the data connected.
2. **The Project Structure (VSCode):** Show the file tree. Explain the separation of concerns: `public/` is for the UI, and `app/` is hidden away for backend logic and security.
3. **Security First:** Open `add_request.php` or `auth.php`. Explain the use of **PDO Prepared Statements** to prevent SQL Injection and the use of **Bcrypt** for hashing passwords.

## Act 3: The JavaScript Engine (2 mins)
*Goal: Highlight modern frontend integration.*
1. **The Fetch API:** Open `main.js`. Show the asynchronous functions (`async/await`). 
2. Explain that this AJAX approach replaces outdated form submissions, allowing the UI to fetch data from PHP endpoints seamlessly behind the scenes.

## Act 4: The Vision (1 min)
*Goal: Connect this assignment to the final Capstone goal.*
1. **Closing Statement:** While showing the Service Requests table, explain the next major phase of the system. 
2. State that the upcoming integration will attach an SMS Gateway API (like Semaphore) to the "Release" button, enabling automated text notifications to the local residents.
