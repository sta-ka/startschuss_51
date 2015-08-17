<?php namespace App\Eloquent\Misc\Log;

interface InfoRepositoryInterface {

    /**
     * Get logins
     *
     * @param int $limit
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
	public function getAll($limit);

}