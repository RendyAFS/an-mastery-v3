<?php

namespace App\Http\Controllers;

use App\Actions\SalaryEmployee\UpdateAdditionalFeeAction;
use App\Enums\StatusSalaryEmployeeEnum;
use App\Http\Resources\SalaryEmployeeResource;
use App\Models\SalaryEmployee;
use App\Repositories\SalaryEmployeeRepository;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;

class SalaryEmployeeController extends Controller
{
    public function __construct(
        private SalaryEmployeeRepository $salaryEmployeeRepository
    ) {}

    public function index()
    {
        $this->authorize('salary-employees.view');

        if (request()->expectsJson()) {
            $filter  = request('filter', 'all');
            $search  = request('search');
            $weekOf  = request('week_of');
            $perPage = min((int) request('per_page', 12), 100);

            $salaries = $this->salaryEmployeeRepository->getAll($filter, $search, $perPage, $weekOf);

            return SalaryEmployeeResource::collection($salaries);
        }

        return view('salary-employee.index');
    }

    public function updateStatus(Request $request, SalaryEmployee $salaryEmployee)
    {
        $this->authorize('salary-employees.update');

        $validated = $request->validate([
            'status' => ['required', new Enum(StatusSalaryEmployeeEnum::class)],
        ]);

        $salaryEmployee->update([
            'status' => $validated['status'],
        ]);

        return response()->json([
            'message' => 'Status updated successfully.',
        ]);
    }

    public function updateAdditionalFee(Request $request, SalaryEmployee $salaryEmployee, UpdateAdditionalFeeAction $action)
    {
        $this->authorize('salary-employees.update');

        $validated = $request->validate([
            'additional_fee'             => 'nullable|array',
            'additional_fee.*.nominal'   => 'required|numeric',
            'additional_fee.*.notes'     => 'nullable|string|max:255',
        ]);

        $salaryEmployee = $action->handle($salaryEmployee, $validated['additional_fee'] ?? []);

        return new SalaryEmployeeResource($salaryEmployee->load([
            'employee',
            'sablonEmployeeDetails.sablon.supplier',
            'sablonEmployeeDetails.sablon.imageFabric',
        ]));
    }
}
