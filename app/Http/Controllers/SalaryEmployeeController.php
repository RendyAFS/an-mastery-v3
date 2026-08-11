<?php

namespace App\Http\Controllers;

use App\Actions\SalaryEmployee\UpsertSalaryEmployeeAction;
use App\Enums\StatusSalaryEmployeeEnum;
use App\Helpers\WeekHelper;
use App\Http\Resources\SalaryEmployeeResource;
use App\Models\Employee;
use App\Models\Memo;
use App\Models\Presence;
use App\Models\SablonEmployeeDetail;
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
            'week_start' => 'required|date',
            'week_end'   => 'required|date|after_or_equal:week_start',
        ]);

        [$dateFrom, $dateTo] = WeekHelper::parseRange(
            $validated['week_start'],
            $validated['week_end']
        );

        $start = $dateFrom->copy()->startOfWeek(\Carbon\Carbon::MONDAY);
        $end   = $dateTo->copy()->endOfWeek(\Carbon\Carbon::SUNDAY);

        $employeeIds = collect()
            ->merge(
                SablonEmployeeDetail::query()
                    ->whereHas('sablon', function ($query) use ($start, $end) {
                        $query->whereBetween('date_sablon', [
                            $start->toDateString(),
                            $end->toDateString(),
                        ]);
                    })
                    ->pluck('employee_id')
            )
            ->merge(
                Memo::query()
                    ->whereBetween('created_at', [
                        $start->copy()->startOfDay(),
                        $end->copy()->endOfDay(),
                    ])
                    ->pluck('employee_id')
            )
            ->merge(
                Presence::query()
                    ->whereBetween('week_of', [
                        $start->toDateString(),
                        $end->toDateString(),
                    ])
                    ->pluck('employee_id')
            )
            ->unique()
            ->values();

        foreach ($employeeIds as $employeeId) {
            $this->upsertSalaryEmployeeAction->handleForEmployee(
                (int) $employeeId,
                $start->toDateString()
            );
        }

        return response()->json([
            'message' => __('salary-employee.sync.synced_success', [
                'count' => $employeeIds->count(),
            ]),
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
