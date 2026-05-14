/**
 * WanderMed – dashboard-admin.js
 * Semua logika JavaScript untuk Dashboard Administrator.
 */
document.addEventListener('DOMContentLoaded', function () {

    // 1. NAVIGASI SIDEBAR (SPA)
    var sections = {
        'navDashboard':'sectionDashboard','navValidasi':'sectionValidasi',
        'navLaporan':'sectionLaporan','navDataWisatawan':'sectionWisatawan',
        'navDataFaskes':'sectionFaskes','navDataPariwisata':'sectionPariwisata',
        'navAllUlasan':'sectionAllUlasan','navChat':'sectionChat',
    };
    document.querySelectorAll('.wm-nav-link').forEach(function(link) {
        link.addEventListener('click', function(e) {
            var targetId = this.id;
            if (!sections[targetId]) return;
            e.preventDefault();
            document.querySelectorAll('.wm-nav-link').forEach(function(l){l.classList.remove('active');});
            this.classList.add('active');
            document.querySelectorAll('.admin-section').forEach(function(sec){sec.style.display='none';});
            var target = document.getElementById(sections[targetId]);
            if (target) { target.style.display='block'; target.classList.remove('wm-section-animate'); void target.offsetWidth; target.classList.add('wm-section-animate'); }
        });
    });
    var navValidasiLink = document.getElementById('navValidasiLink');
    if (navValidasiLink) { navValidasiLink.addEventListener('click',function(e){e.preventDefault();var n=document.getElementById('navValidasi');if(n)n.click();}); }

    // 2. HELPER
    window.filterTable = function(inputId, tableId, colClass) {
        var query = document.getElementById(inputId).value.toLowerCase();
        document.querySelectorAll('#'+tableId+' tbody tr').forEach(function(row){
            var cells = row.querySelectorAll('.'+colClass);
            var match = false;
            cells.forEach(function(c){ if(c.textContent.toLowerCase().includes(query)) match = true; });
            row.style.display = match ? '' : 'none';
        });
    };
    window.updatePendingCount = function() {
        var cardEl=document.getElementById('cardPendingCount'), navEl=document.getElementById('navPendingCount');
        var cur=parseInt(cardEl?cardEl.textContent:0)||0;
        if(cur>0){if(cardEl)cardEl.textContent=cur-1;if(navEl)navEl.textContent=cur-1;}
    };

    // 3. MODAL DETAIL FASKES
    window.showDetailFaskes = function(data) {
        document.getElementById('faskesModalId').value=data.id;
        document.getElementById('detailFaskesNama').textContent=data.faskes?data.faskes.nama_faskes:'-';
        document.getElementById('detailFaskesKategori').textContent=data.faskes?data.faskes.jenis_faskes:'-';
        document.getElementById('detailFaskesPJ').textContent=data.nama_penanggung_jawab;
        document.getElementById('detailFaskesKontak').textContent=data.email+' / '+(data.no_telp||'-');
        document.getElementById('detailFaskesAlamat').textContent=data.faskes?data.faskes.alamat:'-';
        document.getElementById('detailFaskesKoordinat').textContent=data.faskes?(data.faskes.latitude+', '+data.faskes.longitude):'-';
        var bpjs='-';
        if(data.faskes){bpjs=data.faskes.dukungan_bpjs?'<span style="background:rgba(56,161,105,0.1);color:#38a169;border:1px solid rgba(56,161,105,0.3);padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600;">✅ Menerima BPJS</span>':'<span style="background:rgba(229,62,62,0.1);color:#e53e3e;border:1px solid rgba(229,62,62,0.3);padding:3px 10px;border-radius:20px;font-size:12px;font-weight:600;">❌ Non-BPJS</span>';}
        document.getElementById('detailFaskesBPJS').innerHTML=bpjs;
        document.getElementById('detailFaskesLayanan').textContent=(data.faskes&&data.faskes.pengumuman)?data.faskes.pengumuman:'Tidak ada informasi layanan.';
        var docWrap=document.getElementById('detailFaskesDokumenWrap');
        if(data.catatan_admin&&!data.catatan_admin.includes(' ')){docWrap.innerHTML='<a href="/storage/'+data.catatan_admin+'" target="_blank" style="display:inline-flex;align-items:center;gap:6px;background:#ebf8ff;color:#3182ce;border:1px solid #bee3f8;padding:8px 16px;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;"><i class="fas fa-external-link-alt"></i> Buka Dokumen</a>';}
        else{docWrap.innerHTML='<span style="color:#a3aed1;font-size:13px;"><i class="fas fa-times-circle"></i> Dokumen tidak diunggah</span>';}
        document.getElementById('btnApproveFaskesModal').style.display=data.is_verified?'none':'';
        document.getElementById('btnRejectFaskesModal').style.display=data.is_verified?'none':'';
        $('#modalDetailFaskes').modal('show');
    };
    window.approveFaskesFromModal = function() {
        var id=document.getElementById('faskesModalId').value;
        if(!id) return;
        Swal.fire({
            title: 'Konfirmasi Persetujuan',
            text: "Setujui mitra faskes ini? Data akan otomatis muncul di peta publik.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#38a169',
            cancelButtonColor: '#a0aec0',
            confirmButtonText: 'Ya, Setujui',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                var btn=document.getElementById('btnApproveFaskesModal');
                btn.disabled=true;btn.innerHTML='<i class="fas fa-spinner fa-spin"></i> Memproses...';
                fetch('/admin/mitra/'+id+'/approve',{method:'POST',headers:{'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content}})
                .then(function(r){return r.json();}).then(function(data){
                    $('#modalDetailFaskes').modal('hide');
                    var row=document.getElementById('mitraRow-'+id);
                    if(row){row.style.opacity='0.4';row.querySelector('td:last-child').innerHTML='<span class="wm-badge green"><i class="fas fa-check-circle"></i> Disetujui</span>';}
                    updatePendingCount();showToast(data.message||'Mitra berhasil disetujui!');
                    setTimeout(function(){location.reload();}, 1500);
                }).finally(function(){btn.disabled=false;btn.innerHTML='<i class="fas fa-check-circle"></i> Setujui Mitra';});
            }
        });
    };
    window.rejectFaskesFromModal = function() {
        var id=document.getElementById('faskesModalId').value;
        if(!id) return;
        Swal.fire({
            title: 'Konfirmasi Penolakan',
            text: "TOLAK mitra faskes ini? Aksi ini tidak dapat dibatalkan.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e53e3e',
            cancelButtonColor: '#a0aec0',
            confirmButtonText: 'Ya, Tolak',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                var btn=document.getElementById('btnRejectFaskesModal');
                btn.disabled=true;btn.innerHTML='<i class="fas fa-spinner fa-spin"></i>';
                fetch('/admin/mitra/'+id+'/reject',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content},body:JSON.stringify({alasan:'Ditolak setelah peninjauan detail.'})})
                .then(function(r){return r.json();}).then(function(data){
                    $('#modalDetailFaskes').modal('hide');
                    var row=document.getElementById('mitraRow-'+id);
                    if(row){row.style.transition='all .4s';row.style.opacity='0';setTimeout(function(){row.remove();},400);}
                    updatePendingCount();showToast(data.message||'Mitra ditolak.');
                }).finally(function(){btn.disabled=false;btn.innerHTML='<i class="fas fa-times-circle"></i> Tolak Pendaftaran';});
            }
        });
    };

    // 4. MODAL DETAIL PARIWISATA
    window.showDetailWisata = function(data) {
        document.getElementById('wisataModalId').value=data.id;
        document.getElementById('detailWisataNama').textContent=data.nama_wisata;
        document.getElementById('detailWisataKategori').textContent=data.kategori;
        document.getElementById('detailWisataPengelola').textContent=data.nama_pengelola;
        document.getElementById('detailWisataKontak').textContent=(data.email_kontak||'-')+' / '+(data.no_telp||'-');
        document.getElementById('detailWisataAlamat').textContent=data.alamat||'-';
        document.getElementById('detailWisataKoordinat').textContent=(data.latitude||'-')+', '+(data.longitude||'-');
        document.getElementById('detailWisataTiket').textContent=data.harga_tiket?'Rp '+parseInt(data.harga_tiket).toLocaleString('id-ID'):'Gratis / Tidak ada info';
        document.getElementById('detailWisataDeskripsi').textContent=data.deskripsi||'Tidak ada deskripsi.';
        var docWrap=document.getElementById('detailWisataDokumenWrap');
        if(data.foto_path){if(/\.(jpe?g|png|gif|webp)$/i.test(data.foto_path)){docWrap.innerHTML='<img src="/storage/'+data.foto_path+'" style="max-height:200px;max-width:100%;border-radius:10px;"><a href="/storage/'+data.foto_path+'" target="_blank" style="display:inline-flex;align-items:center;gap:6px;background:#ebf8ff;color:#3182ce;border:1px solid #bee3f8;padding:6px 14px;border-radius:8px;font-size:12px;font-weight:600;text-decoration:none;margin-top:8px;"><i class="fas fa-external-link-alt"></i> Buka Penuh</a>';}else{docWrap.innerHTML='<a href="/storage/'+data.foto_path+'" target="_blank" style="display:inline-flex;align-items:center;gap:6px;background:#ebf8ff;color:#3182ce;border:1px solid #bee3f8;padding:8px 16px;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;"><i class="fas fa-external-link-alt"></i> Buka Dokumen</a>';}}
        else{docWrap.innerHTML='<span style="color:#a3aed1;font-size:13px;"><i class="fas fa-times-circle"></i> Tidak ada foto/dokumen</span>';}
        var isPending=(data.status_review==='menunggu');
        document.getElementById('btnApproveWisataModal').style.display=isPending?'':'none';
        document.getElementById('btnRejectWisataModal').style.display=isPending?'':'none';
        $('#modalDetailWisata').modal('show');
    };
    window.approveFromModal = function() {
        var id=document.getElementById('wisataModalId').value;
        if(!id) return;
        Swal.fire({
            title: 'Konfirmasi Persetujuan',
            text: "Setujui destinasi pariwisata ini?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#38a169',
            cancelButtonColor: '#a0aec0',
            confirmButtonText: 'Ya, Setujui',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                var btn=document.getElementById('btnApproveWisataModal');
                btn.disabled=true;btn.innerHTML='<i class="fas fa-spinner fa-spin"></i> Memproses...';
                fetch('/admin/pariwisata/'+id+'/approve',{method:'POST',headers:{'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content}})
                .then(function(r){return r.json();}).then(function(data){
                    $('#modalDetailWisata').modal('hide');
                    var row=document.getElementById('wisataRow-'+id);
                    if(row){row.style.opacity='0.4';row.querySelector('td:last-child').innerHTML='<span class="wm-badge green"><i class="fas fa-check-circle"></i> Disetujui</span>';}
                    updatePendingCount();showToast(data.message||'Destinasi disetujui!');
                    setTimeout(function(){location.reload();}, 1500);
                }).finally(function(){btn.disabled=false;btn.innerHTML='<i class="fas fa-check-circle"></i> Setujui Pendaftaran';});
            }
        });
    };
    window.rejectFromModal = function() {
        var id=document.getElementById('wisataModalId').value;
        if(!id) return;
        Swal.fire({
            title: 'Konfirmasi Penolakan',
            text: "TOLAK destinasi pariwisata ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e53e3e',
            cancelButtonColor: '#a0aec0',
            confirmButtonText: 'Ya, Tolak',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                var btn=document.getElementById('btnRejectWisataModal');
                btn.disabled=true;btn.innerHTML='<i class="fas fa-spinner fa-spin"></i>';
                fetch('/admin/pariwisata/'+id+'/reject',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content},body:JSON.stringify({catatan:'Ditolak setelah peninjauan detail.'})})
                .then(function(r){return r.json();}).then(function(data){
                    $('#modalDetailWisata').modal('hide');
                    var row=document.getElementById('wisataRow-'+id);
                    if(row){row.style.transition='all .4s';row.style.opacity='0';setTimeout(function(){row.remove();},400);}
                    updatePendingCount();showToast(data.message||'Destinasi ditolak.');
                }).finally(function(){btn.disabled=false;btn.innerHTML='<i class="fas fa-times-circle"></i> Tolak';});
            }
        });
    };

    // 5. EDIT FASKES MODAL
    // Daftar fasilitas yang sama dengan dashboard faskes
    var FASKES_FASILITAS = [
        { name: 'UGD 24 Jam',     icon: 'fa-ambulance',         color: '#e74a3b' },
        { name: 'Ambulans',        icon: 'fa-car',               color: '#4e73df' },
        { name: 'Rawat Inap',      icon: 'fa-bed',               color: '#36b9cc' },
        { name: 'Apotek',          icon: 'fa-pills',             color: '#1cc88a' },
        { name: 'Laboratorium',    icon: 'fa-flask',             color: '#f6c23e' },
        { name: 'Dok. Spesialis',  icon: 'fa-user-md',           color: '#ff7a00' },
        { name: 'Poli Anak',       icon: 'fa-baby',              color: '#e74a3b' },
        { name: 'Poli Gigi',       icon: 'fa-tooth',             color: '#4e73df' },
        { name: 'Poli Umum',       icon: 'fa-stethoscope',       color: '#1cc88a' },
        { name: 'Imunisasi',       icon: 'fa-syringe',           color: '#36b9cc' },
        { name: 'Fisioterapi',     icon: 'fa-hand-holding-heart',color: '#e74a3b' },
        { name: 'Radiologi',       icon: 'fa-x-ray',             color: '#6f42c1' },
    ];

    window.openEditFaskes = function(data) {
        document.getElementById('editFaskesId').value = data.id;
        document.getElementById('editFaskesNamaLabel').textContent = data.nama_faskes + ' (' + data.jenis_faskes + ')';
        document.getElementById('editFaskesLat').value = data.latitude || '';
        document.getElementById('editFaskesLng').value = data.longitude || '';
        document.getElementById('editFaskesBPJS').value = data.dukungan_bpjs ? '1' : '0';
        document.getElementById('editFaskesPengumuman').value = data.pengumuman || '';

        // Render checkbox grid fasilitas
        var currentFasilitas = data.layanan_tersedia || [];
        if (typeof currentFasilitas === 'string') {
            try { currentFasilitas = JSON.parse(currentFasilitas); } catch(e) { currentFasilitas = []; }
        }
        var grid = document.getElementById('editFasilitasGrid');
        grid.innerHTML = FASKES_FASILITAS.map(function(f) {
            var checked = currentFasilitas.indexOf(f.name) !== -1;
            return '<label style="display:flex;align-items:center;gap:6px;padding:7px 8px;border-radius:8px;border:1px solid var(--border);background:var(--navy);cursor:pointer;font-size:11.5px;font-weight:500;transition:background 0.15s;">' +
                '<input type="checkbox" name="edit_fasilitas" value="' + f.name + '"' + (checked ? ' checked' : '') + ' style="accent-color:' + f.color + ';width:14px;height:14px;flex-shrink:0;">' +
                '<i class="fas ' + f.icon + '" style="color:' + f.color + ';font-size:12px;"></i>' +
                f.name +
                '</label>';
        }).join('');

        $('#modalEditFaskes').modal('show');
    };

    window.saveFaskesData = function() {
        var btn = document.getElementById('btnSaveFaskesEdit');
        var id  = document.getElementById('editFaskesId').value;
        if (!id) return;

        // Kumpulkan fasilitas yang dicentang
        var checked = Array.from(document.querySelectorAll('#editFasilitasGrid input[name="edit_fasilitas"]:checked'))
                           .map(function(cb) { return cb.value; });

        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
        btn.disabled  = true;

        fetch('/admin/faskes/' + id + '/update-lokasi', {
            method:  'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            body: JSON.stringify({
                latitude:          document.getElementById('editFaskesLat').value,
                longitude:         document.getElementById('editFaskesLng').value,
                dukungan_bpjs:     document.getElementById('editFaskesBPJS').value,
                pengumuman:        document.getElementById('editFaskesPengumuman').value,
                layanan_tersedia:  checked,
            })
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            $('#modalEditFaskes').modal('hide');
            showToast(data.message || 'Data faskes berhasil diperbarui!');
            setTimeout(function() { location.reload(); }, 1500);
        })
        .catch(function() { showToast('Gagal menyimpan. Cek koneksi.', 'danger'); })
        .finally(function() { btn.disabled = false; btn.innerHTML = '<i class="fas fa-save"></i> Simpan Perubahan'; });
    };
    window.deleteFaskes = function(btn,id,nama) {
        Swal.fire({
            title: 'Hapus Faskes?',
            text: 'Yakin HAPUS permanen faskes "'+nama+'" beserta akun mitranya? Data ini tidak dapat dikembalikan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e53e3e',
            cancelButtonColor: '#a0aec0',
            confirmButtonText: 'Ya, Hapus Permanen',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                btn.innerHTML='<i class="fas fa-spinner fa-spin"></i>';btn.disabled=true;
                fetch('/admin/faskes/'+id,{method:'DELETE',headers:{'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content}})
                .then(function(r){return r.json();}).then(function(data){var row=document.getElementById('faskesTableRow-'+id);if(row){row.style.transition='all .4s';row.style.opacity='0';setTimeout(function(){row.remove();},400);}showToast(data.message||'Faskes berhasil dihapus!');})
                .catch(function(){btn.disabled=false;btn.innerHTML='<i class="fas fa-trash"></i>';showToast('Gagal menghapus.','danger');});
            }
        });
    };

    // 6. TOGGLE STATUS FASKES
    window.toggleStatusFaskes = function(btn,id) {
        btn.innerHTML='<i class="fas fa-spinner fa-spin"></i>';
        fetch('/admin/faskes/'+id+'/toggle-status',{method:'POST',headers:{'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content}})
        .then(function(r){return r.json();}).then(function(data){
            var badge=document.getElementById('statusBadge-'+id);
            if(data.status_operasional==='open'){badge.className='wm-badge green';badge.innerHTML='<i class="fas fa-circle" style="font-size:8px;"></i> Buka';}
            else{badge.className='wm-badge danger';badge.innerHTML='<i class="fas fa-circle" style="font-size:8px;"></i> Tutup';}
            btn.innerHTML='<i class="fas fa-power-off"></i>';showToast(data.message||'Status operasional diperbarui!');
        }).catch(function(){btn.innerHTML='<i class="fas fa-power-off"></i>';showToast('Gagal mengubah status.');});
    };

    // 7. EDIT PARIWISATA MODAL
    window.openEditPariwisata = function(data) {
        document.getElementById('editWisataId').value=data.id;
        var typeInput=document.getElementById('editWisataType'); if(typeInput) typeInput.value=data.type;
        document.getElementById('editWisataNamaLabel').textContent=data.nama_wisata+' ('+data.kategori+')';
        document.getElementById('editWisataAlamat').value=data.alamat||'';
        document.getElementById('editWisataLat').value=data.latitude||'';
        document.getElementById('editWisataLng').value=data.longitude||'';
        document.getElementById('editWisataTiket').value=data.harga_tiket||'';
        document.getElementById('editWisataDeskripsi').value=data.deskripsi||'';
        $('#modalEditPariwisata').modal('show');
    };
    window.updateWisataLokasi = function() {
        var btn=document.getElementById('btnSaveWisataEdit'),id=document.getElementById('editWisataId').value;
        var typeInput=document.getElementById('editWisataType'),type=typeInput?typeInput.value:'';
        if(!id)return;
        btn.innerHTML='<i class="fas fa-spinner fa-spin"></i> Menyimpan...';btn.disabled=true;
        fetch('/admin/pariwisata/'+id+'/update-lokasi',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content},body:JSON.stringify({type:type,latitude:document.getElementById('editWisataLat').value,longitude:document.getElementById('editWisataLng').value,alamat:document.getElementById('editWisataAlamat').value,harga_tiket:document.getElementById('editWisataTiket').value,deskripsi:document.getElementById('editWisataDeskripsi').value})})
        .then(function(r){return r.json();}).then(function(data){$('#modalEditPariwisata').modal('hide');showToast(data.message||'Data pariwisata diperbarui!');setTimeout(function(){location.reload();},1500);})
        .catch(function(){showToast('Gagal menyimpan. Cek koneksi.','danger');})
        .finally(function(){btn.disabled=false;btn.innerHTML='<i class="fas fa-save"></i> Simpan Perubahan';});
    };
    window.deletePariwisata = function(btn,id,nama,type) {
        Swal.fire({
            title: 'Hapus Destinasi?',
            text: 'Yakin HAPUS permanen destinasi "'+nama+'"? Data ini tidak bisa dikembalikan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e53e3e',
            cancelButtonColor: '#a0aec0',
            confirmButtonText: 'Ya, Hapus Permanen',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                btn.innerHTML='<i class="fas fa-spinner fa-spin"></i>';btn.disabled=true;
                fetch('/admin/pariwisata/'+id,{method:'DELETE',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content},body:JSON.stringify({type:type})})
                .then(function(r){return r.json();}).then(function(data){var row=btn.closest('tr');row.style.transition='all .4s';row.style.opacity='0';setTimeout(function(){row.remove();},400);showToast(data.message||'Destinasi dihapus!');})
                .catch(function(){btn.disabled=false;btn.innerHTML='<i class="fas fa-trash"></i>';showToast('Gagal menghapus.');});
            }
        });
    };

    // 8. RESOLVE TIKET
    window.resolveTicket = function(btn,id,tiket) {
        Swal.fire({
            title: 'Selesaikan Tiket?',
            text: 'Tandai tiket '+tiket+' sebagai selesai?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#38a169',
            cancelButtonColor: '#a0aec0',
            confirmButtonText: 'Ya, Selesai',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                btn.innerHTML='<i class="fas fa-spinner fa-spin"></i>';
                fetch('/admin/laporan/'+id+'/resolve',{method:'POST',headers:{'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content}})
                .then(function(r){return r.json();}).then(function(data){
                    var row=btn.closest('tr');row.querySelector('td:nth-child(5)').innerHTML='<span class="wm-badge green"><i class="fas fa-check-circle"></i> Resolved</span>';
                    btn.outerHTML='<button class="wm-btn ghost sm" disabled style="opacity:0.5;"><i class="fas fa-lock"></i> Closed</button>';
                    showToast(data.message||'Tiket diselesaikan!');
                });
            }
        });
    };

    // 9. TOGGLE STATUS USER
    window.toggleUserStatus = function(btn,id) {
        var isCurrentlyActive = btn.classList.contains('danger'); // Button 'danger' means they are currently active
        
        if (isCurrentlyActive) {
            Swal.fire({
                title: 'Blokir Wisatawan?',
                text: "Silakan pilih atau tuliskan alasan pemblokiran:",
                icon: 'warning',
                input: 'select',
                inputOptions: {
                    'Ulasan palsu / Manipulasi rating': 'Ulasan palsu / Manipulasi rating',
                    'Spam atau perilaku mengganggu': 'Spam atau perilaku mengganggu',
                    'Pelanggaran Ketentuan Layanan': 'Pelanggaran Ketentuan Layanan',
                    'Aktivitas mencurigakan': 'Aktivitas mencurigakan',
                    'other': 'Lainnya (Tuliskan sendiri...)'
                },
                inputPlaceholder: 'Pilih alasan...',
                showCancelButton: true,
                confirmButtonColor: '#e53e3e',
                confirmButtonText: 'Ya, Blokir Akun',
                cancelButtonText: 'Batal',
                inputValidator: (value) => {
                    return new Promise((resolve) => {
                        if (value) resolve();
                        else resolve('Anda harus memilih alasan.');
                    });
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    var reason = result.value;
                    if (reason === 'other') {
                        Swal.fire({
                            title: 'Alasan Khusus',
                            input: 'text',
                            inputLabel: 'Tuliskan alasan pemblokiran:',
                            showCancelButton: true,
                            confirmButtonText: 'Blokir Sekarang',
                            inputValidator: (v) => { if (!v) return 'Alasan tidak boleh kosong!'; }
                        }).then((res) => { if (res.isConfirmed) executeToggleStatus(btn, id, res.value); });
                    } else {
                        executeToggleStatus(btn, id, reason);
                    }
                }
            });
        } else {
            executeToggleStatus(btn, id, null);
        }
    };

    function executeToggleStatus(btn, id, reason) {
        var oldHtml = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>'; btn.disabled = true;
        fetch('/admin/user/'+id+'/toggle-status', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            body: JSON.stringify({ reason: reason })
        })
        .then(function(r){ return r.json(); })
        .then(function(data){
            var statusCell = document.getElementById('statusUser-'+id);
            if(data.is_active) {
                statusCell.innerHTML = '<span class="wm-badge green">Aktif</span>';
                btn.className = 'wm-btn danger sm'; btn.innerHTML = '<i class="fas fa-ban"></i> Blokir';
            } else {
                statusCell.innerHTML = '<span class="wm-badge danger">Diblokir</span>';
                btn.className = 'wm-btn success sm'; btn.innerHTML = '<i class="fas fa-check"></i> Aktifkan';
            }
            showToast(data.message || 'Status akun diperbarui!');
        }).catch(function() {
            btn.innerHTML = oldHtml; showToast('Gagal mengubah status.', 'danger');
        }).finally(function() { btn.disabled = false; });
    }
});
