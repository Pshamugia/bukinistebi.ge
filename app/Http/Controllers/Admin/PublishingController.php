<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Publishing;
use Illuminate\Http\Request;

class PublishingController extends Controller
{
    public function index()
    {
        $items = Publishing::latest()->get();
        return view('admin.publishing.index', compact('items'));
    }

    public function create()
    {
        return view('admin.publishing.create');
    }

    public function store(Request $request)
    {
        $data = $request->all();

        foreach (['image_1', 'image_2', 'image_3', 'image_4'] as $img) {
            if ($request->hasFile($img)) {
                $data[$img] = $request->file($img)->store('publishing', 'public');
            }
        }

        Publishing::create($data);

        return redirect()->route('admin.publishing.index');
    }

    public function edit($id)
    {
        $item = Publishing::findOrFail($id);

        return view('admin.publishing.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = Publishing::findOrFail($id);

        $data = $request->all();

        foreach (['image_1', 'image_2', 'image_3', 'image_4'] as $img) {
            if ($request->hasFile($img)) {
                $data[$img] = $request->file($img)->store('publishing', 'public');
            }
        }

        $item->update($data);

        return redirect()->route('admin.publishing.index');
    }
}