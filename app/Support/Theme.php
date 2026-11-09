<?php

namespace App\Support;

use App\Settings\SiteSettings;

/**
 * Site color theme: presets, validation and the CSS variables injected into the page.
 * Only the base tokens are emitted; app.css derives muted text, borders and overlays from them.
 */
final class Theme
{
    public const KEYS = [
        'theme_accent', 'theme_accent_light', 'theme_accent_dark',
        'theme_dark_bg', 'theme_dark_surface', 'theme_dark_text',
        'theme_light_bg', 'theme_light_surface', 'theme_light_text',
    ];

    public const PRESETS = [
        'gold' => ['label' => 'Royal gold', 'colors' => ['#C9A227', '#E6C65A', '#8C6D15', '#09090B', '#121215', '#F4F1E8', '#F7F5EF', '#FFFFFF', '#17150F']],
        'emerald' => ['label' => 'Emerald', 'colors' => ['#10B981', '#6EE7B7', '#047857', '#06100C', '#0C1914', '#ECFDF5', '#F3F8F5', '#FFFFFF', '#0B1712']],
        'sapphire' => ['label' => 'Sapphire', 'colors' => ['#3B82F6', '#93C5FD', '#1D4ED8', '#070B14', '#0E1422', '#EEF2FF', '#F4F6FB', '#FFFFFF', '#0C1220']],
        'violet' => ['label' => 'Violet', 'colors' => ['#8B5CF6', '#C4B5FD', '#6D28D9', '#0A0812', '#14101F', '#F5F3FF', '#F6F4FB', '#FFFFFF', '#140F22']],
        'rose' => ['label' => 'Rose', 'colors' => ['#F43F5E', '#FDA4AF', '#BE123C', '#110709', '#1C0E12', '#FFF1F2', '#FBF4F5', '#FFFFFF', '#1F0C10']],
        'cyan' => ['label' => 'Ocean cyan', 'colors' => ['#06B6D4', '#67E8F9', '#0E7490', '#051013', '#0B1A1F', '#ECFEFF', '#F2F8F9', '#FFFFFF', '#0A191D']],
    ];

    /** @return array<string, string> preset colors keyed by setting name */
    public static function preset(string $name): array
    {
        return array_combine(self::KEYS, (self::PRESETS[$name] ?? self::PRESETS['gold'])['colors']);
    }

    /** @return array<string, string> the saved colors, falling back to the gold preset for invalid values */
    public static function colors(?SiteSettings $settings = null): array
    {
        $defaults = self::preset('gold');

        try {
            $settings ??= app(SiteSettings::class);
        } catch (\Throwable) {
            return $defaults;
        }

        $colors = [];
        foreach (self::KEYS as $key) {
            $value = $settings->{$key} ?? null;
            $colors[$key] = self::isHex($value) ? $value : $defaults[$key];
        }

        return $colors;
    }

    public static function defaultMode(?SiteSettings $settings = null): string
    {
        try {
            $mode = ($settings ?? app(SiteSettings::class))->theme_default_mode;
        } catch (\Throwable) {
            return 'dark';
        }

        return in_array($mode, ['dark', 'light', 'system'], true) ? $mode : 'dark';
    }

    public static function css(?SiteSettings $settings = null): string
    {
        $c = self::colors($settings);

        return sprintf(
            ':root{--accent:%1$s;--accent-soft:%3$s;--accent-deep:%3$s;--on-accent:%10$s;--bg:%7$s;--bg-2:%8$s;--text:%9$s}'
            .'.dark{--accent:%1$s;--accent-soft:%2$s;--accent-deep:%3$s;--on-accent:%10$s;--bg:%4$s;--bg-2:%5$s;--text:%6$s}',
            $c['theme_accent'], $c['theme_accent_light'], $c['theme_accent_dark'],
            $c['theme_dark_bg'], $c['theme_dark_surface'], $c['theme_dark_text'],
            $c['theme_light_bg'], $c['theme_light_surface'], $c['theme_light_text'],
            self::onColor($c['theme_accent']),
        );
    }

    /** Readable text color (near-black or white) for content placed on top of $hex. */
    public static function onColor(string $hex): string
    {
        [$r, $g, $b] = array_map(
            fn (string $part): float => hexdec($part) / 255,
            str_split(substr(self::normalize($hex), 1), 2),
        );
        $linear = fn (float $v): float => $v <= 0.03928 ? $v / 12.92 : (($v + 0.055) / 1.055) ** 2.4;
        $luminance = 0.2126 * $linear($r) + 0.7152 * $linear($g) + 0.0722 * $linear($b);

        return $luminance > 0.35 ? '#0A0A0B' : '#FFFFFF';
    }

    public static function isHex(mixed $value): bool
    {
        return is_string($value) && preg_match('/^#([0-9a-f]{3}|[0-9a-f]{6})$/i', $value) === 1;
    }

    private static function normalize(string $hex): string
    {
        if (strlen($hex) === 4) {
            return '#'.$hex[1].$hex[1].$hex[2].$hex[2].$hex[3].$hex[3];
        }

        return $hex;
    }
}
