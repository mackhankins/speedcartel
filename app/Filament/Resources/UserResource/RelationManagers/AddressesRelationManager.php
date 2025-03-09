<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AddressesRelationManager extends RelationManager
{
    protected static string $relationship = 'addresses';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('address_type')
                    ->options([
                        'shipping' => 'Shipping Address',
                        'billing' => 'Billing Address',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('full_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('address_line1')
                    ->label('Address Line 1')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('address_line2')
                    ->label('Address Line 2')
                    ->maxLength(255),
                Forms\Components\Grid::make()
                    ->schema([
                        Forms\Components\TextInput::make('city')
                            ->required()
                            ->maxLength(100),
                        Forms\Components\TextInput::make('state')
                            ->required()
                            ->maxLength(100),
                        Forms\Components\TextInput::make('postal_code')
                            ->required()
                            ->maxLength(20),
                    ])
                    ->columns(3),
                Forms\Components\TextInput::make('country')
                    ->required()
                    ->default('United States')
                    ->maxLength(100),
                Forms\Components\Toggle::make('is_default')
                    ->label('Default Address')
                    ->default(false),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('full_name')
            ->columns([
                Tables\Columns\TextColumn::make('address_type')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'shipping' => 'Shipping',
                        'billing' => 'Billing',
                        default => ucfirst($state),
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'shipping' => 'success',
                        'billing' => 'info',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('full_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('address_line1')
                    ->label('Address')
                    ->description(fn ($record) => $record->address_line2 ? $record->address_line2 : null)
                    ->searchable(),
                Tables\Columns\TextColumn::make('city_state_zip')
                    ->label('City, State ZIP')
                    ->getStateUsing(fn ($record) => "{$record->city}, {$record->state} {$record->postal_code}"),
                Tables\Columns\IconColumn::make('is_default')
                    ->label('Default')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
} 