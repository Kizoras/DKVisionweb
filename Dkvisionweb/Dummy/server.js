/**
 * server.js — DKV SMKN 1 Cibinong
 * Server sederhana yang menyajikan file statis + API penyimpanan data JSON.
 * Jalankan: node server.js
 * Akses  : http://localhost:3000
 */

const http   = require('http');
const fs     = require('fs');
const path   = require('path');
const url    = require('url');

const PORT      = process.env.PORT || 3000;
const DATA_DIR  = path.join(__dirname, 'data');   // folder tempat file JSON disimpan
const SITE_DIR  = path.join(__dirname);            // root folder website

// ── Pastikan folder data/ ada ──────────────────────────────────────────────
if (!fs.existsSync(DATA_DIR)) fs.mkdirSync(DATA_DIR, { recursive: true });

// ── MIME types ────────────────────────────────────────────────────────────
const MIME = {
  '.html': 'text/html; charset=utf-8',
  '.css' : 'text/css; charset=utf-8',
  '.js'  : 'application/javascript; charset=utf-8',
  '.json': 'application/json; charset=utf-8',
  '.png' : 'image/png',
  '.jpg' : 'image/jpeg',
  '.jpeg': 'image/jpeg',
  '.gif' : 'image/gif',
  '.svg' : 'image/svg+xml',
  '.ico' : 'image/x-icon',
  '.woff': 'font/woff',
  '.woff2':'font/woff2',
  '.ttf' : 'font/ttf',
  '.mp4' : 'video/mp4',
  '.webp': 'image/webp',
};

// ── Helper: baca body request ──────────────────────────────────────────────
function readBody(req) {
  return new Promise((resolve, reject) => {
    let body = '';
    req.on('data', chunk => { body += chunk; });
    req.on('end',  () => resolve(body));
    req.on('error', reject);
  });
}

// ── Helper: kirim JSON ─────────────────────────────────────────────────────
function sendJSON(res, status, data) {
  const body = JSON.stringify(data);
  res.writeHead(status, {
    'Content-Type' : 'application/json; charset=utf-8',
    'Access-Control-Allow-Origin' : '*',
    'Access-Control-Allow-Methods': 'GET, POST, OPTIONS',
    'Access-Control-Allow-Headers': 'Content-Type',
    'Content-Length': Buffer.byteLength(body),
  });
  res.end(body);
}

// ── Sanitasi key (hanya huruf, angka, underscore, strip) ──────────────────
function safeKey(key) {
  return String(key).replace(/[^a-zA-Z0-9_\-]/g, '_').substring(0, 64);
}

// ── Handler API /api/data ─────────────────────────────────────────────────
async function handleAPI(req, res) {
  const parsed = url.parse(req.url, true);
  const key    = safeKey(parsed.query.key || '');

  // Preflight CORS
  if (req.method === 'OPTIONS') {
    res.writeHead(204, {
      'Access-Control-Allow-Origin' : '*',
      'Access-Control-Allow-Methods': 'GET, POST, OPTIONS',
      'Access-Control-Allow-Headers': 'Content-Type',
    });
    return res.end();
  }

  // GET /api/data?key=xxx  → kembalikan nilai
  if (req.method === 'GET') {
    if (!key) return sendJSON(res, 400, { error: 'key required' });
    const file = path.join(DATA_DIR, key + '.json');
    if (!fs.existsSync(file)) return sendJSON(res, 200, { value: null });
    try {
      const value = fs.readFileSync(file, 'utf-8');
      return sendJSON(res, 200, { value });
    } catch (e) {
      return sendJSON(res, 500, { error: 'read error' });
    }
  }

  // POST /api/data?key=xxx  body: { value: "..." }
  if (req.method === 'POST') {
    if (!key) return sendJSON(res, 400, { error: 'key required' });
    try {
      const body  = await readBody(req);
      const { value } = JSON.parse(body);
      const file  = path.join(DATA_DIR, key + '.json');
      if (value === null || value === undefined) {
        // DELETE semantics
        if (fs.existsSync(file)) fs.unlinkSync(file);
        return sendJSON(res, 200, { ok: true });
      }
      fs.writeFileSync(file, String(value), 'utf-8');
      return sendJSON(res, 200, { ok: true });
    } catch (e) {
      return sendJSON(res, 500, { error: 'write error: ' + e.message });
    }
  }

  sendJSON(res, 405, { error: 'method not allowed' });
}

// ── Handler file statis ───────────────────────────────────────────────────
function handleStatic(req, res) {
  let pathname = url.parse(req.url).pathname;

  // Hilangkan prefix /user/ jika ada (sesuai struktur devtunnel kamu)
  pathname = pathname.replace(/^\/user\//, '/');

  // Default ke index.html
  if (pathname === '/' || pathname === '') pathname = '/index.html';

  const filePath = path.join(SITE_DIR, pathname);

  // Keamanan: tidak boleh keluar dari SITE_DIR
  if (!filePath.startsWith(SITE_DIR)) {
    res.writeHead(403); return res.end('Forbidden');
  }

  fs.readFile(filePath, (err, data) => {
    if (err) {
      // Coba tambahkan .html
      const withHtml = filePath + '.html';
      fs.readFile(withHtml, (err2, data2) => {
        if (err2) { res.writeHead(404); return res.end('404 Not Found'); }
        const ext = '.html';
        res.writeHead(200, { 'Content-Type': MIME[ext] || 'application/octet-stream' });
        res.end(data2);
      });
      return;
    }
    const ext = path.extname(filePath).toLowerCase();
    res.writeHead(200, { 'Content-Type': MIME[ext] || 'application/octet-stream' });
    res.end(data);
  });
}

// ── Server utama ──────────────────────────────────────────────────────────
const server = http.createServer(async (req, res) => {
  const pathname = url.parse(req.url).pathname;

  // Route: semua /api/data → API handler
  if (pathname === '/api/data') {
    return handleAPI(req, res);
  }

  // Lainnya → file statis
  handleStatic(req, res);
});

server.listen(PORT, () => {
  console.log(`\n DKV Server berjalan di http://localhost:${PORT}`);
  console.log(`   Data disimpan di: ${DATA_DIR}`);
  console.log(`   Tekan Ctrl+C untuk berhenti.\n`);
});