<?php

namespace App\Filament\Widgets;

use App\Models\Rider;
use Carbon\Carbon;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class UpcomingBirthdaysWidget extends BaseWidget
{
    protected static ?int $sort = 3;
    
    protected int|string|array $columnSpan = 'full';
    
    protected function getTableHeading(): string
    {
        return 'Upcoming Rider Birthdays';
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(function (): Builder {
                // Get all riders with date_of_birth not null
                return Rider::query()->whereNotNull('date_of_birth');
            })
            ->columns([
                Tables\Columns\TextColumn::make('full_name')
                    ->label('Rider')
                    ->sortable(),
                Tables\Columns\TextColumn::make('date_of_birth')
                    ->label('Birthday')
                    ->date('F j')
                    ->sortable(),
                Tables\Columns\TextColumn::make('turning_age')
                    ->label('Turning')
                    ->getStateUsing(function (Rider $rider): int {
                        $birthdate = Carbon::parse($rider->date_of_birth);
                        $nextBirthday = Carbon::create(
                            Carbon::now()->year,
                            $birthdate->month,
                            $birthdate->day
                        );
                        
                        // If the birthday has already occurred this year, calculate for next year
                        if ($nextBirthday->isPast()) {
                            return Carbon::now()->addYear()->year - $birthdate->year;
                        }
                        
                        return Carbon::now()->year - $birthdate->year;
                    }),
                Tables\Columns\TextColumn::make('days_until')
                    ->label('Days Until')
                    ->getStateUsing(function (Rider $rider): int {
                        $birthdate = Carbon::parse($rider->date_of_birth);
                        $nextBirthday = Carbon::create(
                            Carbon::now()->year,
                            $birthdate->month,
                            $birthdate->day
                        );
                        
                        // If the birthday has already occurred this year, calculate for next year
                        if ($nextBirthday->isPast()) {
                            $nextBirthday->addYear();
                        }
                        
                        return Carbon::now()->diffInDays($nextBirthday, false);
                    })
                    ->sortable(false), // Make it clear this is not sortable at the database level
            ])
            ->emptyStateHeading('No birthdays found')
            ->emptyStateDescription('There are no riders with birthdays in the system.')
            ->emptyStateIcon('heroicon-o-cake')
            ->paginated(false)
            ->recordClasses(function (Rider $rider) {
                // Calculate days until birthday
                $birthdate = Carbon::parse($rider->date_of_birth);
                $nextBirthday = Carbon::create(
                    Carbon::now()->year,
                    $birthdate->month,
                    $birthdate->day
                );
                
                // If the birthday has already occurred this year, calculate for next year
                if ($nextBirthday->isPast()) {
                    $nextBirthday->addYear();
                }
                
                $daysUntil = Carbon::now()->diffInDays($nextBirthday, false);
                
                // Highlight rows based on how soon the birthday is
                if ($daysUntil === 0) {
                    return 'bg-success-500/10';
                }
                
                if ($daysUntil <= 7) {
                    return 'bg-danger-500/10';
                }
                
                if ($daysUntil <= 14) {
                    return 'bg-warning-500/10';
                }
                
                return '';
            })
            ->modifyQueryUsing(function (Builder $query) {
                // Get all riders
                $riders = $query->get();
                
                // Calculate days until birthday for each rider
                $ridersWithDays = $riders->map(function (Rider $rider) {
                    $birthdate = Carbon::parse($rider->date_of_birth);
                    $nextBirthday = Carbon::create(
                        Carbon::now()->year,
                        $birthdate->month,
                        $birthdate->day
                    );
                    
                    // If the birthday has already occurred this year, calculate for next year
                    if ($nextBirthday->isPast()) {
                        $nextBirthday->addYear();
                    }
                    
                    $daysUntil = Carbon::now()->diffInDays($nextBirthday, false);
                    $rider->days_until = $daysUntil;
                    
                    return $rider;
                })->sortBy('days_until')->take(5); // Sort by days_until in PHP, not in the database
                
                // Get the IDs of the 5 riders with the closest birthdays
                $riderIds = $ridersWithDays->pluck('id')->toArray();
                
                // Modify the query to only include these riders
                return $query->whereIn('id', $riderIds);
            });
    }
} 