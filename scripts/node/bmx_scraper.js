#!/usr/bin/env node

/**
 * BMX Events Scraper
 * 
 * This script scrapes USA BMX events from their website.
 * It can either return just the new events or merge them with existing events.
 * It's designed to be run from Laravel's import:bmx-events command.
 */

const https = require('https');
const fs = require('fs');
const path = require('path');
const puppeteer = require('puppeteer');

// Configuration
const OUTPUT_FILE = process.argv[2] || path.join(process.cwd(), 'storage', 'app', 'events.json');
const MERGE_WITH_EXISTING = process.argv.includes('--merge');
const EVENTS_JSON_PATH = process.argv[3] || path.join(process.cwd(), 'storage', 'app', 'events.json');

// URL to scrape - upcoming events only
const EVENTS_URL = 'https://www.usabmx.com/events?filter_list=upcoming&page_number=1';

// Helper function to make HTTP requests (legacy, keeping for reference)
function makeRequest(url) {
  return new Promise((resolve, reject) => {
    https.get(url, (res) => {
      let data = '';
      
      res.on('data', (chunk) => {
        data += chunk;
      });
      
      res.on('end', () => {
        resolve(data);
      });
      
    }).on('error', (err) => {
      reject(err);
    });
  });
}

// Get page content using Puppeteer (handles JavaScript rendering)
async function getRenderedPageContent(url) {
  console.log(`Launching headless browser to render: ${url}`);
  
  // Determine if we're running on Linux
  const isLinux = process.platform === 'linux';
  
  // Configure browser launch options with additional arguments for Linux
  const launchOptions = {
    headless: 'new',
    args: [
      '--no-sandbox',
      '--disable-setuid-sandbox',
      '--disable-dev-shm-usage',
      '--disable-accelerated-2d-canvas',
      '--no-first-run',
      '--no-zygote',
      '--single-process',
      '--disable-gpu'
    ]
  };
  
  // Add executable path for Linux if needed
  if (isLinux) {
    console.log('Running on Linux, using special configuration');
    // We don't set executablePath as Puppeteer should find the right one
  }
  
  let browser;
  try {
    // Try to launch with standard options
    browser = await puppeteer.launch(launchOptions);
  } catch (error) {
    console.error('Failed to launch browser with standard options:', error.message);
    
    // Try alternative approach for Linux
    if (isLinux) {
      console.log('Trying alternative launch approach for Linux...');
      try {
        // Try with explicit executable path if available via environment variable
        const chromePath = process.env.CHROME_BIN || '/usr/bin/google-chrome';
        console.log(`Trying with explicit Chrome path: ${chromePath}`);
        
        launchOptions.executablePath = chromePath;
        browser = await puppeteer.launch(launchOptions);
      } catch (altError) {
        console.error('Alternative launch approach failed:', altError.message);
        throw new Error(`Failed to launch browser after multiple attempts: ${error.message}, ${altError.message}`);
      }
    } else {
      // Re-throw the original error if not on Linux
      throw error;
    }
  }
  
  try {
    const page = await browser.newPage();
    
    // Set a reasonable viewport
    await page.setViewport({ width: 1280, height: 800 });
    
    // Navigate to the URL
    await page.goto(url, { waitUntil: 'networkidle2' });
    
    console.log('Page loaded, waiting for content to render...');
    
    // Wait for the events to load - using the new selector based on the provided HTML
    await page.waitForSelector('div.border.border-website-ultraLightBlue', { timeout: 10000 })
      .catch(() => console.log('Warning: Event items not found within timeout, continuing anyway'));
    
    // Wait an additional 2 seconds to ensure everything is loaded
    await page.waitForTimeout(2000);
    
    console.log('Content rendered, extracting HTML...');
    
    // Get the full HTML content
    const content = await page.content();
    
    return content;
  } finally {
    if (browser) {
      await browser.close();
      console.log('Browser closed');
    }
  }
}

// Parse HTML to extract event data
function parseEvents(html) {
  const events = [];
  
  // Extract event blocks using the new structure
  // This regex pattern matches the div structure from the provided HTML sample
  const eventBlockRegex = /<div class="p-\[20px\][\s\S]*?<\/div><\/div><\/div>/g;
  const eventBlocks = html.match(eventBlockRegex) || [];
  
  console.log(`Found ${eventBlocks.length} event blocks`);
  
  eventBlocks.forEach(block => {
    try {
      // Extract event type/category
      const categoryMatch = block.match(/<div class="w-fit border bg-website-brightGray.*?">(.*?)<\/div>/);
      let category = categoryMatch ? categoryMatch[1].trim() : '';
      
      // Extract title
      const titleMatch = block.match(/<button class="text-\[18px\].*?">(.*?)<\/button>/);
      const title = titleMatch ? titleMatch[1].trim() : 'Untitled Event';
      
      // Extract date
      const dateMatch = block.match(/<p class="text-\[16px\].*?font-MontserratSemiBold mt-\[8px\]">(.*?)<\/p>/);
      let dateText = dateMatch ? dateMatch[1].trim() : '';
      
      // Parse date
      let startDate = null;
      let endDate = null;
      
      if (dateText) {
        // Try to parse various date formats
        // Example: "March 09, 2025" or "March 09-10, 2025"
        const dateRangeMatch = dateText.match(/(\w+)\s+(\d+)(?:\s*-\s*(\d+))?,\s+(\d{4})/);
        
        if (dateRangeMatch) {
          const month = dateRangeMatch[1];
          const startDay = dateRangeMatch[2];
          const endDay = dateRangeMatch[3] || startDay; // If no end day, use start day
          const year = dateRangeMatch[4];
          
          // Convert month name to number
          const monthMap = {
            'January': 0, 'February': 1, 'March': 2, 'April': 3, 'May': 4, 'June': 5,
            'July': 6, 'August': 7, 'September': 8, 'October': 9, 'November': 10, 'December': 11,
            'Jan': 0, 'Feb': 1, 'Mar': 2, 'Apr': 3, 'May': 4, 'Jun': 5,
            'Jul': 6, 'Aug': 7, 'Sep': 8, 'Oct': 9, 'Nov': 10, 'Dec': 11
          };
          
          const monthNum = monthMap[month] || 0;
          
          startDate = new Date(year, monthNum, startDay);
          endDate = new Date(year, monthNum, endDay);
          
          // Format dates as ISO strings
          startDate = startDate.toISOString().split('T')[0];
          endDate = endDate.toISOString().split('T')[0];
        }
      }
      
      // Extract location
      const locationMatch = block.match(/<p class="text-\[16px\].*?font-MontserratMedium mt-4">(.*?)<\/p>/);
      let locationHtml = locationMatch ? locationMatch[1].trim() : '';
      
      // Extract track name and city/state from location HTML
      const trackMatch = locationHtml.match(/<a class="underline" href="\/tracks\/.*?">(.*?)<\/a>/);
      const trackName = trackMatch ? trackMatch[1].trim() : '';
      
      // Extract city/state - it's between the track name and the directions link
      let location = '';
      if (locationHtml && trackName) {
        const cityStateMatch = locationHtml.match(new RegExp(`${trackName}</a>\\s*-\\s*([^<]+)`));
        location = cityStateMatch ? cityStateMatch[1].trim() : '';
      }
      
      // Extract URL for the track
      const urlMatch = block.match(/<a class="underline" href="(\/tracks\/[^"]+)">/);
      const url = urlMatch ? `https://www.usabmx.com${urlMatch[1]}` : '';
      
      // Create event object
      const event = {
        title,
        start_date: startDate,
        end_date: endDate,
        location: location || trackName, // Use combined location or just track name if that's all we have
        url,
        category: category || 'BMX Event',
        description: `${category || 'BMX'} event: ${title} at ${trackName}`
      };
      
      // Only add events with valid dates
      if (startDate && endDate) {
        events.push(event);
      }
    } catch (error) {
      console.error('Error parsing event block:', error);
    }
  });
  
  return events;
}

// Read existing events from file
function readExistingEvents() {
  try {
    if (fs.existsSync(EVENTS_JSON_PATH)) {
      const data = fs.readFileSync(EVENTS_JSON_PATH, 'utf8');
      const events = JSON.parse(data);
      console.log(`Read ${events.length} existing events from ${EVENTS_JSON_PATH}`);
      return events;
    }
  } catch (error) {
    console.warn(`Warning: Could not read existing events: ${error.message}`);
  }
  
  return [];
}

// Check if two events are duplicates
function isDuplicate(event1, event2) {
  // Check if URLs match (most reliable)
  if (event1.url && event2.url && event1.url === event2.url) {
    return true;
  }
  
  // Check if title and dates match
  if (event1.title === event2.title && 
      event1.start_date === event2.start_date) {
    return true;
  }
  
  return false;
}

// Merge new events with existing ones, avoiding duplicates
function mergeEvents(existingEvents, newEvents) {
  const merged = [...existingEvents];
  let addedCount = 0;
  
  newEvents.forEach(newEvent => {
    // Check if this event already exists
    const isDuplicateEvent = merged.some(existingEvent => 
      isDuplicate(existingEvent, newEvent)
    );
    
    if (!isDuplicateEvent) {
      merged.push(newEvent);
      addedCount++;
    }
  });
  
  console.log(`Added ${addedCount} new events, skipped ${newEvents.length - addedCount} duplicates`);
  return merged;
}

// Main function to scrape events
async function scrapeEvents() {
  try {
    console.log('Starting BMX events scraper...');
    console.log(`Scraping events from: ${EVENTS_URL}`);
    
    // Scrape new events using Puppeteer to handle JavaScript rendering
    const html = await getRenderedPageContent(EVENTS_URL);
    const newEvents = parseEvents(html);
    console.log(`Found ${newEvents.length} valid events from scraping`);
    
    // If we're not merging, just return the new events
    if (!MERGE_WITH_EXISTING) {
      // Write only the new events to the output file
      fs.writeFileSync(OUTPUT_FILE, JSON.stringify(newEvents, null, 2));
      console.log(`New events saved to ${OUTPUT_FILE}`);
      return newEvents;
    }
    
    // If we are merging, read existing events and merge
    const existingEvents = readExistingEvents();
    const mergedEvents = mergeEvents(existingEvents, newEvents);
    console.log(`Total events after merging: ${mergedEvents.length}`);
    
    // Save merged events to the output file
    fs.writeFileSync(OUTPUT_FILE, JSON.stringify(mergedEvents, null, 2));
    console.log(`Merged events saved to ${OUTPUT_FILE}`);
    
    return mergedEvents;
  } catch (error) {
    console.error('Error scraping events:', error);
    throw error;
  }
}

// Run the scraper if this script is executed directly
if (require.main === module) {
  scrapeEvents()
    .then(() => {
      process.exit(0);
    })
    .catch(error => {
      console.error('Scraper failed:', error);
      process.exit(1);
    });
} else {
  // Export for use as a module
  module.exports = { scrapeEvents };
} 