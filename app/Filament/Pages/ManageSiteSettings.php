<?php

namespace App\Filament\Pages;

use App\Settings\SiteSettings;
use App\Support\Theme;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Notifications\Notification;
use Filament\Pages\SettingsPage;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Html;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\HtmlString;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;

class ManageSiteSettings extends SettingsPage
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static string $settings = SiteSettings::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Profile & Settings';

    protected static ?string $title = 'Site Settings';

    protected ?string $subheading = 'Everything visitors see on the portfolio: profile, contact details, colors, 3D scenes and SEO.';

    public static function getNavigationLabel(): string
    {
        return 'Site Settings';
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('viewSite')
                ->label('View site')
                ->icon(Heroicon::OutlinedArrowTopRightOnSquare)
                ->color('gray')
                ->url('/', shouldOpenInNewTab: true),
            Action::make('resetColors')
                ->label('Reset colors')
                ->icon(Heroicon::OutlinedArrowPath)
                ->color('gray')
                ->requiresConfirmation()
                ->modalDescription('Restore the default Royal gold palette. Changes are applied after you save.')
                ->action(function (): void {
                    $this->data = [...$this->data, ...Theme::preset('gold'), 'theme_preset' => 'gold'];
                    Notification::make()->title('Default colors restored — remember to save.')->success()->send();
                }),
        ];
    }

    private static function color(string $name, string $label, ?string $helper = null): ColorPicker
    {
        return ColorPicker::make($name)
            ->label($label)
            ->helperText($helper)
            ->required()
            ->regex('/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/')
            ->live(debounce: 300)
            ->afterStateUpdated(fn (Set $set) => $set('theme_preset', 'custom'));
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Tabs::make('settings')
                    ->persistTabInQueryString()
                    ->tabs([
                        $this->profileTab(),
                        $this->contactTab(),
                        $this->appearanceTab(),
                        $this->sceneTab(),
                        $this->seoTab(),
                    ]),
            ]);
    }

    private function profileTab(): Tab
    {
        return Tab::make('Profile')
            ->icon(Heroicon::OutlinedUserCircle)
            ->schema([
                Grid::make(['default' => 1, 'lg' => 3])->schema([
                    Section::make('Photo & logo')
                        ->icon(Heroicon::OutlinedPhoto)
                        ->schema([
                            FileUpload::make('personal_image')
                                ->label('Personal photo')
                                ->image()
                                ->avatar()
                                ->imageEditor()
                                ->circleCropper()
                                ->directory('settings')
                                ->alignCenter(),
                            FileUpload::make('logo')
                                ->image()
                                ->imageEditor()
                                ->directory('settings')
                                ->helperText('Optional. The first letter of your name is used when empty.'),
                        ])
                        ->columnSpan(1),
                    Section::make('Name & title')
                        ->icon(Heroicon::OutlinedIdentification)
                        ->description('Shown in the hero, navigation and page titles.')
                        ->schema([
                            TextInput::make('first_name')->required()->translatable(),
                            TextInput::make('last_name')->required()->translatable(),
                            TextInput::make('job_title')->translatable()->columnSpanFull(),
                            Textarea::make('professional_summary')
                                ->label('Hero summary')
                                ->rows(3)
                                ->translatable()
                                ->columnSpanFull(),
                            Textarea::make('about_us')
                                ->label('About me')
                                ->rows(6)
                                ->translatable()
                                ->columnSpanFull(),
                        ])
                        ->columns(2)
                        ->columnSpan(['lg' => 2]),
                ]),
            ]);
    }

    private function contactTab(): Tab
    {
        return Tab::make('Contact & social')
            ->icon(Heroicon::OutlinedChatBubbleLeftRight)
            ->schema([
                Section::make('Contact details')
                    ->icon(Heroicon::OutlinedEnvelope)
                    ->schema([
                        TextInput::make('email')->email()->prefixIcon(Heroicon::OutlinedEnvelope),
                        TextInput::make('location')->translatable(),
                        PhoneInput::make('phone')->defaultCountry('SY'),
                        PhoneInput::make('whatsapp')->label('WhatsApp')->defaultCountry('SY'),
                    ])
                    ->columns(2),
                Section::make('Social profiles')
                    ->icon(Heroicon::OutlinedGlobeAlt)
                    ->description('Leave a field empty to hide that link.')
                    ->schema([
                        TextInput::make('linkedin')->label('LinkedIn')->url()->prefixIcon(Heroicon::OutlinedLink),
                        TextInput::make('gitlab')->label('GitLab')->url()->prefixIcon(Heroicon::OutlinedCodeBracket),
                        TextInput::make('github')->label('GitHub')->url()->prefixIcon(Heroicon::OutlinedCodeBracketSquare),
                        TextInput::make('x_twitter')->label('X (Twitter)')->url()->prefixIcon(Heroicon::OutlinedAtSymbol),
                        TextInput::make('facebook')->url()->prefixIcon(Heroicon::OutlinedLink),
                        TextInput::make('instagram')->url()->prefixIcon(Heroicon::OutlinedCamera),
                    ])
                    ->columns(2),
                Section::make('Availability & CV')
                    ->icon(Heroicon::OutlinedBriefcase)
                    ->schema([
                        Toggle::make('available_for_work')->label('Available for work')->inline(false),
                        TextInput::make('years_experience')->label('Years of experience')->numeric()->minValue(0)->suffix('years'),
                        TextInput::make('cv_url')
                            ->label('CV link')
                            ->prefixIcon(Heroicon::OutlinedDocumentArrowDown)
                            ->helperText('Full URL or a path such as /storage/cv/Abdulrahman_Hijazi_CV.pdf')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    private function appearanceTab(): Tab
    {
        return Tab::make('Appearance')
            ->icon(Heroicon::OutlinedSwatch)
            ->schema([
                Section::make('Theme')
                    ->icon(Heroicon::OutlinedSparkles)
                    ->description('Pick a ready-made palette, then fine-tune any color below. The admin panel uses the same accent color.')
                    ->schema([
                        Select::make('theme_preset')
                            ->label('Color preset')
                            ->options([...array_map(fn (array $p) => $p['label'], Theme::PRESETS), 'custom' => 'Custom'])
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (?string $state, Set $set): void {
                                if ($state && isset(Theme::PRESETS[$state])) {
                                    foreach (Theme::preset($state) as $key => $color) {
                                        $set($key, $color);
                                    }
                                }
                            }),
                        ToggleButtons::make('theme_default_mode')
                            ->label('Default mode for new visitors')
                            ->options(['dark' => 'Dark', 'light' => 'Light', 'system' => 'Follow device'])
                            ->icons(['dark' => Heroicon::OutlinedMoon, 'light' => Heroicon::OutlinedSun, 'system' => Heroicon::OutlinedComputerDesktop])
                            ->inline()
                            ->required(),
                    ])
                    ->columns(2),
                Section::make('Live preview')
                    ->icon(Heroicon::OutlinedEye)
                    ->schema([
                        Html::make(fn (Get $get): HtmlString => new HtmlString(view('filament.theme-preview', [
                            'c' => collect(Theme::preset('gold'))
                                ->map(fn (string $default, string $key) => Theme::isHex($get($key)) ? $get($key) : $default)
                                ->all(),
                        ])->render())),
                    ]),
                Section::make('Brand colors')
                    ->icon(Heroicon::OutlinedPaintBrush)
                    ->description('Buttons, links, highlights and gradients.')
                    ->schema([
                        self::color('theme_accent', 'Accent', 'Main brand color.'),
                        self::color('theme_accent_light', 'Accent — light', 'Links and highlights in dark mode.'),
                        self::color('theme_accent_dark', 'Accent — dark', 'Links and highlights in light mode.'),
                    ])
                    ->columns(3),
                Grid::make(['default' => 1, 'lg' => 2])->schema([
                    Section::make('Dark mode')
                        ->icon(Heroicon::OutlinedMoon)
                        ->schema([
                            self::color('theme_dark_bg', 'Background'),
                            self::color('theme_dark_surface', 'Cards & surfaces'),
                            self::color('theme_dark_text', 'Text'),
                        ]),
                    Section::make('Light mode')
                        ->icon(Heroicon::OutlinedSun)
                        ->schema([
                            self::color('theme_light_bg', 'Background'),
                            self::color('theme_light_surface', 'Cards & surfaces'),
                            self::color('theme_light_text', 'Text'),
                        ]),
                ]),
            ]);
    }

    private function sceneTab(): Tab
    {
        return Tab::make('3D scene')
            ->icon(Heroicon::OutlinedCube)
            ->schema([
                Section::make('3D scenes')
                    ->icon(Heroicon::OutlinedCubeTransparent)
                    ->description(new HtmlString('Global switches for the interactive scenes. Individual models are managed in <a href="'.url('/admin/scene-models').'" class="underline font-semibold">Portfolio → 3D Models</a>.'))
                    ->schema([
                        Toggle::make('enable_3d')->label('Enable 3D scenes')->inline(false),
                        Toggle::make('scene_particles')->label('Show floating particles')->inline(false),
                        Toggle::make('scene_follow_theme')
                            ->label('Use site colors for particles and lighting')
                            ->live()
                            ->inline(false)
                            ->columnSpanFull(),
                        ColorPicker::make('particles_color')->label('Particles color')
                            ->visible(fn (Get $get): bool => ! $get('scene_follow_theme')),
                        ColorPicker::make('scene_bloom_color')->label('Accent light color')
                            ->visible(fn (Get $get): bool => ! $get('scene_follow_theme')),
                    ])
                    ->columns(2),
            ]);
    }

    private function seoTab(): Tab
    {
        return Tab::make('SEO')
            ->icon(Heroicon::OutlinedMagnifyingGlass)
            ->schema([
                Section::make('Search engines & sharing')
                    ->icon(Heroicon::OutlinedMagnifyingGlassCircle)
                    ->description('Used for the browser tab title, Google results and link previews.')
                    ->schema([
                        TextInput::make('seo_title')->label('Site title')->maxLength(70)->translatable(),
                        Textarea::make('seo_description')->label('Meta description')->rows(3)->maxLength(170)->translatable(),
                    ]),
            ]);
    }
}
