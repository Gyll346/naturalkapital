[CmdletBinding()]
param(
    [string]$VpsHost = "vps1",
    [string]$RemoteDir = "/home/naturalkapital/naturalkapital"
)

$ErrorActionPreference = "Stop"

Write-Host "`n[1/5] Memeriksa koneksi SSH ke VPS ($VpsHost)..." -ForegroundColor Cyan
ssh -o BatchMode=yes -o ConnectTimeout=10 $VpsHost "echo 'Koneksi SSH Sukses!'"

Write-Host "`n[2/5] Menyiapkan repositori & file di VPS ($RemoteDir)..." -ForegroundColor Cyan
$cmdGit = "if [ ! -d '$RemoteDir/.git' ]; then git clone https://github.com/Gyll346/naturalkapital.git '$RemoteDir'; else cd '$RemoteDir' && git fetch origin && git reset --hard origin/main; fi"
ssh $VpsHost $cmdGit

Write-Host "`n[3/5] Menyalin konfigurasi .env ke VPS..." -ForegroundColor Cyan
scp -o BatchMode=yes .env "${VpsHost}:${RemoteDir}/.env"

Write-Host "`n[4/5] Menjalankan Docker Compose di VPS..." -ForegroundColor Cyan
$cmdDocker = "cd '$RemoteDir' && docker compose up -d"
ssh $VpsHost $cmdDocker

Write-Host "`n[5/5] Memperbarui Dependensi, Migrasi, & Cache Laravel..." -ForegroundColor Cyan
$cmdArtisan = "cd '$RemoteDir' && docker compose exec -T ynki-app composer install --no-dev --optimize-autoloader && docker compose exec -T ynki-app php artisan migrate --force && docker compose exec -T ynki-app php artisan optimize"
ssh $VpsHost $cmdArtisan

Write-Host "`n[SELESAI] DEPLOY SUKSES!" -ForegroundColor Green
Write-Host "Container aktif di VPS:" -ForegroundColor Yellow
docker --context vps1 ps
