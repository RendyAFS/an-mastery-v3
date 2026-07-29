<?php

namespace App\Http\Controllers;

use App\Actions\SalaryEmployee\UpsertSalaryEmployeeAction;
use App\Enums\StatusSalaryEmployeeEnum;
use App\Helpers\WeekHelper;
use App\Http\Resources\SalaryEmployeeResource;
use App\Models\Employee;
use App\Repositories\SalaryEmployeeRepository;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;

class SalaryEmployeeController extends Controller
{
    public function __construct(
        private SalaryEmployeeRepository $salaryEmployeeRepository,
        private UpsertSalaryEmployeeAction $upsertSalaryEmployeeAction
    ) {}

    public function index()
    {
        $this->authorize('salary-employees.view');

        if (request()->expectsJson()) {
            $filter  = request('filter', 'all');
            $search  = request('search');
            $perPage = min((int) request('per_page', 12), 100);

            [$dateFrom, $dateTo] = WeekHelper::parseRange(request('week_start'), request('week_end'));

            $salaries = $this->salaryEmployeeRepository->getAll($filter, $search, $perPage, $dateFrom, $dateTo);

            return SalaryEmployeeResource::collection($salaries);
        }

        return view('salary-employee.index');
    }

    public function sync(Request $request)
    {
        $this->authorize('salary-employees.update');

        $validated = $request->validate([
            'week_of' => 'required|date',
        ]);

        $count = $this->upsertSalaryEmployeeAction->handleBulk($validated['week_of']);

        return response()->json([
            'message' => "Synced {$count} employee salary records.",
        ]);
    }

    public function update(Request $request, Employee $employee)
    {
        $this->authorize('salary-employees.update');

        $validated = $request->validate([
            'week_of'                  => 'required|date',
            'status'                   => ['required', new Enum(StatusSalaryEmployeeEnum::class)],
            'additional_fee'           => 'nullable|array',
            'additional_fee.*.nominal' => 'required|numeric',
            'additional_fee.*.notes'   => 'nullable|string|max:255',
        ]);

        $salary = $this->upsertSalaryEmployeeAction->handleForEmployee(
            $employee->id,
            $validated['week_of'],
            $validated['status'],
            $validated['additional_fee'] ?? []
        );

        return new SalaryEmployeeResource($salary);
    }
}
