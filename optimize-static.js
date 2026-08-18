const fs = require('fs');
const path = require('path');

const patchStyle = `
<!-- Global Avada Stylesheet Fallback -->
<link rel="stylesheet" href="/wp-content/uploads/fusion-styles/47cb6edf07d5b05fc05b3981869760b8.min.css">

<style id="avada-static-display-fix">
  /* Animation and visibility fixes */
  .fusion-animated { opacity: 1 !important; visibility: visible !important; }
  .awb-menu { opacity: 1 !important; visibility: visible !important; }
  .mega-menu-loading { opacity: 1 !important; }
  .fusion-slider-visibility { visibility: visible !important; }
  
  /* Responsive visibility controls */
  @media (min-width: 801px) {
    div.fusion-no-large-visibility,
    .fusion-title.fusion-no-large-visibility,
    .fusion-layout-column.fusion-no-large-visibility,
    .fusion-no-medium-visibility.fusion-no-large-visibility {
      display: none !important;
    }
  }
  @media (max-width: 800px) {
    div.fusion-no-small-visibility,
    .fusion-title.fusion-no-small-visibility,
    .fusion-layout-column.fusion-no-small-visibility {
      display: none !important;
    }
  }

  /* Partner section columns and badges clean grid */
  .fusion-builder-column-14,
  .fusion-builder-column-15,
  .fusion-builder-column-16,
  .fusion-builder-column-17 {
    width: 50% !important;
    max-width: 50% !important;
    flex: 0 0 50% !important;
    min-height: auto !important;
    height: auto !important;
    margin-top: 0px !important;
    margin-bottom: 25px !important;
    padding-left: 10px !important;
    padding-right: 10px !important;
    display: block !important;
    box-sizing: border-box !important;
  }

  @media (max-width: 800px) {
    .fusion-builder-column-14,
    .fusion-builder-column-15,
    .fusion-builder-column-16,
    .fusion-builder-column-17 {
      width: 100% !important;
      max-width: 100% !important;
      flex: 0 0 100% !important;
      margin-bottom: 15px !important;
    }
  }

  .fusion-builder-column-14 .fusion-column-wrapper,
  .fusion-builder-column-15 .fusion-column-wrapper,
  .fusion-builder-column-16 .fusion-column-wrapper,
  .fusion-builder-column-17 .fusion-column-wrapper {
    min-height: auto !important;
    height: auto !important;
    display: block !important;
    padding: 0 !important;
  }

  .fusion-image-carousel,
  .awb-carousel,
  .awb-swiper {
    display: block !important;
    width: 100% !important;
    height: auto !important;
    min-height: auto !important;
    overflow: visible !important;
    padding: 0 !important;
    margin: 0 !important;
  }

  .fusion-image-carousel .swiper-wrapper,
  .awb-swiper .swiper-wrapper {
    display: grid !important;
    grid-template-columns: repeat(5, 1fr) !important;
    gap: 8px !important;
    width: 100% !important;
    height: auto !important;
    min-height: auto !important;
    transform: none !important;
  }

  .awb-swiper.awb-swiper-carousel:not(.swiper-initialized) .swiper-slide,
  .fusion-image-carousel .swiper-slide,
  .awb-swiper .swiper-slide {
    display: flex !important;
    width: 100% !important;
    max-width: 100% !important;
    min-width: 0 !important;
    height: 55px !important;
    align-items: center !important;
    justify-content: center !important;
    padding: 4px 6px !important;
    margin: 0 !important;
    background: #ffffff !important;
    border: 1px solid rgba(0, 0, 0, 0.08) !important;
    border-radius: 6px !important;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.04) !important;
    box-sizing: border-box !important;
  }

  @media (max-width: 600px) {
    .fusion-image-carousel .swiper-wrapper,
    .awb-swiper .swiper-wrapper {
      grid-template-columns: repeat(3, 1fr) !important;
    }
  }

  .fusion-image-carousel .fusion-carousel-item-wrapper,
  .fusion-image-carousel .fusion-image-wrapper,
  .awb-swiper .fusion-image-wrapper {
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    width: 100% !important;
    height: 100% !important;
  }

  .fusion-image-carousel img,
  .awb-swiper img {
    max-height: 34px !important;
    max-width: 90% !important;
    width: auto !important;
    height: auto !important;
    object-fit: contain !important;
    display: block !important;
    margin: 0 auto !important;
  }

  /* Team member card grid fix for tim page */
  .fusion-builder-column-1_3 {
    flex: 0 0 33.333% !important;
    max-width: 33.333% !important;
  }
  @media (max-width: 800px) {
    .fusion-builder-column-1_3 {
      flex: 0 0 100% !important;
      max-width: 100% !important;
    }
  }

  /* Map overlay fix */
  .fusion-google-map .gm-style-moc {
    display: none !important;
  }

  /* Footer styling */
  .fusion-tb-footer, .fusion-footer {
    display: block !important;
    width: 100% !important;
    position: relative !important;
    clear: both !important;
    margin-top: 40px !important;
  }
</style>
`;

function processHtmlContent(html) {
  let output = html;

  // 1. Clean <link rel="preload" ... as="style" href="..."> to standard <link rel="stylesheet" href="...">
  output = output.replace(/<link\b([^>]*?)>/gi, (fullTag, attrs) => {
    if (/as=["']style["']/i.test(attrs) || (/rel=["']preload["']/i.test(attrs) && /\.css/i.test(attrs))) {
      const hrefMatch = attrs.match(/href=["']([^"']+)["']/i);
      if (hrefMatch) {
        return `<link rel="stylesheet" href="${hrefMatch[1]}">`;
      }
    }
    return fullTag;
  });

  // 2. Process all <script> tags
  output = output.replace(/<script\b([^>]*)>([\s\S]*?)<\/script>/gi, (fullMatch, attrStr, innerContent) => {
    if (innerContent.includes('guest.vary.php') || attrStr.includes('guest.vary.php')) return '';
    if (innerContent.includes('litespeed_docref')) return '';
    if (innerContent.includes('litespeed_load_delayed_js') || innerContent.includes('litespeed_ui_events')) return '';
    if (innerContent.includes('litespeed_lazyloaded') || innerContent.includes('window.lazyLoadOptions')) return '';

    if (/type=["']litespeed\/javascript["']/i.test(attrStr)) {
      let newAttrs = attrStr
        .replace(/type=["']litespeed\/javascript["']/gi, '')
        .replace(/data-optimized=["'][^"']*["']/gi, '');
      
      const dataSrcMatch = newAttrs.match(/data-src=["']([^"']+)["']/i);
      if (dataSrcMatch) {
        newAttrs = newAttrs.replace(/data-src=["'][^"']*["']/gi, '');
        newAttrs += ` src="${dataSrcMatch[1]}"`;
      }
      newAttrs = newAttrs.replace(/\s+/g, ' ').trim();
      return `<script ${newAttrs}>${innerContent}</script>`;
    }

    return fullMatch;
  });

  // 3. Process all <img> tags to restore real images
  output = output.replace(/<img\b([^>]*?)>/gi, (imgTag, attrs) => {
    let newAttrs = attrs;
    let dataSrc = null;
    let dataSrcset = null;
    let dataSizes = null;

    const srcMatch = newAttrs.match(/data-src=["']([^"']+)["']/i);
    if (srcMatch) dataSrc = srcMatch[1];

    const srcsetMatch = newAttrs.match(/data-srcset=["']([^"']+)["']/i);
    if (srcsetMatch) dataSrcset = srcsetMatch[1];

    const sizesMatch = newAttrs.match(/data-sizes=["']([^"']+)["']/i);
    if (sizesMatch) dataSizes = sizesMatch[1];

    if (dataSrc) {
      newAttrs = newAttrs.replace(/src=["']data:image\/svg\+xml[^"']*["']/gi, '');
      newAttrs = newAttrs.replace(/data-src=["'][^"']*["']/gi, '');
      newAttrs += ` src="${dataSrc}"`;
    }

    if (dataSrcset) {
      newAttrs = newAttrs.replace(/srcset=["'][^"']*["']/gi, '');
      newAttrs = newAttrs.replace(/data-srcset=["'][^"']*["']/gi, '');
      newAttrs += ` srcset="${dataSrcset}"`;
    }

    if (dataSizes) {
      newAttrs = newAttrs.replace(/sizes=["'][^"']*["']/gi, '');
      newAttrs = newAttrs.replace(/data-sizes=["'][^"']*["']/gi, '');
      newAttrs += ` sizes="${dataSizes}"`;
    }

    newAttrs = newAttrs.replace(/data-lazyloaded=["'][^"']*["']/gi, '');
    newAttrs = newAttrs.replace(/\s+/g, ' ').trim();

    return `<img ${newAttrs}>`;
  });

  // 4. Update Navigation Links across all pages precisely
  output = output.replace(/(<a\s+[^>]*?)href=(["'])[^"']*?\2([^>]*>\s*<span>\s*Sejarah,\s*Visi\s*&amp;\s*Misi\s*<\/span>\s*<\/a>)/gi, '$1href="/sejarah-visi-misi/"$3');
  output = output.replace(/(<a\s+[^>]*?)href=(["'])[^"']*?\2([^>]*>\s*<span>\s*Tim\s*&amp;\s*Pengurus\s*YNKI\s*<\/span>\s*<\/a>)/gi, '$1href="/tim/"$3');
  output = output.replace(/(<a\s+[^>]*?)href=(["'])[^"']*?\2([^>]*>\s*<span>\s*LGOS:\s*Sistem\s*Operasi\s*Organisasi\s*<\/span>\s*<\/a>)/gi, '$1href="/lgos/"$3');
  output = output.replace(/(<a\s+[^>]*?)href=(["'])[^"']*?\2([^>]*>\s*<span>\s*Portfolio\s*<\/span>\s*<\/a>)/gi, '$1href="/portofolio/"$3');
  output = output.replace(/(<a\s+[^>]*?)href=(["'])[^"']*?\2([^>]*>\s*<span>\s*Transparansi\s*&amp;\s*Laporan\s*Mitra\s*<\/span>\s*<\/a>)/gi, '$1href="/transparansi/"$3');

  // 5. Inject Avada global styles and static display fix
  output = output.replace(/<!-- Global Avada Stylesheet Fallback -->[\s\S]*?<\/style>/gi, '');
  output = output.replace(/<style id="avada-static-display-fix">[\s\S]*?<\/style>/gi, '');
  if (output.includes('</head>')) {
    output = output.replace('</head>', `${patchStyle}\n</head>`);
  }

  return output;
}

function getAllHtmlFiles(dir) {
  let results = [];
  const list = fs.readdirSync(dir);
  list.forEach(file => {
    const filePath = path.join(dir, file);
    const stat = fs.statSync(filePath);
    if (stat && stat.isDirectory()) {
      if (file !== 'node_modules' && file !== '.git') {
        results = results.concat(getAllHtmlFiles(filePath));
      }
    } else if (file.endsWith('.html')) {
      results.push(filePath);
    }
  });
  return results;
}

console.log('Optimizing all HTML files...');
const files = getAllHtmlFiles('.');
console.log(`Found ${files.length} HTML files.`);

let count = 0;
for (const file of files) {
  const content = fs.readFileSync(file, 'utf8');
  const processed = processHtmlContent(content);
  fs.writeFileSync(file, processed, 'utf8');
  count++;
}

console.log(`Done! Successfully optimized ${count} HTML files.`);
