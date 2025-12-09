<x-layouts.app :title="__('CCTV')">
    <div class="w-full">
        <div class="max-w-screen-xl mx-auto px-6 py-6">
            <div class="flex items-center justify-between gap-4">
                <h1 class="text-3xl md:text-4xl font-extrabold text-zinc-800 dark:text-white system:text-zinc-900">Playlist CCTV</h1>
                <div class="flex items-center gap-2">
                    <a id="back-rooms" href="#" class="btn btn-primary glow">Kembali ke Rooms</a>
                </div>
            </div>

            <div class="flex items-center justify-between gap-4 mt-4">
                <div class="relative w-full max-w-md">
                    <input id="q" class="w-full sm:w-64 md:w-72 px-3 py-2 rounded-lg bg-white/5 border-2 border-gray-300 dark:border-white/10 system:border-zinc-400 pr-10 text-gray-900 dark:text-white system:text-zinc-900 placeholder-gray-500 dark:placeholder-white/50 system:placeholder-zinc-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Search for Cctv..." />
                    <i class="bx bx-search absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 dark:text-white/70 system:text-zinc-700"></i>
                </div>
            </div>

            <div id="info" class="text-zinc-600 dark:text-zinc-300 system:text-zinc-700 mt-2"></div>
            <div id="cctvs" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-6"></div>
        </div>
    </div>

    <!-- Mobile CCTV List Responsive -->
    <style>
        @media (max-width: 768px) {
            .cctv-container {
                padding: 1rem !important;
            }
            .cctv-header {
                flex-direction: column !important;
                gap: 1rem !important;
                align-items: stretch !important;
            }
            .cctv-header h1 {
                font-size: 1.875rem !important;
                text-align: center !important;
            }
            .cctv-header .btn {
                width: 100% !important;
                text-align: center !important;
            }
            .cctv-grid {
                grid-template-columns: 1fr !important;
                gap: 1rem !important;
            }
            .cctv-card {
                padding: 1rem !important;
                border-radius: 0.75rem !important;
            }
            .cctv-card .font-semibold {
                font-size: 1rem !important;
            }
            .cctv-card .text-sm {
                font-size: 0.75rem !important;
            }
            .cctv-card .btn {
                padding: 0.75rem !important;
                font-size: 0.75rem !important;
            }
        }
        @media (max-width: 480px) {
            .cctv-container {
                padding: 0.75rem !important;
            }
            .cctv-header h1 {
                font-size: 1.5rem !important;
            }
            .cctv-grid {
                gap: 0.75rem !important;
            }
            .cctv-card {
                padding: 0.75rem !important;
                border-radius: 0.5rem !important;
            }
            .cctv-card .font-semibold {
                font-size: 0.875rem !important;
            }
            .cctv-card .text-sm {
                font-size: 0.625rem !important;
            }
            .cctv-card .btn {
                padding: 0.5rem !important;
                font-size: 0.625rem !important;
            }
        }
    </style>

    <script>
        let DATA = { buildings: [] };
        let buildingId = null; let roomId = null;

        function el(html){ const t=document.createElement('template'); t.innerHTML=html.trim(); return t.content.firstChild; }

        async function load(){
            const params = new URLSearchParams(location.search);
            buildingId = parseInt(params.get('building')) || null;
            roomId = parseInt(params.get('room')) || null;
            const back = document.getElementById('back-rooms');
            if (back && buildingId) back.href = '/rooms?building=' + buildingId;
            if (!buildingId || !roomId){ document.getElementById('info').innerHTML = '<span class="text-zinc-600 dark:text-zinc-300 system:text-zinc-700">Building/Room tidak ditentukan.</span>'; return; }
            const res = await fetch('/location-data', { headers: { 'Accept': 'application/json' } });
            DATA = await res.json();
            const building = (DATA.buildings||[]).find(b => b.id === buildingId);
            const room = building?.rooms?.find(r => r.id === roomId);
            if (!building || !room){ document.getElementById('info').innerHTML = '<span class="text-zinc-600 dark:text-zinc-300 system:text-zinc-700">Data tidak ditemukan.</span>'; return; }
            document.getElementById('info').innerHTML = 'Building: <span class="font-semibold text-zinc-800 dark:text-white system:text-zinc-900">' + building.name + '</span> · Room: <span class="font-semibold text-zinc-800 dark:text-white system:text-zinc-900">' + room.name + '</span>';
            renderCctvs(room);
        }

        function renderCctvs(room){
            const wrap = document.getElementById('cctvs');
            wrap.innerHTML = '';

            // Get search term if exists
            const searchTerm = document.getElementById('q')?.value.toLowerCase() || '';

            // Filter CCTVs based on search term
            const cctvsToDisplay = searchTerm
                ? (room.cctvs || []).filter(c => (c.name || 'CCTV').toLowerCase().includes(searchTerm))
                : room.cctvs || [];

            cctvsToDisplay.forEach(c => {
                const card = el(`<div class="rounded-xl p-4 bg-white/5 border border-white/10 card-3d">
                    <div class="font-semibold text-zinc-800 dark:text-white system:text-zinc-900">${c.name||'CCTV'}</div>
                    <div class="text-zinc-600 dark:text-zinc-300 system:text-zinc-700 text-sm mt-1">Status: ${c.status||'-'}</div>
                    <div class="mt-3 grid grid-cols-1 gap-2">
                        <button class="w-full px-4 py-3 rounded-lg bg-gradient-to-r from-red-500 to-red-600 text-white text-sm font-semibold tracking-wide border border-red-400/50" data-live="${c.id}">Live Stream</button>
                    </div>
                </div>`);
                wrap.appendChild(card);
            });
            if (!wrap.children.length){ wrap.innerHTML = '<div class="text-zinc-600 dark:text-zinc-300 system:text-zinc-700">Belum ada CCTV.</div>'; }
        }

        // Add search functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('q');
            if (searchInput) {
                searchInput.addEventListener('input', function(e) {
                    const params = new URLSearchParams(location.search);
                    buildingId = parseInt(params.get('building')) || null;
                    roomId = parseInt(params.get('room')) || null;
                    if (buildingId && roomId) {
                        const building = (DATA.buildings||[]).find(b => b.id === buildingId);
                        const room = building?.rooms?.find(r => r.id === roomId);
                        if (room) {
                            renderCctvs(room);
                        }
                    }
                });
            }
        });

        document.addEventListener('click', async (e)=>{
            const live = e.target.closest('[data-live]');
            if (live){
                e.preventDefault();
                const id = parseInt(live.getAttribute('data-live'));

                // Redirect to the new dedicated stream page using named route
                window.location.href = `/cctv/stream/${id}`;

                return false;
            }
        });

        load();
    </script>
</x-layouts.app>
