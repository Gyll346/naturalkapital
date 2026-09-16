$candidates = @(
    'C:\xampp\php\php.exe',
    'C:\laragon\bin\php\php-8.2.0-Win32-vs16-x64\php.exe',
    'C:\laragon\bin\php\php-8.1.0-Win32-vs16-x64\php.exe',
    'C:\Program Files\PHP\php.exe',
    'C:\tools\php\php.exe'
)
$found = Get-ChildItem -Path 'C:\laragon\bin\php', 'C:\xampp\php' -Filter 'php.exe' -Recurse -ErrorAction SilentlyContinue | Select-Object -First 1

if ($found) {
    Write-Output "Found PHP at: $($found.FullName)"
    & $found.FullName check_db_team.php
} else {
    Write-Output "PHP executable not found in standard paths"
}
