# Payroll

Payroll backend, developed for the company 'We are X'. Not delivered. It is built on Laravel 5.8.38

## Installation

First clone this project, then run:

```bash
composer install
```

```bash
cp .env.example .env
```

```bash
set values for Database Connection
set values for Mail Configuration
```

```bash
php artisan key:generate
```

```bash
php artisan migrate
```

```bash
php artisan passport:install
```

```bash
php artisan serve
```

## Read please...

An already data-filled database is attached to the source code under the payroll-backup directory. You can import it and get started within a matter of seconds.
