# Portfolio — Laravel 13 + Filament v5 + Inertia + Vue 3 (AR/EN, RTL/LTR)

Bilingual (Arabic RTL / English LTR) portfolio with admin-managed profile via Settings,
and content modules: Projects, Services, Skills, Languages, Coursera Certificates, Education.

## Stack
- Laravel 13 (PHP 8.3+), SQLite by default (switchable to MySQL)
- Filament v5 admin (`/admin`): Shield roles, LanguageSwitch (en/ar), Translatable fields (en/ar),
  Phone input, Spatie Media Library, Spatie Settings
- Inertia v3 + Vue 3 + vue-i18n + shadcn-style UI + Tailwind v4, dark + gold premium design

> Note: `composer require filament/filament` resolves to Filament **v5.8** (latest, Laravel 13 compatible),
> so `bezhansalleh/filament-language-switch` resolved to **v5.0** (the v5 equivalent of the requested 4.x branch).
> All other requested plugins installed at their v5-compatible releases.

## Quick start
```bash
composer install
cp .env.example .env   # or edit existing .env (APP_NAME=Portfolio, DB_CONNECTION=sqlite)
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
npm install
npm run build   # or npm run dev
php artisan serve
```

- Frontend: http://localhost:8000 (add `?locale=ar` for Arabic RTL)
- Admin: http://localhost:8000/admin — `admin@portfolio.test` / `password` (super_admin)

## Admin editing
- **Profile & contact** (first/last name, logo, personal image, email, phone, WhatsApp,
  professional summary, about us, socials, CV, SEO): `/admin/manage-site-settings`
- **Content**: `/admin/projects`, `/admin/services`, `/admin/skills`, `/admin/language-items`,
  `/admin/certificates`, `/admin/education`, `/admin/contact-messages`
- **Access control**: `/admin/shield/roles` (Shield, group "Settings & Security")

## 3D scenes (three.js) — controlled from the admin
- **Portfolio → 3D Models** (`/admin/scene-models`): add/edit/duplicate/reorder models per placement
  (`hero`, `about`, `skills`, `projects`, `contact`). Built-in shapes: torus knot, crystal, diamond, sphere,
  ring, cube, atom, planet, server rack, laptop, `</>` brackets, database — or upload your own `.glb`.
  Sliders control color/glow, metalness, roughness, opacity, wireframe, scale, X/Y/Z position,
  rotation speed, floating, mouse-follow, and whether visitors can drag-rotate it.
- **Site Settings → 3D scene**: global on/off, particles on/off, particles color, accent light color.
- Frontend: `resources/js/lib/three/{engine,shapes}.js` + `components/three/ThreeStage.vue`
  (lazy-loaded chunk, pauses off-screen, respects reduced motion, falls back when WebGL is unavailable).

## CV content
`php artisan db:seed --class=PortfolioSeeder` loads Abdulrahman Hijazi's CV (profile, photo, CV PDF,
experience, education, skills, languages, Coursera certificates, services) and the 25 projects built at
Vega (plus the graduation project and the 3NOW app). Re-running updates records in place.

## Routes
- `GET /`, `/{locale}` (home), `/about`, `/projects`, `/projects/{project:slug}`, `/contact`
- `POST /contact` → validates + stores `contact_messages`, Inertia flash `success`

## Tests / build
```bash
php artisan test    # 3 passed (/, /projects)
npm run build       # vite build OK (~6s, chunk >500KB warning only)
```

## Project map
- `app/Settings/SiteSettings.php` + `database/settings/*` — profile/settings
- `app/Models/{Project,Service,Skill,LanguageItem,Certificate,Education,ContactMessage}.php`
- `app/Filament/Resources/*` + `app/Filament/Pages/ManageSiteSettings.php`
- `app/Http/Controllers/PortfolioController.php` + `app/Http/Middleware/SetLocale.php`
  + `HandleInertiaRequests.php` (shares `locale`/`dir`/`flash`)
- `resources/js/{app.js,i18n.js,lang/en.json,lang/ar.json,Pages/Home|About|Projects/Index|Projects/Show|Contact.vue,components/...}`
- `resources/views/app.blade.php`, `resources/css/app.css` (gold theme, Cairo/Tajawal + Inter, RTL fixes)
