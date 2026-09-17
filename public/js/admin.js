// admin.js - UI Helpers only (no content generation)

/* NOTIFICATION TOGGLE */
function toggleNotification() {
    const panel = document.getElementById("notificationPanel");
    if (panel.style.display === "block") {
        panel.style.display = "none";
    } else {
        panel.style.display = "block";
        // Remove red count when opened
        document.querySelector(".notification-count").style.display = "none";
    }
}

/* CLOSE NOTIFICATION WHEN CLICKING OUTSIDE */
document.addEventListener("click", function (e) {
    const wrapper = document.querySelector(".notification-wrapper");
    const panel = document.getElementById("notificationPanel");
    if (panel && wrapper && !wrapper.contains(e.target)) {
        panel.style.display = "none";
    }
});

/* DROPDOWN TOGGLE (for sidebar sub-menus) */
function toggleDropdown(id) {
    const menu = document.getElementById(id);
    if (menu.style.display === "block") {
        menu.style.display = "none";
    } else {
        menu.style.display = "block";
    }
}

/* PDF MODAL */
function showPDFs(type) {
    const modal = document.getElementById('pdfModal');
    const body = document.getElementById('pdfModalBody');
    if (!modal || !body) return;
    modal.style.display = 'flex';

    body.innerHTML = `
        <div class="pdf-grid">
            <!-- Your PDF cards (copy from your original showPDFs) -->
        </div>
    `;
}

function closePDFModal() {
    const modal = document.getElementById('pdfModal');
    if (modal) modal.style.display = 'none';
}

/* FILTER REPORTS (for monitoring page) */
function filterReports() {
    const report = document.getElementById("reportType");
    const category = document.getElementById("categoryType");
    const result = document.getElementById("monitorResult");
    if (!report || !category || !result) return;

    // Your filtering logic (keep as is)
    if (report.value === "Proposal" && category.value === "Internally Funded") {
        result.innerHTML = `...`; // your filtered HTML
    } else {
        result.innerHTML = `<div class="card" style="text-align:center;padding:60px;color:#777;">No reports found.</div>`;
    }
}