[CmdletBinding()]
param(
    [string]$RemoteHost = "hostinger",
    [string]$RemoteDir = "/home/u8426292/naturalkapital",
    [string]$PublicHtml = "/home/u8426292/public_html"
)

$ErrorActionPreference = "Stop"

Write-Host "`n🚀 [1/4] Memeriksa koneksi SSH ke Hostinger ($RemoteHost)..." -ForegroundColor Cyan
ssh $RemoteHost "echo 'Koneksi SSH Hostinger Sukses!'"

Write-Host "`n📦 [2/4] Menarik kode terbaru dari branch 'main' GitHub..." -ForegroundColor Cyan
ssh $RemoteHost "cd $RemoteDir && git fetch origin && git reset --hard origin/main"

Write-Host "`n⚡ [3/4] Menjalankan Autoload, Migrasi, & Cache Optimization Laravel..." -ForegroundColor Cyan
$remoteCmd = @"
cd $RemoteDir
/opt/alt/php82/usr/bin/php /usr/local/bin/composer dump-autoload -o --no-scripts
/opt/alt/php82/usr/bin/php artisan migrate --force
/opt/alt/php82/usr/bin/php artisan optimize
ln -sfn $RemoteDir/assets $PublicHtml/assets
ln -sfn $RemoteDir/storage/app/public $PublicHtml/storage
chmod -R 775 $RemoteDir/storage $RemoteDir/bootstrap/cache
"@
ssh $RemoteHost $remoteCmd

Write-Host "`n✅ [4/4] Selesai! Website sudah live di: https://naturalkapital.or.id" -ForegroundColor Green

