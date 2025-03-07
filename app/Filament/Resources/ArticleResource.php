<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArticleResource\Pages;
use App\Models\Article;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use FilamentTiptapEditor\Enums\TiptapOutput;
use FilamentTiptapEditor\TiptapEditor;
use Illuminate\Database\Eloquent\Builder;
use RalphJSmit\Filament\SEO\SEO;

class ArticleResource extends Resource
{
    protected static ?string $model = Article::class;

    protected static ?string $slug = 'articles';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    TextInput::make('title')
                        ->label('Title')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn (string $state, callable $set) =>
                        $set('slug', str($state)->slug())),
                    TextInput::make('slug')
                        ->label('Slug')
                        ->required()
                        ->unique(ignorable: fn ($record) => $record)
                        ->maxLength(255),
                    Textarea::make('excerpt')
                        ->label('Excerpt')
                        ->maxLength(500)
                        ->rows(3),
                    Select::make('author_id')
                        ->relationship('author', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),
                    Select::make('status')
                        ->options([
                            'draft' => 'Draft',
                            'published' => 'Published',
                            'scheduled' => 'Scheduled',
                        ])
                        ->default('draft')
                        ->required(),
                    DateTimePicker::make('published_at')
                        ->label('Publish Date')
                        ->visible(fn (callable $get) => in_array($get('status'), ['published', 'scheduled']))
                        ->required(fn (callable $get) => $get('status') === 'scheduled'),
                ])->columns(2),
                Section::make('Content')->schema([
                    TiptapEditor::make('content')
                        ->profile('default')
                        ->disableFloatingMenus()
                        ->output(TiptapOutput::Html)
                        ->maxContentWidth('5xl')
                        ->columnSpanFull()
                        ->required(),
                ])->columnSpanFull(),
                Section::make('Media')->schema([
                    SpatieMediaLibraryFileUpload::make('photo')
                        ->collection('articles')
                        ->image()
                        ->imageEditor()
                        ->imageResizeMode('cover')
                        ->maxFiles(1)
                        ->required(),
                ])->columnSpan(['sm' => 6, 'lg' => 2, 'xl' => 2]),
                Section::make('Tags')->schema([
                    SpatieTagsInput::make('tags')
                        ->label('Tags')
                ])->columnSpan(['sm' => 6, 'lg' => 2, 'xl' => 2]),
                Section::make('SEO')->schema([
                    SEO::make(),
                ])->collapsed()
                    ->columnSpanFull(),
            ])->columns(4);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('photo')
                    ->collection('articles')
                    ->conversion('thumb')
                    ->size(50)
                    ->circular(),
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('author.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'published' => 'success',
                        'scheduled' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('published_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('tags')
                    ->badge(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('status')
                    ->options([
                        'published' => 'Published',
                        'draft' => 'Draft',
                        'scheduled' => 'Scheduled',
                    ]),
                SelectFilter::make('author')
                    ->relationship('author', 'name'),
            ])
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListArticles::route('/'),
            'create' => Pages\CreateArticle::route('/create'),
            'edit' => Pages\EditArticle::route('/{record}/edit'),
        ];
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['author', 'tags']);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'excerpt', 'author.name', 'tags.name'];
    }
}
