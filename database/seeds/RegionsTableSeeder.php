<?php

use Illuminate\Database\Seeder;

class RegionsTableSeeder extends Seeder 
{

	public function run()
	{
        /**
         * Note: Convert CSV files generated by Excel to UTF-8
         */

		// import city data
		$regions = file('resources/data/regions.txt');

		foreach($regions as $region)
		{
			list($name, $slug, $description, $meta_description, $keywords) = explode(';', trim($region));

			$data = array(
				'name'				=> $name,
				'slug'				=> $slug,
				'description' 		=> $description,
			 	'meta_description' 	=> $meta_description,
			 	'keywords' 			=> $keywords
			);

            \App\Eloquent\Misc\Region\Region::create($data);
		}
	}
}