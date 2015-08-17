<?php namespace App\Eloquent\Misc\Region;

class DbRegionRepository implements RegionRepositoryInterface {

	/**
	 * Get all regions
     *
     * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function getAll()
	{
		return Region::all();
	}

	/**
	 * Get an array of all regions with the specified columns and keys
     *
     * @param string $column
     * @param string $key
     *
     * @return array
	 */
	public function lists($column, $key)
	{
		return Region::lists($column, $key);
	}

	/**
	 * Return count of region by slug
     *
     * @param string $slug
     *
     * @return int
	 */
	public function exists($slug)
	{
		return Region::where('slug', $slug)
					->count();
	}

	/**
	 * Get region by ID
     *
     * @param int $region_id
     *
     * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function findById($region_id)
	{
		return Region::findOrFail($region_id);
	}

	/**
	 * Get region by Slug
     *
     * @param string $slug
     *
     * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function findBySlug($slug)
	{
		return Region::where('slug', $slug)
					->firstOrFail();
	}

	/**
	 * Get all visible and upcoming events for a specific region
     *
     * @param int $region_id
     * @param int $limit
     *
     * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function getEventsInRegion($region_id, $limit)
	{
		return Region::findOrFail($region_id)
					->events()
					->with('organizer', 'region')
					->visible()
					->upcoming()
					->orderBy('start_date')
					->simplePaginate($limit);
	}

}