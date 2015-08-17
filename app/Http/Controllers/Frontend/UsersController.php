<?php namespace App\Http\Controllers\Frontend;

use Illuminate\Routing\Controller;

use App\Eloquent\User\UserRepositoryInterface as Users;
use App\Services\Creator\UserCreator;

use App\Services\Mailers\UserMailer;

use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\RegisterRequest;
use App\Http\Requests\User\ForgottenPasswordRequest;
use App\Http\Requests\User\NewPasswordRequest;
use App\Http\Requests\User\ContactRequest;

use Event;
use Input;
use Notification;
use Sentry;

/**
 * Class UsersController
 *
 * @package App\Http\Controllers\Frontend
 */
class UsersController extends Controller {

    /**
     * @var Users
     */
    private $user;

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
	 * Display Login page
     *
     * @return \Illuminate\View\View
	 */
	public function login()
	{
		return view('start.misc.login');
	}

	/**
	 * Process Login
     *
     * @param LoginRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
	 */
	public function processLogin(LoginRequest $request)
	{
		try {
			$credentials = [
					'username' => Input::get('username'),
					'password' => Input::get('password')
			];

			$user = Sentry::findUserByLogin($credentials['username']);
			
			Sentry::authenticate($credentials, false);

			Event::fire('log.login', [Input::get('username'), true, null, $user->id]);
			
			Notification::success('login_successful');

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
		} catch (\Cartalyst\Sentry\Users\WrongPasswordException $e) {
			Event::fire('log.login', [Input::get('username'), false, 'Falsches Passwort']);
			Notification::error('login_unsuccessful');
		} catch (\Cartalyst\Sentry\Users\UserNotFoundException $e) {
			Event::fire('log.login', [Input::get('username'), false, 'Nutzer nicht gefunden']);
			Notification::error('login_unsuccessful');
		} catch (\Cartalyst\Sentry\Throttling\UserSuspendedException $e) {
			Event::fire('log.login', [Input::get('username'), false, 'Nutzer temporär gespeert']);
			Notification::error('account_suspended');
		} catch (\Cartalyst\Sentry\Throttling\UserBannedException $e) {
			Event::fire('log.login', [Input::get('username'), false, 'Nutzer gespeert']);
			Notification::error('user_banned');
		} catch (\Cartalyst\Sentry\Users\UserNotActivatedException $e) {
			Event::fire('log.login', [Input::get('username'), false, 'Nutzer nicht aktiviert']);
			Notification::error('account_not_activated');
		} catch (\Exception $e) {
			Event::fire('log.login', [Input::get('username'), false]);
			Notification::error('login_unsuccessful');
		}

		return back()->withInput(Input::only('username'));
	}

	/**
	 * Display Register page
     *
     * @return mixed
	 */
	public function register() 
	{
//		Notification::error('registration_disabled');
//
//		return redirect('home');

		return view('start.misc.register');
	}

	/**
	 * Process Registration
     *
     * @param RegisterRequest $request
     *
     * @return mixed
	 */
	public function processRegistration(RegisterRequest $request) 
	{
//		Notification::error('registration_disabled');
//
//		return redirect('home');

		$user = new UserCreator($this->userRepo);
        $success = $user->register(Input::all());

        if ($success == false) {
            Notification::error('registration_unsuccessful');
            return redirect('home');
        }
        
        Event::fire('log.event', ['Neue Registrierung: ' . Input::get('username')]);
        Notification::success('registration_successful');

        return redirect('home');
	}


	/**
	 * Activate account if correct key is given
     *
     * @param string $key
     *
     * @return \Illuminate\Http\RedirectResponse
	 */
	public function activateAccount($key)
	{
		try {
			$key = base64_decode($key);
			$key = explode('/', $key); // key[0] = id, $key[1] = activation-key

			// Find the user using ID
			$user = Sentry::findUserById($key[0]);

			// Attempt to activate the user
			if ($user->attemptActivation($key[1]) == false) {
                Notification::error('activate_unsuccessful');
                return redirect('home');
            }

            Event::fire('log.event', [$user->username .': Kontoaktivierung.']);
            Notification::success('activate_successful');

            return redirect('home');
		} catch (\Cartalyst\Sentry\Users\UserNotFoundException $e) {
			Event::fire('log.event', ['Kontoaktivierung: Nutzer nicht gefunden.']);
			Notification::error('activate_unsuccessful');
		} catch (\Cartalyst\Sentry\Users\UserAlreadyActivatedException $e) {
			Event::fire('log.event', ['Kontoaktivierung: Nutzer bereits aktiv.']);
			Notification::error('user_already_activated');
		}

		return redirect('home');
	}

	/**
	 * Display 'Forgot password' form
     *
     * @return \Illuminate\View\View
	 */
	public function forgotPassword()
	{
		return view('start.misc.forgot_password');
	}

	/**
	 * Process 'Forgot password' form
     *
     * @param ForgottenPasswordRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
	 */
	public function processForgottenPassword(ForgottenPasswordRequest $request)
	{

        $credentials = [
            'username' 	=> Input::get('username'),
            'email'		=> Input::get('email')
        ];

        // Find the user using the users username and email address
        try {

            $user = Sentry::findUserByCredentials($credentials);

            $data['reset_code'] = base64_encode($user->id .'/'. $user->getResetPasswordCode());

            $mailer = new UserMailer($user);
            $success = $mailer->resetPasswordMail($data)->deliver(false);

            if ($success == false) {
                Notification::error('new_password_mail_not_sent');
                return redirect('home');
            }

            $message = 'Mail für neues Passwort versandt ('. $user->username . '/' . $user->email .').';
            Event::fire('log.event', [$message]);

            Notification::success('new_password_mail_sent');
            return redirect('home');
        } catch (\Cartalyst\Sentry\Users\UserNotFoundException $e) {
            $message = 'Passwort zurücksetzen fehlgeschlagen (' .$credentials['username'] . '/' . $credentials['email'] .').';
            Event::fire('log.event', [$message]);

            Notification::error('new_password_mail_not_sent');

            return back()->withInput();
        }
    }

	/**
	 * Display 'Reset password' form
     *
     * @param string $key
     *
     * @return \Illuminate\View\View
	 */
	public function newPassword($key)
	{
		try {
			$key = base64_decode($key);
			$key = explode('/', $key); // key[0] = id, $key[1] = activationkey

			// Find the user using ID
			$user = Sentry::findUserById($key[0]);

			// Check if the reset password code is valid
			if ($user->checkResetPasswordCode($key[1]) == false) {
                Notification::error('key_not_found');
                return redirect('home');
            }

            return view('start.misc.reset_password');
		} catch (\Cartalyst\Sentry\Users\UserNotFoundException $e) {
			Event::fire('log.event', ['Versuchte Vergabe eines neuen Passworts. Nutzer nicht gefunden.']);
			Notification::error('key_not_found');

			return redirect('home');
		}
		
	}

	/**
	 * Process reset password form
     *
     * @param NewPasswordRequest $request
     * @param string $key
     *
     * @return \Illuminate\Http\RedirectResponse
	 */
	public function processNewPassword(NewPasswordRequest $request, $key)
	{
		try {
			$key = base64_decode($key);
			$key = explode('/', $key); // key[0] = id, $key[1] = activationkey

			// Find the user using ID
			$user = Sentry::findUserById($key[0]);

			// Check if the reset password code is valid and attempt to reset the user password
			if ($user->checkResetPasswordCode($key[1]) == false || $user->attemptResetPassword($key[1], Input::get('password')) == false) {
                Notification::error('reset_password_unsuccessful');
                return redirect('home');
            }

            Event::fire('log.event', [$user->username .': Neues Passwort vergeben.']);

            Notification::success('reset_password_successful');
            return redirect('home');

        } catch (\Cartalyst\Sentry\Users\UserNotFoundException $e) {
			Event::fire('log.event', ['Versuchte Vergabe eines neuen Passworts. Nutzer nicht gefunden.']);

			Notification::error('reset_password_unsuccessful');
			return redirect('home');
		}
	}

	/**
	 * Log user out
     *
     * @return \Illuminate\Http\RedirectResponse
	 */
	public function logout()
	{
		Sentry::logout();

		Notification::success('logout_successful');
		return redirect('home');
	}

	/**
	 * Display 'Contact' form
     *
     * @return \Illuminate\View\View
	 */
	public function contact()
	{
		return view('start.misc.contact');
	}

	/**
	 * Process 'Contact' form and send mail
     *
     * @param ContactRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
	 */
	public function sendContactMail(ContactRequest $request)
	{
		$mailer = new UserMailer;
		$mailer->contact()->deliver();

		return redirect('home');
	}

}	
