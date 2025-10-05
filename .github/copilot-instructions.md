# Copilot Instructions for ECV-Whatsinthebox

## Project Overview

ECV-Whatsinthebox is a Laravel-based web application designed for managing and displaying movies, shows, and related metadata. The project leverages Laravel's MVC architecture, Blade templates for views, and Tailwind CSS for responsive design. It integrates BladewindUI components for consistent UI elements.

### Key Components

-   **Models**: Represent core entities like `Movie`, `Show`, `Actor`, `Genre`, etc., located in `app/Models/`.
-   **Controllers**: Handle HTTP requests and business logic, located in `app/Http/Controllers/`.
-   **Views**: Blade templates for rendering UI, located in `resources/views/`.
-   **Migrations**: Define database schema, located in `database/migrations/`.
-   **Seeders**: Populate the database with initial data, located in `database/seeders/`.

## Developer Workflows

### Setting Up the Project

1. Install dependencies:
    ```bash
    composer install
    npm install
    ```
2. Set up the environment file:
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
3. Run migrations and seed the database:
    ```bash
    php artisan migrate --seed
    ```
4. Build frontend assets:
    ```bash
    npm run dev
    ```

### Running the Application

-   Start the development server:
    ```bash
    php artisan serve
    ```
-   Access the application at `http://localhost:8000`.

### Testing

-   Run tests using Pest:
    ```bash
    ./vendor/bin/pest
    ```

## Project-Specific Conventions

### Blade Templates

-   Use BladewindUI components (e.g., Heroicons) for consistent design.
-   Follow Tailwind CSS conventions for responsive layouts.
-   Example: Movie cards in `resources/views/components/movie-card.blade.php` use dynamic titles and responsive grids.

### Database

-   Use migrations for schema changes and seeders for test data.
-   Example: `2025_09_16_143131_create_movie_table.php` defines the `movies` table schema.

### Routing

-   Define routes in `routes/web.php` for web-facing pages.
-   Use controller methods for route actions, e.g., `MovieController@index`.

### External Dependencies

-   **BladewindUI**: For UI components.
-   **Tailwind CSS**: For styling.
-   **Pest**: For testing.

## Integration Points

-   **Frontend**: Tailwind CSS and JavaScript for dynamic interactions.
-   **Backend**: Laravel Eloquent ORM for database operations.
-   **Testing**: Pest for unit and feature tests.

## Examples

### Adding a New Model

1. Create the model:
    ```bash
    php artisan make:model Example
    ```
2. Create a migration:
    ```bash
    php artisan make:migration create_examples_table
    ```
3. Define relationships in the model, e.g., `hasMany` or `belongsTo`.

### Adding a New Blade Component

1. Create the component:
    ```bash
    php artisan make:component ExampleComponent
    ```
2. Use the component in a Blade template:
    ```blade
    <x-example-component />
    ```

## Notes

-   Follow Laravel's [official documentation](https://laravel.com/docs) for framework-specific guidance.
-   Ensure all new features are responsive and tested across devices.
