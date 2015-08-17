<?php namespace App\Services\Creator;

use App\Eloquent\Event\EventRepositoryInterface;

class EventCreator {

	private $eventRepo;

    /**
     * @param EventRepositoryInterface $eventRepo
     */
    public function __construct(EventRepositoryInterface $eventRepo)
	{
		$this->eventRepo = $eventRepo;
	}

    /**
     * Perform create
     *
     * @param array $input
     *
     * @return static
     */
    public function createEvent($input)
	{
		$data = [
			'name'			=> $input['name'],
			'location'		=> $input['location'],
			'start_date'	=> $input['start_date'],
			'end_date'		=> empty($input['end_date']) ? $input['start_date'] : $input['end_date'],
			'profile'		=> \Purifier::clean($input['profile']),
			'slug'			=> $input['slug'],
			'region_id'		=> $input['region_id'],
			'organizer_id'	=> $input['organizer_id']
		];

		// creates a new event in the event table
		return $this->eventRepo->create($data);
	}


    /**
     * Perform create
     *
     * @param array $input
     * @param int   $event_id
     *
     * @return static
     */
    public function requestEvent($input, $event_id)
	{
		$organizer = $this->eventRepo->findById($event_id)->organizer;

		$data = [
			'name'					=> $input['name'],
			'location'				=> $input['location'],
			'start_date'			=> $input['start_date'],
			'end_date'				=> empty($input['end_date']) ? $input['start_date'] : $input['end_date'],
			'specific_location1'	=> $input['specific_location1'],
			'specific_location2'	=> $input['specific_location2'],
			'specific_location3'	=> $input['specific_location3'],
			'profile'				=> \Purifier::clean($input['profile']),
			'slug'					=> \Str::slug($input['name']),
			'visible'				=> 0,
			'region_id'				=> $input['region_id'],
			'organizer_id'			=> $organizer->id,
			'requested_by'			=> \Sentry::getUser()->id
		];

		// creates a new event in the event table
		return $this->eventRepo->create($data);
	}

    /**
     * Perform update
     *
     * @param array $input
     * @param int   $event_id
     *
     * @return bool|int
     */
    public function editGeneralData($input, $event_id)
	{
		$event = $this->eventRepo->findById($event_id);

		$data = [
			'name'			=> $input['name'],
			'location'		=> $input['location'],
			'start_date'	=> $input['start_date'],
			'end_date'		=> empty($input['end_date']) ? $input['start_date'] : $input['end_date'],
			'interviews'	=> \Input::get('interviews', false),
			'region_id'		=> $input['region_id'],
			'organizer_id'	=> $input['organizer_id']
		];

		return $event->update($data); 	
	}

    /**
     * Perform update
     *
     * @param array $input
     * @param int   $event_id
     *
     * @return bool|int
     */
    public function editProfile($input, $event_id)
	{
		$event = $this->eventRepo->findById($event_id);

		$data = [
			'opening_hours1'		=> $input['opening_hours1'],
			'opening_hours2'		=> $input['opening_hours2'],
			'admission'				=> $input['admission'],
			'specific_location1'	=> $input['specific_location1'],
			'specific_location2'	=> $input['specific_location2'],
			'specific_location3'	=> $input['specific_location3'],
			'profile'				=> \Purifier::clean($input['profile'])
		];

		if (\Input::has('audiences'))
		{
			$data['audience'] = implode(', ', $input['audiences'] ? $input['audiences'] : []);
		}

		return $event->update($data); 
	}

    /**
     * Perform update
     *
     * @param array $input
     * @param int   $event_id
     *
     * @return bool|int
     */
    public function editProgram($input, $event_id)
	{
		$event = $this->eventRepo->findById($event_id);

		$data = [
            'program' => \Purifier::clean($input['program'])
        ];

		return $event->update($data);
	}

    /**
     * Perform update
     *
     * @param array $input
     * @param int   $event_id
     *
     * @return bool|int
     */
    public function editContacts($input, $event_id)
	{
		$event = $this->eventRepo->findById($event_id);

		$data = [
			'website' 	=> $input['website'], 
			'facebook' 	=> $input['facebook'], 
			'twitter' 	=> $input['twitter']
		];

		return $event->update($data);
	}

    /**
     * Perform update
     *
     * @param array $input
     * @param int   $event_id
     *
     * @return bool|int
     */
    public function editApplicationDeadline($input, $event_id)
	{
		$event = $this->eventRepo->findById($event_id);

		$data = [
			'application_deadline' => $input['application_deadline']
		];

		return $event->update($data);
	}

    /**
     * Perform update
     *
     * @param array $input
     * @param int   $event_id
     *
     * @return bool|int
     */
    public function editSeoData($input, $event_id)
	{
		$event = $this->eventRepo->findById($event_id);

		$data = [
			'slug'				=> $input['slug'],
			'meta_description'	=> $input['meta_description'],
			'keywords'			=> $input['keywords']
		];

		return $event->update($data); 
	}

    /**
     * Perform update
     *
     * @param int   $event_id
     *
     * @return bool|int
     */
    public function uploadLogo($event_id)
	{
		$event = $this->eventRepo->findById($event_id);

		// create a filename and make it lower case
		$filename = \Str::slug($event->slug) .'_'. date('U') .'.'. \File::extension(\Input::file('logo')->getClientOriginalName());
		$filename = \Str::lower($filename);

		// resize image and save it    
		\Image::make(\Input::file('logo')->getRealPath())->resize(60, 30, function ($constraint) {
																$constraint->aspectRatio();
															})->save('uploads/logos/small/'.$filename);

		// move uploaded file to public/uploads/original
		\Input::file('logo')->move('uploads/logos/original/', $filename);

		// Save logo in the database
		return $event->update(['logo' => $filename]);
	}

}