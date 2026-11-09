<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Experience extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'title', 'company', 'location', 'description', 'highlights', 'techs',
        'company_url', 'start_date', 'end_date', 'current', 'sort',
    ];

    public array $translatable = ['title', 'location', 'description', 'highlights'];

    protected function casts(): array
    {
        return [
            'techs' => 'array',
            'start_date' => 'date',
            'end_date' => 'date',
            'current' => 'boolean',
            'sort' => 'integer',
        ];
    }
}
