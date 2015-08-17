<?php namespace App\Eloquent\Applicant;

class DbApplicantRepository implements ApplicantRepositoryInterface {

	/**
	 * Create a new applicant
     *
     * @param array $data
     *
     * @return static
	 */
	public function create(array $data)
	{
		return Applicant::create($data);
	}

	/**
	 * Get all applicants
     *
     * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function getAll()
	{
		return Applicant::withTrashed()
					->get();
	}

	/**
	 * Get applicant by ID
     *
     * @param int $applicant_id
     *
     * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function findById($applicant_id)
	{
		return Applicant::findOrFail($applicant_id);
	}

	/**
	 * Get applicant by User ID
     *
     * @param int $user_id
     *
     * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function findByUserId($user_id)
	{
		return Applicant::where('user_id', $user_id)
					->firstOrFail();
	}

}