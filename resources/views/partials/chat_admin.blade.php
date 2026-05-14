{{-- ============================================================
     Chat Room: Admin ↔ Mitra Faskes
     WhatsApp-style, Light/Dark aware via CSS variables
     ============================================================ --}}

<div id="sectionChat" class="admin-section" style="display:none;">

<style>
/* ─────────────────────────────────────────────────────────────
   CHAT ADMIN – Layout Shell
   ───────────────────────────────────────────────────────────── */
.chat-shell {
    display: flex;
    height: calc(100vh - 165px);
    min-height: 500px;
    border-radius: 20px;
    overflow: hidden;
    border: 1px solid var(--border);
    background: var(--navy-card);
    box-shadow: 0 4px 24px rgba(0,0,0,0.07);
}

/* ── Sidebar ── */
.chat-sidebar {
    width: 295px;
    flex-shrink: 0;
    display: flex;
    flex-direction: column;
    border-right: 1px solid var(--border);
    background: var(--navy-light);
}
.chat-sidebar-header {
    padding: 18px 16px 12px;
    border-bottom: 1px solid var(--border);
}
.chat-sidebar-title {
    font-size: 14px; font-weight: 700;
    color: var(--text-primary);
    display: flex; align-items: center; gap: 9px;
    margin-bottom: 12px;
}
.chat-sidebar-title i { color: var(--orange); }

.chat-search { position: relative; }
.chat-search input {
    width: 100%;
    background: var(--navy);
    border: 1.5px solid var(--border);
    border-radius: 22px;
    padding: 9px 14px 9px 36px;
    font-size: 12.5px;
    color: var(--text-primary);
    font-family: 'Poppins', sans-serif;
    outline: none;
    transition: border-color 0.2s;
}
.chat-search input:focus { border-color: var(--orange); }
.chat-search input::placeholder { color: var(--text-muted); }
.chat-search i {
    position: absolute;
    left: 13px; top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted); font-size: 12px;
}

.chat-contacts { flex: 1; overflow-y: auto; }
.chat-contacts::-webkit-scrollbar { width: 3px; }
.chat-contacts::-webkit-scrollbar-thumb { background: var(--border); border-radius: 3px; }

.contact-item {
    display: flex; align-items: center; gap: 11px;
    padding: 12px 16px;
    cursor: pointer;
    border-bottom: 1px solid var(--border);
    transition: background 0.15s;
    position: relative;
}
.contact-item:hover { background: rgba(255,122,0,0.05); }
.contact-item.active {
    background: rgba(255,122,0,0.08);
    border-left: 3px solid var(--orange);
    padding-left: 13px;
}
.contact-avatar {
    width: 42px; height: 42px; border-radius: 50%;
    background: linear-gradient(135deg, #4e73df, #224abe);
    display: flex; align-items: center; justify-content: center;
    font-size: 16px; font-weight: 700; color: #fff;
    flex-shrink: 0;
}
.contact-avatar.green  { background: linear-gradient(135deg, #1cc88a, #17a673); }
.contact-avatar.orange { background: linear-gradient(135deg, #ff7a00, #e65c00); }
.contact-info { flex: 1; min-width: 0; }
.contact-name {
    font-size: 13px; font-weight: 600; color: var(--text-primary);
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    margin-bottom: 2px;
}
.contact-last {
    font-size: 11.5px; color: var(--text-muted);
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.contact-meta { display: flex; flex-direction: column; align-items: flex-end; gap: 5px; flex-shrink: 0; }
.contact-time { font-size: 10px; color: var(--text-muted); }
.contact-badge {
    background: var(--orange); color: #fff;
    border-radius: 50%; width: 19px; height: 19px;
    font-size: 10px; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
}
.contact-empty {
    padding: 48px 20px;
    text-align: center; color: var(--text-muted); font-size: 13px;
}
.contact-empty i { font-size: 36px; display: block; margin-bottom: 12px; opacity: 0.2; }

/* ── Main Chat Area ── */
.chat-main {
    flex: 1; display: flex; flex-direction: column;
    background: var(--navy);
    overflow: hidden;
}

/* Topbar percakapan */
.chat-topbar {
    padding: 13px 20px;
    border-bottom: 1px solid var(--border);
    display: flex; align-items: center; gap: 13px;
    background: var(--navy-light);
    flex-shrink: 0;
}
.chat-topbar-avatar {
    width: 38px; height: 38px; border-radius: 50%;
    background: linear-gradient(135deg, #4e73df, #224abe);
    display: flex; align-items: center; justify-content: center;
    font-size: 15px; font-weight: 700; color: #fff;
    flex-shrink: 0;
}
.chat-topbar-name { font-size: 14px; font-weight: 700; color: var(--text-primary); }
.chat-topbar-sub  { font-size: 11px; color: #1cc88a; margin-top: 1px; display: flex; align-items: center; gap: 4px; }
.chat-topbar-sub::before { content: ''; width: 7px; height: 7px; border-radius: 50%; background: #1cc88a; display: inline-block; }

/* Body pesan */
.chat-body {
    flex: 1; overflow-y: auto;
    padding: 20px 24px;
    display: flex; flex-direction: column; gap: 6px;
    background-image: radial-gradient(circle at 80% 10%, rgba(255,122,0,0.02) 0%, transparent 50%);
}
.chat-body::-webkit-scrollbar { width: 5px; }
.chat-body::-webkit-scrollbar-thumb { background: var(--border); border-radius: 10px; }

/* Date Separator */
.chat-date-sep {
    display: flex; align-items: center; gap: 12px;
    margin: 14px 0;
}
.chat-date-sep::before, .chat-date-sep::after {
    content: ''; flex: 1; height: 1px; background: var(--border);
}
.chat-date-sep span {
    font-size: 11px; font-weight: 600; color: var(--text-muted);
    background: var(--navy-card); border: 1px solid var(--border);
    padding: 4px 14px; border-radius: 20px; white-space: nowrap;
    letter-spacing: 0.3px;
}

/* ── Bubbles ── */
.bubble-wrap {
    display: flex; align-items: flex-end; gap: 8px;
    margin-bottom: 2px;
    animation: bubblePop 0.2s cubic-bezier(0.34,1.56,0.64,1) both;
}
@keyframes bubblePop {
    from { opacity: 0; transform: scale(0.9) translateY(4px); }
    to   { opacity: 1; transform: scale(1) translateY(0); }
}
.bubble-wrap.from-admin { justify-content: flex-end; }
.bubble-wrap.from-mitra { justify-content: flex-start; }

/* Avatar kecil untuk pesan Mitra */
.bubble-sender-avatar {
    width: 30px; height: 30px; border-radius: 50%;
    background: linear-gradient(135deg, #1cc88a, #17a673);
    display: flex; align-items: center; justify-content: center;
    font-size: 11px; font-weight: 800; color: #fff;
    flex-shrink: 0; margin-bottom: 4px;
}
.bubble-wrap.from-admin .bubble-sender-avatar { display: none; }

.bubble-group { display: flex; flex-direction: column; max-width: 68%; }
.bubble-wrap.from-admin .bubble-group { align-items: flex-end; }
.bubble-wrap.from-mitra .bubble-group { align-items: flex-start; }

.bubble-sender-label {
    font-size: 10px; font-weight: 700;
    color: #1cc88a; margin-bottom: 4px; padding-left: 2px;
}

.bubble {
    padding: 10px 14px 6px;
    border-radius: 18px;
    font-size: 13.5px; line-height: 1.55;
    word-break: break-word;
}
/* Bubble Admin (kanan, oranye) */
.bubble-wrap.from-admin .bubble {
    background: linear-gradient(135deg, #ff7a00, #e65c00);
    color: #fff;
    border-bottom-right-radius: 6px;
    box-shadow: 0 4px 14px rgba(255,122,0,0.3);
}
/* Bubble Mitra diterima (kiri, abu) */
.bubble-wrap.from-mitra .bubble {
    background: var(--navy-card);
    color: var(--text-primary);
    border-bottom-left-radius: 6px;
    border: 1px solid var(--border);
    box-shadow: 0 1px 4px rgba(0,0,0,0.06);
}
.bubble-time { font-size: 10px; display: block; text-align: right; margin-top: 4px; }
.bubble-wrap.from-admin .bubble-time { color: rgba(255,255,255,0.72); }
.bubble-wrap.from-mitra .bubble-time { color: var(--text-muted); }

/* Empty / Placeholder */
.chat-empty {
    flex: 1; display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    color: var(--text-muted); gap: 12px; padding: 40px; text-align: center;
}
.chat-empty i { font-size: 48px; opacity: 0.15; }
.chat-empty p { font-size: 13px; }

.chat-placeholder {
    flex: 1; display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    color: var(--text-muted); gap: 18px; text-align: center;
}
.chat-placeholder-icon {
    width: 90px; height: 90px; border-radius: 50%;
    background: var(--navy-card); border: 1px solid var(--border);
    display: flex; align-items: center; justify-content: center;
    font-size: 36px; opacity: 0.4;
}
.chat-placeholder h5 { font-size: 16px; font-weight: 700; color: var(--text-secondary); margin: 0; }
.chat-placeholder p  { font-size: 13px; max-width: 260px; line-height: 1.6; margin: 0; }

/* ── Input Area ── */
.chat-input-area {
    padding: 12px 16px;
    border-top: 1px solid var(--border);
    display: flex; align-items: flex-end; gap: 10px;
    background: var(--navy-light);
    flex-shrink: 0;
}
.chat-input-wrap {
    flex: 1;
    background: var(--navy);
    border: 1.5px solid var(--border);
    border-radius: 24px;
    padding: 10px 16px;
    display: flex; align-items: flex-end;
    transition: border-color 0.2s;
}
.chat-input-wrap:focus-within { border-color: var(--orange); }
.chat-input-box {
    flex: 1; background: transparent; border: none; outline: none;
    resize: none; font-size: 13.5px; color: var(--text-primary);
    max-height: 120px; overflow-y: auto; line-height: 1.5;
    font-family: 'Poppins', sans-serif;
}
.chat-input-box::placeholder { color: var(--text-muted); }
.chat-send-btn {
    width: 46px; height: 46px; flex-shrink: 0; border-radius: 50%;
    background: linear-gradient(135deg, #ff7a00, #e65c00);
    border: none; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: 16px;
    transition: transform 0.15s ease, box-shadow 0.15s ease;
    box-shadow: 0 4px 15px rgba(255,122,0,0.45);
}
.chat-send-btn:hover { transform: scale(1.1); box-shadow: 0 6px 20px rgba(255,122,0,0.55); }
.chat-send-btn:active { transform: scale(0.95); }
.chat-send-btn:disabled { opacity: 0.45; cursor: not-allowed; transform: none; box-shadow: none; }

/* Theme transitions */
.chat-shell, .chat-sidebar, .chat-topbar, .chat-body,
.chat-input-area, .chat-input-wrap, .chat-date-sep span,
.bubble-wrap.from-mitra .bubble, .contact-item {
    transition: background-color 0.3s ease, border-color 0.3s ease, color 0.2s ease;
}

@media (max-width: 768px) { .chat-sidebar { width: 220px; } }
</style>

<div class="wm-page-header">
    <div>
        <div class="wm-page-title">
            <i class="fas fa-comments" style="color:var(--orange);margin-right:10px;"></i>Chat dengan Mitra Faskes
        </div>
        <div class="wm-page-subtitle">Komunikasi dua arah langsung dengan mitra faskes yang terverifikasi</div>
    </div>
</div>

<div class="chat-shell" id="chatShell">

    {{-- ─── SIDEBAR KONTAK ─────────────────────────── --}}
    <div class="chat-sidebar">
        <div class="chat-sidebar-header">
            <div class="chat-sidebar-title">
                <i class="fas fa-hospital-user"></i> Daftar Faskes
            </div>
            <div class="chat-search">
                <i class="fas fa-search"></i>
                <input type="text" id="chatContactSearch" placeholder="Cari faskes..." oninput="filterContacts(this.value)" maxlength="100">
            </div>
        </div>
        <div class="chat-contacts" id="chatContactsList">
            <div class="contact-empty">
                <i class="fas fa-spinner fa-spin"></i>
                Memuat daftar faskes...
            </div>
        </div>
    </div>

    {{-- ─── MAIN CHAT AREA ─────────────────────────── --}}
    <div class="chat-main" id="chatMain">

        {{-- Placeholder --}}
        <div class="chat-placeholder" id="chatPlaceholder">
            <div class="chat-placeholder-icon"><i class="fas fa-comments"></i></div>
            <div>
                <h5>Pilih Faskes untuk Mulai Chat</h5>
                <p style="margin-top:6px;">Pilih salah satu mitra faskes dari daftar di sebelah kiri untuk membuka percakapan.</p>
            </div>
        </div>

        {{-- Area percakapan --}}
        <div id="chatConversation" style="display:none; flex-direction:column; flex:1; overflow:hidden; height:100%;">
            <div class="chat-topbar">
                <div class="chat-topbar-avatar" id="chatTopAvatar">F</div>
                <div>
                    <div class="chat-topbar-name" id="chatTopName">Nama Faskes</div>
                    <div class="chat-topbar-sub">Online</div>
                </div>
            </div>

            <div class="chat-body" id="chatBody">
                <div class="chat-empty">
                    <i class="fas fa-comment-medical"></i>
                    <p>Belum ada pesan. Mulai percakapan!</p>
                </div>
            </div>

            <div class="chat-input-area">
                <div class="chat-input-wrap">
                    <textarea class="chat-input-box" id="chatInputAdmin" rows="1"
                        placeholder="Ketik pesan untuk mitra faskes ini..."
                        onkeydown="if(event.key==='Enter'&&!event.shiftKey){event.preventDefault();adminSendMessage();}"
                        oninput="autoResize(this)" maxlength="500"></textarea>
                </div>
                <button class="chat-send-btn" id="chatSendBtnAdmin" onclick="adminSendMessage()" title="Kirim Pesan">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </div>

    </div>
</div>

</div>{{-- /sectionChat --}}
