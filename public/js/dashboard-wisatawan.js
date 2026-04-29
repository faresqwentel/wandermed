/**
 * WanderMed – dashboard-wisatawan.js
 * Logika JavaScript untuk halaman Dashboard Wisatawan.
 */
(function () {
    // Highlight baris tabel berdasarkan label warna
    document.querySelectorAll('#historyTable tbody tr').forEach(function(row) {
        var label = row.getAttribute('data-label');
        if (label === 'green')  row.style.borderLeft = '4px solid #1cc88a';
        if (label === 'yellow') row.style.borderLeft = '4px solid #f6c23e';
    });

    // Mulai edit catatan inline
    window.startEditNote = function(viewEl) {
        var td     = viewEl.closest('td');
        var editEl = td.querySelector('.note-edit-active');
        var input  = editEl.querySelector('input');
        input.value = viewEl.querySelector('.note-text').textContent;
        viewEl.style.display = 'none';
        editEl.classList.add('show');
        input.focus();
    };

    // Simpan catatan via Fetch API
    window.saveNote = function(btn) {
        var td     = btn.closest('td');
        var editEl = btn.closest('.note-edit-active');
        var viewEl = td.querySelector('.note-view');
        var input  = editEl.querySelector('input');
        var noteId = input.getAttribute('data-id');
        var newText = input.value;

        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

        fetch('/wisatawan/catatan/' + noteId, {
            method : 'PUT',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            body   : JSON.stringify({ catatan_pribadi: newText })
        })
        .then(function(response) { return response.json(); })
        .then(function(data) {
            if (data.success) {
                viewEl.querySelector('.note-text').textContent = newText || 'Belum ada catatan...';
                editEl.classList.remove('show');
                viewEl.style.display = '';
                showToast(data.message);
                setTimeout(function() { location.reload(); }, 1500);
            }
            btn.innerHTML = '<i class="fas fa-check"></i>';
        })
        .catch(function(err) {
            console.error(err);
            btn.innerHTML = '<i class="fas fa-check"></i>';
            showToast('Terjadi kesalahan saat menyimpan.');
        });
    };

    // Batal edit catatan
    window.cancelNote = function(btn) {
        var td = btn.closest('td');
        td.querySelector('.note-edit-active').classList.remove('show');
        td.querySelector('.note-view').style.display = '';
    };

    // Quick nav scroll ke profil / medis
    var navProfileLink = document.getElementById('navProfileLink');
    if (navProfileLink) {
        navProfileLink.addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('sectionProfil').scrollIntoView({ behavior: 'smooth' });
        });
    }
    var navMedisLink = document.getElementById('navMedisLink');
    if (navMedisLink) {
        navMedisLink.addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('sectionMedis').scrollIntoView({ behavior: 'smooth' });
        });
    }
})();
