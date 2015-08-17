<?php namespace App\Services\Creator;

use App\Eloquent\Applicant\Application\ApplicationRepositoryInterface;

class ApplicationCreator {

	private $applicationRepo;

    /**
     * @param ApplicationRepositoryInterface $applicationRepo
     */
    public function __construct(ApplicationRepositoryInterface $applicationRepo)
	{
		$this->applicationRepo = $applicationRepo;
	}

    /**
     * Perform update
     *
     * @param array $input
     * @param int   $event_id
     * @param int   $company_id
     *
     * @return static
     */
    public function submitApplication($input, $event_id, $company_id)
	{
		$user = \Sentry::getUser();

		$data = [
			'applicant_id'	=> $user->id,
			'event_id'		=> $event_id,
			'company_id'	=> $company_id,
			'cover_letter'	=> $input['cover_letter'],
			'comment'		=> $input['comment']
			];

		// creates a new application
		return $this->$applicationRepo->submitApplication($data, $event_id, $company_id);
	}

    /**
     * Perform update
     *
     * @param array $input
     * @param int   $application_id
     *
     * @return bool|int
     */
    public function arrangeInterview($input, $application_id)
	{
		$application = $this->$applicationRepo->findById($application_id);

		$data = ['time_of_interview' => \Date::germanToSql($input['date']) .' '. $input['hour'] .':'. $input['minute'] .':00'];

		// adds a time to the interview for the application
		return $application->update($data);
	}

}
