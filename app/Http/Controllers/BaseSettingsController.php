<?php namespace App\Http\Controllers;

use App\Eloquent\User\UserRepositoryInterface as Users;
use App\Services\Creator\UserCreator;

use App\Http\Requests\User\UpdatePasswordRequest;
use App\Http\Requests\User\UpdateEmailRequest;

use Input;
use Notification;
use Sentry;

/**
 * Class BaseSettingsController
 *
 * @package App\Http\Controllers
 */
class BaseSettingsController extends Controller {

    /**
     * @var Users
     */
    private $userRepo;

    /**
     * Usertype (admin, company, applicant, organizer)
     *
     * @var $user_type
     */
    protected $user_type;

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
     * Redirect to 'Settings overview' page
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return redirect($this->user_type . '/settings/show');
    }

    /**
     * Settings overview
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        $data['user'] = $this->userRepo->findById(Sentry::getUser()->id);

        return view($this->user_type . '.settings.show', $data);
    }

    /**
     * Display 'Change password' form
     *
     * @return \Illuminate\View\View
     */
    public function changePassword()
    {
        return view($this->user_type . '.settings.change_password');
    }

    /**
     * Process changing password
     *
     * @param UpdatePasswordRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePassword(UpdatePasswordRequest $request)
    {
        $user = new UserCreator($this->userRepo);
        $success = $user->changePassword(Input::all());

        if ($success == false) {
            Notification::error('password_change_unsuccessful');

            \Event::fire('log.event', [$user->username . ' (ID: ' . $user->id . '): Passwort Änderung fehlgeschlagen.']);
            return back();
        }

        $user = \Sentry::getUser();
        \Event::fire('log.event', [$user->username . ' (ID: ' . $user->id . '): Passwort geändert.']);

        Notification::success('password_change_successful');
        return redirect($this->user_type . '/settings/show');

    }

    /**
     * Display 'Change email' form
     *
     * @return \Illuminate\View\View
     */
    public function changeEmail()
    {
        return view($this->user_type . '.settings.change_email');
    }

    /**
     * Process changing email
     *
     * @param UpdateEmailRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateEmail(UpdateEmailRequest $request)
    {
        $user = new UserCreator($this->userRepo);
        $success = $user->changeEmail(Input::all());

        if ($success == false) {
            Notification::error('email_update_unsuccessful');
            return back()->withInput(Input::only('email'));
        }

        $user = \Sentry::getUser();
        \Event::fire('log.event', [$user->username . ' (ID: ' . $user->id . '): Email Adresse geändert.']);

        Notification::success('email_update_successful');
        return redirect($this->user_type . '/settings/show');
    }
}