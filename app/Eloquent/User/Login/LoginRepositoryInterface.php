<?php namespace App\Eloquent\User\Login;

interface LoginRepositoryInterface {

    /**
     * Get all logins
     *
     * @param      $limit
     * @param bool $successful
     *
     * @return mixed
     */
    public function getAll($limit, $successful = true);

}