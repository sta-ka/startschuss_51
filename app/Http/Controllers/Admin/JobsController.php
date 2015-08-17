<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Eloquent\Company\Job\JobRepositoryInterface as Jobs;
use App\Services\Creator\JobCreator;

use App\Http\Requests\Job\UpdateDataRequest;
use App\Http\Requests\Job\UpdateSeoDataRequest;
use App\Http\Requests\Job\UpdateSettingsRequest;

use Input;
use Notification;

/**
 * Class JobsController
 *
 * @package App\Http\Controllers\Admin
 */
class JobsController extends Controller {

    /**
     * @var Jobs
     */
    private $jobRepo;

	/**
	 * Constructor: inject dependencies
     *
     * @param Jobs $jobRepo
	 */
	public function __construct(Jobs $jobRepo)
	{
		$this->jobRepo = $jobRepo;
	}

	/**
	 * Overview of all jobs
     *
     * @return \Illuminate\View\View
	 */	
	public function index()
	{
		$data['jobs'] = $this->jobRepo->getAll();

		return view('admin.jobs.show', $data);
	}

	/**
	 * Show single job by ID
     *
     * @param int $job_id
     *
     * @return \Illuminate\View\View
	 */
	public function show($job_id)
	{
		$data['job'] = $this->jobRepo->findById($job_id);

		return view('admin.jobs.profile.show', $data);
	}

	/**
	 * Approve new job
     *
     * @param int $job_id
     *
     * @return \Illuminate\Http\RedirectResponse
	 */
	public function approveJob($job_id)
	{
		$job = $this->jobRepo->findById($job_id);

		$job->update(['approved' => 1]);

		return redirect('admin/jobs/'.$job_id.'/show');
	}

	/**
	 * Cancel approval to a job
     *
     * @param int $job_id
     *
     * @return \Illuminate\Http\RedirectResponse
	 */
	public function cancelApproval($job_id)
	{
		$job = $this->jobRepo->findById($job_id);
		
		$job->update(['approved' => 0]);

		return redirect('admin/jobs/'.$job_id.'/show');
	}

	/**
	 * Change active/inactive status
     *
     * @param int $job_id
     *
     * @return \Illuminate\Http\RedirectResponse
	 */
	public function changeStatus($job_id)
	{
		$job = $this->jobRepo->findById($job_id);

		// get status of job
		$status = $job->active;
		
		$job->update(['active' => ! $status]);

		return redirect('admin/jobs/'.$job_id.'/show');
	}	

	/**
	 * Display 'Edit job data' form
     *
     * @param int $job_id
     *
     * @return \Illuminate\View\View
	 */
	public function editData($job_id)
	{
		$data['job'] = $this->jobRepo->findById($job_id);

		return view('admin.jobs.profile.edit_data', $data);
	}

	/**
	 * Process editing
     *
     * @param UpdateDataRequest $request
     * @param int $job_id
     *
     * @return \Illuminate\Http\RedirectResponse
	 */
	public function updateData(UpdateDataRequest $request, $job_id)
	{
		$job = new JobCreator($this->jobRepo);
		$job->editData(Input::all(), $job_id);

		return redirect('admin/jobs/'.$job_id.'/show');
	}

	/**
	 * Display 'Edit SEO data' form
     *
     * @param int $job_id
     *
     * @return \Illuminate\View\View
	 */
	public function editSeoData($job_id)
	{
		$data['job'] = $this->jobRepo->findById($job_id);

		return view('admin.jobs.profile.edit_seo_data', $data);
	}

	/**
	 * Process editing of SEO data
     *
     * @param UpdateSeoDataRequest $request
     * @param int $job_id
     *
     * @return \Illuminate\Http\RedirectResponse
	 */
	public function updateSeoData(UpdateSeoDataRequest $request, $job_id)
	{
		$job = new JobCreator($this->jobRepo);
		$job->editSeoData(Input::all(), $job_id);

		return redirect('admin/jobs/'.$job_id.'/show');
	}

	/**
	 * Display 'Settings' page
     *
     * @param int $job_id
     *
     * @return \Illuminate\View\View
	 */
	public function editSettings($job_id)
	{
		$data['job'] = $this->jobRepo->findById($job_id);

		return view('admin.jobs.profile.settings', $data);
	}

	/**
	 * Process editing settings
     *
     * @param UpdateSettingsRequest $request
     * @param int $job_id
     *
     * @return \Illuminate\Http\RedirectResponse
	 */
	public function updateSettings(UpdateSettingsRequest $request, $job_id)
	{
		$job = new JobCreator($this->jobRepo);
		$job->editSettings($job_id);

		return redirect('admin/jobs/'.$job_id.'/show');
	}


	/**
	 * Delete job - Soft delete is enabled
     *
     * @param int $job_id
     *
     * @return \Illuminate\Http\RedirectResponse
	 */
	public function delete($job_id)
	{
		$job = $this->jobRepo->findById($job_id);

		// check if job is soft deleted
		if ( ! $job->deleted_at) {
			$job->delete() ? // soft-delete job
				Notification::success('job_delete_successful') :
				Notification::error('job_delete_unsuccessful');

            return redirect('admin/jobs');
        }

        // force delete entry from jobs table
        $job->forceDelete();

        Notification::success('job_delete_successful');
        return redirect('admin/jobs');
	}

	/**
	 * Restore soft deleted job
     *
     * @param int $job_id
     *
     * @return \Illuminate\Http\RedirectResponse
	 */
	public function restore($job_id)
	{
		$job = $this->jobRepo->findById($job_id);

        if ($job->deleted_at) {
            $job->restore();
        }

        return redirect('admin/jobs/'.$job_id.'/show');
	}	
}