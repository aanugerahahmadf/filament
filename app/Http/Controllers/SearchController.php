<?php

namespace App\Http\Controllers;

use App\Services\SearchService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    protected SearchService $searchService;

    public function __construct(SearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    /**
     * Display search results
     */
    public function index(Request $request): View
    {
        $query = $request->get('q');
        $types = $request->get('types', []);

        if ($query) {
            $results = $this->searchService->search($query, $types);
        } else {
            $results = [];
        }

        return view('search.index', compact('results', 'query'));
    }

    /**
     * API endpoint for search
     */
    public function apiSearch(Request $request): JsonResponse
    {
        $query = $request->get('q');
        $types = $request->get('types', []);
        $perPage = $request->get('per_page', 15);

        if ($query) {
            $results = $this->searchService->search($query, $types, $perPage);
        } else {
            $results = [];
        }

        return response()->json([
            'success' => true,
            'query' => $query,
            'results' => $results,
        ]);
    }

    /**
     * Global search endpoint
     */
    public function globalSearch(Request $request): JsonResponse
    {
        $query = $request->get('q');
        $perPage = $request->get('per_page', 10);

        if ($query) {
            $results = $this->searchService->globalSearch($query, $perPage);
        } else {
            $results = [];
        }

        return response()->json([
            'success' => true,
            'query' => $query,
            'results' => $results,
        ]);
    }
}
