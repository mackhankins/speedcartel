<?php

namespace App\Console\Commands;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImportBmxEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:bmx-events {--source= : Path to the events.json file} {--reset : Clear existing events before import}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import USA BMX events from JSON file';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $sourcePath = $this->option('source') ?: 'C:\Users\Mack\projects\bmx_events\events.json';
        
        // Check if file exists
        if (!File::exists($sourcePath)) {
            $this->error("File not found: {$sourcePath}");
            return 1;
        }

        // Copy file to storage if needed
        $storagePath = storage_path('app/events.json');
        File::copy($sourcePath, $storagePath);
        
        $this->info("File copied to storage: {$storagePath}");
        
        // Read JSON file
        $jsonContent = File::get($storagePath);
        $events = json_decode($jsonContent, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->error('Invalid JSON file: ' . json_last_error_msg());
            return 1;
        }
        
        $this->info('Found ' . count($events) . ' events in the JSON file');
        
        // Display sample of first event for debugging
        if (count($events) > 0) {
            $this->info('Sample event structure:');
            $this->line(json_encode($events[0], JSON_PRETTY_PRINT));
        }
        
        // Reset events if requested
        if ($this->option('reset')) {
            if ($this->confirm('Are you sure you want to delete all existing events?', false)) {
                Event::query()->forceDelete();
                $this->info('All existing events have been deleted');
            }
        }
        
        $bar = $this->output->createProgressBar(count($events));
        $bar->start();
        
        $imported = 0;
        $skipped = 0;
        $errors = 0;
        
        foreach ($events as $eventData) {
            try {
                // Check if event already exists by title and date
                $startDate = null;
                $endDate = null;
                
                // Try different date field names
                if (!empty($eventData['start_date'])) {
                    $startDate = Carbon::parse($eventData['start_date']);
                } elseif (!empty($eventData['date'])) {
                    $startDate = Carbon::parse($eventData['date']);
                } elseif (!empty($eventData['start'])) {
                    $startDate = Carbon::parse($eventData['start']);
                } else {
                    // Skip events without a date
                    $skipped++;
                    $bar->advance();
                    continue;
                }
                
                if (!empty($eventData['end_date'])) {
                    $endDate = Carbon::parse($eventData['end_date']);
                } elseif (!empty($eventData['end'])) {
                    $endDate = Carbon::parse($eventData['end']);
                } else {
                    // If no end date, use start date
                    $endDate = $startDate->copy();
                }
                
                $existingEvent = Event::where('title', $eventData['title'] ?? $eventData['name'] ?? '')
                    ->where('start_date', $startDate)
                    ->first();
                
                if ($existingEvent) {
                    $skipped++;
                    $bar->advance();
                    continue;
                }
                
                // Create new event
                $event = new Event();
                $event->title = $eventData['title'] ?? $eventData['name'] ?? 'Untitled Event';
                $event->description = $eventData['description'] ?? $eventData['details'] ?? '';
                $event->location = $eventData['location'] ?? $eventData['venue'] ?? '';
                $event->start_date = $startDate;
                $event->end_date = $endDate;
                $event->is_all_day = true;
                $event->url = $eventData['url'] ?? $eventData['link'] ?? '';
                $event->status = 'confirmed';
                
                // Set user_id to the first admin user or create a default
                $event->user_id = 1; // Assuming user ID 1 exists
                
                $event->save();
                
                // Instead of multiple tags, we'll select just one primary tag
                $primaryTag = null;
                
                // First priority: Use the event category if available
                if (!empty($eventData['category']) && is_string($eventData['category'])) {
                    $primaryTag = trim($eventData['category']);
                }
                
                // Second priority: Use the first tag with type 'event_category' if available
                if (!$primaryTag && !empty($eventData['tags']) && is_array($eventData['tags'])) {
                    foreach ($eventData['tags'] as $tagData) {
                        if (is_array($tagData) && 
                            isset($tagData['type']) && 
                            $tagData['type'] === 'event_category' && 
                            isset($tagData['name']) && 
                            is_string($tagData['name'])) {
                            $primaryTag = trim($tagData['name']);
                            break;
                        }
                    }
                }
                
                // Third priority: Use the event type if available
                if (!$primaryTag && !empty($eventData['type']) && is_string($eventData['type'])) {
                    $primaryTag = trim($eventData['type']);
                }
                
                // Fourth priority: Use the first tag name if available
                if (!$primaryTag && !empty($eventData['tags']) && is_array($eventData['tags'])) {
                    foreach ($eventData['tags'] as $tagData) {
                        if (is_array($tagData) && isset($tagData['name']) && is_string($tagData['name'])) {
                            $primaryTag = trim($tagData['name']);
                            break;
                        } elseif (is_string($tagData)) {
                            $primaryTag = trim($tagData);
                            break;
                        }
                    }
                }
                
                // Default to 'BMX Event' if no suitable tag was found
                if (!$primaryTag || strlen($primaryTag) < 2) {
                    $primaryTag = 'BMX Event';
                }
                
                // Format the tag (lowercase for consistency in storage)
                $primaryTag = strtolower($primaryTag);
                
                // Attach the single tag to the event
                try {
                    $event->attachTag($primaryTag, 'races');
                    $this->info("  - Tagged as: {$primaryTag}");
                } catch (\Exception $e) {
                    $this->warn("Could not attach tag '{$primaryTag}': " . $e->getMessage());
                }
                
                $imported++;
            } catch (\Exception $e) {
                $errors++;
                $this->error("Error importing event: {$eventData['title']} - {$e->getMessage()}");
            }
            
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine(2);
        
        $this->info("Import completed:");
        $this->info("- Imported: {$imported}");
        $this->info("- Skipped (duplicates): {$skipped}");
        $this->info("- Errors: {$errors}");
        
        return 0;
    }
}
