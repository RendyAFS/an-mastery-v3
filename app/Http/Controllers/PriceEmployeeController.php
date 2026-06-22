<?php

namespace App\Http\Controllers;

use App\Http\Requests\PriceEmployee\SavePriceEmployeeRequest;
use App\Http\Resources\PriceEmployeeResource;
use App\Models\PriceEmployee;
use App\Models\TypeColor;
use App\Models\TypeFabric;
use App\Repositories\PriceEmployeeRepository;
use Illuminate\Http\Request;

class PriceEmployeeController extends Controller
{
    public function __construct(
        private PriceEmployeeRepository $priceEmployeeRepository
    ) {}

    public function index()
    {
        $this->authorize('price-employees.view');

        if (request()->expectsJson()) {

            $filter = request('filter', 'active');

            $priceEmployees = $this->priceEmployeeRepository->getAll($filter);

            return PriceEmployeeResource::collection($priceEmployees);
        }
        $typeFabrics = TypeFabric::pluck('name', 'id')->all();
        $typeColors = TypeColor::pluck('name', 'id')->all();

        return view('price-employee.index', compact('typeFabrics', 'typeColors'));
    }

    public function create()
    {
        //
    }

    public function store(SavePriceEmployeeRequest $request)
    {
        $this->authorize('price-employees.create');

        $priceEmployee = PriceEmployee::create($request->validated());

        return new PriceEmployeeResource($priceEmployee);
    }

    public function show(PriceEmployee $priceEmployee)
    {
        $this->authorize('price-employees.view');

        $priceEmployee->load(['typeFabric', 'typeColor']);

        return new PriceEmployeeResource($priceEmployee);
    }

    public function edit()
    {
        //
    }

    public function update(SavePriceEmployeeRequest $request, PriceEmployee $priceEmployee)
    {
        $this->authorize('price-employees.update');

        $priceEmployee->update($request->validated());

        return new PriceEmployeeResource($priceEmployee);
    }

    public function destroy(PriceEmployee $priceEmployee)
    {
        $this->authorize('price-employees.delete');

        $priceEmployee->delete();

        return response()->noContent();
    }

    public function restore(int $id)
    {
        $this->authorize('price-employees.restore');

        $priceEmployee = PriceEmployee::onlyTrashed()->findOrFail($id);

        $priceEmployee->restore();

        return response()->json([
            'message' => 'Price Supplier restored successfully'
        ]);
    }

    public function forceDelete(int $id)
    {
        $this->authorize('price-employees.forceDelete');

        $priceEmployee = PriceEmployee::onlyTrashed()->findOrFail($id);

        $priceEmployee->forceDelete();

        return response()->json([
            'message' => 'Price Supplier permanently deleted'
        ]);
    }
}
