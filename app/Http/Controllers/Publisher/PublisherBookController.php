<?php

namespace App\Http\Controllers\Publisher;

use App\Models\Book;
use App\Models\Genre;
use App\Models\Author;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use App\Mail\SubscriptionNotification;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;


class PublisherBookController extends Controller
{


    public function create(Request $request)
    {
        $locale = $request->get('lang', app()->getLocale());
        app()->setLocale($locale); // set locale dynamically for consistency

        $authors = Author::query();
        $genres = Genre::query();

        if ($locale === 'en') {
            $authors = $authors->whereNotNull('name_en');
            $genres = $genres->whereNotNull('name_en');
        } else {
            $authors = $authors->whereNotNull('name');
            $genres = $genres->whereNotNull('name');
        }

        $authors = $authors->get();
        $genres = $genres->get();
        $isHomePage = false;

        return view('publisher.create', compact('authors', 'genres', 'isHomePage', 'locale'));
    }





    public function store(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
        'title'           => 'required|string|max:255',
        'price'           => 'required|numeric|min:0',
        'photo'           => 'nullable|image|mimetypes:image/jpeg,image/png,image/jpg,image/gif,image/svg,image/webp|max:5120',
        'photo_2'         => 'nullable|image|mimetypes:image/jpeg,image/png,image/jpg,image/gif,image/svg,image/webp|max:5120',
        'photo_3'         => 'nullable|image|mimetypes:image/jpeg,image/png,image/jpg,image/gif,image/svg,image/webp|max:5120',
        'photo_4'         => 'nullable|image|mimetypes:image/jpeg,image/png,image/jpg,image/gif,image/svg,image/webp|max:5120',
        'description'     => 'required|string',
        'quantity'        => 'required|integer|min:1',
        'full'            => 'nullable|string',
        'author_id'       => 'required|exists:authors,id',
        'genre_id'        => 'nullable|array',
        'genre_id.*'      => 'exists:genres,id',
        'status'          => 'nullable|string|max:255',
        'pages'           => 'nullable|integer|min:1',
        'publishing_date' => 'nullable|string|max:50',
        'cover'           => 'required|string|max:255',
        'language'        => 'required|in:ka,en',
    ]);

    if ($validator->fails()) {
        return back()->withErrors($validator)->withInput();
    }

    $validatedData = $validator->validated();

    // --- image handling (unchanged) ---
    foreach (['photo', 'photo_2', 'photo_3', 'photo_4'] as $key) {
        if ($request->hasFile($key)) {
            $file = $request->file($key);
            $uniqueFileName = time() . '_' . uniqid() . '.webp';
            $image = \Intervention\Image\Facades\Image::make($file)
                ->resize(800, null, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->encode('webp', 75);

            $imagePath = 'uploads/books/' . $uniqueFileName;
            \Illuminate\Support\Facades\Storage::disk('public')->put($imagePath, $image);
            $validatedData[$key] = $imagePath;
        }
    }

    // add uploader / language / hide
    $validatedData['uploader_id'] = auth()->id();
    $validatedData['language'] = $request->language;
    $validatedData['hide'] = auth()->user()->role === 'publisher' ? '1' : '0';

    // remove genre_id from mass-assign
    $bookData = $validatedData;
    unset($bookData['genre_id']);

    $book = \App\Models\Book::create($bookData);

    if ($request->filled('genre_id')) {
        $book->genres()->sync($request->genre_id);
    }

    \Illuminate\Support\Facades\Cache::forget('home_books');
    \Illuminate\Support\Facades\Cache::forget('popular_books');
    \Illuminate\Support\Facades\Cache::forget('top_books');

    return redirect()
        ->route('publisher.dashboard')
        ->with('success', 'წიგნი წარმატებით აიტვირთა. მოდერაციის გავლის შემდეგ ის გამოჩნდება ჩვენს ვებსაიტზე');
}












    protected function notifySubscribers($book)
    {
        $subscribers = Subscriber::all();
        $subjectLine = 'გამოგვწერეთ ახალი დამატებული წიგნი';
        $messageContent = "ბუკინისტებში დაემატა ახალი წიგნი: '{$book->title}'.";

        foreach ($subscribers as $subscriber) {
            $encryptedEmail = Crypt::encryptString($subscriber->email);

            Mail::to($subscriber->email)->send(
                new \App\Mail\SubscriptionNotification($subjectLine, $messageContent, $encryptedEmail)
            );
        }
    }




    public function myBooks()
    {
        // Retrieve books uploaded by the authenticated publisher
        $books = Book::where('uploader_id', Auth::id())->orderBy('created_at', 'DESC')->get();
        $isHomePage = false;
        // Return view with the books data
        return view('publisher.books.my_books', compact('books', 'isHomePage'));
    }
}
