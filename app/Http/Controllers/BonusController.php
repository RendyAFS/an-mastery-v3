<?php

namespace App\Http\Controllers;

use App\Http\Requests\Bonus\SaveBonusRequest;
use App\Http\Resources\BonusResource;
use App\Models\Bonus;
use App\Repositories\BonusRepository;

class BonusController extends Controller
{
    public function __construct(
        private BonusRepository $bonusRepository
    ) {}

    public function index()
    {
        $this->authorize('bonuses.view');

        if (request()->expectsJson()) {
            $filter = request('filter', 'active');
            $bonuses = $this->bonusRepository->getAll($filter);

            return BonusResource::collection($bonuses);
        }

        return view('bonus.index');
    }

    public function create()
    {
        //
    }

    public function store(SaveBonusRequest $request)
    {
        $this->authorize('bonuses.create');

        $bonus = Bonus::create($request->validated());

        return new BonusResource($bonus);
    }

    public function show(Bonus $bonus)
    {
        $this->authorize('bonuses.view');

        return new BonusResource($bonus);
    }

    public function edit()
    {
        //
    }

    public function update(SaveBonusRequest $request, Bonus $bonus)
    {
        $this->authorize('bonuses.update');

        $bonus->update($request->validated());

        return new BonusResource($bonus);
    }

    public function destroy(Bonus $bonus)
    {
        $this->authorize('bonuses.delete');

        $bonus->delete();

        return response()->noContent();
    }

    public function restore(int $id)
    {
        $this->authorize('bonuses.restore');

        $bonus = Bonus::onlyTrashed()->findOrFail($id);
        $bonus->restore();

        return response()->json([
            'message' => __('crud.restored', ['model' => __('models.Bonus')]),
        ]);
    }

    public function forceDelete(int $id)
    {
        $this->authorize('bonuses.forceDelete');

        $bonus = Bonus::onlyTrashed()->findOrFail($id);
        $bonus->forceDelete();

        return response()->json([
            'message' => __('crud.force_deleted', ['model' => __('models.Bonus')]),
        ]);
    }
}
