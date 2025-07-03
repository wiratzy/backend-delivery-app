@echo off
cd /d C:\Users\wiran\Desktop\backend-delivery-app
php artisan schedule:run >> schedule_log.txt
