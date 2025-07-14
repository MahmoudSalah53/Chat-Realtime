<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetUserTimezone
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Session::has('user-timezone')) {
            config(['app.timezone' => Session::get('user-timezone')]);
            date_default_timezone_set(Session::get('user-timezone'));
            Carbon::setLocale(config('app.locale'));
        }

        return $next($request);
    }
}
