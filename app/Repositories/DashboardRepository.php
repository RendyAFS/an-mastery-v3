<?php

namespace App\Repositories;

use App\Enums\StatusSablonEnum;
use App\Enums\StatusSalaryEmployeeEnum;
use App\Helpers\RupiahHelper;
use App\Models\BillSupplier;
use App\Models\Employee;
use App\Models\Fabric;
use App\Models\FabricDetail;
use App\Models\Sablon;
use App\Models\SalaryEmployee;
use App\Models\Supplier;
use Carbon\Carbon;

class DashboardRepository
{
    public function getStats(): array
    {
        $unpaidTotal = (int) BillSupplier::where('is_paid', false)->sum('total_fee');

        return [
            'employees_total'                      => Employee::where('is_active', true)->count(),
            'suppliers_total'                      => Supplier::where('is_active', true)->count(),
            'fabric_stock_total'                   => (int) FabricDetail::sum('stock'),
            'bill_supplier_unpaid_count'           => BillSupplier::where('is_paid', false)->count(),
            'bill_supplier_unpaid_total'           => $unpaidTotal,
            'bill_supplier_unpaid_total_formatted' => RupiahHelper::format($unpaidTotal),
            'salary_pending_count'                 => SalaryEmployee::where('status', StatusSalaryEmployeeEnum::PENDING)->count(),
        ];
    }

    public function getStatusSablonChart(Carbon $dateFrom, Carbon $dateTo): array
    {
        $rows = Sablon::query()
            ->whereBetween('date_sablon', [$dateFrom, $dateTo])
            ->selectRaw('status, supplier_id, count(*) as total')
            ->groupBy('status', 'supplier_id')
            ->with('supplier:id,name')
            ->get();

        $labels    = [];
        $series    = [];
        $suppliers = [];

        foreach (StatusSablonEnum::cases() as $status) {
            $statusRows = $rows->where('status', $status);

            $labels[]    = $status->labels();
            $series[]    = (int) $statusRows->sum('total');
            $suppliers[] = $statusRows
                ->map(fn($r) => [
                    'supplier' => $r->supplier?->name ?? '-',
                    'total'    => (int) $r->total,
                ])
                ->values();
        }

        return compact('labels', 'series', 'suppliers');
    }

    public function getSablonPerDayChart(Carbon $dateFrom, Carbon $dateTo): array
    {
        $rows = Sablon::query()
            ->whereBetween('date_sablon', [$dateFrom, $dateTo])
            ->selectRaw('date_sablon, count(*) as total')
            ->groupBy('date_sablon')
            ->get()
            ->keyBy(fn($row) => Carbon::parse($row->date_sablon)->toDateString());

        $categories = [];
        $series     = [];

        foreach (Carbon::parse($dateFrom)->daysUntil(Carbon::parse($dateTo)) as $date) {
            $key = $date->toDateString();
            $categories[] = $date->translatedFormat('d M');
            $series[]      = (int) ($rows[$key]->total ?? 0);
        }

        return compact('categories', 'series');
    }

    public function getTopSupplierChart(Carbon $dateFrom, Carbon $dateTo, int $limit = 5): array
    {
        $rows = Sablon::query()
            ->whereBetween('date_sablon', [$dateFrom, $dateTo])
            ->selectRaw('supplier_id, count(*) as total')
            ->groupBy('supplier_id')
            ->orderByDesc('total')
            ->limit($limit)
            ->with('supplier:id,name')
            ->get();

        return [
            'categories' => $rows->map(fn($r) => $r->supplier?->name ?? '-')->values()->toArray(),
            'series'     => $rows->pluck('total')->map(fn($v) => (int) $v)->values()->toArray(),
        ];
    }

    public function getPresenceThisWeek(): array
    {
        $weekOf = Carbon::now()->startOfWeek(Carbon::MONDAY);

        return Employee::query()
            ->where('is_active', true)
            ->with(['presences' => fn($q) => $q->where('week_of', $weekOf->toDateString())])
            ->orderBy('name')
            ->get()
            ->map(function ($employee) {
                $presence = $employee->presences->first();

                return [
                    'employee_id' => $employee->id,
                    'name'        => $employee->name,
                    'total'       => $presence->total ?? 0,
                ];
            })
            ->values()
            ->toArray();
    }

    public function getLatestSablons(Carbon $dateFrom, Carbon $dateTo, int $limit = 10): array
    {
        return Sablon::query()
            ->with(['supplier', 'imageFabric'])
            ->whereBetween('date_sablon', [$dateFrom, $dateTo])
            ->orderByDesc('date_sablon')
            ->limit($limit)
            ->get()
            ->map(fn($s) => [
                'id'                     => $s->id,
                'supplier'               => $s->supplier?->name ?? '-',
                'image_fabric'           => $s->imageFabric?->name ?? '-',
                'status'                 => $s->status?->value,
                'status_label'           => $s->status?->labels(),
                'date_sablon'            => $s->date_sablon?->format('Y-m-d'),
                'total_sablon_formatted' => RupiahHelper::format($s->total_sablon),
            ])
            ->values()
            ->toArray();
    }

    public function getLatestSablonsQuery(Carbon $dateFrom, Carbon $dateTo)
    {
        return Sablon::query()
            ->with(['supplier', 'imageFabric', 'typeFabric', 'typeColor'])
            ->when($dateFrom && $dateTo, fn($q) => $q->whereBetween('date_sablon', [$dateFrom, $dateTo]))
            ->orderByDesc('date_sablon')
            ->limit(50)
            ->get();
    }

    public function getFabricsQuery(int $limit = 20)
    {
        return Fabric::query()
            ->with([
                'supplier',
                'typeFabric',
                'fabricDetails.colorFabric',
            ])
            ->orderBy('id', 'desc')
            ->limit($limit)
            ->get();
    }
}
