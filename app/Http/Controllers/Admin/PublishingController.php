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
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'category' => ['nullable', 'string', 'max:255'],
            'shop_url' => ['nullable', 'url', 'max:255'],
            'image_1' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'image_2' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'image_3' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'image_4' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        foreach (['image_1', 'image_2', 'image_3', 'image_4'] as $img) {
            if ($request->hasFile($img)) {
                $data[$img] = $request->file($img)->store('publishing', 'public');
            } else {
                unset($data[$img]);
            }
        }

        Publishing::create($data);

        return redirect()
            ->route('admin.publishing.index')
            ->with('success', 'Publishing item created successfully.');
    }

    public function edit($id)
    {
        $item = Publishing::findOrFail($id);

        return view('admin.publishing.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = Publishing::findOrFail($id);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'category' => ['nullable', 'string', 'max:255'],
            'shop_url' => ['nullable', 'url', 'max:255'],
            'image_1' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'image_2' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'image_3' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'image_4' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        foreach (['image_1', 'image_2', 'image_3', 'image_4'] as $img) {
            if ($request->hasFile($img)) {
                $data[$img] = $request->file($img)->store('publishing', 'public');
            } else {
                unset($data[$img]);
            }
        }

        $item->update($data);

        return redirect()
            ->route('admin.publishing.index')
            ->with('success', 'Publishing item updated successfully.');
    }
}