<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Eloquent\Company\CompanyRepositoryInterface as Companies;
use App\Eloquent\Event\EventRepositoryInterface as Events;
use App\Eloquent\Misc\Log\InfoRepositoryInterface as Infos;
use App\Eloquent\User\Login\LoginRepositoryInterface as Logins;
use App\Eloquent\Organizer\OrganizerRepositoryInterface as Organizers;
use App\Eloquent\User\UserRepositoryInterface as Users;

/**
 * Class DashboardController
 *
 * @package App\Http\Controllers\Admin
 */
class DashboardController extends Controller {

    /**
     * @var Companies
     */
    private $companyRepo;

    /**
     * @var Events
     */
    private $eventRepo;

    /**
     * @var Infos
     */
    private $infoRepo;

    /**
     * @var Logins
     */
    private $loginRepo;

    /**
     * @var Organizers
     */
    private $organizerRepo;

    /**
     * @var Users
     */
    private $userRepo;

    /**
     * Constructor: inject dependencies
     *
     * @param Companies  $companyRepo
     * @param Events     $eventRepo
     * @param Infos      $infoRepo
     * @param Logins     $loginRepo
     * @param Organizers $organizerRepo
     * @param Users      $userRepo
     */
    public function __construct(Companies $companyRepo, Events $eventRepo, Infos $infoRepo, Logins $loginRepo,  Organizers $organizerRepo, Users $userRepo)
	{
		$this->companyRepo 	 = $companyRepo;
		$this->eventRepo 	 = $eventRepo;
		$this->infoRepo 	 = $infoRepo;
		$this->loginRepo 	 = $loginRepo;
		$this->organizerRepo = $organizerRepo;
		$this->userRepo 	 = $userRepo;
	}

	/**
	 * Redirect to 'Login overview' page
     *
     * @return \Illuminate\Http\RedirectResponse
	 */
	public function index()
	{
		return redirect('admin/dashboard/logins');
	}

	/**
	 * Show all requested events
     *
     * @return \Illuminate\Http\RedirectResponse
	 */
	public function requestedEvents()
	{
		$data['events'] = $this->eventRepo->getRequestedEvents();

		return view('admin.dashboard.news.requested_events', $data);
	}

	/**
	 * Display last 200 logins
     *
     * @return \Illuminate\View\View
	 */
	public function logins()
	{
		$data['logins'] = $this->loginRepo->getAll(200);

		return view('admin.dashboard.statistics.logins', $data);
	}

	/**
	 * Display login attempts
     *
     * @return \Illuminate\View\View
	 */
	public function loginAttempts()
	{
		$data['login_attempts'] = $this->userRepo->getLoginAttempts();

		return view('admin.dashboard.statistics.login_attempts', $data);
	}

	/**
	 * Display logged data
     *
     * @return \Illuminate\View\View
	 */
	public function loggedData()
	{
		$data['logged_data'] = $this->infoRepo->getAll(200);

		return view('admin.dashboard.news.logged_data', $data);
	}

	/**
	 * Display overview of revision types
     *
     * @return \Illuminate\View\View
	 */
	public function revisions()
	{
		return view('admin.dashboard.revisions.overview');
	}

	/**
	 * Display history of revisions to user table
     *
     * @return \Illuminate\View\View
	 */
	public function userRevisions()
	{
		$data['users'] 		= $this->userRepo->getAll();
		$data['user_list']  = $this->userRepo->lists('username', 'id');

		return view('admin.dashboard.revisions.usertable', $data);
	}

	/**
	 * Display history of revisions to event table
     *
     * @return \Illuminate\View\View
	 */
	public function eventRevisions()
	{
		$data['events'] 	= $this->eventRepo->getAll(true); // true = include soft-deleted events
		$data['user_list']  = $this->userRepo->lists('username', 'id');

		return view('admin.dashboard.revisions.eventtable', $data);
	}

	/**
	 * Display history of revisions to company table
     *
     * @return \Illuminate\View\View
	 */
	public function companyRevisions()
	{
		$data['companies'] = $this->companyRepo->getAll(true); // true = include soft-deleted companies
		$data['user_list'] = $this->userRepo->lists('username', 'id');

		return view('admin.dashboard.revisions.companytable', $data);
	}

	/**
	 * Display history of revisions to organizer table
     *
     * @return \Illuminate\View\View
	 */
	public function organizerRevisions()
	{
		$data['organizers'] = $this->organizerRepo->getAll(true); // true = include soft-deleted organizers
		$data['user_list']  = $this->userRepo->lists('username', 'id');

		return view('admin.dashboard.revisions.organizertable', $data);
	}

}