// SpecialGig - Main Application JS

document.addEventListener('DOMContentLoaded', function() {
    // Auto-dismiss flash messages after 5 seconds
    setTimeout(function() {
        document.querySelectorAll('.toast-container .toast').forEach(function(el) {
            el.style.transition = 'opacity .3s';
            el.style.opacity = '0';
            setTimeout(function() { el.remove(); }, 300);
        });
    }, 5000);

    // Confirm dialogs for delete actions
    document.querySelectorAll('[data-confirm]').forEach(function(el) {
        el.addEventListener('click', function(e) {
            if (!confirm(this.dataset.confirm)) {
                e.preventDefault();
            }
        });
    });

    // File input display filename
    document.querySelectorAll('input[type="file"]').forEach(function(input) {
        input.addEventListener('change', function() {
            const label = this.closest('.form-group')?.querySelector('.file-label');
            if (label && this.files.length > 0) {
                label.textContent = this.files.length > 1
                    ? this.files.length + ' files selected'
                    : this.files[0].name;
            }
        });
    });
});

// Sidebar toggle
function toggleSidebar() {
    const sidebar = document.getElementById('adminSidebar');
    if (sidebar) {
        sidebar.classList.toggle('open');
    }
}
