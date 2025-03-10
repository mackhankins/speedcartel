<?php

namespace App\Console\Commands;

use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class ImportBmxEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:bmx-events 
                            {--source= : Path to the events.json file (default: storage/app/events.json)}
                            {--reset : Clear existing events before import}
                            {--scrape : Scrape new events from USA BMX website}
                            {--initial : Perform initial import from events.json (only needed once)}
                            {--test : Test mode - force import all scraped events as new}
                            {--keep-temp : Keep the temp file after import}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import USA BMX events from JSON file or scrape from website';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $storagePath = storage_path('app/events.json');
        $tempOutputPath = storage_path('app/temp_events.json');
        
        // If scrape option is set, run the Node.js scraper
        if ($this->option('scrape')) {
            $this->info('Scraping events from USA BMX website...');
            
            $nodePath = $this->findNodeExecutable();
            if (!$nodePath) {
                $this->error('Node.js executable not found. Please make sure Node.js is installed.');
                return 1;
            }
            
            $scraperPath = base_path('scripts/node/bmx_scraper.js');
            
            // Check if scraper exists
            if (!File::exists($scraperPath)) {
                $this->error("Scraper script not found: {$scraperPath}");
                return 1;
            }
            
            // Check if Puppeteer is installed
            if (!$this->isPuppeteerInstalled()) {
                $this->info('Puppeteer not found. Installing dependencies...');
                if (!$this->installNodeDependencies()) {
                    $this->error('Failed to install Node.js dependencies.');
                    return 1;
                }
            }
            
            // Run the scraper - don't modify events.json, just get new events
            $process = new Process([$nodePath, $scraperPath, $tempOutputPath]);
            $process->setTimeout(300); // 5 minutes timeout
            
            try {
                $process->mustRun(function ($type, $buffer) {
                    if (Process::ERR === $type) {
                        $this->error($buffer);
                    } else {
                        $this->line($buffer);
                    }
                });
                
                $this->info('Scraping completed successfully.');
            } catch (ProcessFailedException $exception) {
                $this->error('Scraping failed: ' . $exception->getMessage());
                return 1;
            }
        } else if (!$this->option('initial') && !$this->option('test')) {
            // If neither --scrape, --initial, nor --test is specified, default to scrape
            $this->info('No options specified, defaulting to scrape for new events...');
            return $this->call('import:bmx-events', ['--scrape' => true]);
        }
        
        // If we're doing an initial import, use the provided source file or default
        if ($this->option('initial')) {
            $sourcePath = $this->option('source') ?: $storagePath;
            
            // Check if file exists
            if (!File::exists($sourcePath)) {
                $this->error("File not found: {$sourcePath}");
                return 1;
            }
            
            // If source is not the default storage path, copy file to storage
            if ($sourcePath !== $storagePath) {
                File::copy($sourcePath, $storagePath);
                $this->info("File copied to storage: {$storagePath}");
            } else {
                $this->info("Using file from storage: {$storagePath}");
            }
            
            // If we also want to scrape new events and merge them with the initial import
            if ($this->option('scrape')) {
                $this->info('Scraping new events and merging with initial import...');
                
                // Run the scraper with the --merge option to update events.json
                if (!$this->runScraperWithMerge($storagePath)) {
                    return 1;
                }
            }
            
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
            
            // Reset events if requested (but we generally don't want to do this)
            if ($this->option('reset')) {
                if ($this->confirm('Are you sure you want to delete all existing events?', false)) {
                    Event::query()->forceDelete();
                    $this->info('All existing events have been deleted');
                }
            }
            
            $this->importEvents($events, $this->option('test'));
            return 0;
        }
        
        // For test mode without scrape, we need to check if the temp file exists
        if ($this->option('test') && !$this->option('scrape') && !File::exists($tempOutputPath)) {
            $this->error("No temp events file found. Run with --scrape first or use --scrape --test together.");
            return 1;
        }
        
        // For scrape option or test mode, we want to import newly scraped events
        // Read the temp file that contains only the new events
        if (File::exists($tempOutputPath)) {
            $jsonContent = File::get($tempOutputPath);
            $newEvents = json_decode($jsonContent, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                $this->error('Invalid JSON file: ' . json_last_error_msg());
                return 1;
            }
            
            $this->info('Found ' . count($newEvents) . ' new events to process');
            
            // Import the new events
            $this->importEvents($newEvents, $this->option('test'));
            
            // Clean up the temporary file unless we're in test mode and want to keep it
            if (!($this->option('test') && $this->option('keep-temp'))) {
                File::delete($tempOutputPath);
            }
        } else if (!$this->option('initial')) {
            $this->info('No events to process.');
        }
        
        return 0;
    }
    
    /**
     * Run the scraper with the --merge option to update events.json
     */
    protected function runScraperWithMerge($storagePath)
    {
        $nodePath = $this->findNodeExecutable();
        if (!$nodePath) {
            $this->error('Node.js executable not found. Please make sure Node.js is installed.');
            return false;
        }
        
        $scraperPath = base_path('scripts/node/bmx_scraper.js');
        
        // Check if scraper exists
        if (!File::exists($scraperPath)) {
            $this->error("Scraper script not found: {$scraperPath}");
            return false;
        }
        
        // Run the scraper with the --merge option
        $process = new Process([$nodePath, $scraperPath, $storagePath, '--merge', $storagePath]);
        $process->setTimeout(300); // 5 minutes timeout
        
        try {
            $process->mustRun(function ($type, $buffer) {
                if (Process::ERR === $type) {
                    $this->error($buffer);
                } else {
                    $this->line($buffer);
                }
            });
            
            $this->info('Scraping and merging completed successfully.');
            return true;
        } catch (ProcessFailedException $exception) {
            $this->error('Scraping and merging failed: ' . $exception->getMessage());
            return false;
        }
    }
    
    /**
     * Import events from the provided array
     */
    protected function importEvents(array $events, bool $testMode = false)
    {
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
                
                // In test mode, we skip the duplicate check
                $existingEvent = null;
                if (!$testMode) {
                    // Improved duplicate detection - check by title and date range
                    $existingEvent = Event::where(function ($query) use ($eventData, $startDate) {
                        $query->where('title', $eventData['title'] ?? $eventData['name'] ?? '')
                              ->where('start_date', $startDate);
                    })->orWhere(function ($query) use ($eventData, $startDate, $endDate) {
                        // Also check for events with the same URL if available
                        if (!empty($eventData['url'])) {
                            $query->where('url', $eventData['url']);
                        }
                    })->first();
                }
                
                if ($existingEvent && !$testMode) {
                    $skipped++;
                    $bar->advance();
                    continue;
                }
                
                // Prepare event data for database
                $title = $eventData['title'] ?? $eventData['name'] ?? 'Untitled Event';
                $description = $eventData['description'] ?? $eventData['details'] ?? '';
                $location = $eventData['location'] ?? $eventData['venue'] ?? '';
                $url = $eventData['url'] ?? $eventData['link'] ?? '';
                
                // Determine primary tag
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
                
                if ($testMode) {
                    // In test mode, display detailed information about what would be imported
                    $this->newLine();
                    $this->info("Event that would be imported:");
                    $this->line("---------------------------");
                    $this->line("Title:       " . $title);
                    $this->line("Description: " . (strlen($description) > 50 ? substr($description, 0, 50) . "..." : $description));
                    $this->line("Location:    " . $location);
                    $this->line("Start Date:  " . $startDate->format('Y-m-d H:i:s'));
                    $this->line("End Date:    " . $endDate->format('Y-m-d H:i:s'));
                    $this->line("URL:         " . $url);
                    $this->line("Tag:         " . $primaryTag);
                    $this->line("---------------------------");
                    
                    // Show the raw data for debugging
                    $this->line("Raw JSON data:");
                    $this->line(json_encode($eventData, JSON_PRETTY_PRINT));
                    $this->newLine();
                    
                    $imported++;
                    $bar->advance();
                    continue;
                }
                
                // Create new event
                $event = new Event();
                $event->title = $title;
                $event->description = $description;
                $event->location = $location;
                $event->start_date = $startDate;
                $event->end_date = $endDate;
                $event->is_all_day = true;
                $event->url = $url;
                $event->status = 'confirmed';
                
                // Set user_id to the first admin user or create a default
                $event->user_id = 1; // Assuming user ID 1 exists
                
                $event->save();
                
                // Attach the single tag to the event
                try {
                    $event->attachTag($primaryTag, 'races');
                } catch (\Exception $e) {
                    $this->warn("Could not attach tag '{$primaryTag}': " . $e->getMessage());
                }
                
                $imported++;
            } catch (\Exception $e) {
                $errors++;
                $this->error("Error importing event: " . ($eventData['title'] ?? 'Unknown') . " - {$e->getMessage()}");
            }
            
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine(2);
        
        if ($testMode) {
            $this->info("Test import completed (no actual database changes):");
            $this->info("- Would import: {$imported}");
            $this->info("- Would skip (duplicates): {$skipped}");
            $this->info("- Errors: {$errors}");
        } else {
            $this->info("Import completed:");
            $this->info("- Imported: {$imported}");
            $this->info("- Skipped (duplicates): {$skipped}");
            $this->info("- Errors: {$errors}");
        }
    }

    /**
     * Find the Node.js executable path.
     *
     * @return string|null
     */
    protected function findNodeExecutable()
    {
        // Try common Node.js executable paths
        $possiblePaths = [
            'node',
            '/usr/bin/node',
            '/usr/local/bin/node',
            'C:\\Program Files\\nodejs\\node.exe',
            'C:\\Program Files (x86)\\nodejs\\node.exe',
        ];
        
        foreach ($possiblePaths as $path) {
            try {
                $process = new Process([$path, '--version']);
                $process->run();
                
                if ($process->isSuccessful()) {
                    return $path;
                }
            } catch (\Exception $e) {
                // Continue to the next path
            }
        }
        
        return null;
    }

    /**
     * Check if Puppeteer is installed
     */
    protected function isPuppeteerInstalled()
    {
        $nodeModulesPath = base_path('scripts/node/node_modules/puppeteer');
        return File::exists($nodeModulesPath);
    }
    
    /**
     * Install Node.js dependencies
     */
    protected function installNodeDependencies()
    {
        $this->info('Installing Node.js dependencies...');
        
        $npmPath = $this->findNpmExecutable();
        if (!$npmPath) {
            $this->error('npm executable not found. Please make sure npm is installed.');
            return false;
        }
        
        // Check if we're on Linux and try to install Chrome dependencies
        if (PHP_OS_FAMILY === 'Linux') {
            $this->info('Linux detected. Checking for Chrome dependencies...');
            
            // Try to install Chrome dependencies if we have sudo access
            // Note: This might not work in all environments due to permissions
            try {
                $this->info('Attempting to install Chrome dependencies (this may require sudo)...');
                $chromeDepProcess = new Process(['apt-get', 'update', '-y']);
                $chromeDepProcess->setTimeout(300);
                $chromeDepProcess->run();
                
                $chromeDepProcess = new Process([
                    'apt-get', 'install', '-y',
                    'libx11-xcb1', 'libxcomposite1', 'libxcursor1', 'libxdamage1',
                    'libxi6', 'libxtst6', 'libnss3', 'libcups2', 'libxss1',
                    'libxrandr2', 'libasound2', 'libatk1.0-0', 'libatk-bridge2.0-0',
                    'libpangocairo-1.0-0', 'libgtk-3-0', 'libgbm1'
                ]);
                $chromeDepProcess->setTimeout(300);
                $chromeDepProcess->run();
                
                if ($chromeDepProcess->isSuccessful()) {
                    $this->info('Chrome dependencies installed successfully.');
                } else {
                    $this->warn('Could not install Chrome dependencies. This might be due to permission issues.');
                    $this->warn('The scraper might still work with the updated configuration.');
                }
            } catch (\Exception $e) {
                $this->warn('Could not install Chrome dependencies: ' . $e->getMessage());
                $this->warn('The scraper might still work with the updated configuration.');
            }
        }
        
        $process = new Process([$npmPath, 'install'], base_path('scripts/node'));
        $process->setTimeout(300); // 5 minutes timeout
        
        try {
            $process->mustRun(function ($type, $buffer) {
                if (Process::ERR === $type) {
                    $this->error($buffer);
                } else {
                    $this->line($buffer);
                }
            });
            
            $this->info('Node.js dependencies installed successfully.');
            return true;
        } catch (ProcessFailedException $exception) {
            $this->error('Failed to install Node.js dependencies: ' . $exception->getMessage());
            return false;
        }
    }
    
    /**
     * Find the npm executable path
     */
    protected function findNpmExecutable()
    {
        // Try common npm executable paths
        $possiblePaths = [
            'npm',
            '/usr/bin/npm',
            '/usr/local/bin/npm',
            'C:\\Program Files\\nodejs\\npm.cmd',
            'C:\\Program Files (x86)\\nodejs\\npm.cmd',
        ];
        
        foreach ($possiblePaths as $path) {
            try {
                $process = new Process([$path, '--version']);
                $process->run();
                
                if ($process->isSuccessful()) {
                    return $path;
                }
            } catch (\Exception $e) {
                // Continue to the next path
            }
        }
        
        return null;
    }
}
