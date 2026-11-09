<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class Certificate extends Model implements HasMedia
{
    use HasFactory, HasTranslations, InteractsWithMedia;

    protected $fillable = [
        'title', 'issuer', 'description', 'credential_id',
        'credential_url', 'issued_at', 'skills', 'featured', 'sort',
    ];

    public array $translatable = ['title', 'description'];

    protected function casts(): array
    {
        return [
            'issued_at' => 'date',
            'skills' => 'array',
            'featured' => 'boolean',
            'sort' => 'integer',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')->singleFile();
    }
}
