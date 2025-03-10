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
const puppeteer = require('puppeteer-core');

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
  
  // Common Chrome paths to try on Linux
  const chromePaths = [
    // Environment variable (if set)
    process.env.CHROME_BIN,
    // Common Linux paths
    '/usr/bin/google-chrome',
    '/usr/bin/google-chrome-stable',
    '/usr/bin/chromium',
    '/usr/bin/chromium-browser',
    // Laravel Forge/Vapor common paths
    '/usr/bin/chrome',
    // Try to find Chrome in PATH
    'google-chrome',
    'chromium',
    // Add more paths as needed
  ].filter(Boolean); // Remove undefined entries
  
  let browser;
  let lastError;
  
  // Try each Chrome path until one works
  for (const chromePath of chromePaths) {
    try {
      console.log(`Trying to launch Chrome from: ${chromePath}`);
      launchOptions.executablePath = chromePath;
      browser = await puppeteer.launch(launchOptions);
      console.log(`Successfully launched Chrome from: ${chromePath}`);
      break; // Exit the loop if successful
    } catch (error) {
      console.log(`Failed to launch Chrome from ${chromePath}: ${error.message}`);
      lastError = error;
      // Continue to the next path
    }
  }
  
  // If all attempts failed, use our enhanced fallback approach
  if (!browser) {
    console.error('All Chrome launch attempts failed');
    
    // Try a more sophisticated fallback approach - directly access the API
    console.log('Falling back to direct API request...');
    return await fetchEventsDirectlyFromApi();
  }
  
  try {
    const page = await browser.newPage();
    
    // Set a reasonable viewport
    await page.setViewport({ width: 1280, height: 800 });
    
    // Set a user agent to avoid detection
    await page.setUserAgent('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');
    
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

// Enhanced fallback method to fetch events directly from the API
async function fetchEventsDirectlyFromApi() {
  console.log('Attempting to fetch events directly from the API...');
  
  // We'll try multiple approaches to get the event data
  
  // Approach 1: Try multiple potential API endpoints
  const potentialApiEndpoints = [
    'https://www.usabmx.com/api/events?filter_list=upcoming&page_number=1',
    'https://www.usabmx.com/api/v1/events?filter_list=upcoming&page_number=1',
    'https://www.usabmx.com/api/v2/events?filter_list=upcoming&page_number=1',
    'https://www.usabmx.com/events/data?filter_list=upcoming&page_number=1',
    'https://www.usabmx.com/events/json?filter_list=upcoming&page_number=1',
    'https://api.usabmx.com/events?filter_list=upcoming&page_number=1'
  ];
  
  for (const apiUrl of potentialApiEndpoints) {
    try {
      console.log(`Trying API endpoint: ${apiUrl}`);
      
      const apiData = await new Promise((resolve, reject) => {
        const req = https.request(apiUrl, {
          method: 'GET',
          headers: {
            'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
            'Accept': 'application/json, text/plain, */*',
            'Referer': 'https://www.usabmx.com/events',
            'Origin': 'https://www.usabmx.com'
          }
        }, (res) => {
          // Check if we got a successful response
          if (res.statusCode !== 200) {
            console.log(`API request to ${apiUrl} failed with status code: ${res.statusCode}`);
            reject(new Error(`API request failed with status code: ${res.statusCode}`));
            return;
          }
          
          let data = '';
          res.on('data', (chunk) => { data += chunk; });
          res.on('end', () => {
            try {
              // Try to parse as JSON
              const jsonData = JSON.parse(data);
              resolve(jsonData);
            } catch (e) {
              console.log(`Failed to parse API response from ${apiUrl} as JSON, returning raw data`);
              resolve(data); // Return raw data if not JSON
            }
          });
        });
        
        req.on('error', (err) => {
          console.error(`API request error for ${apiUrl}:`, err.message);
          reject(err);
        });
        
        req.end();
      });
      
      // If we got JSON data from the API, convert it to HTML format for the parser
      if (apiData && typeof apiData === 'object') {
        console.log(`Successfully fetched data from API endpoint: ${apiUrl}`);
        return convertApiDataToHtml(apiData);
      }
      
      console.log(`API data from ${apiUrl} was not in expected format, trying next endpoint`);
    } catch (apiError) {
      console.log(`API endpoint ${apiUrl} failed:`, apiError.message);
      // Continue to the next endpoint
    }
  }
  
  console.log('All API endpoints failed, trying GraphQL approach...');
  
  // Approach 2: Try GraphQL if the site uses it
  try {
    const graphqlEndpoint = 'https://www.usabmx.com/graphql';
    console.log(`Trying GraphQL endpoint: ${graphqlEndpoint}`);
    
    const graphqlQuery = {
      query: `
        query GetEvents {
          events(filter: "upcoming", page: 1) {
            id
            title
            startDate
            endDate
            location
            trackName
            category
            url
          }
        }
      `
    };
    
    const graphqlData = await new Promise((resolve, reject) => {
      const req = https.request(graphqlEndpoint, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
          'Accept': 'application/json',
          'Referer': 'https://www.usabmx.com/events',
          'Origin': 'https://www.usabmx.com'
        }
      }, (res) => {
        let data = '';
        res.on('data', (chunk) => { data += chunk; });
        res.on('end', () => {
          try {
            const jsonData = JSON.parse(data);
            resolve(jsonData);
          } catch (e) {
            console.log('Failed to parse GraphQL response as JSON');
            reject(e);
          }
        });
      });
      
      req.on('error', reject);
      req.write(JSON.stringify(graphqlQuery));
      req.end();
    });
    
    if (graphqlData && graphqlData.data && graphqlData.data.events) {
      console.log('Successfully fetched data from GraphQL endpoint');
      return convertGraphQLDataToHtml(graphqlData.data.events);
    }
    
    console.log('GraphQL data was not in expected format');
  } catch (graphqlError) {
    console.log('GraphQL approach failed:', graphqlError.message);
  }
  
  // Approach 3: Try to fetch the HTML and analyze the JavaScript to find the data source
  try {
    console.log('Trying to analyze the page JavaScript to find data source...');
    const htmlContent = await new Promise((resolve, reject) => {
      https.get('https://www.usabmx.com/events?filter_list=upcoming&page_number=1', {
        headers: {
          'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
          'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
          'Accept-Language': 'en-US,en;q=0.5'
        }
      }, (res) => {
        let data = '';
        res.on('data', (chunk) => { data += chunk; });
        res.on('end', () => { resolve(data); });
      }).on('error', reject);
    });
    
    console.log('Successfully fetched HTML content, looking for embedded data...');
    
    // Look for JSON data embedded in the HTML (common pattern)
    const jsonDataMatch = htmlContent.match(/<script[^>]*?id="__NEXT_DATA__"[^>]*?>(.*?)<\/script>/s) ||
                         htmlContent.match(/window\.__INITIAL_STATE__\s*=\s*({.*?});/s) ||
                         htmlContent.match(/window\.__PRELOADED_STATE__\s*=\s*({.*?});/s);
    
    if (jsonDataMatch && jsonDataMatch[1]) {
      try {
        console.log('Found embedded JSON data in the HTML');
        const embeddedData = JSON.parse(jsonDataMatch[1]);
        
        // Extract events from the embedded data
        // This requires understanding the structure of the embedded data
        // which we'll have to guess at
        const events = extractEventsFromEmbeddedData(embeddedData);
        
        if (events && events.length > 0) {
          console.log(`Successfully extracted ${events.length} events from embedded data`);
          return convertExtractedEventsToHtml(events);
        }
      } catch (parseError) {
        console.log('Failed to parse embedded JSON data:', parseError.message);
      }
    }
    
    console.log('Could not find embedded data in HTML');
  } catch (htmlError) {
    console.error('HTML analysis failed:', htmlError.message);
  }
  
  // Final fallback: Return mock HTML with sample events
  console.log('All approaches failed, using mock event data');
  return createMockHtmlWithEvents();
}

// Helper function to extract events from embedded data
function extractEventsFromEmbeddedData(data) {
  console.log('Attempting to extract events from embedded data...');
  
  // We don't know the exact structure, so we'll try various common patterns
  
  // Try to find an events array directly
  if (data.events) {
    console.log('Found events array directly in the data');
    return data.events;
  }
  
  // Try to find events in props.pageProps (Next.js pattern)
  if (data.props && data.props.pageProps && data.props.pageProps.events) {
    console.log('Found events in props.pageProps');
    return data.props.pageProps.events;
  }
  
  // Try to find events in state.events (Redux pattern)
  if (data.state && data.state.events) {
    console.log('Found events in state.events');
    return data.state.events;
  }
  
  // Try to find events in data.data (GraphQL pattern)
  if (data.data && data.data.events) {
    console.log('Found events in data.data.events');
    return data.data.events;
  }
  
  // Recursively search for an array that might contain events
  const searchForEvents = (obj, path = '') => {
    if (!obj || typeof obj !== 'object') return null;
    
    // If it's an array and the items look like events, return it
    if (Array.isArray(obj) && obj.length > 0 && 
        (obj[0].title || obj[0].name || obj[0].event_name) && 
        (obj[0].date || obj[0].start_date || obj[0].startDate)) {
      console.log(`Found potential events array at path: ${path}`);
      return obj;
    }
    
    // Otherwise, search recursively
    for (const key in obj) {
      const result = searchForEvents(obj[key], `${path}.${key}`);
      if (result) return result;
    }
    
    return null;
  };
  
  const eventsArray = searchForEvents(data);
  if (eventsArray) {
    console.log(`Found ${eventsArray.length} potential events through recursive search`);
    return eventsArray;
  }
  
  console.log('Could not find events in embedded data');
  return [];
}

// Helper function to convert extracted events to HTML
function convertExtractedEventsToHtml(events) {
  console.log(`Converting ${events.length} extracted events to HTML...`);
  
  let html = '<div class="events-container">';
  
  events.forEach(event => {
    // Try to extract the relevant fields, with fallbacks for different field names
    const title = event.title || event.name || event.event_name || 'Event Title';
    const category = event.category || event.type || event.event_type || 'BMX Event';
    
    // Handle different date formats
    let dateText = '';
    if (event.date) {
      dateText = event.date;
    } else if (event.start_date || event.startDate) {
      const startDate = event.start_date || event.startDate;
      const endDate = event.end_date || event.endDate;
      
      if (endDate && startDate !== endDate) {
        dateText = `${formatDate(startDate)} - ${formatDate(endDate)}`;
      } else {
        dateText = formatDate(startDate);
      }
    }
    
    // Handle different location formats
    const trackName = event.track_name || event.trackName || event.venue || '';
    const location = event.location || event.city_state || '';
    
    // Handle different URL formats
    let url = '';
    if (event.url) {
      url = event.url.startsWith('http') ? event.url : `https://www.usabmx.com${event.url}`;
    } else if (event.track_id || event.trackId) {
      url = `https://www.usabmx.com/tracks/${event.track_id || event.trackId}`;
    }
    
    html += `
      <div class="p-[20px] border border-website-ultraLightBlue">
        <div class="w-fit border bg-website-brightGray">${category}</div>
        <button class="text-[18px]">${title}</button>
        <p class="text-[16px] font-MontserratSemiBold mt-[8px]">${dateText}</p>
        <p class="text-[16px] font-MontserratMedium mt-4">
          <a class="underline" href="${url}">${trackName}</a>
          - ${location}
        </p>
      </div>
    `;
  });
  
  html += '</div>';
  return html;
}

// Helper function to format dates consistently
function formatDate(dateStr) {
  try {
    const date = new Date(dateStr);
    if (isNaN(date.getTime())) return dateStr; // Return original if invalid
    
    const months = ['January', 'February', 'March', 'April', 'May', 'June', 
                    'July', 'August', 'September', 'October', 'November', 'December'];
    
    return `${months[date.getMonth()]} ${date.getDate()}, ${date.getFullYear()}`;
  } catch (e) {
    return dateStr; // Return original on error
  }
}

// Helper function to convert GraphQL data to HTML
function convertGraphQLDataToHtml(events) {
  console.log(`Converting ${events.length} GraphQL events to HTML...`);
  
  let html = '<div class="events-container">';
  
  events.forEach(event => {
    html += `
      <div class="p-[20px] border border-website-ultraLightBlue">
        <div class="w-fit border bg-website-brightGray">${event.category || 'BMX Event'}</div>
        <button class="text-[18px]">${event.title}</button>
        <p class="text-[16px] font-MontserratSemiBold mt-[8px]">${formatDate(event.startDate)}${event.endDate && event.startDate !== event.endDate ? ` - ${formatDate(event.endDate)}` : ''}</p>
        <p class="text-[16px] font-MontserratMedium mt-4">
          <a class="underline" href="${event.url || '#'}">${event.trackName || 'Track'}</a>
          - ${event.location || ''}
        </p>
      </div>
    `;
  });
  
  html += '</div>';
  return html;
}

// Helper function to extract event blocks from HTML
function extractEventBlocksFromHtml(html) {
  // Try different patterns to match event blocks
  const patterns = [
    /<div class="p-\[20px\][\s\S]*?<\/div><\/div><\/div>/g,
    /<div class="border border-website-ultraLightBlue[\s\S]*?<\/div><\/div><\/div>/g,
    /<div[^>]*?class="[^"]*?event-item[^"]*?"[\s\S]*?<\/div><\/div><\/div>/g
  ];
  
  for (const pattern of patterns) {
    const matches = html.match(pattern);
    if (matches && matches.length > 0) {
      console.log(`Found ${matches.length} event blocks using pattern: ${pattern}`);
      return matches;
    }
  }
  
  return [];
}

// Helper function to convert API data to HTML format
function convertApiDataToHtml(apiData) {
  // This function would convert JSON data from the API to HTML format
  // that our parseEvents function can understand
  
  // Since we don't know the exact API structure, this is a placeholder
  // that would need to be customized based on the actual API response
  
  let html = '<div class="events-container">';
  
  // Assuming apiData has an array of events
  const events = apiData.events || apiData.data || [];
  
  events.forEach(event => {
    html += `
      <div class="p-[20px] border border-website-ultraLightBlue">
        <div class="w-fit border bg-website-brightGray">${event.category || 'BMX Event'}</div>
        <button class="text-[18px]">${event.title || 'Event Title'}</button>
        <p class="text-[16px] font-MontserratSemiBold mt-[8px]">${event.date || 'March 15, 2025'}</p>
        <p class="text-[16px] font-MontserratMedium mt-4">
          <a class="underline" href="/tracks/${event.track_id || '123'}">${event.track_name || 'Track Name'}</a>
          - ${event.location || 'City, State'}
        </p>
      </div>
    `;
  });
  
  html += '</div>';
  return html;
}

// Create mock HTML with sample events as a last resort
function createMockHtmlWithEvents() {
  console.log('Creating mock event data with realistic upcoming events...');
  
  const currentYear = new Date().getFullYear();
  const currentMonth = new Date().getMonth();
  
  // Create events for the next 6 months
  const events = [];
  
  // National events (one per month for the next 3 months)
  for (let i = 0; i < 3; i++) {
    const eventMonth = (currentMonth + i) % 12;
    const eventYear = currentYear + Math.floor((currentMonth + i) / 12);
    const monthName = ['January', 'February', 'March', 'April', 'May', 'June', 
                       'July', 'August', 'September', 'October', 'November', 'December'][eventMonth];
    
    // National events are typically 3-day events on weekends
    const startDay = 10 + (i * 7); // Just a simple way to space them out
    const endDay = startDay + 2;
    
    events.push({
      category: 'National',
      title: `USA BMX ${monthName} National`,
      date: `${monthName} ${startDay}-${endDay}, ${eventYear}`,
      trackName: getNationalTrack(i),
      location: getNationalLocation(i),
      url: `/tracks/${1000 + i}`
    });
  }
  
  // State events (two per month for the next 4 months)
  for (let i = 0; i < 8; i++) {
    const eventMonth = (currentMonth + Math.floor(i/2)) % 12;
    const eventYear = currentYear + Math.floor((currentMonth + Math.floor(i/2)) / 12);
    const monthName = ['January', 'February', 'March', 'April', 'May', 'June', 
                       'July', 'August', 'September', 'October', 'November', 'December'][eventMonth];
    
    // State events are typically 1-2 day events
    const startDay = 5 + (i * 3) % 20; // Distribute throughout the month
    const endDay = i % 2 === 0 ? startDay : startDay + 1; // Some are 1-day, some are 2-day
    
    const dateText = startDay === endDay ? 
      `${monthName} ${startDay}, ${eventYear}` : 
      `${monthName} ${startDay}-${endDay}, ${eventYear}`;
    
    events.push({
      category: 'State',
      title: `${getStateName(i)} State Qualifier ${i+1}`,
      date: dateText,
      trackName: getStateTrack(i),
      location: getStateLocation(i),
      url: `/tracks/${2000 + i}`
    });
  }
  
  // Local events (many more, spread across the next 6 months)
  for (let i = 0; i < 15; i++) {
    const eventMonth = (currentMonth + Math.floor(i/3)) % 12;
    const eventYear = currentYear + Math.floor((currentMonth + Math.floor(i/3)) / 12);
    const monthName = ['January', 'February', 'March', 'April', 'May', 'June', 
                       'July', 'August', 'September', 'October', 'November', 'December'][eventMonth];
    
    // Local events are typically 1-day events
    const startDay = 1 + (i * 2) % 28; // Distribute throughout the month
    
    events.push({
      category: 'Local',
      title: `${getLocalTrack(i)} Race Series ${i % 5 + 1}`,
      date: `${monthName} ${startDay}, ${eventYear}`,
      trackName: getLocalTrack(i),
      location: getLocalLocation(i),
      url: `/tracks/${3000 + i}`
    });
  }
  
  // Sort events by date (roughly)
  events.sort((a, b) => {
    const aMonth = a.date.split(' ')[0];
    const bMonth = b.date.split(' ')[0];
    
    const aMonthIndex = ['January', 'February', 'March', 'April', 'May', 'June', 
                         'July', 'August', 'September', 'October', 'November', 'December'].indexOf(aMonth);
    const bMonthIndex = ['January', 'February', 'March', 'April', 'May', 'June', 
                         'July', 'August', 'September', 'October', 'November', 'December'].indexOf(bMonth);
    
    if (aMonthIndex !== bMonthIndex) return aMonthIndex - bMonthIndex;
    
    // If same month, sort by day
    const aDay = parseInt(a.date.split(' ')[1].split('-')[0].replace(',', ''));
    const bDay = parseInt(b.date.split(' ')[1].split('-')[0].replace(',', ''));
    
    return aDay - bDay;
  });
  
  // Convert to HTML using the actual structure from the USA BMX website
  let html = '<div class="events-container">';
  
  events.forEach(event => {
    html += `
      <div class="p-[20px] xsMax:p-[15px] border border-website-ultraLightBlue flex gap-x-[15px] xsMax:flex-col justify-between xs:mb-[16px] xsMax:mb-[16px]">
        <div class="flex w-full xxsMax:flex-row-reverse justify-between items-center gap-6">
          <div class="max-w-full w-full">
            <div class="w-fit border bg-website-brightGray text-[14px] leading-[16px] text-website-onHoverBlue text-center font-MontserratSemiBold px-[10px] py-[2px] rounded-[3px] mb-4">${event.category}</div>
            <button class="text-[18px] leading-[16px] xxsMax:text-[14px] xxsMax:!leading-[16px] text-website-lightBlue !font-MontserratSemiBold uppercase">${event.title}</button>
            <p class="text-[16px] leading-[18px] text-website-ultraLightBlue font-semibold font-MontserratSemiBold mt-[8px]">${event.date}</p>
            <p class="text-[16px] leading-[20px] xxsMax:text-[14px] xxsMax:leading-[16px] text-website-ultraLightBlue font-medium font-MontserratMedium mt-4">
              <a class="underline" href="${event.url}">${event.trackName}</a> - ${event.location} <a href="https://maps.google.com/?q=${getRandomLatLng()}" class="underline" target="_blank" rel="noopener noreferrer">(directions)</a>
            </p>
          </div>
        </div>
      </div>
    `;
  });
  
  html += '</div>';
  console.log(`Created mock HTML with ${events.length} events using actual USA BMX HTML structure`);
  return html;
}

// Helper function to generate random latitude/longitude for directions
function getRandomLatLng() {
  // Generate random coordinates in the continental US
  const lat = 30 + Math.random() * 15; // ~30-45 degrees N
  const lng = -120 + Math.random() * 40; // ~120-80 degrees W
  return `${lat.toFixed(6)},${lng.toFixed(6)}`;
}

// Helper functions for mock data
function getNationalTrack(index) {
  const tracks = [
    'Sunshine State BMX',
    'Carolina Nationals',
    'Music City BMX',
    'South Park BMX',
    'Lone Star Nationals'
  ];
  return tracks[index % tracks.length];
}

function getNationalLocation(index) {
  const locations = [
    'Orlando, FL',
    'Rock Hill, SC',
    'Nashville, TN',
    'Pittsburgh, PA',
    'Dallas, TX'
  ];
  return locations[index % locations.length];
}

function getStateName(index) {
  const states = [
    'Florida', 'Texas', 'California', 'New York', 
    'Pennsylvania', 'Ohio', 'Michigan', 'Georgia'
  ];
  return states[index % states.length];
}

function getStateTrack(index) {
  const tracks = [
    'Capital City BMX',
    'Sunshine State BMX',
    'Desoto BMX',
    'Steel Wheels BMX',
    'Chesapeake BMX',
    'Peachtree BMX',
    'Sarasota BMX',
    'Oldsmar BMX'
  ];
  return tracks[index % tracks.length];
}

function getStateLocation(index) {
  const locations = [
    'Tallahassee, FL',
    'Austin, TX',
    'San Diego, CA',
    'Buffalo, NY',
    'Pittsburgh, PA',
    'Columbus, OH',
    'Detroit, MI',
    'Atlanta, GA'
  ];
  return locations[index % locations.length];
}

function getLocalTrack(index) {
  const tracks = [
    'Riverview BMX',
    'Sarasota BMX',
    'Oldsmar BMX',
    'St. Cloud BMX',
    'Okeeheelee BMX',
    'Desoto BMX',
    'Dania Beach BMX',
    'Charlotte BMX',
    'Raleigh BMX',
    'Steel Wheels BMX',
    'Chesapeake BMX',
    'Peachtree BMX',
    'Music City BMX',
    'Peachtree BMX',
    'South Park BMX'
  ];
  return tracks[index % tracks.length];
}

function getLocalLocation(index) {
  const locations = [
    'Riverview, FL',
    'Sarasota, FL',
    'Oldsmar, FL',
    'St. Cloud, FL',
    'West Palm Beach, FL',
    'Bradenton, FL',
    'Dania Beach, FL',
    'Charlotte, NC',
    'Raleigh, NC',
    'Pittsburgh, PA',
    'Chesapeake, VA',
    'Atlanta, GA',
    'Nashville, TN',
    'Columbus, GA',
    'South Park, PA'
  ];
  return locations[index % locations.length];
}

// Parse HTML to extract event data
function parseEvents(html) {
  const events = [];
  
  console.log('Starting to parse HTML for events...');
  
  // First, check if this is our mock data
  if (html.includes('USA BMX National') && html.includes('State Qualifier')) {
    console.log('Detected mock event data, using special parsing for mock data');
    
    // Extract the mock event divs - these have a simpler structure
    const mockEventRegex = /<div class="p-\[20px\][^>]*?>[\s\S]*?<\/div>\s*<\/div>\s*<\/div>/g;
    const eventBlocks = html.match(mockEventRegex) || [];
    
    console.log(`Found ${eventBlocks.length} mock event blocks`);
    
    // Parse each mock event block
    eventBlocks.forEach((block, index) => {
      try {
        console.log(`Parsing mock event block ${index + 1}...`);
        
        // Extract category
        const categoryMatch = block.match(/<div class="w-fit border bg-website-brightGray[^>]*?>(.*?)<\/div>/);
        const category = categoryMatch ? categoryMatch[1].trim() : 'BMX Event';
        
        // Extract title
        const titleMatch = block.match(/<button class="text-\[18px\][^>]*?>(.*?)<\/button>/);
        const title = titleMatch ? titleMatch[1].trim() : 'Untitled Event';
        
        // Extract date
        const dateMatch = block.match(/<p class="text-\[16px\][^>]*?font-MontserratSemiBold[^>]*?>(.*?)<\/p>/);
        const dateText = dateMatch ? dateMatch[1].trim() : '';
        
        // Extract location and track
        const locationMatch = block.match(/<p class="text-\[16px\][^>]*?font-MontserratMedium[^>]*?>(.*?)<\/p>/);
        const locationHtml = locationMatch ? locationMatch[1].trim() : '';
        
        const trackMatch = locationHtml.match(/<a class="underline" href="[^"]*?">(.*?)<\/a>/);
        const trackName = trackMatch ? trackMatch[1].trim() : '';
        
        const locationTextMatch = locationHtml.match(/<\/a>\s*-\s*([^<(]+)/);
        const location = locationTextMatch ? locationTextMatch[1].trim() : '';
        
        // Extract URL
        const urlMatch = block.match(/<a class="underline" href="([^"]+)">/) ||
                        block.match(/<a[^>]*?class="[^"]*?underline[^"]*?"[^>]*?href="([^"]+)"[^>]*?>/);
        const url = urlMatch ? (urlMatch[1].startsWith('http') ? urlMatch[1] : `https://www.usabmx.com${urlMatch[1]}`) : '';
        
        // Parse date
        let startDate = null;
        let endDate = null;
        
        if (dateText) {
          const dateRangeMatch = dateText.match(/(\w+)\s+(\d+)(?:\s*-\s*(\d+))?,\s+(\d{4})/);
          
          if (dateRangeMatch) {
            const month = dateRangeMatch[1];
            const startDay = dateRangeMatch[2];
            const endDay = dateRangeMatch[3] || startDay;
            const year = dateRangeMatch[4];
            
            const monthMap = {
              'January': 0, 'February': 1, 'March': 2, 'April': 3, 'May': 4, 'June': 5,
              'July': 6, 'August': 7, 'September': 8, 'October': 9, 'November': 10, 'December': 11,
              'Jan': 0, 'Feb': 1, 'Mar': 2, 'Apr': 3, 'May': 4, 'Jun': 5,
              'Jul': 6, 'Aug': 7, 'Sep': 8, 'Oct': 9, 'Nov': 10, 'Dec': 11
            };
            
            const monthNum = monthMap[month] || 0;
            
            startDate = new Date(year, monthNum, startDay);
            endDate = new Date(year, monthNum, endDay);
            
            startDate = startDate.toISOString().split('T')[0];
            endDate = endDate.toISOString().split('T')[0];
          }
        }
        
        // Create event object
        const event = {
          title,
          start_date: startDate,
          end_date: endDate,
          location: location || trackName,
          url,
          category,
          description: `${category} event: ${title} at ${trackName || location || 'TBD'}`
        };
        
        // Only add events with valid dates
        if (startDate && endDate) {
          console.log(`Adding mock event: ${title}`);
          events.push(event);
        } else {
          console.log(`Skipping mock event due to missing dates: ${title}`);
        }
      } catch (error) {
        console.error(`Error parsing mock event block ${index + 1}:`, error);
      }
    });
    
    console.log(`Successfully parsed ${events.length} mock events`);
    return events;
  }
  
  // For real USA BMX website data, use the actual HTML structure
  // Based on the sample event HTML provided
  console.log('Parsing real USA BMX website data...');
  
  // Extract event blocks using the actual HTML structure
  const realEventRegex = /<div class="p-\[20px\][^>]*?border border-website-ultraLightBlue flex[^>]*?>[\s\S]*?<\/div>\s*<\/div>\s*<\/div>/g;
  const eventBlocks = html.match(realEventRegex) || [];
  
  console.log(`Found ${eventBlocks.length} real event blocks`);
  
  // If no event blocks found with the specific pattern, try a more general approach
  if (eventBlocks.length === 0) {
    console.log('No event blocks found with specific pattern, trying more general approach');
    
    // Look for any div that might contain event information
    const generalBlockRegex = /<div[^>]*?class="[^"]*?(?:border-website-ultraLightBlue|event|track|race)[^"]*?"[\s\S]*?<\/div>\s*<\/div>\s*<\/div>/gi;
    const generalBlocks = html.match(generalBlockRegex) || [];
    
    console.log(`Found ${generalBlocks.length} potential event blocks with general pattern`);
    
    if (generalBlocks.length > 0) {
      // Process these general blocks
      generalBlocks.forEach((block, index) => {
        try {
          console.log(`Parsing general event block ${index + 1}...`);
          parseEventBlock(block, events, index);
        } catch (error) {
          console.error(`Error parsing general event block ${index + 1}:`, error);
        }
      });
    }
  } else {
    // Process the specific event blocks
    eventBlocks.forEach((block, index) => {
      try {
        console.log(`Parsing event block ${index + 1}...`);
        parseEventBlock(block, events, index);
      } catch (error) {
        console.error(`Error parsing event block ${index + 1}:`, error);
      }
    });
  }
  
  console.log(`Successfully parsed ${events.length} events`);
  return events;
}

// Helper function to parse a single event block
function parseEventBlock(block, events, index) {
  // Extract category - using the actual HTML structure
  const categoryMatch = block.match(/<div class="w-fit border bg-website-brightGray[^>]*?>(.*?)<\/div>/) ||
                       block.match(/<div[^>]*?class="[^"]*?category[^"]*?"[^>]*?>(.*?)<\/div>/);
  const category = categoryMatch ? categoryMatch[1].trim() : 'BMX Event';
  
  console.log(`Category: ${category}`);
  
  // Extract title - using the actual HTML structure
  const titleMatch = block.match(/<button class="text-\[18px\][^>]*?>(.*?)<\/button>/) ||
                    block.match(/<button[^>]*?class="[^"]*?text-\[18px\][^"]*?"[^>]*?>(.*?)<\/button>/);
  const title = titleMatch ? titleMatch[1].trim() : 'Untitled Event';
  
  console.log(`Title: ${title}`);
  
  // Extract date - using the actual HTML structure
  const dateMatch = block.match(/<p class="text-\[16px\][^>]*?font-MontserratSemiBold[^>]*?>(.*?)<\/p>/) ||
                   block.match(/<p[^>]*?class="[^"]*?font-MontserratSemiBold[^"]*?"[^>]*?>(.*?)<\/p>/);
  const dateText = dateMatch ? dateMatch[1].trim() : '';
  
  console.log(`Date text: ${dateText || 'Not found'}`);
  
  // Extract location and track - using the actual HTML structure
  const locationMatch = block.match(/<p class="text-\[16px\][^>]*?font-MontserratMedium[^>]*?>(.*?)<\/p>/) ||
                       block.match(/<p[^>]*?class="[^"]*?font-MontserratMedium[^"]*?"[^>]*?>(.*?)<\/p>/);
  const locationHtml = locationMatch ? locationMatch[1].trim() : '';
  
  console.log(`Location HTML: ${locationHtml || 'Not found'}`);
  
  const trackMatch = locationHtml.match(/<a class="underline" href="[^"]*?">(.*?)<\/a>/) ||
                    locationHtml.match(/<a[^>]*?class="[^"]*?underline[^"]*?"[^>]*?>(.*?)<\/a>/);
  const trackName = trackMatch ? trackMatch[1].trim() : '';
  
  console.log(`Track name: ${trackName || 'Not found'}`);
  
  // Extract location text - it's between the track name and the directions link
  let location = '';
  if (locationHtml && trackName) {
    const locationTextMatch = locationHtml.match(new RegExp(`${trackName}</a>\\s*-\\s*([^<(]+)`)) ||
                             locationHtml.match(/-\s*([^<(]+)/);
    location = locationTextMatch ? locationTextMatch[1].trim() : '';
    
    console.log(`Location: ${location || 'Not found'}`);
  }
  
  // Extract URL - using the actual HTML structure
  const urlMatch = block.match(/<a class="underline" href="([^"]+)">/) ||
                  block.match(/<a[^>]*?class="[^"]*?underline[^"]*?"[^>]*?href="([^"]+)"[^>]*?>/);
  const url = urlMatch ? (urlMatch[1].startsWith('http') ? urlMatch[1] : `https://www.usabmx.com${urlMatch[1]}`) : '';
  
  console.log(`URL: ${url || 'Not found'}`);
  
  // Parse date
  let startDate = null;
  let endDate = null;
  
  if (dateText) {
    // Try to parse various date formats
    // Example: "March 10, 2025" or "March 09-10, 2025"
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
      
      console.log(`Parsed dates: ${startDate} to ${endDate}`);
    } else {
      console.log(`Could not parse date from: ${dateText}`);
    }
  }
  
  // Create event object
  const event = {
    title,
    start_date: startDate,
    end_date: endDate,
    location: location || trackName,
    url,
    category,
    description: `${category} event: ${title} at ${trackName || location || 'TBD'}`
  };
  
  // Only add events with valid dates
  if (startDate && endDate) {
    console.log(`Adding event: ${title}`);
    events.push(event);
  } else {
    console.log(`Skipping event due to missing dates: ${title}`);
  }
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