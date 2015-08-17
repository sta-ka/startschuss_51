<?php namespace App\Http\Middleware\Organizer;

use Closure;
use App\Eloquent\User\UserRepositoryInterface as Users;

class OwnershipOrganizerApplicationMiddleware {

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
	 * Check if an application belongs to the currently logged-in organizer user
	 *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
		$user = \Sentry::getUser();

		$event_id       = (int) $request->route()->parameter('event_id');
		$application_id = (int) $request->route()->parameter('application_id');

		if ($this->userRepo->checkApplicationOwnership($event_id, $application_id, $user->id, 'organizer') !== 1) {
			\Notification::error('missing_rights');
			return \Redirect::to('organizer/applications');
		}

        return $next($request);
	}
}
