
# CRM.test

This is a bootstrap and adminLTE based prototype of CRM system with possibility to manage users, clients, projects and tasks.


## Features

- Avatars for users and clients (Spatie media library)
- User notifications via email
- Permission system (Spatie Permission)
- Database seeding
- Email registration verification
- Email user task/project assignment/completion notification


How to setup an application:
- clone the repository
- сreate config file `cp .env.example .env`
- set .env settings to:
    - `DB_CONNECTION=pgsql`
    - `DB_HOST=database`
    - `DB_PORT=5432`
    - `DB_DATABASE=laravel`
    - `DB_TEST_DATABASE=`
    - `DB_USERNAME=postgres`
    - `DB_PASSWORD=password`

- Run `docker-compose up -d`
- Inside php container `docker compose exec php bash`
    - `composer install`
    - `php artisan key:generate`
    - `php artisan migrate:fresh --seed`
    - `php artisan storage:link`
- Inside node container `docker compose run node bash`
    - `yarn`
    - `yarn watch`
- Restart docker containers with commands
    - `docker compose down`
    - `docker compose up -d`
- Open in browser `http://localhost`


- superadmin account (full permissions, can manage roles/permissions):
    - `email: admin@admin.com`
    - `password: 12345678`

- admin account (can manage roles/permissions):
    - `email: admin@example.com`
    - `password: 12345678`

- user account (contrained functionality)
    - `email: user@example.com`
    - `password: 12345678`
