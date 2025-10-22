<x-layouts.app :title="__('Location')">
    <!-- Remove the fixed height container that conflicts with main layout -->
    <div class="w-full">
        <div class="max-w-screen-xl mx-auto px-6 py-6">
            <div class="flex items-center justify-between gap-4">
            <h1 class="text-3xl md:text-4xl font-extrabold text-zinc-800 dark:text-white system:text-zinc-900">Location</h1>
                <div class="flex items-center gap-2 relative">
                    <div class="relative">
                        <input id="q" class="px-3 py-2 rounded-lg bg-white/5 border-2 border-gray-300 dark:border-white/10 system:border-zinc-400 pr-10 text-gray-900 dark:text-white system:text-zinc-900 placeholder-gray-500 dark:placeholder-white/50 system:placeholder-zinc-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Cari gedung..." />
                        <i class="bx bx-search absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 dark:text-white/70 system:text-zinc-700"></i>
                        <div id="q-suggest" class="hidden absolute right-0 mt-2 w-72 bg-black/70 border border-white/10 system:border-zinc-300 rounded-xl backdrop-blur-md shadow-2xl overflow-hidden z-10"></div>
                    </div>
                    <div id="f-online" class="px-4 py-2 rounded-full text-sm font-medium bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white shadow-lg hover:shadow-green-500/25 transform hover:scale-105 transition-all duration-300 cursor-pointer border border-green-400/50">
                        <i class="bx bxs-video mr-1"></i>Online
                    </div>
                    <div id="f-offline" class="px-4 py-2 rounded-full text-sm font-medium bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white shadow-lg hover:shadow-red-500/25 transform hover:scale-105 transition-all duration-300 cursor-pointer border border-red-400/50">
                        <i class="bx bxs-error-circle mr-1"></i>Offline
                    </div>
                    <div id="f-maint" class="px-4 py-2 rounded-full text-sm font-medium bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-white shadow-lg hover:shadow-yellow-500/25 transform hover:scale-105 transition-all duration-300 cursor-pointer border border-yellow-400/50">
                        <i class="bx bxs-wrench mr-1"></i>Maintenance
                    </div>
                </div>
            </div>
            <div id="buildings" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-6"></div>
        </div>
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
            }
            .location-search {
                width: 100% !important;
            }
            .location-filter-buttons {
                display: grid !important;
                grid-template-columns: repeat(3, 1fr) !important;
                gap: 0.5rem !important;
            }
            .location-filter-btn {
                padding: 0.5rem !important;
                font-size: 0.75rem !important;
                text-align: center !important;
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
                    <div class="text-white/60 text-sm mt-1 text-zinc-600 dark:text-zinc-300 system:text-zinc-700">Rooms: ${b.rooms_count||0} | CCTVs: ${b.cctvs_count||0}</div>
                    <div class="mt-3 flex gap-2">
                        <a href="/rooms?building=${b.id}" class="block px-4 py-3 rounded-lg bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white text-sm font-semibold tracking-wide text-center shadow-lg hover:shadow-blue-500/25 transition-all duration-300 border border-blue-400/50 w-full">Rooms</a>
                        <a href="/maps" class="px-4 py-2 rounded-lg bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white text-sm font-medium shadow-lg hover:shadow-green-500/25 transform hover:scale-105 transition-all duration-300 cursor-pointer border border-green-400/50">
                            <i class="bx bxs-map mr-1"></i>Lihat di Maps
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
                        <button class="px-3 py-1 rounded-lg bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white text-xs font-medium shadow-lg hover:shadow-red-500/25 transform hover:scale-105 transition-all duration-300 cursor-pointer border border-red-400/50" data-live="${c.id}">
                            <i class="bx bxs-play mr-1"></i>Live
                        </button>
                        <button class="px-3 py-1 rounded-lg bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white text-xs font-medium shadow-lg hover:shadow-emerald-500/25 transform hover:scale-105 transition-all duration-300 cursor-pointer border border-emerald-400/50" data-shot="${c.id}">
                            <i class="bx bxs-camera mr-1"></i>Screenshot
                        </button>
                        <button class="px-3 py-1 rounded-lg bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-white text-xs font-medium shadow-lg hover:shadow-yellow-500/25 transform hover:scale-105 transition-all duration-300 cursor-pointer border border-yellow-400/50" data-record="${c.id}">
                            <i class="bx bxs-video-recording mr-1"></i>Record 30s
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
                const res = await fetch(`/stream/${id}/start`, { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }});
                const json = await res.json();
                if (json.hls){
                    const w = window.open('', '_blank');
                    w.document.write(`<video src='${json.hls}' controls autoplay style='width:100%;height:100dvh;background:#000'></video>`);
                }
                return;
            }
            const shot = e.target.closest('[data-shot]');
            if (shot){
                const id = parseInt(shot.getAttribute('data-shot'));
                shot.disabled = true;
                const res = await fetch(`/stream/${id}/snapshot`, { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }});
                shot.disabled = false;
                const json = await res.json();
                if (json.image){
                    const w = window.open(json.image, '_blank');
                    if (!w) alert('Popup blocked. Open: '+json.image);
                }
                return;
            }
            const rec = e.target.closest('[data-record]');
            if (rec){
                const id = parseInt(rec.getAttribute('data-record'));
                rec.disabled = true;
                const res = await fetch(`/stream/${id}/record`, { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }, body: new URLSearchParams({ seconds: '30' }) });
                rec.disabled = false;
                const json = await res.json();
                if (json.video){
                    const w = window.open(json.video, '_blank');
                    if (!w) alert('Popup blocked. Open: '+json.video);
                }
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

        // Toggle status filters
        function toggleStatus(el){ el.classList.toggle('active'); }
        ['f-online','f-offline','f-maint'].forEach(id => {
            const el = document.getElementById(id);
            if (el){
                el.classList.add('active');
                el.addEventListener('click', ()=>{
                    toggleStatus(el);
                    // Rerender opened room boxes respecting filters
                    document.querySelectorAll('[id^="cctvs-" ]').forEach(node => {
                        const parts = node.id.split('-');
                        const bid = parseInt(parts[1]);
                        const rid = parseInt(parts[2]);
                        renderCctvs(bid, rid);
                    });
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
</x-layouts.app>
