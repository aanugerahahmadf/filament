<x-layouts.app :title="__('Rooms')">
    <div class="w-full">
        <div class="max-w-screen-xl mx-auto px-6 py-6">
            <div class="flex items-center justify-between gap-4">
                <h1 class="text-3xl md:text-4xl font-extrabold text-zinc-800 dark:text-white">Room</h1>
                <div class="flex items-center gap-2">
                    <a href="/locations" class="btn btn-primary glow">Kembali ke Locations</a>
                </div>
            </div>

            <div id="info" class="text-zinc-600 dark:text-zinc-300 mt-2"></div>
            <div id="rooms" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-6"></div>
        </div>
    </div>

    <script>
        let DATA = { buildings: [] };
        let buildingId = null;

        function el(html){ const t=document.createElement('template'); t.innerHTML=html.trim(); return t.content.firstChild; }

        async function load(){
            const params = new URLSearchParams(location.search);
            buildingId = parseInt(params.get('building')) || null;
            if (!buildingId){ document.getElementById('info').textContent = 'Building tidak ditentukan.'; return; }
            const res = await fetch('/location-data', { headers: { 'Accept': 'application/json' } });
            DATA = await res.json();
            const building = (DATA.buildings||[]).find(b => b.id === buildingId);
            if (!building){ document.getElementById('info').textContent = 'Building tidak ditemukan.'; return; }
            document.getElementById('info').innerHTML = `Building: <span class="font-semibold">${building.name}</span>`;
            renderRooms(building);
        }

        function renderRooms(building){
            const wrap = document.getElementById('rooms');
            wrap.innerHTML = '';
            (building.rooms||[]).forEach(r => {
                const card = el(`<div class="rounded-xl p-4 bg-white/5 border border-white/10 card-3d">
                    <div class="font-semibold text-zinc-800 dark:text-white">${r.name}</div>
                    <div class="text-zinc-600 dark:text-zinc-300 text-sm mt-1">CCTVs: ${(r.cctvs||[]).length}</div>
                    <div class="mt-3">
                        <a href="/cctv?building=${building.id}&room=${r.id}" class="block w-full px-4 py-3 rounded-lg bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white text-sm font-semibold tracking-wide text-center shadow-lg hover:shadow-purple-500/25 transition-all duration-300 border border-purple-400/50">Buka CCTV</a>
                    </div>
                </div>`);
                wrap.appendChild(card);
            });
            if (!wrap.children.length){ wrap.innerHTML = '<div class="text-zinc-600 dark:text-zinc-300">Belum ada Room.</div>'; }
        }

        load();
    </script>
</x-layouts.app>
