<?php namespace App\Services\Mailers;

abstract class Mailer {


    /**
     * Email address from admin user
     * @var string
     */
    protected $info_mail = 'info@startschuss-karriere.de';

    /**
     * Domain
     * @var string
     */
    protected $domain = 'startschuss-karriere.de';

    protected $to;
	protected $email;
	protected $subject;
	protected $view;
	protected $data = [];

    /**
     * Construct "to" and "email" property if user is given
     *
     * @param object|null $user
     */
    public function __construct($user = null)
	{
		if ($user) {
			$this->to    = $user->username;
			$this->email = $user->email;
		}
	}

    /**
     * Send mail and give feedback
     *
     * @param bool $feedback
     *
     * @return bool
     */
    public function deliver($feedback = true)
	{
		try
		{
			$success = \Mail::send($this->view, $this->data, function($message) {
				$message->to($this->email, $this->to)
					    ->subject($this->subject);
			});

			if ($feedback) { // by default the deliver method sends a notification
				$success ?
					\Notification::success('mail_sent_successful') :
					\Notification::error('mail_sent_unsuccessful');
			}

			return $success;
		}
		catch (\Exception $message)
		{
			\Notification::error('mail_sent_unsuccessful');

			return false;
		}

	}
}