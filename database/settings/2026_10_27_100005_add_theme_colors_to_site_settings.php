<?php

use App\Support\Theme;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('site.theme_preset', 'gold');
        $this->migrator->add('site.theme_default_mode', 'dark');

        foreach (Theme::preset('gold') as $key => $color) {
            $this->migrator->add("site.{$key}", $color);
        }
    }
};
