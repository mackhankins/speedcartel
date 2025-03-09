<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RiderResource\Pages;
use App\Filament\Resources\RiderResource\RelationManagers;
use App\Models\Rider;
use App\Models\Track;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RiderResource extends Resource
{
    protected static ?string $model = Rider::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    
    protected static ?string $navigationGroup = 'Athlete Management';
    
    protected static ?string $recordTitleAttribute = 'full_name';
    
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\TextInput::make('firstname')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('lastname')
                                    ->required()
                                    ->maxLength(255),
                            ]),
                        Forms\Components\TextInput::make('nickname')
                            ->maxLength(255),
                        Forms\Components\DatePicker::make('date_of_birth')
                            ->required()
                            ->maxDate(now()),
                        Forms\Components\Select::make('home_track')
                            ->label('Home Track')
                            ->options(Track::all()->pluck('name', 'id'))
                            ->searchable(),
                    ]),
                
                Forms\Components\Section::make('Racing Information')
                    ->schema([
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\CheckboxList::make('class')
                                    ->options(Rider::$classOptions)
                                    ->required(),
                                Forms\Components\Select::make('skill_level')
                                    ->options(Rider::$skillLevelOptions)
                                    ->required(),
                            ])
                            ->columns(2),
                    ]),
                
                Forms\Components\Section::make('Profile Picture')
                    ->schema([
                        Forms\Components\FileUpload::make('profile_pic')
                            ->image()
                            ->directory('profile-photos')
                            ->visibility('public')
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('1:1')
                            ->imageResizeTargetWidth('300')
                            ->imageResizeTargetHeight('300'),
                    ]),
                
                Forms\Components\Section::make('Social Profiles')
                    ->schema([
                        Forms\Components\Grid::make()
                            ->schema(function () {
                                $fields = [];
                                
                                // Map platform keys to appropriate Heroicons
                                $iconMap = [
                                    'instagram' => 'heroicon-m-camera',
                                    'facebook' => 'heroicon-m-globe-alt',
                                    'twitter' => 'heroicon-m-at-symbol',
                                    'tiktok' => 'heroicon-m-play',
                                    'youtube' => 'heroicon-m-video-camera'
                                ];
                                
                                foreach (Rider::$socialProfileOptions as $key => $label) {
                                    $fields[] = Forms\Components\TextInput::make("social_profiles.{$key}")
                                        ->label($label)
                                        ->url()
                                        ->prefixIcon($iconMap[$key] ?? 'heroicon-m-link')
                                        ->placeholder("Enter {$label} URL");
                                }
                                
                                return $fields;
                            })
                            ->columns(2),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('profile_pic')
                    ->label('Photo')
                    ->circular(),
                Tables\Columns\TextColumn::make('full_name')
                    ->label('Name')
                    ->searchable(['firstname', 'lastname'])
                    ->sortable(['firstname', 'lastname']),
                Tables\Columns\TextColumn::make('nickname')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date_of_birth')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('homeTrack.name')
                    ->label('Home Track')
                    ->searchable(),
                Tables\Columns\TextColumn::make('skill_level')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->color(fn (string $state): string => match ($state) {
                        'novice' => 'gray',
                        'intermediate' => 'info',
                        'expert' => 'warning',
                        'pro' => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('class')
                    ->formatStateUsing(function ($state): string {
                        if (!is_array($state)) {
                            return is_string($state) ? ucfirst($state) : '';
                        }
                        return implode(', ', array_map('ucfirst', $state));
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('skill_level')
                    ->options(Rider::$skillLevelOptions),
                Tables\Filters\SelectFilter::make('class')
                    ->options(Rider::$classOptions)
                    ->multiple(),
                Tables\Filters\SelectFilter::make('home_track')
                    ->relationship('homeTrack', 'name'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
            RelationManagers\PlatesRelationManager::make(),
            RelationManagers\UsersRelationManager::make(),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRiders::route('/'),
            'create' => Pages\CreateRider::route('/create'),
            'view' => Pages\ViewRider::route('/{record}'),
            'edit' => Pages\EditRider::route('/{record}/edit'),
        ];
    }
}
