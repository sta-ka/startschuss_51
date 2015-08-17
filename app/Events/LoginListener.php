<?php namespace App\Events;

use App\Eloquent\User\Login\Login;

class LoginListener {

    /**
     * Write entry about login success in login table
     *
     * @param string      $username
     * @param bool        $success
     * @param string|bool $comment
     * @param int|bool    $user_id
     */
    public function handle($username, $success, $comment = false, $user_id = false)
    {
        $data = [
            'username'   => $username,
            'user_id'    => $user_id ? $user_id : null,
            'ip_address' => $_SERVER['REMOTE_ADDR'],
            'success'    => $success,
            'comment'    => $comment ? $comment : null
        ];

        Login::create($data);
    }

}