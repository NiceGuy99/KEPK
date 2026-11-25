@echo off
title WA Gateway e-KEPK ETHOS RSUD Sidoarjo Barat
echo ====================================================
echo Menjalankan WhatsApp Gateway e-KEPK ETHOS...
echo Port: 8088
echo Buka browser di http://localhost:8088/qr untuk scan
echo ====================================================
cd /d %~dp0wa-gateway
node server.js
pause
