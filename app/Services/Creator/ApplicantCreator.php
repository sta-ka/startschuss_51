<?php namespace App\Services\Creator;

use App\Eloquent\Applicant\ApplicantRepositoryInterface;

class ApplicantCreator {

	private $applicantRepo;

	private $applicant;

    /**
     * @param ApplicantRepositoryInterface $applicantRepo
     */
    public function __construct(ApplicantRepositoryInterface $applicantRepo)
	{
		$this->applicantRepo = $applicantRepo;

        $this->applicant = $this->applicantRepo->findByUserId(\Sentry::getUser()->id);
    }

    /**
     * Perform update
     *
     * @param array $input
     *
     * @return bool|int
     */
    public function editBasics($input)
    {
        $data = [
            'name'		=> $input['name'],
            'birthday'	=> $input['birthday']
        ];

        return $this->applicant->update($data);
    }


    /**
     * Perform update
     *
     * @param array $input
     *
     * @return bool|int
     */
    public function editContacts($input)
	{
		$data = [
			'email'	=> $input['email'],
			'phone'	=> $input['phone']
			];

		return  $this->applicant->update($data);
	}


    /**
     * Perform update
     *
     * @return bool|int
     */
    public function uploadPhoto()
	{
		// create a filename and make it lower case
		$filename = \Str::slug($this->applicant->slug) .'_'. date('U') .'.'. \File::extension(\Input::file('photo')->getClientOriginalName());
		$filename = \Str::lower($filename);

		// resize image	and save them
		\Image::make(\Input::file('photo')->getRealPath())->resize(100, 50, function ($constraint) {
																$constraint->aspectRatio();
															})->save('uploads/photos/medium/'.$filename);
		\Image::make(\Input::file('photo')->getRealPath())->resize(50, 25, function ($constraint) {
																$constraint->aspectRatio();
															})->save('uploads/photos/small/'.$filename);


		// move uploaded file to public/uploads/photos/original
		\Input::file('photo')->move('uploads/photos/original/', $filename);

		// Save photo in the database
		return $this->applicant->update(['photo' => $filename]);
	}
}