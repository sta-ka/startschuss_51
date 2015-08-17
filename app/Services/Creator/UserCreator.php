<?php namespace App\Services\Creator;

use App\Eloquent\User\UserRepositoryInterface as Users;

use App\Services\Mailers\UserMailer;

class UserCreator {

	private $userRepo;

    /**
     * @param Users $userRepo
     */
    public function __construct(Users $userRepo)
	{
		$this->userRepo = $userRepo;
	}

    /**
     * Register user and send mail
     *
     * @param array $input
     *
     * @return bool
     */
    public function register($input)
	{
		$credentials = [
			'username'		=> $input['username'],
			'email'			=> $input['email'],
			'password'  	=> $input['password']
			];

		// create a new, inactive user in the user table
		$user = \Sentry::register($credentials, false);

		// create a new entry in the applicant table
		$applicant = \App::make('App\Eloquent\Applicant\ApplicantRepositoryInterface');
		$applicant->create(['user_id' => $user->id]);

		if ($user == false) {
            return false;
        }

        // create entry in the table users_groups
        $group = \Sentry::findGroupByName('applicant');
        $user->addGroup($group);
        $data['activation_code'] = base64_encode($user->id .'/'. $user->getActivationCode());

        $mailer = new UserMailer($user);
        $mailer->sendActivationCode($data)->deliver(false); // TODO: No return value

        return true;
    }

    /**
     * Perform create
     *
     * @param array $input
     *
     * @return Object $user
     */
    public function createUser($input)
	{
		$credentials = [
			'username'		=> $input['username'],
			'email'			=> $input['email'],
			'password'  	=> 'staka365'
			];

		// creates a new user in the user table
		$user = \Sentry::register($credentials, true);

		// create entry in the table users_groups
		$group = \Sentry::getGroupProvider()->findById($input['group']);
		$user->addGroup($group);

		if ($input['group'] == 4) // 4 = applicant user
		{
			$data = ['user_id' => $user->id];

			$applicant = \App::make('App\Eloquent\Applicant\ApplicantRepository');
			$applicant->create($data);
		}

		return $user;
	}

    /**
     * Perform update
     *
     * @param array $input
     * @param int   $user_id
     *
     * @return bool|int
     */
    public function edit($input, $user_id)
	{
		$user = $this->userRepo->findById($user_id);

		$data = [
			'username'	=> $input['username'],
			'email'		=> $input['email']
		];

		return $user->update($data);
	}

    /**
     * Perform update
     *
     * @param array $input
     *
     * @return bool|int
     */
    public function changePassword($input)
	{
        $user = \Sentry::getUser();

		if (\Sentry::getUser()->checkPassword($input['oldpassword']))
		{
			return $user->update(['password' => $input['newpassword']]);
		}

		return false;
	}

    /**
     * Perform update
     *
     * @param array $input
     *
     * @return bool|int
     */
    public function changeEmail($input)
	{
        $user = \Sentry::getUser();

		if ($user->checkPassword($input['password']))
		{
			return $user->update(['email' => $input['email']]);
		}

		return false;
	}
}