<?php

namespace App\Filament\Resources\RiderResource\RelationManagers;

use App\Models\Plate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PlatesRelationManager extends RelationManager
{
    protected static string $relationship = 'plates';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('type')
                    ->options(collect(Plate::$typeOptions)->pluck('name', 'value')->toArray())
                    ->required(),
                Forms\Components\TextInput::make('number')
                    ->required()
                    ->maxLength(10),
                Forms\Components\TextInput::make('year')
                    ->numeric()
                    ->required()
                    ->default(date('Y')),
                Forms\Components\Toggle::make('is_current')
                    ->label('Current Plate')
                    ->default(true),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('number')
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->formatStateUsing(function ($state): string {
                        if (!is_string($state)) {
                            return '';
                        }
                        $type = collect(Plate::$typeOptions)->firstWhere('value', $state);
                        return $type ? $type['name'] : ucfirst($state);
                    }),
                Tables\Columns\TextColumn::make('number')
                    ->label('Plate #')
                    ->searchable(),
                Tables\Columns\TextColumn::make('year')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_current')
                    ->label('Current')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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