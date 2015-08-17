<?php namespace App\Eloquent\Misc\City;

class DbCityRepository implements CityRepositoryInterface {

	/**
	 * Get all cities
     *
     * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function getAll()
	{
		return City::all();
	}

	/**
	 * Return count of city by slug
     *
     * @param string $slug
     *
     * @return int
	 */
	public function exists($slug)
	{
		return City::where('slug', $slug)
					->count();
	}

	/**
	 * Get city by slug
     *
     * @param string $slug
     *
     * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function findBySlug($slug)
	{
		return City::where('slug', $slug)
					->first();
	}

	/**
	 * Get city by ID
     *
     * @param int $id
     *
     * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function findById($id)
	{
		return City::findOrFail($id);
	}

}