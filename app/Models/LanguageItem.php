<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class LanguageItem extends Model implements \Spatie\MediaLibrary\HasMedia
{
    use HasFactory, HasTranslations, \Spatie\MediaLibrary\InteractsWithMedia;

    protected $table = 'language_items';

    protected $fillable = ['name', 'proficiency', 'level', 'sort'];

    public array $translatable = ['name'];

    protected function casts(): array
    {
        return ['level' => 'integer', 'sort' => 'integer'];
    }
}
