<?php

namespace App\Http\Controllers\Admin;

use App\Filters\CollectionFilter;
use App\Headers\CollectionHeader;
use App\Helper\TransformHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\CollectionRequest;
use App\Models\Collection;
use App\Models\Publisher;
use App\Services\CollectionService;
use App\Services\PublisherService;
use App\Transformers\CardTransformer;
use App\Transformers\collectionTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class CollectionController extends Controller
{
    protected $collectionService;
    protected $publisherService;

    public function __construct(CollectionService $collectionService, PublisherService $publisherService )
    {
        $this->collectionService = $collectionService;
        $this->publisherService = $publisherService;
    }
    public function index(Request $request)
    {
        $filters = CollectionFilter::getFilters();
        $headers = CollectionHeader::getHeaders();
        $collections = $this->getDataWithFilters($request);

        return Inertia::render('Admin/Collections/Index', [
            'collections' => $collections,
            'filters' => $filters,
            'headers' => $headers,
            'funnels' => CollectionFilter::getFunnelOptions(),
        ]);
    }

    public function create()
    {
        $publishers = Publisher::all(['id', 'name']);

        return Inertia::render('Admin/Collections/Create', [
            'publishers' => $publishers,
        ]);
    }

    public function store(CollectionRequest $request)
    {
        $data = $request->validated();

        DB::beginTransaction();
        try {
            $collection = Collection::create($data);
            DB::commit();

            return redirect()->route('admin.collections.index')->with('success', 'Colección creada correctamente');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error al crear colección: ' . $e->getMessage(), [
                'stack' => $e->getTraceAsString(),
                'input' => $request->all(),
            ]);

            return back()->withErrors(['error' => 'Hubo un problema al crear la colección'])->withInput();
        }
    }

    public function show(Collection $collection)
    {
        $collection->load('publisher');

        return Inertia::render('Admin/Collections/Show', [
            'collection' => [
                'id' => $collection->id,
                'name' => $collection->name,
                'description' => $collection->description,
                'year' => $collection->year,
                'publisher' => $collection->publisher ? $collection->publisher->name : '---',
                'card_number' => $collection->cards()->count(),
                'created_at' => $collection->created_at->format('d/m/Y'),
            ],
        ]);
    }

    public function edit(Collection $collection)
    {
        $publishers = Publisher::all(['id', 'name']);

        return Inertia::render('Admin/Collections/Edit', [
            'collection' => $collection,
            'publishers' => $publishers,
        ]);
    }

    public function update(CollectionRequest $request, Collection $collection)
    {
        $data = $request->all();

        DB::beginTransaction();
        try {
            $collection->update($data);
            DB::commit();

            return redirect()->route('admin.collections.index')
                ->with('success', 'Colección actualizada correctamente');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error al actualizar colección: ' . $e->getMessage());

            return back()->withErrors(['error' => 'Hubo un problema al actualizar la colección']);
        }
    }

    public function destroy(Collection $collection)
    {
        try {
            $collection->delete();

            return redirect()->route('admin.collections.index')
                ->with('success', 'Colección eliminada correctamente');
        } catch (\Throwable $e) {
            Log::error('Error al eliminar colección: ' . $e->getMessage());

            return back()->withErrors(['error' => 'Hubo un problema al eliminar la colección']);
        }
    }

    public function getData(Request $request){
        $collections  = $this->getDataWithFilters($request);
        return response()->json($collections);
    }

    public function getDataWithFilters(Request $request){
        $collections  = $this->collectionService->getDataWithFilters($request->all());
        return TransformHelper::transform(CollectionTransformer::class, $collections);
    }

}
