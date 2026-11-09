<?php

namespace App\Filament\Resources\LanguageItems\Pages;

use App\Filament\Resources\LanguageItems\LanguageItemResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageLanguageItems extends ManageRecords
{
    protected static string $resource = LanguageItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
