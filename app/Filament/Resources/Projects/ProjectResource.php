<?php

namespace App\Filament\Resources\Projects;

use App\Filament\Resources\Projects\Pages\ManageProjects;
use App\Models\Project;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'title';

    protected static string|\UnitEnum|null $navigationGroup = 'Portfolio';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Content')
                    ->schema([
                        TextInput::make('title')
                            ->label('Title')
                            ->required()
                            ->translatable(),
                        TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state ?? ''))),
                        Textarea::make('short_desc')
                            ->label('Short description')
                            ->translatable()
                            ->columnSpanFull(),
                        Textarea::make('description')
                            ->label('Description')
                            ->translatable()
                            ->columnSpanFull(),
                        Textarea::make('features')
                            ->label('Key features (one per line)')
                            ->rows(6)
                            ->translatable()
                            ->columnSpanFull(),
                    ])->columns(2),
                Section::make('Details')
                    ->schema([
                        TextInput::make('client'),
                        TextInput::make('company')
                            ->label('Company (e.g. Vega)')
                            ->datalist(['Vega', '3Now / Mos Gruppe', 'Freelance', 'Personal']),
                        TextInput::make('category')->label('Category / domain')->translatable(),
                        ColorPicker::make('accent_color')->label('Card accent color'),
                        TextInput::make('project_url')->url()->label('Project URL'),
                        TextInput::make('github_url')->url()->label('GitHub URL'),
                        Select::make('status')
                            ->options([
                                'completed' => 'Completed',
                                'in_progress' => 'In progress',
                                'planned' => 'Planned',
                            ])->default('completed')->required(),
                        Toggle::make('featured'),
                        TextInput::make('sort')->numeric()->default(0),
                        TagsInput::make('techs')->columnSpanFull(),
                        DatePicker::make('started_at'),
                        DatePicker::make('ended_at'),
                    ])->columns(2),
                Section::make('Media')
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('cover')
                            ->collection('cover')
                            ->image()
                            ->columnSpanFull(),
                        SpatieMediaLibraryFileUpload::make('gallery')
                            ->collection('gallery')
                            ->image()
                            ->multiple()
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                SpatieMediaLibraryImageColumn::make('cover')
                    ->collection('cover')
                    ->label('Cover'),
                TextColumn::make('title')->searchable(),
                TextColumn::make('slug')->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('client')->searchable(),
                TextColumn::make('company')->badge()->searchable()->sortable(),
                TextColumn::make('status')->badge()->searchable(),
                IconColumn::make('featured')->boolean(),
                TextColumn::make('sort')->numeric()->sortable(),
                TextColumn::make('started_at')->date()->sortable(),
            ])
            ->defaultSort('sort')
            ->reorderable('sort')
            ->filters([
                SelectFilter::make('company')
                    ->options(fn (): array => Project::query()->whereNotNull('company')->distinct()->pluck('company', 'company')->all()),
            ])
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
            'index' => ManageProjects::route('/'),
        ];
    }
}
