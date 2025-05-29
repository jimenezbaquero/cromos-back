<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PublisherRequest;
use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Carbon\Carbon;

class PublisherController extends Controller
{
    public function index(Request $request)
    {
        $query = Publisher::query();
        
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%$search%");
        }
        
        if ($request->filled('sort') && in_array($request->input('sort'), ['name', 'id'])) {
            $query->orderBy($request->input('sort'), $request->input('direction') === 'desc' ? 'desc' : 'asc');
        }
        
        $publishers = $query->paginate(10)->withQueryString();
        
        $data = [];
        foreach ($publishers as $key=>$publisher) {
            
            $publishers[$key] = [
                'id' => $publisher->id,
                'name' => $publisher->name,
                'created_at' => Carbon::parse($publisher->created_at)->format('d/m/Y'),
            ];
        }
        
        return Inertia::render('Admin/Publishers/Index', [
            'publishers' => $publishers,
            'filters' => $request->only('search', 'sort', 'direction'),
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
}
