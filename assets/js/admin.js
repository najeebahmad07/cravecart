/**
 * CraveCart Admin Panel JavaScript
 * Developed by Neha Qazmi
 */

(function() {
    'use strict';

    // ========================================
    // CONFIRM DELETE
    // ========================================
    const deleteButtons = document.querySelectorAll('[data-confirm-delete]');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to delete this item? This action cannot be undone.')) {
                e.preventDefault();
                return false;
            }
        });
    });

    // ========================================
    // IMAGE PREVIEW
    // ========================================
    const imageInputs = document.querySelectorAll('input[type="file"][accept*="image"]');

    imageInputs.forEach(input => {
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Create preview
                    let preview = input.parentElement.querySelector('.image-preview');
                    if (!preview) {
                        preview = document.createElement('img');
                        preview.className = 'image-preview mt-2 rounded';
                        preview.style.maxWidth = '200px';
                        preview.style.maxHeight = '200px';
                        input.parentElement.appendChild(preview);
                    }
                    preview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    });

    // ========================================
    // AUTO-SUBMIT STATUS FORMS
    // ========================================
    const statusSelects = document.querySelectorAll('select[name="status"]');

    statusSelects.forEach(select => {
        select.addEventListener('change', function() {
            if (confirm('Update order status?')) {
                this.form.submit();
            } else {
                // Reset to previous value
                this.selectedIndex = Array.from(this.options).findIndex(opt => opt.defaultSelected);
            }
        });
    });

    // ========================================
    // REAL-TIME SEARCH
    // ========================================
    const searchInputs = document.querySelectorAll('.table-search');

    searchInputs.forEach(input => {
        input.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const table = this.closest('.table-card').querySelector('table tbody');
            const rows = table.querySelectorAll('tr');

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });

    // ========================================
    // FORM VALIDATION
    // ========================================
    const forms = document.querySelectorAll('form');

    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('is-invalid');
                } else {
                    field.classList.remove('is-invalid');
                }
            });

            if (!isValid) {
                e.preventDefault();
                alert('Please fill in all required fields');
                return false;
            }
        });
    });

    // ========================================
    // STATISTICS ANIMATION
    // ========================================
    const statNumbers = document.querySelectorAll('.stat-card h3');

    statNumbers.forEach(stat => {
        const finalValue = parseInt(stat.textContent.replace(/[^0-9]/g, ''));
        if (!isNaN(finalValue)) {
            animateValue(stat, 0, finalValue, 1500);
        }
    });

    function animateValue(element, start, end, duration) {
        const range = end - start;
        const increment = range / (duration / 16);
        let current = start;

        const timer = setInterval(() => {
            current += increment;
            if (current >= end) {
                current = end;
                clearInterval(timer);
            }

            // Preserve currency/formatting
            const originalText = element.textContent;
            if (originalText.includes('Rs.')) {
                element.textContent = 'Rs. ' + Math.floor(current).toLocaleString();
            } else {
                element.textContent = Math.floor(current);
            }
        }, 16);
    }

    // ========================================
    // SIDEBAR TOGGLE (Mobile)
    // ========================================
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.querySelector('.admin-sidebar');

    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('show');
        });
    }

    // ========================================
    // DATA TABLE SORTING
    // ========================================
    const tableHeaders = document.querySelectorAll('.table thead th[data-sortable]');

    tableHeaders.forEach(header => {
        header.style.cursor = 'pointer';
        header.innerHTML += ' <i class="bi bi-arrow-down-up small"></i>';

        header.addEventListener('click', function() {
            const table = this.closest('table');
            const tbody = table.querySelector('tbody');
            const rows = Array.from(tbody.querySelectorAll('tr'));
            const index = Array.from(this.parentNode.children).indexOf(this);
            const isAscending = this.classList.contains('asc');

            rows.sort((a, b) => {
                const aValue = a.children[index].textContent.trim();
                const bValue = b.children[index].textContent.trim();

                if (isAscending) {
                    return aValue > bValue ? -1 : 1;
                } else {
                    return aValue < bValue ? -1 : 1;
                }
            });

            // Remove all rows
            while (tbody.firstChild) {
                tbody.removeChild(tbody.firstChild);
            }

            // Append sorted rows
            rows.forEach(row => tbody.appendChild(row));

            // Toggle sort direction
            tableHeaders.forEach(h => h.classList.remove('asc', 'desc'));
            this.classList.toggle('asc', !isAscending);
            this.classList.toggle('desc', isAscending);
        });
    });

    // ========================================
    // AUTO-REFRESH ORDERS (Every 30 seconds)
    // ========================================
    if (window.location.pathname.includes('orders.php')) {
        setInterval(() => {
            // Only refresh if user is not interacting
            if (document.hidden) {
                location.reload();
            }
        }, 30000);
    }

    // ========================================
    // CHART.JS INTEGRATION (Optional)
    // ========================================
    const chartCanvas = document.getElementById('salesChart');
    if (chartCanvas && typeof Chart !== 'undefined') {
        const ctx = chartCanvas.getContext('2d');
        // Add your chart configuration here
    }

})();

// ========================================
// UTILITY FUNCTIONS
// ========================================
function formatCurrency(amount) {
    return 'Rs. ' + parseFloat(amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
}

function showNotification(message, type = 'success') {
    // Implementation for toast notifications
    const toast = document.createElement('div');
    toast.className = `alert alert-${type} position-fixed top-0 end-0 m-3`;
    toast.style.zIndex = '9999';
    toast.textContent = message;
    document.body.appendChild(toast);

    setTimeout(() => {
        toast.remove();
    }, 3000);
}