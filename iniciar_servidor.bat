@echo off
title Sistema de Farmacia - Servidor de Desarrollo
cd /d "%~dp0"

echo ========================================================
echo Iniciando Servidor de la API - Sistema de Farmacia
echo ========================================================
echo.

set PHP_BIN=\php\php.exe
set PHP_INI=\php
if exist "%PHP_BIN%" goto INICIAR_CON_PHP

set PHP_BIN=\php\php.exe
set PHP_INI=\php
if exist "%PHP_BIN%" goto INICIAR_CON_PHP

echo Usando PHP del sistema...
php artisan serve --host=127.0.0.1 --port=8000
goto FIN

:INICIAR_CON_PHP
echo Usando PHP portable de la asignatura.
echo Servidor disponible en: http://127.0.0.1:8000
echo Presiona Ctrl+C para detener el servidor.
echo --------------------------------------------------------
"%PHP_BIN%" -c "%PHP_INI%" artisan serve --host=127.0.0.1 --port=8000

:FIN
pause
