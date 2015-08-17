<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Eloquent\User\User;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\ConsoleOutput;

class UserSuspendCommand extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:suspend';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Suspend or unsuspend user.';

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
        $this->info('Suspend user by user id.');

        echo "\n";

        $user_id = $this->ask('User ID?');

		$user = User::join('users_groups', 'users.id', '=', 'users_groups.user_id')
					->where('users.id', $user_id)
					->get(['id', 'username', 'email', 'activated', 'last_login', 'created_at']);

		if (count($user) == 0) {
            $this->error('No user found.');
            return;
        }

        $this->info("Found a user:");

        $table = new Table(new ConsoleOutput);

        $table->setHeaders(['id', 'name', 'e-mail', 'activated', 'last_login', 'created_at'])
            ->setRows($user->toArray())
            ->render($this->getOutput());

        $throttle = \Sentry::findThrottlerByUserId($user_id);

        if ($throttle->isBanned()) {
            $this->info("User is already banned.");

            if ($this->confirm('Unsuspend user?')) {
                $throttle->unban();
                $this->info("User successful unsuspended.");
            }
            return;
        }

        if ($this->confirm('Suspend user?')) {
            $throttle->ban();
            $this->info("User successful suspended.");
        }
	}
}
