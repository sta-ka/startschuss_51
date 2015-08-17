<?php namespace App\Services\Notification;

class NotificationService {

    /**
     * @var array
     */
    protected $wrapper = [
        'start' => '<div id="messages">',
        'end'   => '</div>'
    ];

    /**
     * @var array
     */
    protected $message_delimiter = [
        'start' => '<p>',
        'end'   => '</p>'
    ];

	/**
	 * Helper Method for the set method
     *
     * @param $message
	 */
	public function success($message)
	{
		$this->set($message, 'success');
	}

    /**
     * Helper Method for the set method
     *
     * @param $message
     */
    public function error($message)
	{
		$this->set($message, 'danger');
	}


    /**
     * Set a message with a specific type
     *
     * @param $message
     * @param $type
     */
    public function set($message, $type)
	{
		$message = [
			'type' 		=> $type,
			'message' 	=> $message
		];

		\Session::flash('message', $message);
    }


    /**
     * Display methods
     *
     * @return string
     */
    public function display()
	{
		$message = \Session::get('message');

		if (count($message) == 0 || $message['type'] == '')         {
            return false;
        }

        $output  = $this->wrapper['start']."\r\n";

        $output .= '<div class="alert-' . $message['type'] . '">'."\r\n";

        $text    = \Lang::get('notification.'.$message['message']);
        $output .= $this->message_delimiter['start'] . $text . $this->message_delimiter['end'] . "\r\n";

        $output .= '</div>'."\r\n";

        $output .= $this->wrapper['end']."\r\n";

        return $output;
	}

}