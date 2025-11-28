/* ============================================
   FILE 2: public/assets/js/script.js
   ============================================ */

// Validasi form ketika submit
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('[novalidate]');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(event) {
            // Cek validasi HTML5
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });

    // Auto-hide alert setelah 5 detik
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(function() {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
});

// Validasi NISN (20 digit)
function validateNISN(nisn) {
    return nisn.length === 20 && /^\d{20}$/.test(nisn);
}

// Validasi No. HP (10-13 digit)
function validateNoHP(noHp) {
    return /^\d{10,13}$/.test(noHp);
}

// Validasi Tahun Terbit (4 digit)
function validateTahun(tahun) {
    const now = new Date().getFullYear();
    return /^\d{4}$/.test(tahun) && tahun >= 1900 && tahun <= now;
}

// Confirm delete
function confirmDelete(message = 'Yakin ingin menghapus data ini?') {
    return confirm(message);
}

// Format tanggal DD-MM-YYYY
function formatDate(dateString) {
    const options = { year: 'numeric', month: '2-digit', day: '2-digit' };
    return new Date(dateString).toLocaleDateString('id-ID', options);
}

// Show spinner saat form submit
document.addEventListener('submit', function(e) {
    const submitBtn = e.target.querySelector('button[type="submit"]');
    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Loading...';
    }
});