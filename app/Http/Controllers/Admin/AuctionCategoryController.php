<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\AuctionCategory;
use App\Http\Controllers\Controller;

class AuctionCategoryController extends Controller
{
    public function index()
    {
        $categories = AuctionCategory::orderBy('name')->get();
        return view('admin.auction-categories.index', compact('categories'));
    }

    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255|unique:auction_categories,name',
    ]);

    AuctionCategory::create([
        'name' => $request->name,
        'slug' => Str::slug($request->name),
    ]);

    return back()->with('success', 'კატეგორია დამატებულია');
}

    public function update(Request $request, AuctionCategory $auctionCategory)
{
    $request->validate([
        'name' => 'required|string|max:255|unique:auction_categories,name,' . $auctionCategory->id,
    ]);

    $auctionCategory->update([
        'name' => $request->name,
        'slug' => Str::slug($request->name),
    ]);

    return back()->with('success', 'კატეგორია განახლდა');
}

    public function destroy(AuctionCategory $auctionCategory)
    {
        $auctionCategory->delete();
        return back()->with('success', 'კატეგორია წაიშალა');
    }
}
