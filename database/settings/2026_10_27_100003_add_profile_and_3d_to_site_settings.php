<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('site.gitlab', null);
        $this->migrator->add('site.years_experience', 3);
        $this->migrator->add('site.enable_3d', true);
        $this->migrator->add('site.scene_particles', true);
        $this->migrator->add('site.particles_color', '#E6C65A');
        $this->migrator->add('site.scene_bloom_color', '#C9A227');
    }
};
