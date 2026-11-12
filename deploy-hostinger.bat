@echo off
setlocal
echo ========================================================
echo   Deploying Natural Kapital to Hostinger Production
echo ========================================================
powershell.exe -NoProfile -ExecutionPolicy Bypass -File "%~dp0deploy-hostinger.ps1"
if %ERRORLEVEL% NEQ 0 (
    echo.
    echo [ERROR] Deployment to Hostinger failed with exit code %ERRORLEVEL%
    pause
    exit /b %ERRORLEVEL%
)
endlocal

