<?php namespace App\Services\Mailers;

class UserMailer extends Mailer {

    /**
     * Prepare contact mail
     *
     * @return $this
     */
	public function contact()
	{
		$this->to       = $this->domain;
		$this->email    = $this->info_mail;
		$this->subject  = 'Nachricht über das Kontaktformular';
		$this->view     = 'emails.user.contact';

		$this->data = [
			'name' 		=> \Input::get('name'),
			'email' 	=> \Input::get('email'),
			'body' 		=> \Input::get('body')
		];

		return $this;
	}

    /**
     * Prepare reset password mail
     *
     * @param $data
     *
     * @return $this
     */
	public function resetPasswordMail($data)
	{
		$this->subject  = 'Passwort zurücksetzen | '. $this->domain;;
		$this->view     = 'emails.user.reset_password_mail';
		$this->data     = $data;

		return $this;
	}

    /**
     * Prepare mail to contact specific user
     *
     * @return $this
     */
	public function contactUser()
	{
		$this->subject  = 'Nachricht von ' . $this->domain;;
		$this->view     = 'emails.user.contact_user';

		$this->data = [
			'subject' 	=> \Input::get('subject'),
			'username' 	=> $this->to,
			'body'		=> \Input::get('body')
		];

		return $this;
	}

    /**
     * Prepare mail to send activationcode to user
     *
     * @param $data
     *
     * @return $this
     */
	public function sendActivationCode($data)
	{
		$this->subject  = 'Kontoaktivierung | ' . $this->domain;;
		$this->view     = 'emails.user.send_activation_code';

		$this->data = $data;

		return $this;
	}
}