<?php

namespace App\Providers;

use App\Models\Genre;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    
    public function boot()
    {
        // ✅ LANG URL BLADE DIRECTIVE
        Blade::directive('langurl', function ($expression) {
            return "<?php
                \$__url = {$expression};
                \$__lang = request('lang');

                echo \$__lang
                    ? (str_contains(\$__url, '?')
                        ? \$__url . '&lang=' . \$__lang
                        : \$__url . '?lang=' . \$__lang)
                    : \$__url;
            ?>";
        });

        // ✅ GLOBAL VIEW COMPOSER
        view()->composer('*', function ($view) {

            // Page view logging
            DB::table('page_views')->insert([
                'url' => request()->url(),
                'ip_address' => request()->ip(),
                'created_at' => now(),
            ]);

            // Georgian alphabet sort
            $georgianAlphabet = [
                'ა','ბ','გ','დ','ე','ვ','ზ','თ','ი','კ','ლ','მ','ნ','ო','პ',
                'ჟ','რ','ს','ტ','უ','ფ','ქ','ღ','ყ','შ','ჩ','ც','ძ','წ','ჭ',
                'ხ','ჯ','ჰ'
            ];

            $genres = Genre::all()->sortBy(function ($genre) use ($georgianAlphabet) {
                $cleaned = preg_replace('/[^ა-ჰ]/u', '', $genre->name);
                $firstLetter = mb_substr($cleaned, 0, 1, 'UTF-8');
                $index = array_search($firstLetter, $georgianAlphabet);
                return $index === false ? 999 : $index;
            })->values();

            $view->with('genres', $genres);
        });
    }
}