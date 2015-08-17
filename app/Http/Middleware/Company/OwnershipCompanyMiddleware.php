<?php namespace App\Http\Middleware\Company;

use Closure;
use App\Eloquent\User\UserRepositoryInterface as Users;

class OwnershipCompanyMiddleware {

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
     * Check if a company belongs to the currently logged-in user
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = \Sentry::getUser();
        $company_id = (int) $request->route()->parameter('company_id');

        if ($this->userRepo->checkCompanyOwnership($company_id, $user->id) !== 1) {
            \Notification::error('missing_rights');
            return \Redirect::to('company/dashboard');
        }

        return $next($request);
    }

}
