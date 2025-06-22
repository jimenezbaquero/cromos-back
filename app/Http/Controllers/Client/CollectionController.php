<?php

namespace App\Http\Controllers\Client;

use App\Filters\CollectionFilter;
use App\Headers\CollectionHeader;
use App\Helper\TransformHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\CollectionRequest;
use App\Models\Collection;
use App\Models\Publisher;
use App\Services\CollectionService;
use App\Services\ProductService;
use App\Services\PublisherService;
use App\Transformers\CardTransformer;
use App\Transformers\collectionTransformer;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class CollectionController extends Controller
{
    protected $collectionService;
    protected $publisherService;
    protected $productService;

    public function __construct(CollectionService $collectionService, PublisherService $publisherService, ProductService $productService )
    {
        $this->collectionService = $collectionService;
        $this->publisherService = $publisherService;
        $this->productService = $productService;
    }
    public function index(Request $request)
    {
        $products = $this->productService->getProducts();
        $collections = $this->collectionService->getCollectionsToClient($request);
        $collections = TransformHelper::transform(CollectionTransformer::class, $collections);

        return Inertia::render('Client/Collections/Index', [
            'collections' => $collections,
            'filters' => CollectionFilter::getFilters(),
            'headers' => CollectionHeader::getHeaders(),
            'funnels' => CollectionFilter::getFunnelOptions(),
            'products' => $products
        ]);
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

    public function getData(Request $request){
        $collections  = $this->getDataWithFilters($request);
        return response()->json($collections);
    }

    public function getDataWithFilters(Request $request){
        $collections  = $this->collectionService->getDataWithFilters($request->all());
        return TransformHelper::transform(CollectionTransformer::class, $collections);
    }

}
