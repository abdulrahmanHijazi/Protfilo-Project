<?php

namespace App\Models;

use App\Support\Theme;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SceneModel extends Model
{
    use HasFactory;

    public const PLACEMENTS = [
        'hero' => 'Hero (top of page)',
        'about' => 'About section',
        'skills' => 'Skills section',
        'projects' => 'Projects section',
        'contact' => 'Contact section',
    ];

    public const SHAPES = [
        'torus_knot' => 'Torus knot',
        'icosahedron' => 'Icosahedron (crystal)',
        'octahedron' => 'Octahedron (diamond)',
        'dodecahedron' => 'Dodecahedron',
        'sphere' => 'Sphere',
        'torus' => 'Ring (torus)',
        'cube' => 'Rounded cube',
        'atom' => 'Atom (sphere + orbits)',
        'planet' => 'Planet with ring',
        'server' => 'Server rack (backend)',
        'laptop' => 'Laptop',
        'code' => 'Code brackets </>',
        'database' => 'Database cylinders',
        'glb' => 'Custom uploaded model (.glb)',
    ];

    protected $fillable = [
        'name', 'placement', 'shape', 'model_file', 'use_theme_color',
        'color', 'emissive_color', 'emissive_intensity',
        'metalness', 'roughness', 'opacity', 'wireframe',
        'scale', 'position_x', 'position_y', 'position_z',
        'rotation_speed', 'float_intensity', 'mouse_follow',
        'draggable', 'is_active', 'sort',
    ];

    protected function casts(): array
    {
        return [
            'emissive_intensity' => 'float',
            'metalness' => 'float',
            'roughness' => 'float',
            'opacity' => 'float',
            'wireframe' => 'boolean',
            'use_theme_color' => 'boolean',
            'scale' => 'float',
            'position_x' => 'float',
            'position_y' => 'float',
            'position_z' => 'float',
            'rotation_speed' => 'float',
            'float_intensity' => 'float',
            'mouse_follow' => 'float',
            'draggable' => 'boolean',
            'is_active' => 'boolean',
            'sort' => 'integer',
        ];
    }

    public function toScene(): array
    {
        $theme = Theme::colors();
        $hasGlow = strtolower($this->emissive_color ?? '#000000') !== '#000000';

        return [
            ...$this->only([
                'id', 'name', 'placement', 'shape', 'color', 'emissive_color', 'emissive_intensity',
                'metalness', 'roughness', 'opacity', 'wireframe', 'scale',
                'rotation_speed', 'float_intensity', 'mouse_follow', 'draggable',
            ]),
            // "Follow site colors": tint with the brand accent from Site Settings → Appearance.
            ...($this->use_theme_color ? [
                'color' => $this->wireframe ? $theme['theme_accent_light'] : $theme['theme_accent'],
                'emissive_color' => $hasGlow ? $theme['theme_accent'] : '#000000',
            ] : ($hasGlow && $this->shape === 'server' ? ['emissive_color' => $theme['theme_accent']] : [])),
            'position' => [$this->position_x, $this->position_y, $this->position_z],
            'model_url' => $this->model_file ? Storage::disk('public')->url($this->model_file) : null,
        ];
    }
}
