<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Actions\Action;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ManageSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $title = 'Site Settings';

    protected static ?string $navigationLabel = 'Site Settings';

    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.pages.manage-settings';

    public ?array $data = [];

    public Setting $setting;

    public function mount(): void
    {
        $this->setting = Setting::firstOrCreate(['id' => 1]);
        $this->form->fill($this->setting->attributesToArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Settings Tabs')->tabs([
                    Tabs\Tab::make('Hero & Branding')->icon('heroicon-o-sparkles')->schema([
                        TextInput::make('site_title')
                            ->label('Site Title')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Entu Creative — Graphic Designer & Art Director'),

                        TextInput::make('tagline')
                            ->label('Tagline / Slogan')
                            ->maxLength(255)
                            ->placeholder('Bold visual identities and thoughtful packaging design.'),

                        TextInput::make('hero_heading')
                            ->label('Hero Main Headline')
                            ->maxLength(255)
                            ->placeholder('Visual Identity & Graphic Design That Commands Attention'),

                        Textarea::make('hero_subheading')
                            ->label('Hero Subheading / Intro')
                            ->rows(3)
                            ->placeholder('Hi, I’m Entu — a multidisciplinary graphic designer...'),
                    ]),

                    Tabs\Tab::make('About Entu')->icon('heroicon-o-user')->schema([
                        TextInput::make('about_heading')
                            ->label('About Headline')
                            ->maxLength(255)
                            ->placeholder('Designing with Purpose, Crafting with Heart'),

                        RichEditor::make('about_body')
                            ->label('About Bio / Narrative')
                            ->columnSpanFull(),

                        Grid::make(2)->schema([
                            SpatieMediaLibraryFileUpload::make('bio_photo')
                                ->collection('bio_photo')
                                ->model($this->setting)
                                ->image()
                                ->imageEditor()
                                ->maxSize(10240)
                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                ->label('Portrait / Bio Photo')
                                ->helperText('Featured in About section and bio overlays (max 10MB).'),

                            SpatieMediaLibraryFileUpload::make('cv')
                                ->collection('cv')
                                ->model($this->setting)
                                ->maxSize(10240)
                                ->acceptedFileTypes([
                                    'application/pdf',
                                ])
                                ->label('Resume / CV (PDF)')
                                ->helperText('Downloadable resume on the About page (PDF only, max 10MB).'),
                        ]),
                    ]),

                    Tabs\Tab::make('Contact Info')->icon('heroicon-o-phone')->schema([
                        Grid::make(3)->schema([
                            TextInput::make('email')
                                ->email()
                                ->placeholder('hello@entucreative.com'),

                            TextInput::make('phone')
                                ->tel()
                                ->placeholder('+251 911 234 567'),

                            TextInput::make('location')
                                ->placeholder('Addis Ababa, Ethiopia'),
                        ]),
                    ]),

                    Tabs\Tab::make('Social Links')->icon('heroicon-o-globe-alt')->schema([
                        Grid::make(2)->schema([
                            TextInput::make('instagram')
                                ->url()
                                ->placeholder('https://instagram.com/entucreative'),

                            TextInput::make('behance')
                                ->url()
                                ->placeholder('https://behance.net/entucreative'),

                            TextInput::make('dribbble')
                                ->url()
                                ->placeholder('https://dribbble.com/entucreative'),

                            TextInput::make('linkedin')
                                ->url()
                                ->placeholder('https://linkedin.com/in/entucreative'),

                            TextInput::make('facebook')
                                ->url()
                                ->placeholder('https://facebook.com/entucreative'),

                            TextInput::make('tiktok')
                                ->url()
                                ->placeholder('https://tiktok.com/@entucreative'),
                        ]),
                    ]),

                    Tabs\Tab::make('Analytics & SEO')->icon('heroicon-o-chart-bar')->schema([
                        TextInput::make('analytics_id')
                            ->label('Analytics ID / Domain')
                            ->maxLength(255)
                            ->placeholder('e.g. G-XXXXXXXXXX for Google Analytics, or entucreative.com for Plausible')
                            ->helperText('Supports Plausible Analytics (domain name) or Google Analytics (GA4 Measurement ID starting with G-). Left blank to disable tracking.'),
                    ]),

                    Tabs\Tab::make('Footer')->icon('heroicon-o-document-text')->schema([
                        Textarea::make('footer_text')
                            ->rows(3)
                            ->label('Copyright & Footer Note')
                            ->placeholder('© 2026 Entu Creative. All rights reserved.'),
                    ]),
                ]),
            ])
            ->statePath('data')
            ->model($this->setting);
    }

    public function save(): void
    {
        $state = $this->form->getState();

        $this->setting->update($state);
        $this->form->model($this->setting)->saveRelationships();

        Notification::make()
            ->title('Settings saved successfully')
            ->success()
            ->send();
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Save Changes')
                ->submit('save')
                ->color('primary'),
        ];
    }
}
