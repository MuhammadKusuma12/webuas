<?php

namespace App\Http\Controllers;

use App\Models\KodeItem;
use Illuminate\Http\Request;
use Inertia\Inertia; 

class KodeItemController extends Controller
{
    
    public function index()
    {
    
        $kodeItems = KodeItem::latest()->get();

        return Inertia::render('KodeItems/Index', [
            'kodeItems' => $kodeItems
        ]);
    }


    public function store(Request $request)
    {
        KodeItem::create($request->all());

        return redirect()->route('kode-items.index');
    }

    
    public function update(Request $request, KodeItem $kodeItem)
    {
        $kodeItem->update($request->all());

        return redirect()->route('kode-items.index');
    }

    
    public function destroy(KodeItem $kodeItem)
    {
        $kodeItem->delete();

        return redirect()->route('kode-items.index');
    }

    
    public function create()
    {
        return redirect()->route('kode-items.index');
    }

    public function edit(KodeItem $kodeItem)
    {
        return redirect()->route('kode-items.index');
    }

    public function show(KodeItem $kodeItem)
    {
        return redirect()->route('kode-items.index');
    }
}