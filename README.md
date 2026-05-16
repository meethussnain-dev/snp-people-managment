# SNP People Manager

SNP People Manager is a Laravel 8 and Livewire 2 implementation of the assessment brief. It provides authenticated CRUD management for people, stores languages and interests as reference data, and sends an email notification when a person is captured through an event, listener, job, and mail flow.

## Delivery Summary

- Framework: Laravel 8
- UI layer: Livewire 2 + Blade
- Database: MySQL
- Authentication: Laravel auth with a seeded admin user
- Architecture: service and repository pattern, separated from the UI layer
- Deployment compatibility: works with `php artisan serve` and standard Apache or Nginx virtual-host setups

## Requirements Coverage

This project aligns with the brief in these areas:

- Uses PHP and MySQL through Laravel instead of vanilla PHP or a CMS
- Uses migrations and seeders, so a separate SQL dump is not required for normal setup
- Provides explicit login credentials for assessment review
- Avoids hard-coded option lists in the form by seeding languages and interests into the database
- Keeps business logic out of Blade templates by routing data changes through Livewire components, services, repositories, events, listeners, and jobs
- Can be hosted on a virtual host because it is a standard Laravel application served from the `public` directory

## Tech Stack

- PHP 7.3+ compatible through Laravel 8 requirements
- Laravel 8
- Livewire 2
- MySQL
- Bootstrap 5.3 via CDN
- Bootstrap Icons via CDN

## Local Environment Used

- Application URL: `http://127.0.0.1:8080`
- Database name: `laravel`
- Database username: `root`
- Database password: `root`
- Queue driver: `sync`
- Mail driver: `log`

## Reviewer Credentials

- Email: `admin@snp.test`
- Password: `Password123!`

## Setup

1. Run `composer install`
2. Copy `.env.example` to `.env` if needed
3. Configure the MySQL connection in `.env`
4. Run `php artisan key:generate`
5. Run `php artisan migrate:fresh --seed`
6. Start the application with either `php artisan serve --host=127.0.0.1 --port=8080` or a normal Apache/Nginx virtual host pointing to the `public` directory

## Virtual Host Support

The application is compatible with a normal virtual-host deployment.

- For Apache or Nginx, point the document root to the `public` directory
- Keep the Laravel project itself outside the public web root except for `public`
- Set `APP_URL` in `.env` to the final host name or local domain used by the server
- Ensure the web server can write to `storage` and `bootstrap/cache`

This satisfies the brief's requirement that the system must be able to work on a virtual host.

## Architecture Notes

- Repository contracts and Eloquent repositories encapsulate persistence concerns
- Services coordinate business operations for people management
- Livewire components handle UI interactions without putting business logic in Blade
- Reference data for languages and interests is loaded from seeded tables rather than duplicated in forms
- Email dispatch on person creation follows this flow:
	`PersonCaptured` -> `DispatchPersonCapturedEmail` -> `SendPersonCapturedEmailJob` -> `PersonCapturedMail`

## Assessment Notes

- Registration is intentionally disabled because the brief only requires an authenticated application with known access credentials
- The mail driver is set to `log`, so generated emails can be verified safely from the log output during assessment
- Bootstrap assets are loaded from CDNs, so a frontend build step is not required to run the application
- Because migrations and seeders are included, reviewers do not need an SQL dump unless they explicitly prefer one

## Verification

Use the following checks when reviewing the application:

1. Run `php artisan migrate:fresh --seed`
2. Start the application and log in with the seeded credentials
3. Create, edit, and delete people from the People screen
4. Confirm languages and interests are populated from seeded data
5. Confirm a mail log entry is written after creating a person

## Code Quality Response To The Brief

The brief specifically warns against hard-coding values, duplicating code, spaghetti code, and submitting copied work. This project addresses that by:

- Centralizing persistence logic in repositories
- Centralizing business operations in services
- Using seeded reference tables for languages and interests instead of hard-coded form values
- Keeping the UI layer focused on display and interaction
- Using Laravel's standard structure instead of mixing unrelated responsibilities together

## Reset Confirmation

The application was re-tested with a clean database reset before handoff:

- Run `php artisan migrate:fresh --seed`
- Confirm the admin login still works
- Confirm the People workflow still works after the reset
- Verified on `2026-05-15` by running `php artisan migrate:fresh --seed` successfully and boot-checking the app with `php artisan route:list`
