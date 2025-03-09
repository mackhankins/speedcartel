<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages;
use App\Filament\Resources\EventResource\RelationManagers;
use App\Models\Event;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Artisan;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Tables\Columns\SpatieTagsColumn;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Event Details')
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                        
                        SpatieTagsInput::make('tags')
                            ->type('races')
                            ->label('Race Categories'),

                        TextInput::make('url')
                            ->url()
                            ->maxLength(255),

                        RichEditor::make('description')
                            ->required()
                            ->columnSpanFull(),

                        TextInput::make('location')
                            ->required()
                            ->maxLength(255),

                        DateTimePicker::make('start_date')
                            ->required()
                            ->label('Event Start'),

                        DateTimePicker::make('end_date')
                            ->required()
                            ->label('Event End')
                            ->after('start_date'),

                        Toggle::make('is_all_day')
                            ->label('All Day Event'),

                        TextInput::make('recurrence_rule')
                            ->label('Recurrence Pattern')
                            ->placeholder('e.g. FREQ=WEEKLY;COUNT=10'),

                        Select::make('status')
                            ->options([
                                'confirmed' => 'Confirmed',
                                'tentative' => 'Tentative',
                                'cancelled' => 'Cancelled'
                            ])
                            ->default('confirmed')
                            ->required(),

                        Select::make('user_id')
                            ->relationship('user', 'name')
                            ->required()
                            ->label('Event Owner'),
                    ])
                    ->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                
                SpatieTagsColumn::make('tags')
                    ->label('Category')
                    ->type('races'),

                TextColumn::make('start_date')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('end_date')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('location')
                    ->searchable(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'published' => 'success',
                        'confirmed' => 'success',
                        'draft' => 'warning',
                        'tentative' => 'warning',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('user.name')
                    ->label('Owner')
                    ->sortable(),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }
}
