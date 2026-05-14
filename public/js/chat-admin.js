/**
 * chat-admin.js
 * Logika ruang obrolan Admin ↔ Mitra Faskes
 * Strategi: AJAX Polling setiap 3 detik untuk real-time updates.
 */

(function () {
    'use strict';

    // ── State ──────────────────────────────────────────────────
    let currentMitraId   = null;
    let currentMitraName = '';
    let lastMessageId    = 0;
    let pollTimer        = null;
    let allContacts      = [];

    const CSRF = () => document.querySelector('meta[name="csrf-token"]')?.content
                    || document.querySelector('meta[name="csrf-token-chat"]')?.content
                    || '';

    // ── Init ────────────────────────────────────────────────────
    window.initAdminChat = function () {
        loadContacts();
        // Refresh daftar kontak setiap 10 detik (update unread badge)
        setInterval(loadContacts, 10000);
    };

    // ── Load Daftar Kontak ──────────────────────────────────────
    function loadContacts() {
        fetch('/admin/chat/contacts')
            .then(r => r.json())
            .then(contacts => {
                allContacts = contacts;
                renderContacts(contacts);
            })
            .catch(() => {});
    }

    function renderContacts(contacts) {
        const list = document.getElementById('chatContactsList');
        if (!list) return;

        if (!contacts.length) {
            list.innerHTML = `<div class="contact-empty">
                <i class="fas fa-hospital-user"></i>
                Belum ada mitra faskes terverifikasi.
            </div>`;
            return;
        }

        const avatarColors = ['', 'green', 'orange'];
        list.innerHTML = contacts.map((c, i) => {
            const colorClass = avatarColors[i % 3] || '';
            const isActive   = c.id === currentMitraId ? ' active' : '';
            const badge      = c.unread > 0
                ? `<div class="contact-badge">${c.unread}</div>` : '';
            const lastMsg    = c.last_message
                ? c.last_message.length > 35 ? c.last_message.substring(0, 35) + '…' : c.last_message
                : '<em>Belum ada pesan</em>';

            return `
            <div class="contact-item${isActive}" data-id="${c.id}" data-nama="${c.nama}" onclick="selectContact(${c.id}, '${escHtml(c.nama)}', '${escHtml(c.jenis)}', '${escHtml(c.initial)}')">
                <div class="contact-avatar ${colorClass}">${escHtml(c.initial)}</div>
                <div class="contact-info">
                    <div class="contact-name">${escHtml(c.nama)}</div>
                    <div class="contact-last">${lastMsg}</div>
                </div>
                <div class="contact-meta">
                    ${c.last_time ? `<div class="contact-time">${c.last_time}</div>` : ''}
                    ${badge}
                </div>
            </div>`;
        }).join('');
    }

    window.filterContacts = function (query) {
        const q = query.toLowerCase().trim();
        if (!q) { renderContacts(allContacts); return; }
        renderContacts(allContacts.filter(c => c.nama.toLowerCase().includes(q)));
    };

    // ── Pilih Kontak ───────────────────────────────────────────
    window.selectContact = function (mitraId, nama, jenis, initial) {
        if (currentMitraId === mitraId) return;

        // Reset state
        currentMitraId   = mitraId;
        currentMitraName = nama;
        lastMessageId    = 0;

        // Update active state kontak
        document.querySelectorAll('#chatContactsList .contact-item').forEach(el => {
            el.classList.toggle('active', parseInt(el.dataset.id) === mitraId);
        });

        // Update topbar
        document.getElementById('chatTopAvatar').textContent = initial;
        document.getElementById('chatTopName').textContent   = nama;

        // Tampilkan area percakapan
        document.getElementById('chatPlaceholder').style.display   = 'none';
        const conv = document.getElementById('chatConversation');
        conv.style.display = 'flex';

        // Clear messages lama & tampilkan loading
        const body = document.getElementById('chatBody');
        body.innerHTML = `<div class="chat-empty"><i class="fas fa-spinner fa-spin"></i><p>Memuat percakapan...</p></div>`;

        // Stop polling lama
        if (pollTimer) clearInterval(pollTimer);

        // Fetch pesan awal
        fetchMessages(mitraId).then(() => {
            // Mulai polling baru
            pollTimer = setInterval(() => pollNewMessages(mitraId), 3000);
        });
    };

    // ── Fetch Semua Pesan ─────────────────────────────────────
    function fetchMessages(mitraId) {
        return fetch(`/admin/chat/messages/${mitraId}`)
            .then(r => r.json())
            .then(data => {
                const body = document.getElementById('chatBody');
                if (!data.messages || data.messages.length === 0) {
                    body.innerHTML = `<div class="chat-empty">
                        <i class="fas fa-comment-medical"></i>
                        <p>Belum ada pesan. Mulai percakapan!</p>
                    </div>`;
                    lastMessageId = 0;
                    return;
                }

                body.innerHTML = '';
                let lastDate = null;
                data.messages.forEach(msg => {
                    if (msg.date !== lastDate) {
                        body.innerHTML += `<div class="chat-date-sep"><span>${msg.date}</span></div>`;
                        lastDate = msg.date;
                    }
                    body.innerHTML += buildBubble(msg);
                    lastMessageId = Math.max(lastMessageId, msg.id);
                });
                scrollToBottom(body);

                // Reload kontak supaya badge unread hilang
                loadContacts();
            })
            .catch(() => {});
    }

    // ── Polling Pesan Baru ────────────────────────────────────
    function pollNewMessages(mitraId) {
        if (currentMitraId !== mitraId) return;

        fetch(`/admin/chat/poll/${mitraId}?last_id=${lastMessageId}`)
            .then(r => r.json())
            .then(data => {
                if (!data.messages || data.messages.length === 0) return;

                const body     = document.getElementById('chatBody');
                const atBottom = body.scrollHeight - body.scrollTop - body.clientHeight < 60;

                // Hapus chat-empty jika ada
                const empty = body.querySelector('.chat-empty');
                if (empty) empty.remove();

                let lastDate = body.querySelector('.chat-date-sep:last-of-type')?.textContent?.trim() || null;

                data.messages.forEach(msg => {
                    if (msg.date !== lastDate) {
                        body.innerHTML += `<div class="chat-date-sep"><span>${msg.date}</span></div>`;
                        lastDate = msg.date;
                    }
                    body.innerHTML += buildBubble(msg);
                    lastMessageId = Math.max(lastMessageId, msg.id);
                });

                if (atBottom) scrollToBottom(body);
                loadContacts(); // update badge
            })
            .catch(() => {});
    }

    // ── Kirim Pesan (Admin) ───────────────────────────────────
    window.adminSendMessage = function () {
        const input = document.getElementById('chatInputAdmin');
        const btn   = document.getElementById('chatSendBtnAdmin');
        const body  = document.getElementById('chatBody');
        const text  = input.value.trim();

        if (!text || !currentMitraId) return;

        btn.disabled = true;
        input.value  = '';
        input.style.height = '';

        // Optimistic UI — tampilkan bubble langsung
        const tempId  = 'temp_' + Date.now();
        const now     = new Date();
        const tempBub = buildBubble({
            id: tempId, body: text,
            sender_role: 'admin',
            time: now.toTimeString().substring(0, 5),
            date: now.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' }),
        });

        // Hapus empty state jika ada
        const empty = body.querySelector('.chat-empty');
        if (empty) empty.remove();

        body.innerHTML += tempBub;
        scrollToBottom(body);

        fetch('/admin/chat/send', {
            method:  'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF(),
            },
            body: JSON.stringify({ mitra_id: currentMitraId, body: text }),
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                // Ganti bubble temp dengan yang real
                const tempEl = body.querySelector(`[data-id="${tempId}"]`);
                if (tempEl) {
                    tempEl.outerHTML = buildBubble(data.message);
                    lastMessageId = Math.max(lastMessageId, data.message.id);
                }
                loadContacts();
            }
        })
        .catch(() => {})
        .finally(() => { btn.disabled = false; input.focus(); });
    };

    // ── Helpers ───────────────────────────────────────────────
    function buildBubble(msg) {
        const isAdmin  = msg.sender_role === 'admin';
        const dir      = isAdmin ? 'from-admin' : 'from-mitra';

        // Avatar kecil hanya untuk pesan dari Mitra (kiri)
        const avatarHtml = !isAdmin
            ? `<div class="bubble-sender-avatar">F</div>` : '';

        // Label pengirim untuk pesan Mitra
        const labelHtml = !isAdmin
            ? `<div class="bubble-sender-label"><i class="fas fa-hospital" style="font-size:9px;margin-right:3px;"></i>Mitra Faskes</div>` : '';

        return `
        <div class="bubble-wrap ${dir}" data-id="${msg.id}">
            ${avatarHtml}
            <div class="bubble-group">
                ${labelHtml}
                <div class="bubble">
                    ${escHtml(msg.body).replace(/\n/g, '<br>')}
                    <span class="bubble-time">${msg.time}</span>
                </div>
            </div>
        </div>`;
    }

    function scrollToBottom(el) {
        requestAnimationFrame(() => { el.scrollTop = el.scrollHeight; });
    }

    function escHtml(str) {
        return String(str ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    window.autoResize = function (el) {
        el.style.height = '';
        el.style.height = Math.min(el.scrollHeight, 120) + 'px';
    };

    // ── Expose init ───────────────────────────────────────────
    // Panggil saat section Chat dibuka
    document.addEventListener('DOMContentLoaded', function () {
        // Jika sidebar nav Chat diklik
        const navChat = document.getElementById('navChat');
        if (navChat) {
            navChat.addEventListener('click', function () {
                if (!window._chatAdminInited) {
                    window._chatAdminInited = true;
                    initAdminChat();
                }
            });
        }
    });
})();
