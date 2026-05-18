# People Management System

People Management System is a full-stack Laravel web application for capturing, managing, and tracking people profiles. Built with Laravel 13, Livewire 4, and Bootstrap 5, it follows a service-repository architecture with feature-driven test coverage. The application allows authenticated users to create, edit, search, paginate, and delete people records, each linked to a preferred language and one or more interests. When a person is created, the system dispatches a queued email notification through an event-driven pipeline.

---

## Table of Contents

1. [Tech Stack and Versions](#1-tech-stack-and-versions)
2. [Why These Choices](#2-why-these-choices)
3. [Setup and Installation](#3-setup-and-installation)
4. [Reviewer Credentials](#4-reviewer-credentials)
5. [Project Structure](#5-project-structure)
6. [Architecture and Design Patterns](#6-architecture-and-design-patterns)
7. [Database Schema and Migrations](#7-database-schema-and-migrations)
8. [Models and Relationships](#8-models-and-relationships)
9. [Repositories](#9-repositories)
10. [Services](#10-services)
11. [Livewire Components](#11-livewire-components)
12. [Livewire Forms](#12-livewire-forms)
13. [Request Validation](#13-request-validation)
14. [Custom Validation Rules](#14-custom-validation-rules)
15. [Events, Listeners, Jobs, and Mail](#15-events-listeners-jobs-and-mail)
16. [Routes and Middleware](#16-routes-and-middleware)
17. [Frontend and Blade Templates](#17-frontend-and-blade-templates)
18. [Blade Components](#18-blade-components)
19. [Factories and Seeders](#19-factories-and-seeders)
20. [Testing with Pest](#20-testing-with-pest)
21. [Virtual Host Deployment](#21-virtual-host-deployment)
22. [Verification Checklist](#22-verification-checklist)

---

## 1. Tech Stack and Versions

| Technology        | Version   | Purpose                                      |
|-------------------|-----------|----------------------------------------------|
| PHP               | 8.3+      | Server-side language                         |
| Laravel           | 13.x      | Application framework                        |
| Livewire          | 4.x       | Reactive full-stack UI components            |
| MySQL             | 8.x       | Relational database                          |
| Bootstrap         | 5.3.3     | CSS framework (loaded via CDN)               |
| Bootstrap Icons   | 1.11.3    | Icon library (loaded via CDN)                |
| Pest PHP          | 4.7+      | Testing framework                            |
| Inter (Google)    | 300-700   | Primary typeface                             |

---

## 2. Why These Choices

### Laravel 13
Laravel 13 is the latest stable release. It provides modern PHP 8.3+ features, improved performance, native type declarations, and the most up-to-date ecosystem support for packages like Livewire 4 and Pest 4.

### Livewire 4 over Traditional Controllers
Livewire replaces the need for separate controllers, JSON APIs, and JavaScript frameworks. Each page is a self-contained Livewire component that handles its own state, user interactions, and server communication. This keeps the application in a single codebase (PHP + Blade) with no frontend build step.

### Pest over PHPUnit
Pest is a testing framework built on top of PHPUnit with a cleaner, more expressive syntax. It reduces boilerplate, supports chained expectations, and integrates natively with Laravel. Tests read like plain English, making them easier to write, review, and maintain.

### Bootstrap via CDN
No `npm install` or `npm run dev` is required. Bootstrap 5.3 is loaded from a CDN, which simplifies setup and ensures the reviewer can run the application immediately without a frontend build step.

### Service-Repository Pattern
Separating data access (repositories) from business logic (services) from UI (Livewire components) produces testable, maintainable code where each layer has a single responsibility.

---

## 3. Setup and Installation

```bash
# 1. Install PHP dependencies
composer install

# 2. Configure environment
cp .env.example .env
# Edit .env with your MySQL credentials

# 3. Generate application key
php artisan key:generate

# 4. Run migrations and seed reference data
php artisan migrate:fresh --seed

# 5. Start the application
php artisan serve --host=127.0.0.1 --port=8080
```

No `npm install` or frontend build step is required.

### Local Environment Defaults

| Setting           | Value                      |
|-------------------|----------------------------|
| Application URL   | `http://127.0.0.1:8000`    |
| Database name     | `laravel`                  |
| Database user     | `root`                     |
| Database password | `root`                     |
| Queue driver      | `sync`                     |
| Mail driver       | `log`                      |

---

## 4. Reviewer Credentials

| Field    | Value              |
|----------|--------------------|
| Email    | `admin@pms.test`   |
| Password | `Password123!`     |

Registration is intentionally disabled. The brief requires an authenticated application with known access credentials, so only a seeded admin account is provided.

---

## 5. Project Structure

```
app/
  Console/                    Artisan commands
  Events/
    PersonCaptured.php        Fired when a new person is created
  Http/
    Controllers/
      Auth/                   Laravel auth controllers (login only)
      HomeController.php      Redirects to people.index
    Middleware/
      Authenticate.php        Redirects guests to login
    Requests/
      PersonSaveRequest.php   Centralized validation rules
  Jobs/
    SendPersonCapturedEmailJob.php   Queued email dispatch
  Listeners/
    DispatchPersonCapturedEmail.php  Bridges event to job
  Livewire/
    Forms/
      PersonForm.php          Livewire form object for person data
    People/
      PersonFormComponent.php Abstract base for create/edit
      CreatePerson.php        Create person page
      EditPerson.php          Edit person page
      ListPeople.php          People listing with search/pagination/delete
  Mail/
    PersonCapturedMail.php    Email sent to captured person
  Models/
    User.php                  Authenticated admin user
    Person.php                Core entity
    Language.php              Reference: languages
    Interest.php              Reference: interests
  Providers/
    EventServiceProvider.php       Event-listener bindings
    RepositoryServiceProvider.php  Repository interface bindings
  Repositories/
    Eloquent/
      BaseRepository.php     Generic CRUD implementation
    Person/
      PersonRepository.php           Transactional create/update with pivot sync
      PersonRepositoryInterface.php  Contract
    Language/
      LanguageRepository.php         Read-only language access
      LanguageRepositoryInterface.php
    Interest/
      InterestRepository.php         Read-only interest access
      InterestRepositoryInterface.php
  Rules/
    SouthAfricanIdNumber.php  Custom Luhn-based SA ID validation
  Services/
    Person/PersonService.php     Business logic for people
    Language/LanguageService.php Read-only language retrieval
    Interest/InterestService.php Read-only interest retrieval
  View/
    Components/
      Form/
        TextInput.php              Class-based text input component
        SelectInput.php            Class-based select input component
        CheckboxMultiSelect.php    Class-based checkbox group component
      People/
        InterestsBadges.php        Class-based interest badges component

resources/views/
  layouts/
    app.blade.php             Main layout with nav, styles, scripts
  livewire/people/
    list-people.blade.php     People table with search, pagination, delete modal
    create-edit-form.blade.php Shared form for create and edit
  components/
    headers/
      page-header.blade.php  Reusable page header with title, description, icon, slot
    form/
      text-input.blade.php        Text input template
      select-input.blade.php      Select dropdown template
      checkbox-multi-select.blade.php  Checkbox group template
      search-input.blade.php      Live search input template
    people/
      interests-badges.blade.php  Interest badges with overflow tooltip
  emails/
    person-captured.blade.php     Email body template

routes/
  web.php                     All application routes

tests/
  Pest.php                    Pest configuration (base test class, RefreshDatabase)
  Unit/Rules/
    SouthAfricanIdNumberTest.php  SA ID validation unit tests
  Feature/
    Auth/
      LoginTest.php           Authentication tests
    People/
      CreatePersonTest.php    Person creation tests
      EditPersonTest.php      Person editing tests
      DeletePersonTest.php    Person deletion tests
      ListPeopleTest.php      People listing tests

database/
  factories/                  UserFactory, PersonFactory, LanguageFactory, InterestFactory
  migrations/                 9 migration files
  seeders/                    AdminAccountSeeder, LanguageSeeder, InterestSeeder
```

---

## 6. Architecture and Design Patterns

### Layered Architecture

```
Routes (web.php)
    |
Livewire Components (UI layer)
    |
Services (business logic)
    |
Repositories (data access)
    |
Eloquent Models (database)
```

Each layer has a single responsibility:

- **Routes** map URLs to Livewire components.
- **Livewire components** handle user interactions, render views, and delegate data operations to services.
- **Services** contain business logic and coordinate repository calls.
- **Repositories** encapsulate Eloquent queries behind interfaces. The `RepositoryServiceProvider` binds interfaces to implementations, so services depend on contracts, not concrete classes.
- **Models** define table mappings, relationships, and attribute casts.

### Repository Pattern

Every model that requires data access has a repository interface and an Eloquent implementation. The `BaseRepository` provides generic CRUD methods (`create`, `find`, `update`, `delete`, `all`, etc.). Specific repositories like `PersonRepository` extend it with domain-specific methods like `paginatedList` and `createProfile`.

Interfaces are bound in `RepositoryServiceProvider`:

```php
PersonRepositoryInterface::class  => PersonRepository::class
LanguageRepositoryInterface::class => LanguageRepository::class
InterestRepositoryInterface::class => InterestRepository::class
```

### Event-Driven Email Flow

Person creation triggers an asynchronous email pipeline:

```
PersonRepository::createProfile()
    -> fires PersonCaptured event
        -> DispatchPersonCapturedEmail listener
            -> dispatches SendPersonCapturedEmailJob (queued)
                -> sends PersonCapturedMail
```

This decouples the email concern from the creation logic. The job implements `ShouldQueue`, so in production the email is processed asynchronously. Locally the queue driver is `sync`, and the mail driver is `log`, so emails appear in `storage/logs/laravel.log`.

### Database Transactions

Person creation and updates are wrapped in database transactions inside `PersonRepository`. This ensures that the person record and their interest pivot records are either both committed or both rolled back.

---

## 7. Database Schema and Migrations

Migrations are numbered and named descriptively:

| Migration                                    | Table            | Purpose                                        |
|----------------------------------------------|------------------|-------------------------------------------------|
| `create_pms_accounts_table`                  | `users`          | Admin user accounts                             |
| `create_pms_password_reset_records_table`    | `password_resets`| Password reset tokens                           |
| `create_pms_failed_jobs_log_table`           | `failed_jobs`    | Failed queue job tracking                       |
| `create_pms_access_tokens_table`             | `personal_access_tokens` | Sanctum API tokens                     |
| `create_pms_people_registry_table`           | `people`         | Core people records                             |
| `create_pms_language_catalog_table`          | `languages`      | Language reference data                         |
| `create_pms_interest_catalog_table`          | `interests`      | Interest reference data                         |
| `create_pms_person_interest_links_table`     | `interest_person`| Many-to-many pivot table                        |
| `link_pms_people_to_language_catalog`        | `people`         | Adds `language_id` foreign key                  |

### People Table Columns

| Column          | Type       | Constraints                      |
|-----------------|------------|----------------------------------|
| `id`            | bigint     | Primary key, auto-increment      |
| `created_by`    | bigint     | Foreign key to `users`           |
| `language_id`   | bigint     | Foreign key to `languages`       |
| `name`          | string     | Required                         |
| `surname`       | string     | Required                         |
| `sa_id_number`  | string(13) | Required, unique                 |
| `mobile_number` | string(20) | Required                         |
| `email`         | string     | Required, unique                 |
| `birth_date`    | date       | Required, must be past date      |
| `created_at`    | timestamp  | Auto-managed                     |
| `updated_at`    | timestamp  | Auto-managed                     |

### Pivot Table: `interest_person`

| Column        | Type   | Constraints                                      |
|---------------|--------|--------------------------------------------------|
| `person_id`   | bigint | Foreign key to `people`, cascade on delete        |
| `interest_id` | bigint | Foreign key to `interests`, cascade on delete     |

A unique composite constraint on `(person_id, interest_id)` prevents duplicate assignments.

---

## 8. Models and Relationships

### User

```
User --hasMany--> Person (via created_by)
```

The `User` model represents authenticated administrators. Each user can create many people. Fields: `name`, `email`, `password`.

### Person

```
Person --belongsTo--> User (creator, via created_by)
Person --belongsTo--> Language (via language_id)
Person --belongsToMany--> Interest (via interest_person pivot)
```

The `Person` model is the core entity. The `birth_date` attribute is cast to a `date` (Carbon instance). Each person has exactly one language and one or more interests.

### Language

```
Language --hasMany--> Person
```

A reference table with 5 seeded languages: Afrikaans, English, isiXhosa, isiZulu, Sesotho.

### Interest

```
Interest --belongsToMany--> Person (via interest_person pivot)
```

A reference table with 8 seeded interests: Art, Cooking, Gaming, Music, Reading, Sports, Technology, Travel.

### Relationship Summary

| From     | Relation        | To       | Key / Pivot         |
|----------|-----------------|----------|---------------------|
| User     | hasMany         | Person   | `created_by`        |
| Person   | belongsTo       | User     | `created_by`        |
| Person   | belongsTo       | Language | `language_id`       |
| Language | hasMany         | Person   | `language_id`       |
| Person   | belongsToMany   | Interest | `interest_person`   |
| Interest | belongsToMany   | Person   | `interest_person`   |

---

## 9. Repositories

### BaseRepository

A generic abstract class providing reusable CRUD operations for any Eloquent model:

`save`, `insert`, `create`, `update`, `updateWhere`, `updateOrCreate`, `all`, `allWhere`, `find`, `findOneOrFail`, `firstOrCreate`, `findBy`, `findOneBy`, `delete`, `newModelInstance`

### PersonRepository

Extends `BaseRepository` with domain-specific methods:

- **`paginatedList(perPage, search)`** - Returns a paginated collection with eager-loaded `language`, `interests`, and `creator` relationships. Searches across `name`, `surname`, `email`, `sa_id_number`, and `mobile_number` columns using `LIKE` queries.
- **`createProfile(attributes)`** - Wraps person creation and interest syncing in a database transaction. After creation, fires the `PersonCaptured` event.
- **`updateProfile(id, attributes)`** - Wraps person update and interest syncing in a database transaction. Does not fire any event.

### LanguageRepository and InterestRepository

Minimal implementations that inherit all functionality from `BaseRepository`. Used only for read operations.

---

## 10. Services

### PersonService

Coordinates all business operations for people:

| Method                    | What It Does                                         |
|---------------------------|------------------------------------------------------|
| `personList(perPage, search)` | Delegates to repository for paginated, searchable list |
| `create(attributes)`     | Delegates to repository's transactional create       |
| `update(attributes, id)` | Delegates to repository's transactional update       |
| `find(id, with)`         | Retrieves a single person with optional eager loading |
| `findOneOrFail(id)`      | Retrieves or throws `ModelNotFoundException`         |
| `delete(id)`             | Deletes a person by ID                               |

### LanguageService

- **`allLanguages()`** - Returns all languages ordered alphabetically, selecting only `id` and `name`.

### InterestService

- **`allInterests()`** - Returns all interests ordered alphabetically, selecting only `id` and `name`.

---

## 11. Livewire Components

### PersonFormComponent (Abstract Base)

The shared parent for `CreatePerson` and `EditPerson`. It:

- Injects `PersonService`, `LanguageService`, and `InterestService` via `boot()`.
- Holds the `PersonForm` form object as a public property.
- Provides computed properties `languages()` and `interestOptions()` for dropdown/checkbox data.
- Provides `redirectToIndex(message)` for post-save redirection with a flash message.
- Declares public properties `$heading`, `$pageDescription`, `$icon`, and `$submitLabel` that the blade template uses for dynamic rendering.

### CreatePerson

- **Route:** `GET /people/create`
- **Extends:** `PersonFormComponent`
- **Properties:** `heading = 'Create Person'`, `icon = 'person-plus'`, `submitLabel = 'Save Person'`
- **`save()`** calls `personService->create()` with the form payload (including `created_by`), which triggers the email pipeline.
- **Renders:** `livewire.people.create-edit-form`

### EditPerson

- **Route:** `GET /people/{person}/edit`
- **Extends:** `PersonFormComponent`
- **Properties:** `heading = 'Edit Person'`, `icon = 'pencil-square'`, `submitLabel = 'Update Person'`
- **`mount(person)`** loads the existing record and fills the form via `form->fillFromPerson()`.
- **`save()`** calls `personService->update()`. No email is dispatched on update.
- **Renders:** `livewire.people.create-edit-form`

### ListPeople

- **Route:** `GET /people`
- **Uses:** `WithPagination` trait (Bootstrap theme)
- **Properties:**
  - `$search` (URL-bound via `#[Url]`) - live search term
  - `$perPage` - rows per page (10, 25, or 50)
  - `$notification` - success message displayed after create/edit/delete
  - `$pendingDeleteId` / `$pendingDeleteName` - delete confirmation modal state
- **Computed:** `people()` returns the paginated, filtered collection
- **`mount()`** picks up any `session('status')` flash (from create/edit redirects) into `$notification`, then clears it from the session so the layout does not render a duplicate alert.
- **Key methods:** `confirmDeletePerson()`, `cancelDeletePerson()`, `deletePerson()`, `dismissNotification()`
- **Renders:** `livewire.people.list-people`

### Shared View: create-edit-form.blade.php

Both `CreatePerson` and `EditPerson` render the same blade file. The view is dynamic based on the parent component's public properties (`$heading`, `$pageDescription`, `$icon`, `$submitLabel`). This eliminates duplication between the two forms, which are structurally identical.

---

## 12. Livewire Forms

### PersonForm

A Livewire `Form` object that encapsulates person form state:

| Property        | Type     | Purpose                          |
|-----------------|----------|----------------------------------|
| `personId`      | ?int     | Null for create, set for edit    |
| `name`          | string   | First name                       |
| `surname`       | string   | Last name                        |
| `sa_id_number`  | string   | South African ID (13 digits)     |
| `mobile_number` | string   | Phone number                     |
| `email`         | string   | Email address                    |
| `birth_date`    | string   | Date in Y-m-d format            |
| `language_id`   | string   | Selected language ID             |
| `interests`     | array    | Selected interest IDs            |

**Key methods:**

- **`rules()`** - Delegates to `PersonSaveRequest::rulesFor($this->personId)`, centralizing all validation rules in one place.
- **`fillFromPerson(Person)`** - Populates the form from an existing model (used by EditPerson).
- **`payload()`** - Runs validation and returns the validated data array.
- **`payloadWithCreator()`** - Adds the authenticated user's ID to the payload (used by CreatePerson).

---

## 13. Request Validation

### PersonSaveRequest

Validation rules are centralized in a single `FormRequest` class with a static `rulesFor(?personId)` method. This allows both the Livewire form and any future API endpoint to share the same rules.

| Field           | Rules                                                      |
|-----------------|-------------------------------------------------------------|
| `name`          | required, string, max:255                                   |
| `surname`       | required, string, max:255                                   |
| `sa_id_number`  | required, string, min:13, max:255, unique (ignores self)    |
| `mobile_number` | required, string, min:10, max:20                            |
| `email`         | required, email, max:255, unique (ignores self)             |
| `birth_date`    | required, date, before:today                                |
| `language_id`   | required, exists:languages,id                               |
| `interests`     | required, array, min:1                                      |
| `interests.*`   | exists:interests,id                                         |

The `unique` rules use `->ignore($personId)` so that editing a person does not conflict with their own existing values.

---

## 14. Custom Validation Rules

### SouthAfricanIdNumber

A custom rule (`app/Rules/SouthAfricanIdNumber.php`) that implements the `Rule` interface. It validates that a 13-digit SA ID number passes a Luhn-based checksum algorithm. This ensures not just length but mathematical validity of the ID number.

---

## 15. Events, Listeners, Jobs, and Mail

### Flow: Person Creation -> Email Notification

```
CreatePerson::save()
    -> PersonService::create()
        -> PersonRepository::createProfile()
            -> DB transaction: create person + sync interests
            -> fires PersonCaptured event

PersonCaptured event
    -> DispatchPersonCapturedEmail listener (EventServiceProvider)
        -> dispatches SendPersonCapturedEmailJob (ShouldQueue)

SendPersonCapturedEmailJob
    -> sends PersonCapturedMail to person's email address

PersonCapturedMail
    -> subject: "You have been captured on the system"
    -> renders: emails/person-captured.blade.php
```

This pipeline only runs on **creation**, not on update or delete.

With `QUEUE_CONNECTION=sync` and `MAIL_MAILER=log`, the email is processed immediately and written to `storage/logs/laravel.log` for review.

---

## 16. Routes and Middleware

### Route Definitions (routes/web.php)

| Method | URI                    | Handler              | Name          | Auth |
|--------|------------------------|----------------------|---------------|------|
| GET    | `/`                    | Redirect             | -             | No   |
| GET    | `/login`               | Auth\LoginController | login         | No   |
| POST   | `/login`               | Auth\LoginController | -             | No   |
| POST   | `/logout`              | Auth\LoginController | logout        | Yes  |
| GET    | `/home`                | HomeController       | home          | Yes  |
| GET    | `/people`              | ListPeople           | people.index  | Yes  |
| GET    | `/people/create`       | CreatePerson         | people.create | Yes  |
| GET    | `/people/{person}/edit`| EditPerson           | people.edit   | Yes  |

### Middleware

- **`auth`** - Applied to all `/people` routes and `/home`. Guests are redirected to the login page.
- Standard Laravel web middleware stack: EncryptCookies, StartSession, ShareErrorsFromSession, VerifyCsrfToken, SubstituteBindings.

### Root Route Logic

The root `/` URL redirects authenticated users to `people.index` and guests to the login page.

---

## 17. Frontend and Blade Templates

### Main Layout (layouts/app.blade.php)

The application uses a single Blade layout that includes:

- **Bootstrap 5.3.3** CSS and JS from CDN
- **Bootstrap Icons 1.11.3** from CDN
- **Inter** font from Google Fonts (weights 300-700)
- **Custom CSS variables** for PMS branding (`--pms-primary: #2563eb`, `--pms-nav-bg: #0f172a`, etc.)
- **Navigation bar** with the People link, username display, and logout button
- **Flash message area** for session-based notifications
- **Livewire scripts** and Bootstrap tooltip initialization

No `npm`, `webpack`, or `vite` is required. All assets are CDN-loaded.

### list-people.blade.php

The people listing page includes:

- **Notification alert** at the top for success messages (create, edit, delete)
- **Page header** with title, description, and "Add Person" button
- **Card** with a live search input (debounced 400ms) and a rows-per-page selector
- **Responsive table** with columns: Name, SA ID Number, Mobile, Email, Birth Date, Language (badge), Interests (badges with overflow tooltip), Actions (edit/delete buttons)
- **Empty state** when no records match
- **Pagination** links (Bootstrap-styled)
- **Delete confirmation modal** with a warning icon, person name, and confirm/cancel buttons

### create-edit-form.blade.php

A shared form used by both CreatePerson and EditPerson. It is divided into three card sections:

1. **Personal Information** - First name, surname, SA ID number, birth date
2. **Contact Information** - Mobile number, email address
3. **Language & Interests** - Language dropdown, interest checkbox pills

Cancel and submit buttons at the bottom. The submit button label is dynamic (`Save Person` / `Update Person`).

---

## 18. Blade Components

### Why Class-Based Components

All form components use **class-based Blade components** instead of anonymous components with `@php` blocks. This keeps PHP logic in the component class and Blade templates as pure HTML templates. The class constructor handles property computation, error resolution, and wire binding selection. The Blade file only renders markup using those pre-computed values.

### Component Classes

| Component                  | Class                                    | Purpose                                                |
|----------------------------|------------------------------------------|--------------------------------------------------------|
| `<x-form.text-input>`     | `App\View\Components\Form\TextInput`     | Text/email/date input with optional icon, label, validation error display |
| `<x-form.select-input>`   | `App\View\Components\Form\SelectInput`   | Dropdown that accepts objects, arrays, or key-value pairs as options |
| `<x-form.checkbox-multi-select>` | `App\View\Components\Form\CheckboxMultiSelect` | Inline checkbox group styled as pills |
| `<x-people.interests-badges>` | `App\View\Components\People\InterestsBadges` | Shows first 2 interest badges, with "+N" overflow tooltip |

### Anonymous Components

| Component                  | File                                         | Purpose                               |
|----------------------------|----------------------------------------------|---------------------------------------|
| `<x-headers.page-header>` | `components/headers/page-header.blade.php`   | Page title, description, icon, and slot for action buttons |
| `<x-form.search-input>`   | `components/form/search-input.blade.php`     | Live search field with icon and debounce |

### How Components Use Props and Slots

Components accept named props for data (`name`, `label`, `wire-model`, `options`) and use **slots** for nested content. For example, `<x-headers.page-header>` accepts `title`, `description`, and `icon` as props, while the action button (like "Add Person" or "Back to People") is passed through the default slot:

```blade
<x-headers.page-header title="People" description="..." icon="people">
    <a href="{{ route('people.create') }}" class="btn btn-primary">+ Add Person</a>
</x-headers.page-header>
```

### Wire Binding

Form components accept a `wire-modifier` prop (`live`, `blur`, or default) which the class resolves to the correct Livewire attribute (`wire:model.live`, `wire:model.blur`, or `wire:model`). This avoids repeating binding logic in templates.

---

## 19. Factories and Seeders

### Factories

| Factory           | Purpose                                                        |
|-------------------|----------------------------------------------------------------|
| `UserFactory`     | Generates admin users with fake name, unique email, hashed password |
| `PersonFactory`   | Generates people linked to a random user and language           |
| `LanguageFactory`  | Selects from: Afrikaans, English, isiXhosa, isiZulu, Sesotho  |
| `InterestFactory`  | Selects from: Art, Cooking, Gaming, Music, Reading, Sports, Technology, Travel |

### Seeders

| Seeder               | What It Does                                                 |
|----------------------|--------------------------------------------------------------|
| `AdminAccountSeeder` | Creates the reviewer account (`admin@pms.test` / `Password123!`) |
| `LanguageSeeder`     | Seeds 5 South African languages using `updateOrCreate`       |
| `InterestSeeder`     | Seeds 8 interests using `updateOrCreate`                     |
| `DatabaseSeeder`     | Calls all three seeders in order                             |

`updateOrCreate` prevents duplicates when re-seeding.

---

## 20. Testing with Pest

### Why Pest

Pest is a testing framework that wraps PHPUnit with a cleaner API. Instead of writing test classes with methods, tests are written as closures with `it()` or `test()`. Assertions chain naturally. Laravel's Pest plugin adds helpers like `actingAs()`, `assertRedirect()`, and Livewire test methods.

### Configuration (tests/Pest.php)

The Pest configuration file sets up the base test class and applies the `RefreshDatabase` trait, which means every test runs against a fresh database (migrations run, then rollback after each test). This ensures tests are isolated and do not depend on prior state.

### Test Suite Overview

```
Tests:    46 passed (239 assertions)
Duration: ~4 seconds
Status:   ALL PASSING
```

### Test Files and What They Cover

#### Unit Tests

**SouthAfricanIdNumberTest** (7 tests)

| Test                                           | What It Validates                              |
|------------------------------------------------|------------------------------------------------|
| Accepts a valid ID number                      | A correctly checksummed 13-digit ID passes     |
| Rejects an invalid checksum                    | A 13-digit ID with wrong checksum fails        |
| Rejects too short value                        | Fewer than 13 digits fails                     |
| Rejects too long value                         | More than 13 digits fails                      |
| Rejects value containing letters               | Non-numeric characters fail                    |
| Rejects empty string                           | Blank input fails                              |
| Returns the expected error message             | Error message is correct                       |

#### Feature Tests

**LoginTest** (4 tests)

| Test                                           | What It Validates                              |
|------------------------------------------------|------------------------------------------------|
| Guest can view the login page                  | Login page renders for unauthenticated users   |
| User can log in with valid credentials         | Correct email/password authenticates           |
| User cannot log in with invalid credentials    | Wrong password is rejected                     |
| Authenticated user is redirected away           | Already logged-in users skip the login page    |

**CreatePersonTest** (11 tests)

| Test                                           | What It Validates                              |
|------------------------------------------------|------------------------------------------------|
| Guest is redirected to login                   | Auth middleware works                          |
| User can view the create page                  | Form renders with navigation elements          |
| Valid payload creates person and queues email   | Full happy path: DB record + email job         |
| Required field errors on empty submit          | All fields show required errors                |
| Minimum length validation (ID, mobile)         | sa_id_number min:13, mobile min:10             |
| Maximum length validation (names)              | name and surname max:255                       |
| Email format validation                        | Invalid email format is rejected               |
| Birth date must be past date                   | Future and today's date are rejected           |
| Unique email and SA ID number                  | Duplicates are rejected                        |
| Language and interests must exist in DB         | Foreign key validation works                   |
| At least one interest required                 | Empty interests array is rejected              |

**EditPersonTest** (14 tests)

| Test                                           | What It Validates                              |
|------------------------------------------------|------------------------------------------------|
| Guest is redirected to login                   | Auth middleware works                          |
| User can view edit page with mounted values    | Existing data pre-fills the form               |
| 404 for missing record                         | Non-existent person returns 404                |
| Valid update and redirect                      | Happy path: update + redirect + flash          |
| No email job queued on update                  | Email only fires on create, not update         |
| Required field errors on empty submit          | All validation rules still apply               |
| Allows keeping current unique values           | Own email/ID does not conflict with self       |
| Unique values rejected against other records   | Duplicate with another person is rejected      |
| Minimum length validation                      | Same rules as create                           |
| Maximum length validation                      | Same rules as create                           |
| Email format validation                        | Same rules as create                           |
| Birth date validation                          | Same rules as create                           |
| Language and interests must exist              | Same rules as create                           |
| At least one interest required                 | Same rules as create                           |

**DeletePersonTest** (4 tests)

| Test                                           | What It Validates                              |
|------------------------------------------------|------------------------------------------------|
| Guest is redirected to login                   | Auth middleware works                          |
| Can open and cancel delete confirmation        | Modal state management works                   |
| Can delete a person with success notification  | Record removed, notification displayed         |
| No-op when no target selected                  | Calling delete without a target does nothing   |

**ListPeopleTest** (7 tests)

| Test                                           | What It Validates                              |
|------------------------------------------------|------------------------------------------------|
| Guest is redirected to login                   | Auth middleware works                          |
| User can view page with table and search       | All UI elements render correctly               |
| Empty state when no people exist               | Shows "No people found" message                |
| Search by name, surname, email, mobile, ID     | Live search filters across all searchable columns |
| Pagination and per-page changing               | Results paginate and per-page selector works   |
| Flash notification display and dismissal       | Success messages appear and can be closed      |
| Logout functionality                           | User can log out from the application          |

### Running Tests

```bash
php artisan test
```

All 46 tests pass with 239 assertions in approximately 4 seconds.

---

## 21. Virtual Host Deployment

The application is compatible with standard Apache or Nginx virtual host deployments:

1. Point the document root to the `public` directory
2. Keep the Laravel project outside the public web root (only `public/` is exposed)
3. Set `APP_URL` in `.env` to the final hostname
4. Ensure the web server can write to `storage/` and `bootstrap/cache/`

No frontend build step is required since all assets are loaded from CDNs.

---

## 22. Verification Checklist

```bash
# 1. Fresh setup
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed

# 2. Run the test suite
php artisan test
# Expected: 46 passed (239 assertions)

# 3. Start the application
php artisan serve --host=127.0.0.1 --port=8080

# 4. Manual verification
# - Log in with admin@pms.test / Password123!
# - Create a person (fill all fields, select language and interests)
# - Verify success notification and redirect
# - Check storage/logs/laravel.log for the email entry
# - Edit the person and verify fields are pre-populated
# - Search by name, email, mobile, or SA ID number
# - Change rows per page and verify pagination
# - Delete a person using the confirmation modal
# - Verify notification messages appear consistently below the navbar
# - Log out and verify redirect to login page
```
