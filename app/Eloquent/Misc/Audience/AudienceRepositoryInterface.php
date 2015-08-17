<?php namespace App\Eloquent\Misc\Audience;

interface AudienceRepositoryInterface {

    /**
     * Get an array of all audiences with the specified columns and keys
     *
     * @param string $column
     * @param string $key
     *
     * @return array
     */
	public function lists($column, $key);

}