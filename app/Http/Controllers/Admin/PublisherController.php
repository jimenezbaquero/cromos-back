<?php

namespace App\Http\Controllers\Admin;

use App\Filters\PublisherFilter;
use App\Headers\PublisherHeader;
use App\Helper\TransformHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\PublisherRequest;
use App\Models\Publisher;
use App\Services\PublisherService;
use App\Transformers\CardTransformer;
use App\Transformers\PublisherTransformer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Carbon\Carbon;

class PublisherController extends Controller
{
    protected $publisherService;

    public function __construct(PublisherService $publisherService)
    {
        $this->publisherService = $publisherService;
    }

    public function index(Request $request)
    {
        $filters = PublisherFilter::getFilters();
        $headers = PublisherHeader::getHeaders();

        $publishers = $this->getDataWithFilters($request);

        return Inertia::render('Admin/Publishers/Index', [
            'publishers' => $publishers,
            'filters' => $filters,
            'headers' => $headers,
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Publishers/Create');
    }

    public function store(PublisherRequest $request)
    {
        $data = $request->validated();

        DB::beginTransaction();
        try {
            Publisher::create($data);
            DB::commit();
            return redirect()->route('admin.publishers.index')->with('success', 'Editorial creada correctamente');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error al crear editorial: ' . $e->getMessage(), [
                'stack' => $e->getTraceAsString(),
                'input' => $request->all(),
            ]);
            return back()->withErrors(['error' => 'Hubo un problema al crear la editorial'])->withInput();
        }
    }

    public function edit(Publisher $publisher)
    {
        return Inertia::render('Admin/Publishers/Edit', [
            'publisher' => $publisher,
        ]);
    }

    public function update(PublisherRequest $request, Publisher $publisher)
    {
        $data = $request->validated();

        DB::beginTransaction();
        try {
            $publisher->update($data);
            DB::commit();
            return redirect()->route('admin.publishers.index')->with('success', 'Editorial actualizada correctamente');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error al actualizar editorial: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Hubo un problema al actualizar la editorial']);
        }
    }

    public function getData(Request $request){
        $publishers = $this->getDataWithFilters($request);
        return response()->json($publishers);
    }

    public function getDataWithFilters(Request $request){
        $publishers  = $this->publisherService->getDataWithFilters($request->all());
        return TransformHelper::transform(PublisherTransformer::class, $publishers);
    }
}
