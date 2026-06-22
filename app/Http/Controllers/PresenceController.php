<?php

namespace App\Http\Controllers;

use App\Http\Requests\Presence\SavePresenceRequest;
use App\Http\Resources\PresenceResource;
use App\Models\Employee;
use App\Repositories\PresenceRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PresenceController extends Controller
{
    public function __construct(protected PresenceRepository $presenceRepository) {}

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
            return response()->json(['message' => 'Cannot update presence for a deleted employee'], 422);
        }

        $presence = $this->presenceRepository->updateOrCreate(
            $employee->id,
            Carbon::parse($request->validated('week_of'))->startOfWeek(Carbon::MONDAY),
            $request->days(),
            $request->validated('notes')
        );

        return (new PresenceResource($presence->load('employee')))
            ->additional(['message' => 'Presence updated successfully']);
    }

    private function resolveWeekOf(?string $date): Carbon
    {
        return ($date ? Carbon::parse($date) : Carbon::now())->startOfWeek(Carbon::MONDAY);
    }
}
