<?php namespace App\Services\Mailers;

class EventMailer extends Mailer {

    /**
     * Prepare new event notification mail
     *
     * @return $this
     */
	public function newEvent()
	{
        $this->to       = $this->domain;
        $this->email    = $this->info_mail;
        $this->subject  = 'Neue Messe gemeldet';
        $this->view     = 'emails.user.new_event';
		
		$this->data = [
			'contact'		=> \Input::get('contact'),
			'email'			=> \Input::get('email'),
			'name'			=> \Input::get('name'),
			'location'		=> \Input::get('location'),
			'start_date'	=> \Input::get('start_date'),
			'end_date'		=> \Input::has('end_date') ? \Input::get('end_date') : \Input::get('start_date'),
			'region'		=> \Input::get('region'),
			'organizer'		=> \Input::get('organizer')
		];

		return $this;
	}
}