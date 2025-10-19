<?php

namespace App\Services;

use App\Models\Building;
use App\Models\Cctv;
use App\Models\Contact;
use App\Models\Room;
use App\Models\User;
use App\Repositories\CctvRepository;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Excel;

class ExportService
{
    protected CctvRepository $cctvRepository;

    public function __construct(CctvRepository $cctvRepository)
    {
        $this->cctvRepository = $cctvRepository;
    }

    public function exportBuildings(string $format = Excel::CSV)
    {
        $buildings = Building::withCount(['rooms', 'cctvs'])->get();

        $data = $buildings->map(function ($building) {
            return [
                'ID' => $building->id,
                'Name' => $building->name,
                'Address' => $building->address,
                'Description' => $building->description,
                'Contact Person' => $building->contact_person,
                'Phone' => $building->phone,
                'Email' => $building->email,
                'Rooms Count' => $building->rooms_count,
                'CCTVs Count' => $building->cctvs_count,
                'Latitude' => $building->latitude,
                'Longitude' => $building->longitude,
                'Created At' => $building->created_at,
                'Updated At' => $building->updated_at,
            ];
        });

        return $this->exportData($data->toArray(), 'buildings', $format);
    }

    public function exportRooms(string $format = Excel::CSV)
    {
        $rooms = Room::with(['building'])->get();

        $data = $rooms->map(function ($room) {
            return [
                'ID' => $room->id,
                'Building' => $room->building->name ?? null,
                'Name' => $room->name,
                'Description' => $room->description,
                'Floor' => $room->floor,
                'Capacity' => $room->capacity,
                'Latitude' => $room->latitude,
                'Longitude' => $room->longitude,
                'Created At' => $room->created_at,
                'Updated At' => $room->updated_at,
            ];
        });

        return $this->exportData($data->toArray(), 'rooms', $format);
    }

    public function exportCctvs(string $format = Excel::CSV)
    {
        $cctvs = Cctv::with(['building', 'room'])->get();

        $data = $cctvs->map(function ($cctv) {
            return [
                'ID' => $cctv->id,
                'Building' => $cctv->building->name ?? null,
                'Room' => $cctv->room->name ?? null,
                'Name' => $cctv->name,
                'Model' => $cctv->model,
                'Serial Number' => $cctv->serial_number,
                'Firmware Version' => $cctv->firmware_version,
                'Description' => $cctv->description,
                'IP/RTSP' => $cctv->ip_rtsp,
                'Port' => $cctv->port,
                'Resolution' => $cctv->resolution,
                'FPS' => $cctv->fps,
                'Status' => $cctv->status,
                'Latitude' => $cctv->latitude,
                'Longitude' => $cctv->longitude,
                'Last Seen At' => $cctv->last_seen_at,
                'Created At' => $cctv->created_at,
                'Updated At' => $cctv->updated_at,
            ];
        });

        return $this->exportData($data->toArray(), 'cctvs', $format);
    }

    public function exportUsers(string $format = Excel::CSV)
    {
        $users = User::all();

        $data = $users->map(function ($user) {
            return [
                'ID' => $user->id,
                'Name' => $user->name,
                'Email' => $user->email,
                'Phone' => $user->phone,
                'Department' => $user->department,
                'Position' => $user->position,
                'Status' => $user->status,
                'Last Seen At' => $user->last_seen_at,
                'Email Verified At' => $user->email_verified_at,
                'Created At' => $user->created_at,
                'Updated At' => $user->updated_at,
            ];
        });

        return $this->exportData($data->toArray(), 'users', $format);
    }

    public function exportContacts(string $format = Excel::CSV)
    {
        $contacts = Contact::all();

        $data = $contacts->map(function ($contact) {
            return [
                'ID' => $contact->id,
                'Name' => $contact->name,
                'Email' => $contact->email,
                'Phone' => $contact->phone,
                'WhatsApp' => $contact->whatsapp,
                'Address' => $contact->address,
                'Position' => $contact->position,
                'Department' => $contact->department,
                'Instagram' => $contact->instagram,
                'Facebook' => $contact->facebook,
                'LinkedIn' => $contact->linkedin,
                'Created At' => $contact->created_at,
                'Updated At' => $contact->updated_at,
            ];
        });

        return $this->exportData($data->toArray(), 'contacts', $format);
    }

    public function exportStatistics(string $format = Excel::CSV)
    {
        $cctvStats = $this->cctvRepository->getStatusStatistics();

        $data = [
            [
                'Metric' => 'Total CCTVs',
                'Value' => $cctvStats['total'],
            ],
            [
                'Metric' => 'Online CCTVs',
                'Value' => $cctvStats['online'],
            ],
            [
                'Metric' => 'Offline CCTVs',
                'Value' => $cctvStats['offline'],
            ],
            [
                'Metric' => 'Maintenance CCTVs',
                'Value' => $cctvStats['maintenance'],
            ],
            [
                'Metric' => 'Online Percentage',
                'Value' => $cctvStats['online_percentage'].'%',
            ],
            [
                'Metric' => 'Offline Percentage',
                'Value' => $cctvStats['offline_percentage'].'%',
            ],
            [
                'Metric' => 'Maintenance Percentage',
                'Value' => $cctvStats['maintenance_percentage'].'%',
            ],
        ];

        return $this->exportData($data, 'statistics', $format);
    }

    protected function exportData(array $data, string $filename, string $format)
    {
        // Create a temporary file
        $tempFile = tempnam(sys_get_temp_dir(), $filename);

        // Export data based on format
        switch ($format) {
            case Excel::CSV:
                $this->exportToCsv($data, $tempFile);
                $filename .= '.csv';
                break;
            case Excel::XLSX:
                $this->exportToXlsx($data, $tempFile);
                $filename .= '.xlsx';
                break;
            default:
                $this->exportToCsv($data, $tempFile);
                $filename .= '.csv';
        }

        // Move file to storage
        $storagePath = 'exports/'.$filename;
        Storage::put($storagePath, file_get_contents($tempFile));

        // Delete temporary file
        unlink($tempFile);

        return Storage::path($storagePath);
    }

    protected function exportToCsv(array $data, string $filepath)
    {
        if (empty($data)) {
            return;
        }

        $file = fopen($filepath, 'w');

        // Write headers
        fputcsv($file, array_keys($data[0]));

        // Write data
        foreach ($data as $row) {
            fputcsv($file, $row);
        }

        fclose($file);
    }

    protected function exportToXlsx(array $data, string $filepath)
    {
        // For XLSX export, we would typically use a library like PhpSpreadsheet
        // For now, we'll just export as CSV and change the extension
        $this->exportToCsv($data, $filepath);
    }
}
