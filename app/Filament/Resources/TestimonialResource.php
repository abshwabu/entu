<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TestimonialResource\Pages;
use App\Models\Testimonial;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class TestimonialResource extends Resource
{
    protected static ?string $model = Testimonial::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';

    protected static ?string $navigationGroup = 'Site Content';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(3)->schema([
                    Grid::make(1)->schema([
                        Section::make('Client Feedback')->schema([
                            TextInput::make('author_name')
                                ->required()
                                ->maxLength(255)
                                ->label('Client Name')
                                ->placeholder('e.g. Sarah Jenkins'),

                            TextInput::make('author_role')
                                ->required()
                                ->maxLength(255)
                                ->label('Role & Company')
                                ->placeholder('e.g. Creative Director, Apex Studio'),

                            Textarea::make('quote')
                                ->required()
                                ->rows(4)
                                ->placeholder('Client testimonial text...'),
                        ]),
                    ])->columnSpan(2),

                    Grid::make(1)->schema([
                        Section::make('Photo & Status')->schema([
                            SpatieMediaLibraryFileUpload::make('avatar')
                                ->collection('avatar')
                                ->image()
                                ->avatar()
                                ->imageEditor()
                                ->label('Client Avatar'),

                            Toggle::make('is_published')
                                ->label('Published on Site')
                                ->default(true),

                            TextInput::make('sort_order')
                                ->numeric()
                                ->default(0)
                                ->label('Sort Order'),
                        ]),
                    ])->columnSpan(1),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('avatar')
                    ->collection('avatar')
                    ->circular()
                    ->label('Avatar'),

                TextColumn::make('author_name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('author_role')
                    ->color('gray')
                    ->searchable(),

                ToggleColumn::make('is_published')
                    ->label('Published')
                    ->sortable(),

                TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable(),
            ])
            ->reorderable('sort_order')
            ->defaultSort('sort_order', 'asc')
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
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
            'index' => Pages\ListTestimonials::route('/'),
            'create' => Pages\CreateTestimonial::route('/create'),
            'edit' => Pages\EditTestimonial::route('/{record}/edit'),
        ];
    }
}
