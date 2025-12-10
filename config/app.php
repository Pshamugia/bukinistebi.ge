<?php

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\ServiceProvider;

return [

    'name' => env('APP_NAME', 'Laravel'),

    'env' => env('APP_ENV', 'production'),

    'debug' => (bool) env('APP_DEBUG', false),

    'url' => env('APP_URL', 'https://bukinistebi.ge/'),

    'asset_url' => env('ASSET_URL'),

    'timezone' => 'Asia/Tbilisi',

    'locale' => 'ka',

    'fallback_locale' => 'ka',

    'faker_locale' => 'ka_GE',

    'key' => env('APP_KEY'),

    'cipher' => 'AES-256-CBC',

    'maintenance' => [
        'driver' => 'file',
    ],

    /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded on the
    | request to your application. Feel free to add your own services to
    | this array to grant expanded functionality to your applications.
    |
    */

    'providers' => ServiceProvider::defaultProviders()->merge([
        /*
         * Package Service Providers...
         */
        Maatwebsite\Excel\ExcelServiceProvider::class, // For Excel
        Intervention\Image\ImageServiceProvider::class, // For Intervention Image

        /*
         * Application Service Providers...
         */

        Laravel\Socialite\SocialiteServiceProvider::class,

        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        // App\Providers\BroadcastServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class,
        Milon\Barcode\BarcodeServiceProvider::class,
        Barryvdh\DomPDF\ServiceProvider::class,


    ])->toArray(),

    /*
    |--------------------------------------------------------------------------
    | Class Aliases
    |--------------------------------------------------------------------------
    |
    | This array of class aliases will be registered when this application
    | is started. However, feel free to register as many as you wish as
    | the aliases are "lazy" loaded so they don't hinder performance.
    |
    */

    'aliases' => Facade::defaultAliases()->merge([
        'Socialite' => Laravel\Socialite\Facades\Socialite::class,
        'Excel' => Maatwebsite\Excel\Facades\Excel::class, // For Excel
        'Image' => Intervention\Image\Facades\Image::class, // For Intervention Image
        'DNS1D' => Milon\Barcode\Facades\DNS1DFacade::class,
        'DNS2D' => Milon\Barcode\Facades\DNS2DFacade::class,
          'PDF'       => Barryvdh\DomPDF\Facade\Pdf::class,


    ])->toArray(),
];
