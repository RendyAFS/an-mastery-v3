<?php

namespace App\Http\Controllers;

use App\Http\Requests\TypeColor\SaveTypeColorRequest;
use App\Http\Resources\TypeColorResource;
use App\Models\TypeColor;
use App\Repositories\TypeColorRepository;
use Illuminate\Http\Request;

class TypeColorController extends Controller
{
    public function __construct(
        private TypeColorRepository $typeColorRepository
    ) {}

    public function index()
    {
        $this->authorize('type-colors.view');

        if (request()->expectsJson()) {

            $filter = request('filter', 'active');

            $typeColors = $this->typeColorRepository->getAll($filter);

            return TypeColorResource::collection($typeColors);
        }

        return view('type-color.index');
    }

    public function create()
    {
        //
    }

    public function store(SaveTypeColorRequest $request)
    {
        $this->authorize('type-colors.create');

        $typeColor = TypeColor::create($request->validated());

        return new TypeColorResource($typeColor);
    }

    public function show(TypeColor $typeColor)
    {
        $this->authorize('type-colors.view');

        return new TypeColorResource($typeColor);
    }

    public function edit()
    {
        //
    }

    public function update(SaveTypeColorRequest $request, TypeColor $typeColor)
    {
        $this->authorize('type-colors.update');

        $typeColor->update($request->validated());

        return new TypeColorResource($typeColor);
    }

    public function destroy(TypeColor $typeColor)
    {
        $this->authorize('type-colors.delete');

        $typeColor->delete();

        return response()->noContent();
    }

    public function restore(int $id)
    {
        $this->authorize('type-colors.restore');

        $typeColor = TypeColor::onlyTrashed()->findOrFail($id);

        $typeColor->restore();

        return response()->json([
            'message' => 'Type Color restored successfully'
        ]);
    }

    public function forceDelete(int $id)
    {
        $this->authorize('type-colors.forceDelete');

        $typeColor = TypeColor::onlyTrashed()->findOrFail($id);

        $typeColor->forceDelete();

        return response()->json([
            'message' => 'Type Color permanently deleted'
        ]);
    }
}
