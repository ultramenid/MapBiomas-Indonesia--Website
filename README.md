# MapBiomas Indonesia - Website

### Technology stack

- Laravel
- Laravel-Livewire
- AlpineJS
- TailwindCss
- MySQL

### How to use

- clone this repository
- composer install & npm install
- cp .env.example .env
- php artisan key:generate
- php artisan migrate --seed
- php artisan storage:link

### CMS

The admin panel lives at `/cms/login` (Vercel-style UI, light & dark theme).

Default seeded accounts:

| Email | Password (Local) | Password (Production) | Role |
|---|---|---|---|
| admin@mapbiomas.id | `password` | Set via `SEED_ADMIN_PASSWORD` in `.env` | Admin — full access, manages users |
| editor@mapbiomas.id | `password` | Set via `SEED_EDITOR_PASSWORD` in `.env` | Editor — manages content only |

The CMS manages: Pages (About), News (internal articles + external coverage links), FAQ,
Team (technical & Scientific Advisory), Initiatives (Landy / Fire / Alerta platforms),
Partners (footer co-creators & supporters), Media Library, and Users.

### Database Seeding for Production

To safely run migrations and seed all initial data (Pages, News, Team, Initiatives, Partners, Users) on production:

1. In `.env`, provide strong passwords:
   ```env
   SEED_ADMIN_PASSWORD="YourStrongAdminPasswordHere!"
   SEED_EDITOR_PASSWORD="YourStrongEditorPasswordHere!"
   ```
2. Run migration and database seeding:
   ```bash
   php artisan migrate --force
   php artisan db:seed --force
   php artisan storage:link
   ```

Rich-text editors (TinyMCE) use the native, secure Media Manager at `/cms/media` and `/cms/media/upload`.

## Screenshot

![](https://i.imgur.com/Jztfg3x.png)
