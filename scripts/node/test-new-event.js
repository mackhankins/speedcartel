#!/usr/bin/env node

/**
 * Test script to modify scraped events to simulate a new event
 * 
 * This script reads the temp_events.json file, modifies one event to make it appear new,
 * and saves it back to the file. If the file doesn't exist, it creates a sample event.
 */

const fs = require('fs');
const path = require('path');

// Path to the temp events file
const tempEventsPath = path.join(process.cwd(), 'storage', 'app', 'temp_events.json');

// Create storage/app directory if it doesn't exist
const storageAppDir = path.join(process.cwd(), 'storage', 'app');
if (!fs.existsSync(storageAppDir)) {
  console.log(`Creating directory: ${storageAppDir}`);
  fs.mkdirSync(storageAppDir, { recursive: true });
}

// Sample event to use if no events exist
const sampleEvent = {
  title: "Sample BMX Event",
  start_date: new Date().toISOString().split('T')[0],
  end_date: new Date(Date.now() + 86400000).toISOString().split('T')[0], // Tomorrow
  location: "Sample BMX Track, Anytown USA",
  url: "https://www.usabmx.com/tracks/sample-track",
  category: "SAMPLE EVENT",
  description: "This is a sample BMX event created for testing purposes"
};

let events = [];

// Check if the file exists
if (fs.existsSync(tempEventsPath)) {
  try {
    // Read existing events
    const data = fs.readFileSync(tempEventsPath, 'utf8');
    events = JSON.parse(data);
    console.log(`Read ${events.length} events from ${tempEventsPath}`);
  } catch (error) {
    console.error(`Error reading file: ${error.message}`);
    console.log('Creating a new file with a sample event');
    events = [sampleEvent];
  }
} else {
  console.log(`File not found: ${tempEventsPath}`);
  console.log('Creating a new file with a sample event');
  events = [sampleEvent];
}

// If no events, add a sample one
if (events.length === 0) {
  console.log('No events found, adding a sample event');
  events.push(sampleEvent);
}

// Take the first event and modify it to make it appear new
const originalEvent = events[0];
console.log('Original event:');
console.log(JSON.stringify(originalEvent, null, 2));

// Create a modified copy
const modifiedEvent = { ...originalEvent };

// Change the title to make it appear as a new event
const timestamp = new Date().toISOString().replace(/[:.]/g, '-');
modifiedEvent.title = `${originalEvent.title} - TEST NEW EVENT ${timestamp}`;

// Replace the first event with the modified one
events[0] = modifiedEvent;

console.log('Modified event:');
console.log(JSON.stringify(modifiedEvent, null, 2));

// Write the modified events back to the file
fs.writeFileSync(tempEventsPath, JSON.stringify(events, null, 2));

console.log(`Updated ${tempEventsPath} with the modified event`);
console.log('Now run: php artisan import:bmx-events --test'); 