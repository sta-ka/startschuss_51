<?php namespace App\Services\Creator;

use App\Eloquent\Organizer\OrganizerRepositoryInterface;

class OrganizerCreator {

	private $organizerRepo;

    /**
     * @param OrganizerRepositoryInterface $organizerRepo
     */
    public function __construct(OrganizerRepositoryInterface $organizerRepo)
	{
		$this->organizerRepo = $organizerRepo;
	}

    /**
     * Perform create
     *
     * @param array $input
     *
     * @return static
     */
    public function createOrganizer($input)
	{
		$data = [
			'name'		=> $input['name'],
			'slug'		=> $input['slug']
		];

		return $this->organizerRepo->create($data);
	}

    /**
     * Perform update
     *
     * @param array $input
     * @param int   $organizer_id
     *
     * @return bool|int
     */
    public function editGeneralData($input, $organizer_id)
	{
		$organizer = $this->organizerRepo->findById($organizer_id);

		$data = [
			'name'		=> $input['name'],
			'featured'	=> \Input::get('featured', false),
			'premium'	=> \Input::get('premium', false)
		];

		return $organizer->update($data);
	}

    /**
     * Perform update
     *
     * @param array $input
     * @param int   $organizer_id
     *
     * @return bool|int
     */
    public function editProfile($input, $organizer_id)
	{
		$organizer = $this->organizerRepo->findById($organizer_id);

		$data = [
			'address1'	=> $input['address1'],
			'address2'	=> $input['address2'],
			'address3'	=> $input['address3'],
			'profile'	=> \Purifier::clean($input['profile'])
		];

		return $organizer->update($data);	
	}

    /**
     * Perform update
     *
     * @param array $input
     * @param int   $organizer_id
     *
     * @return bool|int
     */
    public function editContacts($input, $organizer_id)
	{
		$organizer = $this->organizerRepo->findById($organizer_id);

		$data = [
			'website' 	=> $input['website'], 
			'facebook' 	=> $input['facebook'], 
			'twitter' 	=> $input['twitter']
		];
		
		return $organizer->update($data);	
	}

    /**
     * Perform update
     *
     * @param array $input
     * @param int   $organizer_id
     *
     * @return bool|int
     */
    public function editSeoData($input, $organizer_id)
	{
		$organizer = $this->organizerRepo->findById($organizer_id);

		$data = [
			'slug' 				=> $input['slug'], 
			'meta_description' 	=> $input['meta_description'], 
			'keywords' 			=> $input['keywords']
		];
		
		return $organizer->update($data);
	}

    /**
     * Perform update
     *
     * @param int   $organizer_id
     *
     * @return bool|int
     */
    public function uploadLogo($organizer_id)
	{
		$organizer = $this->organizerRepo->findById($organizer_id);

		// create a filename and make it lower case
		$filename = \Str::slug($organizer->slug) .'_'. date('U') .'.'. \File::extension(\Input::file('logo')->getClientOriginalName());
		$filename = \Str::lower($filename);

		// resize image	and save them
		\Image::make(\Input::file('logo')->getRealPath())->resize(250, 125, function ($constraint) {
																$constraint->aspectRatio();
															})->save('uploads/logos/big/'.$filename);
		\Image::make(\Input::file('logo')->getRealPath())->resize(100, 50, function ($constraint) {
																$constraint->aspectRatio();
															})->save('uploads/logos/medium/'.$filename);
		\Image::make(\Input::file('logo')->getRealPath())->resize(50, 25, function ($constraint) {
																$constraint->aspectRatio();
															})->save('uploads/logos/small/'.$filename);
				
		// move uploaded file to public/uploads/original
		\Input::file('logo')->move('uploads/logos/original/', $filename);

		// Save logo in the database
		return $organizer->update(['logo' => $filename]);
	}

}