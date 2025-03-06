<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArticleResource\Pages;
use App\Models\Article;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Table;
use FilamentTiptapEditor\Enums\TiptapOutput;
use FilamentTiptapEditor\TiptapEditor;
use RalphJSmit\Filament\SEO\SEO;

class ArticleResource extends Resource
{
    protected static ?string $model = Article::class;

    protected static ?string $slug = 'articles';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()->schema([
                    TextInput::make('title')->label('Title')->required(),
                    TextInput::make('slug')->label('Slug')->required(),
                    TiptapEditor::make('content')
                        ->profile('default')
                        ->disableFloatingMenus()
                        ->output(TiptapOutput::Html)
                        ->maxContentWidth('5xl')
                        ->columnSpanFull()
                        ->required(),

                ])->columnSpan(4),
                Section::make('Media')->schema([
                    SpatieMediaLibraryFileUpload::make('photo')
                        ->collection('articles')
                        ->responsiveImages()
                        ->maxFiles(1)
                        ->required(),
                ])->columnSpan(['sm' => 6, 'lg' => 2, 'xl' => 2]),
                Section::make('SEO')->schema([
                    SEO::make(),
                ])->collapsed()
                ->columnSpanFull(),
            ])->columns(6);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            ])
            ->filters([
                //
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

    public static function getGloballySearchableAttributes(): array
    {
        return [];
    }
}
