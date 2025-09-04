<?php
 
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bundle;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Str; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class BundleController extends Controller
{
    public function index()
    {
        $bundles = Bundle::latest()->paginate(20);
        return view('admin.bundles.index', compact('bundles'));
    }

    public function create()
    {
        $books = Book::with('author:id,name') // eager-load author name
        ->where('hide', 0)
        ->where('language', app()->getLocale())
        ->orderBy('title')
        ->get(['id','title','price','quantity','author_id']); // include author_id!
    
        return view('admin.bundles.create', compact('books'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'         => ['required','string','max:255'],
            'slug'          => ['nullable','string','max:255','unique:bundles,slug'],
            'price'         => ['required','integer','min:0'],
            'active'        => ['nullable','boolean'],
            'description'   => ['nullable','string'],
            'image'         => ['nullable','image','mimes:jpg,jpeg,png,webp','max:2048'],
            'starts_at'     => ['nullable','date'],
            'ends_at'       => ['nullable','date','after_or_equal:starts_at'],
            'book_ids'      => ['required','array','min:2'],
            'book_ids.*'    => ['integer','exists:books,id'],
            'book_qtys'     => ['nullable','array'],
        ]);

        $bundle = Bundle::create([
            'title' => $data['title'],
            'slug'  => $data['slug'] ?? Str::slug($data['title']).'-'.Str::random(6),
            'price' => $data['price'],
            'active'=> $request->boolean('active'),
            'description' => $data['description'] ?? null,
            'image' => $data['image'] ?? null,
            'starts_at' => $data['starts_at'] ?? null,
            'ends_at'   => $data['ends_at'] ?? null,
            // original_price will be set after we attach books
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('bundles', 'public'); // storage/app/public/bundles
            $bundle->update(['image' => $path]); // save relative path e.g. bundles/abc.jpg
        }
        

        // Attach books with qty (default 1)
        $attach = [];
        foreach ($data['book_ids'] as $bookId) {
            $qty = max(1, (int)($data['book_qtys'][$bookId] ?? 1));
            $attach[$bookId] = ['qty' => $qty];
        }
        $bundle->books()->sync($attach);

        // compute original_price = sum(book.price * qty)
        $original = $bundle->books->sum(fn($b) => ($b->price ?? 0) * ($b->pivot->qty ?? 1));
        $bundle->update(['original_price' => $original]);

        return redirect()->route('admin.bundles.index')->with('success', 'Bundle created.');
    }

    public function edit(Bundle $bundle)
    {
        $books = Book::with('author:id,name')
            ->where('hide', 0)
            ->where('language', app()->getLocale())
            ->orderBy('title')
            ->get(['id','title','price','quantity','author_id']);
    
        return view('admin.bundles.edit', compact('bundle','books'));
    }
    

    public function update(Request $request, Bundle $bundle)
{
    $data = $request->validate([
        'title'         => ['required','string','max:255'],
        'slug'          => ['nullable','string','max:255','unique:bundles,slug,'.$bundle->id],
        'price'         => ['required','integer','min:0'],
        'active'        => ['nullable','boolean'],
        'description'   => ['nullable','string'],
        'image'         => ['nullable','image','mimes:jpg,jpeg,png,webp','max:2048'],
        'starts_at'     => ['nullable','date'],
        'ends_at'       => ['nullable','date','after_or_equal:starts_at'],
        'book_ids'      => ['required','array','min:2'],
        'book_ids.*'    => ['integer','exists:books,id'],
        'book_qtys'     => ['nullable','array'],
        // 'remove_image'   => ['nullable','boolean'], // optional: if you want a “remove image” checkbox
    ]);

    DB::transaction(function () use ($bundle, $request, $data) {
        // 1) Update non-file fields (do NOT touch 'image' here)
        $bundle->update([
            'title'       => $data['title'],
            'slug'        => $data['slug'] ?? $bundle->slug,
            'price'       => $data['price'],
            'active'      => $request->boolean('active'),
            'description' => $data['description'] ?? null,
            'starts_at'   => $data['starts_at'] ?? null,
            'ends_at'     => $data['ends_at'] ?? null,
        ]);

        // 2) Sync books & quantities
        $attach = [];
        foreach ($data['book_ids'] as $bookId) {
            $qty = max(1, (int)($data['book_qtys'][$bookId] ?? 1));
            $attach[$bookId] = ['qty' => $qty];
        }
        $bundle->books()->sync($attach);

        // 3) Recompute original price (ensure fresh relation)
        $bundle->load('books');
        $original = $bundle->books->sum(fn($b) => (int)($b->price ?? 0) * (int)($b->pivot->qty ?? 1));
        $bundle->original_price = $original;

        // 4) Handle image only if a new file is uploaded
        if ($request->hasFile('image')) {
            // delete old image if it existed
            if ($bundle->getOriginal('image')) {
                Storage::disk('public')->delete($bundle->getOriginal('image'));
            }
            $path = $request->file('image')->store('bundles', 'public');
            $bundle->image = $path;
        }

        // Optional: allow explicit removal via checkbox
        // if ($request->boolean('remove_image')) {
        //     if ($bundle->image) Storage::disk('public')->delete($bundle->image);
        //     $bundle->image = null;
        // }

        $bundle->save();
    });

    return redirect()->route('admin.bundles.index')->with('success', 'Bundle updated.');
}
    public function destroy(Bundle $bundle)
    {
        $bundle->delete();
        return back()->with('success','Bundle deleted.');
    }
}
