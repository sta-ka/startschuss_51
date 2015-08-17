<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Creator\EventCreator;

use App\Eloquent\Company\CompanyRepositoryInterface as Companies;
use App\Eloquent\Event\EventRepositoryInterface as Events;
use App\Eloquent\Organizer\OrganizerRepositoryInterface as Organizers;
use App\Eloquent\Misc\Audience\AudienceRepositoryInterface as Audiences;
use App\Eloquent\Misc\Region\RegionRepositoryInterface as Regions;
use App\Eloquent\User\UserRepositoryInterface as Users;

use App\Http\Requests\Event\CreateEventRequest;
use App\Http\Requests\Event\UpdateGeneralDataRequest;
use App\Http\Requests\Event\UpdateProfileRequest;
use App\Http\Requests\Event\UpdateProgramRequest;
use App\Http\Requests\Event\UpdateContactsRequest;
use App\Http\Requests\Event\UpdateSeoDataRequest;
use App\Http\Requests\Event\UploadLogoRequest;

use Input;
use Notification;
use Request;
use Sentry;
use URL;

/**
 * Class EventsController
 *
 * @package App\Http\Controllers\Admin
 */
class EventsController extends Controller {

    /**
     * @var Audiences
     */
    private $audienceRepo;

    /**
     * @var Companies
     */
    private $companyRepo;

    /**
     * @var Events
     */
    private $eventRepo;

    /**
     * @var Organizers
     */
    private $organizerRepo;

    /**
     * @var Regions
     */
    private $regionRepo;

    /**
     * @var Users
     */
    private $userRepo;

    /**
     * Constructor: inject dependencies
     *
     * @param Audiences  $audienceRepo
     * @param Companies  $companyRepo
     * @param Events     $eventRepo
     * @param Organizers $organizerRepo
     * @param Regions    $regionRepo
     * @param Users      $userRepo
     */
    public function __construct(Audiences $audienceRepo, Companies $companyRepo, Events $eventRepo, Organizers $organizerRepo, Regions $regionRepo, Users $userRepo)
	{
		$this->audienceRepo = $audienceRepo;
		$this->companyRepo = $companyRepo;
		$this->eventRepo = $eventRepo;
		$this->organizerRepo = $organizerRepo;
		$this->regionRepo = $regionRepo;
		$this->userRepo = $userRepo;
	}

	/**
	 * Events overview
     *
     * @return \Illuminate\View\View
     */
	public function index()
	{
		$data['events'] = $this->eventRepo->getAll(true); // true = include soft deleted events
		$data['duplicates'] = $this->eventRepo->getDuplicateEvents();

		return view('admin.events.show', $data);
	}

	/**
	 * Show event profile
     *
     * @param int $event_id
     *
     * @return \Illuminate\View\View
     */
	public function show($event_id)
	{
		$data['event'] = $this->eventRepo->findById($event_id);
		$data['users_list'] = $this->userRepo->lists('username', 'id', 2); // 2 = organizer group

		if ($data['event']->user_id) { // if event is linked to a user
            $data['user'] = $this->userRepo->findById($data['event']->user_id);
        }

		return view('admin.events.profile.show', $data);
	}

	/**
	 * Display 'New event' form
	 * optionally use old event information as a template
     *
     * @param int|bool $event_id
     *
     * @return \Illuminate\View\View
	 */
	public function newEvent($event_id = false)
	{
		$data['regions']	= $this->regionRepo->lists('name', 'id');
		$data['organizers']	= $this->organizerRepo->lists('name', 'id');

		if ($event_id) { // if event_id is given get data for this event
            $data['event'] = $this->eventRepo->findById($event_id);
        }

		return view('admin.events.new', $data);
	}

	/**
	 * Create event
     *
     * @param CreateEventRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
	 */
	public function createEvent(CreateEventRequest $request)
	{
		$event = new EventCreator($this->eventRepo);
		$success = $event->createEvent(Input::all());

		$success ?
			Notification::success('event_created_successful') :
			Notification::error('event_created_unsuccessful');	

		return redirect('admin/events');
	}

	/**
	 * Accept an requested event
     *
     * @param int $event_id
     *
     * @return \Illuminate\Http\RedirectResponse
	 */
	public function acceptRequest($event_id)
	{
		$event = $this->eventRepo->findById($event_id);
		$event->update(['requested_by' => null]);

		return redirect('admin/events/'. $event_id .'/show');
	}

	/**
	 * Link an event to a user
     *
     * @param int $event_id
     *
     * @return \Illuminate\Http\RedirectResponse
	 */
	public function addLinkage($event_id)
	{
		$user_id = Input::get('user_id');

		$user = Sentry::findUserByID($user_id);

		// if user is not in group 'organizer' -> redirect back
		if (! $user->inGroup(Sentry::findGroupByName('organizer'))) {
			Notification::error('profile_update_unsuccessful');	

			return redirect('admin/events/show/'.$event_id);
		}

		$event = $this->eventRepo->findById($event_id);
		$event->update(['user_id' => $user_id]);

		return redirect('admin/events/'. $event_id .'/show');
	}

	/**
	 * Delete linkage between event and user
     *
     * @param int $event_id
     *
     * @return \Illuminate\Http\RedirectResponse
	 */
	public function deleteLinkage($event_id)
	{
		$event = $this->eventRepo->findById($event_id);
		$event->update(['user_id' => null]);

		return redirect('admin/events/'. $event_id .'/show');
	}

	/**
	 * Display 'Edit general data' form
     *
     * @param int $event_id
     *
     * @return \Illuminate\View\View
	 */
	public function editGeneralData($event_id)
	{
		$data['event']      = $this->eventRepo->findById($event_id);

		$data['regions']	= $this->regionRepo->lists('name', 'id');
		$data['organizers']	= $this->organizerRepo->lists('name', 'id');

		return view('admin.events.profile.edit_general_data', $data);
	}

	/**
	 * Process editing general data
     *
     * @param UpdateGeneralDataRequest $request
     * @param int $event_id
     *
     * @return \Illuminate\Http\RedirectResponse
	 */
	public function updateGeneralData(UpdateGeneralDataRequest $request, $event_id)
	{
		$event = new EventCreator($this->eventRepo);
        $event->editGeneralData(Input::all(), $event_id);

		return redirect('admin/events/'. $event_id .'/show');
	}

	/**
	 * Display 'Edit event profile' form
     *
     * @param int $event_id
     *
     * @return \Illuminate\View\View
	 */
	public function editProfile($event_id)
	{	
		$data['event']		= $this->eventRepo->findById($event_id);
		$data['audience']	= explode(', ', $data['event']->audience);
		$data['audiences']	= $this->audienceRepo->lists('name', 'name');
		
		return view('admin.events.profile.edit_profile', $data);
	}

	/**
	 * Process editing event profile
     *
     * @param UpdateProfileRequest $request
     * @param int $event_id
     *
     * @return \Illuminate\Http\RedirectResponse
	 */
	public function updateProfile(UpdateProfileRequest $request, $event_id)
	{
		$event = new EventCreator($this->eventRepo);
        $event->editProfile(Input::all(), $event_id);

		return redirect('admin/events/'. $event_id .'/show');
	}

	/**
	 * Display 'Edit event program' form
     *
     * @param int $event_id
     *
     * @return \Illuminate\View\View
	 */
	public function editProgram($event_id)
	{
		$data['event'] = $this->eventRepo->findById($event_id);
		
		return view('admin.events.profile.edit_program', $data);
	}

	/**
	 * Process editing event program
     *
     * @param UpdateProgramRequest $request
     * @param int $event_id
     *
     * @return \Illuminate\Http\RedirectResponse
	 */
	public function updateProgram(UpdateProgramRequest $request, $event_id)
	{
		$event = new EventCreator($this->eventRepo);
        $event->editProgram(Input::all(), $event_id);

		return redirect('admin/events/'. $event_id .'/show');
	}

	/**
	 * Display 'Edit event contacts' form
     *
     * @param int $event_id
     *
     * @return \Illuminate\View\View
	 */
	public function editContacts($event_id)
	{
		$data['event'] = $this->eventRepo->findById($event_id);
		
		return view('admin.events.profile.edit_contacts', $data);
	}

	/**
	 * Process editing event contacts
     *
     * @param UpdateContactsRequest $request
     * @param int $event_id
     *
     * @return \Illuminate\Http\RedirectResponse
	 */
	public function updateContacts(UpdateContactsRequest $request, $event_id)
	{
		$event = new EventCreator($this->eventRepo);
        $event->editContacts(Input::all(), $event_id);

		return redirect('admin/events/'. $event_id .'/show');
	}

	/**
	 * Display 'Edit SEO data' form
     *
     * @param int $event_id
     *
     * @return \Illuminate\View\View
	 */
	public function editSeoData($event_id)
	{
		$data['event'] = $this->eventRepo->findById($event_id);

		return view('admin.events.profile.edit_seo_data', $data);
	}

	/**
	 * Process editing SEO data
     *
     * @param UpdateSeoDataRequest $request
     * @param int $event_id
     *
     * @return \Illuminate\Http\RedirectResponse
	 */
	public function updateSeoData(UpdateSeoDataRequest $request, $event_id)
	{
		$event = new EventCreator($this->eventRepo);
        $event->editSeoData(Input::all(), $event_id);

		return redirect('admin/events/'. $event_id .'/show');
	}

	/**
	 * Display 'Edit event logo' form
     *
     * @param int $event_id
     *
     * @return \Illuminate\View\View
	 */
	public function editLogo($event_id)
	{
		$data['event'] = $this->eventRepo->findById($event_id);
		
		return view('admin.events.profile.edit_logo', $data);
	}

	/**
	 * Upload logo
     *
     * @param UploadLogoRequest $request
     * @param int $event_id
     *
     * @return \Illuminate\Http\RedirectResponse
	 */
	public function updateLogo(UploadLogoRequest $request, $event_id)
	{
		$event = new EventCreator($this->eventRepo);
		$event->uploadLogo($event_id);

		return redirect('admin/events/'. $event_id .'/edit-logo');
	}

	/**
	 * Delete logo
     *
     * @param int $event_id
     *
     * @return \Illuminate\Http\RedirectResponse
	 */
	public function deleteLogo($event_id)
	{
		$event = $this->eventRepo->findById($event_id);

        $filename = $event->logo;

		if (empty($filename)) {
            Notification::error('logo_deleted_unsuccessful');
            return redirect('admin/events/' . $event_id . '/edit-logo');
        }

        // delete logo in the database
        $event->update(['logo' => null]);

        // delete files
        \File::delete('uploads/logos/original/' . $filename);
        \File::delete('uploads/logos/small/' . $filename);

        Notification::success('logo_deleted_successful');
        return redirect('admin/events/' . $event_id . '/edit-logo');
	}

	/**
	 * Toggle visibility status of event
     *
     * @param int $event_id
     *
     * @return \Illuminate\Http\RedirectResponse
	 */
	public function changeStatus($event_id)
	{
		$event = $this->eventRepo->findById($event_id);

		// get status of visible
		$visible = $event->visible;
		
		$event->update(['visible' => ! $visible]);

		return back();
	}

	/**
	 * Show companies and manage participants for an event
     *
     * @param int $event_id
     *
     * @return \Illuminate\View\View
	 */
	public function manageParticipants($event_id)
	{
		$data['event']      = $this->eventRepo->findById($event_id);
		$data['companies']	= $this->companyRepo->getCompanies($event_id);

		return view('admin.events.profile.manage_participants', $data);
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
		$data['event'] 		  = $this->eventRepo->findById($event_id);
		$data['applications'] = $this->eventRepo->getApplications($event_id);

		return view('admin.events.profile.interviews', $data);
	}

	/**
	 * Add company as participant to an event
     *
     * @param int $event_id
     * @param int $company_id
     *
     * @return mixed
	 */
	public function addCompany($event_id, $company_id)
	{
		$success = $this->eventRepo->addAsParticipant($event_id, $company_id);

		if (Request::ajax()) {
/*			$success ?
				$data = Html::link('admin/events/remove-company/'. $event_id .'/'.$company_id, 'Als Teilnehmer löschen') :
				$data = Html::link('admin/events/add-company/'. $event_id .'/'.$company_id, 'Als Teilnehmer hinzufügen');*/
			$success ?
				$data = json_encode([
					'text' => 'Als Teilnehmer löschen', 
					'url' => URL::to('admin/events/remove-company/'. $event_id .'/'.$company_id)]) :
				$data = json_encode([
					'text' => 'Als Teilnehmer hinzufügen',
					'url' => Url::to('admin/events/add-company/'. $event_id .'/'.$company_id)]);

			return $data;
		}

        $success ?
            Notification::success('company_added_successful') :
            Notification::error('company_added_unsuccessful');

        return redirect('admin/events/' . $event_id . '/manage-participants');
	}

	/**
	 * Remove company as participant from an event
     *
     * @param int $event_id
     * @param int $company_id
     *
     * @return mixed
     */
	public function removeCompany($event_id, $company_id)
	{
		$success = $this->eventRepo->removeAsParticipant($event_id, $company_id);

		if (Request::ajax()) {
			/*$success ?
				$data = Html::link('admin/events/add-company/'. $event_id .'/'.$company_id, 'Als Teilnehmer hinzufügen') :
				$data = Html::link('admin/events/remove-company/'. $event_id .'/'.$company_id, 'Als Teilnehmer löschen');*/

			$success ?
				$data = json_encode([
					'text' => 'Als Teilnehmer hinzufügen', 
					'url' => URL::to('admin/events/add-company/'. $event_id .'/'.$company_id)]) :
				$data = json_encode([
					'text' => 'Als Teilnehmer löschen',
					'url' => Url::to('admin/events/remove-company/'. $event_id .'/'.$company_id)]);

			return $data;
		}

        $success ?
            Notification::success('company_removed_successful') :
            Notification::error('company_removed_unsuccessful');

        return redirect('admin/events/' . $event_id . '/manage-participants');
	}

	/**
	 * Show participants and manage interviews for an event
     *
     * @param int $event_id
     *
     * @return \Illuminate\View\View
     */
	public function manageInterviews($event_id)
	{
		$data['event']		= $this->eventRepo->findById($event_id);
		$data['companies']	= $this->eventRepo->getParticipatingCompanies($event_id);

		return view('admin.events.profile.manage_interviews', $data);
	}

	/**
	 * Add interview tag to a participant of an event
     *
     * @param int $event_id
     * @param int $company_id
     *
     * @return mixed
	 */
	public function addInterview($event_id, $company_id)
	{
		$success = $this->eventRepo->addInterviewTag($event_id, $company_id);

		if (Request::ajax()) {
			$success ?
				$data = json_encode([
					'text' => 'Löschen', 
					'url' => URL::to('admin/events/remove-interview/'. $event_id .'/'.$company_id)]) :
				$data = json_encode([
					'text' => 'Hinzufügen',
					'url' => Url::to('admin/events/add-interview/'. $event_id .'/'.$company_id)]);

			return $data;
		}

        $success ?
            Notification::success('company_added_successful') :
            Notification::error('company_added_unsuccessful');

        return redirect('admin/events/' . $event_id . '/manage-interviews');
	}

	/**
	 * Remove interview tag from a participant of an event
     *
     * @param int $event_id
     * @param int $company_id
     *
     * @return mixed
	 */
	public function removeInterview($event_id, $company_id)
	{
		$success = $this->eventRepo->removeInterviewTag($event_id, $company_id);

		if (Request::ajax()) {
			$success ?
				$data = json_encode([
					'text' => 'Hinzufügen', 
					'url' => URL::to('admin/events/add-interview/'. $event_id .'/'.$company_id)]) :
				$data = json_encode([
					'text' => 'Löschen',
					'url' => Url::to('admin/events/remove-interview/'. $event_id .'/'.$company_id)]);

			return $data;
        }

        $success ?
            Notification::success('company_removed_successful') :
            Notification::error('company_removed_unsuccessful');

        return redirect('admin/events/' . $event_id . '/manage-interviews');
	}

	/**
	 * Delete event - Soft delete is enabled
     *
     * @param int $event_id
     *
     * @return \Illuminate\Http\RedirectResponse
	 */
	public function delete($event_id)
	{
		$event = $this->eventRepo->findById($event_id);

		// check if event is soft deleted
		if (! $event->deleted_at) {
			$event->delete() ? // soft-delete event
				Notification::success('event_delete_successful') :
				Notification::error('event_delete_unsuccessful');

            return redirect('admin/events');
        }

        // force delete entry from events table
        $event->forceDelete();

        // delete logo
        if ($event->logo) {
            $this->getDeletelogo($event_id);
        }

        // delete entries from participants table
        $event->participants()->detach();

        Notification::success('event_delete_successful');
        return redirect('admin/events');
	}

	/**
	 * Restore event - Restore soft deleted event
     *
     * @param int $event_id
     *
     * @return \Illuminate\Http\RedirectResponse
	 */
	public function restore($event_id)
	{
		$event = $this->eventRepo->findById($event_id);

		if ($event->deleted_at) {
            $event->restore();
        }

		return redirect('admin/events/show/'.$event_id);
	}	
}