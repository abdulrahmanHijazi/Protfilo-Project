<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('site.first_name', ['en' => 'John', 'ar' => 'جون']);
        $this->migrator->add('site.last_name', ['en' => 'Doe', 'ar' => 'دو']);
        $this->migrator->add('site.job_title', ['en' => 'Full Stack Developer', 'ar' => 'مطور full stack']);
        $this->migrator->add('site.professional_summary', ['en' => 'Passionate developer building modern web apps.', 'ar' => 'مطور شغوف يبني تطبيقات ويب حديثة.']);
        $this->migrator->add('site.about_us', ['en' => 'About me content here.', 'ar' => 'نبذة عني هنا.']);
        $this->migrator->add('site.email', 'hello@portfolio.test');
        $this->migrator->add('site.phone', '+201000000000');
        $this->migrator->add('site.whatsapp', '+201000000000');
        $this->migrator->add('site.location', ['en' => 'Cairo, Egypt', 'ar' => 'القاهرة، مصر']);
        $this->migrator->add('site.github', 'https://github.com');
        $this->migrator->add('site.linkedin', 'https://linkedin.com');
        $this->migrator->add('site.x_twitter', 'https://x.com');
        $this->migrator->add('site.facebook', 'https://facebook.com');
        $this->migrator->add('site.instagram', 'https://instagram.com');
        $this->migrator->add('site.cv_url', null);
        $this->migrator->add('site.available_for_work', true);
        $this->migrator->add('site.seo_title', ['en' => 'My Portfolio', 'ar' => 'معرض أعمالي']);
        $this->migrator->add('site.seo_description', ['en' => 'Portfolio of a full stack developer.', 'ar' => 'معرض أعمال مطور full stack.']);
        $this->migrator->add('site.logo', null);
        $this->migrator->add('site.personal_image', null);
    }
};
