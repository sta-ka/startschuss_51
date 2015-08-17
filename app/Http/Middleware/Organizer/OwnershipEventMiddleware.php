<?php namespace App\Http\Middleware\Organizer;

use Closure;
use App\Eloquent\User\UserRepositoryInterface as Users;

class OwnershipEventMiddleware {

    /**
     * @var Users
     */
    private $userRepo;

    /**
     * Constructor: inject dependencies
     *
     * @param Users $userRepo
     */
    public function __construct(Users $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    /**
	 * Check if an event belongs to the currently logged-in user
	 * include all events or only upcoming events
	 *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
	 */
	public function handle($request, Closure $next, $all = null)
	{
        if ($all == 'all') {
            $all = true;
        }

		$user = \Sentry::getUser();
		$event_id = (int) $request->route()->parameter('event_id');

		if ($this->userRepo->checkEventOwnership($event_id, $user->id, $all) !== 1){
			\Notification::error('missing_rights');
			return \Redirect::to('organizer/profile');
		}

        return $next($request);
	}


}
