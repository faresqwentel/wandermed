{{-- ============================================================
     Chat Room: Sisi Mitra Faskes
     WhatsApp-style, Light/Dark aware via CSS variables
     ============================================================ --}}
<div id="sectionChat" class="faskes-section" style="display:none;">

<style>
/* ─────────────────────────────────────────────────────────────
   CHAT MITRA – Layout & Bubbles
   ───────────────────────────────────────────────────────────── */

/* Shell utama */
.mc-shell {
    display: flex;
    flex-direction: column;
    height: calc(100vh - 185px);
    min-height: 500px;
    border-radius: 20px;
    overflow: hidden;
    border: 1px solid var(--border);
    background: var(--navy-card);
    box-shadow: 0 4px 24px rgba(0,0,0,0.07);
}

/* ── Topbar ── */
.mc-topbar {
    padding: 14px 20px;
    display: flex;
    align-items: center;
    gap: 14px;
    background: var(--navy-light);
    border-bottom: 1px solid var(--border);
    flex-shrink: 0;
}
.mc-topbar-avatar {
    width: 42px; height: 42px; border-radius: 50%;
    background: linear-gradient(135deg, #ff7a00, #e65c00);
    display: flex; align-items: center; justify-content: center;
    font-size: 17px; color: #fff; font-weight: 800;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(255,122,0,0.35);
}
.mc-topbar-name { font-size: 14px; font-weight: 700; color: var(--text-primary); }
.mc-topbar-sub  { font-size: 11px; color: #1cc88a; margin-top: 2px; display: flex; align-items: center; gap: 4px; }
.mc-topbar-sub::before {
    content: '';
    width: 7px; height: 7px;
    border-radius: 50%;
    background: #1cc88a;
    display: inline-block;
}
.mc-unread-badge {
    margin-left: auto;
    background: var(--orange); color: #fff;
    padding: 3px 10px; border-radius: 20px;
    font-size: 11px; font-weight: 700;
    display: none;
}

/* ── Body / Messages Area ── */
.mc-body {
    flex: 1;
    overflow-y: auto;
    padding: 20px 24px;
    display: flex;
    flex-direction: column;
    gap: 6px;
    background: var(--navy);
    /* subtle pattern overlay untuk feel WhatsApp */
    background-image: radial-gradient(circle at 25% 25%, rgba(255,122,0,0.02) 0%, transparent 50%);
}
.mc-body::-webkit-scrollbar { width: 5px; }
.mc-body::-webkit-scrollbar-track { background: transparent; }
.mc-body::-webkit-scrollbar-thumb { background: var(--border); border-radius: 10px; }

/* ── Date Separator ── */
.mc-date-sep {
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 14px 0;
    text-align: center;
}
.mc-date-sep::before,
.mc-date-sep::after {
    content: '';
    flex: 1;
    height: 1px;
    background: var(--border);
}
.mc-date-sep span {
    font-size: 11px;
    font-weight: 600;
    color: var(--text-muted);
    background: var(--navy-card);
    border: 1px solid var(--border);
    padding: 4px 14px;
    border-radius: 20px;
    white-space: nowrap;
    letter-spacing: 0.3px;
}

/* ── Bubble Wrapper ── */
.mc-bubble-wrap {
    display: flex;
    align-items: flex-end;
    gap: 8px;
    margin-bottom: 2px;
    animation: mcBubblePop 0.2s cubic-bezier(0.34,1.56,0.64,1) both;
}
@keyframes mcBubblePop {
    from { opacity: 0; transform: scale(0.9) translateY(4px); }
    to   { opacity: 1; transform: scale(1) translateY(0); }
}

.mc-bubble-wrap.from-admin { justify-content: flex-start; }
.mc-bubble-wrap.from-mitra { justify-content: flex-end; }

/* Avatar kecil dari Admin */
.mc-sender-avatar {
    width: 30px; height: 30px;
    border-radius: 50%;
    background: linear-gradient(135deg, #4e73df, #224abe);
    display: flex; align-items: center; justify-content: center;
    font-size: 11px; font-weight: 800; color: #fff;
    flex-shrink: 0;
    margin-bottom: 4px;
}
.mc-bubble-wrap.from-mitra .mc-sender-avatar { display: none; }

/* Bubble container */
.mc-bubble-group {
    display: flex;
    flex-direction: column;
    max-width: 68%;
}
.mc-bubble-wrap.from-admin .mc-bubble-group { align-items: flex-start; }
.mc-bubble-wrap.from-mitra .mc-bubble-group { align-items: flex-end; }

/* Label pengirim (hanya untuk Admin) */
.mc-sender-label {
    font-size: 10px;
    font-weight: 700;
    color: #4e73df;
    margin-bottom: 4px;
    padding-left: 2px;
    letter-spacing: 0.2px;
}

/* Bubble utama */
.mc-bubble {
    padding: 10px 14px 6px;
    border-radius: 18px;
    font-size: 13.5px;
    line-height: 1.55;
    word-break: break-word;
    position: relative;
}

/* Bubble dari Admin (diterima - kiri) */
.mc-bubble-wrap.from-admin .mc-bubble {
    background: var(--navy-card);
    color: var(--text-primary);
    border-bottom-left-radius: 6px;
    border: 1px solid var(--border);
    box-shadow: 0 1px 4px rgba(0,0,0,0.06);
}

/* Bubble dari Mitra (dikirim - kanan) */
.mc-bubble-wrap.from-mitra .mc-bubble {
    background: linear-gradient(135deg, #ff7a00, #e65c00);
    color: #fff;
    border-bottom-right-radius: 6px;
    box-shadow: 0 4px 14px rgba(255,122,0,0.3);
}

/* Waktu di dalam bubble */
.mc-bubble-time {
    font-size: 10px;
    display: block;
    text-align: right;
    margin-top: 4px;
    letter-spacing: 0.2px;
}
.mc-bubble-wrap.from-admin  .mc-bubble-time { color: var(--text-muted); }
.mc-bubble-wrap.from-mitra  .mc-bubble-time { color: rgba(255,255,255,0.72); }

/* ── Empty State ── */
.mc-empty {
    flex: 1;
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    color: var(--text-muted); gap: 14px;
    text-align: center; padding: 40px;
}
.mc-empty-icon {
    width: 72px; height: 72px; border-radius: 50%;
    background: var(--navy-card);
    border: 1px solid var(--border);
    display: flex; align-items: center; justify-content: center;
    font-size: 28px; opacity: 0.5;
}
.mc-empty p { font-size: 13px; line-height: 1.7; max-width: 260px; }
.mc-empty strong { color: var(--text-secondary); display: block; font-size: 14px; margin-bottom: 4px; }

/* ── Input Area ── */
.mc-input-area {
    padding: 12px 16px;
    border-top: 1px solid var(--border);
    display: flex;
    align-items: flex-end;
    gap: 10px;
    background: var(--navy-light);
    flex-shrink: 0;
}
.mc-input-wrap {
    flex: 1;
    background: var(--navy);
    border: 1.5px solid var(--border);
    border-radius: 24px;
    padding: 10px 16px;
    display: flex;
    align-items: flex-end;
    transition: border-color 0.2s;
}
.mc-input-wrap:focus-within { border-color: var(--orange); }
.mc-input-box {
    flex: 1;
    background: transparent;
    border: none;
    outline: none;
    resize: none;
    font-size: 13.5px;
    color: var(--text-primary);
    max-height: 120px;
    overflow-y: auto;
    line-height: 1.5;
    font-family: 'Poppins', sans-serif;
}
.mc-input-box::placeholder { color: var(--text-muted); }
.mc-send-btn {
    width: 46px; height: 46px; flex-shrink: 0;
    border-radius: 50%;
    background: linear-gradient(135deg, #ff7a00, #e65c00);
    border: none; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: 16px;
    transition: transform 0.15s ease, box-shadow 0.15s ease;
    box-shadow: 0 4px 15px rgba(255,122,0,0.45);
}
.mc-send-btn:hover { transform: scale(1.1); box-shadow: 0 6px 20px rgba(255,122,0,0.55); }
.mc-send-btn:active { transform: scale(0.95); }
.mc-send-btn:disabled { opacity: 0.45; cursor: not-allowed; transform: none; box-shadow: none; }

/* ── Smooth transition on theme toggle ── */
.mc-shell, .mc-topbar, .mc-body, .mc-input-area,
.mc-bubble-wrap.from-admin .mc-bubble,
.mc-date-sep span, .mc-input-wrap {
    transition: background-color 0.3s ease, border-color 0.3s ease, color 0.2s ease;
}
</style>

<div class="wm-page-header">
    <div>
        <div class="wm-page-title">
            <i class="fas fa-comments" style="color:var(--orange);margin-right:10px;"></i>Chat dengan Admin WanderMed
        </div>
        <div class="wm-page-subtitle">Ruang komunikasi langsung, aman, dan real-time bersama tim Admin</div>
    </div>
</div>

<div class="mc-shell">

    {{-- ── Topbar ── --}}
    <div class="mc-topbar">
        <div class="mc-topbar-avatar">A</div>
        <div style="flex:1;">
            <div class="mc-topbar-name">Admin WanderMed</div>
            <div class="mc-topbar-sub">Siap membalas pesan Anda</div>
        </div>
        <div class="mc-unread-badge" id="mcUnreadBadge">0 pesan baru</div>
    </div>

    {{-- ── Messages ── --}}
    <div class="mc-body" id="mcBody">
        <div class="mc-empty" id="mcEmpty">
            <div class="mc-empty-icon"><i class="fas fa-comment-dots"></i></div>
            <div>
                <strong>Belum ada percakapan</strong>
                <p>Kirim pesan pertama Anda ke Admin WanderMed untuk memulai komunikasi.</p>
            </div>
        </div>
    </div>

    {{-- ── Input ── --}}
    <div class="mc-input-area">
        <div class="mc-input-wrap">
            <textarea class="mc-input-box" id="mcInputBox" rows="1"
                placeholder="Ketik pesan untuk Admin WanderMed..."
                onkeydown="if(event.key==='Enter'&&!event.shiftKey){event.preventDefault();mitraSendMessage();}"
                oninput="autoResizeMc(this)"></textarea>
        </div>
        <button class="mc-send-btn" id="mcSendBtn" onclick="mitraSendMessage()" title="Kirim Pesan">
            <i class="fas fa-paper-plane"></i>
        </button>
    </div>

</div>

</div>{{-- /sectionChat --}}
