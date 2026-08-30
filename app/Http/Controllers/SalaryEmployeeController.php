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
use App\Models\SalaryEmployee;
use App\Repositories\SalaryEmployeeRepository;
use Carbon\Carbon;
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

        $start = $dateFrom->copy()->startOfWeek(Carbon::MONDAY);
        $end   = $dateTo->copy()->endOfWeek(Carbon::SUNDAY);

        $realignedPairs = $this->upsertSalaryEmployeeAction->realignMisplacedDetails();

        $pairs = collect()
            ->merge(
                SablonEmployeeDetail::query()
                    ->whereHas('sablon', fn($q) => $q->whereBetween('date_sablon', [
                        $start->toDateString(),
                        $end->toDateString(),
                    ]))
                    ->with('sablon')
                    ->get()
                    ->map(fn(SablonEmployeeDetail $d) => $d->employee_id . '|' . Carbon::parse(
                        $d->weekAnchorDate() ?? $d->sablon?->date_sablon
                    )->startOfWeek(Carbon::MONDAY)->toDateString())
            )
            ->merge(
                Memo::query()
                    ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
                    ->get()
                    ->map(fn(Memo $m) => $m->employee_id . '|' . Carbon::parse($m->date)
                        ->startOfWeek(Carbon::MONDAY)->toDateString())
            )
            ->merge(
                Presence::query()
                    ->whereBetween('week_of', [$start->toDateString(), $end->toDateString()])
                    ->get()
                    ->map(fn($p) => $p->employee_id . '|' . Carbon::parse($p->week_of)
                        ->startOfWeek(Carbon::MONDAY)->toDateString())
            )
            ->merge($realignedPairs)
            ->unique()
            ->values();

        foreach ($pairs as $pair) {
            [$employeeId, $weekStart] = explode('|', $pair);
            $this->upsertSalaryEmployeeAction->handleForEmployee((int) $employeeId, $weekStart);
        }

        return response()->json([
            'message' => __('salary-employee.sync.synced_success', [
                'count' => $pairs->count(),
            ]),
        ]);
    }

    public function additionalFee(Request $request, Employee $employee)
    {
        $this->authorize('salary-employees.view');

        $validated = $request->validate([
            'week_of' => 'required|date',
        ]);

        $start = Carbon::parse($validated['week_of'])
            ->startOfWeek(Carbon::MONDAY)
            ->toDateString();

        $salary = SalaryEmployee::query()
            ->where('employee_id', $employee->id)
            ->where('date', $start)
            ->first();

        return response()->json([
            'additional_fee' => $salary->additional_fee ?? [],
            'status'         => $salary->status?->value ?? StatusSalaryEmployeeEnum::PENDING->value,
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
