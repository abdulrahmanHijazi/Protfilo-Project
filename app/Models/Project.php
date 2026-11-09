<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class Project extends Model implements HasMedia
{
    use HasFactory, HasTranslations, InteractsWithMedia;

    protected $fillable = [
        'title', 'slug', 'short_desc', 'description', 'features', 'client', 'company', 'category', 'accent_color',
        'project_url', 'github_url', 'status', 'featured', 'sort',
        'techs', 'started_at', 'ended_at',
    ];

    public array $translatable = ['title', 'short_desc', 'description', 'features', 'category'];

    protected function casts(): array
    {
        return [
            'featured' => 'boolean',
            'sort' => 'integer',
            'techs' => 'array',
            'started_at' => 'date',
            'ended_at' => 'date',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cover')->singleFile();
        $this->addMediaCollection('gallery');
    }
}
