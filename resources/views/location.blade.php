<x-layouts.app :title="__('Location')">
    <!-- Remove the fixed height container that conflicts with main layout -->
    <div class="location-container max-w-screen-xl mx-auto px-4 sm:px-6 py-6">
        <div class="location-header flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-zinc-800 dark:text-white system:text-zinc-900 text-center sm:text-left w-full sm:w-auto">
                Playlist Building
            </h1>

            <div class="location-filters flex flex-col sm:flex-row items-start sm:items-center gap-2 w-full sm:w-auto">
                <div class="relative w-full sm:w-auto">
                    <input id="q" class="w-full sm:w-64 md:w-72 px-3 py-2 rounded-lg bg-white/5 border-2 border-gray-300 dark:border-white/10 system:border-zinc-400 pr-10 text-gray-900 dark:text-white system:text-zinc-900 placeholder-gray-500 dark:placeholder-white/50 system:placeholder-zinc-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Search for Building..." />
                    <i class="bx bx-search absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 dark:text-white/70 system:text-zinc-700"></i>
                    <div id="q-suggest" class="hidden absolute right-0 mt-2 w-full sm:w-72 bg-black/70 border border-white/10 system:border-zinc-300 rounded-xl backdrop-blur-md shadow-2xl overflow-hidden z-10"></div>
                </div>
            </div>
        </div>

        <div id="buildings" class="buildings-grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mt-6"></div>
    </div>

    <!-- Mobile Location Responsive -->
    <style>
        @media (max-width: 768px) {
            .location-container {
                padding: 1rem !important;
            }
            .location-header {
                flex-direction: column !important;
                gap: 1rem !important;
                align-items: stretch !important;
            }
            .location-header h1 {
                font-size: 1.875rem !important;
                text-align: center !important;
            }
            .location-filters {
                flex-direction: column !important;
                gap: 0.5rem !important;
                width: 100% !important;
            }
            .location-search {
                width: 100% !important;
            }
            .location-filter-buttons {
                display: grid !important;
                grid-template-columns: repeat(3, 1fr) !important;
                gap: 0.5rem !important;
                width: 100% !important;
            }
            .location-filter-btn {
                padding: 0.5rem !important;
                font-size: 0.75rem !important;
                text-align: center !important;
                justify-content: center !important;
            }
            .buildings-grid {
                grid-template-columns: 1fr !important;
                gap: 1rem !important;
            }
        }
        @media (max-width: 480px) {
            .location-container {
                padding: 0.75rem !important;
            }
            .location-header h1 {
                font-size: 1.5rem !important;
            }
            .location-filter-buttons {
                grid-template-columns: 1fr !important;
                gap: 0.25rem !important;
            }
            .location-filter-btn {
                padding: 0.375rem !important;
                font-size: 0.625rem !important;
            }
        }

        /* Tablet specific styles */
        @media (min-width: 769px) and (max-width: 1024px) {
            .location-container {
                padding: 1.5rem !important;
            }
            .buildings-grid {
                grid-template-columns: repeat(2, 1fr) !important;
                gap: 1.5rem !important;
            }
            .location-header h1 {
                font-size: 2.5rem !important;
            }
            .location-filter-btn {
                padding: 0.5rem 1rem !important;
            }
        }

        /* Desktop specific styles */
        @media (min-width: 1025px) {
            .location-container {
                padding: 2rem !important;
            }
            .buildings-grid {
                grid-template-columns: repeat(3, 1fr) !important;
                gap: 2rem !important;
            }
        }

        /* Large desktop */
        @media (min-width: 1440px) {
            .location-container {
                padding: 2.5rem !important;
                max-width: 1400px !important;
                margin: 0 auto !important;
            }
            .buildings-grid {
                grid-template-columns: repeat(3, 1fr) !important;
                gap: 2.5rem !important;
            }
        }

        /* Modal streaming */
        .modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,.85); display: none; align-items: center; justify-content: center; z-index: 10000; }
        .modal-overlay.active { display: flex; }
        .modal { width: min(650px, 90vw); max-height: 80vh; background: #111; border-radius: 14px; overflow: hidden; box-shadow: 0 30px 80px rgba(0,0,0,.6); display: flex; flex-direction: column; margin: auto; }
        .modal-header { display: flex; align-items: center; justify-content: space-between; padding: 12px 14px; color: #fff; font-weight: bold; }
        .modal-actions { display: flex; gap: 8px; }
        .modal-btn { padding: 8px 12px; border-radius: 8px; border: none; color: #fff; background: #2563eb; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; }
        .modal-close { background: #ef4444; }
        .modal-body { padding: 10px; background: #000; }

        /* Perfect centering for all devices */
        @media (max-width: 768px) {
            .modal-overlay { align-items: center; justify-content: center; }
            .modal { width: min(95vw, 500px); max-height: 85vh; margin: auto; }
            .modal-header { flex-direction: column; gap: 10px; align-items: flex-start; }
            .modal-actions { width: 100%; justify-content: flex-end; }
        }
        @media (max-width: 480px) {
            .modal-overlay { align-items: center; justify-content: center; padding: 10px; }
            .modal { width: 95vw; max-height: 80vh; margin: auto; }
            .modal-header { padding: 10px; flex-direction: column; gap: 8px; }
            .modal-actions { flex-direction: column; gap: 5px; width: 100%; }
            .modal-btn { width: 100%; justify-content: center; }
        }

        /* Large screen */
        @media (min-width: 1025px) {
            .modal { width: min(700px, 80vw); max-height: 85vh; margin: auto; }
        }

        /* Extra large screens */
        @media (min-width: 1440px) {
            .modal { width: min(800px, 70vw); max-height: 85vh; margin: auto; }
        }
    </style>

    <script>
        let DATA = { buildings: [] };
        function el(html){ const t=document.createElement('template'); t.innerHTML=html.trim(); return t.content.firstChild; }

        async function fetchLocation(){
            try {
                const res = await fetch('/location-data', { headers: { 'Accept': 'application/json' } });
                DATA = await res.json();
                renderBuildings(DATA.buildings||[]);
                // Auto-expand based on URL params (?building=ID&room=ID)
                const params = new URLSearchParams(location.search);
                const bid = parseInt(params.get('building'));
                const rid = parseInt(params.get('room'));
                if (bid) {
                    renderRooms(bid);
                    if (rid) {
                        renderCctvs(bid, rid);
                        const target = document.getElementById(`cctvs-${bid}-${rid}`) || document.getElementById(`rooms-${bid}`);
                        if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                }
            } catch (e) {
                console.error('Failed to load location-data', e);
                document.getElementById('buildings').innerHTML = '<div class="text-zinc-600 dark:text-zinc-300 system:text-zinc-700">Gagal memuat data lokasi.</div>';
            }
        }

        function renderBuildings(items){
            const wrap = document.getElementById('buildings');
            wrap.innerHTML = '';
            if (!items.length) {
                wrap.innerHTML = '<div class="text-zinc-600 dark:text-zinc-300 system:text-zinc-700 col-span-full text-center py-8">Tidak ada data gedung.</div>';
                return;
            }
            items.forEach(b => {
                const card = el(`<div class="rounded-xl p-4 bg-white/5 border border-white/10 card-3d fade-in">
                    <div class="font-semibold text-zinc-800 dark:text-white system:text-zinc-900">${b.name}</div>
                    <div class="text-white/60 text-sm mt-1 text-zinc-600 dark:text-zinc-300 system:text-zinc-700">Room : ${b.rooms_count||0} | CCTV : ${b.cctvs_count||0}</div>
                    <div class="mt-3 flex gap-2">
                        <a href="/rooms?building=${b.id}" class="block px-4 py-3 rounded-lg bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white text-sm font-semibold tracking-wide text-center shadow-lg hover:shadow-blue-500/25 transition-all duration-300 border border-blue-400/50 w-full flex items-center justify-center">Room</a>
                        <a href="/maps?building=${b.id}" class="px-4 py-3 rounded-lg bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white text-sm font-semibold tracking-wide shadow-lg hover:shadow-green-500/25 transition-all duration-300 border border-green-400/50 flex items-center justify-center w-full">
                            Lihat di Maps
                        </a>
                    </div>
                    <div class="mt-3 hidden" id="rooms-${b.id}"></div>
                </div>`);
                wrap.appendChild(card);
            });
        }

        function renderRooms(buildingId){
            const building = (DATA.buildings||[]).find(x=>x.id===buildingId);
            const container = document.getElementById(`rooms-${buildingId}`);
            if (!building || !container) return;
            container.classList.remove('hidden');
            const rooms = building.rooms || [];
            if (!rooms.length) {
                container.innerHTML = '<div class="text-zinc-600 dark:text-zinc-300 system:text-zinc-700 py-2">Tidak ada room.</div>';
                return;
            }
            container.innerHTML = rooms.map(r => `
                <div class="mt-2 rounded-lg p-3 bg-black/30 border border-white/10 card-3d fade-in">
                    <div class="flex items-center justify-between">
                        <div class="font-medium text-zinc-800 dark:text-white system:text-zinc-900">${r.name}</div>
                        <button class="px-3 py-1 rounded-lg bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white text-xs font-medium shadow-lg hover:shadow-purple-500/25 transform hover:scale-105 transition-all duration-300 cursor-pointer border border-purple-400/50" data-open-cctvs="${buildingId}:${r.id}">
                            <i class="bx bxs-video mr-1"></i>CCTV
                        </button>
                    </div>
                    <div class="mt-2 hidden" id="cctvs-${buildingId}-${r.id}"></div>
                </div>
            `).join('');
        }

        function renderCctvs(buildingId, roomId){
            const building = (DATA.buildings||[]).find(x=>x.id===buildingId);
            const room = (building?.rooms||[]).find(x=>x.id===roomId);
            const box = document.getElementById(`cctvs-${buildingId}-${roomId}`);
            if (!room || !box) return;
            box.classList.remove('hidden');
            const cctvs = room.cctvs || [];
            if (!cctvs.length){ box.innerHTML = `<div class='text-white/60 text-sm text-zinc-600 dark:text-zinc-300 system:text-zinc-700'>Tidak ada CCTV</div>`; return; }
            const active = getActiveStatuses();
            box.innerHTML = cctvs.filter(c => active.has(c.status)).map(c => `
                <div class="mt-2 flex items-center justify-between rounded border border-white/10 bg-white/5 p-2 card-3d fade-in">
                    <div class="text-sm text-zinc-800 dark:text-white system:text-zinc-900">${c.name||'CCTV'}</div>
                    <div class="flex items-center gap-2">
                        <button data-live="${c.id}" class="px-3 py-1 rounded-lg bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white text-xs font-medium shadow-lg hover:shadow-red-500/25 transform hover:scale-105 transition-all duration-300 cursor-pointer border border-red-400/50">
                            <i class="bx bxs-play mr-1"></i>Live
                        </button>
                    </div>
                </div>
            `).join('');
        }

        function getActiveStatuses(){
            const set = new Set();
            if (document.getElementById('f-online')?.classList.contains('active')) set.add('online');
            if (document.getElementById('f-offline')?.classList.contains('active')) set.add('offline');
            if (document.getElementById('f-maint')?.classList.contains('active')) set.add('maintenance');
            if (set.size === 0) return new Set(['online','offline','maintenance']);
            return set;
        }

        document.addEventListener('click', async (e) => {
            const live = e.target.closest('[data-live]');
            if (live){
                const id = parseInt(live.getAttribute('data-live'));
                // Open modal and load stream
                openLiveStreamModal(id);
                return;
            }
            const openRooms = e.target.closest('[data-open-rooms]');
            if (openRooms){
                renderRooms(parseInt(openRooms.getAttribute('data-open-rooms')));
                return;
            }
            const openCctvs = e.target.closest('[data-open-cctvs]');
            if (openCctvs){
                const [bid, rid] = openCctvs.getAttribute('data-open-cctvs').split(':').map(x=>parseInt(x));
                renderCctvs(bid, rid);
                return;
            }
            const sug = e.target.closest('[data-sel-building]');
            if (sug){
                const bid = parseInt(sug.getAttribute('data-sel-building'));
                document.getElementById('q-suggest')?.classList.add('hidden');
                renderBuildings(DATA.buildings||[]);
                renderRooms(bid);
                const target = document.getElementById(`rooms-${bid}`);
                if (target) target.scrollIntoView({ behavior:'smooth', block:'start' });
            }
        });

        const q = document.getElementById('q');
        const box = document.getElementById('q-suggest');

        if (q && box) {
            q.addEventListener('input', (e)=>{
                const term = e.target.value.toLowerCase();
                if (!term){ box.classList.add('hidden'); box.innerHTML=''; renderBuildings(DATA.buildings||[]); return; }
                // live filter grid
                const filtered = (DATA.buildings||[]).filter(b => (b.name||'').toLowerCase().includes(term));
                renderBuildings(filtered);
                // suggestions list (top 8)
                const top = filtered.slice(0,8);
                if (!top.length){ box.classList.add('hidden'); box.innerHTML=''; return; }
                box.innerHTML = top.map(b => `
                    <div class="flex items-center gap-2 px-3 py-2 hover:bg-white/10 cursor-pointer text-zinc-800 dark:text-white system:text-zinc-900" data-sel-building="${b.id}">
                        <i class="bx bxs-building-house text-blue-400"></i>
                        <span class="text-sm">${b.name}</span>
                    </div>
                `).join('');
                box.classList.remove('hidden');
            });

            document.addEventListener('click', (e)=>{
                if (!e.target.closest('#q') && !e.target.closest('#q-suggest')){
                    box.classList.add('hidden');
                }
            });
        } else {
            console.error('Search input or suggestion box not found');
        }

        // Live Stream Modal Functions
        function openLiveStreamModal(cctvId) {
            const modal = document.getElementById('stream-modal');
            const modalTitle = document.getElementById('modal-title');
            const streamContainer = document.getElementById('stream-container');

            // Find the CCTV name
            let cctvName = 'CCTV Live Stream';
            outerLoop: for (const building of DATA.buildings || []) {
                for (const room of building.rooms || []) {
                    for (const cctv of room.cctvs || []) {
                        if (cctv.id === cctvId) {
                            cctvName = cctv.name || `CCTV ${cctvId}`;
                            break outerLoop;
                        }
                    }
                }
            }

            // Set modal title with bold text
            modalTitle.innerHTML = `<i class='bx bxs-videos'></i> Live Stream - ${cctvName}`;
            modalTitle.style.fontWeight = 'bold';

            // Show loading state with responsive height
            streamContainer.innerHTML = `
                <div style="width:100%;height:70vh;background:transparent;display:flex;align-items:center;justify-content:center;color:#fff;text-align:center;">
                    <div>
                        <i class='bx bx-loader-alt bx-spin' style='font-size:2rem'></i>
                        <div style="margin-top:10px;">Loading stream...</div>
                    </div>
                </div>
            `;

            // Show modal
            modal.classList.add('active');

            // Start the stream
            startCctvStream(cctvId);
        }

        async function startCctvStream(cctvId) {
            const streamContainer = document.getElementById('stream-container');

            try {
                const res = await fetch(`/stream/${cctvId}/start`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });
                const json = await res.json();

                if (json.hls) {
                    streamContainer.innerHTML = `
                        <video id="cctv-video" src="${json.hls}" autoplay style="width:100%;height:70vh;object-fit:contain;background:transparent" playsinline></video>
                    `;

                    // Re-attach event listeners after video is loaded
                    setTimeout(() => {
                        const fullscreenBtn = document.getElementById('btn-fullscreen');
                        if (fullscreenBtn) {
                            fullscreenBtn.addEventListener('click', toggleFullscreen);
                        }
                    }, 100);
                } else {
                    streamContainer.innerHTML = `
                        <div style="width:100%;height:70vh;background:transparent;display:flex;align-items:center;justify-content:center;color:#fff;text-align:center;">
                            <div>
                                <i class='bx bx-error' style='font-size:2rem'></i>
                                <div style="margin-top:10px;">Failed to load stream</div>
                            </div>
                        </div>
                    `;
                }
            } catch (error) {
                streamContainer.innerHTML = `
                    <div style="width:100%;height:70vh;background:transparent;display:flex;align-items:center;justify-content:center;color:#fff;text-align:center;">
                        <div>
                            <i class='bx bx-error' style='font-size:2rem'></i>
                            <div style="margin-top:10px;">Error loading stream</div>
                        </div>
                    </div>
                `;
            }
        }

        // Fullscreen functionality - simpler approach
        function toggleFullscreen() {
            const video = document.getElementById('cctv-video');
            if (!video) {
                alert('Video stream not available');
                return;
            }

            // Try different fullscreen methods
            const methods = [
                'requestFullscreen',
                'webkitRequestFullscreen',
                'mozRequestFullScreen',
                'msRequestFullscreen'
            ];

            for (let method of methods) {
                if (video[method]) {
                    video[method]();
                    return;
                }
            }

            // Fallback if fullscreen not supported
            alert('Fullscreen mode is not supported in your browser');
        }

        // Handle modal events after DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Close button event
            const closeBtn = document.getElementById('btn-close');
            if (closeBtn) {
                closeBtn.addEventListener('click', function() {
                    document.getElementById('stream-modal').classList.remove('active');
                });
            }

            // Fullscreen button event
            const fullscreenBtn = document.getElementById('btn-fullscreen');
            if (fullscreenBtn) {
                fullscreenBtn.addEventListener('click', toggleFullscreen);
            }

            // Close modal when clicking outside
            const modal = document.getElementById('stream-modal');
            if (modal) {
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        modal.classList.remove('active');
                    }
                });
            }
        });

        // realtime refresh events
        window.addEventListener('cctv-status', fetchLocation);
        window.addEventListener('maps-data-changed', fetchLocation);

        document.addEventListener('DOMContentLoaded', fetchLocation);
        // Also run immediately in case the event didn't fire
        fetchLocation();
    </script>

    <!-- Streaming Modal -->
    <div id="stream-modal" class="modal-overlay" role="dialog" aria-modal="true">
        <div class="modal">
            <div class="modal-header" style="background: transparent; color: #fff; font-weight: bold;">
                <div id="modal-title"><i class='bx bxs-videos'></i> Live Stream</div>
                <div class="modal-actions">
                    <button id="btn-fullscreen" class="modal-btn"><i class='bx bx-fullscreen'></i> Fullscreen</button>
                    <button id="btn-close" class="modal-btn modal-close"><i class='bx bxs-x-circle'></i> Close</button>
                </div>
            </div>
            <div class="modal-body">
                <div id="stream-container" style="width:100%;height:70vh;background:transparent;display:flex;align-items:center;justify-content:center;">
                    <div style="color:#fff;text-align:center;">
                        <i class='bx bx-loader-alt bx-spin' style='font-size:2rem'></i>
                        <div style="margin-top:10px;">Loading stream...</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-layouts.app>
