# ATC V2

## How to Install

 - run ```setup-init.sh```
 - Set up database  
 	- run ```sudo mysql```
 	- run ```ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'root';```
 	- Create your systems database by logging in ```mysql -u root -p``` and Enter your Password
 	- ```create database YOUR_DATABASE;```
 	- ```exit```
 - Go to your project's folder
 - run ```cp .env.example .env```
 - run ```composer install```
 - run ```npm install```
 - run ```php artisan key:generate```
 - run ```php artisan migrate --seed```
 - run ```npm run prod``` for production, ```npm run dev``` for development
 - run ```php artisan queue:work``` to run worker for queue

## Using Docker

Install the docker with docker-compose

Run with docker-compose
```
docker compose -f docker-compose.prod.yml build
docker compose -f docker-compose.prod.yml up -d
docker compose -f docker-compose.prod.yml exec application bash
# Generate key
# Migrate
exit
```