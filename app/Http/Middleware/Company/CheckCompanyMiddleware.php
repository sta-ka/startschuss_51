<?php namespace App\Http\Middleware\Company;

use Closure;
use App\Eloquent\User\UserRepositoryInterface as Users;

class CheckCompanyMiddleware {

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
     * Check if company belongs to logged-in user
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = \Sentry::getUser();

        if ($this->userRepo->checkForCompany($user->id) !== 1) {
            \Notification::error('missing_rights');
            return \Redirect::to('company/dashboard');
        }

        return $next($request);
    }

}
