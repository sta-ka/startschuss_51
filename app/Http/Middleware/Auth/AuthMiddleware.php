<?php namespace App\Http\Middleware\Auth;

use Closure;

class AuthMiddleware {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next, $role)
	{
		$user = \Sentry::getUser();

		if (! \Sentry::check()) {
			\Notification::error('access_denied');
			return \Redirect::to('home');
		}

        if ($role) {

            if (! $user->inGroup(\Sentry::findGroupByName($role))) {
                \Notification::error('access_denied');
                return \Redirect::to('home');
            }

        }

		return $next($request);
	}

}
