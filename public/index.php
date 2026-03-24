<?php
// 1. ILAGAY ITO SA PINAKATAAS PARA I-LOCK ANG PAGE
session_start();
if (!isset($_SESSION['admin_id'])) {
    // Kung walang suot na "ID Lace" (Session), sipain pabalik sa login page!
    header("Location: login");
    exit;
} 

// Securely include the database connection from the hidden app folder
require_once '../app/config/database.php';

?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>BDLS Admin Dashboard</title>
    <link rel="stylesheet" href="assets/css/dashboard.css" />
    <style>
        dialog::backdrop {
            background-color: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(3px); /* Optional: Papalabuhiin ng konti yung background */
        }
    </style>
  </head>
  <body>
    <div class="dashboard-container">
      <aside class="sidebar">
        <div class="logo-area">
          <h2>BDLS Admin</h2>
        </div>
        <nav class="nav-menu" role="tablist">
          <button class="nav-btn active" data-target="tab-requests" role="tab" aria-selected="true">
            Service Requests
          </button>
          <button class="nav-btn" data-target="tab-walkin" role="tab" aria-selected="false">
            Walk-in Request
          </button>
          <button class="nav-btn" data-target="tab-residents" role="tab" aria-selected="false">
            Resident Accounts
          </button>
          <button class="nav-btn" data-target="tab-elogbook" role="tab" aria-selected="false">
            Release E-Logbook
          </button>
          <button class="nav-btn" data-target="tab-audit" role="tab" aria-selected="false">
            System Audit Logs
          </button>
        </nav>
        <div class="logout-area">
            <button id="btnOpenChangePass" class="nav-btn" style="margin-bottom: 0.5rem; width: 100%;">Change Password</button>
            <button id="btnLogoutPrompt" class="nav-btn" style="width: 100%;">Log Out</button>
        </div>
      </aside>

      <main class="main-content">
        <header class="topbar">
          <h1>Hello, Admin <?php echo htmlspecialchars($_SESSION['admin_name']); ?>!</h1>
          <div class="search-bar">
            <input type="text" placeholder="Search..." />
          </div>
        </header>

        <section id="tab-requests" class="tab-panel active" role="tabpanel">
          <h2>Dashboard & Service Requests</h2>
          <div class="card-grid" style="grid-template-columns: 1fr 1fr 2fr; align-items: start;">
            <div class="card card-purple">
              <h3>New Requests</h3>
              <p class="big-number" id="count-pending">0</p>
            </div>
            <div class="card card-white">
              <h3>Processing</h3>
              <p class="big-number" id="count-processing">0</p>
            </div>
            <div class="card card-white" style="display: flex; justify-content: center; align-items: center; height: 150px;">
                <canvas id="statusChart"></canvas>
            </div>
          </div>
          <div class="table-container">
            <p><em>(Data table will be loaded here via AJAX)</em></p>
          </div>
        </section>

        <section id="tab-walkin" class="tab-panel" role="tabpanel" hidden>
            <h2>Create Walk-in Request</h2>
            <div class="card card-white" style="margin-top: 1rem;">
                <p style="margin-bottom: 1rem; color: #6b7280;">Encode a service request for residents physically present in the barangay hall.</p>
                <form id="addRequestForm" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; align-items: end;">
                    <div>
                        <label for="first_name" style="display: block; margin-bottom: 0.5rem; font-size: 0.875rem;">First Name</label>
                        <input type="text" id="first_name" name="first_name" required placeholder="e.g. Juan" style="width: 100%; padding: 0.75rem; border-radius: 8px; border: 1px solid #e5e7eb;">
                    </div>
                    <div>
                        <label for="last_name" style="display: block; margin-bottom: 0.5rem; font-size: 0.875rem;">Last Name</label>
                        <input type="text" id="last_name" name="last_name" required placeholder="e.g. Dela Cruz" style="width: 100%; padding: 0.75rem; border-radius: 8px; border: 1px solid #e5e7eb;">
                    </div>
                    <div style="grid-column: span 2;">
                        <label for="document_type_id" style="display: block; margin-bottom: 0.5rem; font-size: 0.875rem;">Document Requested</label>
                        <select id="document_type_id" name="document_type_id" required style="width: 100%; padding: 0.75rem; border-radius: 8px; border: 1px solid #e5e7eb;">
                            <option value="">Select Document...</option>
                            <option value="1">Barangay Clearance</option>
                            <option value="2">Certificate of Indigency</option>
                        </select>
                    </div>
                    <button type="submit" style="grid-column: span 2; background-color: var(--sidebar-bg); color: white; padding: 1rem; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; transition: 0.2s;">
                        Generate Queue Ticket
                    </button>
                </form>
                <p id="formMessage" style="margin-top: 1rem; font-size: 0.875rem; display: none; padding: 1rem; border-radius: 8px;"></p>
            </div>
        </section>

        <section id="tab-residents" class="tab-panel" role="tabpanel" hidden>
            <h2>Resident Accounts Directory</h2>
            <div class="card card-white" style="margin: 1.5rem 0; display: flex; gap: 1rem; align-items: center;">
                <div>
                    <label style="font-size: 0.875rem; font-weight: bold; margin-right: 0.5rem;">Sort by Name:</label>
                    <select id="sortName" style="padding: 0.5rem; border-radius: 5px; border: 1px solid #ccc;">
                        <option value="ASC">A to Z</option>
                        <option value="DESC">Z to A</option>
                    </select>
                </div>
                <div>
                    <label style="font-size: 0.875rem; font-weight: bold; margin-right: 0.5rem; margin-left: 1rem;">Filter Age:</label>
                    <select id="filterAge" style="padding: 0.5rem; border-radius: 5px; border: 1px solid #ccc;">
                        <option value="all">All Ages</option>
                        <option value="youth">Youth (15-30)</option>
                        <option value="adult">Adult (31-59)</option>
                        <option value="senior">Senior (60+)</option>
                    </select>
                </div>
                <button id="applyResidentFilter" style="padding: 0.5rem 1rem; background: var(--text-main); color: white; border: none; border-radius: 5px; cursor: pointer;">Apply Filter</button>
            </div>
            <div class="table-container" id="residents_table_container">
                <p><em>(Resident masterlist will be loaded here)</em></p>
            </div>
        </section>

        <section id="tab-elogbook" class="tab-panel" role="tabpanel" hidden>
          <h2>Release E-Logbook</h2>
          <div class="table-container"></div>
        </section>

        <section id="tab-audit" class="tab-panel" role="tabpanel" hidden>
          <h2>System Audit Logs</h2>
          <p style="margin-bottom: 1rem; color: #6b7280;">Track all admin actions and system changes here.</p>
          <div class="table-container" id="audit_table_container">
              <p>Loading logs...</p>
          </div>
        </section>
        <dialog id="changePassModal" style="padding: 2rem; border-radius: 15px; border: none; box-shadow: 0 10px 25px rgba(0,0,0,0.2); max-width: 400px; width: 100%; margin: auto;">
            <h3 style="margin-bottom: 1rem; color: var(--sidebar-bg);">Change Password</h3>
            <form id="changePassForm" style="display: flex; flex-direction: column; gap: 1rem;">
                <div>
                    <label style="font-size: 0.875rem; color: #6b7280;">Current Password</label>
                    <input type="password" id="current_password" required style="width: 100%; padding: 0.75rem; border: 1px solid #e5e7eb; border-radius: 8px;">
                </div>
                <div>
                    <label style="font-size: 0.875rem; color: #6b7280;">New Password</label>
                    <input type="password" id="new_password" required style="width: 100%; padding: 0.75rem; border: 1px solid #e5e7eb; border-radius: 8px;">
                </div>
                <div>
                    <label style="font-size: 0.875rem; color: #6b7280;">Confirm New Password</label>
                    <input type="password" id="confirm_password" required style="width: 100%; padding: 0.75rem; border: 1px solid #e5e7eb; border-radius: 8px;">
                </div>
                <p id="passMessage" style="font-size: 0.875rem; display: none; padding: 0.5rem; border-radius: 5px; text-align: center;"></p>
                <div style="display: flex; gap: 0.5rem; margin-top: 0.5rem;">
                    <button type="button" id="btnCloseChangePass" style="flex: 1; padding: 0.75rem; background: #e5e7eb; border: none; border-radius: 8px; cursor: pointer;">Cancel</button>
                    <button type="submit" style="flex: 1; padding: 0.75rem; background: var(--sidebar-bg); color: white; border: none; border-radius: 8px; cursor: pointer;">Save</button>
                </div>
            </form>
        </dialog>
        <dialog id="logoutModal" style="padding: 2rem; border-radius: 15px; border: none; box-shadow: 0 10px 25px rgba(0,0,0,0.2); max-width: 350px; width: 100%; margin: auto; text-align: center;">
            <h3 style="margin-bottom: 1rem; color: #dc2626;">Confirm Logout</h3>
            <p style="margin-bottom: 1.5rem; color: #6b7280; font-size: 0.875rem;">Are you sure you want to end your session?</p>
            <div style="display: flex; gap: 0.5rem;">
                <button type="button" id="btnCancelLogout" style="flex: 1; padding: 0.75rem; background: #e5e7eb; color: #374151; border: none; border-radius: 8px; cursor: pointer; font-weight: bold;">Cancel</button>
                <button type="button" id="btnConfirmLogout" style="flex: 1; padding: 0.75rem; background: #dc2626; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: bold;">Yes, Log Out</button>
            </div>
        </dialog>
      </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="assets/js/main.js"></script>
    
  </body>
</html>
