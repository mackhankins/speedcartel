<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Models\Rider;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RidersRelationManager extends RelationManager
{
    protected static string $relationship = 'riders';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('relationship')
                    ->options([
                        'parent' => 'Parent',
                        'coach' => 'Coach',
                        'manager' => 'Manager',
                        'self' => 'Self',
                        'other' => 'Other',
                    ])
                    ->required(),
                Forms\Components\Toggle::make('status')
                    ->label('Active')
                    ->helperText('Toggle to set the relationship as active or inactive')
                    ->onColor('success')
                    ->offColor('danger')
                    ->default(true),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitle(fn (Rider $record): string => "{$record->firstname} {$record->lastname}")
            ->columns([
                Tables\Columns\ImageColumn::make('profile_pic')
                    ->label('Photo')
                    ->circular(),
                Tables\Columns\TextColumn::make('firstname')
                    ->label('First Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('lastname')
                    ->label('Last Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date_of_birth')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('skill_level')
                    ->badge()
                    ->formatStateUsing(function ($state): string {
                        if (!is_string($state)) {
                            return '';
                        }
                        return ucfirst($state);
                    })
                    ->color(function ($state): string {
                        if (!is_string($state)) {
                            return 'gray';
                        }
                        return match ($state) {
                            'novice' => 'gray',
                            'intermediate' => 'info',
                            'expert' => 'warning',
                            'pro' => 'success',
                            default => 'gray',
                        };
                    }),
                Tables\Columns\TextColumn::make('pivot.relationship')
                    ->badge()
                    ->formatStateUsing(function ($state): string {
                        return is_string($state) ? ucfirst($state) : '';
                    }),
                Tables\Columns\IconColumn::make('pivot.status')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->preloadRecordSelect()
                    ->recordSelectSearchColumns(['firstname', 'lastname'])
                    ->form(fn (Tables\Actions\AttachAction $action): array => [
                        $action->getRecordSelect(),
                        Forms\Components\Select::make('relationship')
                            ->options([
                                'parent' => 'Parent',
                                'coach' => 'Coach',
                                'manager' => 'Manager',
                                'self' => 'Self',
                                'other' => 'Other',
                            ])
                            ->required(),
                        Forms\Components\Toggle::make('status')
                            ->label('Active')
                            ->default(true),
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ]);
    }
} 