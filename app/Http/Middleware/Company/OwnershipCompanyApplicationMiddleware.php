<?php namespace App\Http\Middleware\Company;

use Closure;
use App\Eloquent\User\UserRepositoryInterface as Users;

class OwnershipCompanyApplicationMiddleware {

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
     * Check if the application belongs to the currently logged-in company user
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = \Sentry::getUser();

        $event_id       = (int) $request->route()->parameter('event_id');
        $application_id = (int) $request->route()->parameter('application_id');

        if ($this->userRepo->checkApplicationOwnership($event_id, $application_id, $user->id, 'company') !== 1) {
            \Notification::error('missing_rights');
            return \Redirect::to('company/applications');
        }

        return $next($request);
    }

}
