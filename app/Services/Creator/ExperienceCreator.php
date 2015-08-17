<?php namespace App\Services\Creator;

use App\Eloquent\Applicant\ApplicantRepositoryInterface;
use App\Eloquent\Applicant\Experience\ExperienceRepositoryInterface;

class ExperienceCreator {

	private $applicantRepo;

	private $experienceRepo;

    /**
     * @param ApplicantRepositoryInterface  $applicantRepo
     * @param ExperienceRepositoryInterface $experienceRepo
     */
    public function __construct(ApplicantRepositoryInterface $applicantRepo, ExperienceRepositoryInterface $experienceRepo)
	{
		$this->applicantRepo = $applicantRepo;

		$this->experienceRepo = $experienceRepo;
	}

    /**
     * Perform create
     *
     * @param array $input
     *
     * @return static
     */
    public function addExperience($input)
	{
		$user = \Sentry::getUser();
		$applicant = $this->applicantRepo->findByUserId($user->id);

		$data = [
			'applicant_id'		=> $applicant->id,
			'company'			=> $input['company'],
			'industry'			=> $input['industry'],
			'job_description'	=> $input['job_description'],
			'to_date'			=> \Input::get('to_date') ? $input['to_date'] : 0,
			'month_start'		=> $input['month_start'],
			'year_start'		=> $input['year_start'],
			'month_end'			=> \Input::get('to_date') ? 0 : $input['month_end'],
			'year_end'			=> \Input::get('to_date') ? 0 : $input['year_end'],
			'start_date'		=> \Date::germanToSql('01.' . $input['month_start'] .'.'. $input['year_start']),
			'end_date'			=> \Input::get('to_date') ? '2030-01-01' : \Date::germanToSql('01.'. $input['month_end'] .'.'. $input['year_end'])
			];
		
		return $this->experienceRepo->create($data);
	}

    /**
     * Perform update
     *
     * @param array $input
     * @param int   $experience_id
     *
     * @return bool|int
     */
    public function editExperience($input, $experience_id)
	{
		$experience = $this->experienceRepo->findById($experience_id);

		$data = [
			'company'			=> $input['company'],
			'industry'			=> $input['industry'],
			'job_description'	=> $input['job_description'],
			'to_date'			=> \Input::get('to_date') ? $input['to_date'] : 0,
			'month_start'		=> $input['month_start'],
			'year_start'		=> $input['year_start'],
			'month_end'			=> \Input::get('to_date') ? 0 : $input['month_end'],
			'year_end'			=> \Input::get('to_date') ? 0 : $input['year_end'],
			'start_date'		=> \Date::germanToSql('01.' . $input['month_start'] .'.'. $input['year_start']),
			'end_date'			=> \Input::get('to_date') ? '2030-01-01' : \Date::germanToSql('01.'. $input['month_end'] .'.'. $input['year_end'])
			];
		
		return $experience->update($data);
	}

}
