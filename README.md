# Laravel RBAC (Role-Based Access Control)

This Role-Based Access Control (RBAC) implementation for Laravel, including database schema, migrations, models, seeders, and middleware.

## Features / Application Scope

...

## Tech Stack
[![Composer](https://img.shields.io/badge/Composer-885630?logo=composer&logoColor=fff)](#)
[![Laravel](https://img.shields.io/badge/Laravel-%23FF2D20.svg?logo=laravel&logoColor=white)](#)
[![MySQL](https://img.shields.io/badge/MySQL-4479A1?logo=mysql&logoColor=fff)](#)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-7952B3?logo=bootstrap&logoColor=fff)](#)

## Dependencies

...

## Installation

1. Clone the repository

   ...

2. Navigate into the directory

   ...

3. Install dependencies

    ```bash
    composer install
    ```

    > Make sure you have **_PHP (>=8.1)_** and composer installed
    > If not installed, you can see link bellow for install:
    >
    > - [PHP - Instalation and Configuration](https://www.php.net/manual/en/install.php)
    > - [Download Composer](https://getcomposer.org/download/)

4. Copy and configure `.env`

    ```bash
    cp .env.example .env
    ```

    Edit the `.env` file with your configuration:

    ```env
    DB_HOST=your_database_host
    DB_PORT=your_database_port
    DB_DATABASE=your_database_name
    DB_USERNAME=your_database_username
    DB_PASSWORD=your_database_password
    ```

5. Generate application key

    ```bash
    php artisan key:generate
    ```

6. Run migrations

    ```bash
    php artisan migrate
    ```

7. Serve the App (for local dev)

    ```bash
    php artisan serve
    ```
