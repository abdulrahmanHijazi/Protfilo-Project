<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class SiteSettings extends Settings
{
    public array $first_name = ['en' => '', 'ar' => ''];

    public array $last_name = ['en' => '', 'ar' => ''];

    public array $job_title = ['en' => '', 'ar' => ''];

    public array $professional_summary = ['en' => '', 'ar' => ''];

    public array $about_us = ['en' => '', 'ar' => ''];

    public ?string $email = null;

    public ?string $phone = null;

    public ?string $whatsapp = null;

    public array $location = ['en' => '', 'ar' => ''];

    public ?string $github = null;

    public ?string $linkedin = null;

    public ?string $gitlab = null;

    public ?string $x_twitter = null;

    public ?string $facebook = null;

    public ?string $instagram = null;

    public ?string $cv_url = null;

    public bool $available_for_work = true;

    public int $years_experience = 3;

    public bool $enable_3d = true;

    public bool $scene_particles = true;

    public bool $scene_follow_theme = true;

    public ?string $particles_color = '#E6C65A';

    public ?string $scene_bloom_color = '#C9A227';

    public string $theme_preset = 'gold';

    public string $theme_default_mode = 'dark';

    public string $theme_accent = '#C9A227';

    public string $theme_accent_light = '#E6C65A';

    public string $theme_accent_dark = '#8C6D15';

    public string $theme_dark_bg = '#09090B';

    public string $theme_dark_surface = '#121215';

    public string $theme_dark_text = '#F4F1E8';

    public string $theme_light_bg = '#F7F5EF';

    public string $theme_light_surface = '#FFFFFF';

    public string $theme_light_text = '#17150F';

    public array $seo_title = ['en' => '', 'ar' => ''];

    public array $seo_description = ['en' => '', 'ar' => ''];

    public ?string $logo = null;

    public ?string $personal_image = null;

    public static function group(): string
    {
        return 'site';
    }
}
