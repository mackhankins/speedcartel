<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TrackResource\Pages;
use App\Models\Track;
use App\Models\User;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TrackResource extends Resource
{
    protected static ?string $model = Track::class;

    protected static ?string $slug = 'tracks';

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';

    protected static ?string $navigationGroup = 'Athlete Management';

    protected static ?string $navigationLabel = 'Tracks';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Track Information')
                    ->schema([
                        TextInput::make('name')
                            ->required(),
                    ]),

                Section::make('Address Information')
                    ->schema([
                        TextInput::make('address_line1')
                            ->required()
                            ->label('Address Line 1'),
                        TextInput::make('address_line2')
                            ->label('Address Line 2'),
                        Grid::make()
                            ->schema([
                                TextInput::make('city'),
                                TextInput::make('state'),
                            ]),
                        Grid::make()
                            ->schema([
                                TextInput::make('postal_code')
                                    ->label('Postal/Zip Code'),
                                TextInput::make('country'),
                            ]),
                    ]),

                Section::make('Contact Information')
                    ->schema([
                        TextInput::make('email')
                            ->email(),
                        TextInput::make('phone'),
                        Select::make('local_contact_id')
                            ->label('Local Contact')
                            ->relationship('localContact', 'name')
                            ->searchable()
                            ->preload(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('address_line1')
                    ->label('Address')
                    ->searchable(),
                TextColumn::make('city')
                    ->sortable(),
                TextColumn::make('email')
                    ->searchable(),
                TextColumn::make('phone'),
                TextColumn::make('localContact.name')
                    ->label('Local Contact')
                    ->toggleable(),
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
            'index' => Pages\ListTracks::route('/'),
            'create' => Pages\CreateTrack::route('/create'),
            'edit' => Pages\EditTrack::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'name',
            'address_line1',
            'city',
            'email',
            'phone',
            'localContact.name',
        ];
    }
}
