<?php namespace App\Console\Commands;

use Illuminate\Console\Command;

class ImportEventsCommand extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:events';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import events from a file.';

	/**
	 * Create a new command instance.
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 */
	public function handle()
	{
		$this->info('You are about to import new events to the database.');
		$filename = $this->ask('Give the name of the file.');

		if (empty($filename) || ! file_exists('resources/data/events/'.$filename)) {
			$this->error('Operation cancelled - invalid file given.');
			return;
		}

		$events = file('resources/data/events/'.$filename);

        if ($this->confirm('Do you want to import ' . count($events) . ' new events? [yes|no]') != 'yes') {
            $this->error('Operation aborted.');
            return;
        }

        foreach($events as $event) {
            list($name, $slug, $location, $start_date, $end_date, $visible, $organizer_id, $region_id) = explode(';', trim($event));

            $data = [
                'user_id'		=> 0,
                'name'			=> $name,
                'location'		=> $location,
                'slug'			=> $slug,
                'start_date'	=> \Date::germanToSql($start_date),
                'end_date'		=> \Date::germanToSql($end_date),
                'visible'		=> $visible,
                'organizer_id'	=> $organizer_id,
                'region_id'		=> $region_id,
                'created_at'	=> new \DateTime,
                'updated_at'	=> new \DateTime
            ];

            \DB::table('events')->insert($data);
        }

        $this->info(count($events) . ' events successfully imported.');
	}

}
