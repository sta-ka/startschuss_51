<?php namespace App\Http\Controllers\Frontend;

use Illuminate\Routing\Controller;

use App\Eloquent\Misc\Article\ArticleRepositoryInterface as Articles;
use App\Eloquent\Misc\City\CityRepositoryInterface as Cities;
use App\Eloquent\Event\EventRepositoryInterface as Events;
use App\Eloquent\Organizer\OrganizerRepositoryInterface as Organizers;
use App\Eloquent\Misc\Region\RegionRepositoryInterface as Regions;
use App\Services\Mailers\EventMailer as EventMailer;

use App\Http\Requests\Event\CommitEventRequest;

use Input;
use Notification;

/**
 * Class EventsController
 *
 * @package App\Http\Controllers\Frontend
 */
class EventsController extends Controller {

    /**
     * @var Articles
     */
    private $articleRepo;

    /**
     * @var Cities
     */
    private $cityRepo;

    /**
     * @var Events
     */
    private $eventRepo;

    /**
     * @var Organizers
     */
    private $organizerRepo;

    /**
     * @var Regions
     */
    private $regionRepo;


    /**
     * Constructor: inject dependencies
     *
     * @param Articles   $articleRepo
     * @param Cities     $cityRepo
     * @param Events     $eventRepo
     * @param Organizers $organizerRepo
     * @param Regions    $regionRepo
     */
    public function __construct(Articles $articleRepo, Cities $cityRepo, Events $eventRepo, Organizers $organizerRepo, Regions $regionRepo)
	{
		$this->articleRepo 	 = $articleRepo;
		$this->cityRepo 	 = $cityRepo;
		$this->eventRepo 	 = $eventRepo;
		$this->organizerRepo = $organizerRepo;
		$this->regionRepo 	 = $regionRepo;
	}

	/**
	 * Start page: Overview
     *
     * @return \Illuminate\View\View
	 */
	public function index()
	{
		$data['events']		= $this->eventRepo->getAllVisibleEvents(12);
		$data['organizers'] = $this->organizerRepo->getFeaturedOrganizers();
		$data['articles'] 	= $this->articleRepo->getFeatured(2);

		return view('start.overview', $data);
	}

	/**
	 * Events overview - Displays all events or search results if input is given
     *
     * @return \Illuminate\View\View
	 */
	public function messekalender()
	{
		if (Input::has('stadt')) {
			$data['events'] = $this->eventRepo->getResults(Input::get('stadt'));
		} else {
			$data['events']  = $this->eventRepo->getPaginatedEvents(10);
		}

		$data['regions'] = $this->regionRepo->getAll();

		return view('start.events', $data);
	}

	/**
	 * Display event info if it exists
     *
     * @param string $slug
     *
     * @return \Illuminate\View\View
	 */
	public function messe($slug)
	{
		if (! $this->eventRepo->exists($slug)) {
			Notification::error('event_not_found');
			return redirect()->route('messekalender');
		}

		$data['event']	   = $this->eventRepo->findBySlug($slug);
		$data['events']	   = $this->regionRepo->getEventsInRegion($data['event']->region_id, 5);
		$data['companies'] = $this->eventRepo->getParticipatingCompanies($data['event']->id);

		return view('start.event', $data);
	}

	/**
	 * 'Events in region' page - Display events based on region given
     *
     * @param string $slug
     *
     * @return \Illuminate\View\View
	 */
	public function messen($slug)
	{
		if (! $this->regionRepo->exists($slug)) {
			Notification::error('region_not_found');
			return redirect()->route('messekalender');
		}

		$data['region']  = $this->regionRepo->findBySlug($slug);
		$data['regions'] = $this->regionRepo->getAll();
		
		$data['events']  = $this->regionRepo->getEventsInRegion($data['region']->id, 10);

		return view('start.events_by_region', $data);
	}

	/**
	 * 'Events in city' page - Display events based on city given
     *
     * @param string $slug
     *
     * @return \Illuminate\View\View
	 */
	public function messenIn($slug)
	{
		if (! $this->cityRepo->exists($slug)) {
			Notification::error('city_not_found');
			return redirect()->route('messekalender');
		}

		$data['city']    = $this->cityRepo->findBySlug($slug);
		$data['regions'] = $this->regionRepo->getAll();
		$data['events']  = $this->eventRepo->getResults($slug);

		return view('start.events_by_city', $data);
	}

	/**
	 * Display form to submit new event
     *
     * @return \Illuminate\View\View
	 */
	public function neueMesse()
	{
		$data['regions'] = $this->regionRepo->lists('name', 'name');

		return view('start.misc.new_event', $data);
	}

	/**
	 * Process form to submit new event
     *
     * @param CommitEventRequest $request
     *
     * @return \Illuminate\View\View
	 */
	public function messeEintragen(CommitEventRequest $request)
	{
		// send mail to admin with event data
		$mailer = new EventMailer;
		$mailer->newEvent()->deliver();

		return redirect('home');
	}
}