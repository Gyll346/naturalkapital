<?php
$dirs = glob('assets/images/*');
foreach ($dirs as $d) {
    if (is_dir($d)) {
        echo "=== " . basename($d) . " ===\n";
        $files = glob($d . '/*.{png,jpg,jpeg,webp}', GLOB_BRACE);
        foreach ($files as $f) {
            $size = getimagesize($f);
            if ($size) {
                echo basename($f) . " (" . $size[0] . "x" . $size[1] . ", " . round(filesize($f)/1024) . "KB)\n";
            }
        }
    }
}
