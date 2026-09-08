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
      match: ['/sejarah-visi-misi/', '/tim/', '/lgos/', '/portofolio/', '/transparansi/', '/kontak-kami/', '/annual-report/'],
      links: [
        ['/sejarah-visi-misi/', 'Sejarah, Visi & Misi'],
        ['/tim/', 'Tim & Pengurus YNKI'],
        ['/lgos/', 'LGOS: Sistem Operasi Organisasi'],
        ['/portofolio/', 'Portfolio'],
        ['/kontak-kami/', 'Kontak Kami'],
        ['/ikut-serta/', 'Ikut Serta'],
        ['/annual-report/', 'Annual Report']
      ]
    },
    {
      match: ['/program/', '/landscape-governance/', '/natural-capital/', '/sustainable-commodity/', '/landscape-intelligence/', '/institutional-partnership/'],
      links: [
        ['/landscape-governance/', 'Landscape Governance'],
        ['/natural-capital/', 'Natural Capital & Restoration'],
        ['/sustainable-commodity/', 'Sustainable Commodity System'],
        ['/landscape-intelligence/', 'Pengetahuan Lanskap & Inovasi'],
        ['/institutional-partnership/', 'Institutional Partnership'],
        ['/kontak-kami/', 'Kontak Kami'],
        ['/ikut-serta/', 'Ikut Serta'],
        ['/annual-report/', 'Annual Report']
      ]
    },
    {
      match: ['/dampak/', '/kisah-perubahan/', '/liputan-media/'],
      links: [
        ['/dampak/', 'Dampak'],
        ['/kisah-perubahan/', 'Kisah Perubahan'],
        ['/liputan-media/', 'Liputan Media'],
        ['/kontak-kami/', 'Kontak Kami'],
        ['/ikut-serta/', 'Ikut Serta'],
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
        ['/story-foto-video/', 'Story Foto Video'],
        ['/kontak-kami/', 'Kontak Kami'],
        ['/ikut-serta/', 'Ikut Serta'],
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
    ['/kontak-kami/', 'Kontak Kami'],
    ['/ikut-serta/', 'Ikut Serta'],
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
  // 1. Inject Styles Directly to Document Head
  function injectNavStyles() {
    var existing = document.getElementById('ynki-navigation-core-styles');
    if (existing) existing.remove();

    var style = document.createElement('style');
    style.id = 'ynki-navigation-core-styles';
    style.textContent = `
      /* =======================================================
         DESKTOP VIEW (>= 993px)
         ======================================================= */
      @media (min-width: 993px) {
        /* HIDE ONLY BURGER BUTTON & MOBILE DRAWER ON DESKTOP */
        button.awb-menu__m-toggle,
        .awb-menu__m-toggle,
        .awb-menu__m-toggle-inner,
        button[class*="awb-menu__m-toggle"],
        .fusion-mobile-menu-icons,
        .fusion-mobile-nav-holder,
        .collapsed-nav-text,
        .awb-menu__open-nav-submenu_mobile,
        .ynki-mobile-nav-drawer,
        .ynki-mobile-nav-backdrop,
        #ynki-mobile-drawer,
        #ynki-mobile-backdrop {
          display: none !important;
          visibility: hidden !important;
          opacity: 0 !important;
          width: 0 !important;
          height: 0 !important;
          pointer-events: none !important;
          position: absolute !important;
          left: -9999px !important;
        }

        /* GUARANTEE HEADER STACKS ON TOP OF HERO & ALL PAGE SECTIONS */
        #boxed-wrapper,
        #wrapper,
        .fusion-wrapper {
          overflow: visible !important;
          position: relative !important;
        }

        header,
        #fusion-header,
        .fusion-header,
        .fusion-tb-header,
        .fusion-header-wrapper,
        .fusion-fullwidth.fusion-builder-row-1 {
          position: relative !important;
          z-index: 999999999 !important;
          overflow: visible !important;
          transform: none !important;
          filter: none !important;
        }

        .fusion-builder-row,
        .fusion-builder-row-1,
        .fusion-layout-column,
        .fusion-column-wrapper,
        nav.awb-menu,
        .awb-menu,
        .awb-menu__main-ul,
        .awb-menu__main-li {
          overflow: visible !important;
        }

        /* Hero & content sections placed below header */
        #sliders-container,
        .fusion-slider-visibility,
        main,
        #main,
        #hero-home,
        .fusion-fullwidth:not(.fusion-builder-row-1) {
          position: relative !important;
          z-index: 1 !important;
        }

        /* Header Single Row Alignment */
        .fusion-builder-row-1 {
          display: flex !important;
          align-items: center !important;
          justify-content: space-between !important;
          flex-wrap: nowrap !important;
        }

        .fusion-builder-column-0 {
          flex: 0 0 auto !important;
          width: auto !important;
          max-width: 420px !important;
        }

        .fusion-builder-column-0 img,
        .fusion-tb-header .fusion-imageframe img,
        .fusion-tb-header img.wp-image-183 {
          max-width: 360px !important;
          width: auto !important;
          height: 64px !important;
          max-height: 72px !important;
          object-fit: contain !important;
        }

        .fusion-builder-column-1 {
          flex: 1 1 auto !important;
          width: auto !important;
          max-width: none !important;
        }

        /* Desktop Nav Container */
        nav.awb-menu,
        .awb-menu {
          display: flex !important;
          align-items: center !important;
          justify-content: flex-end !important;
          visibility: visible !important;
          opacity: 1 !important;
          width: 100% !important;
          background: transparent !important;
          border: none !important;
          box-shadow: none !important;
        }

        /* Desktop Main Navigation Bar */
        .awb-menu__main-ul {
          display: flex !important;
          flex-wrap: nowrap !important;
          white-space: nowrap !important;
          align-items: center !important;
          justify-content: flex-end !important;
          list-style: none !important;
          margin: 0 !important;
          padding: 0 !important;
          gap: 14px !important;
          visibility: visible !important;
          opacity: 1 !important;
        }

        .awb-menu__main-li {
          position: relative !important;
          list-style: none !important;
          margin: 0 !important;
          padding: 0 !important;
          display: inline-block !important;
          overflow: visible !important;
        }

        .awb-menu__main-a {
          display: inline-flex !important;
          align-items: center !important;
          padding: 10px 12px !important;
          font-size: 13.5px !important;
          font-weight: 700 !important;
          color: #12291e !important;
          text-decoration: none !important;
          transition: color 0.15s ease !important;
          cursor: pointer !important;
        }

        .awb-menu__main-li:hover > .awb-menu__main-a,
        .awb-menu__main-li.current-menu-item > .awb-menu__main-a,
        .awb-menu__main-li.current-menu-ancestor > .awb-menu__main-a {
          color: #117710 !important;
        }

        /* Invisible hover bridge to prevent losing hover when moving mouse down */
        .awb-menu__main-li::after {
          content: '' !important;
          position: absolute !important;
          top: 100% !important;
          left: 0 !important;
          width: 100% !important;
          height: 12px !important;
          display: block !important;
          z-index: 999999998 !important;
          pointer-events: auto !important;
        }

        /* RESET AVADA HIDDEN CLIPPING ON SUBMENU */
        .awb-menu__sub-ul {
          clip: auto !important;
          clip-path: none !important;
          height: auto !important;
          max-height: none !important;
          transform: none !important;
          transition: none !important;
          overflow: visible !important;
        }

        /* DEFAULT HIDDEN STATE WHEN NOT HOVERED */
        .awb-menu__main-li:not(:hover):not(.is-open):not(:focus-within) > .awb-menu__sub-ul {
          display: none !important;
          opacity: 0 !important;
          visibility: hidden !important;
          pointer-events: none !important;
        }

        /* DROPDOWN SUBMENU BOX - MATCHING IMAGE 1 DESIGN */
        .awb-menu__main-li .awb-menu__sub-ul {
          display: none !important;
          position: absolute !important;
          top: 100% !important;
          left: 0 !important;
          min-width: 260px !important;
          width: max-content !important;
          background: #ffffff !important;
          border: 1px solid #d2e8d1 !important;
          border-top: 3px solid #117710 !important;
          border-radius: 0 0 6px 6px !important;
          box-shadow: 0 12px 30px rgba(0, 0, 0, 0.16) !important;
          padding: 0 !important;
          margin: 0 !important;
          list-style: none !important;
          z-index: 999999999 !important;
          clip: auto !important;
          clip-path: none !important;
          overflow: visible !important;
        }

        /* Instant Dropdown Trigger on Hover (Works at scrollY = 0 without needing to scroll) */
        .awb-menu__main-li:hover > .awb-menu__sub-ul,
        .awb-menu__main-li:focus-within > .awb-menu__sub-ul,
        .awb-menu__main-li.is-open > .awb-menu__sub-ul {
          display: block !important;
          opacity: 1 !important;
          visibility: visible !important;
          pointer-events: auto !important;
          clip: auto !important;
          clip-path: none !important;
        }

        .awb-menu__sub-li {
          list-style: none !important;
          padding: 0 !important;
          margin: 0 !important;
          display: block !important;
          width: 100% !important;
          border-bottom: 1px solid #edf2ed !important;
          overflow: visible !important;
          clip: auto !important;
          clip-path: none !important;
        }

        .awb-menu__sub-li:last-child {
          border-bottom: none !important;
        }

        .awb-menu__sub-a {
          display: block !important;
          padding: 12px 18px !important;
          font-size: 13.5px !important;
          font-weight: 500 !important;
          color: #1e2d24 !important;
          text-decoration: none !important;
          transition: all 0.15s ease !important;
          border-left: 3px solid transparent !important;
          background: #ffffff !important;
          line-height: 1.4 !important;
          cursor: pointer !important;
        }

        .awb-menu__sub-a:hover,
        .awb-menu__sub-li.current-menu-item > .awb-menu__sub-a {
          background: #f0f8f0 !important;
          color: #117710 !important;
          border-left: 3px solid #117710 !important;
          padding-left: 22px !important;
        }
      }

      /* =======================================================
         MOBILE VIEW (<= 992px)
         ======================================================= */
      @media (max-width: 992px) {
        /* Clean nav wrapper */
        nav.awb-menu,
        .awb-menu {
          display: flex !important;
          align-items: center !important;
          justify-content: flex-end !important;
          background: transparent !important;
          border: none !important;
          box-shadow: none !important;
          padding: 0 !important;
          margin: 0 !important;
        }

        /* Show Burger Toggle Button */
        button.awb-menu__m-toggle,
        .awb-menu__m-toggle {
          display: inline-flex !important;
          align-items: center !important;
          justify-content: center !important;
          width: 44px !important;
          height: 44px !important;
          background: #f0fdf4 !important;
          border: 1.5px solid #117710 !important;
          border-radius: 10px !important;
          padding: 0 !important;
          margin: 0 !important;
          cursor: pointer !important;
          box-shadow: 0 2px 8px rgba(17, 119, 16, 0.15) !important;
        }

        .awb-menu__m-toggle .awb-menu__m-collapse-icon {
          display: flex !important;
          align-items: center !important;
          justify-content: center !important;
        }

        .awb-menu__m-toggle .classic-bars-solid,
        .awb-menu__m-toggle .fa-bars {
          color: #117710 !important;
          font-size: 20px !important;
        }

        /* Hide Desktop Main Menu List */
        .awb-menu__main-ul {
          display: none !important;
        }

        /* Hide burger toggle button when mobile drawer is open so it NEVER bleeds through or overlaps */
        body.ynki-mobile-nav-open button.awb-menu__m-toggle,
        body.ynki-mobile-nav-open .awb-menu__m-toggle,
        body.ynki-mobile-nav-open .fusion-mobile-menu-icons {
          display: none !important;
          visibility: hidden !important;
          opacity: 0 !important;
          pointer-events: none !important;
        }

        /* Backdrop & Sliding Drawer (z-index highest to prevent bleed-through) */
        .ynki-mobile-nav-backdrop {
          position: fixed !important;
          top: 0 !important;
          left: 0 !important;
          width: 100vw !important;
          height: 100vh !important;
          height: 100dvh !important;
          background: rgba(14, 36, 27, 0.6) !important;
          backdrop-filter: blur(4px) !important;
          -webkit-backdrop-filter: blur(4px) !important;
          z-index: 2147483640 !important;
          display: none !important;
          pointer-events: none !important;
          opacity: 0 !important;
          transition: opacity 0.3s ease !important;
        }
        .ynki-mobile-nav-backdrop.active {
          display: block !important;
          opacity: 1 !important;
          pointer-events: auto !important;
        }

        .ynki-mobile-nav-drawer {
          position: fixed !important;
          top: 0 !important;
          right: -100% !important;
          width: 330px !important;
          max-width: 86vw !important;
          height: 100vh !important;
          height: 100dvh !important;
          background: #ffffff !important;
          box-shadow: -8px 0 35px rgba(0, 0, 0, 0.28) !important;
          z-index: 2147483647 !important;
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
          padding: 16px 18px !important;
          border-bottom: 1.5px solid #edf4ee !important;
          background: #ffffff !important;
          position: sticky !important;
          top: 0 !important;
          z-index: 10 !important;
        }
        .ynki-mobile-nav-logo img {
          height: 36px !important;
          width: auto !important;
          display: block !important;
        }
        .ynki-mobile-nav-close {
          display: inline-flex !important;
          align-items: center !important;
          gap: 6px !important;
          background: #fff3e6 !important;
          border: 1.5px solid #ff8000 !important;
          color: #ff8000 !important;
          font-size: 13px !important;
          font-weight: 700 !important;
          padding: 6px 12px !important;
          border-radius: 8px !important;
          cursor: pointer !important;
          transition: all 0.2s ease !important;
        }
        .ynki-mobile-nav-close:hover,
        .ynki-mobile-nav-close:active {
          background: #ffe6cc !important;
        }
        .ynki-mobile-nav-menu {
          list-style: none !important;
          margin: 0 !important;
          padding: 8px 0 !important;
          flex: 1 1 auto !important;
          overflow-y: auto !important;
        }
        .ynki-mobile-nav-item {
          list-style: none !important;
          border-bottom: 1px solid #f0f4f1 !important;
          margin: 0 !important;
          padding: 0 !important;
        }
        .ynki-mobile-nav-row {
          display: flex !important;
          align-items: center !important;
          justify-content: space-between !important;
          padding: 4px 16px !important;
        }
        .ynki-mobile-nav-link {
          display: block !important;
          flex: 1 !important;
          padding: 12px 4px !important;
          font-size: 15px !important;
          font-weight: 700 !important;
          color: #12291e !important;
          text-decoration: none !important;
          transition: color 0.15s ease !important;
        }
        .ynki-mobile-nav-link.active,
        .ynki-mobile-nav-link:hover {
          color: #117710 !important;
        }
        .ynki-mobile-nav-toggle-btn {
          background: #eaf6ea !important;
          border: 1px solid #cce8cd !important;
          color: #117710 !important;
          width: 34px !important;
          height: 34px !important;
          border-radius: 8px !important;
          display: inline-flex !important;
          align-items: center !important;
          justify-content: center !important;
          font-size: 18px !important;
          font-weight: 700 !important;
          cursor: pointer !important;
          transition: all 0.2s ease !important;
        }
        .ynki-mobile-nav-toggle-btn.open {
          background: #ff8000 !important;
          border-color: #ff8000 !important;
          color: #ffffff !important;
        }
        .ynki-mobile-submenu {
          list-style: none !important;
          margin: 0 !important;
          padding: 4px 0 8px 0 !important;
          background: #f7faf8 !important;
          border-left: 3px solid #117710 !important;
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
          padding: 10px 18px 10px 24px !important;
          font-size: 13.5px !important;
          font-weight: 600 !important;
          color: #284134 !important;
          text-decoration: none !important;
          transition: all 0.15s ease !important;
        }
        .ynki-mobile-submenu li a:hover,
        .ynki-mobile-submenu li a:active {
          background: #edf7ee !important;
          color: #117710 !important;
          padding-left: 28px !important;
        }
        .ynki-mobile-nav-footer {
          padding: 16px 18px 24px !important;
          border-top: 1.5px solid #edf4ee !important;
          background: #fbfdfb !important;
        }
        .btn-mobile-nav-donate {
          display: flex !important;
          align-items: center !important;
          justify-content: center !important;
          gap: 8px !important;
          background: #B22231 !important;
          color: #ffffff !important;
          padding: 13px 18px !important;
          border-radius: 10px !important;
          font-size: 14px !important;
          font-weight: 700 !important;
          text-decoration: none !important;
          box-shadow: 0 4px 14px rgba(178, 34, 49, 0.28) !important;
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

  // 2. Desktop Dropdown Handlers (Clean, non-destructive class toggle & Instant Navigation)
  function initDesktopDropdowns() {
    // Unblock any Avada loading or animation locks
    var headers = document.querySelectorAll('nav.awb-menu, .fusion-tb-header, .fusion-fullwidth.fusion-builder-row-1');
    headers.forEach(function (el) {
      el.classList.remove('loading');
      el.classList.remove('mega-menu-loading');
      el.classList.remove('fusion-animated');
      el.removeAttribute('data-animationtype');
      el.style.setProperty('opacity', '1', 'important');
      el.style.setProperty('visibility', 'visible', 'important');
      el.style.setProperty('pointer-events', 'auto', 'important');
    });

    var allSubmenus = document.querySelectorAll('.awb-menu__sub-ul, .sub-menu');
    allSubmenus.forEach(function (sub) {
      sub.style.setProperty('clip', 'auto', 'important');
      sub.style.setProperty('clip-path', 'none', 'important');
      sub.style.setProperty('transform', 'none', 'important');
      sub.style.setProperty('overflow', 'visible', 'important');
    });

    // Strict runtime protection: ensure burger toggle is completely hidden on desktop
    function enforceDesktopNavState() {
      var isDesktop = window.innerWidth > 992;
      var burgerBtns = document.querySelectorAll('button.awb-menu__m-toggle, .awb-menu__m-toggle, .fusion-mobile-menu-icons, .fusion-mobile-nav-holder');
      burgerBtns.forEach(function (btn) {
        if (isDesktop) {
          btn.style.setProperty('display', 'none', 'important');
          btn.style.setProperty('visibility', 'hidden', 'important');
        } else {
          btn.style.removeProperty('display');
          btn.style.removeProperty('visibility');
        }
      });
    }

    enforceDesktopNavState();
    window.addEventListener('resize', enforceDesktopNavState);

    // Instant hover dropdown trigger (Works at scrollY = 0 without needing to scroll)
    var menuItems = document.querySelectorAll('.awb-menu__main-li, .menu-item-has-children');
    menuItems.forEach(function (item) {
      var sub = item.querySelector('.awb-menu__sub-ul, .sub-menu');
      if (!sub) return;

      function openMenu() {
        item.classList.add('is-open');
        sub.style.setProperty('display', 'block', 'important');
        sub.style.setProperty('opacity', '1', 'important');
        sub.style.setProperty('visibility', 'visible', 'important');
        sub.style.setProperty('pointer-events', 'auto', 'important');
        sub.style.setProperty('clip', 'auto', 'important');
        sub.style.setProperty('clip-path', 'none', 'important');
        sub.style.setProperty('transform', 'none', 'important');
      }

      function closeMenu() {
        item.classList.remove('is-open');
        sub.style.removeProperty('display');
        sub.style.removeProperty('opacity');
        sub.style.removeProperty('visibility');
        sub.style.removeProperty('pointer-events');
      }

      item.addEventListener('mouseenter', openMenu);
      item.addEventListener('mouseover', openMenu);
      item.addEventListener('mouseleave', closeMenu);
      item.addEventListener('focusin', openMenu);
      item.addEventListener('focusout', function (e) {
        if (!item.contains(e.relatedTarget)) {
          closeMenu();
        }
      });
    });

    // Instant Direct Click Navigation (Runs in CAPTURE phase to bypass all Avada preventDefault blocks)
    document.addEventListener('click', function (e) {
      var link = e.target.closest('.awb-menu__sub-a, .awb-menu__main-a, nav.awb-menu a');
      if (!link) return;
      var href = link.getAttribute('href');
      if (href && href !== '#' && !href.startsWith('javascript:')) {
        e.stopPropagation();
        window.location.href = href;
      }
    }, true);
  }

  // 3. Mobile Drawer Navigation
  function initMobileDrawer() {
    if (document.getElementById('ynki-mobile-drawer')) return;

    var currentPath = window.location.pathname;

    // Create Backdrop & Drawer
    var backdrop = document.createElement('div');
    backdrop.id = 'ynki-mobile-backdrop';
    backdrop.className = 'ynki-mobile-nav-backdrop';
    backdrop.setAttribute('onclick', "(function(){var d=document.getElementById('ynki-mobile-drawer');var b=document.getElementById('ynki-mobile-backdrop');if(d)d.classList.remove('active');if(b)b.classList.remove('active');document.body.classList.remove('ynki-mobile-nav-open');document.body.style.overflow='';})()");

    var drawer = document.createElement('div');
    drawer.id = 'ynki-mobile-drawer';
    drawer.className = 'ynki-mobile-nav-drawer';

    drawer.innerHTML = `
      <div class="ynki-mobile-nav-header">
        <div class="ynki-mobile-nav-logo">
          <a href="/"><img src="/wp-content/uploads/2026/05/logo-ynki-500.webp" alt="YNKI" style="height:32px; max-height:32px; width:auto; max-width:170px; object-fit:contain;"></a>
        </div>
        <button type="button" class="ynki-mobile-nav-close" id="ynki-mobile-close-btn" aria-label="Tutup Menu" onclick="(function(){var d=document.getElementById('ynki-mobile-drawer');var b=document.getElementById('ynki-mobile-backdrop');if(d)d.classList.remove('active');if(b)b.classList.remove('active');document.body.classList.remove('ynki-mobile-nav-open');document.body.style.overflow='';})()">
          <span style="font-size:15px; font-weight:800; line-height:1; pointer-events:none;">✕</span>
          <span style="pointer-events:none;">Tutup</span>
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
      document.body.classList.add('ynki-mobile-nav-open');
      document.body.style.overflow = 'hidden';
    }

    function closeDrawer() {
      drawer.classList.remove('active');
      backdrop.classList.remove('active');
      document.body.classList.remove('ynki-mobile-nav-open');
      document.body.style.overflow = '';
    }

    var closeBtn = document.getElementById('ynki-mobile-close-btn');
    if (closeBtn) {
      closeBtn.addEventListener('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        closeDrawer();
      });
    }
    backdrop.addEventListener('click', function (e) {
      e.preventDefault();
      e.stopPropagation();
      closeDrawer();
    });

    document.addEventListener('click', function (e) {
      if (e.target.closest('#ynki-mobile-close-btn') || e.target.closest('.ynki-mobile-nav-close')) {
        e.preventDefault();
        e.stopPropagation();
        closeDrawer();
      }
    }, true);

    // Connect to burger buttons (explicit button selector)
    var burgerButtons = document.querySelectorAll('button.awb-menu__m-toggle, .awb-menu__m-toggle, .fusion-mobile-menu-icons');
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

  function initAlternatingBadges() {
    var mainContainer = document.querySelector('#content, .post-content, #main, main') || document.body;
    var sections = mainContainer.querySelectorAll('section, .ynki-section');
    
    var badgeContainers = [];

    sections.forEach(function (sec) {
      if (sec.id && (sec.id.indexOf('hero') !== -1 || sec.classList.contains('hero-section'))) return;
      
      var items = sec.querySelectorAll('.section-divider, .divider-line, .section-badge, .section-badge-orange, .section-badge-green');
      items.forEach(function (item) {
        if (item.closest('.positioning-cta-box, .proyek-tags, .pilar-card, .masalah-card, .hasil-card, .int-card, .pen-meta, .doc-header, .pptx-card-body, .gerakan-items, .tujuan-quote, .card-highlight, .timeline-item')) return;
        
        // If it's a badge inside an already-targeted divider/line, skip to avoid double processing
        if (item.classList.contains('section-badge') && item.closest('.section-divider, .divider-line')) return;

        badgeContainers.push(item);
      });
    });

    var badgeIndex = 0;
    badgeContainers.forEach(function (el) {
      var isLeft = (badgeIndex % 2 === 0);
      badgeIndex++;

      if (el.classList.contains('section-divider') || el.classList.contains('divider-line')) {
        var badge = el.querySelector('.section-badge, .section-badge-orange, .section-badge-green, [class*="badge"]');
        var lines = el.querySelectorAll('.line');
        
        el.style.display = 'flex';
        el.style.alignItems = 'center';
        el.style.gap = '16px';
        el.style.marginBottom = el.style.marginBottom || '14px';

        var isWhite = badge && (badge.classList.contains('white') || badge.style.color === '#ffffff' || badge.style.color === 'rgb(255, 255, 255)');
        var lineColor = isWhite ? 'rgba(255,255,255,0.25)' : '#d2e8d1';

        if (lines.length >= 2) {
          if (isLeft) {
            el.style.justifyContent = 'flex-start';
            lines[0].style.display = 'none';
            lines[1].style.display = 'block';
            lines[1].style.flex = '1';
            lines[1].style.background = lineColor;
          } else {
            el.style.justifyContent = 'flex-end';
            lines[0].style.display = 'block';
            lines[0].style.flex = '1';
            lines[0].style.background = lineColor;
            lines[1].style.display = 'none';
          }
        } else if (lines.length === 1) {
          lines[0].style.flex = '1';
          lines[0].style.background = lineColor;
          if (isLeft) {
            el.style.justifyContent = 'flex-start';
            if (badge && el.firstChild !== badge) {
              el.insertBefore(badge, lines[0]);
            }
          } else {
            el.style.justifyContent = 'flex-end';
            if (badge && el.lastChild !== badge) {
              el.appendChild(badge);
            }
          }
        } else if (badge) {
          var newLine = document.createElement('span');
          newLine.className = 'line';
          newLine.style.flex = '1';
          newLine.style.height = '1px';
          newLine.style.background = lineColor;
          if (isLeft) {
            el.style.justifyContent = 'flex-start';
            el.appendChild(newLine);
          } else {
            el.style.justifyContent = 'flex-end';
            el.insertBefore(newLine, badge);
          }
        }
      } else {
        var parent = el.parentElement;
        if (!parent || parent.classList.contains('divider-line') || parent.classList.contains('section-divider')) return;

        var isWhite = el.classList.contains('white') || el.style.color === '#ffffff' || el.style.color === 'rgb(255, 255, 255)';
        var lineColor = isWhite ? 'rgba(255,255,255,0.25)' : '#d2e8d1';

        var wrapper = document.createElement('div');
        wrapper.className = 'divider-line';
        wrapper.style.display = 'flex';
        wrapper.style.alignItems = 'center';
        wrapper.style.gap = '16px';
        wrapper.style.marginBottom = '14px';
        wrapper.style.width = '100%';

        var line = document.createElement('span');
        line.className = 'line';
        line.style.flex = '1';
        line.style.height = '1px';
        line.style.background = lineColor;

        el.parentNode.insertBefore(wrapper, el);

        if (isLeft) {
          wrapper.style.justifyContent = 'flex-start';
          wrapper.appendChild(el);
          wrapper.appendChild(line);
        } else {
          wrapper.style.justifyContent = 'flex-end';
          wrapper.appendChild(line);
          wrapper.appendChild(el);
        }
        el.style.marginBottom = '0';
      }
    });
  }

  function initAllNavigation() {
    injectNavStyles();
    initDesktopDropdowns();
    initMobileDrawer();
    initAlternatingBadges();
  }

  injectNavStyles();

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initAllNavigation);
  } else {
    initAllNavigation();
  }
})();