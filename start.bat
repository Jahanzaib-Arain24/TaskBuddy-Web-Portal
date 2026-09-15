@echo off
title Task Buddy - Local Server Starter
color 0A

echo ========================================================
echo         TASK BUDDY - JOB PORTAL LOCAL SERVER
echo ========================================================
echo.

:: 1. Check & Start MySQL if not running
echo [1/3] Checking MySQL Database Service...
tasklist /FI "IMAGENAME eq mysqld.exe" 2>NUL | find /I /N "mysqld.exe">NUL
if "%ERRORLEVEL%"=="0" (
    echo [OK] MySQL is already running.
) else (
    echo [*] Starting MySQL service from XAMPP...
    if exist "C:\xampp\mysql\bin\mysqld.exe" (
        start /b "" "C:\xampp\mysql\bin\mysqld.exe" --defaults-file="C:\xampp\mysql\bin\my.ini" --standalone
        timeout /t 2 /nobreak >nul
        echo [OK] MySQL started successfully.
    ) else (
        echo [!] Warning: Could not find C:\xampp\mysql\bin\mysqld.exe
        echo     Please ensure XAMPP MySQL is turned ON manually.
    )
)

echo.
:: 2. Launch Browser
echo [2/3] Opening browser at http://localhost:8000 ...
timeout /t 1 /nobreak >nul
start "" "http://localhost:8000"

echo.
:: 3. Start PHP Server
echo [3/3] Starting PHP Development Server on port 8000...
echo ========================================================
echo  Server is running at: http://localhost:8000
echo  Database: job_portal (MySQL port 3306)
echo  Press CTRL + C to stop the server anytime.
echo ========================================================
echo.

if exist "C:\xampp\php\php.exe" (
    "C:\xampp\php\php.exe" -S 0.0.0.0:8000 router.php
) else (
    php -S 0.0.0.0:8000 router.php
)

pause
