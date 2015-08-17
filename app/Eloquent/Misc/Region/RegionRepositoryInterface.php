<?php namespace App\Eloquent\Misc\Region;

interface RegionRepositoryInterface {

	/**
	 * Get all regions
     *
     * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function getAll();

	/**
	 * Get an array of all regions with the specified columns and keys
     *
     * @param string $column
     * @param string $key
     *
     * @return array
	 */
	public function lists($column, $key);

	/**
	 * Return count of region by slug
     *
     * @param string $slug
     *
     * @return int
	 */
	public function exists($slug);

	/**
	 * Get region by ID
     *
     * @param int $region_id
     *
     * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function findById($region_id);

	/**
	 * Get region by Slug
     *
     * @param string $slug
     *
     * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function findBySlug($slug);

	/**
	 * Get all visible and upcoming events for a specific region
     *
     * @param int $region_id
     * @param int $limit
     *
     * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function getEventsInRegion($region_id, $limit);


}