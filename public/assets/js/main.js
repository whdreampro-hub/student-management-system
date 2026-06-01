/**
 * EduAdmin SMS — Main JavaScript
 */

// ── Sidebar (mobile) ─────────────────────────────────
function openSidebar() {
    document.getElementById('sidebar').classList.add('open');
    document.getElementById('sidebarOverlay').classList.add('show');
}
function closeSidebar() {
    document.getElementById('sidebar').classList.remove('open');
    document.getElementById('sidebarOverlay').classList.remove('show');
}

// ── Toast Notifications ──────────────────────────────
function showToast(type, message) {
    const colors = {
        success: '#22c55e',
        error:   '#ef4444',
        warning: '#f59e0b',
        info:    '#06b6d4'
    };
    const icons = {
        success: 'bi-check-circle-fill',
        error:   'bi-x-circle-fill',
        warning: 'bi-exclamation-triangle-fill',
        info:    'bi-info-circle-fill'
    };
    const id = 'toast_' + Date.now();
    const html = `
    <div id="${id}" class="toast align-items-center show mb-2" role="alert" style="
        background:#1e2130; border:1px solid rgba(255,255,255,0.07);
        border-left:4px solid ${colors[type]}; border-radius:10px; min-width:280px;">
        <div class="d-flex align-items-center p-3 gap-2">
            <i class="bi ${icons[type]}" style="color:${colors[type]};font-size:1.1rem;flex-shrink:0"></i>
            <div class="me-auto text-white" style="font-size:.875rem">${message}</div>
            <button type="button" class="btn-close btn-close-white btn-sm" onclick="document.getElementById('${id}').remove()"></button>
        </div>
    </div>`;
    document.getElementById('toastContainer').insertAdjacentHTML('beforeend', html);
    setTimeout(() => { const el = document.getElementById(id); if (el) el.remove(); }, 4000);
}

// ── Confirm helper ───────────────────────────────────
function confirmAction(title, text, callback, btnText = 'Confirm', btnColor = '#ef4444') {
    Swal.fire({
        title, text, icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: btnColor,
        cancelButtonColor: '#6b7280',
        confirmButtonText: btnText,
        cancelButtonText: 'Cancel',
        background: '#1e2130',
        color: '#f1f5f9'
    }).then(result => { if (result.isConfirmed) callback(); });
}

// ── AJAX helper ──────────────────────────────────────
function postJSON(url, data, onSuccess) {
    $.ajax({
        url, method: 'POST',
        data: data instanceof FormData ? data : data,
        processData: !(data instanceof FormData),
        contentType: data instanceof FormData ? false : 'application/x-www-form-urlencoded',
        dataType: 'json',
        success: function(res) {
            if (res.success) onSuccess(res);
            else showToast('error', res.message || 'An error occurred.');
        },
        error: function() { showToast('error', 'Request failed. Please try again.'); }
    });
}

// ── Auto-dismiss alerts ───────────────────────────────
document.addEventListener('DOMContentLoaded', function () {
    // Auto-dismiss Bootstrap alerts after 4s
    document.querySelectorAll('.alert:not(.alert-permanent)').forEach(function(el) {
        setTimeout(() => { const a = bootstrap.Alert.getOrCreateInstance(el); if (a) a.close(); }, 4000);
    });

    // Activate tooltips
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    tooltipTriggerList.forEach(el => new bootstrap.Tooltip(el));
});

// ── Print helpers ────────────────────────────────────
function printSection(sectionId) {
    const content = document.getElementById(sectionId).innerHTML;
    const win = window.open('', '_blank');
    win.document.write(`<!DOCTYPE html><html><head>
        <title>Print</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>body{padding:20px;font-family:Inter,sans-serif;}</style>
    </head><body>${content}</body></html>`);
    win.document.close();
    win.print();
}
