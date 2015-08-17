<?php namespace App\Http\Middleware\Organizer;

use Closure;
use App\Eloquent\Event\EventRepositoryInterface as Events;

class EventsRequestMiddleware {

    /**
     * @var Events
     */
    private $eventRepo;

    /**
     * Constructor: inject dependencies
     *
     * @param Events $eventRepo
     */
    public function __construct(Events $eventRepo)
    {
        $this->eventRepo = $eventRepo;
    }

    /**
     * Check if organizer user already has two or more open requested events
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
		$user = \Sentry::getUser();
		
		if ($this->eventRepo->getRequestedEvents($user->id)->count() >= 2) {
			\Notification::error('missing_rights');
			return \Redirect::to('organizer/profile');
		}

        return $next($request);
	}
}
