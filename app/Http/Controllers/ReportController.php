<?php

namespace App\Http\Controllers;

use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ReportController extends Controller
{
    protected ReportService $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    /**
     * Display the reports dashboard
     */
    public function index(): View
    {
        $statistics = $this->reportService->getReportStatistics();

        return view('reports.index', compact('statistics'));
    }

    /**
     * Generate and download a CCTV status report
     */
    public function cctvStatus(Request $request): BinaryFileResponse
    {
        $format = $request->get('format', 'html');
        $filepath = $this->reportService->generateCctvStatusReport($format);

        return response()->download($filepath)->deleteFileAfterSend(true);
    }

    /**
     * Generate and download a maintenance report
     */
    public function maintenance(Request $request): BinaryFileResponse
    {
        $format = $request->get('format', 'html');
        $filepath = $this->reportService->generateMaintenanceReport($format);

        return response()->download($filepath)->deleteFileAfterSend(true);
    }

    /**
     * Generate and download an alert report
     */
    public function alerts(Request $request): BinaryFileResponse
    {
        $format = $request->get('format', 'html');
        $filepath = $this->reportService->generateAlertReport($format);

        return response()->download($filepath)->deleteFileAfterSend(true);
    }

    /**
     * Generate and download an infrastructure report
     */
    public function infrastructure(Request $request): BinaryFileResponse
    {
        $format = $request->get('format', 'html');
        $filepath = $this->reportService->generateInfrastructureReport($format);

        return response()->download($filepath)->deleteFileAfterSend(true);
    }
}
