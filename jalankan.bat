@echo off
title Menjalankan KasirKu POS
echo ==============================================
echo   MENJALANKAN SISTEM POINT OF SALES KASIRKU
echo ==============================================
echo.

:: Tambahkan path PHP ke sesi saat ini jika belum terbaca
set "PATH=%LOCALAPPDATA%\Microsoft\WinGet\Packages\PHP.PHP.8.2_Microsoft.Winget.Source_8wekyb3d8bbwe;%PATH%"

echo Membuka server lokal di http://127.0.0.1:8000 ...
echo Silakan buka browser Anda di: http://127.0.0.1:8000
echo.
echo Tekan Ctrl + C untuk menghentikan server jika sudah selesai.
echo.

php artisan serve --port=8000
pause
