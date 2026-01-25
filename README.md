# Laravel Filament Lab

This project is a laboratory for implementations of Laravel 12, Filament v5, and a custom RBAC (Role-Based Access Control) system.

## Requirements

- PHP 8.3.6+
- Node.js & NPM
- Composer
- Database (PostgreSQL/SQLite)

## Installation

1. Clone the repository.
2. Install PHP dependencies:
   ```bash
   composer install
   ```
3. Install Node dependencies:
   ```bash
   npm install && npm run build
   ```
4. Copy the `.env.example` file to `.env` and configure your variables:
   ```bash
   cp .env.example .env
   ```
5. Generate the application key:
   ```bash
   php artisan key:generate
   ```

## Database and Permissions

To set up the database from scratch with all users, roles, and permissions:

1. **Run Migrations and Seeders:**
   ```bash
   php artisan migrate:fresh --seed
   ```
   *The `UserSeeder` will automatically create the user `admin@admin.com` with the password `password`.*

2. **Sync Permissions (CRITICAL):**
   ```bash
   php artisan sync:permissions
   ```
   This command performs the following:
   - Creates Roles (`super_admin`, `user`) if they don't exist.
   - Generates permissions based on system models (e.g., `view_projects`, `create_projects`).
   - Assigns permissions to roles based on the `config/rbac.php` file.
   - Assigns the `super_admin` role to the user `admin@admin.com`.

## RBAC Structure

The system uses the `spatie/laravel-permission` package with a simplified configuration layer.

- **Rule Configuration:** `config/rbac.php`
- **Role Enums:** `App\Modules\Permissions\Enums\Roles`
- **Permission Enums:** `App\Modules\Permissions\Enums\PermissionsEnum`

### Super Admin
Has full access to all features. `AppServiceProvider` defines a `Gate::before` that authorizes any action for users with this role.

### Normal User
Has restricted permissions defined in `config/rbac.php`. Policies (e.g., `ProjectPolicy`) ensure that a user can only manage their own resources.

## Useful Commands

- **Sync Permissions:** `php artisan sync:permissions`
- **Code Formatting:** `vendor/bin/pint`
- **Run Tests:** `php artisan test`

## Testing

A set of tests has been implemented to ensure that the RBAC works as expected:
```bash
php artisan test tests/Feature/Permissions/RbacTest.php
```

The tests validate:
1. If the Super Admin can see all projects.
2. If a normal user only sees their own projects.
3. If project creation respects the assigned permissions.
