<?php

namespace App\Filament\Resources\Education;

use App\Filament\Resources\Education\Pages\ManageEducation;
use App\Models\Education;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EducationResource extends Resource
{
    protected static ?string $model = Education::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;

    protected static ?string $recordTitleAttribute = 'degree';

    protected static string|\UnitEnum|null $navigationGroup = 'Portfolio';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('degree')->required()->translatable(),
                TextInput::make('school')->required()->translatable(),
                TextInput::make('field')->translatable(),
                Textarea::make('description')->translatable()->columnSpanFull(),
                DatePicker::make('start_date'),
                DatePicker::make('end_date'),
                Toggle::make('current'),
                TextInput::make('grade'),
                TextInput::make('sort')->numeric()->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('degree')
            ->columns([
                TextColumn::make('degree')->searchable(),
                TextColumn::make('school')->searchable(),
                TextColumn::make('start_date')->date()->sortable(),
                TextColumn::make('end_date')->date()->sortable(),
                IconColumn::make('current')->boolean(),
                TextColumn::make('sort')->numeric()->sortable(),
            ])
            ->defaultSort('sort')
            ->filters([])
            ->recordActions([EditAction::make(), DeleteAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return ['index' => ManageEducation::route('/')];
    }
}
