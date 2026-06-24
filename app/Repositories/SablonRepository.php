<?php

namespace App\Repositories;

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

class SablonRepository
{
    public function getAll(string $filter = 'active', ?string $search = null, int $perPage = 12)
    {
        $query = Sablon::query()
            ->with('supplier', 'fabric', 'imageFabric', 'typeColor', 'typeFabric', 'priceEmployee')
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
            'suppliers'         => Supplier::orderBy('name')->pluck('name', 'id'),
            'fabrics'           => [],
            'imageFabrics'      => ImageFabric::orderBy('name')->pluck('name', 'id'),
            'typeColors'        => TypeColor::orderBy('name')->pluck('name', 'id')->map(fn($name) => $name . ' Warna')->toArray(),
            'typeColorsRaw'     => TypeColor::orderBy('name')->get(['id', 'name'])->mapWithKeys(fn($t) => [$t->id => (float) $t->name])->toArray(),
            'typeFabrics'       => TypeFabric::orderBy('name')->pluck('name', 'id'),
            'priceEmployees' => PriceEmployee::with('typeColor')->orderBy('id')->get()->mapWithKeys(fn($item) => [$item->id => RupiahHelper::format($item->price) . ' - ' . $item->typeColor?->name . ' Warna'])->toArray(),
            'priceEmployeesRaw' => PriceEmployee::orderBy('id')->pluck('price', 'id')->toArray(),
            'employees'         => Employee::orderBy('name')->pluck('name', 'id'),
            'fabricDetails'     => FabricDetail::with('colorFabric')->get()
                ->map(fn($d) => [
                    'id'              => $d->id,
                    'fabric_id'       => $d->fabric_id,
                    'color_fabric_id' => $d->color_fabric_id,
                    'color_name'      => $d->colorFabric->name ?? '',
                    'stock'           => $d->stock,
                ])
                ->values(),
        ];
    }
}
