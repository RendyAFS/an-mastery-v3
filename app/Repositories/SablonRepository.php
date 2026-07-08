<?php

namespace App\Repositories;

use App\Enums\StatusSablonEnum;
use App\Helpers\RupiahHelper;
use App\Models\Employee;
use App\Models\Fabric;
use App\Models\FabricDetail;
use App\Models\ImageFabric;
use App\Models\PriceEmployee;
use App\Models\Sablon;
use App\Models\Supplier;
use App\Models\TypeColor;
use App\Models\TypeFabric;
use Carbon\Carbon;

class SablonRepository
{
    public function getAll(string $filter = 'active', ?string $search = null, int $perPage = 12, ?string $weekOf = null)
    {
        $query = Sablon::query()
            ->with([
                'supplier',
                'fabric',
                'imageFabric',
                'typeColor',
                'typeFabric',
                'priceEmployee',
                'sablonDetails.colorFabric',
                'sablonEmployeeDetails.employee',
            ])
            ->orderBy('date_sablon', 'desc');

        if ($filter === 'deleted') {
            $query->onlyTrashed();
        } elseif ($filter === 'all') {
            $query->withTrashed();
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('notes', 'like', "%{$search}%")
                    ->orWhereHas('supplier', fn($s) => $s->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('fabric', fn($f) => $f->where('code', 'like', "%{$search}%"));
            });
        }

        if ($weekOf) {
            $start = Carbon::parse($weekOf)->startOfWeek(Carbon::MONDAY)->toDateString();
            $end   = Carbon::parse($weekOf)->endOfWeek(Carbon::SUNDAY)->toDateString();

            $query->whereBetween('date_sablon', [$start, $end]);
        }

        return $query->paginate($perPage);
    }

    public function findWithDetails(Sablon $sablon): Sablon
    {
        return $sablon->load([
            'supplier',
            'fabric',
            'imageFabric',
            'typeColor',
            'typeFabric',
            'priceEmployee',
            'sablonDetails.fabricDetail',
            'sablonDetails.colorFabric',
            'sablonEmployeeDetails.fabricDetail',
            'sablonEmployeeDetails.employee',
            'sablonEmployeeDetails.employeeChange',
        ]);
    }

    /**
     * Data pendukung untuk form create/edit.
     */
    public function getFormData(): array
    {
        return [
            'suppliers'         => Supplier::query()->where('is_active', true)->orderBy('name')->pluck('name', 'id'),
            'fabrics'           => [],
            'imageFabrics'      => ImageFabric::orderBy('name')->pluck('name', 'id'),
            'typeColors'        => TypeColor::orderBy('name')->pluck('name', 'id')->map(fn($name) => $name . ' Warna')->toArray(),
            'typeColorsRaw'     => TypeColor::orderBy('name')->get(['id', 'name'])->mapWithKeys(fn($t) => [$t->id => (float) $t->name])->toArray(),
            'typeFabrics'       => TypeFabric::orderBy('name')->pluck('name', 'id'),
            'priceEmployees'    => PriceEmployee::with('typeColor')->orderBy('id')->get()->mapWithKeys(fn($item) => [$item->id => RupiahHelper::format($item->price) . ' - ' . $item->typeColor?->name . ' Warna'])->toArray(),
            'priceEmployeesRaw' => PriceEmployee::orderBy('id')->pluck('price', 'id')->toArray(),
            'employees'         => Employee::orderBy('name')->pluck('name', 'id'),
            'fabricDetails'     => FabricDetail::with([
                'colorFabric',
                'sablonDetails.sablon',
            ])
                ->get()
                ->map(function ($d) {

                    $usedStock = $d->sablonDetails
                        ->filter(function ($detail) {
                            return $detail->sablon
                                && $detail->sablon->status !== StatusSablonEnum::RETURNED;
                        })
                        ->count();

                    return [
                        'id'              => $d->id,
                        'fabric_id'       => $d->fabric_id,
                        'color_fabric_id' => $d->color_fabric_id,
                        'color_name'      => $d->colorFabric->name ?? '',
                        'stock'           => max(0, $d->stock - $usedStock),
                    ];
                })
                ->values(),
        ];
    }
}
