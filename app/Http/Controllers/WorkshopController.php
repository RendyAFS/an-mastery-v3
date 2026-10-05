<?php

namespace App\Http\Controllers;

use App\Actions\Workshop\SaveWorkshopAction;
use App\Http\Requests\Workshop\SaveWorkshopRequest;
use App\Http\Resources\WorkshopResource;
use App\Models\Workshop;
use App\Repositories\WorkshopRepository;
use Illuminate\Http\Request;

class WorkshopController extends Controller
{
    public function __construct(
        private WorkshopRepository $workshopRepository
    ) {}

    public function index()
    {
        $this->authorize('workshops.view');

        if (request()->expectsJson()) {
            $filter  = request('filter', 'active');
            $search  = request('search');
            $perPage = min((int) request('per_page', 12), 100);

            $workshops = $this->workshopRepository->getAll($filter, $search, $perPage);

            return WorkshopResource::collection($workshops);
        }

        return view('workshop.index');
    }

    public function create()
    {
        //
    }

    public function store(SaveWorkshopRequest $request, SaveWorkshopAction $action)
    {
        $this->authorize('workshops.create');

        $workshop = $action->handle($request);

        return new WorkshopResource($workshop);
    }

    public function show(Workshop $workshop)
    {
        $this->authorize('workshops.view');

        return new WorkshopResource($workshop->load('media'));
    }

    public function edit()
    {
        //
    }

    public function update(SaveWorkshopRequest $request, Workshop $workshop, SaveWorkshopAction $action)
    {
        $this->authorize('workshops.update');

        $workshop = $action->handle($request, $workshop);

        return new WorkshopResource($workshop);
    }

    public function destroy(Workshop $workshop)
    {
        $this->authorize('workshops.delete');

        $workshop->delete();

        return response()->noContent();
    }

    public function restore(int $id)
    {
        $this->authorize('workshops.restore');

        $workshop = Workshop::onlyTrashed()->findOrFail($id);

        $workshop->restore();

        return response()->json([
            'message' => __('crud.restored', ['model' => __('models.Workshop')])
        ]);
    }

    public function forceDelete(int $id)
    {
        $this->authorize('workshops.forceDelete');

        $workshop = Workshop::onlyTrashed()->findOrFail($id);

        $workshop->forceDelete();

        return response()->json([
            'message' => __('crud.force_deleted', ['model' => __('models.Workshop')])
        ]);
    }

    public function select(Request $request)
    {
        $workshops = $this->workshopRepository->getDataSelect(
            search: $request->search,
            id: $request->id,
            limit: $request->limit ?? 10,
            page: $request->page ?? 1
        );

        return response()->json([
            'count'   => $workshops->total(),
            'results' => $workshops->getCollection()->map(fn($item) => [
                'id'   => $item->id,
                'name' => $item->name,
                'page' => $workshops->currentPage(),
            ]),
        ]);
    }

    public function activeList()
    {
        $workshops = Workshop::active()
            ->with('media')
            ->orderBy('name')
            ->get()
            ->map(fn($w) => [
                'id'         => $w->id,
                'name'       => $w->name,
                'location'   => $w->location,
                'image_url'  => $w->getFirstMediaUrl('workshop-image'),
                'initials'   => strtoupper(substr($w->name, 0, 2)),
                'is_current' => auth()->user()?->workshop_id === $w->id,
            ]);

        return response()->json([
            'workshops'           => $workshops,
            'current_workshop_id' => auth()->user()?->workshop_id,
        ]);
    }

    public function switchWorkshop(Request $request)
    {
        $validated = $request->validate([
            'workshop_id' => ['required', 'exists:workshops,id,deleted_at,NULL,is_active,1'],
        ]);

        $user = auth()->user();
        $user->update([
            'workshop_id' => $validated['workshop_id'],
        ]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success'  => true,
                'message'  => __('workshop.switched_successfully'),
                'workshop' => $user->fresh()->workshop ? [
                    'id'   => $user->fresh()->workshop->id,
                    'name' => $user->fresh()->workshop->name,
                ] : null,
            ]);
        }

        return back()->with('success', __('workshop.switched_successfully'));
    }
}
