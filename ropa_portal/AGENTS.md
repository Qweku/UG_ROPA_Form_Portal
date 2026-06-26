# AGENTS.md - RoPA Form Portal

## Project Setup
- PHP 8.3+, Laravel 13.x
- Run `composer install` to install dependencies
- Run `php artisan key:generate` if `.env` not set up

## Code Style
- Run `vendor/bin/pint` to fix code style (Laravel Pint)

## Validation
- Run `php -l <file>` to check PHP syntax

## Routes
- All RoPA routes under `/ropa/*` are defined in `routes/web.php`
- Use `php artisan route:list --path=ropa` to view available routes

## View Hierarchy
- `form.blade.php` contains the progress stepper and wraps all step views
- Step views in `resources/views/ropa/steps/step{N}.blade.php` are included via `@include`
- `step1` now correctly uses `form.blade.php` (fixed from direct render)

## Debugging Checklist: CSS Styles for Steps Component

### 1. Verify CSS File Exists and is Linked
- **File location:** `public/assets/css/ropa.css` (line 20 in `app.blade.php`)
- **Key classes:** `.step-wrapper`, `.progress-stepper`, `.step-item`, `.step-label`, `.step-lock`

### 2. Verify Blade Template Inclusion
- Controller must return `view('ropa.form')` for all steps 1-14
- `form.blade.php` (lines 7-69) renders the progress stepper HTML
- Step templates are included via `@include("ropa.steps.step{$step}")` (line 109)

### 3. Check Controller Logic
- `RopaFormController::edit()` must pass `$colleges` and `$basicInfoLocked` variables
- `$basicInfoLocked` is computed via `$parentForm->basicInfoLocked()`

### 4. Stack/Push Directives
- Step templates use `@push('scripts')` for step-specific JavaScript
- `@stack('scripts')` in `app.blade.php` (line 248) renders pushed scripts
- Stack merging works correctly when templates are included via `@include`