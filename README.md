<div align="center">

<img src="https://capsule-render.vercel.app/api?type=waving&color=0:ca8a04,50:eab308,100:facc15&height=180&section=header&text=Protfilo&fontSize=56&fontColor=1f2937&fontAlignY=38&desc=Bilingual+Portfolio+%7C+Laravel+13+%2B+Filament+5+%2B+three.js&descSize=20&descAlignY=62&fontColor=404040&animation=fadeIn"/>

[![Laravel](https://img.shields.io/badge/Laravel-13-FF2D20?style=flat&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.3+-777BB4?style=flat&logo=php&logoColor=white)](https://php.net)
[![Filament](https://img.shields.io/badge/Filament-5-FF3D2E?style=flat&logo=laravel&logoColor=white)](https://filamentphp.com)
[![Vue](https://img.shields.io/badge/Vue-3-4FC08D?style=flat&logo=vuedotjs&logoColor=white)](https://vuejs.org)
[![three.js](https://img.shields.io/badge/three.js-WebGL-049EF4?style=flat&logo=threedotjs&logoColor=white)](https://threejs.org)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-4-06B6D4?style=flat&logo=tailwindcss&logoColor=white)](https://tailwindcss.com)
[![Inertia.js](https://img.shields.io/badge/Inertia-3-9553E9?style=flat&logo=inertia.js&logoColor=white)](https://inertiajs.com)

</div>

---

## 📖 Overview

**Protfilo** is my bilingual personal portfolio website — **Arabic (RTL) / English (LTR)** — with a **Filament 5 admin** to manage every piece of content, and **admin-controlled 3D three.js scenes** on every page section. Dark + gold premium design.

## ✨ Features

- 🌍 **Bilingual AR-RTL / EN-LTR** — vue-i18n, `?locale=ar`, Cairo/Tajawal + Inter fonts, automatic `dir` switching
- 🎛️ **Filament 5 admin** (`/admin`) — Shield roles (super_admin), LanguageSwitch plugin, translatable fields (en/ar), phone input, Spatie Media Library + Settings
- 🧊 **3D scenes, fully editable from admin** — per-placement models (`hero`, `about`, `skills`, `projects`, `contact`): built-in shapes (torus knot, crystal, diamond, atom, server rack, laptop, `</>`, database…) **or upload your own `.glb`**
  - Sliders for color / glow, metalness, opacity, wireframe, scale, position, rotation
  - Floating animation, mouse-follow, visitor drag-rotate, particles
  - Lazy-loaded `ThreeStage.vue`, pauses off-screen, respects `prefers-reduced-motion`, WebGL fallback
- 📚 **Content modules** — Projects (slug-based detail pages), Services, Skills, Languages, Certificates, Education, Experiences, Contact messages, SceneModels (3D), Site Settings
- 📇 **Profile & contact settings** — names, logo, image, email, phone, WhatsApp, summary, about, socials, CV PDF, SEO
- 📬 **Contact form** — validated → `contact_messages` table → admin inbox with flash success
- 📜 **CV seeder** — `PortfolioSeeder` seeds a full profile (experience, education, skills, languages, certificates, 25+ projects), idempotent on re-run

## 🛠️ Tech Stack

| Layer | Tech |
|---|---|
| Backend | Laravel 13 (PHP 8.3+, SQLite default / MySQL switchable) |
| Admin | Filament v5 + Shield, LanguageSwitch, Translatable, Phone Input, Spatie Media Library / Settings |
| Frontend | Inertia v3 + Vue 3 + vue-i18n, shadcn-style UI, Tailwind CSS v4 |
| 3D | three.js (lazy-loaded, admin-configurable scenes) |

## 🚀 Installation

```bash
# 1) PHP dependencies
composer install

# 2) Frontend dependencies
npm install

# 3) Environment
cp .env.example .env
php artisan key:generate

# 4) Database + seed (seeds your CV data)
php artisan migrate --seed

# 5) Storage link (media/CV)
php artisan storage:link

# 6) Run
npm run dev        # terminal 1
php artisan serve  # terminal 2
```

- **Public site:** `/` (or `/{locale}`) · `/about` · `/projects` · `/projects/{slug}` · `/contact`
- **Admin:** `/admin`

## ✅ Verification

```bash
php artisan test   # 3 passed
npm run build      # clean build
```

---

<div align="center">

**Built with ❤️ by [Abdulrahman Hijazi](https://github.com/abdulrahmanHijazi)**

</div>
