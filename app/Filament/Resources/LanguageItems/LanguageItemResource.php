<?php

namespace App\Filament\Resources\LanguageItems;

use App\Filament\Resources\LanguageItems\Pages\ManageLanguageItems;
use App\Models\LanguageItem;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Slider;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LanguageItemResource extends Resource
{
    protected static ?string $model = LanguageItem::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedLanguage;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string|\UnitEnum|null $navigationGroup = 'Portfolio';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')->required()->translatable(),
                Select::make('proficiency')
                    ->options(['native' => 'Native', 'fluent' => 'Fluent', 'intermediate' => 'Intermediate', 'basic' => 'Basic'])
                    ->default('intermediate')->required(),
                Slider::make('level')->minValue(0)->maxValue(100)->default(50),
                TextInput::make('sort')->numeric()->default(0)->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('proficiency')->badge(),
                TextColumn::make('level')->numeric()->sortable(),
                TextColumn::make('sort')->numeric()->sortable(),
            ])
            ->defaultSort('sort')
            ->filters([])
            ->recordActions([EditAction::make(), DeleteAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return ['index' => ManageLanguageItems::route('/')];
    }
}
