<?php

namespace App\Http\Controllers\Admin;

use App\Filters\PublisherFilter;
use App\Headers\PublisherHeader;
use App\Helper\TransformHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\PublisherRequest;
use App\Models\Collection;
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
        $publishers = $this->getDataWithFilters($request);

        return Inertia::render('Admin/Publishers/Index', [
            'publishers' => $publishers,
            'filters' => PublisherFilter::getFilters(),
            'headers' => PublisherHeader::getHeaders(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Publishers/Create');
    }

    public function store(PublisherRequest $request)
    {
        try {
            $this->publisherService->store($request->all());
            return redirect()->route('admin.publishers.index')->with('success', 'publisher_create_success');
        }catch (\Throwable $e) {
            return back()->withErrors(['error' => __('publisher_create_error')])->withInput();
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
        try {
            $this->publisherService->update($publisher, $request->all());
            return redirect()->route('admin.publishers.index')->with('success', __('publisher_update_success'));
        }catch (\Throwable $e) {
            return back()->withErrors(['error' => __('publisher_update_error')]);
        }
    }
    
    public function destroy(Publisher $publisher)
    {
        try {
            $this->publisherService->destroy($publisher);
            return redirect()->route('publishers.index')->with('success', __('publisher_delete_success'));
        }catch (\Throwable $e){
            return back()->withErrors(['error' => __('publisher_delete_error')]);
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
