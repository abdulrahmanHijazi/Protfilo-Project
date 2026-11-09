<?php

namespace App\Filament\Resources\Certificates;

use App\Filament\Resources\Certificates\Pages\ManageCertificates;
use App\Models\Certificate;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CertificateResource extends Resource
{
    protected static ?string $model = Certificate::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    protected static ?string $recordTitleAttribute = 'title';

    protected static string|\UnitEnum|null $navigationGroup = 'Portfolio';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')->required()->translatable(),
                TextInput::make('issuer')->default('Coursera'),
                Textarea::make('description')->translatable()->columnSpanFull(),
                TextInput::make('credential_id'),
                TextInput::make('credential_url')->url(),
                DatePicker::make('issued_at'),
                TagsInput::make('skills')->columnSpanFull(),
                Toggle::make('featured'),
                TextInput::make('sort')->numeric()->default(0),
                SpatieMediaLibraryFileUpload::make('image')->collection('image')->image()->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                SpatieMediaLibraryImageColumn::make('image')->collection('image')->label('Image'),
                TextColumn::make('title')->searchable(),
                TextColumn::make('issuer')->searchable(),
                TextColumn::make('issued_at')->date()->sortable(),
                IconColumn::make('featured')->boolean(),
                TextColumn::make('sort')->numeric()->sortable(),
            ])
            ->defaultSort('sort')
            ->filters([])
            ->recordActions([EditAction::make(), DeleteAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return ['index' => ManageCertificates::route('/')];
    }
}
