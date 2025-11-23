<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureProfileIsComplete
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user->phone || !$user->address) {
            return redirect()->route('account.edit')->with('error', 'აუცილებელია მიუთითოთ თქვენი ტელეფონის ნომერი და მისამართი აუქციონში მონაწილეობის მისაღებად. საიტის მარჯვენა მხარეს დააკლიკეთ, შემდეგ შედით პრიოფილის რედაქტირებაში და შეავსეთ ეს ორი ველი.');
        }

        return $next($request);
    }
}

