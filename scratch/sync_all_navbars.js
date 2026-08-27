const fs = require('fs');
const path = require('path');

const cleanHeaderStyle = `<style id="ynki-universal-clean-header">
      /* ===== YNKI GLOBAL NAVBAR CLEANUP & DESKTOP DROPDOWN ENGINE ===== */
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
      .fusion-fullwidth.fusion-builder-row-1,
      .fusion-builder-row-1 {
        position: relative !important;
        z-index: 999999999 !important;
        overflow: visible !important;
        transform: none !important;
        filter: none !important;
        background: transparent !important;
        background-color: transparent !important;
        border-bottom: none !important;
        box-shadow: none !important;
      }

      .fusion-builder-row,
      .fusion-layout-column,
      .fusion-column-wrapper,
      nav.awb-menu,
      .awb-menu,
      .awb-menu__main-ul,
      .awb-menu__main-li {
        overflow: visible !important;
        pointer-events: auto !important;
      }

      /* Hero & content sections placed below header */
      #sliders-container,
      .fusion-slider-visibility,
      main,
      #main,
      #hero-home,
      .fusion-page-title-bar,
      .fusion-fullwidth:not(.fusion-builder-row-1) {
        position: relative !important;
        z-index: 1 !important;
      }

      /* Desktop: Hide ONLY Burger Buttons & Mobile Drawer */
      @media (min-width: 993px) {
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

        /* Header single row layout */
        .fusion-builder-row-1 {
          display: flex !important;
          align-items: center !important;
          justify-content: space-between !important;
          flex-wrap: nowrap !important;
        }

        .fusion-builder-column-0 {
          flex: 0 0 auto !important;
          width: auto !important;
          max-width: 280px !important;
        }

        .fusion-builder-column-1 {
          flex: 1 1 auto !important;
          width: auto !important;
          max-width: none !important;
        }

        /* Show Nav Container */
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

        .awb-menu__main-li > a,
        .awb-menu__main-a {
          color: #12291e !important;
          font-weight: 700 !important;
          font-size: 13.5px !important;
          text-decoration: none !important;
          padding: 12px 14px !important;
          display: inline-flex !important;
          align-items: center !important;
          transition: color 0.15s ease !important;
          cursor: pointer !important;
        }

        .awb-menu__main-li:hover > a,
        .awb-menu__main-li.current-menu-item > a,
        .awb-menu__main-li.current-menu-ancestor > a {
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

        /* DROPDOWN SUBMENU BOX - MATCHING IMAGE 1 DESIGN (INSTANT AT SCROLL 0) */
        .awb-menu__main-li:hover > .awb-menu__sub-ul,
        .awb-menu__main-li.is-open > .awb-menu__sub-ul,
        .awb-menu__main-li:focus-within > .awb-menu__sub-ul {
          display: block !important;
          opacity: 1 !important;
          visibility: visible !important;
          pointer-events: auto !important;
          position: absolute !important;
          top: 100% !important;
          left: 0 !important;
          min-width: 260px !important;
          width: max-content !important;
          height: auto !important;
          clip: auto !important;
          clip-path: none !important;
          transform: none !important;
          overflow: visible !important;
          background: #ffffff !important;
          border: 1px solid #d2e8d1 !important;
          border-top: 3px solid #117710 !important;
          border-radius: 0 0 6px 6px !important;
          box-shadow: 0 12px 30px rgba(0, 0, 0, 0.16) !important;
          padding: 0 !important;
          margin: 0 !important;
          list-style: none !important;
          z-index: 999999999 !important;
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
          transform: none !important;
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
          box-sizing: border-box !important;
        }

        .awb-menu__sub-a:hover,
        .awb-menu__sub-li.current-menu-item > .awb-menu__sub-a {
          background: #f0f8f0 !important;
          color: #117710 !important;
          border-left: 3px solid #117710 !important;
          padding-left: 22px !important;
        }
      }

      /* Mobile Navbar */
      @media (max-width: 992px) {
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

        .awb-menu__m-toggle .classic-bars-solid,
        .awb-menu__m-toggle .fa-bars {
          color: #117710 !important;
          font-size: 20px !important;
        }

        .awb-menu__main-ul {
          display: none !important;
        }
      }
    </style>`;

// Find all HTML files recursively
const targetDirs = [
  '.', // include root folder for index.html!
  'sejarah-visi-misi', 'tim', 'lgos', 'portofolio',
  'landscape-governance', 'natural-capital', 'sustainable-commodity', 'landscape-intelligence', 'institutional-partnership',
  'dampak', 'kisah-perubahan', 'liputan-media',
  'news-features', 'penelitian-laporan', 'analisis-kebijakan', 'perspektif-budaya',
  'data-spasial-gis', 'data-spasial-dan-gis', 'stori-foto-video', 'story-foto-video',
  'kontak-kami', 'ikut-terlibat', 'ikut-serta', 'transparansi',
  'kategori', 'tag', 'author', 'page', 'video'
];

let updatedCount = 0;

function processHtmlFile(filePath) {
  if (!fs.existsSync(filePath)) return;
  let content = fs.readFileSync(filePath, 'utf8');

  let modified = false;

  // 1. Replace or insert ynki-universal-clean-header
  if (content.includes('id="ynki-universal-clean-header"')) {
    content = content.replace(/<style id="ynki-universal-clean-header">[\s\S]*?<\/style>/, cleanHeaderStyle);
    modified = true;
  } else if (content.includes('</head>')) {
    content = content.replace('</head>', `${cleanHeaderStyle}\n</head>`);
    modified = true;
  }

  // 2. Ensure ynki-responsive-system.css is included in head
  if (!content.includes('ynki-responsive-system.css') && content.includes('</head>')) {
    content = content.replace('</head>', `<link rel="stylesheet" href="/assets/css/ynki-responsive-system.css" />\n</head>`);
    modified = true;
  }

  // 3. Ensure ynki-footer.js is included before </body>
  if (!content.includes('/assets/js/ynki-footer.js') && content.includes('</body>')) {
    content = content.replace('</body>', `<script src="/assets/js/ynki-footer.js"></script>\n</body>`);
    modified = true;
  }

  if (modified) {
    fs.writeFileSync(filePath, content, 'utf8');
    updatedCount++;
    console.log('Updated:', filePath);
  }
}

function traverseDirectory(dir) {
  if (!fs.existsSync(dir)) return;
  const items = fs.readdirSync(dir);
  for (const item of items) {
    const fullPath = path.join(dir, item);
    const stat = fs.statSync(fullPath);
    if (stat.isDirectory()) {
      if (item !== 'node_modules' && item !== '.git' && item !== 'vendor' && item !== 'storage' && item !== 'resources') {
        traverseDirectory(fullPath);
      }
    } else if (item === 'index.html' || (item.endsWith('.html') && dir !== '.')) {
      processHtmlFile(fullPath);
    }
  }
}

// Specifically process root index.html
processHtmlFile(path.join(process.cwd(), 'index.html'));

targetDirs.forEach(d => {
  if (d === '.') return;
  const p = path.join(process.cwd(), d);
  traverseDirectory(p);
});

console.log(`Total files updated: ${updatedCount}`);
