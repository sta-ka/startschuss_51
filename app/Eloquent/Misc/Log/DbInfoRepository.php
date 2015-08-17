<?php namespace App\Eloquent\Misc\Log;

class DbInfoRepository implements InfoRepositoryInterface {

    /**
     * Create a new entry
     *
     * @param array $data
     *
     * @return static
     */
    public function create(array $data)
    {
        return Info::create($data);
    }

    /**
     * Get logins
     *
     * @param int $limit
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
	public function getAll($limit)
	{
		return Info::take($limit)
				->orderBy('created_at', 'desc')
				->get();
	}

}