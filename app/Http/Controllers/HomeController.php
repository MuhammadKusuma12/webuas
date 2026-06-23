<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Item;

class HomeController extends Controller
{

    public function index()
    {
        $ikanKoi = Item::where('stok', '>', 0)->get();

        return Inertia::render('Katalog', [
           'katalogIkan' => $ikanKoi
        ]);
    }
}