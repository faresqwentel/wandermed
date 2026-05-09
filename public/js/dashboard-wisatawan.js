// INISIALISASI TEMA SEGERA
(function() {
    const t = localStorage.getItem('wanderMedTheme') || 'dark';
    if (t === 'light') {
        document.body.classList.remove('dark');
        document.body.classList.add('light');
    }
})();

document.addEventListener('DOMContentLoaded', function() {
    // Sinkronkan icon tema
    const isLight = document.body.classList.contains('light');
    const ico = document.getElementById('themeIco');
    if (ico) ico.className = isLight ? 'fas fa-moon' : 'fas fa-sun';
});

function toggleTheme() {
    const isLight = document.body.classList.toggle('light');
    if (!isLight) document.body.classList.add('dark'); 
    else document.body.classList.remove('dark');
    
    localStorage.setItem('wanderMedTheme', isLight ? 'light' : 'dark');
    const ico = document.getElementById('themeIco');
    if (ico) ico.className = isLight ? 'fas fa-moon' : 'fas fa-sun';
}

function switchTab(name) {
    // Update Panes
    document.querySelectorAll('.w-pane').forEach(p => p.classList.remove('active'));
    document.getElementById('tab-' + name).classList.add('active');
    
    // Update Pills
    document.querySelectorAll('.w-pill').forEach(btn => btn.classList.remove('active'));
    document.querySelector('.w-pill[data-target="tab-' + name + '"]').classList.add('active');
}

// LOGOUT CONFIRMATION (SweetAlert2)
document.addEventListener('DOMContentLoaded', function () {
    const logoutBtn = document.getElementById('logoutBtn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function (e) {
            e.preventDefault();
            const dest = this.href;
            Swal.fire({
                title: 'Keluar dari Akun?',
                html: `<p style="color:var(--text-muted);font-size:14px;margin:0;line-height:1.6;">
                          Sesi Anda akan diakhiri dan Anda akan<br>diarahkan kembali ke halaman login.
                       </p>`,
                icon: 'warning',
                iconColor: '#ff7a00',
                showCancelButton: true,
                confirmButtonText: '<i class="fas fa-sign-out-alt" style="margin-right:6px;"></i>Ya, Keluar',
                cancelButtonText:  '<i class="fas fa-times" style="margin-right:6px;"></i>Batal',
                reverseButtons: true,
                focusCancel: true,
                customClass: {
                    popup:         'wm-swal-popup',
                    title:         'wm-swal-title',
                    confirmButton: 'wm-swal-confirm',
                    cancelButton:  'wm-swal-cancel',
                    icon:          'wm-swal-icon',
                },
                showClass:  { popup: 'animate__animated animate__fadeInDown animate__faster' },
                hideClass:  { popup: 'animate__animated animate__fadeOutUp animate__faster' },
            }).then(function (result) {
                if (result.isConfirmed) {
                    document.body.style.transition = 'opacity 0.18s ease';
                    document.body.style.opacity    = '0';
                    setTimeout(function () { window.location.href = dest; }, 185);
                }
            });
        });
    }
});