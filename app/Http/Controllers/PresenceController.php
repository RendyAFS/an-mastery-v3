<?php

namespace App\Http\Controllers;

use App\Actions\SalaryEmployee\UpsertSalaryEmployeeAction;
use App\Http\Requests\Presence\BulkGeneratePresenceRequest;
use App\Http\Requests\Presence\SavePresenceRequest;
use App\Http\Resources\PresenceResource;
use App\Models\Employee;
use App\Models\SalaryEmployee;
use App\Repositories\PresenceRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PresenceController extends Controller
{
    public function __construct(
        protected PresenceRepository $presenceRepository,
        protected UpsertSalaryEmployeeAction $upsertSalaryEmployeeAction
    ) {}

    public function index()
    {
        return view('presence.index');
    }

    public function data(Request $request)
    {
        $weekOf = $this->resolveWeekOf($request->input('week_of'));
        $filter = $request->input('filter', 'active');

        $employees = $this->presenceRepository->getEmployeesWithPresenceForWeek($weekOf, $filter);

        $data = $employees->map(function (Employee $employee) {
            $presence = $employee->presences->first();

            return [
                'employee_id'   => $employee->id,
                'employee_name' => $employee->name,
                'is_deleted'    => $employee->trashed(),
                'monday'        => $presence?->monday ?? 0,
                'tuesday'       => $presence?->tuesday ?? 0,
                'wednesday'     => $presence?->wednesday ?? 0,
                'thursday'      => $presence?->thursday ?? 0,
                'friday'        => $presence?->friday ?? 0,
                'saturday'      => $presence?->saturday ?? 0,
                'sunday'        => $presence?->sunday ?? 0,
                'total'         => $presence?->total ?? 0,
                'notes'         => $presence?->notes ?? null,
            ];
        });

        return response()->json([
            'data'  => $data,
            'dates' => collect(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'])
                ->mapWithKeys(fn($day, $i) => [$day => $weekOf->copy()->addDays($i)->toDateString()]),
        ]);
    }

    public function show(Employee $employee, Request $request)
    {
        $weekOf = $this->resolveWeekOf($request->get('week_of'));

        $presence = $this->presenceRepository->findOrNew($employee->id, $weekOf);

        return new PresenceResource($presence);
    }

    public function update(SavePresenceRequest $request, Employee $employee)
    {
        if ($employee->trashed()) {
            return response()->json(['message' => __('presence.deleted_employee_error')], 422);
        }

        $weekOf = Carbon::parse($request->validated('week_of'))->startOfWeek(Carbon::MONDAY);

        $presence = $this->presenceRepository->updateOrCreate(
            $employee->id,
            $weekOf,
            $request->days(),
            $request->validated('notes')
        );

        $this->reopenSalaryIfPaid($employee->id, $weekOf);

        $message = $presence->wasRecentlyCreated
            ? __('presence.created_success')
            : __('presence.updated_success');

        return (new PresenceResource($presence->load('employee')))
            ->additional(['message' => $message]);
    }

    private function resolveWeekOf(?string $date): Carbon
    {
        return ($date ? Carbon::parse($date) : Carbon::now())->startOfWeek(Carbon::MONDAY);
    }


    public function employees()
    {
        return response()->json([
            'data' => Employee::query()->where('is_active', true)->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function bulkGenerate(BulkGeneratePresenceRequest $request)
    {
        $weekOf = Carbon::parse($request->validated('week_of'))->startOfWeek(Carbon::MONDAY);

        $employeeIds = $request->validated('employee_ids');

        $count = $this->presenceRepository->bulkGenerate(
            $employeeIds,
            $weekOf,
            $request->validated('amount'),
            $request->validated('days')
        );

        foreach ($employeeIds as $employeeId) {
            $this->reopenSalaryIfPaid((int) $employeeId, $weekOf);
        }

        return response()->json([
            'message' => __('presence.generated_success_count', ['count' => $count]),
        ]);
    }

    private function reopenSalaryIfPaid(int $employeeId, Carbon $weekOf): void
    {
        $salary = SalaryEmployee::where('employee_id', $employeeId)
            ->where('date', $weekOf->toDateString())
            ->first();

        if ($salary) {
            $this->upsertSalaryEmployeeAction->reopenIfPaid($salary);
        }
    }
}
