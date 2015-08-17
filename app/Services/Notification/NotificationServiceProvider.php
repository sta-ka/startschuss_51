<?php namespace App\Services\Notification;

use Illuminate\Support\ServiceProvider;

class NotificationServiceProvider extends ServiceProvider {

	/**
	 * Register in IoC container
	 */
	public function register()
	{
		$this->app->bind('notification', 'App\Services\Notification\NotificationService');
	}

}