<x-layouts.app :title="__('Maps')">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

    <style>
        /* Container mengikuti pola halaman lain: padding internal, tanpa margin luar */
        .maps-container {
            display: flex;
            flex-direction: column;
            padding: 1rem;
            gap: 0.75rem;
            width: 100%;
            box-sizing: border-box;
            max-width: 100%;
            position: relative;
            z-index: 1; /* Ensure map stays behind sidebar */
        }

        /* Header: samakan skala/spacing seperti halaman lain */
        .maps-header {
            background: linear-gradient(145deg, #1a3c6c, #2a5a9c);
            color: #fff;
            padding: 14px 18px;
            border-radius: 12px;
            border: 1px solid #356bb5;
            box-shadow: 0 8px 20px rgba(0,0,0,.12);
            display: flex;
            align-items: center;
            gap: 10px;
            z-index: 10; /* Reduced z-index to stay below app header */
            position: relative;
        }
        .maps-title { font-size: 20px; font-weight: 700; }

        .maps-controls {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 12px;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            box-shadow: 0 6px 16px rgba(0,0,0,.08);
            z-index: 10; /* Reduced z-index to stay below app header */
            position: relative;
        }
        .controls-left { flex: 1; display: flex; justify-content: flex-start; }
        .controls-right { display: flex; align-items: center; gap: 10px; margin-left: auto; }

        .search-box { position: relative; width: min(420px, 100%); }
        .search-input {
            width: 100%;
            padding: 10px 14px 10px 14px;
            border: 2px solid #e5e7eb;
            border-radius: 22px;
            outline: none;
            transition: .2s;
            background-color: #fff;
            color: #333;
        }
        .search-input:focus { border-color: #3a7ad9; box-shadow: 0 0 0 3px rgba(58,122,217,.15); }
        .search-results { position: absolute; top: 110%; left: 0; right: 0; background: #fff; border: 1px solid #e5e7eb; border-radius: 10px; box-shadow: 0 12px 24px rgba(0,0,0,.12); display: none; max-height: 320px; overflow: auto; z-index: 1000; }
        .search-results.active { display: block; }
        .search-item { padding: 10px 12px; cursor: pointer; border-bottom: 1px solid #f3f4f6; color: #333; }
        .search-item:last-child { border-bottom: 0; }
        .search-item:hover { background: #f8fafc; }

        .layer-btn {
            padding: 8px 14px; border-radius: 10px; border: 2px solid transparent; background: #fff; cursor: pointer; font-weight: 600; transition: .2s; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 4px 10px rgba(0,0,0,.08);
            color: #333;
        }
        .layer-btn.active { background: linear-gradient(145deg, #2a5a9c, #3a7ad9); color: #fff; border-color: #3a7ad9; }

        .status-pill { width: 38px; height: 38px; border-radius: 50%; border: 3px solid #fff; box-shadow: 0 6px 14px rgba(0,0,0,.18); display: inline-flex; align-items: center; justify-content: center; color: #fff; font-size: 18px; cursor: pointer; transition: transform .15s; }
        .status-pill:hover { transform: translateY(-2px); }
        .pill-online { background: #22c55e; }
        .pill-offline { background: #ef4444; }
        .pill-maint { background: #f59e0b; }
        .pill-inactive { opacity: .45; filter: grayscale(30%); }

        .map-shell {
            position: relative;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            overflow: hidden;
            background: #fff;
            box-shadow: 0 10px 24px rgba(0,0,0,.12);
            width: 100%;
            box-sizing: border-box;
            max-width: 100%;
            z-index: 5; /* Reduced z-index to stay below app header */
            visibility: visible;
            opacity: 1;
        }
        #map {
            width: 100%;
            height: 75vh;
            min-height: 500px;
            max-width: 100%;
            z-index: 5; /* Reduced z-index to stay below app header */
            visibility: visible !important;
            opacity: 1 !important;
            background: #ddd;
            display: block !important;
            position: relative !important;
        }
        
        /* Force immediate visibility for Leaflet elements */
        .leaflet-container {
            visibility: visible !important;
            opacity: 1 !important;
            display: block !important;
        }
        
        .leaflet-tile-pane {
            visibility: visible !important;
            opacity: 1 !important;
        }
        
        .leaflet-tile {
            visibility: visible !important;
            opacity: 1 !important;
        }
        
        /* Force all Leaflet panes to be visible */
        .leaflet-map-pane,
        .leaflet-tile-pane,
        .leaflet-overlay-pane,
        .leaflet-shadow-pane,
        .leaflet-marker-pane,
        .leaflet-tooltip-pane,
        .leaflet-popup-pane {
            visibility: visible !important;
            opacity: 1 !important;
        }
        
        /* Force tile images to load immediately */
        .leaflet-tile img {
            visibility: visible !important;
            opacity: 1 !important;
            display: block !important;
        }
        
        /* Move zoom controls to bottom right */
        .leaflet-control-zoom {
            position: absolute !important;
            top: auto !important;
            left: auto !important;
            right: 15px !important;
            bottom: 15px !important;
            margin: 0 !important;
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
            z-index: 1000 !important;
            width: auto !important;
            height: auto !important;
        }
        
        .leaflet-control-zoom a {
            background: #fff !important;
            border: 1px solid #ccc !important;
            color: #333 !important;
            box-shadow: 0 2px 8px rgba(0,0,0,.15) !important;
            border-radius: 4px !important;
            margin-bottom: 3px !important;
            width: 30px !important;
            height: 30px !important;
            line-height: 28px !important;
            text-align: center !important;
            display: block !important;
            position: relative !important;
        }
        
        .leaflet-control-zoom a:hover {
            background: #f5f5f5 !important;
            border-color: #999 !important;
        }
        
        /* Responsive zoom controls */
        @media (max-width: 768px) {
            .leaflet-control-zoom {
                right: 12px !important;
                bottom: 12px !important;
            }
            
            .leaflet-control-zoom a {
                width: 32px !important;
                height: 32px !important;
                line-height: 30px !important;
                font-size: 16px !important;
                margin-bottom: 4px !important;
            }
        }
        
        @media (max-width: 480px) {
            .leaflet-control-zoom {
                right: 10px !important;
                bottom: 10px !important;
            }
            
            .leaflet-control-zoom a {
                width: 28px !important;
                height: 28px !important;
                line-height: 26px !important;
                font-size: 14px !important;
                margin-bottom: 3px !important;
            }
        }

        /* Tombol Back saat room view */
        .back-btn { position: absolute; top: 10px; left: 10px; z-index: 50; display: none; }
        .back-btn button { padding: 8px 12px; border-radius: 8px; border: 0; background: #111827; color: #fff; display: inline-flex; gap: 6px; align-items: center; box-shadow: 0 6px 14px rgba(0,0,0,.25); cursor: pointer; }
        .back-btn button:hover { background: #1f2937; transform: translateY(-1px); }
        .back-btn button:active { transform: translateY(0); }

        /* Popup Room & CCTV card */
        .popup-room-header { background: linear-gradient(145deg,#1a3c6c,#2a5a9c); color: #fff; padding: 10px 12px; border-radius: 8px; font-weight: 700; margin-bottom: 8px; }
        .cctv-card { border: 1px solid #e5e7eb; border-radius: 10px; padding: 10px; margin-bottom: 8px; background: linear-gradient(180deg,#fff,#f9fafb); box-shadow: 0 4px 10px rgba(0,0,0,.06); }
        .btn-live { width: 100%; padding: 8px; border-radius: 8px; border: none; color: #fff; background: linear-gradient(145deg,#2a5a9c,#3a7ad9); cursor: pointer; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; justify-content: center; }

        /* Modal streaming */
        .modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,.85); display: none; align-items: center; justify-content: center; z-index: 10000; }
        .modal-overlay.active { display: flex; }
        .modal { width: min(650px, 80vw); max-height: 80vh; background: #111; border-radius: 14px; overflow: hidden; box-shadow: 0 30px 80px rgba(0,0,0,.6); display: flex; flex-direction: column; }
        .modal-header { display: flex; align-items: center; justify-content: space-between; padding: 12px 14px; background: linear-gradient(145deg, #1a3c6c, #2a5a9c); color: #fff; }
        .modal-actions { display: flex; gap: 8px; }
        .modal-btn { padding: 8px 12px; border-radius: 8px; border: none; color: #fff; background: #2563eb; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; }
        .modal-close { background: #ef4444; }
        .modal-body { padding: 10px; background: #000; }
        .player { width: 100%; height: min(50vh, 500px); background: #000; aspect-ratio: 16/9; }
        
        /* Responsive modal sizing */
        @media (max-width: 768px) {
            .modal { width: min(90vw, 500px); max-height: 85vh; }
            .player { height: min(45vh, 350px); }
        }
        
        @media (max-width: 480px) {
            .modal { width: 90vw; max-height: 80vh; }
            .player { height: min(40vh, 300px); }
        }

        /* Dark mode adjustments */
        .dark .maps-controls {
            background: #1f2937;
            border: 1px solid #374151;
        }
        .dark .search-input {
            background-color: #1f2937;
            border: 2px solid #4b5563;
            color: #f9fafb;
        }
        .dark .search-input::placeholder {
            color: #9ca3af;
        }
        .dark .search-results {
            background: #1f2937;
            border: 1px solid #4b5563;
        }
        .dark .search-item {
            color: #f9fafb;
            border-bottom: 1px solid #374151;
        }
        .dark .search-item:hover {
            background: #374151;
        }
        .dark .layer-btn {
            background: #1f2937;
            border: 2px solid #4b5563;
            color: #f9fafb;
        }
        .dark .layer-btn.active {
            background: linear-gradient(145deg, #2a5a9c, #3a7ad9);
            color: #fff;
            border-color: #3a7ad9;
        }

        /* Ensure the map container respects the main content area */
        main .maps-container {
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
        }

        /* Explicitly set the map shell to not exceed its container */
        main .map-shell {
            max-width: 100%;
            box-sizing: border-box;
        }

        /* Make sure the map element itself respects boundaries */
        #map {
            max-width: 100%;
            box-sizing: border-box;
        }

        /* Prevent map from overlapping sidebar */
        .flex-1 .maps-container {
            width: 100%;
            max-width: 100%;
        }

        /* Ensure map stays behind sidebar by setting a lower z-index */
        .maps-container, .maps-header, .maps-controls, .map-shell {
            z-index: 1 !important;
        }

        /* Additional fix to ensure map stays behind sidebar */
        .leaflet-container {
            z-index: 1 !important;
        }

        /* Ensure map controls also stay behind sidebar */
        .leaflet-control-container {
            z-index: 1 !important;
        }

        /* Fix for initial map visibility */
        .leaflet-container {
            background: #ddd;
        }

        /* Ensure header stays on top of everything */
        header.flux-header {
            z-index: 1000 !important;
            position: relative;
        }
    </style>

    <div class="maps-container">
        <div class="maps-header">
            <h1 class="text-4xl font-bold mb-2 gradient-text-outline">Maps</h1>
            <!-- Removed refresh indicator as we're no longer doing real-time updates -->
        </div>

        <div class="maps-controls">
            <div class="controls-left">
                <div class="search-box">
                    <input id="search-input" class="px-3 py-2 rounded-lg bg-white/5 border-2 border-gray-300 dark:border-white/10 pr-10 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Cari gedung..." type="text">
                    <div id="search-results" class="search-results"></div>
                </div>
            </div>
            <div class="controls-right">
                <button class="layer-btn active" data-layer="osm">OpenStreetMap</button>
                <button class="layer-btn" data-layer="satellite">Satellite</button>
                <div id="pill-online" class="status-pill pill-online" title="Online"><i class='bx bxs-check-circle'></i></div>
                <div id="pill-offline" class="status-pill pill-offline" title="Offline"><i class='bx bxs-x-circle'></i></div>
                <div id="pill-maint" class="status-pill pill-maint" title="Maintenance"><i class='bx bxs-error'></i></div>
            </div>
        </div>

        <div class="map-shell">
            <div class="back-btn" id="back-btn"><button type="button"><i class='bx bxs-chevron-left'></i> Kembali</button></div>
            <div id="map"></div>
        </div>
    </div>

    <!-- Streaming Modal -->
    <div id="stream-modal" class="modal-overlay" role="dialog" aria-modal="true">
        <div class="modal">
            <div class="modal-header">
                <div id="modal-title"><i class='bx bxs-videos'></i> Live Stream</div>
                <div class="modal-actions">
                    <button id="btn-close" class="modal-btn modal-close"><i class='bx bxs-x-circle'></i> Close</button>
                </div>
            </div>
            <div class="modal-body">
                <video id="player" class="player" controls playsinline></video>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
    <script>
        let map, osmLayer, satLayer;
        let buildings = [];
        let buildingMarkers = [];
        let roomMarkers = [];
        const activeStatuses = new Set(['online','offline','maintenance']);
        let inRoomView = false; let currentBuildingId = null;

        const CENTER = [-6.3326, 108.4582];

        // Initialize immediately when script loads, not waiting for DOMContentLoaded
        (function() {
            // Wait for both DOM and Leaflet to be ready
            const initWhenReady = () => {
                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', initWhenReady);
                    return;
                }
                
                // Check if Leaflet is loaded
                if (typeof L === 'undefined') {
                    setTimeout(initWhenReady, 10);
                    return;
                }
                
                // Initialize everything immediately
                initMap();
                bindControls();
                loadData();
                
                // Ensure back button is hidden on initial load
                const backButton = document.getElementById('back-btn');
                if (backButton) {
                    backButton.style.display = 'none';
                }
                
                // Force immediate rendering
                if (map) {
                    map.invalidateSize(true);
                    map.getContainer().style.visibility = 'visible';
                    map.getContainer().style.opacity = '1';
                    
                    // Force tile pane visibility immediately
                    const tilePane = map.getPane('tilePane');
                    if (tilePane) {
                        tilePane.style.visibility = 'visible';
                        tilePane.style.opacity = '1';
                    }
                    
                    // Force map to show tiles immediately
                    map.setView(CENTER, 15);
                    map.invalidateSize(true);
                    
                    // Force tiles to load immediately
                    setTimeout(() => {
                        if (map) {
                            map.setView(CENTER, 15);
                            map.invalidateSize(true);
                            
                            // Force all tiles to be visible
                            const tiles = map.getContainer().querySelectorAll('.leaflet-tile');
                            tiles.forEach(tile => {
                                tile.style.visibility = 'visible';
                                tile.style.opacity = '1';
                            });
                        }
                    }, 100);
                }
            };
            
            initWhenReady();
        })();

        function initMap(){
            // Ensure map container is visible before initialization
            const mapContainer = document.getElementById('map');
            if (!mapContainer) {
                console.error('Map container not found');
                return;
            }

            // Pre-load tiles aggressively before map initialization
            const preloadTiles = () => {
                const tiles = [
                    'https://tile.openstreetmap.org/15/26240/17375.png',
                    'https://tile.openstreetmap.org/15/26241/17375.png',
                    'https://tile.openstreetmap.org/15/26240/17376.png',
                    'https://tile.openstreetmap.org/15/26241/17376.png',
                    'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/15/17375/26240',
                    'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/15/17375/26241',
                    'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/15/17376/26240',
                    'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/15/17376/26241'
                ];
                
                tiles.forEach(url => {
                    const img = new Image();
                    img.crossOrigin = 'anonymous';
                    img.src = url;
                });
            };
            preloadTiles();

            map = L.map('map', {
                zoomControl: true,
                preferCanvas: false,
                fadeAnimation: false, // Disable animations for instant loading
                zoomAnimation: false,
                markerZoomAnimation: false,
                bounceAtZoomLimits: false
            }).setView(CENTER, 15);

            // Initialize OSM layer with instant loading settings
            osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap',
                updateWhenIdle: false,
                updateWhenZooming: false,
                keepBuffer: 0, // No buffering for instant loading
                loadingTimeout: 2000,
                crossOrigin: true,
                detectRetina: false,
                zoomOffset: 0,
                noWrap: false,
                bounds: null,
                minZoom: 1,
                tileSize: 256,
                maxNativeZoom: 19
            });

            // Initialize Satellite layer with instant loading settings
            satLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                maxZoom: 19,
                attribution: 'Tiles © Esri',
                updateWhenIdle: false,
                updateWhenZooming: false,
                keepBuffer: 0, // No buffering for instant loading
                loadingTimeout: 2000,
                crossOrigin: true,
                detectRetina: false,
                zoomOffset: 0,
                noWrap: false,
                bounds: null,
                minZoom: 1,
                tileSize: 256,
                maxNativeZoom: 19
            });

            // Add OSM layer by default with immediate rendering
            osmLayer.addTo(map);
            
            // Force immediate rendering
            map.getContainer().style.visibility = 'visible';
            map.getContainer().style.opacity = '1';
            map.invalidateSize(true);
            
            // Force map to show tiles immediately
            map.setView(CENTER, 15);
            map.invalidateSize(true);
            
            // Force tiles to load immediately with multiple attempts
            const forceTileLoad = () => {
                if (map) {
                    map.setView(CENTER, 15);
                    map.invalidateSize(true);
                    
                    // Force all tiles to be visible
                    const tiles = map.getContainer().querySelectorAll('.leaflet-tile');
                    tiles.forEach(tile => {
                        tile.style.visibility = 'visible';
                        tile.style.opacity = '1';
                    });
                    
                    // Force tile pane visibility
                    const tilePane = map.getPane('tilePane');
                    if (tilePane) {
                        tilePane.style.visibility = 'visible';
                        tilePane.style.opacity = '1';
                    }
                }
            };
            
            // Multiple attempts to force tile loading
            forceTileLoad();
            setTimeout(forceTileLoad, 50);
            setTimeout(forceTileLoad, 100);
            setTimeout(forceTileLoad, 200);
            setTimeout(forceTileLoad, 500);
            
            // Ensure zoom controls are visible
            setTimeout(() => {
                const zoomControl = document.querySelector('.leaflet-control-zoom');
                if (zoomControl) {
                    zoomControl.style.display = 'block';
                    zoomControl.style.visibility = 'visible';
                    zoomControl.style.opacity = '1';
                    zoomControl.style.position = 'absolute';
                    zoomControl.style.right = '15px';
                    zoomControl.style.bottom = '15px';
                    zoomControl.style.zIndex = '1000';
                    zoomControl.style.width = 'auto';
                    zoomControl.style.height = 'auto';
                }
            }, 100);

            // Add event listeners
            map.on('click', (e) => {
                if (e.originalEvent.target.closest('#back-btn')) return;
                if (inRoomView && !e.originalEvent.target.closest('.leaflet-marker-icon')) restoreBuildingView();
            });

            // Force tile loading immediately
            map.on('tileload', () => {
                map.getContainer().style.visibility = 'visible';
                map.getContainer().style.opacity = '1';
                
                // Force tile pane visibility
                const tilePane = map.getPane('tilePane');
                if (tilePane) {
                    tilePane.style.visibility = 'visible';
                    tilePane.style.opacity = '1';
                }
                
                // Force all tiles to be visible
                const tiles = map.getContainer().querySelectorAll('.leaflet-tile');
                tiles.forEach(tile => {
                    tile.style.visibility = 'visible';
                    tile.style.opacity = '1';
                });
            });

            // Force map to show tiles immediately
            map.on('viewreset', () => {
                map.getContainer().style.visibility = 'visible';
                map.getContainer().style.opacity = '1';
                
                // Force tile pane visibility
                const tilePane = map.getPane('tilePane');
                if (tilePane) {
                    tilePane.style.visibility = 'visible';
                    tilePane.style.opacity = '1';
                }
            });

            // Force tiles to load on zoom
            map.on('zoomend', () => {
                map.getContainer().style.visibility = 'visible';
                map.getContainer().style.opacity = '1';
                
                // Force tile pane visibility
                const tilePane = map.getPane('tilePane');
                if (tilePane) {
                    tilePane.style.visibility = 'visible';
                    tilePane.style.opacity = '1';
                }
            });

            // Force tiles to load on move
            map.on('moveend', () => {
                map.getContainer().style.visibility = 'visible';
                map.getContainer().style.opacity = '1';
                
                // Force tile pane visibility
                const tilePane = map.getPane('tilePane');
                if (tilePane) {
                    tilePane.style.visibility = 'visible';
                    tilePane.style.opacity = '1';
                }
            });

            // Multiple immediate rendering attempts
            const forceRender = () => {
                if (map) {
                    map.invalidateSize(true);
                    map.getContainer().style.visibility = 'visible';
                    map.getContainer().style.opacity = '1';
                    
                    // Force tile pane visibility
                    const tilePane = map.getPane('tilePane');
                    if (tilePane) {
                        tilePane.style.visibility = 'visible';
                        tilePane.style.opacity = '1';
                    }
                    
                    // Force all tile images to be visible
                    const tiles = map.getContainer().querySelectorAll('.leaflet-tile');
                    tiles.forEach(tile => {
                        tile.style.visibility = 'visible';
                        tile.style.opacity = '1';
                    });
                    
                    // Force map to refresh tiles
                    map.setView(map.getCenter(), map.getZoom());
                    
                    // Force zoom controls to be visible
                    const zoomControl = document.querySelector('.leaflet-control-zoom');
                    if (zoomControl) {
                        zoomControl.style.display = 'block';
                        zoomControl.style.visibility = 'visible';
                        zoomControl.style.opacity = '1';
                        zoomControl.style.position = 'absolute';
                        zoomControl.style.right = '15px';
                        zoomControl.style.bottom = '15px';
                        zoomControl.style.zIndex = '1000';
                        zoomControl.style.width = 'auto';
                        zoomControl.style.height = 'auto';
                    }
                }
            };

            // Immediate rendering without delays
            forceRender();
            requestAnimationFrame(forceRender);
            requestAnimationFrame(() => requestAnimationFrame(forceRender));
            
            // Additional fallback rendering
            setTimeout(forceRender, 0);
            setTimeout(forceRender, 10);
            setTimeout(forceRender, 50);
            setTimeout(forceRender, 100);
            setTimeout(forceRender, 200);
            setTimeout(forceRender, 500);
            
            // Use MutationObserver to watch for tile changes and force visibility
            const observer = new MutationObserver((mutations) => {
                mutations.forEach((mutation) => {
                    if (mutation.type === 'childList') {
                        mutation.addedNodes.forEach((node) => {
                            if (node.nodeType === Node.ELEMENT_NODE) {
                                // Check if it's a tile element
                                if (node.classList && node.classList.contains('leaflet-tile')) {
                                    node.style.visibility = 'visible';
                                    node.style.opacity = '1';
                                }
                                // Check for zoom controls
                                if (node.classList && node.classList.contains('leaflet-control-zoom')) {
                                    node.style.display = 'block';
                                    node.style.visibility = 'visible';
                                    node.style.opacity = '1';
                                    node.style.position = 'absolute';
                                    node.style.right = '15px';
                                    node.style.bottom = '15px';
                                    node.style.zIndex = '1000';
                                    node.style.width = 'auto';
                                    node.style.height = 'auto';
                                }
                                // Check for tile images
                                const tileImages = node.querySelectorAll ? node.querySelectorAll('.leaflet-tile img') : [];
                                tileImages.forEach(img => {
                                    img.style.visibility = 'visible';
                                    img.style.opacity = '1';
                                });
                            }
                        });
                    }
                });
                forceRender();
            });
            
            // Start observing the map container
            if (map && map.getContainer()) {
                observer.observe(map.getContainer(), {
                    childList: true,
                    subtree: true
                });
            }
        }

        async function loadData(){
            try {
                const res = await fetch('/map-data');
                if (!res.ok) {
                    throw new Error(`HTTP error! status: ${res.status}`);
                }
                const json = await res.json();
                buildings = json.buildings || [];
                renderBuildings();
            } catch(err){
                console.error('Error loading map data:', err);
                // Optionally show an error message to the user
            }
        }

        // Load data once on page load, no more polling
        // setInterval(() => {
        //     loadData().catch(err => {
        //         console.error('Error in polling:', err);
        //     });
        // }, 15000);

        function renderBuildings(){
            // Only clear and re-render if there are actual changes
            const previousBuildingCount = buildingMarkers.length;
            const newBuildingCount = buildings.length;

            // If counts are different, re-render everything
            if (previousBuildingCount !== newBuildingCount) {
                roomMarkers.forEach(m => map.removeLayer(m)); roomMarkers = [];
                buildingMarkers.forEach(m => map.removeLayer(m)); buildingMarkers = [];
                document.getElementById('back-btn').style.display = 'none';
                inRoomView = false; currentBuildingId = null;

                buildings.forEach((b) => {
                    const lat = (b.latitude ?? CENTER[0]);
                    const lng = (b.longitude ?? CENTER[1]);
                    const icon = L.divIcon({ className: 'marker-building', html: `<div style=\"display:flex;align-items:center;justify-content:center;width:42px;height:42px;border-radius:50%;background:linear-gradient(145deg,#1a3c6c,#2a5a9c);border:3px solid #fff;box-shadow:0 8px 18px rgba(0,0,0,.35);color:#fff;\"><i class='bx bxs-buildings'></i></div>` });
                    const mk = L.marker([lat,lng], { icon }).addTo(map).on('click', () => zoomToBuilding(b));
                    mk.bindPopup(`<div style=\"min-width:220px\"><div class=\"popup-room-header\">${b.name}</div><div style=\"color:#334155;font-size:13px\">Klik untuk melihat ruangan dan CCTV</div></div>`);
                    buildingMarkers.push(mk);
                });
            } else {
                // Update existing markers with new data without removing them
                buildingMarkers.forEach((mk, index) => {
                    const b = buildings[index];
                    if (b) {
                        // Update popup content if needed
                        mk.setPopupContent(`<div style=\"min-width:220px\"><div class=\"popup-room-header\">${b.name}</div><div style=\"color:#334155;font-size:13px\">Klik untuk melihat ruangan dan CCTV</div></div>`);
                    }
                });
            }
        }

        function zoomToBuilding(b){
            const lat = b.latitude ?? CENTER[0];
            const lng = b.longitude ?? CENTER[1];
            map.setView([lat,lng], 18);

            // Only re-render room markers if the building has changed
            if (currentBuildingId !== b.id) {
                // sembunyikan marker building, render room untuk gedung ini
                buildingMarkers.forEach(m => map.removeLayer(m));
                roomMarkers.forEach(m => map.removeLayer(m)); roomMarkers = [];
                (b.rooms||[]).forEach(r => renderRoom(r, b));
                inRoomView = true; currentBuildingId = b.id;

                // Show back button with better visibility
                const backButton = document.getElementById('back-btn');
                if (backButton) {
                    backButton.style.display = 'block';
                    // Ensure it's on top
                    backButton.style.zIndex = '1000';
                }
            }
        }

        function renderRoom(r, b){
            const baseLat = b.latitude ?? CENTER[0];
            const baseLng = b.longitude ?? CENTER[1];
            // Use fixed positioning based on room ID to prevent jumping
            const lat = r.latitude ?? (baseLat + (r.id * 0.0001) % 0.001 - 0.0005);
            const lng = r.longitude ?? (baseLng + (r.id * 0.0002) % 0.001 - 0.0005);
            const hasStatus = (r.cctvs||[]).some(c => activeStatuses.has(c.status));
            if(!hasStatus) return;
            const icon = L.divIcon({ className: 'marker-room', html: `<div style=\"display:flex;align-items:center;justify-content:center;width:34px;height:34px;border-radius:50%;background:#22c55e;border:3px solid #fff;box-shadow:0 6px 16px rgba(0,0,0,.28);color:#fff;\"><i class='bx bxs-store-alt'></i></div>` });
            const popupHtml = `
                <div style=\"min-width:260px\">
                    <div class=\"popup-room-header\">${r.name}</div>
                    ${(r.cctvs||[]).filter(c => activeStatuses.has(c.status)).map(c => `
                        <div class='cctv-card'>
                            <div style=\"font-weight:700;color:#1f2937;margin-bottom:6px;display:flex;align-items:center;gap:6px\"><i class='bx bxs-cctv'></i> ${c.name || 'CCTV'}</div>
                            <button class='btn-live' onclick=\"openStream(${c.id}, '${(r.name||'Room').replace(/'/g, "\\'")} - ${(c.name||'CCTV').replace(/'/g, "\\'")}')\"><i class='bx bxs-videos'></i> Live CCTV</button>
                        </div>
                    `).join('')}
                </div>`;
            const mk = L.marker([lat,lng], { icon }).addTo(map).bindPopup(popupHtml);
            roomMarkers.push(mk);
        }

        function restoreBuildingView(){
            console.log('restoreBuildingView called');
            // Show building markers again
            buildingMarkers.forEach(m => {
                if (!map.hasLayer(m)) {
                    m.addTo(map);
                }
            });

            // Remove room markers
            roomMarkers.forEach(m => map.removeLayer(m));
            roomMarkers = [];

            // Reset view state
            inRoomView = false;
            currentBuildingId = null;

            // Hide back button
            const backButton = document.getElementById('back-btn');
            if (backButton) {
                backButton.style.display = 'none';
            }

            // Reset map view to show all buildings
            map.setView(CENTER, 15);
            console.log('restoreBuildingView completed');
        }

        function bindControls(){
            // Layer switch
            document.querySelectorAll('.layer-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    document.querySelectorAll('.layer-btn').forEach(b=>b.classList.remove('active'));
                    btn.classList.add('active');
                    
                    if(btn.dataset.layer === 'osm'){
                        if (map.hasLayer(satLayer)) map.removeLayer(satLayer);
                        if (!map.hasLayer(osmLayer)) {
                            osmLayer.addTo(map);
                            
                            // Force immediate rendering without delays
                            const forceLayerRender = () => {
                                if (map) {
                                    map.invalidateSize(true);
                                    map.getContainer().style.visibility = 'visible';
                                    map.getContainer().style.opacity = '1';
                                    
                                    // Force tile pane visibility
                                    const tilePane = map.getPane('tilePane');
                                    if (tilePane) {
                                        tilePane.style.visibility = 'visible';
                                        tilePane.style.opacity = '1';
                                    }
                                    
                                    // Force all tile images to be visible
                                    const tiles = map.getContainer().querySelectorAll('.leaflet-tile');
                                    tiles.forEach(tile => {
                                        tile.style.visibility = 'visible';
                                        tile.style.opacity = '1';
                                    });
                                    
                                    // Force map to refresh tiles
                                    map.setView(map.getCenter(), map.getZoom());
                                }
                            };
                            
                            forceLayerRender();
                            requestAnimationFrame(forceLayerRender);
                            setTimeout(forceLayerRender, 0);
                            setTimeout(forceLayerRender, 10);
                            setTimeout(forceLayerRender, 50);
                            setTimeout(forceLayerRender, 100);
                        }
                    } else {
                        if (map.hasLayer(osmLayer)) map.removeLayer(osmLayer);
                        if (!map.hasLayer(satLayer)) {
                            satLayer.addTo(map);
                            
                            // Force immediate rendering without delays
                            const forceLayerRender = () => {
                                if (map) {
                                    map.invalidateSize(true);
                                    map.getContainer().style.visibility = 'visible';
                                    map.getContainer().style.opacity = '1';
                                    
                                    // Force tile pane visibility
                                    const tilePane = map.getPane('tilePane');
                                    if (tilePane) {
                                        tilePane.style.visibility = 'visible';
                                        tilePane.style.opacity = '1';
                                    }
                                    
                                    // Force all tile images to be visible
                                    const tiles = map.getContainer().querySelectorAll('.leaflet-tile');
                                    tiles.forEach(tile => {
                                        tile.style.visibility = 'visible';
                                        tile.style.opacity = '1';
                                    });
                                    
                                    // Force map to refresh tiles
                                    map.setView(map.getCenter(), map.getZoom());
                                }
                            };
                            
                            forceLayerRender();
                            requestAnimationFrame(forceLayerRender);
                            setTimeout(forceLayerRender, 0);
                            setTimeout(forceLayerRender, 10);
                            setTimeout(forceLayerRender, 50);
                            setTimeout(forceLayerRender, 100);
                        }
                    }
                    
                    // Additional immediate rendering
                    requestAnimationFrame(() => {
                        if (map) map.invalidateSize(true);
                    });
                });
            });
            // Ensure back button event listener is properly set up
            const backButton = document.getElementById('back-btn');
            if (backButton) {
                backButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    console.log('Back button clicked');
                    restoreBuildingView();
                });
            } else {
                console.log('Back button not found');
            }

            // Status filter
            const toggle = (pill, status) => {
                pill.addEventListener('click', () => {
                    if(activeStatuses.has(status)) { activeStatuses.delete(status); pill.classList.add('pill-inactive'); }
                    else { activeStatuses.add(status); pill.classList.remove('pill-inactive'); }
                    // re-render hanya room gedung aktif
                    roomMarkers.forEach(m=>map.removeLayer(m)); roomMarkers=[];
                    if (inRoomView) {
                        const b = buildings.find(x => x.id === currentBuildingId);
                        if (b) (b.rooms||[]).forEach(r => renderRoom(r, b));
                    }
                });
            };
            toggle(document.getElementById('pill-online'),'online');
            toggle(document.getElementById('pill-offline'),'offline');
            toggle(document.getElementById('pill-maint'),'maintenance');

            // Search
            const input = document.getElementById('search-input');
            const results = document.getElementById('search-results');
            input.addEventListener('input', () => {
                const q = input.value.trim().toLowerCase();
                if(!q){ results.classList.remove('active'); return; }
                const list = buildings.filter(b => (b.name||'').toLowerCase().includes(q));
                results.innerHTML = list.map(b => `<div class='search-item' onclick='selectBuilding(${b.id})'><i class=\"bx bxs-building-house\"></i> ${b.name}</div>`).join('') || "<div class='search-item'>Tidak ada hasil</div>";
                results.classList.add('active');
            });
            document.addEventListener('click', (e)=>{ const box=document.querySelector('.search-box'); if(box && !box.contains(e.target)) results.classList.remove('active'); });
        }

        window.selectBuilding = function(id){
            const b = buildings.find(x=>x.id===id); if(!b) return;
            document.getElementById('search-results').classList.remove('active');
            document.getElementById('search-input').value = b.name;
            zoomToBuilding(b);
        }

        // Streaming logic - Simplified for real-time
        let hls = null; let currentCctv = null;
        function openStream(id, title){
            currentCctv = id;
            document.getElementById('modal-title').innerHTML = `<i class='bx bxs-videos'></i> Live Stream - ${title}`;
            document.getElementById('stream-modal').classList.add('active');
            startStream(); // Auto-start stream for real-time experience
        }

        async function startStream(){
            if(!currentCctv) return;
            try{
                const res = await fetch(`/stream/${currentCctv}/start`, { method:'POST', headers:{ 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content } });
                const json = await res.json();
                if(json.success && json.hls){
                    const video = document.getElementById('player');
                    if(Hls.isSupported()){
                        if(hls) hls.destroy();
                        hls = new Hls({ enableWorker:true, lowLatencyMode:true });
                        hls.loadSource(json.hls); hls.attachMedia(video);
                        hls.on(Hls.Events.MANIFEST_PARSED, () => video.play());
                    } else if(video.canPlayType('application/vnd.apple.mpegurl')){
                        video.src = json.hls; video.play();
                    }
                } else { alert('Gagal memulai stream'); }
            }catch(e){ console.error(e); alert('Gagal memulai stream'); }
        }

        async function stopStream(){
            try{
                if(currentCctv) await fetch(`/stream/${currentCctv}/stop`, { method:'POST', headers:{ 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content } });
            }catch(_){}
            if(hls){ hls.destroy(); hls=null; }
            const v=document.getElementById('player');
            v.pause();
            v.removeAttribute('src');
            v.load();
        }

        document.getElementById('btn-close').addEventListener('click', () => {
            stopStream();
            document.getElementById('stream-modal').classList.remove('active');
        });

        // Add resize handler for responsive sidebar
        function handleMapResize() {
            if (typeof map !== 'undefined' && map) {
                // Force the map to recalculate its size
                map.invalidateSize();

                // Also adjust the container width explicitly
                const mapContainer = document.querySelector('.maps-container');
                if (mapContainer) {
                    // Reset to ensure it fits within the main content area
                    mapContainer.style.maxWidth = '100%';
                    mapContainer.style.width = '100%';
                }
            }
        }

        // Listen for window resize events
        window.addEventListener('resize', handleMapResize);

        // Also trigger resize when DOM content changes (for sidebar toggle)
        const resizeObserver = new ResizeObserver(handleMapResize);
        const mainContent = document.querySelector('main');
        if (mainContent) {
            resizeObserver.observe(mainContent);
        }

        // Initial resize
        setTimeout(handleMapResize, 100);
        setTimeout(handleMapResize, 500); // Additional delay for initial render

    </script>
</x-layouts.app>
