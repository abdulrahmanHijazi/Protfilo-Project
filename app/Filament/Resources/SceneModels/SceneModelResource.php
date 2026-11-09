<?php

namespace App\Filament\Resources\SceneModels;

use App\Filament\Resources\SceneModels\Pages\ManageSceneModels;
use App\Models\SceneModel;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ReplicateAction;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Slider;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SceneModelResource extends Resource
{
    protected static ?string $model = SceneModel::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCube;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string|\UnitEnum|null $navigationGroup = 'Portfolio';

    protected static ?string $navigationLabel = '3D Models';

    protected static ?string $modelLabel = '3D model';

    protected static ?string $pluralModelLabel = '3D models';

    private static function slider(string $name, float $min, float $max, float $step, int $decimals = 2): Slider
    {
        return Slider::make($name)
            ->range($min, $max)
            ->step($step)
            ->decimalPlaces($decimals)
            ->tooltips();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Model')
                    ->description('Choose a built-in shape or upload your own .glb model, and where it appears on the site.')
                    ->schema([
                        TextInput::make('name')->required(),
                        Select::make('placement')
                            ->options(SceneModel::PLACEMENTS)
                            ->default('hero')
                            ->required(),
                        Select::make('shape')
                            ->options(SceneModel::SHAPES)
                            ->default('torus_knot')
                            ->required()
                            ->live(),
                        FileUpload::make('model_file')
                            ->label('3D model file (.glb / .gltf)')
                            ->disk('public')
                            ->directory('models')
                            ->acceptedFileTypes(['model/gltf-binary', 'model/gltf+json', 'application/octet-stream'])
                            ->maxSize(20480)
                            ->visible(fn (Get $get): bool => $get('shape') === 'glb')
                            ->required(fn (Get $get): bool => $get('shape') === 'glb'),
                        Toggle::make('is_active')->default(true),
                        TextInput::make('sort')->numeric()->default(0),
                    ])->columns(2),
                Section::make('Material')
                    ->schema([
                        Toggle::make('use_theme_color')
                            ->label('Follow site colors')
                            ->helperText('Uses the accent color from Site Settings → Appearance, so the model changes with the theme.')
                            ->default(true)
                            ->live()
                            ->columnSpanFull(),
                        ColorPicker::make('color')->default('#C9A227')->required()
                            ->visible(fn (Get $get): bool => ! $get('use_theme_color')),
                        ColorPicker::make('emissive_color')->label('Glow color')->default('#000000')
                            ->helperText('Black = no glow.')
                            ->visible(fn (Get $get): bool => ! $get('use_theme_color')),
                        self::slider('emissive_intensity', 0, 3, 0.05)->label('Glow intensity')->default(0),
                        self::slider('metalness', 0, 1, 0.05)->default(0.8),
                        self::slider('roughness', 0, 1, 0.05)->default(0.25),
                        self::slider('opacity', 0.1, 1, 0.05)->default(1),
                        Toggle::make('wireframe')->default(false),
                    ])->columns(2),
                Section::make('Transform & motion')
                    ->schema([
                        self::slider('scale', 0.1, 4, 0.05)->default(1),
                        self::slider('position_x', -5, 5, 0.1, 1)->label('Position X (left / right)')->default(0),
                        self::slider('position_y', -5, 5, 0.1, 1)->label('Position Y (down / up)')->default(0),
                        self::slider('position_z', -5, 5, 0.1, 1)->label('Position Z (back / front)')->default(0),
                        self::slider('rotation_speed', 0, 3, 0.05)->default(0.4),
                        self::slider('float_intensity', 0, 2, 0.05)->label('Floating intensity')->default(0.3),
                        self::slider('mouse_follow', 0, 2, 0.05)->label('Mouse follow strength')->default(0.5),
                        Toggle::make('draggable')
                            ->label('Visitors can rotate it by dragging')
                            ->default(true),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                ColorColumn::make('color'),
                TextColumn::make('name')->searchable(),
                TextColumn::make('placement')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => SceneModel::PLACEMENTS[$state] ?? $state),
                TextColumn::make('shape')
                    ->formatStateUsing(fn (string $state): string => SceneModel::SHAPES[$state] ?? $state),
                TextColumn::make('scale')->numeric(2),
                ToggleColumn::make('is_active'),
                TextColumn::make('sort')->numeric()->sortable(),
            ])
            ->defaultSort('sort')
            ->reorderable('sort')
            ->filters([
                SelectFilter::make('placement')->options(SceneModel::PLACEMENTS),
            ])
            ->recordActions([
                EditAction::make(),
                ReplicateAction::make(),
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
            'index' => ManageSceneModels::route('/'),
        ];
    }
}
