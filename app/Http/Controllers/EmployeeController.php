<?php

namespace App\Http\Controllers;

use App\Repositories\EmployeeRepository;
use App\Http\Requests\Employee\SaveEmployeeRequest;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function __construct(
        private EmployeeRepository $employeeRepository
    ) {}

    public function index()
    {
        $this->authorize('employees.view');

        if (request()->expectsJson()) {

            $filter = request('filter', 'active');

            $employees = $this->employeeRepository->getAll($filter);

            return EmployeeResource::collection($employees);
        }

        return view('employee.index');
    }

    public function create()
    {
        //
    }

    public function store(SaveEmployeeRequest $request)
    {
        $this->authorize('employees.create');

        $employee = Employee::create($request->validated());

        return new EmployeeResource($employee);
    }

    public function show(Employee $employee)
    {
        $this->authorize('employees.view');

        return new EmployeeResource($employee);
    }

    public function edit()
    {
        //
    }

    public function update(SaveEmployeeRequest $request, Employee $employee)
    {
        $this->authorize('employees.update');

        $employee->update($request->validated());

        return new EmployeeResource($employee);
    }

    public function destroy(Employee $employee)
    {
        $this->authorize('employees.delete');

        $employee->delete();

        return response()->noContent();
    }

    public function restore(int $id)
    {
        $this->authorize('employees.restore');

        $employee = Employee::onlyTrashed()->findOrFail($id);

        $employee->restore();

        return response()->json([
            'message' => 'Employee restored successfully'
        ]);
    }

    public function forceDelete(int $id)
    {
        $this->authorize('employees.forceDelete');

        $employee = Employee::onlyTrashed()->findOrFail($id);

        $employee->forceDelete();

        return response()->json([
            'message' => 'Employee permanently deleted'
        ]);
    }

    public function select(Request $request)
    {
        $employees = $this->employeeRepository->getDataSelect(
            search: $request->search,
            limit: $request->limit ?? 10,
            page: $request->page ?? 1
        );

        return response()->json([
            'count'   => $employees->total(),
            'results' => $employees->getCollection()->map(fn($item) => [
                'id'   => $item->id,
                'name' => $item->name,
                'page' => $employees->currentPage(),
            ]),
        ]);
    }
}
