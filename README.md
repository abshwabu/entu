# Entu Creative Portfolio

Dynamic portfolio website for **Entu Creative**, a graphic designer. Visitors can browse her work on the public-facing site, and she manages portfolio projects, media, and site content from the Filament admin panel.

## Tech Stack

- **Framework:** Laravel 12
- **Database:** SQLite (WAL mode, foreign key constraints enabled, busy timeout 5000ms)
- **Frontend:** Blade, Tailwind CSS (with `@tailwindcss/typography` & `@tailwindcss/forms`), Alpine.js, Vite
- **Admin Panel:** Filament 3
- **Media Management:** Spatie Laravel MediaLibrary & Intervention Image
- **Utilities:** Spatie Laravel Sluggable, Spatie Laravel Sitemap

---

## Prerequisites

- PHP >= 8.2 with `pdo_sqlite`, `gd`, and `exif` extensions enabled
- Composer >= 2.x
- Node.js >= 18.x and npm

---

## Installation & Setup

1. **Clone the repository:**
   ```bash
   git clone https://github.com/abshwabu/entu.git entu-creative
   cd entu-creative
   ```

2. **Install PHP dependencies:**
   ```bash
   composer install
   ```

3. **Install JavaScript dependencies:**
   ```bash
   npm install
   ```

4. **Configure environment:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *Note: Ensure `DB_DATABASE` points to the absolute path of `database/database.sqlite`.*

5. **Initialize SQLite database:**
   ```bash
   touch database/database.sqlite
   ```

6. **Run database migrations:**
   ```bash
   php artisan migrate
   ```

7. **Create storage symlink:**
   ```bash
   php artisan storage:link
   ```

8. **Create an admin user for Filament:**
   ```bash
   php artisan make:filament-user
   ```

9. **Start development servers:**
   In two separate terminal windows or using concurrently:
   ```bash
   php artisan serve
   ```
   ```bash
   npm run dev
   ```

---

## Verification

### 1. Verify SQLite PRAGMAs in Tinker
Run:
```bash
php artisan tinker --execute="dump(DB::select('PRAGMA foreign_keys;')); dump(DB::select('PRAGMA journal_mode;'));"
```
Output should indicate `foreign_keys: 1` and `journal_mode: "wal"`.

### 2. Access Admin Panel
Navigate to [http://localhost:8000/admin](http://localhost:8000/admin) to access the Filament admin login page.

### 3. Run Automated Tests
```bash
php artisan test
```

---

## Project Structure Overview

- `app/Http/Controllers/Public`: Controllers for visitor-facing pages and portfolio showcases.
- `app/Models`: Eloquent models with MediaLibrary and Sluggable traits.
- `app/Providers/Filament`: Filament Admin Panel provider and configurations.
- `resources/views/layouts`: Base layout templates for public pages.
- `resources/views/pages`: Specific page views (e.g. portfolio, about, contact).
- `resources/views/components`: Reusable Blade UI components.
- `resources/views/partials`: Shared partial views (header, footer, navigation).
