<?php

namespace App\Http\Controllers\Admin;

use App\Models\BookNews;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class BookNewsController extends Controller
{
    public function index()
    {
        $news = BookNews::latest()->paginate(10);
        return view('admin.book-news.index', compact('news'));
    }

    public function create()
    {
        return view('admin.book-news.create');
    }

    
    
    
    public function store(Request $request)
    {
        try {
            Log::info('STORE triggered', $request->all());
    
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'full' => 'required|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
            ]);
    
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $uniqueFileName = time() . '_' . uniqid() . '.webp';
    
                $image = \Intervention\Image\Facades\Image::make($file)
                    ->resize(800, null, function ($constraint) {
                        $constraint->aspectRatio();
                    })
                    ->encode('webp', 75);
    
                $imagePath = 'uploads/book_news/' . $uniqueFileName;
                Storage::disk('public')->put($imagePath, $image);
    
                $validatedData['image'] = $imagePath;
            }
    
            \App\Models\BookNews::create($validatedData);
    
            return redirect()->route('admin.book-news.index')->with('success', 'Book News created successfully.');
        } catch (\Exception $e) {
            Log::error('Error in BookNewsController store: ' . $e->getMessage());
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }
    






    public function edit(BookNews $bookNews)
    {
        return view('admin.book-news.edit', compact('bookNews'));
    }

  

    public function update(Request $request, BookNews $bookNews)
{
    $validatedData = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'full' => 'required|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    if ($request->hasFile('image')) {
        $file = $request->file('image');

        // Delete old image if it exists
        if ($bookNews->image && Storage::disk('public')->exists($bookNews->image)) {
            Storage::disk('public')->delete($bookNews->image);
        }

        // Generate a unique file name with WebP extension
        $uniqueFileName = time() . '_' . uniqid() . '.webp';

        // Resize and convert to WebP
        $image = Image::make($file)
            ->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
            })
            ->encode('webp', 75); // Convert to WebP format

        // Save the WebP image
        $imagePath = 'uploads/book_news/' . $uniqueFileName;
        Storage::disk('public')->put($imagePath, $image);

        $validatedData['image'] = $imagePath; // Save the WebP image path in the database
    }

    $bookNews->update($validatedData);

    return redirect()->route('admin.book-news.index')->with('success', 'Book News updated successfully.');
}

    



    public function destroy(BookNews $bookNews)
    {
        if ($bookNews->image && Storage::disk('public')->exists('uploads/book_news/' . $bookNews->image)) {
            Storage::disk('public')->delete('uploads/book-news/' . $bookNews->image);
        }

        $bookNews->delete();
        return redirect()->route('admin.book-news.index')->with('success', 'Book News deleted successfully.');
    }

    
}
