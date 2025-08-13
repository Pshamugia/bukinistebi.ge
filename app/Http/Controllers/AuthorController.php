<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthorController extends Controller
{
    /**
     * Display a listing of the authors.
     */
    public function index()
    {
        $authors = Author::paginate(10);
        return view('authors.index', compact('authors'));
    }

    /**
     * Show the form for creating a new author.
     */
    public function create()
    {
        return view('authors.create');
    }

    /**
     * Store a newly created author in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:authors,name',
        ]);

        Author::create($request->all());

        return redirect()->route('authors.index')->with('success', 'Author created successfully.');
    }

    /**
     * Display the specified author.
     */
    public function show(Author $author)
    {
        return view('authors.show', compact('author'));
    }

    /**
     * Show the form for editing the specified author.
     */
    public function edit(Author $author)
    {
        return view('authors.edit', compact('author'));
    }

    /**
     * Update the specified author in storage.
     */
    public function update(Request $request, Author $author)
    {
        $request->validate([
            'name' => 'required|unique:authors,name,' . $author->id,
        ]);

        $author->update($request->all());

        return redirect()->route('authors.index')->with('success', 'Author updated successfully.');
    }

    /**
     * Remove the specified author from storage.
     */
    public function destroy(Author $author)
    {
        $author->delete();
        return redirect()->route('authors.index')->with('success', 'Author deleted successfully.');
    }

    public function full_author(Request $request, $name, $id)
{
    $author = Author::findOrFail($id);

    $books = Book::with('author')
        ->where('author_id', $author->id)
        ->where('hide', 0) // <— only visible books
        ->when($request->boolean('exclude_sold'), fn ($q) => $q->where('quantity', '>', 0))
        ->orderByDesc('created_at')
        ->paginate(12)
        ->withQueryString(); // keep ?exclude_sold=1 on next pages

    $cartItemIds = (Auth::check() && Auth::user()->cart)
        ? Auth::user()->cart->cartItems->pluck('book_id')->toArray()
        : [];

    $isHomePage = false;

    return view('full_author', compact('author', 'books', 'cartItemIds', 'isHomePage'));
}
    
}
