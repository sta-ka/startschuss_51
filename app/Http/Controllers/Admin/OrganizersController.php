<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Eloquent\Organizer\OrganizerRepositoryInterface as Organizers;
use App\Services\Creator\OrganizerCreator;

use App\Http\Requests\Organizer\CreateOrganizerRequest;
use App\Http\Requests\Organizer\UpdateContactsRequest;
use App\Http\Requests\Organizer\UpdateGeneralDataRequest;
use App\Http\Requests\Organizer\UpdateProfileRequest;
use App\Http\Requests\Organizer\UpdateSeoDataRequest;
use App\Http\Requests\Organizer\UploadLogoRequest;

use Input;
use Notification;

/**
 * Class OrganizersController
 *
 * @package App\Http\Controllers\Admin
 */
class OrganizersController extends Controller {

    /**
     * @var Organizers
     */
    private $organizerRepo;

	/**
	 * Constructor: inject dependencies
     *
     * @param Organizers $organizerRepo
	 */
	public function __construct(Organizers $organizerRepo)
	{
		$this->organizerRepo = $organizerRepo;
	}

	/**
	 * Organizers Overview
     *
     * @return \Illuminate\View\View
	 */
	public function index()
	{
		$data['organizers'] = $this->organizerRepo->getAll(true); // true = include soft-deleted organizers

		return view('admin.organizers.show', $data);
	}

	/**
	 * Show organizer profile
     *
     * @param int $organizer_id
     *
     * @return \Illuminate\View\View
	 */
	public function show($organizer_id)
	{
		$data['organizer'] = $this->organizerRepo->findById($organizer_id);

		return view('admin.organizers.profile.show', $data);
	}

	/**
	 * Display 'New organizer' form
     *
     * @return \Illuminate\View\View
	 */
	public function newOrganizer()
	{
		return view('admin.organizers.new');
	}

	/**
	 * Create new organizer
     *
     * @param CreateOrganizerRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
	 */
	public function create(CreateOrganizerRequest $request)
	{
		$organizer = new OrganizerCreator($this->organizerRepo);
		$success = $organizer->createOrganizer(Input::all());

		$success ?
			Notification::success('organizer_created_successful') :
			Notification::error('organizer_created_unsuccessful');

		return redirect('admin/organizers/show');

	}

	/**
	 * Display 'Edit general data' form
     *
     * @param int $organizer_id
     *
     * @return \Illuminate\View\View
	 */
	public function editGeneralData($organizer_id)
	{
		$data['organizer'] = $this->organizerRepo->findById($organizer_id);

		return view('admin.organizers.profile.edit_general_data', $data);
	}

	/**
	 * Process editing general data
     *
     * @param UpdateGeneralDataRequest $request
     * @param int $organizer_id
     *
     * @return \Illuminate\Http\RedirectResponse
	 */
	public function updateGeneralData(UpdateGeneralDataRequest $request, $organizer_id)
	{
		$organizer = new OrganizerCreator($this->organizerRepo);
        $organizer->editGeneralData(Input::all(), $organizer_id);

		return redirect('admin/organizers/'. $organizer_id .'/show');
	}

	/**
	 * Display 'Edit profile' form
     *
     * @param int $organizer_id
     *
     * @return \Illuminate\View\View
	 */
	public function editProfile($organizer_id)
	{
		$data['organizer'] = $this->organizerRepo->findById($organizer_id);
		
		return view('admin.organizers.profile.edit_profile', $data);
	}

	/**
	 * Process editing profile
     *
     * @param UpdateProfileRequest $request
     * @param int $organizer_id
     *
     * @return \Illuminate\Http\RedirectResponse
	 */
	public function updateProfile(UpdateProfileRequest $request, $organizer_id)
	{
		$organizer = new OrganizerCreator($this->organizerRepo);
        $organizer->editProfile(Input::all(), $organizer_id);

		return redirect('admin/organizers/'. $organizer_id .'/show');
	}

	/**
	 * Display 'Edit contacts' form
     *
     * @param int $organizer_id
     *
     * @return \Illuminate\View\View
	 */
	public function editContacts($organizer_id)
	{
		$data['organizer'] = $this->organizerRepo->findById($organizer_id);
		
		return view('admin.organizers.profile.edit_contacts', $data);
	}

	/**
	 * Process editing  contacts
     *
     * @param UpdateContactsRequest $request
     * @param int $organizer_id
     *
     * @return \Illuminate\Http\RedirectResponse
	 */
	public function updateContacts(UpdateContactsRequest $request, $organizer_id)
	{
		$organizer = new OrganizerCreator($this->organizerRepo);
        $organizer->editContacts(Input::all(), $organizer_id);

		return redirect('admin/organizers/'. $organizer_id .'/show');

	}

	/**
	 * Display 'Edit SEO data' form
     *
     * @param int $organizer_id
     *
     * @return \Illuminate\View\View
	 */
	public function editSeoData($organizer_id)
	{
		$data['organizer'] = $this->organizerRepo->findById($organizer_id);

		return view('admin.organizers.profile.edit_seo_data', $data);
	}

	/**
	 * Process editing SEO data
     *
     * @param UpdateSeoDataRequest $request
     * @param int $organizer_id
     *
     * @return \Illuminate\Http\RedirectResponse
	 */
	public function updateSeoData(UpdateSeoDataRequest $request, $organizer_id)
	{
		$organizer = new OrganizerCreator($this->organizerRepo);
        $organizer->editSeoData(Input::all(), $organizer_id);

		return redirect('admin/organizers/'. $organizer_id .'/show');

	}

	/**
	 * Display 'Edit logo' form
     *
     * @param int $organizer_id
     *
     * @return \Illuminate\View\View
	 */
	public function editLogo($organizer_id)
	{
		$data['organizer'] = $this->organizerRepo->findById($organizer_id);
		
		return view('admin.organizers.profile.edit_logo', $data);
	}

	/**
	 * Upload logo
     *
     * @param UploadLogoRequest $request
     * @param int $organizer_id
     *
     * @return \Illuminate\Http\RedirectResponse
	 */
	public function updateLogo(UploadLogoRequest $request, $organizer_id)
	{
		$organizer = new OrganizerCreator($this->organizerRepo);
		$organizer->uploadLogo($organizer_id);

		return redirect('admin/organizers/edit-logo/'. $organizer_id);

	}

	/**
	 * Delete logo
     *
     * @param int $organizer_id
     *
     * @return \Illuminate\Http\RedirectResponse
	 */
	public function deleteLogo($organizer_id)
	{
		$organizer = $this->organizerRepo->findById($organizer_id);

		if ($filename = $organizer->logo) {
			// delete logo in the database
			$organizer->update(['logo' => null]);

			// delete files
			\File::delete('uploads/logos/original/'. $filename);
			\File::delete('uploads/logos/big/'. $filename);
			\File::delete('uploads/logos/medium/'. $filename);
			\File::delete('uploads/logos/small/'. $filename);
				
			Notification::success('logo_deleted_successful');
		} else {
			Notification::error('logo_deleted_unsuccessful');
		}

		return redirect('admin/organizers/edit-logo/'. $organizer_id);
	}

	/**
	 * Delete organizer from database
     *
     * @param int $organizer_id
     *
     * @return \Illuminate\Http\RedirectResponse
	 */
	public function delete($organizer_id)
	{
		// check if organizer is linked to events
		if (count($this->organizerRepo->getEventsForOrganizer($organizer_id)) > 0) {
			Notification::error('organizer_linked_to_events');
			return redirect('admin/organizers');
		}

		$organizer = $this->organizerRepo->findById($organizer_id);

		// check if organizer is soft deleted
		if ( ! $organizer->deleted_at) {
			$organizer->delete() ? 
				Notification::success('organizer_delete_successful') :
				Notification::error('organizer_delete_unsuccessful');

            return redirect('admin/organizers');
        }


        // force delete entry from organizers table
        $organizer->forceDelete();

        // delete logo
        if ($organizer->logo) {
            $this->getDeletelogo($organizer_id);
        }

        Notification::success('organizer_delete_successful');
        return redirect('admin/organizers');
	}

	/**
	 * Restore organizer - Restore soft deleted organizer
     *
     * @param int $organizer_id
     *
     * @return \Illuminate\Http\RedirectResponse
	 */
	public function restore($organizer_id)
	{
		$organizer = $this->organizerRepo->findById($organizer_id);

		if ($organizer->deleted_at) {
            $organizer->restore();
        }

		return redirect('admin/organizers/'. $organizer_id .'/show');
	}

}