/**
 * chat-mitra.js
 * Logika ruang obrolan Mitra Faskes ↔ Admin
 * Strategi: AJAX Polling setiap 3 detik.
 */

(function () {
    'use strict';

    let lastMessageId = 0;
    let pollTimer     = null;
    let initialized   = false;

    const CSRF = () => document.querySelector('meta[name="csrf-token"]')?.content ?? '';

    // ── Init ────────────────────────────────────────────────────
    window.initMitraChat = function () {
        if (initialized) return;
        initialized = true;

        fetchAllMessages();

        // Polling setiap 3 detik
        pollTimer = setInterval(pollNewMessages, 3000);

        // Juga polling unread count di badge notifikasi (nav)
        setInterval(refreshUnreadBadge, 8000);
    };

    // ── Fetch Semua Pesan ─────────────────────────────────────
    function fetchAllMessages() {
        fetch('/mitra/chat/messages')
            .then(r => r.json())
            .then(data => {
                const body  = document.getElementById('mcBody');
                const empty = document.getElementById('mcEmpty');
                if (!body) return;

                if (!data.messages || data.messages.length === 0) {
                    if (empty) empty.style.display = 'flex';
                    lastMessageId = 0;
                    return;
                }

                if (empty) empty.style.display = 'none';
                body.innerHTML = '';

                let lastDate = null;
                data.messages.forEach(msg => {
                    if (msg.date !== lastDate) {
                        body.innerHTML += `<div class="mc-date-sep"><span>${msg.date}</span></div>`;
                        lastDate = msg.date;
                    }
                    body.innerHTML += buildMcBubble(msg);
                    lastMessageId = Math.max(lastMessageId, msg.id);
                });

                scrollToBottomMc(body);
            })
            .catch(() => {});
    }

    // ── Polling ───────────────────────────────────────────────
    function pollNewMessages() {
        fetch(`/mitra/chat/poll?last_id=${lastMessageId}`)
            .then(r => r.json())
            .then(data => {
                if (!data.messages || data.messages.length === 0) return;

                const body  = document.getElementById('mcBody');
                const empty = document.getElementById('mcEmpty');
                if (!body) return;

                const atBottom = body.scrollHeight - body.scrollTop - body.clientHeight < 60;

                if (empty) empty.style.display = 'none';

                let lastDate = body.querySelector('.mc-date-sep:last-of-type')?.textContent?.trim() || null;

                data.messages.forEach(msg => {
                    if (msg.date !== lastDate) {
                        body.innerHTML += `<div class="mc-date-sep"><span>${msg.date}</span></div>`;
                        lastDate = msg.date;
                    }
                    body.innerHTML += buildMcBubble(msg);
                    lastMessageId = Math.max(lastMessageId, msg.id);
                });

                if (atBottom) scrollToBottomMc(body);
            })
            .catch(() => {});
    }

    // ── Kirim Pesan (Mitra) ───────────────────────────────────
    window.mitraSendMessage = function () {
        const input = document.getElementById('mcInputBox');
        const btn   = document.getElementById('mcSendBtn');
        const body  = document.getElementById('mcBody');
        const empty = document.getElementById('mcEmpty');
        const text  = input.value.trim();

        if (!text) return;

        btn.disabled = true;
        input.value  = '';
        input.style.height = '';

        // Optimistic UI
        const tempId  = 'temp_' + Date.now();
        const now     = new Date();
        const tempBub = buildMcBubble({
            id: tempId, body: text,
            sender_role: 'mitra',
            time: now.toTimeString().substring(0, 5),
            date: now.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' }),
        });

        if (empty) empty.style.display = 'none';
        body.innerHTML += tempBub;
        scrollToBottomMc(body);

        fetch('/mitra/chat/send', {
            method:  'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF(),
            },
            body: JSON.stringify({ body: text }),
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                const tempEl = body.querySelector(`[data-id="${tempId}"]`);
                if (tempEl) {
                    tempEl.outerHTML = buildMcBubble(data.message);
                    lastMessageId = Math.max(lastMessageId, data.message.id);
                }
            }
        })
        .catch(() => {})
        .finally(() => { btn.disabled = false; input.focus(); });
    };

    // ── Refresh Unread Badge di Nav ───────────────────────────
    function refreshUnreadBadge() {
        fetch('/mitra/chat/unread')
            .then(r => r.json())
            .then(data => {
                const badge = document.getElementById('chatNavBadge');
                if (!badge) return;
                if (data.unread > 0) {
                    badge.textContent = data.unread;
                    badge.style.display = 'inline-flex';
                } else {
                    badge.style.display = 'none';
                }
            })
            .catch(() => {});
    }

    // ── Helpers ───────────────────────────────────────────────
    function buildMcBubble(msg) {
        const isAdmin = msg.sender_role === 'admin';
        const dir     = isAdmin ? 'from-admin' : 'from-mitra';

        const avatarHtml = isAdmin
            ? `<div class="mc-sender-avatar">A</div>` : '';

        const labelHtml = isAdmin
            ? `<div class="mc-sender-label"><i class="fas fa-shield-alt" style="font-size:9px;margin-right:3px;"></i>Admin WanderMed</div>` : '';

        return `
        <div class="mc-bubble-wrap ${dir}" data-id="${msg.id}">
            ${avatarHtml}
            <div class="mc-bubble-group">
                ${labelHtml}
                <div class="mc-bubble">
                    ${escHtml(msg.body).replace(/\n/g, '<br>')}
                    <span class="mc-bubble-time">${msg.time}</span>
                </div>
            </div>
        </div>`;
    }

    function scrollToBottomMc(el) {
        requestAnimationFrame(() => { el.scrollTop = el.scrollHeight; });
    }

    function escHtml(str) {
        return String(str ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    window.autoResizeMc = function (el) {
        el.style.height = '';
        el.style.height = Math.min(el.scrollHeight, 120) + 'px';
    };

    // ── Auto-init saat section Chat dibuka ────────────────────
    document.addEventListener('DOMContentLoaded', function () {
        const navChat = document.getElementById('navChat');
        if (navChat) {
            navChat.addEventListener('click', function () {
                initMitraChat();
            });
        }
        // Juga init jika URL hash mengarah ke chat
        if (window.location.hash === '#chat') initMitraChat();

        // Mulai cek unread badge segera
        refreshUnreadBadge();
    });
})();
