<?php namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;

use App\Eloquent\Applicant\ApplicantRepositoryInterface as Applicants;
use App\Eloquent\Applicant\Education\EducationRepositoryInterface as Education;
use App\Eloquent\Applicant\Experience\ExperienceRepositoryInterface as Experience;

use App\Services\Creator\ApplicantCreator;
use App\Services\Creator\EducationCreator;
use App\Services\Creator\ExperienceCreator;

use App\Http\Requests\Applicant\UpdateBasicsRequest;
use App\Http\Requests\Applicant\UpdateContactsRequest;
use App\Http\Requests\Applicant\UploadPhotoRequest;
use App\Http\Requests\Experience\AddExperienceRequest;
use App\Http\Requests\Experience\UpdateExperienceRequest;
use App\Http\Requests\Education\AddEducationRequest;
use App\Http\Requests\Education\UpdateEducationRequest;

use Input;
use Notification;

/**
 * Class ProfileController
 *
 * @package App\Http\Controllers\Applicant
 */
class ProfileController extends Controller {

    /**
     * @var Applicants
     */
    private $applicantRepo;

    /**
     * @var Education
     */
    private $educationRepo;

    /**
     * @var Experience
     */
    private $experienceRepo;

    /**
     * @var \Illuminate\Database\Eloquent\Collection
     */
    private $applicant;

    /**
 	 * Constructor: inject dependencies and apply filters
     *
     * @param Applicants $applicantRepo
     * @param Education  $educationRepo
     * @param Experience $experienceRepo
     */
    public function __construct(Applicants $applicantRepo, Education $educationRepo, Experience $experienceRepo)
	{
		$this->applicantRepo = $applicantRepo;
		$this->educationRepo = $educationRepo;
		$this->experienceRepo = $experienceRepo;

		$user = \Sentry::getUser();

		if ($user) {
            $this->applicant = $this->applicantRepo->findByUserId($user->id);
        }
	}

	/**
	 * Redirect to show method
     *
     * @return \Illuminate\View\View
	 */
	public function index()
	{
		return redirect('applicant/profile/show');
	}	

	/**
	 * Show applicant profile
     *
     * @return \Illuminate\View\View
	 */
	public function show()
	{
		$data['applicant'] = $this->applicantRepo->findById($this->applicant->id);

		return view('applicant.profile.show', $data);
	}

	/**
	 * Download applicant profile as PDF
     *
     * \Illuminate\Http\Response
     */
	public function download()
	{
		$data['applicant'] = $this->applicantRepo->findById($this->applicant->id);

		$pdf = \PDF::loadView('applicant.profile.pdf', $data);
		
		return $pdf->stream();
	}

	/**
	 * Display 'Edit basic data' form
     *
     * @return \Illuminate\View\View
	 */
	public function editBasics()
	{
		$data['applicant'] = $this->applicantRepo->findById($this->applicant->id);

		return view('applicant.profile.edit_basics', $data);
	}

	/**
	 * Process editing basic data
     *
     * @param UpdateBasicsRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
	 */
	public function updateBasics(UpdateBasicsRequest $request)
	{
		$applicant = new ApplicantCreator($this->applicantRepo);
		$applicant->editBasics(Input::all());

		return redirect('applicant/profile/show');
	}

	/**
	 * Show education overview
     *
     * @return \Illuminate\View\View
	 */
	public function showEducation()
	{
		$data['educations'] = $this->educationRepo->findAllById($this->applicant->id);

		return view('applicant.profile.education.show', $data);
	}

	/**
	 * Add a new education
     *
     * @return \Illuminate\View\View
	 */
	public function addEducation()
	{
		return view('applicant.profile.education.add');
	}

	/**
	 * Adding a new education
     *
     * @param AddEducationRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
	 */
	public function createEducation(AddEducationRequest $request)
	{
		$applicant = new EducationCreator($this->applicantRepo, $this->educationRepo);
		$success = $applicant->addEducation(Input::all());

		$success ?
			Notification::success('profile_update_successful') :
			Notification::error('profile_update_unsuccessful');	

		return redirect('applicant/profile/show');
	}

	/**
	 * Edit a education
     *
     * @param int $education_id
     *
     * @return \Illuminate\View\View
	 */
	public function editEducation($education_id)
	{
		$data['education'] = $this->educationRepo->findById($education_id);

		return view('applicant.profile.education.edit', $data);
	}

	/**
	 * Editing a education
     *
     * @param UpdateEducationRequest $request
     * @param int $education_id
     *
     * @return \Illuminate\Http\RedirectResponse
	 */
	public function updateEducation(UpdateEducationRequest $request, $education_id)
	{
		$education = new EducationCreator($this->applicantRepo, $this->educationRepo);
		$success = $education->editEducation(Input::all(), $education_id);

		$success ?
			Notification::success('profile_update_successful') :
			Notification::error('profile_update_unsuccessful');	

		return redirect('applicant/profile/show');
	}

	/**
	 * Delete a education
     *
     * @param int $education_id
     *
     * @return \Illuminate\Http\RedirectResponse
	 */
	public function deleteEducation($education_id)
	{
		$education = $this->educationRepo->findById($education_id);
		$success = $education->delete();

		$success ?
			Notification::success('profile_update_successful') :
			Notification::error('profile_update_unsuccessful');	

		return redirect('applicant/profile/show');
	}

	/**
	 * Show experience overview
     *
     * @return \Illuminate\View\View
	 */
	public function showExperience()
	{
		$data['experiences'] = $this->experienceRepo->findAllById($this->applicant->id);

		return view('applicant.profile.experience.show', $data);
	}

	/**
	 * Add a new experience
	 */
	public function addExperience()
	{
		return view('applicant.profile.experience.add');
	}

	/**
	 * Adding a new experience
     *
     * @param AddExperienceRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
	 */
	public function createExperience(AddExperienceRequest $request)
	{
		$applicant = new ExperienceCreator($this->applicantRepo, $this->experienceRepo);
		$success = $applicant->addExperience(Input::all());

		$success ?
			Notification::success('profile_update_successful') :
			Notification::error('profile_update_unsuccessful');	

		return redirect('applicant/profile/show');
	}

	/**
	 * Edit a education
     *
     * @param int $experience_id
     *
     * @return \Illuminate\View\View
	 */
	public function editExperience($experience_id)
	{
		$data['experience'] = $this->experienceRepo->findById($experience_id);

		return view('applicant.profile.experience.edit', $data);
	}

	/**
	 * Editing a education
     *
     * @param UpdateExperienceRequest $request
     * @param int $experience_id
     *
     * @return \Illuminate\Http\RedirectResponse
	 */
	public function updateExperience(UpdateExperienceRequest $request, $experience_id)
	{
		$experience = new ExperienceCreator($this->applicantRepo, $this->experienceRepo);
		$success = $experience->editExperience(Input::all(), $experience_id);

		$success ?
			Notification::success('profile_update_successful') :
			Notification::error('profile_update_unsuccessful');	

		return redirect('applicant/profile/show');
	}

	/**
	 * Delete a experience
     *
     * @param int $experience_id
     *
     * @return \Illuminate\Http\RedirectResponse
	 */
	public function deleteExperience($experience_id)
	{
		$experience = $this->experienceRepo->findById($experience_id);
		$success = $experience->delete();

		$success ?
			Notification::success('profile_update_successful') :
			Notification::error('profile_update_unsuccessful');	

		return redirect('applicant/profile/show');
	}

	/**
	 * Display 'Edit contact' form
     *
     * @return \Illuminate\View\View
	 */
	public function editContacts()
	{
		$data['applicant'] = $this->applicantRepo->findById($this->applicant->id);
		
		return view('applicant.profile.edit_contacts', $data);
	}

	/**
	 * Process editing contacts
     *
     * @param UpdateContactsRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
	 */
	public function updateContacts(UpdateContactsRequest $request)
	{
		$applicant = new ApplicantCreator($this->applicantRepo);
		$applicant->editContacts(Input::all());

		return redirect('applicant/profile/show');
	}

	/**
	 * Upload photo
     *
     * @return \Illuminate\View\View
	 */
	public function editPhoto()
	{
		$data['applicant'] = $this->applicantRepo->findById($this->applicant->id);
		
		return view('applicant.profile.edit_photo', $data);
	}

	/**
	 * Process photo upload
     *
     * @param UploadPhotoRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
	 */
	public function updatePhoto(UploadPhotoRequest $request)
	{
		$applicant = new ApplicantCreator($this->applicantRepo);
		$applicant->uploadPhoto();

		return redirect('applicant/profile/edit-photo');
	}

	/**
	 * Delete photo
     *
     * @return \Illuminate\Http\RedirectResponse
	 */
	public function deletePhoto()
	{
		$applicant = $this->applicantRepo->findById($this->applicant->id);

        $filename = $applicant->photo;

		if (empty($filename)) {
            Notification::error('logo_deleted_unsuccessful');
            return redirect('applicant/profile/edit-photo');
        }

        // delete photo in the database
        $applicant->update(['photo' => null]);

        // delete files
        \File::delete('uploads/photos/original/'.$filename);
        \File::delete('uploads/photos/medium/'.$filename);
        \File::delete('uploads/photos/small/'.$filename);

        Notification::success('logo_deleted_successful');
        return redirect('applicant/profile/edit-photo');
	}

}