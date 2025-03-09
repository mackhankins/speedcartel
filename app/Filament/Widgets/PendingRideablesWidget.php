<?php

namespace App\Filament\Widgets;

use App\Models\Rider;
use App\Models\User;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class PendingRideablesWidget extends BaseWidget
{
    protected static ?int $sort = 2;
    
    protected int|string|array $columnSpan = 'full';
    
    protected function getTableHeading(): string
    {
        return 'Pending Rider Relationships';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                User::query()
                    ->whereHas('riders', function (Builder $query) {
                        $query->wherePivot('status', false);
                    })
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('User')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('pending_riders_count')
                    ->label('Pending Riders')
                    ->counts('riders', function (Builder $query) {
                        return $query->wherePivot('status', false);
                    }),
                Tables\Columns\TextColumn::make('pending_riders')
                    ->label('Rider Names')
                    ->getStateUsing(function (User $user): string {
                        return $user->riders()
                            ->wherePivot('status', false)
                            ->get()
                            ->pluck('full_name')
                            ->join(', ');
                    }),
                Tables\Columns\TextColumn::make('relationships')
                    ->label('Relationships')
                    ->getStateUsing(function (User $user): string {
                        return $user->riders()
                            ->wherePivot('status', false)
                            ->get()
                            ->map(function ($rider) use ($user) {
                                $relationship = $user->riders()->wherePivot('rider_id', $rider->id)->first()->pivot->relationship;
                                return ucfirst($relationship);
                            })
                            ->join(', ');
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->action(function (User $user) {
                        $user->riders()->wherePivot('status', false)->updateExistingPivot(
                            $user->riders()->wherePivot('status', false)->pluck('rider_id'),
                            ['status' => true]
                        );
                    })
                    ->requiresConfirmation(),
                Tables\Actions\Action::make('view_details')
                    ->label('View Details')
                    ->icon('heroicon-o-eye')
                    ->url(fn (User $user) => route('filament.manage.resources.users.edit', $user))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('approve_selected')
                    ->label('Approve Selected')
                    ->icon('heroicon-o-check')
                    ->action(function ($records) {
                        foreach ($records as $user) {
                            $user->riders()->wherePivot('status', false)->updateExistingPivot(
                                $user->riders()->wherePivot('status', false)->pluck('rider_id'),
                                ['status' => true]
                            );
                        }
                    })
                    ->requiresConfirmation()
                    ->deselectRecordsAfterCompletion(),
            ])
            ->emptyStateHeading('No Pending Relationships')
            ->emptyStateDescription('There are no pending rider relationships that need approval.')
            ->emptyStateIcon('heroicon-o-user-group')
            ->defaultSort('name', 'asc')
            ->paginated([10, 25, 50]);
    }
} 