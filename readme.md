My article management system
=================

This is a simple application representing article management system (AMS) using the [Nette](https://nette.org).

[Nette](https://nette.org) is a popular tool for PHP web development.
It is designed to be the most usable and friendliest as possible. It focuses
on security and performance and is definitely one of the safest PHP frameworks.


Requirements
------------

- This Web Project for Nette 3.1 requires PHP 8.1+
- MySQL database service
- Apache or built-in PHP web server
- **Optional but recommended:** Install [XAMPP](https://www.apachefriends.org/) which includes
  Apache web server and MySQL service in a single package and is (in my opinion) the best for PHP development.


Web Server Setup
----------------

### Built-in PHP server

The simplest way to get started is to start the built-in PHP server in the root directory of your project:

    php -S localhost:8000 -t www

Then visit `http://localhost:8000` in your browser to see the introduction page.

> **Note:** It is important to enable extensions that will be used in your `php.ini` file (or one
> of `php.ini-development` and `php.ini-production` files) otherwise you might get various errors reported
> by [Tracy](https://tracy.nette.org/en/).
>
> One of those errors might be *"PDO Exception: Driver not found"* because the
> extension *pdo_mysql* hasn't been enabled.

### Apache

With the Apache web server provided in [XAMPP](https://www.apachefriends.org/) you need to:

- Move whole project folder to `xampp/htdocs` directory
- Start up apache web server via XAMPP control panel
- Start up MySQL service via XAMPP control panel

Then you can visit `http://localhost:8080/my-ams/www/` in your browser to see the introduction page.

**It is CRITICAL that whole `app/`, `config/`, `log/` and `temp/` directories are not accessible directly
via a web browser. See [security warning](https://nette.org/security-warning).**

Before you start
----------------

You need to create the database, admin user and seed the database for application to work as intended from the start.
You can find the commands to seed the database in `sql/seed.sql`.
