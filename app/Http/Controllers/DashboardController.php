<?php

namespace App\Http\Controllers;

use App\Helpers\WeekHelper;
use App\Http\Resources\DashboardLatestSablonResource;
use App\Http\Resources\FabricResource;
use App\Repositories\DashboardRepository;

class DashboardController extends Controller
{
    public function __construct(
        private DashboardRepository $dashboardRepository
    ) {}

    public function index()
    {
        $this->authorize('dashboard.view');

        if (request()->expectsJson()) {
            [$dateFrom, $dateTo] = WeekHelper::parseRange(request('week_start'), request('week_end'));

            return response()->json([
                'stats' => $this->dashboardRepository->getStats(),
                'charts' => [
                    'status_sablon'  => $this->dashboardRepository->getStatusSablonChart($dateFrom, $dateTo),
                    'sablon_per_day' => $this->dashboardRepository->getSablonPerDayChart($dateFrom, $dateTo),
                    'top_supplier'   => $this->dashboardRepository->getTopSupplierChart($dateFrom, $dateTo),
                ],
                'presences' => $this->dashboardRepository->getPresenceThisWeek(),
            ]);
        }

        return view('dashboard.index');
    }

    public function fabrics()
    {
        $this->authorize('dashboard.view');

        $fabrics = $this->dashboardRepository->getFabricsQuery();

        return FabricResource::collection($fabrics);
    }

    public function latestSablons()
    {
        $this->authorize('dashboard.view');

        [$dateFrom, $dateTo] = WeekHelper::parseRange(request('week_start'), request('week_end'));

        $sablons = $this->dashboardRepository->getLatestSablonsQuery($dateFrom, $dateTo);

        return DashboardLatestSablonResource::collection($sablons);
    }
}
