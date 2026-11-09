<?php

namespace App\Filament\Resources\SceneModels\Pages;

use App\Filament\Resources\SceneModels\SceneModelResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageSceneModels extends ManageRecords
{
    protected static string $resource = SceneModelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
