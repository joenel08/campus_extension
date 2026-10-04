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


window.showConfirm = function (options) {
    const modal = document.getElementById('confirmModal');
    const icon = document.getElementById('confirmModalIcon');
    const title = document.getElementById('confirmModalTitle');
    const message = document.getElementById('confirmModalMessage');
    const okBtn = document.getElementById('confirmModalOk');
    const cancelBtn = document.getElementById('confirmModalCancel');

    // Icon + variant
    const variant = options.variant || 'warning';
    icon.className = 'confirm-modal-icon ' + (variant === 'warning' ? '' : variant);

    const iconMap = {
        warning: 'fa-exclamation-triangle',
        danger: 'fa-trash-alt',
        success: 'fa-check-circle',
        info: 'fa-info-circle'
    };
    icon.innerHTML = '<i class="fas ' + (iconMap[variant] || iconMap.warning) + '"></i>';

    title.textContent = options.title || 'Are you sure?';
    message.textContent = options.message || 'This action cannot be undone.';
    okBtn.textContent = options.okText || 'Confirm';
    cancelBtn.textContent = options.cancelText || 'Cancel';

    // OK button style
    okBtn.className = 'confirm-btn confirm-btn-ok';
    if (variant === 'success') okBtn.classList.add('success');
    if (variant === 'info') okBtn.classList.add('info');

    // Remove any previous listeners
    const newOk = okBtn.cloneNode(true);
    const newCancel = cancelBtn.cloneNode(true);
    okBtn.parentNode.replaceChild(newOk, okBtn);
    cancelBtn.parentNode.replaceChild(newCancel, cancelBtn);

    // Attach fresh listeners
    newOk.addEventListener('click', function () {
        closeConfirm();
        if (typeof options.onConfirm === 'function') options.onConfirm();
    });
    newCancel.addEventListener('click', closeConfirm);

    modal.style.display = 'flex';
};

window.closeConfirm = function () {
    document.getElementById('confirmModal').style.display = 'none';
};

// Close on outside click
document.addEventListener('click', function (e) {
    const modal = document.getElementById('confirmModal');
    if (e.target === modal) modal.style.display = 'none';
});

// Close on Escape
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('confirmModal');
        if (modal) modal.style.display = 'none';
    }
});


document.addEventListener('submit', function (e) {
    const form = e.target;

    // Only intercept forms marked with class="confirm-form"
    if (!form.classList.contains('confirm-form')) return;

    // If we already confirmed once, let it go through
    if (form.dataset.confirmed === '1') return;

    e.preventDefault();

    window.showConfirm({
        title: form.dataset.title || 'Are you sure?',
        message: form.dataset.message || 'This action cannot be undone.',
        okText: form.dataset.ok || 'Confirm',
        variant: form.dataset.variant || 'warning',
        onConfirm: function () {
            form.dataset.confirmed = '1';
            form.submit(); // native submit bypasses the submit event
        }
    });
});