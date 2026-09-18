@echo off
title Sistema de Farmacia - Suite de Pruebas Automatizadas
cd /d "%~dp0"

echo ========================================================
echo Ejecutando Pruebas Automatizadas (PHPUnit)
echo ========================================================
echo.

set PHP_BIN=\php\php.exe
set PHP_INI=\php
if exist "%PHP_BIN%" goto PROBAR_CON_PHP

echo Usando PHP del sistema...
php artisan test --testdox
goto FIN

:PROBAR_CON_PHP
"%PHP_BIN%" -c "%PHP_INI%" artisan test --testdox

:FIN
echo.
echo ========================================================
echo Pruebas finalizadas.
echo ========================================================
pause
