<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class CardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $cards = $this->getDataQuery($request)->paginate(10)->withQueryString();
        
        foreach ($cards as $key => $card) {
            $cards[$key] = [
                'id' => $card->id,
                'number' => $card->number,
                'collection' => $card->collection->name,
                'card_type' => $card->card_type->name,
                'url' => $card->url,
                'publisher' => $card->publisher->name ,
                'created_at' => $card->created_at->format('d/m/Y'),
            ];
        }
        
        return Inertia::render('Admin/Collections/Index', [
            'cards' => $cards,
            'filters' => $request->only('search', 'sort', 'direction'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Card $card)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Card $card)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Card $card)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Card $card)
    {
        try {
            $card->delete();
            
            return response()->json([
                'message' => 'Cromo eliminado correctamente',
                'status' => 'success',
            ], 200);
        } catch (\Throwable $e) {
            Log::error('Error al eliminar cromo: ' . $e->getMessage());
            
            return response()->json([
                'message' => 'Hubo un problema al eliminar el cromo',
                'status' => 'error',
            ], 500);
        }
    }
    
    public function getDataQuery(Request $request){
        $query = Card::query();
        
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('number', 'like', "%$search%");
        }
        
        if ($request->filled('sort') && in_array($request->input('sort'), ['number', 'id'])) {
            $query->orderBy($request->input('sort'), $request->input('direction') === 'desc' ? 'desc' : 'asc');
        }
        
        return $query;
    }
    
    public function showCardsCollection(Request $request, Collection $collection){
        
        $cards = $this->getDataQuery($request)->where('collection_id',$collection->id);
        $cards = $cards->paginate(5)->withQueryString();
        
        foreach ($cards as $key => $card) {
            $cards[$key] = [
                'id' => $card->id,
                'number' => $card->number,
                'card_type' => $card->cardType->name,
                'url' => $card->url_photo,
                'created_at' => $card->created_at->format('d/m/Y'),
            ];
        }
        
        return Inertia::render('Admin/Collections/ShowCards', [
            'cards' => $cards,
            'collection' => $collection,
            'filters' => $request->only('search', 'sort', 'direction'),
        ]);
        
    }
}
