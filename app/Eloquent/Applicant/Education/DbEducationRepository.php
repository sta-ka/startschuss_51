<?php namespace App\Eloquent\Applicant\Education;

use App\Eloquent\Applicant\Applicant;

class DbEducationRepository implements EducationRepositoryInterface {

	/**
	 * Create new education
     *
     * @param array $data
     *
     * @return static
	 */
	public function create($data)
	{
		return Education::create($data);
	}
	
	/**
	 * Get education by ID
     *
     * @param int $education_id
     *
     * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function findById($education_id)
	{
		$user = \Sentry::getUser();
		$applicant = Applicant::where('user_id', $user->id)->firstOrFail();
		
		return Education::where('applicant_id', $applicant->id)
						->where('id', $education_id)
						->firstOrFail();
	}
	
	/**
	 * Get all education by user ID
     *
     * @param int $applicant_id
     *
     * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function findAllById($applicant_id)
	{
		return Education::where('applicant_id', $applicant_id)
					->orderBy('end_date', 'desc')
					->get();
	}

}
		