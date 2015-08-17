<?php namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Eloquent\User\UserRepositoryInterface as Users;
use App\Services\Creator\UserCreator;
use App\Services\Mailers\UserMailer;

use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Requests\User\SendMailRequest;

use DateTime;
use Input;
use Notification;
use Sentry;

/**
 * Class UsersController
 *
 * @package App\Http\Controllers\Admin
 */
class UsersController extends Controller {

    /**
     * @var Users
     */
    private $userRepo;

	/**
	 * Constructor: inject dependencies
     *
     * @param Users $userRepo
	 */
	public function __construct(Users $userRepo)
	{
		$this->userRepo = $userRepo;
	}

	/**
	 * Users overview
     *
     * @return \Illuminate\View\View
	 */
	public function index()
	{
		$data['users'] = $this->userRepo->getAll();

		return view('admin.users.show', $data);
	}

	/**
	 * Show single user by ID
     *
     * @param int $user_id
     *
     * @return \Illuminate\View\View
	 */
	public function show($user_id)
	{
		$data['user'] = $this->userRepo->findById($user_id);

		$data['company'] 	 = $this->userRepo->getCompany($user_id); // get company linked to user
		$data['events'] 	 = $this->userRepo->getEvents($user_id); // get upcomingevents linked to user
		$data['past_events'] = $this->userRepo->getEvents($user_id, false); // get past events linked to user

		return view('admin.users.profile.show', $data);
	}

	/**
	 * Display 'New user' form
     *
     * @return \Illuminate\View\View
	 */
	public function newUser()
	{
		return view('admin.users.new');
	}

	/**
	 * Create new user
     *
     * @param CreateUserRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
	 */
	public function createUser(CreateUserRequest $request)
	{
		$user = new UserCreator($this->userRepo);
		$user->createUser(Input::all());

		return redirect('admin/users');
	}

	/**
	 * Display 'Edit user' form
     *
     * @param int $user_id
     *
     * @return \Illuminate\View\View
	 */
	public function edit($user_id)
	{
		$data['user'] = $this->userRepo->findById($user_id);

		return view('admin.users.profile.edit', $data);
	}

    /**
	 * Ban/unban user depending on status
     *
     * @param int $user_id
     *
     * @return \Illuminate\Http\RedirectResponse
	 */
	public function changeStatus($user_id)
	{
		$throttle = Sentry::findThrottlerByUserId($user_id);

		if ($throttle->isBanned()) {
			$throttle->unban();
			Notification::success('activate_successful');
		} else {
			$throttle->ban();
			Notification::success('deactivate_successful');
		}

		return back();
	}

    /**
     * Process editing user
     *
     * @param UpdateUserRequest $request
     * @param int $user_id

     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateUser(UpdateUserRequest $request, $user_id)
    {
        $user = new UserCreator($this->userRepo);
        $success = $user->edit(Input::all(), $user_id);

        $success ?
            Notification::success('profile_update_successful') :
            Notification::error('profile_update_unsuccessful');

        return redirect('admin/users');
    }

    /**
	 * Unsuspend user
     *
     * @param int $user_id
     *
     * @return \Illuminate\Http\RedirectResponse
	 */
	public function unsuspend($user_id)
	{
		$throttle = Sentry::findThrottlerByUserId($user_id);

		$throttle->unsuspend();

		return back();
	}

	/**
	 * Activate user
     *
     * @param int $user_id
     *
     * @return \Illuminate\Http\RedirectResponse
	 */
	public function activateUser($user_id)
	{
		$user = $this->userRepo->findById($user_id);

		$user->update([
				'activated' 		=> true,
				'activation_code' 	=> null,
				'activated_at'   	=> new DateTime
				]);

		return back();
	}

	/**
	 * Deactivate user
     *
     * @param int $user_id
     *
     * @return \Illuminate\Http\RedirectResponse
	 */
	public function deactivateUser($user_id)
	{
		$user = $this->userRepo->findById($user_id);
		$user->update(['activated' => false]);

		return back();
	}

	/**
	 * Log into user account
     *
     * @param int $user_id
     *
     * @return \Illuminate\Http\RedirectResponse
	 */
	public function forceLogin($user_id)
	{
		$user = Sentry::findUserById($user_id);

		Sentry::login($user);

		// routing depending on type of group
        if ($user->inGroup(Sentry::findGroupByName('admin'))) {
            return redirect('admin/users');
        } elseif ($user->inGroup(Sentry::findGroupByName('organizer'))) {
            return redirect('organizer/profile');
        } elseif ($user->inGroup(Sentry::findGroupByName('company'))) {
            return redirect('company/profile');
        } elseif ($user->inGroup(Sentry::findGroupByName('applicant'))) {
            return redirect('applicant/dashboard');
        } else {
            return redirect('logout');
        }
    }

	/**
	 * Send mail to user
     *
     * @param int $user_id
     *
     * @return \Illuminate\View\View
	 */
	public function composeMail($user_id)
	{
		$data['user'] = $this->userRepo->findById($user_id);

		return view('admin.users.profile.mail', $data);
	}

	/**
	 * Process sending mail
     *
     * @param SendMailRequest $request
      * @param int $user_id
     *
     * @return \Illuminate\Http\RedirectResponse
	 */
	public function sendMail(SendMailRequest $request, $user_id)
	{
        $user = $this->userRepo->findById($user_id);

        $mailer = new UserMailer($user);
        $mailer->contactUser()->deliver();

        return redirect('admin/users');
	}

	/**
	 * Send mail with activation code to user
     *
     * @param int $user_id
     *
     * @return \Illuminate\Http\RedirectResponse
	 */
	public function sendActivationCode($user_id)
	{
		$user = Sentry::findUserById($user_id);

		if ($user->activated) {
			Notification::error('user_already_activated');
			return redirect('admin/users/'.$user_id.'/show');
		}

		$activation_code = $user->getRandomString();

		$user->update(['activation_code' => $activation_code]);

		$data['activation_code'] = base64_encode($user->id .'/'. $activation_code);

		$mailer = new UserMailer($user);
		$mailer->sendActivationCode($data)->deliver();

		return redirect('admin/users');
	}

	/**
	 * Display user statistic
     *
     * @param int $user_id
     *
     * @return \Illuminate\View\View
	 */
	public function statistics($user_id)
	{
		$data['user'] = $this->userRepo->findById($user_id);
		$data['logins'] = $this->userRepo->getLogins($user_id);

		return view('admin.users.profile.statistics', $data);
	}

	/**
	 * Delete user from database
     *
     * @param int $user_id
     *
     * @return \Illuminate\Http\RedirectResponse
	 */
	public function delete($user_id)
	{
		// check if user is linked to events/a company
		if (count($this->userRepo->getCompany($user_id)) > 0 ||
		    count($this->userRepo->getEvents($user_id)) > 0 ||
		    count($this->userRepo->getEvents($user_id, false)) > 0) {

			Notification::error('user_not_deletable');
			return redirect('admin/users');
		}

		$user = $this->userRepo->findById($user_id);

		// check if user is soft deleted
		if ( ! $user->deleted_at) {
			if ($user->delete()) {
				// if user gets soft deleted, ban him as well
				Sentry::findThrottlerByUserId($user_id)->ban();

				Notification::success('user_delete_successful');
			} else {
				Notification::error('user_delete_unsuccessful');
			}
		} elseif ($user->deleted_at) {
			if ($user->applicant) {
                $user->applicant->delete();
            }

			// force delete entry from users table
			$user->forceDelete();

			Notification::success('user_delete_successful');
		}
		
		return redirect('admin/users');
	}

	/**
	 * Restore user - Restore soft deleted user
     *
     * @param int $user_id
     *
     * @return \Illuminate\Http\RedirectResponse
	 */
	public function restore($user_id)
	{
		$user = $this->userRepo->findById($user_id);

		if ($user->deleted_at) {
            $user->restore();
        }

		return redirect('admin/users/'.$user_id.'/show');
	}
		

}