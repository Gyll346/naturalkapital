$root = $PSScriptRoot
$files = Get-ChildItem -Path $root -Recurse -Filter '*.html' | Where-Object { $_.FullName -notmatch '\\(vendor|node_modules|storage)\\' }
$cssTag = '  <link rel="stylesheet" href="/assets/css/ynki-responsive-system.css" />' + [Environment]::NewLine + '</head>'
$headClosing = '</head>'
$jsTag = '  <script src="/assets/js/ynki-footer.js"></script>' + [Environment]::NewLine + '</body>'
$bodyClosing = '</body>'
$count = 0

foreach ($file in $files) {
    $content = [System.IO.File]::ReadAllText($file.FullName, [System.Text.Encoding]::UTF8)
    $modified = $false

    if (-not $content.Contains('ynki-responsive-system.css') -and $content.Contains($headClosing)) {
        $content = $content.Replace($headClosing, $cssTag)
        $modified = $true
    }

    if (-not $content.Contains('ynki-footer.js') -and $content.Contains($bodyClosing)) {
        $content = $content.Replace($bodyClosing, $jsTag)
        $modified = $true
    }

    if ($modified) {
        [System.IO.File]::WriteAllText($file.FullName, $content, [System.Text.Encoding]::UTF8)
        $count++
        Write-Output "Updated: $($file.FullName)"
    }
}

Write-Output "Total files updated: $count"
