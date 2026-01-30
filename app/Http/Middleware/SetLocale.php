<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    public function handle($request, Closure $next)
{
    if ($request->has('lang')) {
        session(['locale' => $request->get('lang')]);
    }

    app()->setLocale(
        session('locale', config('app.locale'))
    );

    return $next($request);
}

}
