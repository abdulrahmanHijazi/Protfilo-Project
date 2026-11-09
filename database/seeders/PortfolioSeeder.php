<?php

namespace Database\Seeders;

use App\Models\Certificate;
use App\Models\Education;
use App\Models\Experience;
use App\Models\LanguageItem;
use App\Models\Project;
use App\Models\SceneModel;
use App\Models\Service;
use App\Models\Skill;
use App\Models\User;
use App\Settings\SiteSettings;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

/**
 * Seeds the portfolio with Abdulrahman Hijazi's CV data and the projects built at Vega.
 * Safe to re-run: every record is matched by a stable key and updated in place.
 */
class PortfolioSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@portfolio.test'],
            ['name' => 'Admin', 'password' => Hash::make('password')]
        );

        if (! $admin->hasRole('super_admin')) {
            $admin->assignRole('super_admin');
        }

        $this->removeDemoContent();
        $this->seedSettings();
        $this->seedExperiences();
        $this->seedEducation();
        $this->seedSkills();
        $this->seedLanguages();
        $this->seedCertificates();
        $this->seedServices();
        $this->seedProjects();
        $this->seedSceneModels();
    }

    private function removeDemoContent(): void
    {
        Project::whereIn('slug', ['portfolio-website', 'ecommerce-app'])->get()->each->delete();
        Certificate::where('credential_id', 'DEMO-001')->delete();
        Education::where('degree->en', 'B.Sc. Computer Science')->delete();
        Skill::whereIn('name->en', ['Flutter', 'Docker', 'Vue.js'])->delete();
        Service::whereIn('title->en', ['Web Development', 'API Development'])->delete();
    }

    private function copyToPublic(string $source, string $target): ?string
    {
        $path = database_path("seeders/{$source}");

        if (! is_file($path)) {
            return null;
        }

        Storage::disk('public')->put($target, file_get_contents($path));

        return $target;
    }

    private function seedSettings(): void
    {
        $settings = app(SiteSettings::class);

        $settings->first_name = ['en' => 'Abdulrahman', 'ar' => 'عبد الرحمن'];
        $settings->last_name = ['en' => 'Hijazi', 'ar' => 'حجازي'];
        $settings->job_title = ['en' => 'Backend Developer — PHP / Laravel', 'ar' => 'مطور Backend – PHP / Laravel'];
        $settings->professional_summary = [
            'en' => 'Software engineer and backend developer with 3+ years of experience designing and building scalable web systems and RESTful APIs with PHP, Laravel and MySQL.',
            'ar' => 'مهندس برمجيات ومطور Backend بخبرة تزيد عن 3 سنوات في تصميم وتطوير أنظمة الويب وواجهات RESTful API القابلة للتوسع باستخدام PHP وLaravel وMySQL.',
        ];
        $settings->about_us = [
            'en' => "I'm a backend engineer with 3+ years of experience designing and building scalable web systems and RESTful APIs with PHP, Laravel and MySQL.\n\nI have shipped production platforms in e-learning, healthcare, delivery and e-commerce. I'm experienced in requirements analysis and system design with UML, writing clean, tested code, and working in distributed remote teams.",
            'ar' => "مهندس برمجيات ومطور Backend بخبرة تزيد عن 3 سنوات في تصميم وتطوير أنظمة الويب وواجهات RESTful API القابلة للتوسع باستخدام PHP وLaravel وMySQL.\n\nنفذت منصات إنتاجية في مجالات التعليم الإلكتروني والرعاية الصحية والتوصيل والأسواق الإلكترونية، مع خبرة في تحليل المتطلبات وتصميم الأنظمة باستخدام UML، وكتابة كود نظيف مختبر، والعمل ضمن فرق موزعة عن بعد.",
        ];
        $settings->email = 'eng.abdulrahman.hijazi@gmail.com';
        $settings->phone = '+963954557172';
        $settings->whatsapp = '+963954557172';
        $settings->location = ['en' => 'Damascus, Syria', 'ar' => 'دمشق، سوريا'];
        $settings->gitlab = 'https://gitlab.com/AbdulrahmanHijazi';
        $settings->linkedin = 'https://linkedin.com/in/abdulrahman-hijazi';
        $settings->github = null;
        $settings->x_twitter = null;
        $settings->facebook = null;
        $settings->instagram = null;
        $settings->available_for_work = true;
        $settings->years_experience = 3;
        $settings->enable_3d = true;
        $settings->scene_particles = true;
        $settings->scene_follow_theme = true;
        $settings->particles_color = '#E6C65A';
        $settings->scene_bloom_color = '#C9A227';
        $settings->seo_title = ['en' => 'Abdulrahman Hijazi — Backend Developer', 'ar' => 'عبد الرحمن حجازي — مطور Backend'];
        $settings->seo_description = [
            'en' => 'Portfolio of Abdulrahman Hijazi, a Laravel backend developer building scalable APIs, admin dashboards and real-time platforms.',
            'ar' => 'معرض أعمال عبد الرحمن حجازي، مطور Backend بلارافيل يبني واجهات API ولوحات تحكم ومنصات فورية قابلة للتوسع.',
        ];

        if ($image = $this->copyToPublic('abdulrahman.png', 'settings/abdulrahman.png')) {
            $settings->personal_image = $image;
        }

        if ($cv = $this->copyToPublic('Abdulrahman_Hijazi_CV.pdf', 'cv/Abdulrahman_Hijazi_CV.pdf')) {
            $settings->cv_url = '/storage/'.$cv;
        }

        $settings->save();
    }

    private function seedExperiences(): void
    {
        $items = [
            [
                'company' => 'Vega',
                'title' => ['en' => 'Backend Developer', 'ar' => 'مطور Backend'],
                'location' => ['en' => 'Istanbul, Turkey (Remote)', 'ar' => 'إسطنبول، تركيا (عن بعد)'],
                'description' => [
                    'en' => 'Building and maintaining production backends, APIs and admin dashboards for web and mobile products across e-learning, healthcare, delivery, mobility and marketplaces.',
                    'ar' => 'بناء وصيانة الأنظمة الخلفية وواجهات API ولوحات التحكم لمنتجات ويب وموبايل في التعليم الإلكتروني والرعاية الصحية والتوصيل والنقل والأسواق الإلكترونية.',
                ],
                'highlights' => [
                    'en' => implode("\n", [
                        'Designed, built and documented secure RESTful APIs serving web and mobile apps across several production platforms.',
                        'Built an 18-module Modular Monolith with Laravel, Inertia.js, Vue 3, Redis/Horizon and Laravel Reverb.',
                        'Optimised database performance with complex Eloquent queries, raw SQL and MySQL indexing to speed up Filament dashboards.',
                        'Implemented authentication and authorisation with Laravel Sanctum/Passport, middleware and role-based permissions.',
                        'Integrated Stripe payments, Firebase Cloud Messaging, Vimeo videos and real-time chat over WebSockets.',
                        'Wrote PHPUnit/Pest tests, handled production issues and collaborated with product, frontend and QA teams in sprints.',
                    ]),
                    'ar' => implode("\n", [
                        'تصميم وبناء وتوثيق واجهات RESTful API آمنة تخدم تطبيقات الويب والموبايل في عدة منصات إنتاجية.',
                        'بناء نظام Modular Monolith مكون من 18 وحدة باستخدام Laravel وInertia.js وVue 3 وRedis/Horizon وLaravel Reverb.',
                        'تحسين أداء قواعد البيانات عبر استعلامات Eloquent ORM معقدة وSQL خام وفهرسة MySQL لتسريع لوحات التحكم في Filament.',
                        'تنفيذ أنظمة مصادقة وتفويض باستخدام Laravel Sanctum/Passport وMiddleware وصلاحيات حسب الأدوار.',
                        'دمج مدفوعات Stripe وإشعارات Firebase Cloud Messaging وفيديوهات Vimeo والدردشة الفورية عبر WebSockets.',
                        'كتابة اختبارات PHPUnit/Pest ومعالجة مشكلات الإنتاج، والتعاون مع فرق المنتج وFrontend وQA ضمن Sprints.',
                    ]),
                ],
                'techs' => ['Laravel', 'PHP', 'MySQL', 'Filament', 'Laravel Nova', 'Inertia.js', 'Vue 3', 'Redis', 'Horizon', 'Reverb', 'Stripe', 'FCM', 'Pest'],
                'start_date' => '2024-12-01',
                'end_date' => null,
                'current' => true,
                'sort' => 1,
            ],
            [
                'company' => '3Now / Mos Gruppe',
                'title' => ['en' => 'Android Developer', 'ar' => 'مطور تطبيقات Android'],
                'location' => ['en' => 'Damascus, Syria', 'ar' => 'دمشق، سوريا'],
                'description' => [
                    'en' => 'Developed and maintained the 3NOW delivery app with Kotlin, published on Google Play.',
                    'ar' => 'تطوير وصيانة تطبيق 3NOW لخدمات التوصيل باستخدام Kotlin، والمنشور على متجر Google Play.',
                ],
                'highlights' => [
                    'en' => implode("\n", [
                        'Integrated real-time order tracking, scheduled booking and secure in-app payment.',
                        'Consumed RESTful APIs with local storage via Room/SQLite, fixed bugs and improved app performance.',
                    ]),
                    'ar' => implode("\n", [
                        'دمج ميزات التتبع الفوري للطلبات والحجز المسبق والدفع الآمن داخل التطبيق.',
                        'استهلاك واجهات RESTful API والتخزين المحلي باستخدام Room/SQLite، ومعالجة الأخطاء وتحسين أداء التطبيق.',
                    ]),
                ],
                'techs' => ['Kotlin', 'Android', 'Room', 'SQLite', 'REST APIs', 'Google Play'],
                'start_date' => '2023-01-01',
                'end_date' => '2023-12-31',
                'current' => false,
                'sort' => 2,
            ],
            [
                'company' => 'Freelance',
                'title' => ['en' => 'Network Technician', 'ar' => 'فني شبكات'],
                'location' => ['en' => 'Damascus, Syria', 'ar' => 'دمشق، سوريا'],
                'description' => [
                    'en' => 'Designed, installed and maintained small-business and home networks.',
                    'ar' => 'تصميم وتركيب وصيانة شبكات المنازل والشركات الصغيرة.',
                ],
                'highlights' => ['en' => '', 'ar' => ''],
                'techs' => ['Networking', 'Troubleshooting'],
                'start_date' => '2019-01-01',
                'end_date' => '2022-12-31',
                'current' => false,
                'sort' => 3,
            ],
            [
                'company' => 'Al-Huda Islamic Foundation',
                'title' => ['en' => 'IT Support Technician', 'ar' => 'فني دعم تقني IT'],
                'location' => ['en' => 'Damascus, Syria', 'ar' => 'دمشق، سوريا'],
                'description' => [
                    'en' => 'Provided technical support for staff devices, systems and the internal network.',
                    'ar' => 'تقديم الدعم التقني لأجهزة الموظفين والأنظمة والشبكة الداخلية في مؤسسة الهدى الإسلامية.',
                ],
                'highlights' => ['en' => '', 'ar' => ''],
                'techs' => ['IT Support', 'Hardware', 'Networking'],
                'start_date' => '2016-01-01',
                'end_date' => '2018-12-31',
                'current' => false,
                'sort' => 4,
            ],
        ];

        foreach ($items as $item) {
            Experience::updateOrCreate(['company' => $item['company']], $item);
        }
    }

    private function seedEducation(): void
    {
        Education::updateOrCreate(['degree->en' => 'Bachelor of Informatics Engineering'], [
            'degree' => ['en' => 'Bachelor of Informatics Engineering', 'ar' => 'بكالوريوس هندسة المعلوماتية'],
            'school' => ['en' => 'Damascus University, Syria', 'ar' => 'جامعة دمشق، سوريا'],
            'field' => ['en' => 'Software Engineering', 'ar' => 'هندسة البرمجيات'],
            'description' => [
                'en' => 'Graduation project: "Aqar & Service" — led the team, analysed and designed the system with UML, and built the Laravel backend (WebSockets, queues, Agora, push notifications, design patterns).',
                'ar' => 'مشروع التخرج: «عقار وخدمات» — قيادة الفريق، تحليل وتصميم النظام بـ UML، وتطوير الـ Backend بلارافيل (WebSockets وQueues وAgora والإشعارات وأنماط التصميم).',
            ],
            'start_date' => '2019-09-01',
            'end_date' => '2024-07-01',
            'current' => false,
            'grade' => null,
            'sort' => 1,
        ]);

        Education::updateOrCreate(['degree->en' => 'Network Technician Diploma'], [
            'degree' => ['en' => 'Network Technician Diploma', 'ar' => 'دبلوم فني شبكات'],
            'school' => ['en' => 'Masar Academy, Syria', 'ar' => 'أكاديمية مسار، سوريا'],
            'field' => ['en' => 'Computer Networks', 'ar' => 'شبكات الحاسوب'],
            'description' => ['en' => '', 'ar' => ''],
            'start_date' => '2015-09-01',
            'end_date' => '2017-07-01',
            'current' => false,
            'grade' => null,
            'sort' => 2,
        ]);
    }

    private function seedSkills(): void
    {
        $skills = [
            ['PHP', 'backend', 95], ['Laravel', 'backend', 95], ['RESTful APIs', 'backend', 92],
            ['Eloquent ORM', 'backend', 90], ['Sanctum / Passport', 'backend', 88], ['Queues · Redis / Horizon', 'backend', 85],
            ['WebSockets · Reverb', 'backend', 82], ['PHPUnit / Pest', 'backend', 78],
            ['MySQL', 'database', 90], ['SQL & query optimisation', 'database', 85], ['Oracle', 'database', 60],
            ['Filament', 'integrations', 92], ['Laravel Nova', 'integrations', 88], ['Stripe', 'integrations', 80],
            ['Firebase Cloud Messaging', 'integrations', 82], ['Vimeo · Google Maps · Agora', 'integrations', 75],
            ['Vue.js · Inertia.js', 'frontend', 78], ['JavaScript', 'frontend', 75], ['HTML · CSS · Bootstrap', 'frontend', 80],
            ['Kotlin · Android', 'mobile', 70], ['Java', 'mobile', 65],
            ['Git', 'tools', 90], ['Jira · Azure DevOps', 'tools', 80], ['UML & system design', 'tools', 85],
            ['OOP · Clean Code · Design Patterns', 'tools', 88],
        ];

        foreach ($skills as $i => [$name, $category, $level]) {
            Skill::updateOrCreate(['name->en' => $name], [
                'name' => ['en' => $name, 'ar' => $name],
                'category' => $category,
                'level' => $level,
                'sort' => $i + 1,
            ]);
        }
    }

    private function seedLanguages(): void
    {
        foreach ([
            ['en' => 'Arabic', 'ar' => 'العربية', 'proficiency' => 'native', 'level' => 100],
            ['en' => 'English', 'ar' => 'الإنجليزية', 'proficiency' => 'intermediate', 'level' => 65],
        ] as $i => $l) {
            LanguageItem::updateOrCreate(['name->en' => $l['en']], [
                'name' => ['en' => $l['en'], 'ar' => $l['ar']],
                'proficiency' => $l['proficiency'],
                'level' => $l['level'],
                'sort' => $i + 1,
            ]);
        }
    }

    private function seedCertificates(): void
    {
        $certs = [
            ['Version Control', 'التحكم بالإصدارات', ['Git']],
            ['Programming with JavaScript', 'البرمجة بلغة JavaScript', ['JavaScript']],
            ['Programming Fundamentals in Kotlin', 'أساسيات البرمجة بلغة Kotlin', ['Kotlin']],
            ['HTML and CSS in depth', 'HTML وCSS بالتفصيل', ['HTML', 'CSS']],
            ['Introduction to Android Mobile Application Development', 'مقدمة في تطوير تطبيقات Android', ['Android', 'Kotlin']],
        ];

        foreach ($certs as $i => [$en, $ar, $skills]) {
            Certificate::updateOrCreate(['title->en' => $en], [
                'title' => ['en' => $en, 'ar' => $ar],
                'issuer' => 'Coursera',
                'description' => ['en' => '', 'ar' => ''],
                'skills' => $skills,
                'featured' => true,
                'sort' => $i + 1,
            ]);
        }
    }

    private function seedServices(): void
    {
        $services = [
            ['Server', 'Backend development', 'تطوير الأنظمة الخلفية', 'Scalable Laravel backends with clean architecture, modular monoliths and solid test coverage.', 'أنظمة خلفية قابلة للتوسع بلارافيل ببنية نظيفة ووحدات مستقلة واختبارات موثوقة.'],
            ['Webhook', 'RESTful API design', 'تصميم واجهات RESTful API', 'Secure, documented APIs for web and mobile apps with Sanctum/Passport authentication.', 'واجهات API آمنة وموثقة لتطبيقات الويب والموبايل مع مصادقة Sanctum/Passport.'],
            ['LayoutDashboard', 'Admin dashboards', 'لوحات التحكم', 'Powerful Filament and Nova admin panels with roles, permissions, reports and exports.', 'لوحات تحكم قوية بـ Filament وNova مع الأدوار والصلاحيات والتقارير والتصدير.'],
            ['Zap', 'Real-time & integrations', 'الأنظمة الفورية والتكاملات', 'Chat over WebSockets/Reverb, push notifications, Stripe payments, Vimeo and maps.', 'دردشة فورية عبر WebSockets/Reverb وإشعارات ومدفوعات Stripe وVimeo والخرائط.'],
            ['Database', 'Database optimisation', 'تحسين قواعد البيانات', 'Schema design, indexing and query tuning that keep MySQL fast under load.', 'تصميم المخططات والفهرسة وتحسين الاستعلامات لإبقاء MySQL سريعاً تحت الضغط.'],
            ['Smartphone', 'Android apps', 'تطبيقات Android', 'Native Kotlin apps that consume REST APIs with offline storage.', 'تطبيقات Kotlin أصلية تستهلك واجهات REST مع التخزين المحلي.'],
        ];

        foreach ($services as $i => [$icon, $en, $ar, $descEn, $descAr]) {
            Service::updateOrCreate(['title->en' => $en], [
                'title' => ['en' => $en, 'ar' => $ar],
                'description' => ['en' => $descEn, 'ar' => $descAr],
                'icon' => $icon,
                'sort' => $i + 1,
                'is_active' => true,
            ]);
        }
    }

    private function seedProjects(): void
    {
        $lines = fn (array $items): array => ['en' => implode("
", $items['en']), 'ar' => implode("
", $items['ar'])];

        foreach (require __DIR__.'/data/vega_projects.php' as $i => $project) {
            Project::updateOrCreate(['slug' => $project['slug']], [
                'title' => $project['title'],
                'category' => $project['category'],
                'short_desc' => $project['short'],
                'description' => $project['description'],
                'features' => $lines($project['features']),
                'client' => null,
                'company' => 'Vega',
                'accent_color' => $project['accent'],
                'status' => 'completed',
                'featured' => $project['featured'],
                'sort' => $i + 1,
                'techs' => $project['techs'],
            ]);
        }

        Project::updateOrCreate(['slug' => 'aqar-and-service'], [
            'title' => ['en' => 'Aqar & Service', 'ar' => 'عقار وخدمات'],
            'category' => ['en' => 'Graduation project', 'ar' => 'مشروع تخرج'],
            'short_desc' => [
                'en' => 'Graduation project: led the team, analysed and designed the system with UML, and built the backend.',
                'ar' => 'مشروع التخرج: قيادة الفريق، تحليل وتصميم النظام بـ UML، وتطوير الـ Backend.',
            ],
            'description' => [
                'en' => 'Real-estate and home-services platform. I led the team, analysed and designed the system with UML and built the Laravel backend with WebSockets, queues, Agora video calls, push notifications and design patterns.',
                'ar' => 'منصة للعقارات والخدمات المنزلية. قدت الفريق وحللت وصممت النظام بـ UML وطورت الـ Backend بلارافيل مع WebSockets وQueues ومكالمات Agora والإشعارات وأنماط التصميم.',
            ],
            'company' => 'Damascus University',
            'accent_color' => '#C9A227',
            'status' => 'completed',
            'featured' => true,
            'sort' => 100,
            'techs' => ['Laravel', 'WebSockets', 'Queues', 'Agora', 'Push Notifications', 'Design Patterns'],
        ]);

        Project::updateOrCreate(['slug' => '3now-delivery-app'], [
            'title' => ['en' => '3NOW Delivery App', 'ar' => 'تطبيق 3NOW للتوصيل'],
            'category' => ['en' => 'Android', 'ar' => 'أندرويد'],
            'short_desc' => [
                'en' => 'Kotlin delivery app on Google Play with live order tracking, scheduled booking and in-app payment.',
                'ar' => 'تطبيق توصيل بلغة Kotlin على Google Play مع تتبع فوري للطلبات وحجز مسبق ودفع داخل التطبيق.',
            ],
            'description' => [
                'en' => 'Developed and maintained the 3NOW delivery app: live order tracking, scheduled booking, secure in-app payment, REST API integration and offline storage with Room/SQLite.',
                'ar' => 'تطوير وصيانة تطبيق 3NOW للتوصيل: التتبع الفوري للطلبات والحجز المسبق والدفع الآمن داخل التطبيق واستهلاك واجهات REST والتخزين المحلي بـ Room/SQLite.',
            ],
            'company' => '3Now / Mos Gruppe',
            'accent_color' => '#EF4444',
            'status' => 'completed',
            'featured' => false,
            'sort' => 101,
            'techs' => ['Kotlin', 'Android', 'Room', 'SQLite', 'REST APIs'],
        ]);
    }

    private function seedSceneModels(): void
    {
        $defaults = [
            'metalness' => 0.85, 'roughness' => 0.2, 'opacity' => 1, 'wireframe' => false,
            'emissive_color' => '#000000', 'emissive_intensity' => 0,
            'position_x' => 0, 'position_y' => 0, 'position_z' => 0,
            'rotation_speed' => 0.35, 'float_intensity' => 0.35, 'mouse_follow' => 0.6,
            'draggable' => true, 'is_active' => true,
        ];

        $models = [
            ['name' => 'Hero — Laravel core knot', 'placement' => 'hero', 'shape' => 'torus_knot', 'color' => '#C9A227', 'scale' => 1.15, 'sort' => 1],
            ['name' => 'Hero — Wire crystal', 'placement' => 'hero', 'shape' => 'icosahedron', 'color' => '#E6C65A', 'wireframe' => true, 'scale' => 0.55,
                'position_x' => 2.1, 'position_y' => 1.5, 'position_z' => -1, 'rotation_speed' => 0.8, 'sort' => 2],
            ['name' => 'Hero — Glowing diamond', 'placement' => 'hero', 'shape' => 'octahedron', 'color' => '#8C6D15', 'emissive_color' => '#C9A227',
                'emissive_intensity' => 0.6, 'scale' => 0.45, 'position_x' => -2.2, 'position_y' => -1.4, 'position_z' => 0.4, 'rotation_speed' => 1, 'sort' => 3],
            ['name' => 'About — Server rack', 'placement' => 'about', 'shape' => 'server', 'color' => '#1c1c21', 'emissive_color' => '#C9A227',
                'emissive_intensity' => 1.2, 'metalness' => 0.3, 'roughness' => 0.55, 'scale' => 1, 'rotation_speed' => 0.25, 'sort' => 1],
            ['name' => 'Skills — Atom', 'placement' => 'skills', 'shape' => 'atom', 'color' => '#E6C65A', 'emissive_color' => '#C9A227',
                'emissive_intensity' => 0.4, 'scale' => 1, 'rotation_speed' => 0.6, 'sort' => 1],
            ['name' => 'Projects — Code brackets', 'placement' => 'projects', 'shape' => 'code', 'color' => '#C9A227', 'scale' => 1,
                'rotation_speed' => 0.3, 'sort' => 1],
            ['name' => 'Contact — Planet', 'placement' => 'contact', 'shape' => 'planet', 'color' => '#C9A227', 'emissive_color' => '#5f491e',
                'emissive_intensity' => 0.3, 'metalness' => 0.5, 'roughness' => 0.4, 'scale' => 1, 'rotation_speed' => 0.25, 'sort' => 1],
        ];

        foreach ($models as $model) {
            SceneModel::updateOrCreate(['name' => $model['name']], [...$defaults, ...$model]);
        }
    }
}
