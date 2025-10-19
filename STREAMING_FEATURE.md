# Live Stream CCTV Feature

This document describes the live streaming CCTV feature that has been implemented in the ATCS KPI system.

## Feature Overview

The live stream feature provides a dedicated page for viewing CCTV cameras with the following capabilities:

1. **Live Streaming**: View real-time video feeds from CCTV cameras
2. **Screenshot Capture**: Take snapshots of the current video frame
3. **Video Recording**: Record clips of specified duration
4. **Multiple Camera Support**: Switch between different CCTV cameras

## Accessing the Feature

The live stream feature can be accessed at: `http://127.0.0.1:8000/livestream`

Users must be authenticated to access this page.

## Technical Implementation

### Backend Components

1. **StreamController**: Handles stream control operations (start, stop, snapshot, record)
2. **FfmpegStreamService**: Manages FFmpeg processes for streaming, screenshots, and recording
3. **CCTV Model**: Stores camera information including RTSP URLs and HLS paths

### Frontend Components

1. **livestream.blade.php**: Main page template with camera list and video player
2. **JavaScript**: Handles API calls and UI interactions

### Directory Structure

- `public/live/`: HLS stream files (.m3u8 playlists and .ts segments)
- `public/screenshots/`: Captured screenshots (.jpg files)
- `public/recordings/`: Recorded video clips (.mp4 files)

## Usage Instructions

### Starting a Live Stream

1. Navigate to the live stream page
2. Select a CCTV camera from the list
3. Click the "Start Stream" button
4. The video will begin playing in the player

### Taking a Screenshot

1. Ensure a camera is selected
2. Click the "Screenshot" button
3. The screenshot will be saved and appear in the recordings panel

### Recording a Video Clip

1. Ensure a camera is selected
2. Set the desired recording duration (5-300 seconds)
3. Click the "Record" button
4. The recording will start and automatically save when complete
5. The video will appear in the recordings panel

### Stopping a Stream

1. Click the "Stop Stream" button
2. The video player will stop and reset

## API Endpoints

- `POST /api/cctvs/{id}/start-stream`: Start streaming for a CCTV
- `POST /api/cctvs/{id}/stop-stream`: Stop streaming for a CCTV
- `POST /stream/{id}/snapshot`: Take a screenshot
- `POST /stream/{id}/record`: Record a video clip

## HLS Stream URLs

Generated HLS streams follow the pattern: `http://127.0.0.1:8000/live/cctv_{id}.m3u8`

For example: `http://127.0.0.1:8000/live/cctv_1.m3u8`

## Requirements

1. FFmpeg must be installed and accessible
2. Write permissions for `public/live`, `public/screenshots`, and `public/recordings` directories
3. RTSP cameras with accessible URLs
