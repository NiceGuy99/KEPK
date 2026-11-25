import express from 'express';
import cors from 'cors';
import pino from 'pino';
import QRCode from 'qrcode';
import qrcodeTerminal from 'qrcode-terminal';
import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';
import makeWASocket, { 
    DisconnectReason, 
    useMultiFileAuthState,
    Browsers,
    fetchLatestBaileysVersion
} from '@whiskeysockets/baileys';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const app = express();
const PORT = process.env.PORT || 8088;

app.use(cors());
app.use(express.json());
app.use(express.urlencoded({ extended: true }));

const SESSION_DIR = path.join(__dirname, 'session_auth');
if (!fs.existsSync(SESSION_DIR)) {
    fs.mkdirSync(SESSION_DIR, { recursive: true });
}

let sock = null;
let currentQR = null;
let currentQRImage = null;
let lastQrTimestamp = null;
let connectionState = 'connecting';
let connectedUser = null;
let isReconnecting = false;

// Helper to format phone number to standard international format without '+'
function formatPhoneNumber(phone) {
    let cleaned = String(phone).replace(/\D/g, '');
    if (cleaned.startsWith('0')) {
        cleaned = '62' + cleaned.substring(1);
    } else if (cleaned.startsWith('8')) {
        cleaned = '62' + cleaned;
    }
    return cleaned;
}

async function connectToWhatsApp() {
    if (isReconnecting) return;
    isReconnecting = true;

    try {
        console.log('[WA Gateway] Menghubungi WhatsApp Web Server...');
        connectionState = 'connecting';

        const { version, isLatest } = await fetchLatestBaileysVersion().catch(() => ({
            version: [2, 3000, 1043857760],
            isLatest: true
        }));
        console.log(`[WA Gateway] Menggunakan Baileys Version: ${version.join('.')} (Latest: ${isLatest})`);

        const { state, saveCreds } = await useMultiFileAuthState(SESSION_DIR);

        sock = makeWASocket({
            version,
            auth: state,
            printQRInTerminal: false,
            logger: pino({ level: 'silent' }),
            browser: Browsers.windows('Desktop'),
            connectTimeoutMs: 60000,
            defaultQueryTimeoutMs: 0,
            keepAliveIntervalMs: 10000,
            retryRequestDelayMs: 500
        });

        sock.ev.on('creds.update', saveCreds);

        sock.ev.on('connection.update', async (update) => {
            const { connection, lastDisconnect, qr } = update;

            if (qr) {
                currentQR = qr;
                lastQrTimestamp = Date.now();
                connectionState = 'qr_ready';
                try {
                    currentQRImage = await QRCode.toDataURL(qr, { 
                        width: 320, 
                        margin: 2,
                        color: {
                            dark: '#0f172a',
                            light: '#ffffff'
                        }
                    });
                } catch (err) {
                    console.error('[WA Gateway] Error generating QR image:', err);
                }

                console.log('\n======================================================');
                console.log('[WA Gateway] QR CODE BARU SIAP DISCAN DENGAN WHATSAPP:');
                console.log('Buka http://localhost:' + PORT + '/qr atau scan langsung di bawah ini:');
                console.log('======================================================\n');
                qrcodeTerminal.generate(qr, { small: true });
            }

            if (connection === 'close') {
                const statusCode = (lastDisconnect?.error)?.output?.statusCode;
                const shouldReconnect = statusCode !== DisconnectReason.loggedOut;
                connectionState = 'disconnected';
                connectedUser = null;
                currentQR = null;
                currentQRImage = null;

                console.log(`[WA Gateway] Koneksi terputus. Kode alasan: ${statusCode}. Coba sambung ulang: ${shouldReconnect}`);

                isReconnecting = false;
                if (shouldReconnect) {
                    setTimeout(connectToWhatsApp, 3000);
                } else {
                    console.log('[WA Gateway] Sesi dikeluarkan / expired. Membersihkan sesi lama...');
                    if (fs.existsSync(SESSION_DIR)) {
                        fs.rmSync(SESSION_DIR, { recursive: true, force: true });
                        fs.mkdirSync(SESSION_DIR, { recursive: true });
                    }
                    setTimeout(connectToWhatsApp, 2000);
                }
            } else if (connection === 'open') {
                isReconnecting = false;
                connectionState = 'connected';
                currentQR = null;
                currentQRImage = null;
                connectedUser = sock.user;
                const userPhone = sock.user?.id ? sock.user.id.split(':')[0] : 'Unknown';
                console.log(`\n======================================================`);
                console.log(`[WA Gateway] BERHASIL TERHUBUNG!`);
                console.log(`Nomor WhatsApp Aktif: +${userPhone}`);
                console.log(`Gateway siap mengirimkan notifikasi dari e-KEPK ETHOS.`);
                console.log(`======================================================\n`);
            }
        });
    } catch (err) {
        isReconnecting = false;
        console.error('[WA Gateway] Gagal inisialisasi koneksi:', err);
        setTimeout(connectToWhatsApp, 5000);
    }
}

// 1. Status API (JSON)
app.get('/status', (req, res) => {
    res.json({
        success: true,
        status: connectionState,
        connected: connectionState === 'connected',
        user: connectedUser ? {
            phone: connectedUser.id ? connectedUser.id.split(':')[0] : '',
            name: connectedUser.name || 'Nomor WhatsApp KEPK'
        } : null,
        has_qr: !!currentQRImage,
        qr_image: currentQRImage,
        qr_raw: currentQR,
        updated_at: lastQrTimestamp
    });
});

// 2. Web Visual QR Code & Status Dashboard
app.get('/qr', (req, res) => {
    res.send(`<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WhatsApp Gateway - e-KEPK RSUD Sidoarjo Barat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #0b1120; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 24px; color: #f8fafc; }
        .card-gateway { background: #1e293b; border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 24px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5); max-width: 500px; width: 100%; overflow: hidden; }
        .btn-teal { background: #0d9488; color: #fff; font-weight: 600; border: none; border-radius: 12px; }
        .btn-teal:hover { background: #0f766e; color: #fff; }
        .qr-wrapper { background: #ffffff; padding: 18px; border-radius: 20px; display: inline-block; box-shadow: 0 10px 25px rgba(0,0,0,0.3); }
        .pulse-dot { display: inline-block; width: 10px; height: 10px; border-radius: 50%; background: #10b981; box-shadow: 0 0 0 rgba(16, 185, 129, 0.4); animation: pulse 2s infinite; }
        @keyframes pulse { 0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); } 70% { box-shadow: 0 0 0 10px rgba(16, 185, 129, 0); } 100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); } }
    </style>
</head>
<body>
    <div class="card-gateway p-4 p-md-5 text-center">
        <!-- Header -->
        <div class="d-flex align-items-center justify-content-center gap-3 mb-4">
            <div class="rounded-circle d-flex align-items-center justify-content-center text-white" style="width: 52px; height: 52px; background: linear-gradient(135deg, #10b981 0%, #0d9488 100%);">
                <i class="bi bi-whatsapp fs-3"></i>
            </div>
            <div class="text-start">
                <h5 class="fw-bold mb-0 text-white">e-KEPK WA Gateway</h5>
                <small class="text-white-50">RSUD Sidoarjo Barat</small>
            </div>
        </div>

        <!-- Dynamic Content Box -->
        <div id="dynamicContainer">
            <div class="py-5" id="loadingState">
                <div class="spinner-border mb-3" style="color: #14b8a6; width: 3rem; height: 3rem;" role="status"></div>
                <div class="fw-semibold text-white fs-6">Menghubungkan ke WhatsApp Server...</div>
                <small class="text-white-50 d-block mt-2">Mohon tunggu sejenak, kode QR sedang dibuat...</small>
            </div>
        </div>

        <!-- Footer Actions -->
        <div class="border-top border-secondary border-opacity-25 pt-3 mt-4 d-flex justify-content-between align-items-center small text-white-50">
            <span id="footerStatus"><span class="spinner-grow spinner-grow-sm text-info me-1"></span> Menghubungkan...</span>
            <div class="d-flex gap-2">
                <button onclick="resetSession()" class="btn btn-sm btn-outline-warning px-2.5 py-1 rounded-pill" title="Hapus sesi lama & buat QR baru" style="font-size: 0.75rem;">
                    <i class="bi bi-arrow-repeat me-1"></i> Buat Ulang QR
                </button>
            </div>
        </div>
    </div>

    <script>
        let lastQr = '';
        let wasConnected = false;

        async function checkStatus() {
            try {
                const res = await fetch('/status');
                const data = await res.json();
                renderUI(data);
            } catch (err) {
                document.getElementById('footerStatus').innerHTML = '<span class="text-danger"><i class="bi bi-x-circle me-1"></i> Server Offline</span>';
            }
        }

        function renderUI(data) {
            const container = document.getElementById('dynamicContainer');
            const footerStatus = document.getElementById('footerStatus');

            if (data.connected) {
                footerStatus.innerHTML = '<span class="pulse-dot me-1"></span> <strong class="text-success">ONLINE & TERHUBUNG</strong>';
                container.innerHTML = \`
                    <div class="p-4 rounded-4 border border-success border-opacity-50" style="background: rgba(16, 185, 129, 0.08);">
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center text-success mb-3" style="width: 64px; height: 64px; background: rgba(16, 185, 129, 0.2);">
                            <i class="bi bi-patch-check-fill fs-1"></i>
                        </div>
                        <h5 class="fw-bold text-success mb-1">WhatsApp Terhubung Aktif!</h5>
                        <p class="text-white-50 small mb-3">Gateway siap mengirimkan notifikasi otomatis ke peneliti & penelaah etik.</p>
                        <div class="badge bg-success-subtle text-success border border-success px-3 py-2 rounded-pill fs-6 mb-4">
                            <i class="bi bi-phone me-1"></i> +\${data.user?.phone || ''}
                        </div>
                        <div>
                            <button onclick="logoutWA()" class="btn btn-sm btn-outline-danger px-3 py-2 rounded-pill">
                                <i class="bi bi-box-arrow-right me-1"></i> Putuskan Tautan (Logout)
                            </button>
                        </div>
                    </div>
                \`;
            } else if (data.has_qr && data.qr_image) {
                footerStatus.innerHTML = '<span class="text-warning"><i class="bi bi-qr-code me-1"></i> Menunggu Pemindaian QR</span>';
                
                // Only update DOM if QR changed or first time
                if (lastQr !== data.qr_raw) {
                    lastQr = data.qr_raw;
                    container.innerHTML = \`
                        <div class="mb-2">
                            <div class="qr-wrapper mb-3">
                                <img src="\${data.qr_image}" alt="Scan QR Code WA" class="img-fluid" style="width: 250px; height: 250px; border-radius: 12px;">
                            </div>
                            <h6 class="fw-bold text-white mb-2">Scan QR Code dengan WhatsApp</h6>
                            <div class="text-start rounded-3 p-3 mb-2" style="background: rgba(255,255,255,0.04); font-size: 0.8rem; line-height: 1.6; color: #94a3b8;">
                                <div class="mb-1"><strong class="text-white">1.</strong> Buka WhatsApp di HP resmi KEPK / RSUD</div>
                                <div class="mb-1"><strong class="text-white">2.</strong> Pilih <strong>Titik Tiga</strong> (Android) atau <strong>Pengaturan</strong> (iPhone)</div>
                                <div><strong class="text-white">3.</strong> Ketuk <strong>Perangkat Tertaut</strong> &rarr; <strong>Tautkan Perangkat</strong>, arahkan kamera ke QR di atas</div>
                            </div>
                            <small class="text-white-50" style="font-size: 0.72rem;">
                                <i class="bi bi-info-circle me-1"></i> QR akan otomatis diperbarui secara berkala.
                            </small>
                        </div>
                    \`;
                }
            } else {
                footerStatus.innerHTML = '<span class="text-info"><i class="bi bi-arrow-repeat me-1"></i> Menghubungkan...</span>';
                if (!document.getElementById('loadingState')) {
                    container.innerHTML = \`
                        <div class="py-5" id="loadingState">
                            <div class="spinner-border mb-3" style="color: #14b8a6; width: 3rem; height: 3rem;" role="status"></div>
                            <div class="fw-semibold text-white fs-6">Menghubungkan ke WhatsApp Server...</div>
                            <small class="text-white-50 d-block mt-2">Mohon tunggu sejenak, kode QR sedang dibuat...</small>
                        </div>
                    \`;
                }
            }
        }

        async function resetSession() {
            if (confirm('Buat ulang QR Code dan perbarui sesi?')) {
                try {
                    await fetch('/api/reset', { method: 'POST' });
                    checkStatus();
                } catch(e) {
                    alert('Gagal mereset sesi.');
                }
            }
        }

        async function logoutWA() {
            if (confirm('Apakah Anda yakin ingin memutuskan tautan nomor WhatsApp?')) {
                try {
                    await fetch('/api/logout', { method: 'POST' });
                    checkStatus();
                } catch(e) {
                    alert('Gagal logout.');
                }
            }
        }

        // Poll every 2 seconds
        checkStatus();
        setInterval(checkStatus, 2000);
    </script>
</body>
</html>`);
});

// 3. Send Message API
app.post('/api/send', async (req, res) => {
    const { phone, message } = req.body;

    if (!phone || !message) {
        return res.status(400).json({
            success: false,
            message: 'Parameter phone dan message wajib diisi.'
        });
    }

    if (connectionState !== 'connected' || !sock) {
        return res.status(503).json({
            success: false,
            message: 'WhatsApp Gateway belum terhubung. Harap scan QR Code di http://localhost:' + PORT + '/qr'
        });
    }

    try {
        const formatted = formatPhoneNumber(phone);
        const jid = `${formatted}@s.whatsapp.net`;

        const result = await sock.sendMessage(jid, { text: message });

        return res.json({
            success: true,
            message: 'Pesan berhasil dikirim',
            data: {
                target: formatted,
                messageId: result.key?.id
            }
        });
    } catch (err) {
        console.error('[WA Gateway] Error sending message:', err);
        return res.status(500).json({
            success: false,
            message: 'Gagal mengirim pesan: ' + err.message
        });
    }
});

// 4. Reset & Restart Session API (Creates fresh QR immediately)
app.post('/api/reset', async (req, res) => {
    try {
        console.log('[WA Gateway] Permintaan reset sesi diterima...');
        if (sock) {
            try { sock.end(new Error('Reset requested')); } catch(e) {}
        }
        if (fs.existsSync(SESSION_DIR)) {
            fs.rmSync(SESSION_DIR, { recursive: true, force: true });
            fs.mkdirSync(SESSION_DIR, { recursive: true });
        }
        connectionState = 'connecting';
        connectedUser = null;
        currentQR = null;
        currentQRImage = null;
        isReconnecting = false;

        setTimeout(connectToWhatsApp, 1000);

        return res.json({ success: true, message: 'Sesi direset. Kode QR baru segera dibuat.' });
    } catch (err) {
        return res.status(500).json({ success: false, message: err.message });
    }
});

// 5. Logout API
app.post('/api/logout', async (req, res) => {
    try {
        if (sock) {
            await sock.logout();
        }
        if (fs.existsSync(SESSION_DIR)) {
            fs.rmSync(SESSION_DIR, { recursive: true, force: true });
            fs.mkdirSync(SESSION_DIR, { recursive: true });
        }
        connectionState = 'disconnected';
        connectedUser = null;
        currentQR = null;
        currentQRImage = null;
        isReconnecting = false;

        setTimeout(connectToWhatsApp, 1500);

        return res.json({ success: true, message: 'Berhasil logout' });
    } catch (err) {
        return res.status(500).json({ success: false, message: err.message });
    }
});

// Start server and initialize Baileys
app.listen(PORT, '0.0.0.0', () => {
    console.log(`====================================================`);
    console.log(`[WA Gateway] Server berjalan di http://localhost:${PORT}`);
    console.log(`[WA Gateway] Buka http://localhost:${PORT}/qr untuk scan WhatsApp`);
    console.log(`====================================================`);
    connectToWhatsApp();
});
