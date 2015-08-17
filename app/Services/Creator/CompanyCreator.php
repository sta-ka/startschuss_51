<?php namespace App\Services\Creator;

use App\Eloquent\Company\CompanyRepositoryInterface;

class CompanyCreator {

	private $companyRepo;

    /**
     * @param CompanyRepositoryInterface $companyRepo
     */
    public function __construct(CompanyRepositoryInterface $companyRepo)
	{
		$this->companyRepo = $companyRepo;
	}

    /**
     * Perform create
     *
     * @param array $input
     *
     * @return static
     */
    public function createCompany($input)
	{
		$data = [
			'name'		=> $input['name'],
			'full_name' => $input['full_name']
		];

		return $this->companyRepo->create($data);
	}

    /**
     * Perform update
     *
     * @param array $input
     * @param int   $company_id
     *
     * @return bool|int
     */
    public function editGeneralData($input, $company_id)
	{
        $company = $this->companyRepo->findById($company_id);

		$data = [
			'name' 		=> $input['name'], 
			'full_name' => $input['full_name'],
			'featured'	=> \Input::get('featured', false),
			'premium'	=> \Input::get('premium', false)
		];

		return $company->update($data);
	}

    /**
     * Perform update
     *
     * @param array $input
     * @param int   $company_id
     *
     * @return bool|int
     */
    public function editProfile($input, $company_id)
	{
        $company = $this->companyRepo->findById($company_id);

		$data = [
			'profile'	=> \Purifier::clean($input['profile'])
		];

		return $company->update($data);
	}

    /**
     * Perform update
     *
     * @param array $input
     * @param int   $company_id
     *
     * @return bool|int
     */
    public function editContacts($input, $company_id)
	{
        $company = $this->companyRepo->findById($company_id);

		$data = [
			'website' 	=> $input['website'], 
			'facebook' 	=> $input['facebook'], 
			'twitter' 	=> $input['twitter']
		];

		return $company->update($data);
	}

    /**
     * Perform update
     *
     * @param int $company_id
     *
     * @return bool|int
     */
    public function uploadLogo($company_id)
	{
        $company = $this->companyRepo->findById($company_id);

		// create a filename and make it lower case
		$filename = \Str::slug($this->company->slug) .'_'. date('U') .'.'. \File::extension(\Input::file('logo')->getClientOriginalName());
		$filename = \Str::lower($filename);

		// resize image	and save it	
		\Image::make(\Input::file('logo')->getRealPath())->resize(100, 50, function ($constraint) {
																$constraint->aspectRatio();
															})->save('uploads/logos/medium/'.$filename);

		// move uploaded file to public/uploads/original
		\Input::file('logo')->move('uploads/logos/original/', $filename);

		// Save logo in the database
		return $company->update(['logo' => $filename]);
	}

}