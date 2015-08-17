<?php namespace App\Eloquent\User\Login;

class DbLoginRepository implements LoginRepositoryInterface
{

    /**
     * Get all logins
     *
     * @param      $limit
     * @param bool $successful
     *
     * @return mixed
     */
    public function getAll($limit, $successful = true)
    {
        $query = Login::take($limit)
            ->orderBy('created_at', 'desc');

        if ($successful == false) {
            $query->where('success', 0);
        }

        return $query->get(['username', 'ip_address', 'success', 'comment', 'created_at']);
    }

}