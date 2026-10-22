<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Models\Project;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationGroup = 'Portfolio';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(3)->schema([
                    // Main column (2 cols wide)
                    Grid::make(1)->schema([
                        Section::make('Project Overview')->schema([
                            TextInput::make('title')
                                ->required()
                                ->maxLength(255)
                                ->live(onBlur: true)
                                ->afterStateUpdated(function (string $operation, ?string $state, Set $set) {
                                    if ($operation === 'create' && filled($state)) {
                                        $set('slug', Str::slug($state));
                                    }
                                }),

                            TextInput::make('slug')
                                ->required()
                                ->maxLength(255)
                                ->unique(Project::class, 'slug', ignoreRecord: true),

                            Grid::make(3)->schema([
                                Select::make('category_id')
                                    ->relationship('category', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->nullable()
                                    ->label('Category'),

                                TextInput::make('client')
                                    ->maxLength(255)
                                    ->placeholder('e.g. Kaffa Roasters'),

                                TextInput::make('year')
                                    ->maxLength(10)
                                    ->placeholder('e.g. 2026'),
                            ]),

                            Textarea::make('summary')
                                ->rows(3)
                                ->maxLength(500)
                                ->placeholder('A concise summary highlighting the core project challenge and result.'),

                            RichEditor::make('description')
                                ->label('Detailed Description')
                                ->columnSpanFull(),

                            TagsInput::make('tools_used')
                                ->placeholder('Add tool & press Enter')
                                ->suggestions([
                                    'Adobe Illustrator',
                                    'Adobe Photoshop',
                                    'Adobe InDesign',
                                    'Figma',
                                    'After Effects',
                                    'Procreate',
                                    'Blender',
                                ]),

                            TextInput::make('project_url')
                                ->url()
                                ->prefix('https://')
                                ->placeholder('clientwebsite.com'),
                        ]),

                        Section::make('Search Engine Optimization (SEO)')
                            ->collapsible()
                            ->collapsed()
                            ->schema([
                                TextInput::make('seo_title')
                                    ->label('Meta Title')
                                    ->maxLength(255)
                                    ->placeholder('Leave blank to use project title'),

                                Textarea::make('seo_description')
                                    ->label('Meta Description')
                                    ->rows(3)
                                    ->maxLength(300)
                                    ->placeholder('Brief overview for search engines and social sharing'),
                            ]),
                    ])->columnSpan(2),

                    // Sidebar column (1 col wide)
                    Grid::make(1)->schema([
                        Section::make('Publishing & Visibility')->schema([
                            Toggle::make('is_published')
                                ->label('Published')
                                ->default(false)
                                ->live()
                                ->afterStateUpdated(function (bool $state, Set $set, $get) {
                                    if ($state && blank($get('published_at'))) {
                                        $set('published_at', now()->toDateTimeString());
                                    }
                                }),

                            Toggle::make('is_featured')
                                ->label('Featured on Homepage')
                                ->default(false),

                            DateTimePicker::make('published_at')
                                ->label('Publish Date')
                                ->default(now())
                                ->native(false),

                            TextInput::make('sort_order')
                                ->numeric()
                                ->default(0)
                                ->label('Sort Order Priority'),
                        ]),

                        Section::make('Cover Image')->schema([
                            SpatieMediaLibraryFileUpload::make('cover')
                                ->collection('cover')
                                ->image()
                                ->imageEditor()
                                ->imageEditorAspectRatios([
                                    '16:9',
                                    '4:3',
                                    '1:1',
                                ])
                                ->helperText('Main visual featured on portfolio cards (600w & 1600w WebP generated).'),
                        ]),

                        Section::make('Project Gallery')->schema([
                            SpatieMediaLibraryFileUpload::make('gallery')
                                ->collection('gallery')
                                ->multiple()
                                ->reorderable()
                                ->image()
                                ->imageEditor()
                                ->helperText('Upload and drag-to-reorder detail shots and process work.'),
                        ]),
                    ])->columnSpan(1),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('cover')
                    ->collection('cover')
                    ->conversion('thumb')
                    ->square()
                    ->size(54)
                    ->label('Cover'),

                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (Project $record): ?string => $record->client),

                TextColumn::make('category.name')
                    ->label('Category')
                    ->badge()
                    ->color('gray')
                    ->sortable()
                    ->searchable(),

                ToggleColumn::make('is_featured')
                    ->label('Featured')
                    ->sortable(),

                ToggleColumn::make('is_published')
                    ->label('Published')
                    ->sortable(),

                TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable(),

                TextColumn::make('published_at')
                    ->date('M j, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->reorderable('sort_order')
            ->defaultSort('sort_order', 'asc')
            ->filters([
                SelectFilter::make('category_id')
                    ->relationship('category', 'name')
                    ->label('Category')
                    ->preload(),

                TernaryFilter::make('is_published')
                    ->label('Published'),

                TernaryFilter::make('is_featured')
                    ->label('Featured'),

                TrashedFilter::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
                RestoreAction::make(),
                ForceDeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProjects::route('/'),
            'create' => Pages\CreateProject::route('/create'),
            'edit' => Pages\EditProject::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
