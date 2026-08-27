(function () {
  var footer = document.querySelector('.fusion-tb-footer');
  if (!footer) return;

  var path = location.pathname;
  var emailAddress = 'sekretariat@naturalkapital.or.id';

  // 1. Direct Clickable Email Link (Lompat langsung ke Gmail / Aplikasi Email)
  var emailElements = footer.querySelectorAll('a[href*="mailto:"], a[href*="naturalkapital.or.id"], .ynki-email-link, .ynki-email-copy-btn');
  emailElements.forEach(function (el) {
    el.setAttribute('href', 'mailto:' + emailAddress);
    el.setAttribute('target', '_blank');
    el.setAttribute('rel', 'noopener');
    el.style.color = '#ffffff';
    el.style.textDecoration = 'none';
    el.style.cursor = 'pointer';
    el.style.display = 'inline-flex';
    el.style.alignItems = 'center';
    el.style.gap = '6px';
    el.title = 'Kirim email ke ' + emailAddress;
    el.innerHTML = '<span class="ynki-email-text" style="color:#ffffff;font-weight:600;">' + emailAddress + '</span>';
    el.onclick = function (e) {
      window.location.href = 'mailto:' + emailAddress;
    };
  });

  // 2. Format / Modernize Left Column (Organization Info & Socials)
  var col = footer.querySelector('.fusion-layout-column');
  var wrap = col && col.querySelector('.fusion-column-wrapper');
  if (wrap) {
    var oldSocial = wrap.querySelector('.ynki-footer-social');
    if (oldSocial) oldSocial.remove();

    var socialContainer = document.createElement('div');
    socialContainer.className = 'ynki-footer-social';

    var socials = [
      {
        name: 'YouTube',
        url: 'https://www.youtube.com/@naturalkapital123',
        svg: '<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>'
      },
      {
        name: 'Facebook',
        url: 'https://web.facebook.com/naturalkapital?_rdc=1&_rdr#',
        svg: '<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.469h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.469h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>'
      },
      {
        name: 'Instagram',
        url: 'https://www.instagram.com/yayasannaturalkapital',
        svg: '<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>'
      },
      {
        name: 'X (Twitter)',
        url: 'https://x.com/kapital_natural',
        svg: '<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>'
      },
      {
        name: 'LinkedIn',
        url: 'https://id.linkedin.com/company/natural-kapital-foundation',
        svg: '<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M4.98 3.5c0 1.381-1.11 2.5-2.48 2.5s-2.48-1.119-2.48-2.5c0-1.38 1.11-2.5 2.48-2.5s2.48 1.12 2.48 2.5zm.02 4.5h-5v16h5v-16zm7.982 0h-4.968v16h4.969v-8.399c0-4.67 6.029-5.052 6.029 0v8.399h4.988v-10.131c0-7.88-8.922-7.593-11.018-3.714v-2.155z"/></svg>'
      }
    ];

    socials.forEach(function (item) {
      var a = document.createElement('a');
      a.href = item.url;
      a.target = '_blank';
      a.rel = 'noopener';
      a.title = item.name;
      a.className = 'ynki-social-btn ynki-social-' + item.name.toLowerCase().replace(/[^a-z0-9]/g, '');
      a.innerHTML = item.svg;
      a.setAttribute('aria-label', item.name);
      socialContainer.appendChild(a);
    });

    wrap.appendChild(socialContainer);
  }

  // 3. Tautan cepat sesuai konteks halaman
  var sections = [
    {
      match: ['/sejarah-visi-misi/', '/tim/', '/lgos/', '/portofolio/', '/transparansi/', '/annual-report/'],
      links: [
        ['/sejarah-visi-misi/', 'Sejarah, Visi & Misi'],
        ['/tim/', 'Tim & Pengurus YNKI'],
        ['/lgos/', 'LGOS: Sistem Operasi Organisasi'],
        ['/portofolio/', 'Portfolio'],
        ['/annual-report/', 'Annual Report']
      ]
    },
    {
      match: ['/program/', '/landscape-governance/', '/natural-capital/', '/sustainable-commodity/', '/landscape-intelligence/', '/institutional-partnership/'],
      links: [
        ['/landscape-governance/', 'Landscape Governance'],
        ['/natural-capital/', 'Natural Capital & Restoration'],
        ['/sustainable-commodity/', 'Sustainable Commodity System'],
        ['/landscape-intelligence/', 'Landscape Intelligence & Innovation'],
        ['/annual-report/', 'Annual Report']
      ]
    },
    {
      match: ['/dampak/', '/kisah-perubahan/', '/liputan-media/'],
      links: [
        ['/dampak/', 'Dampak'],
        ['/kisah-perubahan/', 'Kisah Perubahan'],
        ['/liputan-media/', 'Liputan Media'],
        ['/annual-report/', 'Annual Report']
      ]
    },
    {
      match: ['/kategori/', '/tag/', '/news-features/', '/penerbitan/', '/publikasi/', '/penelitian-laporan/', '/analisis-kebijakan/', '/perspektif-budaya/', '/data-spasial-gis/', '/data-spasial-dan-gis/', '/stori-foto-video/', '/story-foto-video/'],
      links: [
        ['/news-features/', 'News & Features'],
        ['/penelitian-laporan/', 'Penelitian & Laporan'],
        ['/analisis-kebijakan/', 'Analisis & Kebijakan'],
        ['/kategori/perspektif-budaya/', 'Perspektif Budaya'],
        ['/data-spasial-gis/', 'Data Spasial dan GIS'],
        ['/annual-report/', 'Annual Report']
      ]
    }
  ];

  // Default untuk Beranda dan halaman umum
  var berandaLinks = [
    ['/sejarah-visi-misi/', 'Sejarah, Visi & Misi'],
    ['/landscape-governance/', 'Landscape Governance'],
    ['/dampak/', 'Dampak'],
    ['/news-features/', 'News & Features'],
    ['/annual-report/', 'Annual Report']
  ];


  var quickLinks = berandaLinks;
  for (var s = 0; s < sections.length; s++) {
    if (sections[s].match.some(function (p) { return path.indexOf(p) === 0; })) {
      quickLinks = sections[s].links;
      break;
    }
  }

  var strongs = footer.querySelectorAll('strong');
  var ul = null;
  for (var i = 0; i < strongs.length; i++) {
    if (strongs[i].textContent.trim() === 'Tautan Cepat') {
      var t = strongs[i].closest('div');
      if (t) ul = t.querySelector('ul');
      break;
    }
  }
  if (!ul) return;

  ul.innerHTML = quickLinks.map(function (l) {
    return '<li><a href="' + l[0] + '">' + l[1] + '</a></li>';
  }).join('');
})();

/* ==========================================================================
   SMOOTH GENTLE SCROLL ANIMATION (FOR HERO ACTION BUTTONS & ANCHOR LINKS)
   ========================================================================== */
(function () {
  function smoothScrollTo(targetPosition, duration) {
    var startPosition = window.pageYOffset || document.documentElement.scrollTop;
    var distance = targetPosition - startPosition;
    var startTime = null;

    function animation(currentTime) {
      if (startTime === null) startTime = currentTime;
      var timeElapsed = currentTime - startTime;
      var progress = Math.min(timeElapsed / duration, 1);

      // Easing function: easeInOutCubic (gerakan awal perlahan, lembut di akhir)
      var ease = progress < 0.5
        ? 4 * progress * progress * progress
        : 1 - Math.pow(-2 * progress + 2, 3) / 2;

      window.scrollTo(0, startPosition + distance * ease);

      if (timeElapsed < duration) {
        requestAnimationFrame(animation);
      }
    }

    requestAnimationFrame(animation);
  }

  document.addEventListener('click', function (e) {
    var link = e.target.closest('a[href*="#"]');
    if (!link) return;

    var href = link.getAttribute('href');
    if (!href) return;

    // Jika tautan adalah anchor lokal pada halaman yang sama (misal: "#visi-section" atau "/sejarah-visi-misi/#visi-section")
    var urlParts = href.split('#');
    var targetId = urlParts[1];
    var targetPath = urlParts[0];

    if (!targetId) return;

    var currentPath = window.location.pathname;
    var isSamePage = !targetPath || targetPath === '' || targetPath === currentPath || currentPath.indexOf(targetPath) === 0;

    if (isSamePage) {
      var targetElem = document.getElementById(targetId) || document.querySelector('#' + targetId) || document.querySelector('[name="' + targetId + '"]');
      if (targetElem) {
        e.preventDefault();
        var headerOffset = 30;
        var elementPosition = targetElem.getBoundingClientRect().top;
        var offsetPosition = elementPosition + (window.pageYOffset || document.documentElement.scrollTop) - headerOffset;
        
        // Animasi scroll perlahan (durasi 950ms)
        smoothScrollTo(offsetPosition, 950);

        if (history.pushState) {
          history.pushState(null, null, '#' + targetId);
        }
      }
    }
  });
})();

/* ==========================================================================
   YNKI UNIVERSAL NAVIGATION ENGINE (DESKTOP DROPDOWN & MOBILE ACCORDION DRAWER)
   ========================================================================== */
(function () {
  // 1. Inject Styles Directly to Document Head to Guarantee Styling on ALL Pages
  function injectNavStyles() {
    if (document.getElementById('ynki-navigation-core-styles')) return;
    var style = document.createElement('style');
    style.id = 'ynki-navigation-core-styles';
    style.textContent = `
      /* HIDE MOBILE DRAWER & BACKDROP ON DESKTOP */
      @media (min-width: 993px) {
        .ynki-mobile-nav-drawer,
        .ynki-mobile-nav-backdrop,
        #ynki-mobile-drawer,
        #ynki-mobile-backdrop {
          display: none !important;
          visibility: hidden !important;
          opacity: 0 !important;
          pointer-events: none !important;
        }

        /* Desktop Dropdown Submenus */
        .awb-menu__main-ul {
          display: flex !important;
          align-items: center !important;
        }
        .awb-menu__main-li {
          position: relative !important;
        }
        .awb-menu__main-li .awb-menu__sub-ul {
          display: block !important;
          position: absolute !important;
          top: 100% !important;
          left: 0 !important;
          min-width: 260px !important;
          background: #ffffff !important;
          border: 1px solid #d2e8d1 !important;
          border-top: 3px solid #117710 !important;
          border-radius: 0 0 12px 12px !important;
          box-shadow: 0 14px 32px rgba(0, 0, 0, 0.15) !important;
          padding: 8px 0 !important;
          margin: 0 !important;
          list-style: none !important;
          opacity: 0 !important;
          visibility: hidden !important;
          pointer-events: none !important;
          transform: translateY(10px) !important;
          transition: opacity 0.22s ease, transform 0.22s ease, visibility 0.22s !important;
          z-index: 999999 !important;
        }
        .awb-menu__main-li:hover > .awb-menu__sub-ul,
        .awb-menu__main-li:focus-within > .awb-menu__sub-ul,
        .awb-menu__main-li.is-open > .awb-menu__sub-ul {
          opacity: 1 !important;
          visibility: visible !important;
          pointer-events: auto !important;
          transform: translateY(0) !important;
        }
        .awb-menu__sub-li {
          list-style: none !important;
          padding: 0 !important;
          margin: 0 !important;
          display: block !important;
        }
        .awb-menu__sub-a {
          display: block !important;
          padding: 10px 22px !important;
          font-size: 13.5px !important;
          font-weight: 600 !important;
          color: #1e2d24 !important;
          text-decoration: none !important;
          transition: all 0.2s ease !important;
          border-left: 3px solid transparent !important;
          background: transparent !important;
          line-height: 1.4 !important;
        }
        .awb-menu__sub-a:hover {
          background: #eaf6ea !important;
          color: #117710 !important;
          border-left: 3px solid #117710 !important;
          padding-left: 26px !important;
        }
      }

      /* MOBILE DRAWER & ACCORDION (<= 992px) */
      @media (max-width: 992px) {
        .ynki-mobile-nav-backdrop {
          position: fixed !important;
          top: 0 !important;
          left: 0 !important;
          width: 100vw !important;
          height: 100vh !important;
          background: rgba(0, 0, 0, 0.55) !important;
          backdrop-filter: blur(4px) !important;
          -webkit-backdrop-filter: blur(4px) !important;
          z-index: 999998 !important;
          opacity: 0 !important;
          visibility: hidden !important;
          transition: opacity 0.3s ease, visibility 0.3s ease !important;
        }
        .ynki-mobile-nav-backdrop.active {
          opacity: 1 !important;
          visibility: visible !important;
        }
        .ynki-mobile-nav-drawer {
          position: fixed !important;
          top: 0 !important;
          right: -100% !important;
          width: 320px !important;
          max-width: 88vw !important;
          height: 100vh !important;
          background: #ffffff !important;
          box-shadow: -6px 0 28px rgba(0, 0, 0, 0.2) !important;
          z-index: 999999 !important;
          display: flex !important;
          flex-direction: column !important;
          transition: right 0.35s cubic-bezier(0.16, 1, 0.3, 1) !important;
          overflow-y: auto !important;
          box-sizing: border-box !important;
        }
        .ynki-mobile-nav-drawer.active {
          right: 0 !important;
        }
        .ynki-mobile-nav-header {
          display: flex !important;
          align-items: center !important;
          justify-content: space-between !important;
          padding: 16px 20px !important;
          border-bottom: 1px solid #eef4f0 !important;
          background: #fdfdfd !important;
        }
        .ynki-mobile-nav-logo img {
          height: 38px !important;
          width: auto !important;
          display: block !important;
        }
        .ynki-mobile-nav-close {
          display: inline-flex !important;
          align-items: center !important;
          gap: 6px !important;
          background: #fff3e6 !important;
          border: 1px solid rgba(255, 128, 0, 0.4) !important;
          color: #ff8000 !important;
          font-size: 12.5px !important;
          font-weight: 700 !important;
          padding: 6px 14px !important;
          border-radius: 8px !important;
          cursor: pointer !important;
        }
        .ynki-mobile-nav-menu {
          list-style: none !important;
          margin: 0 !important;
          padding: 12px 0 !important;
          flex: 1 !important;
        }
        .ynki-mobile-nav-item {
          list-style: none !important;
          border-bottom: 1px solid #f2f6f3 !important;
          margin: 0 !important;
          padding: 0 !important;
        }
        .ynki-mobile-nav-row {
          display: flex !important;
          align-items: center !important;
          justify-content: space-between !important;
          padding: 0 16px !important;
        }
        .ynki-mobile-nav-link {
          display: block !important;
          flex: 1 !important;
          padding: 13px 4px !important;
          font-size: 14.5px !important;
          font-weight: 700 !important;
          color: #12291e !important;
          text-decoration: none !important;
        }
        .ynki-mobile-nav-toggle-btn {
          background: #eaf6ea !important;
          border: 1px solid #c8e6c7 !important;
          color: #117710 !important;
          width: 32px !important;
          height: 32px !important;
          border-radius: 8px !important;
          display: inline-flex !important;
          align-items: center !important;
          justify-content: center !important;
          font-size: 16px !important;
          font-weight: 800 !important;
          cursor: pointer !important;
        }
        .ynki-mobile-nav-toggle-btn.open {
          background: #ff8000 !important;
          border-color: #ff8000 !important;
          color: #ffffff !important;
        }
        .ynki-mobile-submenu {
          list-style: none !important;
          margin: 0 !important;
          padding: 0 0 8px 0 !important;
          background: #f8faf8 !important;
          display: none !important;
        }
        .ynki-mobile-submenu.open {
          display: block !important;
        }
        .ynki-mobile-submenu li {
          list-style: none !important;
          margin: 0 !important;
          padding: 0 !important;
        }
        .ynki-mobile-submenu li a {
          display: block !important;
          padding: 10px 24px 10px 32px !important;
          font-size: 13.5px !important;
          font-weight: 600 !important;
          color: #3b5045 !important;
          text-decoration: none !important;
        }
        .ynki-mobile-nav-footer {
          padding: 18px 20px 24px !important;
          border-top: 1px solid #eef4f0 !important;
          background: #fafcfa !important;
        }
        .btn-mobile-nav-donate {
          display: flex !important;
          align-items: center !important;
          justify-content: center !important;
          background: #B22231 !important;
          color: #ffffff !important;
          padding: 12px 18px !important;
          border-radius: 10px !important;
          font-size: 14px !important;
          font-weight: 700 !important;
          text-decoration: none !important;
        }
      }
    `;
    if (document.head) {
      document.head.appendChild(style);
    } else {
      document.addEventListener('DOMContentLoaded', function () {
        document.head.appendChild(style);
      });
    }
  }

  // 2. Desktop Dropdowns (Smooth Hover & Click)
  function initDesktopDropdowns() {
    var menuItems = document.querySelectorAll('.awb-menu__main-li, .menu-item-has-children');
    menuItems.forEach(function (item) {
      var sub = item.querySelector('.awb-menu__sub-ul, .sub-menu');
      if (!sub) return;

      item.addEventListener('mouseenter', function () {
        item.classList.add('is-open');
      });
      item.addEventListener('mouseleave', function () {
        item.classList.remove('is-open');
      });
      item.addEventListener('focusin', function () {
        item.classList.add('is-open');
      });
      item.addEventListener('focusout', function (e) {
        if (!item.contains(e.relatedTarget)) {
          item.classList.remove('is-open');
        }
      });
    });
  }

  // 3. Mobile Drawer Navigation
  function initMobileDrawer() {
    if (document.getElementById('ynki-mobile-drawer')) return;

    var currentPath = window.location.pathname;

    // Create Backdrop & Drawer
    var backdrop = document.createElement('div');
    backdrop.id = 'ynki-mobile-backdrop';
    backdrop.className = 'ynki-mobile-nav-backdrop';

    var drawer = document.createElement('div');
    drawer.id = 'ynki-mobile-drawer';
    drawer.className = 'ynki-mobile-nav-drawer';

    drawer.innerHTML = `
      <div class="ynki-mobile-nav-header">
        <div class="ynki-mobile-nav-logo">
          <a href="/"><img src="/wp-content/uploads/2026/05/logo-ynki-80.webp" alt="YNKI"></a>
        </div>
        <button type="button" class="ynki-mobile-nav-close" id="ynki-mobile-close-btn" aria-label="Tutup Menu">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
          Close
        </button>
      </div>

      <ul class="ynki-mobile-nav-menu">
        <!-- 1. Beranda -->
        <li class="ynki-mobile-nav-item">
          <div class="ynki-mobile-nav-row">
            <a href="/" class="ynki-mobile-nav-link ${currentPath === '/' ? 'active' : ''}">Beranda</a>
          </div>
        </li>

        <!-- 2. Tentang Kami -->
        <li class="ynki-mobile-nav-item">
          <div class="ynki-mobile-nav-row">
            <a href="/sejarah-visi-misi/" class="ynki-mobile-nav-link">Tentang Kami</a>
            <button type="button" class="ynki-mobile-nav-toggle-btn" aria-label="Buka Submenu Tentang Kami">+</button>
          </div>
          <ul class="ynki-mobile-submenu">
            <li><a href="/sejarah-visi-misi/">Sejarah, Visi &amp; Misi</a></li>
            <li><a href="/tim/">Tim &amp; Pengurus YNKI</a></li>
            <li><a href="/lgos/">LGOS: Sistem Operasi Organisasi</a></li>
            <li><a href="/portofolio/">Portfolio</a></li>
          </ul>
        </li>

        <!-- 3. Program Kami -->
        <li class="ynki-mobile-nav-item">
          <div class="ynki-mobile-nav-row">
            <a href="/landscape-governance/" class="ynki-mobile-nav-link">Program Kami</a>
            <button type="button" class="ynki-mobile-nav-toggle-btn" aria-label="Buka Submenu Program Kami">+</button>
          </div>
          <ul class="ynki-mobile-submenu">
            <li><a href="/landscape-governance/">Landscape Governance</a></li>
            <li><a href="/natural-capital/">Natural Capital &amp; Restoration</a></li>
            <li><a href="/sustainable-commodity/">Sustainable Commodity System</a></li>
            <li><a href="/landscape-intelligence/">Landscape Intelligence &amp; Innovation</a></li>
            <li><a href="/institutional-partnership/">Institutional Sustainability &amp; Partnership</a></li>
          </ul>
        </li>

        <!-- 4. Dampak & Pembelajaran -->
        <li class="ynki-mobile-nav-item">
          <div class="ynki-mobile-nav-row">
            <a href="/dampak/" class="ynki-mobile-nav-link">Dampak &amp; Pembelajaran</a>
            <button type="button" class="ynki-mobile-nav-toggle-btn" aria-label="Buka Submenu Dampak">+</button>
          </div>
          <ul class="ynki-mobile-submenu">
            <li><a href="/dampak/">Dampak</a></li>
            <li><a href="/kisah-perubahan/">Kisah Perubahan</a></li>
            <li><a href="/liputan-media/">Liputan Media</a></li>
          </ul>
        </li>

        <!-- 5. Pustaka & Pengetahuan -->
        <li class="ynki-mobile-nav-item">
          <div class="ynki-mobile-nav-row">
            <a href="/news-features/" class="ynki-mobile-nav-link">Pustaka &amp; Pengetahuan</a>
            <button type="button" class="ynki-mobile-nav-toggle-btn" aria-label="Buka Submenu Pustaka">+</button>
          </div>
          <ul class="ynki-mobile-submenu">
            <li><a href="/news-features/">News &amp; Features</a></li>
            <li><a href="/penelitian-laporan/">Penelitian &amp; Laporan</a></li>
            <li><a href="/analisis-kebijakan/">Analisis &amp; Kebijakan</a></li>
            <li><a href="/perspektif-budaya/">Perspektif Budaya</a></li>
            <li><a href="/data-spasial-dan-gis/">Data Spasial dan GIS</a></li>
            <li><a href="/story-foto-video/">Story Foto &amp; Video</a></li>
          </ul>
        </li>

        <!-- 6. Ikut Terlibat -->
        <li class="ynki-mobile-nav-item">
          <div class="ynki-mobile-nav-row">
            <a href="/ikut-terlibat" class="ynki-mobile-nav-link ${currentPath.indexOf('/ikut') !== -1 ? 'active' : ''}">Ikut Serta / Terlibat</a>
          </div>
        </li>

        <!-- 7. Kontak Kami -->
        <li class="ynki-mobile-nav-item">
          <div class="ynki-mobile-nav-row">
            <a href="/kontak-kami" class="ynki-mobile-nav-link ${currentPath.indexOf('/kontak') !== -1 ? 'active' : ''}">Kontak Kami</a>
          </div>
        </li>

        <!-- 8. Annual Report -->
        <li class="ynki-mobile-nav-item">
          <div class="ynki-mobile-nav-row">
            <a href="/annual-report/" class="ynki-mobile-nav-link">Annual Report</a>
          </div>
        </li>
      </ul>

      <div class="ynki-mobile-nav-footer">
        <a href="/donasi" class="btn-mobile-nav-donate">
          ❤️ Donasi &amp; Dukung Program
        </a>
      </div>
    `;

    document.body.appendChild(backdrop);
    document.body.appendChild(drawer);

    // Open / Close Handlers
    function openDrawer() {
      drawer.classList.add('active');
      backdrop.classList.add('active');
      document.body.style.overflow = 'hidden';
    }

    function closeDrawer() {
      drawer.classList.remove('active');
      backdrop.classList.remove('active');
      document.body.style.overflow = '';
    }

    document.getElementById('ynki-mobile-close-btn').addEventListener('click', closeDrawer);
    backdrop.addEventListener('click', closeDrawer);

    // Connect to burger buttons
    var burgerButtons = document.querySelectorAll('.awb-menu__m-toggle, [aria-label="Toggle Navigation"], .collapsed-nav-text, .awb-menu_mobile-toggle');
    burgerButtons.forEach(function (btn) {
      btn.addEventListener('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        openDrawer();
      });
    });

    // Accordion Toggle (+ / -)
    var toggleButtons = drawer.querySelectorAll('.ynki-mobile-nav-toggle-btn');
    toggleButtons.forEach(function (btn) {
      btn.addEventListener('click', function (e) {
        e.preventDefault();
        var submenu = this.closest('.ynki-mobile-nav-item').querySelector('.ynki-mobile-submenu');
        if (!submenu) return;

        var isOpen = submenu.classList.contains('open');
        if (isOpen) {
          submenu.classList.remove('open');
          this.classList.remove('open');
          this.textContent = '+';
        } else {
          submenu.classList.add('open');
          this.classList.add('open');
          this.textContent = '✕';
        }
      });
    });
  }

  function initAllNavigation() {
    injectNavStyles();
    initDesktopDropdowns();
    initMobileDrawer();
  }

  injectNavStyles();

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initAllNavigation);
  } else {
    initAllNavigation();
  }
})();