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
        $locale = app()->getLocale();

        $news = BookNews::when($locale === 'en', function ($query) {
            return $query->whereNotNull('title_en');
        })
            ->when($locale !== 'en', function ($query) {
                return $query
                    ->where('title', '!=', 'წესები და პირობები')
                    ->where('title', '!=', 'ბუკინისტებისათვის');
            })
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
        $booknews = BookNews::findOrFail($id);

        // Determine the correct localized title for slug
        $locale = app()->getLocale();
        $actualTitle = $locale === 'en' && $booknews->title_en ? $booknews->title_en : $booknews->title;

        $slug = Str::slug($actualTitle);

        // Redirect if slug doesn't match locale-based title
        if ($slug !== $title) {
            return redirect()->route('full_news', ['title' => $slug, 'id' => $booknews->id]);
        }

        $isHomePage = false;

        return view('full_news', compact('booknews', 'isHomePage'));
    }
}
