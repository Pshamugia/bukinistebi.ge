<?php

namespace App\Http\Controllers;

use App\Models\Bundle;

class BundleFrontController extends Controller
{
    public function index()
    {
        $bundles = Bundle::with('books')->active()->latest()->paginate(12);
        return view('bundles.index', compact('bundles'));
    }

    public function show(string $slug)
    {
        $bundle = Bundle::with(['books.author'])->active()->where('slug',$slug)->firstOrFail();
        $available = $bundle->availableQuantity();
        return view('bundles.show', compact('bundle','available'));
    }


    
}
