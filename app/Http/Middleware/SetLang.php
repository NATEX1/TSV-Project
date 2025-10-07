<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLang
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {


        if (session()->has('locale')) {
            app()->setLocale(session('locale'));
            $lang = app()->getLocale();
        } else {
            app()->setLocale(config('app.locale'));
            $lang = config('app.locale');
        }

        view()->share('lang', $lang);

        return $next($request);
    }
}
