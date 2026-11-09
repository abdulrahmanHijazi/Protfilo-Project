<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class Education extends Model implements HasMedia
{
    use HasFactory, HasTranslations, InteractsWithMedia;

    protected $table = 'educations';

    protected $fillable = [
        'degree', 'school', 'field', 'description',
        'start_date', 'end_date', 'current', 'grade', 'sort',
    ];

    public array $translatable = ['degree', 'school', 'field', 'description'];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'current' => 'boolean',
            'sort' => 'integer',
        ];
    }
}
