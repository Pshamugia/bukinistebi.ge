<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookNews; 
use App\Models\Author; 
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str; 
use Illuminate\Support\Facades\Auth;

class BookNewsController extends Controller
{
    public function index()
    {
        $news = BookNews::latest()->paginate(6);
        return view('book_news.index', compact('news'));
    }

    public function allbooksnews()
    {
        $news = BookNews::
        where('title', '!=', 'წესები და პირობები')
        ->where('title', '!=', 'ბუკინისტებისათვის')
        ->orderBy('id', 'DESC')
        ->paginate(10);
        return view('all_book_news', compact('news'));
    }

    public function show($id)
    {
        $book = BookNews::findOrFail($id);
        return view('book_news.show', compact('booknews'));
    }

    public function terms()
    {
        $terms = BookNews::where('title', 'წესები და პირობები')->first();
        $bukinistebisatvis = BookNews::where('title', 'ბუკინისტებისათვის')->first();

return view('terms_conditions', compact('terms', 'bukinistebisatvis'));

    }
    


    public function full_news($title, $id)
{
    // Fetch the news article by ID
    $booknews = BookNews::findOrFail($id);  // Change $book to $newsItem

    // Optionally, ensure the title in the URL matches the news item's actual title
    $slug = Str::slug($booknews->title);

    if ($slug !== $title) {
        // Redirect to the correct URL if the slug in the URL doesn't match the actual news title
        return redirect()->route('full_news', ['title' => $slug, 'id' => $booknews->id]);
    }

    $isHomePage = false;
    // Pass the newsItem to the view
    return view('full_news', compact('booknews', 'isHomePage'));
}
}
