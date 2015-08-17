<?php namespace App\Http\Middleware\Auth;

use Closure;
use Illuminate\Contracts\Routing\Middleware;

class GuestMiddleware implements Middleware {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (\Sentry::check()) {
            \Notification::error('already_logged_in');
            return \Redirect::to('home');
        }
        return $next($request);
    }

}
