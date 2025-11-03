<?php

namespace App\Http\Controllers;

use App\Models\Building;
use App\Models\Cctv;
use App\Models\Contact;
use App\Models\Room;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function buildings()
    {
        return Excel::download(new class implements FromCollection, WithHeadings
        {
            public function collection()
            {
                return Building::select('id', 'name', 'latitude', 'longitude')->get();
            }

            public function headings(): array
            {
                return ['ID', 'Name', 'Latitude', 'Longitude'];
            }
        }, 'buildings.xlsx');
    }

    public function rooms()
    {
        return Excel::download(new class implements FromCollection, WithHeadings
        {
            public function collection()
            {
                return Room::with('building')->get()->map(function ($room) {
                    return [
                        'ID' => $room->id,
                        'Building' => $room->building->name ?? '',
                        'Name' => $room->name,
                        'Latitude' => $room->latitude,
                        'Longitude' => $room->longitude,
                    ];
                });
            }

            public function headings(): array
            {
                return ['ID', 'Building', 'Name', 'Latitude', 'Longitude'];
            }
        }, 'rooms.xlsx');
    }

    public function cctvs()
    {
        return Excel::download(new class implements FromCollection, WithHeadings
        {
            public function collection()
            {
                return Cctv::with(['building', 'room'])->get()->map(function ($cctv) {
                    return [
                        'ID' => $cctv->id,
                        'Building' => $cctv->building->name ?? '',
                        'Room' => $cctv->room->name ?? '',
                        'Name' => $cctv->name,
                        'RTSP' => $cctv->ip_rtsp,
                        'Status' => $cctv->status,
                    ];
                });
            }

            public function headings(): array
            {
                return ['ID', 'Building', 'Room', 'Name', 'RTSP', 'Status'];
            }
        }, 'cctvs.xlsx');
    }

    public function users()
    {
        return Excel::download(new class implements FromCollection, WithHeadings
        {
            public function collection()
            {
                return User::select('id', 'name', 'email', 'email_verified_at')->get();
            }

            public function headings(): array
            {
                return ['ID', 'Name', 'Email', 'Verified At'];
            }
        }, 'users.xlsx');
    }

    public function contacts()
    {
        return Excel::download(new class implements FromCollection, WithHeadings
        {
            public function collection()
            {
                return Contact::select('id', 'name', 'email', 'whatsapp', 'address', 'instagram')->get();
            }

            public function headings(): array
            {
                return ['ID', 'Name', 'Email', 'WhatsApp', 'Address', 'Instagram'];
            }
        }, 'contacts.xlsx');
    }

    public function stats()
    {
        $data = [
            ['Metric', 'Value'],
            ['Total Buildings', Building::count()],
            ['Total Rooms', Room::count()],
            ['CCTV Online', Cctv::where('status', 'online')->count()],
            ['CCTV Offline', Cctv::where('status', 'offline')->count()],
            ['CCTV Maintenance', Cctv::where('status', 'maintenance')->count()],
            ['Total Users', User::count()],
        ];

        return Excel::download(new class($data) implements FromCollection
        {
            public function __construct(private array $rows) {}

            public function collection()
            {
                return collect($this->rows);
            }
        }, 'stats.xlsx');
    }
}
