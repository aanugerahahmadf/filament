<?php

namespace App\Services;

use App\Models\Alert;
use App\Models\Building;
use App\Models\Cctv;
use App\Models\Maintenance;
use App\Models\Room;
use Illuminate\Support\Facades\Storage;

class ReportService
{
    public function generateCctvStatusReport(string $format = 'html'): string
    {
        $cctvs = Cctv::with(['building', 'room'])->get();

        $reportData = [
            'title' => 'CCTV Status Report',
            'generated_at' => now(),
            'total_cctvs' => $cctvs->count(),
            'online_cctvs' => $cctvs->where('status', Cctv::STATUS_ONLINE)->count(),
            'offline_cctvs' => $cctvs->where('status', Cctv::STATUS_OFFLINE)->count(),
            'maintenance_cctvs' => $cctvs->where('status', Cctv::STATUS_MAINTENANCE)->count(),
            'cctvs' => $cctvs,
        ];

        return $this->generateReport('cctv-status', $reportData, $format);
    }

    public function generateMaintenanceReport(string $format = 'html'): string
    {
        $maintenances = Maintenance::with(['cctv', 'technician'])->get();

        $reportData = [
            'title' => 'Maintenance Report',
            'generated_at' => now(),
            'total_maintenances' => $maintenances->count(),
            'scheduled' => $maintenances->where('status', Maintenance::STATUS_SCHEDULED)->count(),
            'in_progress' => $maintenances->where('status', Maintenance::STATUS_IN_PROGRESS)->count(),
            'completed' => $maintenances->where('status', Maintenance::STATUS_COMPLETED)->count(),
            'cancelled' => $maintenances->where('status', Maintenance::STATUS_CANCELLED)->count(),
            'total_cost' => $maintenances->sum('cost'),
            'maintenances' => $maintenances,
        ];

        return $this->generateReport('maintenance', $reportData, $format);
    }

    public function generateAlertReport(string $format = 'html'): string
    {
        $alerts = Alert::with(['user', 'alertable'])->get();

        $reportData = [
            'title' => 'Alert Report',
            'generated_at' => now(),
            'total_alerts' => $alerts->count(),
            'active' => $alerts->where('status', Alert::STATUS_ACTIVE)->count(),
            'acknowledged' => $alerts->where('status', Alert::STATUS_ACKNOWLEDGED)->count(),
            'resolved' => $alerts->where('status', Alert::STATUS_RESOLVED)->count(),
            'suppressed' => $alerts->where('status', Alert::STATUS_SUPPRESSED)->count(),
            'by_severity' => $alerts->groupBy('severity')->map->count(),
            'by_category' => $alerts->groupBy('category')->map->count(),
            'alerts' => $alerts,
        ];

        return $this->generateReport('alert', $reportData, $format);
    }

    public function generateInfrastructureReport(string $format = 'html'): string
    {
        $buildings = Building::withCount(['rooms', 'cctvs'])->get();
        $rooms = Room::with(['building'])->get();

        $reportData = [
            'title' => 'Infrastructure Report',
            'generated_at' => now(),
            'total_buildings' => $buildings->count(),
            'total_rooms' => $rooms->count(),
            'buildings' => $buildings,
            'rooms' => $rooms,
        ];

        return $this->generateReport('infrastructure', $reportData, $format);
    }

    protected function generateReport(string $reportType, array $data, string $format): string
    {
        $filename = "reports/{$reportType}_".now()->format('Y-m-d_H-i-s');

        switch ($format) {
            case 'pdf':
                return $this->generatePdfReport($filename, $data);
            case 'csv':
                return $this->generateCsvReport($filename, $data);
            case 'html':
            default:
                return $this->generateHtmlReport($filename, $data);
        }
    }

    protected function generateHtmlReport(string $filename, array $data): string
    {
        $content = view('reports.html', $data)->render();
        $filepath = $filename.'.html';
        Storage::put($filepath, $content);

        return Storage::path($filepath);
    }

    protected function generatePdfReport(string $filename, array $data): string
    {
        // For PDF generation, you would typically use a library like DomPDF or TCPDF
        // For now, we'll just generate HTML and change the extension
        $content = view('reports.html', $data)->render();
        $filepath = $filename.'.pdf';
        Storage::put($filepath, $content);

        return Storage::path($filepath);
    }

    protected function generateCsvReport(string $filename, array $data): string
    {
        // For CSV reports, we would need to flatten the data structure
        // For now, we'll just generate a simple CSV with basic data
        $filepath = $filename.'.csv';
        Storage::put($filepath, 'Report generated at: '.now()->toISOString());

        return Storage::path($filepath);
    }

    public function getReportStatistics(): array
    {
        return [
            'cctv_status' => [
                'total' => Cctv::count(),
                'online' => Cctv::online()->count(),
                'offline' => Cctv::offline()->count(),
                'maintenance' => Cctv::maintenance()->count(),
            ],
            'maintenance' => [
                'total' => Maintenance::count(),
                'scheduled' => Maintenance::scheduled()->count(),
                'in_progress' => Maintenance::inProgress()->count(),
                'completed' => Maintenance::completed()->count(),
                'cancelled' => Maintenance::cancelled()->count(),
                'total_cost' => Maintenance::sum('cost'),
            ],
            'alerts' => [
                'total' => Alert::count(),
                'active' => Alert::active()->count(),
                'acknowledged' => Alert::acknowledged()->count(),
                'resolved' => Alert::resolved()->count(),
                'suppressed' => Alert::suppressed()->count(),
            ],
        ];
    }
}
