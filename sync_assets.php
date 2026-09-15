<?php
$dir = new RecursiveDirectoryIterator(__DIR__);
$it = new RecursiveIteratorIterator($dir);
$count = 0;
foreach ($it as $f) {
    if ($f->isFile() && $f->getExtension() === 'html') {
        $path = $f->getPathname();
        if (preg_match('#[\\\\/](vendor|node_modules|storage)[\\\\/]#', $path)) {
            continue;
        }
        $content = file_get_contents($path);
        $modified = false;

        // Check if ynki-responsive-system.css is in head
        if (strpos($content, 'ynki-responsive-system.css') === false && strpos($content, '</head>') !== false) {
            $tag = '  <link rel="stylesheet" href="/assets/css/ynki-responsive-system.css" />' . "\n</head>";
            $content = str_replace('</head>', $tag, $content);
            $modified = true;
        }

        // Check if ynki-footer.js is before </body>
        if (strpos($content, 'ynki-footer.js') === false && strpos($content, '</body>') !== false) {
            $tag = '  <script src="/assets/js/ynki-footer.js"></script>' . "\n</body>";
            $content = str_replace('</body>', $tag, $content);
            $modified = true;
        }

        if ($modified) {
            file_get_contents($path);
            file_put_contents($path, $content);
            $count++;
            echo "Updated: $path\n";
        }
    }
}
echo "Total updated files: $count\n";
