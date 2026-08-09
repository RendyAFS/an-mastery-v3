<?php

namespace App\Http\Controllers;

use App\Actions\SalaryEmployee\UpsertSalaryEmployeeAction;
use App\Http\Requests\Memo\SaveMemoRequest;
use App\Http\Resources\MemoResource;
use App\Models\Memo;
use App\Repositories\MemoRepository;

class MemoController extends Controller
{
    public function __construct(
        private MemoRepository $memoRepository,
        private UpsertSalaryEmployeeAction $upsertSalaryEmployeeAction
    ) {}

    public function index()
    {
        $this->authorize('memos.view');

        if (request()->expectsJson()) {
            $filter    = request('filter', 'active');
            $startWeek = request('start_week');
            $endWeek   = request('end_week');

            $memos = $this->memoRepository->getAll($filter, $startWeek, $endWeek);

            return MemoResource::collection($memos);
        }

        return view('memo.index');
    }

    public function create()
    {
        //
    }

    public function store(SaveMemoRequest $request)
    {
        $this->authorize('memos.create');

        $memo = Memo::create($request->validated());

        return new MemoResource($memo->load('employee'));
    }

    public function show(Memo $memo)
    {
        $this->authorize('memos.view');

        return new MemoResource($memo->load('employee'));
    }

    public function edit()
    {
        //
    }

    public function update(SaveMemoRequest $request, Memo $memo)
    {
        $this->authorize('memos.update');

        $salary = $memo->salaryEmployee;

        $memo->update($request->validated());

        if ($salary) {
            $this->upsertSalaryEmployeeAction->reopenIfPaid($salary);
        }

        return new MemoResource($memo->load('employee'));
    }

    public function destroy(Memo $memo)
    {
        $this->authorize('memos.delete');

        $salary = $memo->salaryEmployee;

        $memo->delete();

        if ($salary) {
            $this->upsertSalaryEmployeeAction->reopenIfPaid($salary);
        }

        return response()->noContent();
    }

    public function restore(int $id)
    {
        $this->authorize('memos.restore');

        $memo = Memo::onlyTrashed()->findOrFail($id);
        $memo->restore();

        if ($memo->salaryEmployee) {
            $this->upsertSalaryEmployeeAction->reopenIfPaid($memo->salaryEmployee);
        }

        return response()->json(['message' => 'Memo restored successfully']);
    }

    public function forceDelete(int $id)
    {
        $this->authorize('memos.forceDelete');

        $memo = Memo::onlyTrashed()->findOrFail($id);
        $memo->forceDelete();

        return response()->json(['message' => 'Memo permanently deleted']);
    }
}
