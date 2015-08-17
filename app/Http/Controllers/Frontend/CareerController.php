<?php namespace App\Http\Controllers\Frontend;

use Illuminate\Routing\Controller;

use App\Eloquent\Misc\Article\ArticleRepositoryInterface as Articles;
use App\Eloquent\Misc\City\CityRepositoryInterface as Cities;
use App\Eloquent\Company\CompanyRepositoryInterface as Companies;
use App\Eloquent\Company\Job\JobRepositoryInterface as Jobs;

use Input;
use Notification;

/**
 * Class CareerController
 *
 * @package App\Http\Controllers\Frontend
 */
class CareerController extends Controller {

    /**
     * @var Articles
     */
    private $articleRepo;

    /**
     * @var Cities
     */
    private $cityRepo;

    /**
     * @var Companies
     */
    private $companyRepo;

    /**
     * @var Jobs
     */
    private $jobRepo;

    /**
     * Constructor: inject dependencies
     *
     * @param Articles  $articleRepo
     * @param Cities    $cityRepo
     * @param Companies $companyRepo
     * @param Jobs      $jobRepo
     */
    public function __construct(Articles $articleRepo, Cities $cityRepo, Companies $companyRepo, Jobs $jobRepo)
	{
		$this->articleRepo 	 = $articleRepo;
		$this->cityRepo 	 = $cityRepo;
		$this->companyRepo 	 = $companyRepo;
		$this->jobRepo 		 = $jobRepo;
	}

	/**
	 * Displays all jobs, highlight premium jobs
     *
     * @return \Illuminate\View\View
	 */
	public function jobs()
	{
		if (Input::has('stadt') || Input::has('typ')) {
			$data['jobs'] = $this->jobRepo->getResults(Input::all());
		} else {
			$data['jobs'] = $this->jobRepo->getActive();
		}

		return view('start.jobs', $data);
	}


    /**
     * 'Jobs in city' page - Display jobs based on given city
     *
     * @param string $slug
     *
     * @return \Illuminate\View\View
     */
    public function jobsIn($slug)
	{
		if (! $this->cityRepo->exists($slug)) {
			Notification::error('city_not_found');
			return redirect()->route('messekalender');
		}

		$data['city'] = $this->cityRepo->findBySlug($slug);
		$data['jobs'] = $this->jobRepo->getResults(['stadt' => $slug, 'typ' => Input::get('typ')]);

		return view('start.jobs_by_city', $data);
	}

	/**
	 * Displays a single job
     *
     * @param int $job_id
     *
     * @return \Illuminate\View\View
	 */
	public function job($job_id)
	{
		if (! $this->jobRepo->exists($job_id)) {
			Notification::error('job_not_found');
			return redirect('jobs');
		}

		$data['job'] = $this->jobRepo->findById($job_id, false); // false = do not include soft-deleted jobs

		return view('start.job', $data);
	}

	/**
	 * Displays a single company
     *
     * @param string $slug
     *
     * @return \Illuminate\View\View
	 */
	public function unternehmen($slug)
	{
		if (! $this->companyRepo->exists($slug)) {
			Notification::error('company_not_found');
			return redirect('jobs');
		}

		$data['company'] = $this->companyRepo->findBySlug($slug);

		return view('start.company', $data);
	}

	/**
	 * Displays all articles, highlight featured articles
     *
     * @return \Illuminate\View\View
	 */
	public function ratgeber()
	{
		$data['featured_articles'] = $this->articleRepo->getFeatured(2); // get two featured articles
		$data['articles'] 		   = $this->articleRepo->getOthers(); // get other articles excluding the two featured articles
		
		return view('start.guide', $data);
	}

	/**
	 * Show single article
     *
     * @param string $slug
     *
     * @return \Illuminate\View\View
	 */
	public function artikel($slug)
	{
		if (! $this->articleRepo->exists($slug)) {
			Notification::error('article_not_found');
			return redirect('karriereratgeber');
		}
		
		$data['article']  = $this->articleRepo->findBySlug($slug);
		$data['articles'] = $this->articleRepo->getOthers($slug);

		return view('start.article', $data);
	}
}