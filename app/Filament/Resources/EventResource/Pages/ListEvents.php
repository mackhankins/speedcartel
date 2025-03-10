<?php

namespace App\Filament\Resources\EventResource\Pages;

use App\Filament\Resources\EventResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Artisan;

class ListEvents extends ListRecords
{
    protected static string $resource = EventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('import')
                ->label('Import BMX Events')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Import BMX Events')
                ->modalDescription('This will scrape the latest events from the USA BMX website and import them into the database. This process may take a few minutes.')
                ->modalSubmitActionLabel('Yes, Import Events')
                ->action(function () {
                    // Show a notification that the import is starting
                    Notification::make()
                        ->title('Import Started')
                        ->body('Scraping and importing BMX events. This may take a few minutes...')
                        ->info()
                        ->send();
                    
                    // Run the import command (default behavior now just scrapes for new events)
                    $exitCode = Artisan::call('import:bmx-events');
                    $output = Artisan::output();
                    
                    if ($exitCode === 0) {
                        Notification::make()
                            ->title('BMX Events Imported Successfully')
                            ->success()
                            ->send();
                    } else {
                        Notification::make()
                            ->title('Import Failed')
                            ->body($output)
                            ->danger()
                            ->send();
                    }
                    
                    $this->refreshData();
                }),
        ];
    }
}
