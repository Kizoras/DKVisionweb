// Simple client-side demo auth + admin controls (restored simple version)
// NOTE: Admin login sekarang menggunakan PHP (admin/login.php), bukan modal JavaScript

const ADMIN_USER = {username: 'admin', password: 'admin123'};

function qs(sel) { return document.querySelector(sel) }

function isAdmin() { return localStorage.getItem('dkv_isAdmin') === '1' }

function renderAuthButtons() {
  // Admin buttons tidak lagi ditampilkan di halaman utama
  // Login sekarang menggunakan PHP page (admin/login.php)
  const adminControls = qs('#adminControls');
  if (adminControls) adminControls.style.display = 'block';

  const portfolioAdmin = qs('#portfolioAdminControls'); if (portfolioAdmin) portfolioAdmin.style.display = isAdmin() ? 'block' : 'none';
  const productsAdmin = qs('#productsAdminControls'); if (productsAdmin) productsAdmin.style.display = isAdmin() ? 'block' : 'none';
  const adminNews = qs('#adminNewsControls'); if (adminNews) adminNews.style.display = isAdmin() ? 'block' : 'none';
  const adminPortfolio = qs('#adminPortfolioControls'); if (adminPortfolio) adminPortfolio.style.display = isAdmin() ? 'block' : 'none';
  const adminProducts = qs('#adminProductsControls'); if (adminProducts) adminProducts.style.display = isAdmin() ? 'block' : 'none';
}

function loadKey(key) { const raw = localStorage.getItem(key) || '[]'; try { return JSON.parse(raw) } catch (e) { return [] } }
function saveKey(key, list) { localStorage.setItem(key, JSON.stringify(list)) }

// Video helpers
function parseYouTubeId(url){
  if(!url) return null;
  try{
    // common YouTube URL forms
    const u = new URL(url, window.location.origin);
    const host = u.hostname.toLowerCase();
    if(host.includes('youtu.be')){
      return u.pathname.slice(1).split(/[?&/]/)[0];
    }
    if(host.includes('youtube.com')){
      // /watch?v=ID or /embed/ID or /shorts/ID
      if(u.searchParams && u.searchParams.get('v')) return u.searchParams.get('v');
      const parts = u.pathname.split('/').filter(Boolean);
      const idx = parts.indexOf('embed'); if(idx>=0 && parts[idx+1]) return parts[idx+1];
      const sidx = parts.indexOf('shorts'); if(sidx>=0 && parts[sidx+1]) return parts[sidx+1];
      if(parts.length>0) return parts[parts.length-1];
    }
  }catch(e){ return null }
  return null;
}

function getVideoThumbnail(url){
  const id = parseYouTubeId(url);
  if(id) return `https://img.youtube.com/vi/${id}/hqdefault.jpg`;
  return null;
}

// News
function loadNews() { return loadKey('dkv_news') }
function saveNews(list) { saveKey('dkv_news', list) }
function renderNews() {
  const container = qs('#newsList'); if (!container) return;
  const list = loadNews(); container.innerHTML = '';
  if (list.length === 0) { container.innerHTML = '<p>Tidak ada berita.</p>'; return }
  const cards = document.createElement('div'); cards.className = 'cards';
  list.forEach((item, idx) => {
    const card = document.createElement('article'); card.className = 'card';
    const vidThumb = item.video ? getVideoThumbnail(item.video) : null;
    const thumbHTML = item.image ? `<div class="thumb"><img src="${item.image}" style="width:100%;object-fit:cover;border-radius:8px" onerror="this.style.display='none'"/></div>` : (vidThumb ? `<div class="thumb video-thumb"><img src="${vidThumb}" style="width:100%;object-fit:cover;border-radius:8px" onerror="this.style.display='none'"/><div class="play-overlay" aria-hidden="true">►</div></div>` : `<div class="thumb">Berita</div>`);
    card.innerHTML = thumbHTML + `
      <h4>${item.title}</h4>
      <p>${item.content}</p>
    `;
    if(item.video){
      const vlink = document.createElement('p'); vlink.innerHTML = `<a class="video-link" href="${item.video}" target="_blank" rel="noopener">Tonton Video</a>`; card.appendChild(vlink);
    }
    if (isAdmin()){
      const actions = document.createElement('div'); actions.className='news-actions';
      const edit = document.createElement('button'); edit.textContent='Edit'; edit.className='secondary';
      const del = document.createElement('button'); del.textContent='Hapus'; del.className='danger';
      // set dataset so delegated handlers can work
      edit.dataset.action = 'edit'; edit.dataset.idx = String(idx); edit.dataset.type = 'news';
      del.dataset.action = 'delete'; del.dataset.idx = String(idx); del.dataset.type = 'news';
      edit.addEventListener('click', function(e){ e.preventDefault(); console.log('Edit clicked (news)', idx); editNews(idx); });
      del.addEventListener('click', function(e){ e.preventDefault(); console.log('Delete clicked (news)', idx); deleteNews(idx); });
      actions.appendChild(edit); actions.appendChild(del); card.appendChild(actions);
    }
    cards.appendChild(card);
  })
  container.appendChild(cards);
}
function deleteNews(idx){ if(!confirm('Hapus berita ini?')) return; const list = loadNews(); list.splice(idx,1); saveNews(list); renderNews(); }

// Portfolio (simple global list)
function loadPortfolio(){ return loadKey('dkv_portfolio') }
function savePortfolio(list){ saveKey('dkv_portfolio', list) }
function renderPortfolio(){
  const container = qs('#portfolioList'); if(!container) return;
  const list = loadPortfolio(); container.innerHTML='';
  if(list.length===0){container.innerHTML='<p>Belum ada portofolio.</p>';return}
  const cards = document.createElement('div'); cards.className='cards';
  list.forEach((item,idx)=>{
    const card = document.createElement('article'); card.className='card';
    const vidThumb = item.video ? getVideoThumbnail(item.video) : null;
    const thumbHTML = item.image ? `<div class="thumb"><img src="${item.image}" style="width:100%;object-fit:cover;border-radius:8px" onerror="this.style.display='none'"/></div>` : (vidThumb ? `<div class="thumb video-thumb"><img src="${vidThumb}" style="width:100%;object-fit:cover;border-radius:8px" onerror="this.style.display='none'"/><div class="play-overlay" aria-hidden="true">►</div></div>` : `<div class="thumb">Karya</div>`);
    card.innerHTML = thumbHTML + `
      <h4>${item.title}</h4>
      <p>${item.description}</p>
    `;
    if(item.video){
      const vlink = document.createElement('p'); vlink.innerHTML = `<a class="video-link" href="${item.video}" target="_blank" rel="noopener">Tonton Video</a>`; card.appendChild(vlink);
    }
    if(isAdmin()){
      const actions=document.createElement('div'); actions.className='news-actions';
      const edit=document.createElement('button'); edit.textContent='Edit'; edit.className='secondary';
      const del=document.createElement('button'); del.textContent='Hapus'; del.className='danger';
      edit.dataset.action = 'edit'; edit.dataset.idx = String(idx); edit.dataset.type = 'portfolio';
      del.dataset.action = 'delete'; del.dataset.idx = String(idx); del.dataset.type = 'portfolio';
      edit.addEventListener('click', function(e){ e.preventDefault(); console.log('Edit clicked (portfolio)', idx); editPortfolio(idx); });
      del.addEventListener('click', function(e){ e.preventDefault(); console.log('Delete clicked (portfolio)', idx); deletePortfolio(idx); });
      actions.appendChild(edit); actions.appendChild(del); card.appendChild(actions);
    }
    cards.appendChild(card);
  })
  container.appendChild(cards);
}
function deletePortfolio(idx){ if(!confirm('Hapus item portfolio?')) return; const list=loadPortfolio(); list.splice(idx,1); savePortfolio(list); renderPortfolio(); }

// Products
function loadProducts(){ return loadKey('dkv_products') }
function saveProducts(list){ saveKey('dkv_products', list) }
function renderProducts(){
  const container=qs('#productsList'); if(!container) return;
  const list=loadProducts(); container.innerHTML='';
  if(list.length===0){container.innerHTML='<p>Belum ada produk.</p>';return}
  const cards = document.createElement('div'); cards.className='cards';
  list.forEach((item,idx)=>{
    const card=document.createElement('article'); card.className='card';
    const vidThumb = item.video ? getVideoThumbnail(item.video) : null;
    const thumbHTML = item.image ? `<div class="thumb"><img src="${item.image}" style="width:100%;height:140px;object-fit:cover;border-radius:8px" onerror="this.style.display='none'"/></div>` : (vidThumb ? `<div class="thumb video-thumb"><img src="${vidThumb}" style="width:100%;height:140px;object-fit:cover;border-radius:8px" onerror="this.style.display='none'"/><div class="play-overlay" aria-hidden="true">►</div></div>` : `<div class="thumb">Produk</div>`);
    card.innerHTML = thumbHTML + `
      <h4>${item.name}</h4>
      <p>${item.desc}</p>
    `;
    if(item.video){
      const vlink = document.createElement('p'); vlink.innerHTML = `<a class="video-link" href="${item.video}" target="_blank" rel="noopener">Tonton Video</a>`; card.appendChild(vlink);
    }
    if(isAdmin()){
      const actions=document.createElement('div'); actions.className='news-actions';
      const edit=document.createElement('button'); edit.textContent='Edit'; edit.className='secondary';
      const del=document.createElement('button'); del.textContent='Hapus'; del.className='danger';
      edit.dataset.action = 'edit'; edit.dataset.idx = String(idx); edit.dataset.type = 'products';
      del.dataset.action = 'delete'; del.dataset.idx = String(idx); del.dataset.type = 'products';
      edit.addEventListener('click', function(e){ e.preventDefault(); console.log('Edit clicked (products)', idx); editProduct(idx); });
      del.addEventListener('click', function(e){ e.preventDefault(); console.log('Delete clicked (products)', idx); deleteProduct(idx); });
      actions.appendChild(edit); actions.appendChild(del); card.appendChild(actions);
    }
    cards.appendChild(card);
    
  })
  container.appendChild(cards);
}
function deleteProduct(idx){ if(!confirm('Hapus produk ini?')) return; const list=loadProducts(); list.splice(idx,1); saveProducts(list); renderProducts(); }

// Events
const addNewsBtn = qs('#addNewsBtn'); if(addNewsBtn) addNewsBtn.onclick = addNews;
const addPortfolioBtn = qs('#addPortfolioBtn'); if(addPortfolioBtn) addPortfolioBtn.onclick = addPortfolio;
const addProductBtn = qs('#addProductBtn'); if(addProductBtn) addProductBtn.onclick = addProduct;
// Contact form handler
const contactForm = qs('#contactForm'); if(contactForm) contactForm.addEventListener('submit', function(e){ e.preventDefault(); alert('Pesan telah dikirim! Terima kasih atas kontaknya.'); contactForm.reset(); });
// Paste image buttons (open modal and focus image input)
// Helper: determine which type/page we're on by presence of known containers
function detectCurrentType(){ if(qs('#newsList')) return 'news'; if(qs('#productsList')) return 'products'; if(qs('#portfolioList')) return 'portfolio'; return 'news'; }

const pasteBtn = qs('#pasteImageBtn'); if(pasteBtn) pasteBtn.onclick = ()=>{ const t = detectCurrentType(); openItemModal(t,'add'); if(itemImage){ itemImage.focus(); itemImage.select(); } }
const pasteBtnNews = qs('#pasteImageBtn_news'); if(pasteBtnNews) pasteBtnNews.onclick = ()=>{ openItemModal('news','add'); if(itemImage){ itemImage.focus(); itemImage.select(); } }
const pasteBtnPort = qs('#pasteImageBtn_port'); if(pasteBtnPort) pasteBtnPort.onclick = ()=>{ openItemModal('portfolio','add'); if(itemImage){ itemImage.focus(); itemImage.select(); } }
const pasteBtnProd = qs('#pasteImageBtn_prod'); if(pasteBtnProd) pasteBtnProd.onclick = ()=>{ openItemModal('products','add'); if(itemImage){ itemImage.focus(); itemImage.select(); } }

// Item modal handling (for add/edit news, portfolio, products)
let itemModalState = { mode: null, type: null, idx: null };
const itemModal = qs('#itemModal');
const itemForm = qs('#itemForm');
const itemTitle = qs('#itemTitle');
const itemDesc = qs('#itemDesc');
const itemImage = qs('#itemImage');
const itemVideo = qs('#itemVideo');
const itemPreview = qs('#itemPreview');
const itemModalTitle = qs('#itemModalTitle');
const itemCancel = qs('#itemCancel');

function openItemModal(type, mode='add', idx=null){
  console.log('openItemModal called', {type, mode, idx});
  if(!itemModal) { console.warn('itemModal not found'); return; }
  itemModalState = { type, mode, idx };
  if(itemForm) itemForm.reset(); else console.warn('itemForm not found');
  if(itemPreview) itemPreview.innerHTML='Preview'; else console.warn('itemPreview not found');
    if(mode==='edit'){
    let src; let vid;
    if(type==='news'){ const list = loadNews(); const it = list[idx]; itemTitle.value = it.title; itemDesc.value = it.content; src = it.image; vid = it.video }
    if(type==='portfolio'){ const list = loadPortfolio(); const it = list[idx]; itemTitle.value = it.title; itemDesc.value = it.description; src = it.image; vid = it.video }
    if(type==='products'){ const list = loadProducts(); const it = list[idx]; itemTitle.value = it.name; itemDesc.value = it.desc; src = it.image; vid = it.video }
    itemModalTitle.textContent = 'Ubah Item';
    if(src) { itemImage.value = src; itemPreview.innerHTML = `<img src="${src}" style="max-width:100%;border-radius:6px"/>` }
    if(itemVideo && vid) { itemVideo.value = vid }
    // if there is a video but no image, show the video thumbnail preview
    if(!src && vid && itemPreview){ const thumb = getVideoThumbnail(vid); if(thumb) itemPreview.innerHTML = `<img src="${thumb}" style="max-width:100%;border-radius:6px" onerror="this.style.display='none'"/>` }
  } else {
    itemModalTitle.textContent = 'Tambah Item';
  }
  itemModal.style.display='flex';
  itemModal.classList.add('show');
  // focus first input for accessibility
  try{ const first = itemModal.querySelector('input,textarea,button'); if(first) first.focus(); }catch(e){}
}

function closeItemModal(){ if(!itemModal) return; itemModal.style.display='none'; itemModalState = { mode:null, type:null, idx:null }; }

if(itemImage){ itemImage.addEventListener('input', ()=>{ const v = itemImage.value.trim(); if(!v){ itemPreview.innerHTML='Preview'; return } itemPreview.innerHTML = `<img src="${v}" style="max-width:100%;border-radius:6px" onerror="this.style.display='none'"/>` }) }
if(itemVideo){ itemVideo.addEventListener('input', ()=>{ const v = itemVideo.value.trim(); if(!v) return; const thumb = getVideoThumbnail(v); if(thumb){ itemPreview.innerHTML = `<img src="${thumb}" style="max-width:100%;border-radius:6px" onerror="this.style.display='none'"/>` } else { /* no thumbnail available */ } }) }
if(itemCancel) itemCancel.onclick = (e)=>{ e.preventDefault(); closeItemModal(); }

if(itemForm) itemForm.addEventListener('submit', function(e){ e.preventDefault(); const title = itemTitle.value.trim(); const desc = itemDesc.value.trim(); const img = itemImage.value.trim(); const vid = (itemVideo?itemVideo.value.trim():''); if(!title) return alert('Judul diperlukan'); const st = itemModalState; if(st.mode==='add'){
  if(st.type==='news'){ const list=loadNews(); list.unshift({title,content:desc,image:img,video:vid}); saveNews(list); renderNews(); }
  if(st.type==='portfolio'){ const list=loadPortfolio(); list.unshift({title,description:desc,image:img,video:vid}); savePortfolio(list); renderPortfolio(); }
  if(st.type==='products'){ const list=loadProducts(); list.unshift({name:title,desc, image:img,video:vid}); saveProducts(list); renderProducts(); }
} else if(st.mode==='edit'){
  const idx=st.idx;
  if(st.type==='news'){ const list=loadNews(); list[idx]={title,content:desc,image:img,video:vid}; saveNews(list); renderNews(); }
  if(st.type==='portfolio'){ const list=loadPortfolio(); list[idx]={title,description:desc,image:img,video:vid}; savePortfolio(list); renderPortfolio(); }
  if(st.type==='products'){ const list=loadProducts(); list[idx]={name:title,desc,image:img,video:vid}; saveProducts(list); renderProducts(); }
}
closeItemModal(); renderAuthButtons(); })

// Replace prompt-based add/edit with modal opens
function addNews(){ openItemModal('news','add'); }
function editNews(idx){ openItemModal('news','edit',idx); }
function addPortfolio(){ openItemModal('portfolio','add'); }
function editPortfolio(idx){ openItemModal('portfolio','edit',idx); }
function addProduct(){ openItemModal('products','add'); }
function editProduct(idx){ openItemModal('products','edit',idx); }



function renderAll(){ renderNews(); renderPortfolio(); renderProducts(); }

renderAuthButtons(); renderAll();

// Delegated click handlers for edit/delete to make handlers robust
function delegatedEditDeleteHandler(e){
  // find closest button from event target
  const btn = (e.target && typeof e.target.closest === 'function') ? e.target.closest('button') : null;
  if(!btn) return;
  const action = btn.dataset && btn.dataset.action;
  if(!action) return;
  const type = btn.dataset.type;
  const idx = parseInt(btn.dataset.idx,10);
  console.log('Delegated click', action, type, idx, 'target:', e.target);
  if(action === 'edit'){
    if(type === 'news') editNews(idx);
    else if(type === 'portfolio') editPortfolio(idx);
    else if(type === 'products') editProduct(idx);
  } else if(action === 'delete'){
    if(type === 'news') deleteNews(idx);
    else if(type === 'portfolio') deletePortfolio(idx);
    else if(type === 'products') deleteProduct(idx);
  }
}

const newsContainer = qs('#newsList'); if(newsContainer) newsContainer.addEventListener('click', delegatedEditDeleteHandler);
const portContainer = qs('#portfolioList'); if(portContainer) portContainer.addEventListener('click', delegatedEditDeleteHandler);
const prodContainer = qs('#productsList'); if(prodContainer) prodContainer.addEventListener('click', delegatedEditDeleteHandler);

// Paste handler: accept image file or image URL from clipboard and populate item modal (quick paste)
document.addEventListener('paste', function(e){
  try{
    // Only allow paste-to-modal for admins to avoid unexpected behavior for visitors
    if(!isAdmin()) return;
    const clipboard = (e.clipboardData || window.clipboardData);
    if(!clipboard) return;

    // First, check for image files
    if(clipboard.items){
      for(let i=0;i<clipboard.items.length;i++){
        const it = clipboard.items[i];
        if(it.kind === 'file' && it.type.indexOf('image/') === 0){
          const file = it.getAsFile();
          const reader = new FileReader();
          reader.onload = function(){
            // open modal for the current page when pasting image
            const type = detectCurrentType();
            openItemModal(type,'add');
            if(itemImage) itemImage.value = reader.result;
            if(itemPreview) itemPreview.innerHTML = `<img src="${reader.result}" style="max-width:100%;border-radius:6px"/>`;
          };
          reader.readAsDataURL(file);
          e.preventDefault();
          return;
        }
      }
    }

    // Next, check for text (possible URL)
    const text = clipboard.getData('text/plain') || '';
    const t = text.trim();
    if(t){
      // detect common image extensions or data image
      const isImageUrl = /^data:image\//i.test(t) || /\.(png|jpe?g|gif|webp|bmp)(\?|$)/i.test(t);
      if(isImageUrl){
        const type = detectCurrentType();
        openItemModal(type,'add');
        if(itemImage) itemImage.value = t;
        if(itemPreview) itemPreview.innerHTML = `<img src="${t}" style="max-width:100%;border-radius:6px" onerror="this.style.display='none'"/>`;
        e.preventDefault();
        return;
      }
    }
  }catch(err){
    // ignore paste errors silently
    console.error('Paste handler error', err);
  }
});

// Check for admin page access
if (window.location.pathname.includes('admin.html') && !isAdmin()) {
  window.location.href = 'index.html';
}

renderAll();

// ========== BACKGROUND SLIDESHOW (TIDAK MENGANGGU KONTEN) ==========
(function() {
    console.log('Initializing background slideshow...');
    
    // Daftar gambar background Anda
    const images = [
        // GANTI DENGAN GAMBAR ANDA - gunakan path yang benar
        '../gambar/webpage.jpg',
        '../gambar/dkvlab.jpg',
        // tambah gambar lain di sini
    ];
    
    // Jika pakai gambar online untuk test (copot tanda // di bawah)
    /*
    const images = [
        'https://images.unsplash.com/photo-1561070791-2526d30994b5?w=1920&h=1080&fit=crop',
        'https://images.unsplash.com/photo-1581291518633-83b4ebd1d83e?w=1920&h=1080&fit=crop',
        'https://images.unsplash.com/photo-1542744173-8e7e53415bb0?w=1920&h=1080&fit=crop',
    ];
    */
    
    let currentIndex = 0;
    
    // Cek apakah sudah ada container slideshow
    let container = document.querySelector('.background-slideshow');
    if (!container) {
        container = document.createElement('div');
        container.className = 'background-slideshow';
        document.body.insertBefore(container, document.body.firstChild);
    } else {
        // Kosongkan container jika sudah ada
        container.innerHTML = '';
    }
    
    // Buat slide untuk setiap gambar
    images.forEach((src, index) => {
        const slide = document.createElement('div');
        slide.className = 'slide';
        if (index === 0) slide.classList.add('active');
        slide.style.backgroundImage = `url('${src}')`;
        slide.style.backgroundSize = 'cover';
        slide.style.backgroundPosition = 'center';
        slide.style.backgroundRepeat = 'no-repeat';
        container.appendChild(slide);
    });
    
    // Buat overlay jika belum ada
    let overlay = document.querySelector('.background-overlay');
    if (!overlay) {
        overlay = document.createElement('div');
        overlay.className = 'background-overlay';
        document.body.appendChild(overlay);
    }

    // Fungsi untuk mengatur kecerahan overlay
function setOverlayOpacity(level = 0) {
    const overlay = document.querySelector('.background-overlay');
    if (overlay) {
        // Hapus style inline sebelumnya
        overlay.style.background = '';
        overlay.style.backgroundColor = '';
        // Set ulang
        overlay.style.background = `rgba(0, 0, 0, ${level})`;
        console.log(`Overlay opacity set to ${level}`);
        
        // Cek apakah berhasil
        const computed = window.getComputedStyle(overlay);
        console.log('Current overlay background:', computed.background);
    } else {
        console.log('Overlay element not found');
    }
}

// Jalankan
setOverlayOpacity(0);

// Contoh penggunaan di console:
// setOverlayOpacity(0.1)  // sangat terang
// setOverlayOpacity(0.3)  // sedang
// setOverlayOpacity(0.5)  // standar
    
    // Fungsi ganti gambar
    function changeSlide() {
        const slides = document.querySelectorAll('.background-slideshow .slide');
        if (slides.length === 0) return;
        
        slides[currentIndex].classList.remove('active');
        currentIndex = (currentIndex + 1) % slides.length;
        slides[currentIndex].classList.add('active');
    }
    
    // Jalankan slideshow setiap 5 detik jika ada lebih dari 1 gambar
    if (images.length > 1) {
        setInterval(changeSlide, 5000);
    }
    
    console.log('Background slideshow started with', images.length, 'images');
})(); 

