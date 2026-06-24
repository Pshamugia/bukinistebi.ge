<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class SetLocale
{
    public function handle($request, Closure $next)
    {
        $supportedLocales = ['ka', 'en', 'ru'];

        if ($request->filled('lang') && in_array($request->get('lang'), $supportedLocales)) {
            session(['locale' => $request->get('lang')]);
        }

        $locale = session('locale', config('app.locale'));

        if (!in_array($locale, $supportedLocales)) {
            $locale = config('app.locale', 'ka');
            session(['locale' => $locale]);
        }

        App::setLocale($locale);

        return $next($request);
    }
}