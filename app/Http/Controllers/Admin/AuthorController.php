<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function index()
    {
        $authors = Author::all();
        return view('admin.authors.index', compact('authors'));
    }

    public function create()
    {
        return view('admin.authors.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'name_ru' => 'nullable|string|max:255',

        ]);

Author::create(
    $request->only('name', 'name_en', 'name_ru')
);

        return redirect()->route('admin.authors.index')->with('success', 'Author created successfully.');
    }



    public function edit(Author $author)
    {
        return view('admin.authors.edit', compact('author'));
    }


   public function quickStore(Request $request)
{
    $request->validate([
        'new_author_name' => ['required','string','max:255'],
        'lang'            => ['nullable','in:ka,en,ru'],
    ]);

    $lang = $request->input('lang', app()->getLocale());
    $name = trim($request->input('new_author_name'));

    $column = match ($lang) {
        'en' => 'name_en',
        'ru' => 'name_ru',
        default => 'name',
    };

    $existing = Author::whereRaw(
        "LOWER($column) = ?",
        [mb_strtolower($name)]
    )->first();

    if ($existing) {
        return response()->json([
            'success' => true,
            'created' => false,
            'author'  => $existing,
        ]);
    }

    $author = new Author();
    $author->$column = $name;
    $author->save();

    return response()->json([
        'success' => true,
        'created' => true,
        'author'  => $author,
    ], 201);
}




    public function update(Request $request, Author $author)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
        ]);

$author->update(
    $request->only('name', 'name_en', 'name_ru')
);

        return redirect()->route('admin.authors.index')->with('success', 'Author updated successfully.');
    }


    public function destroy(Author $author)
    {
        $author->delete();

        return redirect()->route('admin.authors.index')->with('success', 'Author deleted successfully.');
    }
}
