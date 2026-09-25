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

Default seeded accounts (change the passwords immediately):

| Email | Password | Role |
|---|---|---|
| admin@mapbiomas.id | `password` | Admin — full access, manages users |
| editor@mapbiomas.id | `password` | Editor — manages content only |

The CMS manages: Pages (About), News (internal articles + external coverage links), FAQ,
Team (technical & Scientific Advisory), Initiatives (Landy / Fire / Alerta platforms),
Partners (footer co-creators & supporters) and Users.

TinyMCE rich-text editors use the Laravel Filemanager at `/cms/fire-filemanager`
(uses the `public` disk — run `php artisan storage:link`).

## Screenshot

![](https://i.imgur.com/Jztfg3x.png)
