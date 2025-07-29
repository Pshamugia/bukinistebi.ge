<?php

namespace App\Http\Middleware;

use Closure;

class CheckPostSize
{
    public function handle($request, Closure $next)
    {
        $max = ini_get('post_max_size');
        $maxBytes = $this->convertToBytes($max);

        if ($request->server('CONTENT_LENGTH') > $maxBytes) {
            return back()->withErrors([
                'file' => 'ფაილი ძალიან დიდია. დასაშვები მაქსიმალური ზომაა ' . $max,
            ])->withInput();
        }

        return $next($request);
    }

    protected function convertToBytes($value)
    {
        $unit = strtolower(substr($value, -1));
        $bytes = (int) $value;

        switch ($unit) {
            case 'g': $bytes *= 1024;
            case 'm': $bytes *= 1024;
            case 'k': $bytes *= 1024;
        }

        return $bytes;
    }
}
