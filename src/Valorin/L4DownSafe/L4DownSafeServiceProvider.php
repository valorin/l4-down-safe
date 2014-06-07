<?php namespace Valorin\L4DownSafe;

use Illuminate\Support\ServiceProvider;
use Valorin\L4DownSafe\Command\DownSafe;

class L4DownSafeServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		// register the down:safe command
		$this->app['command.down.safe'] = $this->app->share(function($app)
		{
			return new DownSafe();
		});

		$this->commands('command.down.safe');
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array();
	}

}
