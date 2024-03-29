<?php

use Illuminate\Database\Seeder;

class EventsTableSeeder extends Seeder {

	public function run()
	{
        /**
         * Note: Convert CSV files generated by Excel to UTF-8
         */

		// import event data
		$events = file('resources/data/events/events.txt');

		foreach($events as $event)
		{
			list($name, $slug, $location, $start_date, $end_date, $visible, $organizer_id, $region_id, $profile, $meta_description) = explode(';', trim($event));

			$data = array(
				'user_id'			=> 0,
				'name'				=> $name,
				'location'			=> $location,
				'slug'				=> $slug,
				'start_date'		=> Date::germanToSql($start_date),
				'end_date'			=> Date::germanToSql($end_date),
				'visible'			=> $visible,
				'organizer_id'		=> $organizer_id,
				'region_id'			=> $region_id,
				'profile'			=> $profile,
				'meta_description'	=> $meta_description,
				'created_at'		=> new DateTime,
				'updated_at'		=> new DateTime
			);
			
			DB::table('events')->insert($data);
		}
	}
}