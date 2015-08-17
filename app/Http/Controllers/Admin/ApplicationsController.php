<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Eloquent\Applicant\Application\ApplicationRepositoryInterface as Applications;

use Notification;

/**
 * Class ApplicationsController
 *
 * @package App\Http\Controllers\Admin
 */
class ApplicationsController extends Controller {

    /**
     * @var Applications
     */
    private $applicationRepo;

	/**
	 * Constructor: inject dependencies
     *
     * @param Applications $applicationRepo
	 */
	public function __construct(Applications $applicationRepo)
	{
		$this->applicationRepo = $applicationRepo;
	}

	/**
	 * Overview of all applications
     *
     * @return \Illuminate\View\View
	 */	
	public function index()
	{
		$data['applications'] = $this->applicationRepo->getAll();

		return view('admin.applications.overview', $data);
	}

	/**
	 * Show single application by ID
     *
     * @param int $application_id
     *
     * @return \Illuminate\View\View
	 */
	public function show($application_id)
	{
		$data['application'] = $this->applicationRepo->findById($application_id);

		return view('admin.applications.profile.show', $data);
	}

	/**
	 * Delete single application
     *
     * @param int $application_id
     *
     * @return \Illuminate\Http\RedirectResponse
	 */
	public function delete($application_id)
	{
		$application = $this->applicationRepo->findById($application_id);

		if (! $application->deleted_at) { // check if application is soft deleted
            // soft-delete application
            $application->delete() ?
				Notification::success('application_delete_successful') :
				Notification::error('application_delete_unsuccessful');

            return redirect('admin/application');
        }

        $application->forceDelete(); // force delete entry from applications table

        Notification::success('application_delete_successful');
		return redirect('admin/application');
	}

	/**
	 * Restore soft deleted application
     *
     * @param int $application_id
     *
     * @return \Illuminate\Http\RedirectResponse
	 */
	public function restore($application_id)
	{
		$application = $this->applicationRepo->findById($application_id);

		if ($application->deleted_at) {
            $application->restore();
        }

		return redirect('admin/application');
	}	
}