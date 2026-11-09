<?php

namespace App\Filament\Resources\Experiences;

use App\Filament\Resources\Experiences\Pages\ManageExperiences;
use App\Models\Experience;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ExperienceResource extends Resource
{
    protected static ?string $model = Experience::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBriefcase;

    protected static ?string $recordTitleAttribute = 'company';

    protected static string|\UnitEnum|null $navigationGroup = 'Portfolio';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Position')
                    ->schema([
                        TextInput::make('title')->label('Job title')->required()->translatable(),
                        TextInput::make('company')->required(),
                        TextInput::make('location')->translatable(),
                        TextInput::make('company_url')->url()->label('Company URL'),
                        Textarea::make('description')->translatable()->columnSpanFull(),
                        Textarea::make('highlights')
                            ->label('Highlights (one per line)')
                            ->rows(6)
                            ->translatable()
                            ->columnSpanFull(),
                        TagsInput::make('techs')->columnSpanFull(),
                    ])->columns(2),
                Section::make('Period')
                    ->schema([
                        DatePicker::make('start_date'),
                        DatePicker::make('end_date'),
                        Toggle::make('current')->label('Current position'),
                        TextInput::make('sort')->numeric()->default(0),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('company')
            ->columns([
                TextColumn::make('title')->searchable(),
                TextColumn::make('company')->searchable(),
                TextColumn::make('start_date')->date('m/Y')->sortable(),
                TextColumn::make('end_date')->date('m/Y'),
                IconColumn::make('current')->boolean(),
                TextColumn::make('sort')->numeric()->sortable(),
            ])
            ->defaultSort('sort')
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageExperiences::route('/'),
        ];
    }
}
