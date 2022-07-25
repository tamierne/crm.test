
# CRM.test

This is a bootstrap and adminLTE based prototype of CRM system with possibility to manage users, clients, projects and tasks.


## Features

- Avatars for users and clients (Spatie media library)
- User notifications via email
- Permission system (Spatie Permission)
- Database seeding


How to setup an application:
- clone the repository
- set .env settings to:
    - `APP_URL=http://localhost:8000`
    - `DB_TEST_DATABASE=crm.test.polygon`

- Run `docker-compose up -d`
- Inside php container `docker-compose exec php bash`
    - `composer install`
    - `artisan key:generate`
    - `artisan migrate:fresh --seed`
    - `artisan storage:link`
- Open in browser `http://localhost:8000/`

- superadmin account (full permissions, can manage roles/permissions):
    - email: admin@admin.com
    - password: 12345678

- admin account (can manage roles/permissions):
    - email: admin@example.com
    - password: 12345678

- user account (contrained functionality)
    - email: user@example.com
    - password: 12345678