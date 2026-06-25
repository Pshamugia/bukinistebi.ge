<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Publishing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PublishingController extends Controller
{
    public function index()
    {
        $items = Publishing::latest()->paginate(20);

        return view('admin.publishing.index', compact('items'));
    }

    public function create()
    {
        return view('admin.publishing.create', ['item' => new Publishing()]);
    }

    public function store(Request $request)
    {
        Publishing::create($this->validatedData($request));

        return redirect()->route('admin.publishing.index')
            ->with('success', 'Publishing ჩანაწერი წარმატებით დაემატა.');
    }

    public function edit(Publishing $publishing)
    {
        return view('admin.publishing.edit', ['item' => $publishing]);
    }

    public function update(Request $request, Publishing $publishing)
    {
        $publishing->update($this->validatedData($request, $publishing));

        return redirect()->route('admin.publishing.index')
            ->with('success', 'Publishing ჩანაწერი წარმატებით განახლდა.');
    }

    public function destroy(Publishing $publishing)
    {
        foreach (['image_1', 'image_2', 'image_3', 'image_4'] as $image) {
            if ($publishing->{$image}) {
                Storage::disk('public')->delete($publishing->{$image});
            }
        }

        $publishing->delete();

        return redirect()->route('admin.publishing.index')
            ->with('success', 'Publishing ჩანაწერი წაიშალა.');
    }

    private function validatedData(Request $request, ?Publishing $publishing = null): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'shop_url' => ['nullable', 'url', 'max:255'],
            'description' => ['nullable', 'string'],
            'image_1' => ['nullable', 'image', 'max:4096'],
            'image_2' => ['nullable', 'image', 'max:4096'],
            'image_3' => ['nullable', 'image', 'max:4096'],
            'image_4' => ['nullable', 'image', 'max:4096'],
        ]);

        foreach (['image_1', 'image_2', 'image_3', 'image_4'] as $image) {
            if ($request->hasFile($image)) {
                if ($publishing?->{$image}) {
                    Storage::disk('public')->delete($publishing->{$image});
                }

                $data[$image] = $request->file($image)->store('publishing', 'public');
            } else {
                unset($data[$image]);
            }
        }

        return $data;
    }
}
