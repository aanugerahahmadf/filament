<?php

namespace App\Services;

use App\Models\Alert;
use App\Models\Building;
use App\Models\Cctv;
use App\Models\Contact;
use App\Models\Maintenance;
use App\Models\Room;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class SearchService
{
    public function search(string $query, array $types = [], int $perPage = 15): array
    {
        $results = [];

        // If no types specified, search all
        if (empty($types)) {
            $types = ['buildings', 'rooms', 'cctvs', 'users', 'contacts', 'maintenances', 'alerts'];
        }

        foreach ($types as $type) {
            $results[$type] = $this->searchType($type, $query, $perPage);
        }

        return $results;
    }

    protected function searchType(string $type, string $query, int $perPage): LengthAwarePaginator
    {
        return match ($type) {
            'buildings' => $this->searchBuildings($query, $perPage),
            'rooms' => $this->searchRooms($query, $perPage),
            'cctvs' => $this->searchCctvs($query, $perPage),
            'users' => $this->searchUsers($query, $perPage),
            'contacts' => $this->searchContacts($query, $perPage),
            'maintenances' => $this->searchMaintenances($query, $perPage),
            'alerts' => $this->searchAlerts($query, $perPage),
            default => collect()->paginate($perPage),
        };
    }

    protected function searchBuildings(string $query, int $perPage): LengthAwarePaginator
    {
        return Building::where('name', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->orWhere('contact_person', 'like', "%{$query}%")
            ->withCount(['rooms', 'cctvs'])
            ->paginate($perPage, ['*'], 'buildings_page');
    }

    protected function searchRooms(string $query, int $perPage): LengthAwarePaginator
    {
        return Room::where('name', 'like', "%{$query}%")
            ->with(['building'])
            ->paginate($perPage, ['*'], 'rooms_page');
    }

    protected function searchCctvs(string $query, int $perPage): LengthAwarePaginator
    {
        return Cctv::where('name', 'like', "%{$query}%")
            ->orWhere('model', 'like', "%{$query}%")
            ->orWhere('serial_number', 'like', "%{$query}%")
            ->orWhere('ip_rtsp', 'like', "%{$query}%")
            ->with(['building', 'room'])
            ->paginate($perPage, ['*'], 'cctvs_page');
    }

    protected function searchUsers(string $query, int $perPage): LengthAwarePaginator
    {
        return User::where('name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->orWhere('phone', 'like', "%{$query}%")
            ->orWhere('department', 'like', "%{$query}%")
            ->orWhere('position', 'like', "%{$query}%")
            ->paginate($perPage, ['*'], 'users_page');
    }

    protected function searchContacts(string $query, int $perPage): LengthAwarePaginator
    {
        return Contact::where('name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->orWhere('phone', 'like', "%{$query}%")
            ->orWhere('whatsapp', 'like', "%{$query}%")
            ->orWhere('address', 'like', "%{$query}%")
            ->orWhere('position', 'like', "%{$query}%")
            ->orWhere('department', 'like', "%{$query}%")
            ->paginate($perPage, ['*'], 'contacts_page');
    }

    protected function searchMaintenances(string $query, int $perPage): LengthAwarePaginator
    {
        return Maintenance::where('description', 'like', "%{$query}%")
            ->orWhere('notes', 'like', "%{$query}%")
            ->with(['cctv', 'technician'])
            ->paginate($perPage, ['*'], 'maintenances_page');
    }

    protected function searchAlerts(string $query, int $perPage): LengthAwarePaginator
    {
        return Alert::where('title', 'like', "%{$query}%")
            ->orWhere('message', 'like', "%{$query}%")
            ->with(['user', 'alertable'])
            ->paginate($perPage, ['*'], 'alerts_page');
    }

    public function globalSearch(string $query, int $perPage = 10): array
    {
        if (empty($query)) {
            return [];
        }

        $results = [
            'buildings' => $this->searchBuildings($query, $perPage)->items(),
            'rooms' => $this->searchRooms($query, $perPage)->items(),
            'cctvs' => $this->searchCctvs($query, $perPage)->items(),
            'users' => $this->searchUsers($query, $perPage)->items(),
            'contacts' => $this->searchContacts($query, $perPage)->items(),
        ];

        // Filter out empty results
        return array_filter($results, fn ($items) => ! empty($items));
    }
}
