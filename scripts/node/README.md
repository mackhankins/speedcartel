# BMX Events Scraper

This Node.js script generates mock USA BMX events data and saves them to a JSON file.

## Requirements

- Node.js v14 or higher

## Usage

### Direct Usage

You can run the script directly:

```bash
node bmx_scraper.js [output_file_path]
```

If no output path is provided, it will save to `storage/app/events.json` relative to the Laravel project root.

### Via Laravel Command

The script is integrated with Laravel's `import:bmx-events` command:

```bash
# Generate mock events and import them (default behavior)
php artisan import:bmx-events

# Explicitly generate mock events
php artisan import:bmx-events --scrape

# Import from an existing JSON file
php artisan import:bmx-events --source=/path/to/events.json --initial

# Clear existing events before import
php artisan import:bmx-events --reset

# Test mode - force import all events as new
php artisan import:bmx-events --test

# Keep temporary files after import
php artisan import:bmx-events --keep-temp

# Combine options
php artisan import:bmx-events --scrape --reset
```

## How It Works

The script:

1. Generates realistic mock data for national, state, and local BMX events
2. Creates events with proper dates, locations, and track information
3. Saves the events to a JSON file
4. The Laravel command then imports these events into the database

## Mock Data

The mock data includes:
- National events
- State qualifier events
- Local race series events

Each event includes:
- Event title
- Event category (National, State, Local)
- Start and end dates
- Track name
- Location (City, State)
- URL

## Troubleshooting

If you encounter issues:

1. Make sure Node.js is installed and accessible
2. Verify file permissions for the output directory
3. Check the Laravel logs for any import errors 