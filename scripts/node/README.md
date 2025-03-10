# BMX Events Scraper

This Node.js script scrapes USA BMX events from their website and saves them to a JSON file.

## Requirements

- Node.js v14 or higher

## Usage

### Direct Usage

You can run the scraper directly:

```bash
node bmx_scraper.js [output_file_path]
```

If no output path is provided, it will save to `storage/app/events.json` relative to the Laravel project root.

### Via Laravel Command

The scraper is integrated with Laravel's `import:bmx-events` command:

```bash
# Scrape new events and import them
php artisan import:bmx-events --scrape

# Import from an existing JSON file
php artisan import:bmx-events --source=/path/to/events.json

# Clear existing events before import
php artisan import:bmx-events --reset

# Combine options
php artisan import:bmx-events --scrape --reset
```

## How It Works

The scraper:

1. Fetches event data from USA BMX website for national, state, and local events
2. Parses the HTML to extract event details (title, dates, location, etc.)
3. Saves the events to a JSON file
4. The Laravel command then imports these events into the database

## Troubleshooting

If you encounter issues:

1. Make sure Node.js is installed and accessible
2. Check that the USA BMX website structure hasn't changed
3. Verify file permissions for the output directory 