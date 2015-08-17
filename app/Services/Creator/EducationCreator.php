<?php namespace App\Services\Creator;

use App\Eloquent\Applicant\ApplicantRepositoryInterface;
use App\Eloquent\Applicant\Education\EducationRepositoryInterface;

class EducationCreator {

	private $applicantRepo;

	private $educationRepo;

    /**
     * @param ApplicantRepositoryInterface $applicantRepo
     * @param EducationRepositoryInterface $educationRepo
     */
    public function __construct(ApplicantRepositoryInterface $applicantRepo, EducationRepositoryInterface $educationRepo)
	{
		$this->applicantRepo = $applicantRepo;
		$this->educationRepo = $educationRepo;
	}

    /**
     * Perform create
     *
     * @param array $input
     *
     * @return static
     */
    public function addEducation($input)
	{
		$user = \Sentry::getUser();
		$applicant = $this->applicantRepo->findByUserId($user->id);

		$data = [
			'applicant_id'		=> $applicant->id,
			'university'		=> $input['university'],
			'branch_of_study'	=> $input['branch_of_study'],
			'key_aspects'		=> $input['key_aspects'],
			'to_date'			=> \Input::get('to_date') ? $input['to_date'] : 0,
			'month_start'		=> $input['month_start'],
			'year_start'		=> $input['year_start'],
			'month_end'			=> \Input::get('to_date') ? 0 : $input['month_end'],
			'year_end'			=> \Input::get('to_date') ? 0 : $input['year_end'],
			'start_date'		=> \Date::germanToSql('01.' . $input['month_start'] .'.'. $input['year_start']),
			'end_date'			=> \Input::get('to_date') ? '2030-01-01' : \Date::germanToSql('01.'. $input['month_end'] .'.'. $input['year_end'])
			];

		return $this->educationRepo->create($data);
	}

    /**
     * Perform update
     *
     * @param array $input
     * @param int   $education_id
     *
     * @return bool|int
     */
    public function editEducation($input, $education_id)
	{
		$education = $this->educationRepo->findById($education_id);

		$data = [
			'university'		=> $input['university'],
			'branch_of_study'	=> $input['branch_of_study'],
			'key_aspects'		=> $input['key_aspects'],
			'to_date'			=> \Input::get('to_date') ? $input['to_date'] : 0,
			'month_start'		=> $input['month_start'],
			'year_start'		=> $input['year_start'],
			'month_end'			=> \Input::get('to_date') ? 0 : $input['month_end'],
			'year_end'			=> \Input::get('to_date') ? 0 : $input['year_end'],
			'start_date'		=> \Date::germanToSql('01.' . $input['month_start'] .'.'. $input['year_start']),
			'end_date'			=> \Input::get('to_date') ? '2030-01-01' : \Date::germanToSql('01.'. $input['month_end'] .'.'. $input['year_end'])
			];
		
		return $education->update($data);
	}

}
