<?php namespace App\Http\Middleware\Company;

use Closure;
use App\Eloquent\User\UserRepositoryInterface as Users;

class OwnershipJobMiddleware {

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
     * Check if a job belongs to the currently logged-in company user
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = \Sentry::getUser();
        $job_id = (int) $request->route()->parameter('job_id');

        if ($this->userRepo->checkJobOwnership($job_id, $user->id) !== 1) {
            \Notification::error('missing_rights');
            return \Redirect::to('company/jobs');
        }

        return $next($request);
    }

}
