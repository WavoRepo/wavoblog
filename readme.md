# Wavo Blog Application
A simple blog application using laravel as framework, sqlite as database and Vuejs, Vuex, VueRouter and Axios as HTTP client for simple,easy XMLHttpRequests.

### Getting Started

* Clone the project repo from https://github.com/WavoRepo/wavoblog.git
* In your project's main directory, run:
  * run `composer install` _(Note you may need to run `composer update` if you wish to update to the latest production/stable library versions)_
  * run `npm install or yarn install`
  * run `php artisan key:generate`
  * copy the outputted key into your `.env` file
  * If you don't have a .env file, run `cp .env.example .env`
  * add new file under database folder: database.sqlite
  * run `php artisan migrate`

### add to your env

  #Application name

  APP_NAME=WavoBlog

  #sqlite connection

  DB_CONNECTION=sqlite

  DB_HOST=127.0.0.1

  DB_DATABASE=database.sqlite

  DB_PORT=3306

  DB_USERNAME=homestead

  DB_PASSWORD=secret

### Authors
* Jeremie Yunsay - Initial work - Freelance

### License

This project is licensed under the MIT License
