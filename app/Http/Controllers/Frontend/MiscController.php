<?php namespace App\Http\Controllers\Frontend;

use Illuminate\Routing\Controller;

use App\Eloquent\Misc\Article\ArticleRepositoryInterface as Articles;
use App\Eloquent\Misc\City\CityRepositoryInterface as Cities;
use App\Eloquent\Event\EventRepositoryInterface as Events;
use App\Eloquent\Organizer\OrganizerRepositoryInterface as Organizers;
use App\Eloquent\Misc\Region\RegionRepositoryInterface as Regions;

use URL;

/**
 * Class MiscController
 *
 * @package App\Http\Controllers\Frontend
 */
class MiscController extends Controller {

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
	 * Display imprint
     *
     * @return \Illuminate\View\View
	 */
	public function imprint()
	{
		return view('start.misc.imprint');
	}

	/**
	 * Create XML sitemap
	 */
	public function showXMLSitemap()
	{
		$sitemap = \App::make("sitemap");

		$event 		= $this->eventRepo->getLastModifiedEvent();
		$organizer 	= $this->organizerRepo->getLastModifiedOrganizer();
		$article 	= $this->articleRepo->getLastModifiedArticle();

		$default_timestamp = '2015-02-15';

		// Add static pages
		$sitemap->add(URL::to('home'), date('c', strtotime($event->updated_at)), '1.0', 'daily');
		$sitemap->add(URL::to('jobmessekalender'), date('c', strtotime($event->updated_at)), '0.7', 'daily');
		$sitemap->add(URL::to('veranstalterdatenbank'), date('c', strtotime($organizer->updated_at)), '0.7', 'monthly');
		$sitemap->add(URL::to('karriereratgeber'), date('c', strtotime($article->updated_at)), '0.7', 'weekly');
		$sitemap->add(URL::to('login'), date('c', strtotime($default_timestamp)), '0.2', 'monthly');
		$sitemap->add(URL::to('jobmesse-eintragen'), date('c', strtotime($default_timestamp)), '0.2', 'monthly');
		$sitemap->add(URL::to('kontakt'), date('c', strtotime($default_timestamp)), '0.2', 'monthly');

		// Add all (visibile) 'events' pages
		$events = $this->eventRepo->getAllEvents();

		foreach($events as $event)  {
			$sitemap->add(URL::to("jobmesse/".$event->slug), date('c', strtotime($event->updated_at)), '0.9', 'weekly');
		}

		// Add all 'events in region' pages
		$regions = $this->regionRepo->getAll();

		foreach($regions as $region) {
			$event = $this->eventRepo->getLastModifiedEventByRegion($region->id);

			$event_timestamp = isset($event->updated_at) ? $event->updated_at : $default_timestamp;

			$sitemap->add(URL::to("jobmessen/".$region->slug), date('c', strtotime($event_timestamp)), '0.7', 'weekly');
		}

		// Add all 'events in city' pages
		$cities = $this->cityRepo->getAll();

		foreach($cities as $city) {
			$event = $this->eventRepo->getLastModifiedEventByCity($city->name);

			$event_timestamp = isset($event->updated_at) ? $event->updated_at : $default_timestamp;

			$sitemap->add(URL::to("jobmessen/in/".$city->slug), date('c', strtotime($event_timestamp)), '0.7', 'weekly');
		}

		// Add all 'organizer' pages
		$organizers = $this->organizerRepo->getAll();

		foreach($organizers as $organizer) 
		{
			$event = $this->eventRepo->getLastModifiedEventByOrganizer($organizer->id);
			
			$event_timestamp = isset($event->updated_at) ? $event->updated_at : $default_timestamp;

			$sitemap->add(URL::to("veranstalter/".$organizer->slug), date('c', strtotime($event_timestamp)), '0.7', 'weekly');
		}

		// Now, output the sitemap
		return $sitemap->render('xml');
	}
}	
