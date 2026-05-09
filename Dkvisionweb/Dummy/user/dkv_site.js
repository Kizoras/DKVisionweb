/**
 * dkv_site.js — DKV SMKN 1 Cibinong
 * Versi async murni — tidak ada sync XHR, tidak memblokir halaman.
 * Data disimpan ke api_data.php di server PHP.
 */

(function () {
  'use strict';

  // Deteksi path api_data.php otomatis dari lokasi script ini.
  // dkv_site.js ada di user/, api_data.php ada di root (satu level di atas).
  // Cara ini tetap benar meski project ada di subfolder seperti /DUMMY/.
  var _scriptEl  = document.currentScript;
  var _scriptSrc = _scriptEl ? _scriptEl.src : '';
  var _scriptDir = _scriptSrc ? _scriptSrc.replace(/\/[^\/]+$/, '/') : '';
  var API        = _scriptDir ? _scriptDir + '../api_data.php' : '../api_data.php';

  // ── Async get/set ke server ──────────────────────────────────────────────
  async function serverGet(key) {
    try {
      const r = await fetch(API + '?key=' + encodeURIComponent(key));
      const d = await r.json();
      return d.value !== undefined ? d.value : null;
    } catch (e) { return null; }
  }

  async function serverSet(key, value) {
    try {
      await fetch(API + '?key=' + encodeURIComponent(key), {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ value }),
      });
    } catch (e) {}
    try { window._nativeLS.setItem(key, value); } catch (e) {}
  }

  async function serverRemove(key) {
    try {
      await fetch(API + '?key=' + encodeURIComponent(key), {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ value: null }),
      });
    } catch (e) {}
    try { window._nativeLS.removeItem(key); } catch (e) {}
  }

  // Simpan referensi localStorage asli
  window._nativeLS = window.localStorage;

  // ── API publik ────────────────────────────────────────────────────────────
  window.DKVData = {
    get: serverGet,
    set: serverSet,
    remove: serverRemove,

    async getJSON(key) {
      const raw = await serverGet(key);
      if (!raw) return [];
      try { return JSON.parse(raw); } catch (e) { return []; }
    },

    async setJSON(key, value) {
      await serverSet(key, JSON.stringify(value));
    },
  };

  // ── Setelah semua script load, sinkronkan data server → localStorage ─────
  window.addEventListener('load', function () {

    // Patch saveKey global (dari app.js) agar otomatis sync ke server
    if (typeof window.saveKey === 'function') {
      const _orig = window.saveKey;
      window.saveKey = function (key, list) {
        _orig(key, list);
        serverSet(key, JSON.stringify(list));
      };
    }

    const KEYS = [
      'dkv_guru_data',
      'dkv_about_content',
      'dkv_portfolio',
      'dkv_products',
      'dkv_news',
      'products',
      'dkvProducts',
      'dkvItems',
      'items',
    ];

    let done = 0;
    KEYS.forEach(async (key) => {
      const serverVal = await serverGet(key);
      if (serverVal !== null) {
        // Server punya data → masukkan ke localStorage
        try { window._nativeLS.setItem(key, serverVal); } catch (e) {}
      } else {
        // Server kosong → upload dari localStorage ke server
        const local = window._nativeLS.getItem(key);
        if (local) await serverSet(key, local);
      }
      done++;
      if (done === KEYS.length) rerender();
    });
  });

  function rerender() {
    if (typeof window.renderTeachersFromStorage === 'function') window.renderTeachersFromStorage();
    if (typeof window.renderPortfolio === 'function') window.renderPortfolio();
    if (typeof window.renderNews === 'function') window.renderNews();
    if (typeof window.renderAll === 'function') window.renderAll();
    if (typeof window.updatePengajarStat === 'function') window.updatePengajarStat();
    // products.html pakai renderGrid
    if (typeof window.renderGrid === 'function') {
      const raw = window._nativeLS.getItem('dkv_products')
               || window._nativeLS.getItem('products');
      if (raw) { try { window.renderGrid(JSON.parse(raw)); } catch (e) {} }
    }
    if (typeof window.renderProductCards === 'function') {
      const raw = window._nativeLS.getItem('dkv_products')
               || window._nativeLS.getItem('products');
      if (raw) { try { window.renderProductCards(JSON.parse(raw)); } catch (e) {} }
    }
  }

  console.log('[dkv_site.js] Async mode aktif → api_data.php');
  console.log('[dkv_site.js] API path:', API);

  // ── AUTO BRANDING: update nama website & email di semua halaman ──────────
  // Jalankan segera saat DOM siap (tidak perlu tunggu load penuh)
  function applyBrandingToPage(branding, email) {
    // Selector umum untuk semua elemen logo-name & logo-tagline di semua halaman
    var namEls     = document.querySelectorAll('.logo-name');
    var taglineEls = document.querySelectorAll('.logo-tagline');

    namEls.forEach(function(el) {
      if (branding && branding.nama) el.textContent = branding.nama;
    });
    taglineEls.forEach(function(el) {
      if (branding && branding.tagline) el.textContent = branding.tagline;
    });

    // Email — hanya ada di contact.html (id spesifik) & footer semua halaman
    if (email) {
      // contact.html info kontak
      var contactEl = document.getElementById('contact-email-link');
      if (contactEl) { contactEl.textContent = email; contactEl.href = 'mailto:' + email; }

      // footer semua halaman (pakai class footer-contact-item a dengan envelope icon)
      var footerEmailEl = document.getElementById('footer-email-link');
      if (footerEmailEl) { footerEmailEl.textContent = email; footerEmailEl.href = 'mailto:' + email; }

      // Fallback: cari semua link mailto di footer
      var footerEls = document.querySelectorAll('.footer-contact-item a[href^="mailto:"]');
      footerEls.forEach(function(el) {
        if (!el.id) { // jangan override yang sudah punya ID
          el.textContent = email;
          el.href = 'mailto:' + email;
        }
      });
    }
  }

  async function loadAndApplyBranding() {
    try {
      var brandingRaw = await serverGet('dkv_site_branding');
      var emailRaw    = await serverGet('dkv_email_admin');

      var branding = { nama: 'Jurusan DKV', tagline: 'SMKN 1 Cibinong' };
      var email    = null;

      if (brandingRaw) {
        try { Object.assign(branding, JSON.parse(brandingRaw)); } catch(e) {}
      }
      if (emailRaw) {
        email = emailRaw.replace(/^"|"$/g, '').trim() || null;
      }

      applyBrandingToPage(branding, email);
      console.log('[dkv_site.js] Branding applied:', branding.nama, '|', email);
    } catch(e) {
      console.warn('[dkv_site.js] Branding load failed:', e);
    }
  }

  // Jalankan saat DOM siap
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', loadAndApplyBranding);
  } else {
    loadAndApplyBranding();
  }
  // ─────────────────────────────────────────────────────────────────────────

})();