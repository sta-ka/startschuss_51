<?php namespace App\Eloquent\Applicant\Experience;

use App\Eloquent\Applicant\Applicant;

class DbExperienceRepository implements ExperienceRepositoryInterface {

	/**
	 * Create new experience
     *
     * @param array $data
     *
     * @return static
	 */
	public function create(array $data)
	{
		return Experience::create($data);
	}

	/**
	 * Get experience by ID
     *
     * @param int $experience_id
     *
     * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function findById($experience_id)
	{
		$user = \Sentry::getUser();
		$applicant = Applicant::where('user_id', $user->id)->firstOrFail();

		return Experience::where('applicant_id', $applicant->id)
						->where('id', $experience_id)
						->firstOrFail();
	}
	
	/**
	 * Get all experience by user ID
     *
     * @param int $applicant_id
     *
     * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function findAllById($applicant_id)
	{
		return Experience::where('applicant_id', $applicant_id)
					->orderBy('end_date', 'desc')
					->get();
	}

}
		