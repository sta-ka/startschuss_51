<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Eloquent\Company\Company;

class ImportCompaniesCommand extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:companies';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import companies from a file.';

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
		$this->info('You are about to import new companies to the database.');
		$filename = $this->ask('Give the name of the file.');

		if (empty($filename) || ! file_exists('resources/data/companies/'.$filename)) 		{
			$this->error('Operation cancelled - invalid file given.');
			return;
		}

		$companies = file('resources/data/companies/'.$filename);

		if ($this->confirm('Do you want to import ' . count($companies) . ' new companies? [yes|no]') != 'yes') {
            $this->error('Operation aborted.');
            return;
        }

        foreach ($companies as $company) {
            list($name, $full_name, $slug) = explode(';', trim($company));

            $data = [
                'user_id'   => 0,
                'name'      => $name,
                'full_name' => $full_name,
                'slug'      => $slug
            ];

            Company::create($data);
        }

        $this->info(count($companies) . ' companies successfully imported.');
	}
}