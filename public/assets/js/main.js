// ==========================================
// 1. SIDEBAR TAB NAVIGATION LOGIC
// ==========================================
document.addEventListener('DOMContentLoaded', () => {
    const navButtons = document.querySelectorAll('.nav-btn[data-target]');
    const tabPanels = document.querySelectorAll('.tab-panel');

    navButtons.forEach(button => {
        button.addEventListener('click', () => {
            navButtons.forEach(btn => {
                btn.classList.remove('active');
                btn.setAttribute('aria-selected', 'false');
            });
            tabPanels.forEach(panel => {
                panel.classList.remove('active');
                panel.setAttribute('hidden', 'true');
            });

            button.classList.add('active');
            button.setAttribute('aria-selected', 'true');

            const targetId = button.getAttribute('data-target');
            const targetPanel = document.getElementById(targetId);
            if (targetPanel) {
                targetPanel.classList.add('active');
                targetPanel.removeAttribute('hidden');
            }
        });
    });
});

// ==========================================
// 2. MAIN DASHBOARD: SERVICE REQUESTS & CHARTS
// ==========================================
let myChart = null;

async function loadDashboardStats() {
    try {
        const response = await fetch('../app/api/get_chart_data.php');
        const result = await response.json();

        if (result.success) {
            let pending = 0, processing = 0, released = 0;

            result.data.forEach(item => {
                if (item.status === 'Pending') pending = item.total;
                if (item.status === 'Processing') processing = item.total;
                if (item.status === 'Released') released = item.total;
            });

            // Update Numbers
            const countPendingEl = document.getElementById('count-pending');
            const countProcessingEl = document.getElementById('count-processing');
            if (countPendingEl) countPendingEl.textContent = pending;
            if (countProcessingEl) countProcessingEl.textContent = processing;

            // Render Chart
            const ctx = document.getElementById('statusChart');
            if (!ctx) return;

            if (myChart) myChart.destroy();

            myChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Pending', 'Processing', 'Released'],
                    datasets: [{
                        data: [pending, processing, released],
                        backgroundColor: ['#fef3c7', '#dbeafe', '#d1fae5'], 
                        borderColor: ['#f59e0b', '#3b82f6', '#10b981'],
                        borderWidth: 1
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'right' } } }
            });
        }
    } catch (error) {
        console.error('Chart Data Error:', error);
    }
}

async function loadServiceRequests() {
    const tableContainer = document.querySelector('#tab-requests .table-container');
    if (!tableContainer) return;
    tableContainer.innerHTML = '<p>Loading latest requests...</p>';

    try {
        const response = await fetch('../app/api/get_requests.php');
        const result = await response.json();

        if (result.success && result.data.length > 0) {
            let tableHTML = `
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Queue #</th>
                            <th>Resident Name</th>
                            <th>Document</th>
                            <th>Status</th>
                            <th>Date Requested</th>
                            <th>Actions</th> 
                        </tr>
                    </thead>
                    <tbody>
            `;

            result.data.forEach(req => {
                const date = new Date(req.created_at).toLocaleDateString('en-PH');
                let actionBtn = '';
                if (req.status === 'Pending') {
                    actionBtn = `<button onclick="updateStatus(${req.id}, 'Processing')" style="background:#4f46e5; color:white; padding:5px 10px; border:none; border-radius:5px; cursor:pointer;">Process</button>`;
                } else if (req.status === 'Processing') {
                    actionBtn = `<button onclick="updateStatus(${req.id}, 'Released')" style="background:#059669; color:white; padding:5px 10px; border:none; border-radius:5px; cursor:pointer;">Release</button>`;
                }
                
                tableHTML += `
                    <tr>
                        <td><strong>${req.queue_number}</strong></td>
                        <td>${req.first_name} ${req.last_name}</td>
                        <td>${req.document_name}</td>
                        <td><span class="status-badge status-${req.status.toLowerCase()}">${req.status}</span></td>
                        <td>${date}</td>
                        <td>${actionBtn}</td>
                    </tr>
                `;
            });
            tableHTML += `</tbody></table>`;
            tableContainer.innerHTML = tableHTML;
        } else {
            tableContainer.innerHTML = '<p>No service requests found.</p>';
        }
    } catch (error) {
        tableContainer.innerHTML = '<p style="color: red;">Failed to load data.</p>';
    }
}

async function updateStatus(id, newStatus) {
    if (!confirm(`Mark this request as ${newStatus}?`)) return;

    try {
        const payload = { id: id, status: newStatus };
        const response = await fetch('../app/api/update_status.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });
        const result = await response.json();
        
        if (result.success) {
            // REFRESH LAHAT KAPAG MAY PININDOT NA ACTION BUTTON
            loadServiceRequests();
            loadDashboardStats();
            loadLogbook();
            loadAuditLogs();
        } else {
            alert('Failed to update: ' + result.message);
        }
    } catch (error) {
        alert('Server connection error.');
    }
}

// ==========================================
// 3. WALK-IN REQUEST LOGIC
// ==========================================
const addRequestForm = document.getElementById('addRequestForm');
const formMessage = document.getElementById('formMessage');

if (addRequestForm) {
    addRequestForm.addEventListener('submit', async function(e) {
        e.preventDefault(); 

        const payload = {
            first_name: document.getElementById('first_name').value.trim(),
            last_name: document.getElementById('last_name').value.trim(),
            document_type_id: document.getElementById('document_type_id').value
        };
        
        try {
            const response = await fetch('../app/api/add_request.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });
            const result = await response.json();
            
            formMessage.style.display = 'block';
            if (result.success) {
                formMessage.style.backgroundColor = '#d1fae5';
                formMessage.style.color = '#065f46';
                formMessage.textContent = `${result.message} Queue: ${result.queue_number}`;
                addRequestForm.reset(); 
                
                // REFRESH ANG DASHBOARD AT TABLE KAPAG MAY BAGONG WALK-IN
                loadServiceRequests(); 
                loadDashboardStats();
                if (typeof loadAuditLogs === "function") {
                    loadAuditLogs(); 
                }
            } else {
                formMessage.style.backgroundColor = '#fee2e2';
                formMessage.style.color = '#991b1b';
                formMessage.textContent = result.message;
            }
        } catch (error) {
            formMessage.style.display = 'block';
            formMessage.style.color = 'red';
            formMessage.textContent = 'Server error. Please check your connection.';
        }
    });
}

// ==========================================
// 4. RESIDENT ACCOUNTS & FILTERING
// ==========================================
let allResidents = [];

async function loadResidents() {
    const container = document.getElementById('residents_table_container');
    if (!container) return;
    container.innerHTML = '<p>Loading masterlist...</p>';

    try {
        const response = await fetch('../app/api/get_all_residents.php');
        const result = await response.json();
        if (result.success) {
            allResidents = result.data; 
            renderResidentsTable(allResidents); 
        } else {
            container.innerHTML = '<p style="color:red;">Failed to load residents.</p>';
        }
    } catch (error) {
        container.innerHTML = '<p style="color:red;">Server connection error.</p>';
    }
}

function renderResidentsTable(data) {
    const container = document.getElementById('residents_table_container');
    if (data.length === 0) {
        container.innerHTML = '<p>No residents match your filter.</p>';
        return;
    }
    let html = `
        <table class="data-table">
            <thead>
                <tr><th>Name</th><th>Date of Birth</th><th>Age</th><th>Status</th></tr>
            </thead>
            <tbody>
    `;
    data.forEach(user => {
        const dobText = user.date_of_birth ? new Date(user.date_of_birth).toLocaleDateString('en-PH') : '<em>Walk-in (Not specified)</em>';
        const ageText = user.age !== 'N/A' ? `${user.age} yrs old` : 'N/A';
        html += `
            <tr>
                <td><strong>${user.first_name} ${user.last_name}</strong></td>
                <td>${dobText}</td>
                <td>${ageText}</td>
                <td><span class="status-badge status-released">Verified</span></td>
            </tr>
        `;
    });
    html += `</tbody></table>`;
    container.innerHTML = html;
}

const btnApplyFilter = document.getElementById('applyResidentFilter');
if (btnApplyFilter) {
    btnApplyFilter.addEventListener('click', () => {
        const sortOrder = document.getElementById('sortName').value; 
        const ageFilter = document.getElementById('filterAge').value; 
        let filteredData = [...allResidents]; 

        if (ageFilter !== 'all') {
            filteredData = filteredData.filter(user => {
                if (user.age === 'N/A') return false; 
                if (ageFilter === 'youth') return user.age >= 15 && user.age <= 30;
                if (ageFilter === 'adult') return user.age >= 31 && user.age <= 59;
                if (ageFilter === 'senior') return user.age >= 60;
                return true;
            });
        }
        filteredData.sort((a, b) => {
            const nameA = a.first_name.toLowerCase();
            const nameB = b.first_name.toLowerCase();
            return sortOrder === 'ASC' ? nameA.localeCompare(nameB) : nameB.localeCompare(nameA);
        });
        renderResidentsTable(filteredData);
    });
}

// ==========================================
// 5. RELEASE E-LOGBOOK
// ==========================================
async function loadLogbook() {
    const logbookContainer = document.querySelector('#tab-elogbook');
    if (!logbookContainer) return;
    
    let tableContainer = logbookContainer.querySelector('.table-container');
    if (!tableContainer) {
        tableContainer = document.createElement('div');
        tableContainer.className = 'table-container';
        logbookContainer.appendChild(tableContainer);
    }
    
    tableContainer.innerHTML = '<p>Loading logbook...</p>';
    
    try {
        const response = await fetch('../app/api/get_logbook.php');
        const result = await response.json();

        if (result.success && result.data.length > 0) {
            let tableHTML = `
            <table class="data-table">
                <thead><tr><th>Queue #</th><th>Resident Name</th><th>Document</th><th>Status</th><th>Date Released</th></tr></thead>
                <tbody>
            `;
            result.data.forEach(req => {
                const date = new Date(req.created_at).toLocaleString('en-PH');
                tableHTML += `
                <tr>
                    <td><strong>${req.queue_number}</strong></td>
                    <td>${req.first_name} ${req.last_name}</td>
                    <td>${req.document_name}</td>
                    <td><span class="status-badge status-released">${req.status}</span></td>
                    <td>${date}</td>
                </tr>
                `;
            });
            tableHTML += `</tbody></table>`;
            tableContainer.innerHTML = tableHTML;
        } else {
            tableContainer.innerHTML = '<p>No released documents yet.</p>';
        }
    } catch (error) {
        tableContainer.innerHTML = '<p style="color: red;">Failed to load logbook.</p>';
    }
}

// ==========================================
// 6. SYSTEM AUDIT LOGS
// ==========================================
async function loadAuditLogs() {
    const container = document.getElementById('audit_table_container');
    if (!container) return;

    try {
        const response = await fetch('../app/api/get_audit_logs.php');
        const result = await response.json();

        if (result.success && result.data.length > 0) {
            let html = `
                <table class="data-table">
                    <thead><tr><th>Date & Time</th><th>Admin Personnel</th><th>Action Performed</th></tr></thead>
                    <tbody>
            `;
            result.data.forEach(log => {
                const date = new Date(log.created_at).toLocaleString('en-PH');
                html += `
                    <tr>
                        <td style="color: #6b7280; font-size: 0.875rem;">${date}</td>
                        <td><strong>${log.first_name} ${log.last_name}</strong></td>
                        <td>${log.action}</td>
                    </tr>
                `;
            });
            html += `</tbody></table>`;
            container.innerHTML = html;
        } else {
            container.innerHTML = '<p>No audit logs recorded yet.</p>';
        }
    } catch (error) {
        container.innerHTML = '<p style="color:red;">Failed to load logs.</p>';
    }
}

// ==========================================
// 7. INITIALIZE EVERYTHING ON PAGE LOAD
// ==========================================
loadDashboardStats();
loadServiceRequests();
loadResidents();
loadLogbook();
loadAuditLogs();

// ==========================================
// 8. CHANGE PASSWORD MODAL LOGIC
// ==========================================
const btnOpenChangePass = document.getElementById('btnOpenChangePass');
const btnCloseChangePass = document.getElementById('btnCloseChangePass');
const changePassModal = document.getElementById('changePassModal');
const changePassForm = document.getElementById('changePassForm');
const passMessage = document.getElementById('passMessage');

if (btnOpenChangePass && changePassModal) {
    // Buksan ang modal
    btnOpenChangePass.addEventListener('click', () => {
        changePassModal.showModal();
    });

    // Isara ang modal at i-clear ang form
    btnCloseChangePass.addEventListener('click', () => {
        changePassModal.close();
        changePassForm.reset();
        passMessage.style.display = 'none';
    });

    // Handle form submission
    changePassForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const payload = {
            current_password: document.getElementById('current_password').value,
            new_password: document.getElementById('new_password').value,
            confirm_password: document.getElementById('confirm_password').value
        };

        passMessage.style.display = 'block';
        passMessage.style.color = '#4f46e5';
        passMessage.textContent = 'Processing...';

        try {
            const response = await fetch('../app/api/change_password.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });
            const result = await response.json();

            if (result.success) {
                passMessage.style.color = '#065f46';
                passMessage.style.backgroundColor = '#d1fae5';
                passMessage.textContent = result.message;

                // ---> DAGDAG MO ITONG LINYA NA ITO DITO <---
                if (typeof loadAuditLogs === "function") {
                    loadAuditLogs(); 
                }
                
                // Isara automatically after 2 seconds
                setTimeout(() => {
                    changePassModal.close();
                    changePassForm.reset();
                    passMessage.style.display = 'none';
                }, 2000);
            } else {
                passMessage.style.color = '#dc2626';
                passMessage.style.backgroundColor = '#fee2e2';
                passMessage.textContent = result.message;
            }
        } catch (error) {
            passMessage.style.color = '#dc2626';
            passMessage.textContent = 'Server connection failed.';
        }
    });
}

// ==========================================
// 9. LOGOUT MODAL LOGIC
// ==========================================
const btnLogoutPrompt = document.getElementById('btnLogoutPrompt');
const logoutModal = document.getElementById('logoutModal');
const btnCancelLogout = document.getElementById('btnCancelLogout');
const btnConfirmLogout = document.getElementById('btnConfirmLogout');

if (btnLogoutPrompt && logoutModal) {
    // 1. Buksan ang modal kapag kinlik ang Log Out sa sidebar
    btnLogoutPrompt.addEventListener('click', () => {
        logoutModal.showModal();
    });

    // 2. Isara ang modal kapag nag-Cancel
    btnCancelLogout.addEventListener('click', () => {
        logoutModal.close();
    });

    // 3. Ituloy ang logout kapag nag-Yes
    btnConfirmLogout.addEventListener('click', () => {
        window.location.href = 'logout';
    });
}