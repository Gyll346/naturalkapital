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
      match: ['/sejarah-visi-misi/', '/tim/', '/lgos/', '/portofolio/', '/transparansi/'],
      links: [
        ['/sejarah-visi-misi/', 'Sejarah, Visi & Misi'],
        ['/tim/', 'Tim & Pengurus YNKI'],
        ['/lgos/', 'LGOS: Sistem Operasi Organisasi'],
        ['/portofolio/', 'Portfolio'],
        ['/transparansi/', 'Transparansi & Laporan Mitra']
      ]
    },
    {
      match: ['/program/', '/landscape-governance/', '/natural-capital/', '/sustainable-commodity/', '/landscape-intelligence/', '/institutional-partnership/'],
      links: [
        ['/landscape-governance/', 'Landscape Governance'],
        ['/natural-capital/', 'Natural Capital & Restoration'],
        ['/sustainable-commodity/', 'Sustainable Commodity System'],
        ['/landscape-intelligence/', 'Landscape Intelligence & Innovation'],
        ['/institutional-partnership/', 'Institutional Sustainability & Partnership']
      ]
    },
    {
      match: ['/dampak/', '/kisah-perubahan/', '/liputan-media/'],
      links: [
        ['/dampak/', 'Dampak'],
        ['/kisah-perubahan/', 'Kisah Perubahan'],
        ['/liputan-media/', 'Liputan Media']
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
        ['/stori-foto-video/', 'Story Foto & Video']
      ]
    }
  ];

  // Default untuk Beranda dan halaman umum
  var berandaLinks = [
    ['/sejarah-visi-misi/', 'Sejarah, Visi & Misi'],
    ['/landscape-governance/', 'Landscape Governance'],
    ['/dampak/', 'Dampak'],
    ['/news-features/', 'News & Features']
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