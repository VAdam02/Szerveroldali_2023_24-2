@echo off
REM This script is for setting up the project after cloning it from git
REM Make sure to run this script in the project directory

set "envFile=false"

if not exist .env (
    set "envFile=true"
    copy .env.example .env
)

composer update
composer install
npm install

if "%envFile%"=="true" (
    php artisan key:generate
)

if not exist ".\database\database.sqlite" (
    type nul > .\database\database.sqlite
    php artisan migrate:fresh --seed
)

REM To host the project run both in two different terminals
REM npm run dev
REM php artisan serve
