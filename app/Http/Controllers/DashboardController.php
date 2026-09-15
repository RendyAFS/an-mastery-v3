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
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if ($user && !$user->hasRole('Super Admin') && $user->cannot('dashboard.view')) {
            $firstAccessibleUrl = \App\Helpers\MenuHelper::getFirstAccessibleUrl($user);
            if ($firstAccessibleUrl && $firstAccessibleUrl !== '/dashboard' && $firstAccessibleUrl !== url('/dashboard')) {
                return redirect($firstAccessibleUrl);
            }
        }

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

        $search  = request('search');
        $perPage = min((int) request('per_page', 6), 50);

        $fabrics = $this->dashboardRepository->getFabricsQuery($search, $perPage);

        return FabricResource::collection($fabrics);
    }

    public function latestSablons()
    {
        $this->authorize('dashboard.view');

        [$dateFrom, $dateTo] = WeekHelper::parseRange(
            request('week_start'),
            request('week_end')
        );

        $dateFrom ??= now()->startOfMonth();
        $dateTo   ??= now()->endOfMonth();

        $sablons = $this->dashboardRepository->getLatestSablonsQuery($dateFrom, $dateTo);

        return DashboardLatestSablonResource::collection($sablons);
    }
}
