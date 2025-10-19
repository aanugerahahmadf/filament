# Dokumentasi Fitur MAPS - Sistem CCTV Kilang Pertamina Balongan

## Deskripsi Fitur
Halaman Maps adalah interface interaktif untuk memantau semua CCTV di area Kilang Pertamina Internasional - Refinery Unit VI Balongan secara real-time menggunakan peta.

## Lokasi File
- **View**: `resources/views/maps.blade.php`
- **Controller**: `app/Http/Controllers/MapController.php`
- **Route**: `/maps` (authenticated)
- **API Endpoint**: `/map-data` (authenticated)
- **Test**: `tests/Feature/MapsTest.php`

## Fitur Utama

### 1. Dual Layer Maps
- **OpenStreetMap**: Peta standar dengan detail jalan dan lokasi
- **Satellite View**: Tampilan satelit untuk visualisasi area lebih detail
- **Toggle Button**: Switch antara kedua layer dengan mudah

### 2. Search Box
- Terletak di kanan atas
- Pencarian real-time nama gedung
- Dropdown hasil pencarian dengan auto-complete
- Klik hasil pencarian langsung zoom ke gedung tersebut

### 3. Status Filter (Bulat Warna)
Tiga tombol filter bulat untuk memfilter CCTV berdasarkan status:

- 🟢 **Hijau** = CCTV Online
- 🔴 **Merah** = CCTV Offline  
- 🟡 **Kuning** = CCTV Maintenance

**Cara Kerja Filter:**
- Klik satu bulat warna → Tampilkan hanya CCTV dengan status tersebut
- Klik beberapa bulat → Tampilkan semua status yang dipilih
- Semua aktif → Tampilkan semua CCTV

### 4. Building Markers (Marker Gedung)
- Marker utama untuk setiap gedung
- Icon custom jika Super Admin upload via Filament
- Default: Angka 1-18 dalam lingkaran biru
- Klik marker gedung → Zoom in dan tampilkan room markers

### 5. Room Markers (Marker Ruangan)
- Muncul setelah zoom ke gedung
- Warna marker sesuai status CCTV di room:
  - Hijau: Ada CCTV online
  - Merah: Ada CCTV offline
  - Kuning: Ada CCTV maintenance
- Icon custom jika Super Admin upload via Filament
- Popup saat diklik menampilkan:
  - Nama ruangan
  - Daftar semua CCTV di ruangan
  - Status setiap CCTV
  - Button "Live CCTV" untuk setiap kamera

### 6. Live Streaming Modal
Ketika klik tombol "Live CCTV":
- Modal overlay muncul dengan video player
- Control buttons:
  - **Start Stream**: Mulai streaming HLS
  - **Stop Stream**: Hentikan streaming
  - **Screenshot**: Ambil tangkapan layar
  - **Record**: Rekam video (dengan input durasi)
- Real-time streaming menggunakan HLS.js
- Support untuk browser modern dan Safari (native HLS)

## Koordinat Lokasi
Peta dipusatkan pada lokasi aktual:
- **Latitude**: -6.3326
- **Longitude**: 108.4582
- **Lokasi**: PT Kilang Pertamina Internasional - Refinery Unit VI Balongan

## Teknologi yang Digunakan

### Frontend
- **Leaflet.js v1.9.4**: Library peta interaktif
- **HLS.js**: Streaming video HLS
- **TailwindCSS v4**: Styling modern 3D
- **Boxicons**: Icon library

### Backend
- **Laravel 12**: Framework backend
- **MapController**: Handle data endpoint
- **StreamController**: Handle streaming operations
- **FFmpeg**: Convert RTSP to HLS

## API Endpoint

### GET /map-data
Response format:
```json
{
  "buildings": [
    {
      "id": 1,
      "name": "Gedung Kolaboratif",
      "latitude": -6.3326,
      "longitude": 108.4582,
      "marker_icon_path": "/storage/markers/building1.png",
      "rooms": [
        {
          "id": 1,
          "name": "Control Room",
          "latitude": -6.3327,
          "longitude": 108.4583,
          "marker_icon_path": "/storage/markers/room1.png",
          "cctvs": [
            {
              "id": 1,
              "name": "CCTV-CR-001",
              "status": "online",
              "latitude": -6.3327,
              "longitude": 108.4583
            }
          ]
        }
      ]
    }
  ]
}
```

## Workflow Penggunaan

### User Interface
1. User login dan akses halaman `/maps`
2. Peta menampilkan 18 marker gedung di area Kilang Pertamina
3. User dapat:
   - Search gedung via search box
   - Toggle antara OpenStreetMap dan Satellite
   - Filter CCTV by status (klik bulat warna)
4. Klik marker gedung → Zoom in
5. Tampil marker rooms di gedung tersebut
6. Klik marker room → Popup dengan daftar CCTV
7. Klik "Live CCTV" → Modal streaming terbuka
8. Control streaming dengan buttons

### Super Admin (via Filament Panel)
1. Login ke `/admin`
2. CRUD Buildings:
   - Upload custom marker icon
   - Set koordinat latitude/longitude
3. CRUD Rooms:
   - Upload custom marker icon
   - Set koordinat (near building)
4. CRUD CCTVs:
   - Set status (online/offline/maintenance)
   - Configure RTSP URL
5. Perubahan langsung ter-reflect di Maps User Interface

## 18 Gedung yang Harus Ada
1. Gedung Kolaboratif
2. Gerbang Utama
3. AWI
4. Shelter Maintenance Area 1
5. Shelter Maintenance Area 2
6. Shelter Maintenance Area 3
7. Shelter Maintenance Area 4
8. Shelter White OM
9. Pintu Masuk Area Kilang
10. Marine Region III
11. Main Control Room
12. Tank Farm Area 1
13. Gedung EXOR
14. Produksi CDU
15. HSSE Demo Room
16. Gedung Amanah
17. POC
18. JGC

## Testing
File: `tests/Feature/MapsTest.php`

**Test Cases (Semua Pass):**
- ✅ Maps page dapat diakses authenticated users
- ✅ Maps page memerlukan authentication
- ✅ Map data endpoint return buildings dengan rooms dan CCTVs
- ✅ Map menampilkan status markers yang berbeda
- ✅ Building search berfungsi correct
- ✅ Room markers menampilkan jumlah CCTVs yang benar
- ✅ Maps page contain semua UI elements
- ✅ Custom marker icons digunakan jika tersedia
- ✅ Room tanpa CCTV ditampilkan dengan empty state

**Run Tests:**
```bash
php artisan test --filter=MapsTest
```

## Responsive Design
- **Desktop**: Full layout dengan sidebar dan controls
- **Tablet**: Stacked layout, controls di atas
- **Mobile**: Optimized untuk layar kecil, touch-friendly

## Security
- Authentication required (middleware auth)
- CSRF protection pada semua POST requests
- Input validation pada streaming controls

## Performance Optimization
- Marker clustering untuk banyak CCTVs
- Lazy load room markers (hanya saat zoom)
- HLS streaming dengan low latency mode
- Efficient JSON structure dari API

## Troubleshooting

### Marker tidak muncul
- Pastikan database memiliki building dengan koordinat
- Check console browser untuk errors
- Verifikasi endpoint `/map-data` return valid JSON

### Streaming tidak jalan
- Pastikan FFmpeg terinstall
- Check RTSP URL CCTV valid
- Verifikasi HLS path di `public/live/`
- Check browser support HLS

### Search tidak work
- Pastikan building memiliki nama
- Check JavaScript console untuk errors
- Verifikasi event listener attached

## Future Enhancements
- Real-time status update via WebSocket/Laravel Echo
- Heatmap view berdasarkan density CCTV
- History playback untuk recorded streams
- Multi-language support
- Export map view as PDF/Image
- Notification alert untuk CCTV offline

## Credits
Developed for PT Kilang Pertamina Internasional - Refinery Unit VI Balongan

