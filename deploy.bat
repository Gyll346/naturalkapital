@echo off
setlocal
echo ========================================================
echo   Deploying Natural Kapital to VPS via Docker and SSH
echo ========================================================
powershell.exe -NoProfile -ExecutionPolicy Bypass -File "%~dp0deploy.ps1"
if %ERRORLEVEL% NEQ 0 (
    echo.
    echo [ERROR] Deployment failed with exit code %ERRORLEVEL%
    exit /b %ERRORLEVEL%
)
endlocal
