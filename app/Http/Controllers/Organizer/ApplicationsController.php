<?php namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;

use App\Eloquent\Applicant\ApplicantRepositoryInterface as Applicants;
use App\Eloquent\Applicant\Application\ApplicationRepositoryInterface as Applications;
use App\Eloquent\Event\EventRepositoryInterface as Events;
use App\Eloquent\User\UserRepositoryInterface as Users;

use App\Services\Creator\ApplicationCreator;

use App\Http\Requests\Application\ArrangeInterviewRequest;

use Event;
use Input;
use Notification;
use Sentry;

/**
 * Class ApplicationsController
 *
 * @package App\Http\Controllers\Organizer
 */
class ApplicationsController extends Controller
{

    /**
     * @var Applicants
     */
    private $applicantRepo;
    /**
     * @var Applications
     */
    private $applicationRepo;
    /**
     * @var Events
     */
    private $eventRepo;
    /**
     * @var Users
     */
    private $userRepo;

    /**
     * Constructor: inject dependencies
     *
     * @param Applicants   $applicantRepo
     * @param Applications $applicationRepo
     * @param Events       $eventRepo
     * @param Users        $userRepo
     */
    public function __construct(Applicants $applicantRepo, Applications $applicationRepo, Events $eventRepo, Users $userRepo)
    {
        $this->applicantRepo    = $applicantRepo;
        $this->applicationRepo  = $applicationRepo;
        $this->eventRepo        = $eventRepo;
        $this->userRepo         = $userRepo;

        // check if event belongs to user at all
        $this->middleware('ownership.event:all', ['except' => ['index']]);

        // check if application belongs to organizer user
        $this->middleware('ownership.organizer.application', ['except' => [
                                                                    'index',
                                                                    'event',
                                                                    'lockInterviews',
                                                                    'closeApplications',
                                                                    'eventInterviews'
                                                                ]
        ]);
    }

    /**
     * Overview -  Show all applications
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Sentry::getUser();

        $data['events'] = $this->userRepo->getEventsHostingInterviews($user->id, 'organizer');

        return view('organizer.applications.events_overview', $data);
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
        $data['event'] = $this->eventRepo->findById($event_id);
        $data['applications'] = $this->eventRepo->getApplications($event_id);

        return view('organizer.applications.event_overview', $data);
    }

    /**
     * Lock all interview dates
     *
     * @param int $event_id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function lockInterviews($event_id)
    {
        $applications = $this->eventRepo->getApplications($event_id);

        // check if all applications are properly processed
        foreach ($applications as $application) {
            if ($application->time_of_interview == null && $application->accepted_by_company == true) {
                Notification::error('interview_times_missing');
                return redirect('organizer/applications/event/' . $event_id);
            }
        }

        $event = $this->eventRepo->findById($event_id);
        $event->update(['interviews_locked' => true]);

        Notification::success('interviews_locked');
        return redirect('organizer/applications/event/' . $event_id);
    }

    /**
     * Lock all interview dates
     *
     * @param int $event_id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function closeApplications($event_id)
    {
        $event = $this->eventRepo->findById($event_id);
        $event->update(['applications_closed' => true]);

        Notification::success('applications_closed');
        return redirect('organizer/applications/event/' . $event_id);
    }

    /**
     * Detail page - Show single application
     *
     * @param int $event_id
     * @param int $application_id
     *
     * @return \Illuminate\View\View
     */
    public function show($event_id, $application_id)
    {
        $data['event'] = $this->eventRepo->findById($event_id);
        $data['application'] = $this->applicationRepo->findById($application_id);

        return view('organizer.applications.profile.show', $data);
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

        $data['event'] = $this->eventRepo->findById($event_id);
        $data['applicant'] = $this->applicantRepo->findByUserId($applicant_id);

        return view('organizer.applications.profile.applicant', $data);
    }

    /**
     * Approve single application
     *
     * @param int $event_id
     * @param int $application_id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approveApplication($event_id, $application_id)
    {
        $success = $this->applicationRepo->approveApplication($application_id);

        $success ?
            Notification::success('application_approved') :
            Notification::error('approving_application_failed');

        return redirect('organizer/applications/show/' . $event_id . '/' . $application_id);
    }

    /**
     * Disapprove single application
     *
     * @param int $event_id
     * @param int $application_id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function disapproveApplication($event_id, $application_id)
    {
        $success = $this->applicationRepo->disapproveApplication($application_id);

        $success ?
            Notification::success('application_rejected') :
            Notification::error('disapproving_application_failed');

        return redirect('organizer/applications/show/' . $event_id . '/' . $application_id);
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
        $data['event'] = $this->eventRepo->findById($event_id);
        $data['applications'] = $this->eventRepo->getApplications($event_id);

        return view('organizer.applications.event_interviews', $data);
    }

    /**
     * Delete date for an interview
     *
     * @param int $event_id
     * @param int $application_id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteInterview($event_id, $application_id)
    {
        $application = $this->applicationRepo->findById($application_id);
        $event = $this->eventRepo->findById($event_id);

        // redirect if interviews are locked
        if ($event->interviews_locked) {
            return redirect('organizer/applications/show/' . $event_id . '/' . $application_id);
        }

        $success = $application->update(['time_of_interview' => null]);

        if ($success == false) {
            Notification::error('interview_deleted_unsuccessful');
            return redirect('organizer/applications/show/' . $event_id . '/' . $application_id);
        }

        Event::fire('log.event', [$event->name . ': Einzelgesprächstermin gelöscht.']);

        Notification::success('interview_deleted_successful');
        return redirect('organizer/applications/show/' . $event_id . '/' . $application_id);
    }

    /**
     * Arrange date for an interview
     *
     * @param ArrangeInterviewRequest $request
     * @param int                     $event_id
     * @param    int                  $application_id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function arrangeInterview(ArrangeInterviewRequest $request, $event_id, $application_id)
    {
        $event = $this->eventRepo->findById($event_id);

        $application = new ApplicationCreator($this->applicationRepo);
        $success = $application->arrangeInterview(Input::all(), $application_id);

        if ($success == false) {
            Notification::error('interview_arranged_unsuccessful');
            return redirect('organizer/applications/show/' . $event_id . '/' . $application_id);
        }

        Event::fire('log.event', [$event->name . ': Einzelgesprächstermin vergeben.']);

        Notification::success('interview_arranged_successful');
        return redirect('organizer/applications/show/' . $event_id . '/' . $application_id);
    }

    /**
     * Delete date for an interview
     *
     * @param int    $event_id
     * @param    int $application_id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function rearrangeInterview($event_id, $application_id)
    {
        $application = $this->applicationRepo->findById($application_id);
        $event = $this->eventRepo->findById($event_id);

        if ($event->interviews_locked) {
            return redirect('organizer/applications/show/' . $event_id . '/' . $application_id);
        }

        $success = $application->update(['time_of_interview' => null]);

        if ($success == false) {
            Notification::error('interview_deleted_unsuccessful');
            return redirect('organizer/applications/show/' . $event_id . '/' . $application_id);
        }

        Event::fire('log.event', [$event->name . ': Einzelgesprächstermin gelöscht.']);

        Notification::success('interview_deleted_successful');
        return redirect('organizer/applications/show/' . $event_id . '/' . $application_id);
    }


}