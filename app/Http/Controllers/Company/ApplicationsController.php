<?php namespace App\Http\Controllers\Company;

use App\Eloquent\Applicant\ApplicantRepositoryInterface as Applicants;
use App\Eloquent\Applicant\Application\ApplicationRepositoryInterface as Applications;
use App\Eloquent\Company\CompanyRepositoryInterface as Companies;
use App\Eloquent\Event\EventRepositoryInterface as Events;
use App\Eloquent\User\UserRepositoryInterface as Users;
use App\Http\Controllers\Controller;
use Notification;
use Sentry;

/**
 * Class ApplicationsController
 *
 * @package App\Http\Controllers\Company
 */
class ApplicationsController extends Controller {

    /**
     * @var Applicants
     */
    private $applicantRepo;

    /**
     * @var Applications
     */
    private $applicationRepo;

    /**
     * @var Companies
     */
    private $companyRepo;

    /**
     * @var Events
     */
    private $eventRepo;

    /**
     * @var Users
     */
    private $userRepo;

    /**
     * Constructor: inject dependencies and apply filters
     *
     * @param Applicants   $applicantRepo
     * @param Applications $applicationRepo
     * @param Companies    $companyRepo
     * @param Events       $eventRepo
     * @param Users        $userRepo
     */
    public function __construct(Applicants $applicantRepo, Applications $applicationRepo, Companies $companyRepo, Events $eventRepo, Users $userRepo)
	{
        $this->applicantRepo    = $applicantRepo;
        $this->applicationRepo  = $applicationRepo;
        $this->companyRepo      = $companyRepo;
        $this->eventRepo        = $eventRepo;
        $this->userRepo         = $userRepo;

        // check if user is linked to a company
        $this->middleware('check.company');

		// check if application 'belongs' to company user
		$this->middleware('ownership.company.application', ['only' => [
																'applicant',
																'show',
																'acceptApplication',
																'rejectApplication'
															]
														]);
	}

	/**
	 * Show all events for company which offer interviews
     *
     * @return \Illuminate\View\View
	 */
	public function index()
	{
		$user = Sentry::getUser();

		$data['events'] = $this->userRepo->getEventsHostingInterviews($user->id, 'company');

		return view('company.applications.events_overview', $data);
	}

	/**
	 * Show all applications for a single event
     *
     * @param int $event_id
     *
     * @return \Illuminate\View\View
	 */
	public function event($event_id)
	{
		$user = Sentry::getUser();

		$data['event']	      = $this->eventRepo->findById($event_id);
		$data['applications'] = $this->userRepo->getApplicationsForCompany($user->id, $event_id);

		return view('company.applications.event_overview', $data);
	}

	/**
	 * Show all interview dates for an event
     *
     * @param int $event_id
     *
     * @return \Illuminate\View\View
	 */
	public function eventInterviews($event_id)
	{
		$user = Sentry::getUser();

		$data['event']	      = $this->eventRepo->findById($event_id);
		$data['applications'] = $this->userRepo->getApplicationsForCompany($user->id, $event_id);

		return view('company.applications.event_interviews', $data);
	}

	/**
	 * Show single application
	 * (Application must belong to the company user -> see filters)
     *
     * @param int $event_id
     * @param int $application_id
     *
     * @return \Illuminate\View\View
	 */
	public function show($event_id, $application_id)
	{
		$data['event']	     = $this->eventRepo->findById($event_id);
		$data['application'] = $this->applicationRepo->findById($application_id);

		return view('company.applications.profile.show', $data);
	}

	/**
	 * Show applicant
     *
     * @param int $event_id
     * @param int $application_id
     *
     * @return \Illuminate\View\View
	 */
	public function applicant($event_id, $application_id)
	{
		$applicant_id = $this->applicationRepo->findById($application_id)->applicant_id;

		$data['event'] 		 = $this->eventRepo->findById($event_id);
		$data['applicant']	 = $this->applicantRepo->findByUserId($applicant_id);

		return view('company.applications.profile.applicant', $data);
	}

	/**
	 * Accept application
     *
     * @param int $event_id
     * @param int $application_id
     *
     * @return \Illuminate\Http\RedirectResponse
	 */
	public function acceptApplication($event_id, $application_id)
	{
		$success = $this->applicationRepo->acceptApplication($application_id);

		$success ?
				Notification::success('application_accepted') :
				Notification::error('accepting_application_failed');		

		return redirect('company/applications/show/'.$event_id.'/'.$application_id);
	}

	/**
	 * Reject application
     *
     * @param int $event_id
     * @param int $application_id
     *
     * @return \Illuminate\Http\RedirectResponse
	 */
	public function rejectApplication($event_id, $application_id)
	{
		$success = $this->applicationRepo->rejectApplication($application_id);

		$success ?
				Notification::success('application_rejected') :
				Notification::error('rejecting_application_failed');	

		return redirect('company/applications/show/'.$event_id.'/'.$application_id);
	}


}