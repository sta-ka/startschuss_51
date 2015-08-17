<?php namespace App\Services\Creator;

use App\Eloquent\Misc\City\CityRepositoryInterface;

class CityCreator {

	private $cityRepo;

    /**
     * @param CityRepositoryInterface $city
     */
    public function __construct(CityRepositoryInterface $city)
	{
		$this->cityRepo = $city;
	}

    /**
     * Perform update
     *
     * @param array $input
     * @param int   $city_id
     *
     * @return bool|int
     */
    public function updateData($input, $city_id)
	{
		$city = $this->cityRepo->findById($city_id);

		$data = [
			'name'				=> $input['name'],
			'slug'				=> $input['slug'],
			'description'		=> \Purifier::clean($input['description']),
			'meta_description'	=> $input['meta_description'],
			'keywords'			=> $input['keywords']
		];

		return $city->update($data);

	}
}