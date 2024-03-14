#!/bin/bash

#This script is for setting up the project after cloning it from git
#chmod +x AfterGitClone.sh

envFile=false

if [ ! -f .env ]; then
    envFile=true
    cp .env.example .env
fi

composer update
composer install
npm install

if [ $envFile ]; then
    php artisan key:generate
fi

if [ ! -f d./database/atabase.sqlite ]; then
    touch ./database/database.sqlite
    php artisan migrate:fresh --seed
fi

#To host the project run both in two different terminals
#npm run dev
#php artisan serve
