<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Eloquent\User\User;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\ConsoleOutput;


class UserShowCommand extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:show';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Shows all user based on search criteria.';

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
        $groups = [
            '1' => 'admins',
            '2' => 'organizers',
            '3' => 'companies',
            '4' => 'applicants',
        ];

        $this->info('Show users based on group:');

        echo "\n";

        $group_name = $this->choice('Choose the group id:', $groups);

		$users = User::join('users_groups', 'users.id', '=', 'users_groups.user_id')
					->where('users_groups.group_id', array_search($group_name, $groups))
					->get(['id', 'username', 'email', 'activated', 'last_login', 'created_at']);

        echo "\n";

		if (count($users) == 0) {
            $this->error('No users found.');
            return;
        }

        $this->info("All registered {$group_name}:");

        $table = new Table(new ConsoleOutput);

        $table->setHeaders(['id', 'name', 'e-mail', 'activated', 'last_login', 'created_at'])
            ->setRows($users->toArray())
            ->render($this->getOutput());
	}
}
