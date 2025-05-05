<?php

namespace App\Providers;

use App\Models\Genre;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    
     public function boot()
     {
         view()->composer('*', function ($view) {
             // Log page views
             DB::table('page_views')->insert([
                 'url' => request()->url(),
                 'ip_address' => request()->ip(),
                 'created_at' => now(),
             ]);
     
             // Share genres globally to all views (like navbar, filters, etc.)
             $georgianAlphabet = [
                'ა','ბ','გ','დ','ე','ვ','ზ','თ','ი','კ','ლ','მ','ნ','ო','პ',
                'ჟ','რ','ს','ტ','უ','ფ','ქ','ღ','ყ','შ','ჩ','ც','ძ','წ','ჭ',
                'ხ','ჯ','ჰ'
            ];
            
            $genres = \App\Models\Genre::all()->sortBy(function ($genre) use ($georgianAlphabet) {
                $cleaned = preg_replace('/[^ა-ჰ]/u', '', $genre->name);
                $firstLetter = mb_substr($cleaned, 0, 1, 'UTF-8');
                $index = array_search($firstLetter, $georgianAlphabet);
                return $index === false ? 999 : $index;
            })->values();
            
            $view->with('genres', $genres);
         });
     }
     
}
