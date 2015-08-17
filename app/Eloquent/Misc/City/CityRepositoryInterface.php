<?php namespace App\Eloquent\Misc\City;

interface CityRepositoryInterface {

	/**
	 * Get all cities
	 */
	public function getAll();

	/**
	 * Return count of city by slug
     *
     * @param string $slug
     *
     * @return int
	 */
	public function exists($slug);

	/**
	 * Get city by slug
     *
     * @param string $slug
     *
     * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function findBySlug($slug);

	/**
	 * Get city by ID
     *
     * @param int $id
     *
     * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function findById($id);}